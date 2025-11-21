<?php

namespace Backstage\Translations\Laravel\Drivers;

use Backstage\Translations\Laravel\Contracts\TranslatorContract;
use Prism\Prism\Facades\Prism;

class AITranslator implements TranslatorContract
{
    public function translate(string | array $text, string $targetLanguage, ?string $extraPrompt = null): string | array
    {
        if (is_array($text)) {
            return $this->translateJson($text, $targetLanguage, $extraPrompt);
        }

        $systemPromptLines = [
            config('translations.translators.drivers.ai.system_prompt'),

            'You are a professional translation engine. Your sole task is to translate raw input text into the specified target language, accurately and precisely, without any form of commentary, confirmation, clarification, or explanation.',
            '',
            'Your response must follow these strict rules at all times:',
            '',
            '1. Only output the translated text. No headings, introductions, explanations, or metadata. For example, do NOT say:',
            '   - "Certainly!"',
            '   - "The translation of..."',
            '   - "Here is the translation:"',
            '   - "(\'Inloggen\' is already in Dutch.)"',
            '   - "The key %key in Dutch could be..."',
            '   - "Translated to [language]:"',
            '   - "This text is already in..."',
            '',
            '2. Never repeat the input text or key name. Just return the translated string itself.',
            '',
            '3. Do not guess or assume formatting intentions. Preserve:',
            '   - All punctuation (.,!?: etc.)',
            '   - Line breaks and spacing',
            '   - HTML tags and structure',
            '   - Markdown, quotes, dashes, and ellipses',
            '   - Capitalization and tone (e.g., commands, questions, titles)',
            '',
            '4. Do NOT translate variables. These are words starting with a colon (:), such as:',
            '   - :name',
            '   - :count',
            '   - :user_email',
            '',
            '   Leave them exactly as-is. For example:',
            '   - Input: Hello, :name!',
            '   - Output (to German): Hallo, :name!',
            '',
            '5. Do NOT alter or explain language behavior. Even if the input is already in the target language, do not say so—just return it unchanged.',
            '',
            "6. Do NOT interpret keys like %button_label unless they are part of the text itself. If you're translating `%manage_subscription_button_label: Abonnement beheren`, only translate the value (`Abonnement beheren`), unless instructed otherwise.",
            '',
            '7. Only respond with the translated string. No code blocks, no markdown, no quotes. Just the raw translated text, nothing else.',
            '',
            '8. Follow cultural and grammatical conventions of the target language. Ensure fluency, natural tone, and proper grammar.',
            '',
            'You are NOT a chatbot. You are NOT a teacher. You are a silent, efficient translation tool.',
            '"9. You may also receive an entire JSON object or array. When this happens:",
            "   - ONLY translate the **values**, not the **keys**.",
            "   - Preserve all JSON structure, indentation, and formatting.",
            "   - Do NOT reformat, wrap, or explain the JSON.",
            "   - Return only the modified JSON as raw text—no code blocks, no comments, no extra output.",
            "   - Keep all colon-prefixed variables like `:count` intact inside values.",',
        ];

        $instructions = [];

        if ($extraPrompt) {
            $instructions[] = $extraPrompt;
        }

        $instructions[] = "Translate the following text to {$targetLanguage}.";
        $instructions[] = 'Preserve punctuation, tone, and special characters.';
        $instructions[] = 'Do NOT translate colon-prefixed variables (e.g., :name).';
        $instructions[] = 'Only return the translated sentence—no commentary.';
        $instructions[] = "Text: {$text}";

        $systemPrompt = implode("\n", $systemPromptLines);

        $instructionsString = implode("\n", $instructions);

        $response = Prism::text()
            ->withClientOptions([
                'timeout' => 600,
                'text_output_only' => true,
            ])
            ->withProviderOptions([
                'reasoning' => ['effort' => 'minimal'],
            ])
            ->withClientRetry(4, 100)
            ->using(config('translations.translators.drivers.ai.provider'), config('translations.translators.drivers.ai.model'))
            ->withSystemPrompt($systemPrompt)
            ->withPrompt($instructionsString)
            ->asText();

        return trim($response->text);
    }

    protected function translateJson(array $toBeJson, string $targetLanguage, ?string $extraPrompt = null): array
    {
        $systemPromptLines = [
            'You are a professional translation engine. Your sole task is to translate raw input values of a JSON object into the specified target language.',
            '',
            'Follow these rules:',
            '',
            '1. Only translate the values, NOT the keys.',
            '2. Preserve variables prefixed with a colon (e.g., :name, :count).',
            '3. Maintain formatting, HTML, punctuation, and spacing exactly.',
            '4. Do NOT add comments, notes, explanations, or code blocks.',
            '5. Respond with only the translated JSON object.',
            '',
            'Example Input:',
            '{ "welcome": "Welcome, :name!", "logout": "Log out" }',
            '',
            'Example Output (in French):',
            '{ "welcome": "Bienvenue, :name!", "logout": "Se déconnecter" }',
        ];

        $systemPrompt = implode("\n", $systemPromptLines);

        $json = json_encode($toBeJson, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        $prompt = <<<PROMPT
                Translate the following JSON object to {$targetLanguage}.
                Only translate the values.
                Keep all keys, punctuation, and formatting exactly as-is.
                Return a valid JSON object. Do not include any notes or explanations.

                JSON:
                {$json}
            PROMPT;

        if ($extraPrompt) {
            $prompt = $extraPrompt . "\n\n" . $prompt;
        }

        $response = Prism::text()
            ->using(config('translations.translators.drivers.ai.provider'), config('translations.translators.drivers.ai.model'))
            ->withSystemPrompt($systemPrompt)
            ->withPrompt($prompt)
            ->asText();

        $translated = trim($response->text);

        json_decode($translated);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Invalid JSON returned from AI translation.');
        }

        return json_decode($translated, true);
    }
}
