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
- [Project Structure](#project-structure)
    - [Project Index](#project-index)
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

## Project Structure

```sh
└── laravel-translations/
    ├── .github
    │   ├── FUNDING.yml
    │   ├── ISSUE_TEMPLATE
    │   ├── dependabot.yml
    │   └── workflows
    ├── CHANGELOG.md
    ├── LICENSE.md
    ├── README.md
    ├── composer.json
    ├── config
    │   └── translations.php
    ├── database
    │   └── migrations
    ├── phpstan-baseline.neon
    ├── phpstan.neon.dist
    ├── phpunit.xml.dist
    ├── src
    │   ├── Base
    │   ├── Caches
    │   ├── Commands
    │   ├── Contracts
    │   ├── Domain
    │   ├── Drivers
    │   ├── Events
    │   ├── Facades
    │   ├── Jobs
    │   ├── Listners
    │   ├── Managers
    │   ├── Models
    │   ├── Observers
    │   ├── TranslationLoaderServiceProvider.php
    │   ├── TranslationServiceProvider.php
    │   └── helpers.php
    └── tests
        ├── ArchTest.php
        ├── ExampleTest.php
        ├── Pest.php
        └── TestCase.php
```

---

### Project Index

<details open>
	<summary><b><code>LARAVEL-TRANSLATIONS/</code></b></summary>
	<!-- __root__ Submodule -->
	<details>
		<summary><b>__root__</b></summary>
		<blockquote>
			<div class='directory-path' style='padding: 8px 0; color: #666;'>
				<code><b>⦿ __root__</b></code>
			<table style='width: 100%; border-collapse: collapse;'>
			<thead>
				<tr style='background-color: #f8f9fa;'>
					<th style='width: 30%; text-align: left; padding: 8px;'>File Name</th>
					<th style='text-align: left; padding: 8px;'>Summary</th>
				</tr>
			</thead>
				<tr style='border-bottom: 1px solid #eee;'>
					<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/phpstan-baseline.neon'>phpstan-baseline.neon</a></b></td>
					<td style='padding: 8px;'>- Establishes a baseline configuration for PHPStan static analysis, defining rules and exceptions to ensure consistent code quality across the project<br>- It guides the static analysis process, helping maintain code standards and detect potential issues early, thereby supporting the overall architectures stability and maintainability within the PHP-based codebase.</td>
				</tr>
				<tr style='border-bottom: 1px solid #eee;'>
					<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/CHANGELOG.md'>CHANGELOG.md</a></b></td>
					<td style='padding: 8px;'>- Documents the evolution of the laravel-translations project by recording all significant updates and modifications<br>- Serves as a historical reference to track feature additions, improvements, and bug fixes, ensuring transparency and facilitating maintenance within the overall architecture of the translation management system.</td>
				</tr>
				<tr style='border-bottom: 1px solid #eee;'>
					<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/README.md'>README.md</a></b></td>
					<td style='padding: 8px;'>- Provides core functionality for managing, scanning, and translating language strings within a Laravel application<br>- Facilitates adding new languages, automating translation workflows, and integrating multiple translation providers, including AI-based options<br>- Supports seamless localization updates, ensuring consistent and efficient multilingual content management across the entire codebase architecture.</td>
				</tr>
				<tr style='border-bottom: 1px solid #eee;'>
					<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/phpstan.neon.dist'>phpstan.neon.dist</a></b></td>
					<td style='padding: 8px;'>- Defines static analysis parameters for the project, ensuring code quality and consistency across core directories such as source, configuration, and database<br>- It sets the analysis level, temporary directory, and compatibility checks, facilitating early detection of issues and maintaining adherence to coding standards within the overall architecture.</td>
				</tr>
				<tr style='border-bottom: 1px solid #eee;'>
					<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/composer.json'>composer.json</a></b></td>
					<td style='padding: 8px;'>- Provides the core functionality for managing and translating application content within a Laravel environment<br>- Facilitates seamless integration of multiple translation services, enabling dynamic language support and efficient localization workflows across the codebase<br>- Acts as a foundational component for delivering multilingual capabilities in the overall architecture.</td>
				</tr>
				<tr style='border-bottom: 1px solid #eee;'>
					<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/phpunit.xml.dist'>phpunit.xml.dist</a></b></td>
					<td style='padding: 8px;'>- Defines the configuration for running automated tests within the project, specifying test discovery, execution parameters, and reporting formats<br>- Ensures consistent, reliable testing of source code located in the src directory, facilitating quality assurance and integration within the overall architecture<br>- Supports efficient test execution and detailed reporting to maintain code integrity across the codebase.</td>
				</tr>
				<tr style='border-bottom: 1px solid #eee;'>
					<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/LICENSE.md'>LICENSE.md</a></b></td>
					<td style='padding: 8px;'>- Defines the licensing terms and legal permissions for the project, ensuring clear usage rights and restrictions<br>- It establishes the open-source nature of the software, facilitating legal clarity for users and contributors while protecting intellectual property under the MIT License<br>- This document supports the projects integration and distribution within the broader software ecosystem.</td>
				</tr>
			</table>
		</blockquote>
	</details>
	<!-- config Submodule -->
	<details>
		<summary><b>config</b></summary>
		<blockquote>
			<div class='directory-path' style='padding: 8px 0; color: #666;'>
				<code><b>⦿ config</b></code>
			<table style='width: 100%; border-collapse: collapse;'>
			<thead>
				<tr style='background-color: #f8f9fa;'>
					<th style='width: 30%; text-align: left; padding: 8px;'>File Name</th>
					<th style='text-align: left; padding: 8px;'>Summary</th>
				</tr>
			</thead>
				<tr style='border-bottom: 1px solid #eee;'>
					<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/config/translations.php'>translations.php</a></b></td>
					<td style='padding: 8px;'>- Defines configuration for managing multilingual support within the application, including translation file scanning, supported functions, and multiple translation service providers such as Google Translate, DeepL, and OpenAI<br>- Facilitates seamless integration and switching between translation engines, ensuring accurate and efficient localization across the codebase.</td>
				</tr>
			</table>
		</blockquote>
	</details>
	<!-- .github Submodule -->
	<details>
		<summary><b>.github</b></summary>
		<blockquote>
			<div class='directory-path' style='padding: 8px 0; color: #666;'>
				<code><b>⦿ .github</b></code>
			<table style='width: 100%; border-collapse: collapse;'>
			<thead>
				<tr style='background-color: #f8f9fa;'>
					<th style='width: 30%; text-align: left; padding: 8px;'>File Name</th>
					<th style='text-align: left; padding: 8px;'>Summary</th>
				</tr>
			</thead>
				<tr style='border-bottom: 1px solid #eee;'>
					<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/.github/FUNDING.yml'>FUNDING.yml</a></b></td>
					<td style='padding: 8px;'>- Facilitates project funding recognition by linking the repository to the Vormkracht10 GitHub organization<br>- It streamlines acknowledgment of financial support, ensuring proper attribution within the broader project architecture<br>- This setup promotes transparency and aligns funding sources with the projects open-source ecosystem, supporting sustainable development and community engagement.</td>
				</tr>
				<tr style='border-bottom: 1px solid #eee;'>
					<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/.github/dependabot.yml'>dependabot.yml</a></b></td>
					<td style='padding: 8px;'>- Automates dependency management updates for GitHub Actions workflows and PHP Composer packages, ensuring the project remains current with external libraries and tools<br>- Integrates scheduled weekly checks, facilitating proactive security and stability improvements across the codebase<br>- Supports maintaining a reliable and secure development environment aligned with best practices.</td>
				</tr>
			</table>
			<!-- workflows Submodule -->
			<details>
				<summary><b>workflows</b></summary>
				<blockquote>
					<div class='directory-path' style='padding: 8px 0; color: #666;'>
						<code><b>⦿ .github.workflows</b></code>
					<table style='width: 100%; border-collapse: collapse;'>
					<thead>
						<tr style='background-color: #f8f9fa;'>
							<th style='width: 30%; text-align: left; padding: 8px;'>File Name</th>
							<th style='text-align: left; padding: 8px;'>Summary</th>
						</tr>
					</thead>
						<tr style='border-bottom: 1px solid #eee;'>
							<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/.github/workflows/update-changelog.yml'>update-changelog.yml</a></b></td>
							<td style='padding: 8px;'>- Automates the process of updating the projects changelog upon new releases by integrating release notes and version information<br>- Ensures the changelog remains current and accurate, facilitating transparent documentation of changes<br>- This workflow supports maintaining consistent release documentation within the overall project architecture, enhancing traceability and communication for users and contributors.</td>
						</tr>
						<tr style='border-bottom: 1px solid #eee;'>
							<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/.github/workflows/fix-php-code-style-issues.yml'>fix-php-code-style-issues.yml</a></b></td>
							<td style='padding: 8px;'>- Automates the enforcement and correction of PHP code style standards across the codebase<br>- Integrates with GitHub workflows to automatically identify and fix styling issues on code pushes, ensuring consistent code quality and reducing manual formatting efforts<br>- Supports maintaining a clean, readable, and maintainable PHP codebase within the project architecture.</td>
						</tr>
						<tr style='border-bottom: 1px solid #eee;'>
							<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/.github/workflows/phpstan.yml'>phpstan.yml</a></b></td>
							<td style='padding: 8px;'>- Automates static analysis of PHP code to ensure code quality and adherence to standards<br>- Integrates PHPStan into the CI/CD pipeline, running on code changes to detect potential issues early<br>- Supports maintaining a robust, reliable codebase by providing continuous feedback on code correctness within the projects architecture.</td>
						</tr>
						<tr style='border-bottom: 1px solid #eee;'>
							<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/.github/workflows/dependabot-auto-merge.yml'>dependabot-auto-merge.yml</a></b></td>
							<td style='padding: 8px;'>- Automates the merging of Dependabot pull requests for minor and patch semantic version updates, ensuring timely integration of dependency updates<br>- Enhances the overall project architecture by maintaining up-to-date dependencies with minimal manual intervention, thereby improving security, stability, and development efficiency within the continuous integration workflow.</td>
						</tr>
						<tr style='border-bottom: 1px solid #eee;'>
							<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/.github/workflows/run-tests.yml'>run-tests.yml</a></b></td>
							<td style='padding: 8px;'>- Defines the automated testing workflow for the project, ensuring code quality across multiple PHP versions, Laravel frameworks, and operating systems<br>- It orchestrates dependency installation and executes tests, facilitating continuous integration and validation of the codebases stability and compatibility within the overall architecture.</td>
						</tr>
					</table>
				</blockquote>
			</details>
			<!-- ISSUE_TEMPLATE Submodule -->
			<details>
				<summary><b>ISSUE_TEMPLATE</b></summary>
				<blockquote>
					<div class='directory-path' style='padding: 8px 0; color: #666;'>
						<code><b>⦿ .github.ISSUE_TEMPLATE</b></code>
					<table style='width: 100%; border-collapse: collapse;'>
					<thead>
						<tr style='background-color: #f8f9fa;'>
							<th style='width: 30%; text-align: left; padding: 8px;'>File Name</th>
							<th style='text-align: left; padding: 8px;'>Summary</th>
						</tr>
					</thead>
						<tr style='border-bottom: 1px solid #eee;'>
							<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/.github/ISSUE_TEMPLATE/config.yml'>config.yml</a></b></td>
							<td style='padding: 8px;'>- Defines project contribution and issue reporting guidelines within the GitHub repository<br>- Facilitates community engagement by providing structured links for asking questions, requesting features, and reporting security issues<br>- Enhances collaboration and project transparency, ensuring users and contributors can easily access support channels and contribute effectively to the Laravel translations ecosystem.</td>
						</tr>
						<tr style='border-bottom: 1px solid #eee;'>
							<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/.github/ISSUE_TEMPLATE/bug.yml'>bug.yml</a></b></td>
							<td style='padding: 8px;'>- Defines the structure and content of bug report submissions within the projects GitHub issue tracking system<br>- It standardizes user input for identifying, reproducing, and diagnosing issues, ensuring consistent and comprehensive bug reports<br>- This enhances the overall quality and efficiency of issue resolution across the codebase architecture.</td>
						</tr>
					</table>
				</blockquote>
			</details>
		</blockquote>
	</details>
	<!-- src Submodule -->
	<details>
		<summary><b>src</b></summary>
		<blockquote>
			<div class='directory-path' style='padding: 8px 0; color: #666;'>
				<code><b>⦿ src</b></code>
			<table style='width: 100%; border-collapse: collapse;'>
			<thead>
				<tr style='background-color: #f8f9fa;'>
					<th style='width: 30%; text-align: left; padding: 8px;'>File Name</th>
					<th style='text-align: left; padding: 8px;'>Summary</th>
				</tr>
			</thead>
				<tr style='border-bottom: 1px solid #eee;'>
					<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/src/helpers.php'>helpers.php</a></b></td>
					<td style='padding: 8px;'>- Provides localization utilities for displaying country and language names based on locale codes<br>- Facilitates internationalization by translating region and language identifiers into user-friendly, localized display names, supporting seamless multilingual user experiences within the broader application architecture.</td>
				</tr>
				<tr style='border-bottom: 1px solid #eee;'>
					<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/src/TranslationLoaderServiceProvider.php'>TranslationLoaderServiceProvider.php</a></b></td>
					<td style='padding: 8px;'>- Defines a service provider that integrates a custom translation loader into the Laravel framework, enabling dynamic and flexible management of translation resources within the applications architecture<br>- It ensures the translation loader is registered as a singleton, facilitating efficient and centralized handling of localization data across the codebase.</td>
				</tr>
				<tr style='border-bottom: 1px solid #eee;'>
					<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/src/TranslationServiceProvider.php'>TranslationServiceProvider.php</a></b></td>
					<td style='padding: 8px;'>- Registers and configures the Laravel translation package, integrating migration, command, event, and cache setups to facilitate multilingual support<br>- Manages translation data synchronization, language management, and event handling, ensuring seamless translation workflows within the application architecture<br>- Automates scheduled translation updates and maintains cache consistency, supporting scalable and maintainable internationalization features.</td>
				</tr>
			</table>
			<!-- Domain Submodule -->
			<details>
				<summary><b>Domain</b></summary>
				<blockquote>
					<div class='directory-path' style='padding: 8px 0; color: #666;'>
						<code><b>⦿ src.Domain</b></code>
					<!-- Translatables Submodule -->
					<details>
						<summary><b>Translatables</b></summary>
						<blockquote>
							<div class='directory-path' style='padding: 8px 0; color: #666;'>
								<code><b>⦿ src.Domain.Translatables</b></code>
							<!-- Actions Submodule -->
							<details>
								<summary><b>Actions</b></summary>
								<blockquote>
									<div class='directory-path' style='padding: 8px 0; color: #666;'>
										<code><b>⦿ src.Domain.Translatables.Actions</b></code>
									<table style='width: 100%; border-collapse: collapse;'>
									<thead>
										<tr style='background-color: #f8f9fa;'>
											<th style='width: 30%; text-align: left; padding: 8px;'>File Name</th>
											<th style='text-align: left; padding: 8px;'>Summary</th>
										</tr>
									</thead>
										<tr style='border-bottom: 1px solid #eee;'>
											<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/src/Domain/Translatables/Actions/TranslateAttributesForAllLanguages.php'>TranslateAttributesForAllLanguages.php</a></b></td>
											<td style='padding: 8px;'>- Facilitates the retrieval of translatable attributes for a given model across all supported languages, enabling comprehensive multilingual data handling within the application<br>- Integrates seamlessly into the broader translation architecture, ensuring consistent and efficient access to localized content for diverse language audiences.</td>
										</tr>
										<tr style='border-bottom: 1px solid #eee;'>
											<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/src/Domain/Translatables/Actions/UpdateTranslateAttributes.php'>UpdateTranslateAttributes.php</a></b></td>
											<td style='padding: 8px;'>- Facilitates bulk updating of translatable attributes across all supported languages within the applications domain<br>- Ensures that specified attributes are translated for each language, maintaining consistency and completeness of multilingual data<br>- Integrates seamlessly into the translation management workflow, supporting dynamic language updates and enhancing the applications internationalization capabilities.</td>
										</tr>
										<tr style='border-bottom: 1px solid #eee;'>
											<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/src/Domain/Translatables/Actions/TranslateAttribute.php'>TranslateAttribute.php</a></b></td>
											<td style='padding: 8px;'>- Facilitates dynamic translation of model attributes within the application, enabling seamless localization by translating text and nested data structures into target languages<br>- Integrates with translation services to handle individual strings, arrays, and complex paths, ensuring translatable content is efficiently managed and stored, thereby supporting multilingual functionality across the codebase architecture.</td>
										</tr>
										<tr style='border-bottom: 1px solid #eee;'>
											<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/src/Domain/Translatables/Actions/GetTranslatedAttributes.php'>GetTranslatedAttributes.php</a></b></td>
											<td style='padding: 8px;'>- Provides a mechanism to retrieve translated attributes for models implementing translatable functionality within the application<br>- It centralizes the logic for fetching localized attribute values, supporting multi-language content management and ensuring consistent translation retrieval across the codebase<br>- This enhances the systems ability to deliver localized data seamlessly in various contexts.</td>
										</tr>
										<tr style='border-bottom: 1px solid #eee;'>
											<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/src/Domain/Translatables/Actions/PushTranslatedAttribute.php'>PushTranslatedAttribute.php</a></b></td>
											<td style='padding: 8px;'>- Facilitates the storage and updating of translated attribute values for models within the multilingual system<br>- Ensures that translations are correctly associated with their respective models and locales, creating language entries as needed<br>- Supports seamless management of translatable content across different languages, integrating with the broader localization architecture of the application.</td>
										</tr>
										<tr style='border-bottom: 1px solid #eee;'>
											<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/src/Domain/Translatables/Actions/TranslateAttributes.php'>TranslateAttributes.php</a></b></td>
											<td style='padding: 8px;'>- Facilitates the translation of model attributes into a specified language, enabling seamless localization within the application<br>- It centralizes the logic for retrieving and translating translatable attributes, supporting dynamic language selection and ensuring consistent multilingual data presentation across the codebase.</td>
										</tr>
										<tr style='border-bottom: 1px solid #eee;'>
											<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/src/Domain/Translatables/Actions/IsTranslatableAttribute.php'>IsTranslatableAttribute.php</a></b></td>
											<td style='padding: 8px;'>- Provides a mechanism to verify if a specific attribute of a model is translatable within the translation management system<br>- It integrates with models implementing translatable attributes, enabling the application to dynamically determine which attributes support localization, thereby supporting flexible and scalable multilingual content handling across the codebase.</td>
										</tr>
										<tr style='border-bottom: 1px solid #eee;'>
											<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/src/Domain/Translatables/Actions/UpdateAttributesIfTranslatable.php'>UpdateAttributesIfTranslatable.php</a></b></td>
											<td style='padding: 8px;'>- Facilitates updating translatable attributes across all language versions within the domain layer, ensuring consistency and synchronization of multilingual data<br>- Integrates seamlessly with models implementing translatable behavior, enabling efficient bulk updates of specified attributes to maintain data integrity in a multilingual application architecture.</td>
										</tr>
										<tr style='border-bottom: 1px solid #eee;'>
											<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/src/Domain/Translatables/Actions/SyncTranslations.php'>SyncTranslations.php</a></b></td>
											<td style='padding: 8px;'>- Facilitates synchronization of translatable attributes across all supported languages within models, ensuring consistent multilingual content management<br>- Integrates seamlessly into the broader translation architecture, enabling efficient updates and maintaining data integrity across the applications localization layer<br>- This action streamlines the process of propagating translation changes, supporting the system’s multilingual capabilities.</td>
										</tr>
										<tr style='border-bottom: 1px solid #eee;'>
											<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/src/Domain/Translatables/Actions/GetTranslatedAttribute.php'>GetTranslatedAttribute.php</a></b></td>
											<td style='padding: 8px;'>- Facilitates retrieval of translated attribute values within the applications domain, enabling seamless localization support for models with translatable attributes<br>- Integrates with the broader architecture to ensure consistent translation handling, allowing dynamic attribute mutation and fallback to default values when translations are unavailable<br>- Enhances multilingual data management across the codebase.</td>
										</tr>
										<tr style='border-bottom: 1px solid #eee;'>
											<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/src/Domain/Translatables/Actions/TranslateAttributeForAllLanguages.php'>TranslateAttributeForAllLanguages.php</a></b></td>
											<td style='padding: 8px;'>- Facilitates bulk translation of a specified attribute across all available languages for a given model, ensuring comprehensive multilingual support within the application<br>- Integrates seamlessly into the broader translation architecture, enabling efficient and consistent localization by automating the process of generating translations for multiple languages simultaneously.</td>
										</tr>
									</table>
								</blockquote>
							</details>
						</blockquote>
					</details>
					<!-- Scanner Submodule -->
					<details>
						<summary><b>Scanner</b></summary>
						<blockquote>
							<div class='directory-path' style='padding: 8px 0; color: #666;'>
								<code><b>⦿ src.Domain.Scanner</b></code>
							<!-- Actions Submodule -->
							<details>
								<summary><b>Actions</b></summary>
								<blockquote>
									<div class='directory-path' style='padding: 8px 0; color: #666;'>
										<code><b>⦿ src.Domain.Scanner.Actions</b></code>
									<table style='width: 100%; border-collapse: collapse;'>
									<thead>
										<tr style='background-color: #f8f9fa;'>
											<th style='width: 30%; text-align: left; padding: 8px;'>File Name</th>
											<th style='text-align: left; padding: 8px;'>Summary</th>
										</tr>
									</thead>
										<tr style='border-bottom: 1px solid #eee;'>
											<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/src/Domain/Scanner/Actions/FindTranslatables.php'>FindTranslatables.php</a></b></td>
											<td style='padding: 8px;'>- Facilitates the discovery of translatable keys within source files by scanning specified directories and file types<br>- It extracts translation keys, including namespaces and groups, and compiles them into a collection for further processing or integration into translation management workflows<br>- Supports merging with existing translation data to maintain consistency across the codebase.</td>
										</tr>
									</table>
								</blockquote>
							</details>
						</blockquote>
					</details>
				</blockquote>
			</details>
			<!-- Listners Submodule -->
			<details>
				<summary><b>Listners</b></summary>
				<blockquote>
					<div class='directory-path' style='padding: 8px 0; color: #666;'>
						<code><b>⦿ src.Listners</b></code>
					<table style='width: 100%; border-collapse: collapse;'>
					<thead>
						<tr style='background-color: #f8f9fa;'>
							<th style='width: 30%; text-align: left; padding: 8px;'>File Name</th>
							<th style='text-align: left; padding: 8px;'>Summary</th>
						</tr>
					</thead>
						<tr style='border-bottom: 1px solid #eee;'>
							<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/src/Listners/DeleteTranslations.php'>DeleteTranslations.php</a></b></td>
							<td style='padding: 8px;'>- Handles cleanup of translation data upon language removal by listening for LanguageDeleted events and deleting associated translations from the database<br>- Ensures data consistency and integrity within the translation management system, supporting seamless language lifecycle management in the overall application architecture.</td>
						</tr>
						<tr style='border-bottom: 1px solid #eee;'>
							<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/src/Listners/HandleLanguageCodeChanges.php'>HandleLanguageCodeChanges.php</a></b></td>
							<td style='padding: 8px;'>- Handles updates to translation entries when a language code changes, ensuring data consistency across the translation system<br>- It listens for language code change events and synchronizes the affected translation records by updating their codes and resetting translation timestamps, thereby maintaining accurate and current multilingual content within the applications architecture.</td>
						</tr>
					</table>
				</blockquote>
			</details>
			<!-- Drivers Submodule -->
			<details>
				<summary><b>Drivers</b></summary>
				<blockquote>
					<div class='directory-path' style='padding: 8px 0; color: #666;'>
						<code><b>⦿ src.Drivers</b></code>
					<table style='width: 100%; border-collapse: collapse;'>
					<thead>
						<tr style='background-color: #f8f9fa;'>
							<th style='width: 30%; text-align: left; padding: 8px;'>File Name</th>
							<th style='text-align: left; padding: 8px;'>Summary</th>
						</tr>
					</thead>
						<tr style='border-bottom: 1px solid #eee;'>
							<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/src/Drivers/GoogleTranslator.php'>GoogleTranslator.php</a></b></td>
							<td style='padding: 8px;'>- Facilitates language translation within the application by integrating Google Translate API, enabling dynamic conversion of text to various target languages<br>- Serves as a core component in the multilingual architecture, abstracting external translation service interactions and ensuring seamless, reliable localization support across the codebase.</td>
						</tr>
						<tr style='border-bottom: 1px solid #eee;'>
							<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/src/Drivers/DeepLTranslator.php'>DeepLTranslator.php</a></b></td>
							<td style='padding: 8px;'>- Implements a translation driver leveraging DeepLs API to facilitate multilingual support within the application<br>- It integrates seamlessly into the broader translation architecture, enabling dynamic text translation by adhering to the projects contract standards<br>- This component enhances the system's ability to deliver accurate, real-time translations, supporting internationalization efforts across the codebase.</td>
						</tr>
						<tr style='border-bottom: 1px solid #eee;'>
							<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/src/Drivers/AITranslator.php'>AITranslator.php</a></b></td>
							<td style='padding: 8px;'>- Implements AI-powered translation capabilities within the application, enabling accurate and context-aware conversion of text and JSON data into various target languages<br>- Ensures preservation of formatting, variables, and structure, facilitating seamless multilingual support across the codebase<br>- Serves as a core component for integrating advanced language translation services into the overall architecture.</td>
						</tr>
					</table>
				</blockquote>
			</details>
			<!-- Models Submodule -->
			<details>
				<summary><b>Models</b></summary>
				<blockquote>
					<div class='directory-path' style='padding: 8px 0; color: #666;'>
						<code><b>⦿ src.Models</b></code>
					<table style='width: 100%; border-collapse: collapse;'>
					<thead>
						<tr style='background-color: #f8f9fa;'>
							<th style='width: 30%; text-align: left; padding: 8px;'>File Name</th>
							<th style='text-align: left; padding: 8px;'>Summary</th>
						</tr>
					</thead>
						<tr style='border-bottom: 1px solid #eee;'>
							<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/src/Models/Language.php'>Language.php</a></b></td>
							<td style='padding: 8px;'>- Defines the Language model within the translation system, representing supported languages and their attributes<br>- Facilitates retrieval of language details, including localized names and country codes, and manages relationships with translation data<br>- Serves as a core component for handling multilingual support and language-specific content within the overall application architecture.</td>
						</tr>
						<tr style='border-bottom: 1px solid #eee;'>
							<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/src/Models/Translation.php'>Translation.php</a></b></td>
							<td style='padding: 8px;'>- Defines the translation data model within the applications multilingual architecture, enabling storage, retrieval, and management of translation entries<br>- Facilitates association with language entities and ensures cache consistency upon updates, supporting efficient and accurate localization across the platform.</td>
						</tr>
						<tr style='border-bottom: 1px solid #eee;'>
							<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/src/Models/TranslatedAttribute.php'>TranslatedAttribute.php</a></b></td>
							<td style='padding: 8px;'>- Defines the data model for storing translated attributes associated with various translatable entities within the application<br>- Facilitates dynamic multilingual support by linking translation records to their respective models and languages, enabling seamless retrieval and management of localized content across the systems architecture.</td>
						</tr>
					</table>
					<!-- Concerns Submodule -->
					<details>
						<summary><b>Concerns</b></summary>
						<blockquote>
							<div class='directory-path' style='padding: 8px 0; color: #666;'>
								<code><b>⦿ src.Models.Concerns</b></code>
							<table style='width: 100%; border-collapse: collapse;'>
							<thead>
								<tr style='background-color: #f8f9fa;'>
									<th style='width: 30%; text-align: left; padding: 8px;'>File Name</th>
									<th style='text-align: left; padding: 8px;'>Summary</th>
								</tr>
							</thead>
								<tr style='border-bottom: 1px solid #eee;'>
									<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/src/Models/Concerns/HasTranslatableAttributes.php'>HasTranslatableAttributes.php</a></b></td>
									<td style='padding: 8px;'>- Provides a reusable trait to enable multilingual support within Laravel models by managing translatable attributes<br>- It facilitates translation, retrieval, synchronization, and updating of localized content, integrating seamlessly with model lifecycle events<br>- This trait centralizes translation logic, ensuring consistent handling of multilingual data across the applications architecture.</td>
								</tr>
							</table>
						</blockquote>
					</details>
				</blockquote>
			</details>
			<!-- Contracts Submodule -->
			<details>
				<summary><b>Contracts</b></summary>
				<blockquote>
					<div class='directory-path' style='padding: 8px 0; color: #666;'>
						<code><b>⦿ src.Contracts</b></code>
					<table style='width: 100%; border-collapse: collapse;'>
					<thead>
						<tr style='background-color: #f8f9fa;'>
							<th style='width: 30%; text-align: left; padding: 8px;'>File Name</th>
							<th style='text-align: left; padding: 8px;'>Summary</th>
						</tr>
					</thead>
						<tr style='border-bottom: 1px solid #eee;'>
							<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/src/Contracts/TranslatorContract.php'>TranslatorContract.php</a></b></td>
							<td style='padding: 8px;'>- Defines a contract for translation services within the Laravel-based architecture, establishing a standardized interface for translating text into various languages<br>- Facilitates consistent implementation across different translation providers, ensuring seamless integration and extensibility within the overall translation system of the project.</td>
						</tr>
						<tr style='border-bottom: 1px solid #eee;'>
							<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/src/Contracts/TranslatesAttributes.php'>TranslatesAttributes.php</a></b></td>
							<td style='padding: 8px;'>- Defines a contract for integrating multilingual translation capabilities into Eloquent models, enabling attribute translation, retrieval, and synchronization across locales<br>- Facilitates seamless management of translatable attributes, supporting storage, updates, and relationship handling, thereby ensuring consistent multilingual data handling within the applications architecture.</td>
						</tr>
					</table>
				</blockquote>
			</details>
			<!-- Facades Submodule -->
			<details>
				<summary><b>Facades</b></summary>
				<blockquote>
					<div class='directory-path' style='padding: 8px 0; color: #666;'>
						<code><b>⦿ src.Facades</b></code>
					<table style='width: 100%; border-collapse: collapse;'>
					<thead>
						<tr style='background-color: #f8f9fa;'>
							<th style='width: 30%; text-align: left; padding: 8px;'>File Name</th>
							<th style='text-align: left; padding: 8px;'>Summary</th>
						</tr>
					</thead>
						<tr style='border-bottom: 1px solid #eee;'>
							<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/src/Facades/Translator.php'>Translator.php</a></b></td>
							<td style='padding: 8px;'>- Provides a facade for seamless access to translation functionalities within the Laravel application, enabling consistent and simplified interaction with the underlying translation contract<br>- It integrates the translation service into the applications architecture, promoting modularity and ease of use across the codebase<br>- This facilitates efficient management and retrieval of localized content throughout the system.</td>
						</tr>
					</table>
				</blockquote>
			</details>
			<!-- Commands Submodule -->
			<details>
				<summary><b>Commands</b></summary>
				<blockquote>
					<div class='directory-path' style='padding: 8px 0; color: #666;'>
						<code><b>⦿ src.Commands</b></code>
					<table style='width: 100%; border-collapse: collapse;'>
					<thead>
						<tr style='background-color: #f8f9fa;'>
							<th style='width: 30%; text-align: left; padding: 8px;'>File Name</th>
							<th style='text-align: left; padding: 8px;'>Summary</th>
						</tr>
					</thead>
						<tr style='border-bottom: 1px solid #eee;'>
							<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/src/Commands/TranslateTranslations.php'>TranslateTranslations.php</a></b></td>
							<td style='padding: 8px;'>- Facilitates bulk and targeted translation of language strings within the application, enabling efficient updates and synchronization of translations across multiple languages<br>- Integrates with background jobs to process translation requests, ensuring the applications multilingual content remains current and accurate in alignment with the overall architecture.</td>
						</tr>
						<tr style='border-bottom: 1px solid #eee;'>
							<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/src/Commands/SyncTranslations.php'>SyncTranslations.php</a></b></td>
							<td style='padding: 8px;'>- Facilitates synchronization and cleanup of translation data across all translatable models within the application<br>- Ensures translation attributes are up-to-date, removes orphaned translations, and optimizes performance through concurrent processing<br>- Integrates seamlessly into the overall architecture by maintaining consistency and integrity of multilingual content across the codebase.</td>
						</tr>
						<tr style='border-bottom: 1px solid #eee;'>
							<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/src/Commands/TranslationsAddLanguage.php'>TranslationsAddLanguage.php</a></b></td>
							<td style='padding: 8px;'>- Facilitates the addition of new languages to the translation system within the application<br>- It ensures seamless integration of language data by capturing user input or arguments, verifying existing entries, and creating new language records<br>- This command supports the dynamic expansion of multilingual content, maintaining consistency and ease of management across the codebases localization architecture.</td>
						</tr>
						<tr style='border-bottom: 1px solid #eee;'>
							<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/src/Commands/TranslationsScan.php'>TranslationsScan.php</a></b></td>
							<td style='padding: 8px;'>- Facilitates the detection and processing of translation strings across supported languages within the Laravel application<br>- Ensures all active languages are accounted for and triggers background jobs to scan and update translation content, thereby maintaining accurate and up-to-date multilingual support across the codebase.</td>
						</tr>
					</table>
				</blockquote>
			</details>
			<!-- Observers Submodule -->
			<details>
				<summary><b>Observers</b></summary>
				<blockquote>
					<div class='directory-path' style='padding: 8px 0; color: #666;'>
						<code><b>⦿ src.Observers</b></code>
					<table style='width: 100%; border-collapse: collapse;'>
					<thead>
						<tr style='background-color: #f8f9fa;'>
							<th style='width: 30%; text-align: left; padding: 8px;'>File Name</th>
							<th style='text-align: left; padding: 8px;'>Summary</th>
						</tr>
					</thead>
						<tr style='border-bottom: 1px solid #eee;'>
							<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/src/Observers/LanguageObserver.php'>LanguageObserver.php</a></b></td>
							<td style='padding: 8px;'>- Manages language lifecycle events within the translation system, ensuring consistent default and active status across languages<br>- Handles creation, updates, and deletion of language entries, maintaining data integrity and triggering relevant events for system-wide updates<br>- Facilitates seamless language management, supporting dynamic language configurations and ensuring the applications multilingual functionality remains reliable.</td>
						</tr>
					</table>
				</blockquote>
			</details>
			<!-- Managers Submodule -->
			<details>
				<summary><b>Managers</b></summary>
				<blockquote>
					<div class='directory-path' style='padding: 8px 0; color: #666;'>
						<code><b>⦿ src.Managers</b></code>
					<table style='width: 100%; border-collapse: collapse;'>
					<thead>
						<tr style='background-color: #f8f9fa;'>
							<th style='width: 30%; text-align: left; padding: 8px;'>File Name</th>
							<th style='text-align: left; padding: 8px;'>Summary</th>
						</tr>
					</thead>
						<tr style='border-bottom: 1px solid #eee;'>
							<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/src/Managers/TranslatorManager.php'>TranslatorManager.php</a></b></td>
							<td style='padding: 8px;'>- Provides a centralized management interface for selecting and instantiating various translation driver implementations, enabling flexible integration of multiple translation services such as Google, DeepL, and AI-based translators<br>- Facilitates dynamic driver selection based on configuration or runtime input, supporting scalable and adaptable multilingual translation capabilities within the applications architecture.</td>
						</tr>
					</table>
				</blockquote>
			</details>
			<!-- Events Submodule -->
			<details>
				<summary><b>Events</b></summary>
				<blockquote>
					<div class='directory-path' style='padding: 8px 0; color: #666;'>
						<code><b>⦿ src.Events</b></code>
					<table style='width: 100%; border-collapse: collapse;'>
					<thead>
						<tr style='background-color: #f8f9fa;'>
							<th style='width: 30%; text-align: left; padding: 8px;'>File Name</th>
							<th style='text-align: left; padding: 8px;'>Summary</th>
						</tr>
					</thead>
						<tr style='border-bottom: 1px solid #eee;'>
							<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/src/Events/LanguageCodeChanged.php'>LanguageCodeChanged.php</a></b></td>
							<td style='padding: 8px;'>- Represents an event triggered when a language code is updated within the translation management system<br>- It facilitates communication across the application to handle language code changes, ensuring that related components can respond appropriately<br>- This event supports maintaining consistency and synchronization of language data throughout the system architecture.</td>
						</tr>
						<tr style='border-bottom: 1px solid #eee;'>
							<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/src/Events/LanguageDeleted.php'>LanguageDeleted.php</a></b></td>
							<td style='padding: 8px;'>- Represents an event triggered upon the deletion of a language within the translation management system<br>- It facilitates the propagation of language removal actions across the application, ensuring consistent updates and integrations related to language data<br>- This event plays a key role in maintaining data integrity and synchronization within the overall architecture.</td>
						</tr>
						<tr style='border-bottom: 1px solid #eee;'>
							<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/src/Events/LanguageAdded.php'>LanguageAdded.php</a></b></td>
							<td style='padding: 8px;'>- Represents an event signaling the addition of a new language within the translation management system<br>- It facilitates communication across the application when a language is introduced, enabling other components to respond accordingly, such as updating language lists or triggering related workflows<br>- This event supports the modular and event-driven architecture of the translation feature set.</td>
						</tr>
					</table>
				</blockquote>
			</details>
			<!-- Jobs Submodule -->
			<details>
				<summary><b>Jobs</b></summary>
				<blockquote>
					<div class='directory-path' style='padding: 8px 0; color: #666;'>
						<code><b>⦿ src.Jobs</b></code>
					<table style='width: 100%; border-collapse: collapse;'>
					<thead>
						<tr style='background-color: #f8f9fa;'>
							<th style='width: 30%; text-align: left; padding: 8px;'>File Name</th>
							<th style='text-align: left; padding: 8px;'>Summary</th>
						</tr>
					</thead>
						<tr style='border-bottom: 1px solid #eee;'>
							<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/src/Jobs/ScanTranslationStrings.php'>ScanTranslationStrings.php</a></b></td>
							<td style='padding: 8px;'>- Facilitates the extraction, localization, and storage of translation strings across multiple languages within the application<br>- Ensures translation data is up-to-date by scanning source files, retrieving existing translations, and caching results for efficient access<br>- Supports both initial and re-translation workflows, maintaining consistency and accuracy of multilingual content throughout the system.</td>
						</tr>
						<tr style='border-bottom: 1px solid #eee;'>
							<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/src/Jobs/TranslateKeys.php'>TranslateKeys.php</a></b></td>
							<td style='padding: 8px;'>- Handles the translation of pending text entries across specified or all active languages by dispatching translation jobs<br>- It ensures that untranslated strings are processed efficiently, updating their content with translated versions and marking them as completed<br>- This component integrates with the broader localization architecture to maintain up-to-date multilingual content within the application.</td>
						</tr>
					</table>
				</blockquote>
			</details>
			<!-- Base Submodule -->
			<details>
				<summary><b>Base</b></summary>
				<blockquote>
					<div class='directory-path' style='padding: 8px 0; color: #666;'>
						<code><b>⦿ src.Base</b></code>
					<table style='width: 100%; border-collapse: collapse;'>
					<thead>
						<tr style='background-color: #f8f9fa;'>
							<th style='width: 30%; text-align: left; padding: 8px;'>File Name</th>
							<th style='text-align: left; padding: 8px;'>Summary</th>
						</tr>
					</thead>
						<tr style='border-bottom: 1px solid #eee;'>
							<td style='padding: 8px;'><b><a href='https://github.com/backstagephp/laravel-translations/blob/master/src/Base/TranslationLoader.php'>TranslationLoader.php</a></b></td>
							<td style='padding: 8px;'>- Provides a custom translation loader that integrates database-stored translations with file-based ones, ensuring dynamic and up-to-date multilingual support<br>- It enhances the applications architecture by enabling seamless fallback and caching mechanisms for translation strings, thereby improving localization flexibility and performance across different environments.</td>
						</tr>
					</table>
				</blockquote>
			</details>
		</blockquote>
	</details>
</details>

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
$translatedDescription = $modelInstance->getTranslatedAttributes();
```

If needed to get all translated attributes a specific locale use:

```php
$translatedDescription = $modelInstance->getTranslatedAttributes(
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
