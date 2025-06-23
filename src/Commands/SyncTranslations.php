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
        $items = $this->getTranslatableItems();

        info("Found {$items->count()} translatable items to sync.");

        $this->syncItems($items);

        info('Translations synced successfully.');

        $this->cleanOrphanedTranslations();
    }

    protected function getTranslatableItems(): Collection
    {
        $models = collect(config('translations.eloquent.translatable-models', [
            //
        ]));

        return $models
            ->flatMap(fn (string $model) => $model::all())
            ->filter(fn ($item) => $item instanceof TranslatesAttributes);
    }

    protected function syncItems(Collection $items): void
    {
        $this->newLine();

        progress('Syncing translatable items', $items, fn (TranslatesAttributes $item) => $item->syncTranslations());

        $this->newLine();
    }

    protected function cleanOrphanedTranslations(): void
    {
        note('Cleaning unused translations...');

        $orphans = static::getOrphanedAttributes();

        if ($orphans->isEmpty()) {
            note('No unused translations found.');

            return;
        }

        progress('Deleting unused translations', $orphans, function (TranslatedAttribute $attr) {
            $attr->delete();
        });
    }

    protected static function getOrphanedAttributes(): Collection
    {
        return TranslatedAttribute::query()->get()->filter(function ($attr) {
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
