<?php

namespace Backstage\Translations\Laravel\Commands;

use Backstage\Translations\Laravel\Contracts\TranslatesAttributes;
use Backstage\Translations\Laravel\Models\TranslatedAttribute;
use Illuminate\Console\Command;
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
        $items = static::getTranslatableItems();

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

    protected static function getTranslatableItems(): Collection
    {
        $models = collect(config('translations.eloquent.translatable-models', []));

        return $models
            ->flatMap(fn (string $model) => $model::all())
            ->filter(fn ($item) => $item instanceof TranslatesAttributes);
    }

    protected static function syncItems(Collection $items): void
    {
        progress('Syncing translatable items', $items, fn (TranslatesAttributes $item) => $item->syncTranslations());
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
                $type = $attr->translatable_type;
                $id = $attr->translatable_id;

                if (! class_exists($type)) {
                    return true;
                }

                $query = $type::query();

                if (in_array(SoftDeletes::class, class_uses_recursive($type))) {
                    $query->withTrashed();
                }

                return ! $query->whereKey($id)->exists();
            });
    }
}
