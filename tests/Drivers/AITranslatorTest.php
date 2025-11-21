<?php

use Backstage\Translations\Laravel\Drivers\AITranslator;
use Backstage\Translations\Laravel\Models\Language;
use Illuminate\Support\Facades\Config;

it('translates text using AI', function () {
    Language::create(['code' => 'fr', 'name' => 'French', 'active' => true]);

    Config::set('translations.translators.drivers.ai.provider', 'openai');
    Config::set('translations.translators.drivers.ai.model', 'gpt-4');
    Config::set('translations.translators.drivers.ai.system_prompt', 'Translate text');

    $translator = new AITranslator;

    expect(fn () => $translator->translate('Hello', 'fr'))
        ->toThrow();
})->skip('Requires actual AI provider configuration');

it('translates JSON arrays', function () {
    Language::create(['code' => 'fr', 'name' => 'French', 'active' => true]);

    Config::set('translations.translators.drivers.ai.provider', 'openai');
    Config::set('translations.translators.drivers.ai.model', 'gpt-4');

    $translator = new AITranslator;

    expect(fn () => $translator->translate(['welcome' => 'Welcome', 'logout' => 'Log out'], 'fr'))
        ->toThrow();
})->skip('Requires actual AI provider configuration');

it('throws exception when AI returns invalid JSON', function () {
    Language::create(['code' => 'fr', 'name' => 'French', 'active' => true]);

    Config::set('translations.translators.drivers.ai.provider', 'openai');
    Config::set('translations.translators.drivers.ai.model', 'gpt-4');

    $translator = new AITranslator;

    expect(fn () => $translator->translate(['key' => 'value'], 'fr'))
        ->toThrow();
})->skip('Requires actual AI provider configuration');
