<?php
namespace Ari\SeoEngine\Core\Analyzers;

use Ari\SeoEngine\Core\Contracts\AnalyzerInterface;

class ReadabilityAnalyzer implements AnalyzerInterface
{
    public function analyze(array $data): array
    {
        $content = $data['content'] ?? '';

        $words = str_word_count(strip_tags($content));
        $sentencesArray = preg_split('/[.!?]+/', $content);
        $totalSentences = max(count($sentencesArray), 1);
        $syllables = $this->countSyllables($content);

        $fleschScore = 206.835 - (1.015 * ($words / $totalSentences)) - (84.6 * ($syllables / $words));

        if ($fleschScore >= 80) $status = 'very good';
        elseif ($fleschScore >= 60) $status = 'good';
        elseif ($fleschScore >= 50) $status = 'fair';
        else $status = 'bad';

        $issues = [];
        if ($status === 'bad' || $status === 'fair') {
            $issues[] = "Artikel terlalu sulit dibaca, pertimbangkan kalimat lebih pendek dan kata sederhana.";
        }

        return [
            'type' => 'readability',
            'score' => round($fleschScore, 2),
            'status' => $status,
            'issues' => $issues,
            'totalWords' => $words,
            'totalSentences' => $totalSentences,
            'totalSyllables' => $syllables,
            'avgWordsPerSentence' => round($words / $totalSentences, 2)
        ];
    }

    private function countSyllables($content)
    {
        $content = strtolower(strip_tags($content));
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
        $word = preg_replace('/[^a-z]/', '', strtolower($word));
        $word = preg_replace('/[aeiouy]{2,}/', 'a', $word);
        preg_match_all('/[aeiouy]/', $word, $matches);
        $count = count($matches[0]);
        return max($count, 1);
    }
}