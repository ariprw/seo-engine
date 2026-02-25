<?php
namespace Ari\SeoEngine\Core\Analyzers;

use Ari\SeoEngine\Core\Contracts\AnalyzerInterface;

class KeywordAnalyzer implements AnalyzerInterface
{
    public function analyze(array $data): array
    {
        $keyword = strtolower($data['keyword'] ?? '');
        $content = strtolower(strip_tags($data['content'] ?? ''));

        $count = substr_count($content, $keyword);
        $totalWords = str_word_count($content);
        $density = $totalWords > 0 ? ($count / $totalWords) * 100 : 0;
        $minWords = config('seo.min_words_for_analysis', 400);

        if ($totalWords < $minWords) {
            return [
                'type' => 'keyword',
                'keywordCount' => $count,
                'density' => round($density, 2),
                'status' => 'bad',
                'message' => "Artikel kurang dari {$minWords} kata, analisis keyword belum akurat."
            ];
        }

        return [
            'type' => 'keyword',
            'keywordCount' => $count,
            'density' => round($density, 2),
            'status' => $density >= 0.8 && $density <= 2 ? 'good' : 'bad'
        ];
    }
}