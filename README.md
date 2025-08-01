<div id="top">

<div align="center">


# Laravel Translations package

 Nice to meet you, we're [Vormkracht10](https://vormrkacht10.nl)

<em>Break Language Barriers, Empower Global Success</em>

<img src="https://img.shields.io/github/license/backstagephp/laravel-translations?style=flat&logo=opensourceinitiative&logoColor=white&color=0080ff" alt="license">
<img src="https://img.shields.io/github/last-commit/backstagephp/laravel-translations?style=flat&logo=git&logoColor=white&color=0080ff" alt="last-commit">
<img src="https://img.shields.io/github/languages/top/backstagephp/laravel-translations?style=flat&color=0080ff" alt="repo-top-language">
<img src="https://img.shields.io/github/languages/count/backstagephp/laravel-translations?style=flat&color=0080ff" alt="repo-language-count">
<span>
    <a href="https://packagist.org/packages/backstage/laravel-translations">
        <img src="https://img.shields.io/packagist/v/backstage/laravel-translations.svg?style=flat-square" alt="Latest Version on Packagist">
    </a>
    <a href="https://github.com/backstagephp/laravel-translations/actions?query=workflow%3Arun-tests+branch%3Amain">
        <img src="https://img.shields.io/github/actions/workflow/status/backstagephp/laravel-translations/run-tests.yml?branch=main&label=tests&style=flat-square" alt="GitHub Tests Action Status">
    </a>
    <a href="https://github.com/backstagephp/laravel-translations/actions?query=workflow%3A%22Fix+PHP+code+style+issues%22+branch%3Amain">
        <img src="https://img.shields.io/github/actions/workflow/status/backstagephp/laravel-translations/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square" alt="GitHub Code Style Action Status">
    </a>
    <a href="https://packagist.org/packages/backstage/laravel-translations">
        <img src="https://img.shields.io/packagist/dt/backstage/laravel-translations.svg?style=flat-square" alt="Total Downloads">
    </a>
</span>


<em>Built with the tools and technologies:</em>

<img src="https://img.shields.io/badge/JSON-000000.svg?style=flat&logo=JSON&logoColor=white" alt="JSON">
<img src="https://img.shields.io/badge/Markdown-000000.svg?style=flat&logo=Markdown&logoColor=white" alt="Markdown">
<img src="https://img.shields.io/badge/Composer-885630.svg?style=flat&logo=Composer&logoColor=white" alt="Composer">
<img src="https://img.shields.io/badge/GitHub%20Actions-2088FF.svg?style=flat&logo=GitHub-Actions&logoColor=white" alt="GitHub%20Actions">
<img src="https://img.shields.io/badge/PHP-777BB4.svg?style=flat&logo=PHP&logoColor=white" alt="PHP">
<img src="https://img.shields.io/badge/Laravel-FF2D20.svg?style=flat&logo=Laravel&logoColor=white" alt="Laravel">
<img src="https://img.shields.io/badge/Backstage-a4d6b9" alt="Backstagephp">
</div>
<br>

---

## Table of Contents

- [Overview](#overview)
- [Getting Started](#getting-started)
    - [Prerequisites](#prerequisites)
    - [Installation](#installation)
    - [Usage](#usage)
    - [Testing](#testing)
- [Features](#features)
- [Contributing](#contributing)
- [License](#license)
- [Acknowledgment](#acknowledgment)

---

## Overview

Laravel-Translations is a powerful developer tool that simplifies the management, translation, and synchronization of multilingual content within Laravel applications. It integrates multiple translation providers, automates workflows like language addition and translation updates, and ensures high code quality through static analysis and testing.

**Why laravel-translations?**

This project helps developers build scalable, maintainable multilingual systems with ease. The core features include:

- 🧩 **🌐 Globe:** Support for multiple translation providers like Google Translate, DeepL, and AI, enabling flexible localization strategies.
- 🚀 **⚙️ Automation:** Automates adding new languages, scanning source files, and updating translations to streamline workflows.
- 🔍 **🛠️ Quality:** Integrates static analysis (PHPStan) and automated testing to maintain a robust codebase.
- 🔄 **🔧 Synchronization:** Manages language lifecycle events and keeps translation data consistent across the system.
- 🎯 **🧰 Extensibility:** Custom loaders, translation drivers, and models provide a flexible architecture for complex localization needs.

---

## Features

|      | Component            | Details                                                                                     |
| :--- | :------------------- | :------------------------------------------------------------------------------------------ |
| ⚙️  | **Architecture**     | <ul><li>Laravel package for managing translations</li><li>Uses service providers for integration</li><li>Follows Laravel's modular structure</li></ul> |
| 🔩 | **Code Quality**     | <ul><li>Type safety via PHPDoc and static analysis with PHPStan</li><li>Code style enforced through PHP-CS-Fixer</li><li>Includes baseline and multiple config files for quality checks</li></ul> |
| 📄 | **Documentation**    | <ul><li>README provides setup and usage instructions</li><li>Config files documented for PHPStan, PHPUnit</li><li>CI workflows include documentation updates</li></ul> |
| 🔌 | **Integrations**      | <ul><li>Laravel framework</li><li>PHPStan for static analysis</li><li>PHPUnit for testing</li><li>GitHub Actions for CI/CD</li></ul> |
| 🧩 | **Modularity**        | <ul><li>Separated configuration files for different tools</li><li>Uses Composer for dependency management</li><li>Modular test setup with distinct workflows</li></ul> |
| 🧪 | **Testing**           | <ul><li>Unit tests via PHPUnit (`phpunit.xml.dist`)</li><li>Static analysis checks with PHPStan</li><li>CI pipelines run tests automatically</li></ul> |
| ⚡️  | **Performance**       | <ul><li>Optimized static analysis with PHPStan baseline</li><li>CI workflows designed for efficient runs</li></ul> |
| 🛡️ | **Security**          | <ul><li>Code quality tools help prevent common issues</li><li>Dependabot auto-merge for dependency updates reduces vulnerabilities</li></ul> |
| 📦 | **Dependencies**      | <ul><li>Managed via `composer.json`</li><li>Includes PHPStan, PHPUnit, and other dev tools</li></ul> |

---

## Getting Started

### Prerequisites

This project requires the following dependencies:

- **Programming Language:** PHP
- **Package Manager:** Composer

### Installation

Build laravel-translations from the source and install dependencies:

1. **Clone the repository:**

    ```sh
    ❯ git clone https://github.com/backstagephp/laravel-translations
    ```

2. **Navigate to the project directory:**

    ```sh
    ❯ cd laravel-translations
    ```

3. **Install the dependencies:**

**Using [composer](https://www.php.net/):**

```sh
❯ composer install
```

### Usage (with installation)

Run the project with:

**Using [composer](https://getcomposer.org/):**

You can install the package via composer:

```bash
composer require backstage/laravel-translations
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --provider="Backstage\Translations\Laravel\TranslationServiceProvider"
php artisan migrate
```

### Add lang types

If you want to add a language use the following command:

```bash
php artisan translations:languages:add {locale} {label}
```

For example:

```bash
php artisan translations:languages:add nl Nederlands

translations:languages:add en English

translations:languages:add fr-BE Français // French specifically for Belgians
```

### Scan for translations

To scan for translations within your Laravel application, use the following command:

```bash
php artisan translations:scan
```

### Scan for translations

To scan for translations within your Laravel application, use the following command:

```bash
php artisan translations:scan
```

### Translate scanned translations

To translate the scanned translations, use the following command:

```bash
php artisan translations:translate
        {--all : Translate language strings for all languages}
        {--code= : Translate language strings for a specific language}
        {--update : Update and overwrite existing translations}
```

For example:

```bash
php artisan translations:translate --code=nl

php artisan translations:translate --code=en

php artisan translations:translate --code=fr-BE --update // overwrite existing translations

php artisan translations:translate // translate all languages
```

### Using the the model translatable attributes feature

To translate attributes within youre models, import the following to your model:

```php
use Backstage\Translations\Laravel\Contracts\TranslatesAttributes;
use Backstage\Translations\Laravel\Models\Concerns\HasTranslatableAttributes;
use Illuminate\Database\Eloquent\Model;

class TestTranslateModel extends Model implements TranslatesAttributes
{
    use HasTranslatableAttributes;
}
```

After that register the model inside the ``translations.php``:
```php
    'eloquent' => [
        'translatable-models' => [
            // Content::class,
            TestTranslateModel::class
        ],
    ],
```

Now add the translatable attributes to the model:
```php
use Backstage\Translations\Laravel\Contracts\TranslatesAttributes;
use Backstage\Translations\Laravel\Models\Concerns\HasTranslatableAttributes;
use Illuminate\Database\Eloquent\Model;

class TestTranslateModel extends Model implements TranslatesAttributes
{
    use HasTranslatableAttributes;

    public function getTranslatableAttributes(): array
    {
        return [
            'title',
            'description',
            'body',
            'metadata',
            'views',
        ];
    }
}
```

After this it's very important that you add the casts per translatable attribute:
```php
   protected $casts = [
        'title' => 'string',
        'description' => 'encrypted',
        'body' => 'array',
        'metadata' => 'array',
        'views' => 'integer',
    ];
```

After this is done, every time you save an entry, the (new) contents automatticly gets updates (queued). If you want to check every night if the translatable attributes are synced, use this command to schedule:
```bash
php artisan translations:sync # this will remove orphaned translations (if existing) and fills translations if they are missing
```

To retrieve the translatable attribute you use:
```php
$translatedDescription = $modelInstance->getTranslatedAttribute('description');
```

If a specific locale is needed, use:
```php
$translatedDescription = $modelInstance->getTranslatedAttribute(
    attribute: 'description',
    locale: 'de' // Can be any language existig in the DB (check translations:languages:add command)
 );
```

If needed to get all translated attributes, use:

```php
$translatedAttributes = $modelInstance->getTranslatedAttributes();
```

If needed to get all translated attributes a specific locale use:

```php
$translatedAttributes = $modelInstance->getTranslatedAttributes(
    locale: 'de' 
 );
```

### Testing

Laravel-translations uses the {__test_framework__} test framework. Run the test suite with:

**Using [composer](https://www.php.net/):**

```bash
vendor/bin/phpunit
```

---

## Contributing

- **💬 [Join the Discussions](https://github.com/backstagephp/laravel-translations/discussions)**: Share your insights, provide feedback, or ask questions.
- **🐛 [Report Issues](https://github.com/backstagephp/laravel-translations/issues)**: Submit bugs found or log feature requests for the `laravel-translations` project.
- **💡 [Submit Pull Requests](https://github.com/backstagephp/laravel-translations/blob/main/CONTRIBUTING.md)**: Review open PRs, and submit your own PRs.

<details closed>
<summary>Contributing Guidelines</summary>

1. **Fork the Repository**: Start by forking the project repository to your github account.
2. **Clone Locally**: Clone the forked repository to your local machine using a git client.
   ```sh
   git clone https://github.com/backstagephp/laravel-translations
   ```
3. **Create a New Branch**: Always work on a new branch, giving it a descriptive name.
   ```sh
   git checkout -b new-feature-x
   ```
4. **Make Your Changes**: Develop and test your changes locally.
5. **Commit Your Changes**: Commit with a clear message describing your updates.
   ```sh
   git commit -m 'Implemented new feature x.'
   ```
6. **Push to github**: Push the changes to your forked repository.
   ```sh
   git push origin new-feature-x
   ```
7. **Submit a Pull Request**: Create a PR against the original project repository. Clearly describe the changes and their motivations.
8. **Review**: Once your PR is reviewed and approved, it will be merged into the main branch. Congratulations on your contribution!
</details>

<details closed>
<summary>Contributor Graph</summary>
<br>
<p align="left">
   <a href="https://github.com{/backstagephp/laravel-translations/}graphs/contributors">
      <img src="https://contrib.rocks/image?repo=backstagephp/laravel-translations">
   </a>
</p>
</details>

---

## License

Laravel-translations is protected under the [LICENSE](https://choosealicense.com/licenses) License. For more details, refer to the [LICENSE](https://choosealicense.com/licenses/) file.

---

## Acknowledgments

- Credit `contributors`, `inspiration`, `references`, etc.

<div align="left"><a href="#top">⬆ Return</a></div>

---
