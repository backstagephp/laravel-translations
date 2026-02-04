<?php

namespace Backstage\Translations\Laravel\Tests;

use Backstage\Translations\Laravel\TranslationServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Backstage\\Translations\\Laravel\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );

        $this->runMigrations();
    }

    protected function getPackageProviders($app)
    {
        return [
            TranslationServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $dbPath = database_path('database.sqlite');

        if (file_exists($dbPath)) {
            unlink($dbPath);
        }

        touch($dbPath);

        config()->set('database.default', 'testing');
        config()->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => $dbPath,
            'prefix' => '',
            'foreign_key_constraints' => true,
        ]);
    }

    protected function runMigrations(): void
    {
        if (Schema::hasTable('languages')) {
            $this->createTestTables();

            return;
        }

        $migrations = [
            __DIR__.'/../database/migrations/create_languages_table.php.stub',
            __DIR__.'/../database/migrations/create_translations_table.php.stub',
            __DIR__.'/../database/migrations/create_translated_attributes_table.php.stub',
            __DIR__.'/../database/migrations/create_language_rules_tables.php.stub',
        ];

        foreach ($migrations as $migration) {
            if (file_exists($migration)) {
                $migration = include $migration;
                $migration->up();
            }
        }

        $this->createTestTables();
    }

    protected function createTestTables(): void
    {
        if (! Schema::hasTable('test_translatable_models')) {
            Schema::create('test_translatable_models', function ($table) {
                $table->id();
                $table->string('title')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('test_models')) {
            Schema::create('test_models', function ($table) {
                $table->id();
                $table->timestamps();
            });
        }
    }
}
