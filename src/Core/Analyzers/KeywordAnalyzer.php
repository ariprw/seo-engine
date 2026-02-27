<?php
namespace Ari\SeoEngine\Core\Analyzers;

use Ari\SeoEngine\Core\Contracts\AnalyzerInterface;

class KeywordAnalyzer implements AnalyzerInterface
{
    public function analyze(array $data): array
    {
        $keyword = strtolower($data['keyword'] ?? '');
        $title = strtolower($data['title'] ?? '');
        $meta = strtolower($data['metaDescription'] ?? '');
        $content = strtolower(strip_tags(html_entity_decode($data['content'] ?? '')));

        $words = str_word_count($content, 1);
        $totalWords = count($words);

        $keywordCount = count(array_filter($words, fn($w) => $w === $keyword));
        $density = $totalWords > 0 ? ($keywordCount / $totalWords) * 100 : 0;

        $minWords = config('seo.min_words_for_analysis', 400);

        $inTitle = $keyword && stripos($title, $keyword) !== false;
        $inMeta = $keyword && stripos($meta, $keyword) !== false;

        $sentences = preg_split('/[.!?]/', $content);
        $avgWordsPerSentence = $totalWords / max(count($sentences), 1);
        $readability = $avgWordsPerSentence <= 20 ? 'good' : 'bad';

        $minDensity = config('seo.min_density', 1);
        $maxDensity = config('seo.max_density', 3);

        $score = 0;
        if ($inTitle) $score += 30;
        if ($inMeta) $score += 20;
        if ($density >= $minDensity && $density <= $maxDensity) $score += 30;
        if ($readability === 'good') $score += 20;

        $status = $score >= 70 ? 'good' : ($score >= 40 ? 'ok' : 'bad');

        $issues = [];
        if ($totalWords < $minWords) {
            $issues[] = "Artikel kurang dari {$minWords} kata, analisis keyword belum akurat.";
        }
        if (!$inTitle) $issues[] = "Keyword tidak ada di judul.";
        if (!$inMeta) $issues[] = "Keyword tidak ada di meta description.";
        if ($density < $minDensity) $issues[] = "Keyword terlalu sedikit, tambahkan kata kunci lebih sering.";
        if ($density > $maxDensity) $issues[] = "Keyword terlalu padat, kurangi penggunaan kata kunci.";
        if ($readability === 'bad') $issues[] = "Kalimat terlalu panjang, buat rata-rata < 20 kata per kalimat.";

        return [
            'type' => 'keyword',
            'keywordCount' => $keywordCount,
            'density' => round($density, 2),
            'totalWords' => $totalWords,
            'inTitle' => $inTitle,
            'inMetaDescription' => $inMeta,
            'avgWordsPerSentence' => round($avgWordsPerSentence, 2),
            'readability' => $readability,
            'score' => $score,
            'status' => $status,
            'issues' => $issues
        ];
    }
}