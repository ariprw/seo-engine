<?php
namespace Ari\SeoEngine\Core\Analyzers;

use Ari\SeoEngine\Core\Contracts\AnalyzerInterface;

class SlugAnalyzer implements AnalyzerInterface
{
    public function analyze(array $data): array
    {
        $slug = trim($data['slug'] ?? '');
        $keyword = strtolower(str_replace(' ', '-', $data['keyword'] ?? ''));

        $issues = [];
        $score = 0;

        if (!empty($keyword) && stripos($slug, $keyword) !== false) {
            $score += 50;
        } else {
            $issues[] = "Slug tidak mengandung focus keyword.";
        }

        if (preg_match('/^[a-z0-9\-]+$/i', $slug)) {
            $score += 50;
        } else {
            $issues[] = "Slug mengandung karakter yang tidak SEO-friendly.";
        }

        if ($score >= 80) {
            $status = 'good';
        } elseif ($score >= 40) {
            $status = 'ok';
        } else {
            $status = 'bad';
        }

        return [
            'type' => 'slug',
            'slug' => $slug,
            'keyword' => $keyword,
            'score' => $score,
            'status' => $status,
            'issues' => $issues
        ];
    }
}