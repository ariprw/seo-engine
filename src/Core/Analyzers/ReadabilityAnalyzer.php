<?php
namespace Ari\SeoEngine\Core\Analyzers;

use Ari\SeoEngine\Core\Contracts\AnalyzerInterface;

class ReadabilityAnalyzer implements AnalyzerInterface
{
    public function analyze(array $data): array
    {
        $content = $data['content'] ?? '';

        $words = str_word_count($content);
        $sentences = substr_count($content, '.');
        $syllables = $this->countSyllables($content);

        $fleschScore = 206.835 - (1.015 * ($words / $sentences)) - (84.6 * ($syllables / $words));

        $status = $fleschScore >= 60 ? 'good' : 'bad';

        return [
            'type' => 'readability',
            'score' => round($fleschScore, 2),
            'status' => $status,
            'issues' => $status === 'bad' ? ['Artikel terlalu sulit dibaca'] : []
        ];
    }

    private function countSyllables($content)
    {
        $content = strtolower($content);
        $content = preg_replace('/[^a-z\s]/', '', $content);
        $words = explode(' ', $content);

        $syllableCount = 0;

        foreach ($words as $word) {
            $syllableCount += $this->syllableInWord($word);
        }

        return $syllableCount;
    }

    private function syllableInWord($word)
    {
        $syllables = 0;
        $vowels = ['a', 'e', 'i', 'o', 'u'];

        for ($i = 0; $i < strlen($word); $i++) {
            if (in_array($word[$i], $vowels)) {
                $syllables++;
            }
        }

        return $syllables;
    }
}