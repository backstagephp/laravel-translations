<?php

namespace Backstage\Translations\Laravel\Commands;

use Backstage\Translations\Laravel\Contracts\TranslatesAttributes;
use Backstage\Translations\Laravel\Models\TranslatedAttribute;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class SyncTranslations extends Command
{
    protected $signature = 'translations:sync';

    protected $description = 'Sync translations for all translatable models';

    public function handle(): void
    {
        $items = $this->getTranslatableItems();

        $this->info("Found {$items->count()} translatable items to sync.");

        $this->syncItems($items);

        $this->info('Translations synced successfully.');

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
        $this->withProgressBar($items, fn (TranslatesAttributes $item) => $item->syncTranslations());
    }

    protected function cleanOrphanedTranslations(): void
    {
        $this->info('Cleaning unused translations...');

        $orphans = $this->getOrphanedAttributes();

        if ($orphans->isEmpty()) {
            $this->info('No unused translations found.');

            return;
        }

        $this->withProgressBar($orphans, fn (TranslatedAttribute $attr) => $attr->delete());
    }

    protected function getOrphanedAttributes(): Collection
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
