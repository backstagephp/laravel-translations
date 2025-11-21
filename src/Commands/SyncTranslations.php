<?php

namespace Backstage\Translations\Laravel\Commands;

use Backstage\Translations\Laravel\Contracts\TranslatesAttributes;
use Backstage\Translations\Laravel\Models\TranslatedAttribute;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

use function Laravel\Prompts\info;
use function Laravel\Prompts\note;
use function Laravel\Prompts\progress;

class SyncTranslations extends Command
{
    protected $signature = 'translations:sync';

    protected $description = 'Sync translations for all translatable models';

    public function handle(): void
    {
        $items = $this->getTranslatableItems();

        info("Found {$items->count()} translatable items to sync.");

        $this->newLine();

        $this->syncItems($items);

        $this->newLine();

        info('Translations synced successfully.');

        note('Cleaning unused translations...');

        $orphans = static::getOrphanedAttributes();

        if ($orphans->isEmpty()) {
            note('No unused translations found.');

            return;
        } else {
            static::cleanOrphanedTranslations($orphans);
        }
    }

    protected function getTranslatableItems(): Collection
    {
        $models = collect(config('translations.eloquent.translatable-models', []));

        return $models
            ->flatMap(fn (string $model) => $model::all())
            ->filter(fn ($item) => $item instanceof TranslatesAttributes);
    }

    protected function syncItems(Collection $items): void
    {
        if ($items->isEmpty()) {
            info('No translatable items found to sync.');

            return;
        }

        try {
            $itemsCount = $items
                ->filter(function (TranslatesAttributes | Model $item) {
                    $translations = $item->translatableAttributes()->count();

                    if ($translations === count($item->getTranslatableAttributes())) {
                        return false;
                    }

                    return true;
                })
                ->map(fn (TranslatesAttributes | Model $item) => count($item->getTranslatableAttributes()))
                ->sum();

            $this->output->progressStart($itemsCount);

            $items->each(function (TranslatesAttributes | Model $item) {
                try {
                    $item->syncTranslations($this->output);
                } catch (\Throwable $e) {
                    info("Failed to sync translations for item: {$item->getKey()} - {$e->getMessage()}");
                }
            });
        } catch (\Throwable $e) {
            info('Payload is too large, syncing items one by one.');

            progress('Syncing translatable items', $items, function (TranslatesAttributes | Model $item) {
                try {
                    $item->syncTranslations($this->output);
                } catch (\Throwable $e) {
                    info("Failed to sync translations for item: {$item->getKey()} - {$e->getMessage()}");
                }
            });
        }
    }

    protected static function cleanOrphanedTranslations($orphans): void
    {
        progress('Deleting unused translations', $orphans, function (TranslatedAttribute $attr) {
            $attr->forceDelete();
        });
    }

    protected static function getOrphanedAttributes(): Collection
    {
        return TranslatedAttribute::query()
            ->get()
            ->filter(function (TranslatedAttribute $attr) {
                /**
                 * @var \Illuminate\Database\Eloquent\Model $type
                 */
                $type = $attr->translatable_type;

                $id = $attr->translatable_id;

                if (! class_exists($type) && Relation::getMorphedModel($type) === null) {
                    return true;
                }

                $model = $attr->translatable()->get()->first();

                $attribute = $attr->attribute;

                if (! method_exists($model, 'translatableAttributes')) {
                    return true;
                }

                if (! in_array($attribute, $model->getTranslatableAttributes())) {
                    return true;
                }

                /**
                 * @var \Illuminate\Database\Eloquent\Builder $query
                 */
                $query = get_class($model)::query();

                if (in_array(SoftDeletes::class, class_uses_recursive($model))) {
                    $query->withTrashed();
                }

                return ! $query->whereKey($id)->exists();
            });
    }
}
