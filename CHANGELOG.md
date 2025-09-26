# Changelog

All notable changes to `laravel-translations` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- Initial release of Laravel Translations package
- Support for Google Translate, DeepL, and AI translation providers
- Automatic scanning of Laravel translation functions
- Model attribute translation with Eloquent integration
- Language management system
- Translation synchronization and caching
- Artisan commands for translation management
- Comprehensive documentation

### Features
- **Multiple Translation Providers**: Google Translate (free), DeepL (premium), AI providers (OpenAI, Anthropic)
- **Automatic Scanning**: Detects `trans()`, `__()`, `@lang`, and other Laravel translation functions
- **Model Translation**: Automatically translate Eloquent model attributes
- **Language Management**: Add, remove, and manage multiple languages
- **Performance Optimization**: Optional permanent caching and queued operations
- **Laravel Integration**: Seamless integration with Laravel's translation system

### Commands
- `translations:languages:add` - Add new languages
- `translations:scan` - Scan application for translation strings
- `translations:translate` - Translate scanned strings
- `translations:sync` - Synchronize translations and clean up

### Requirements
- PHP 8.2+
- Laravel 10.x, 11.x, or 12.x
