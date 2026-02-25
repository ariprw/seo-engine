<?php
namespace Ari\SeoEngine\Core\Analyzers;

use Ari\SeoEngine\Core\Contracts\AnalyzerInterface;

class SlugAnalyzer implements AnalyzerInterface
{
    public function analyze(array $data): array
    {
        $slug = $data['slug'] ?? '';
        $keyword = strtolower(str_replace(' ', '-', $data['keyword'] ?? ''));

        if (stripos($slug, $keyword) === false) {
            return [
                'type' => 'slug',
                'status' => 'bad',
                'message' => 'Slug tidak mengandung focus keyword.',
                'issues' => ['Slug tidak SEO-friendly.']
            ];
        }

        return [
            'type' => 'slug',
            'status' => 'good',
            'message' => 'Slug mengandung focus keyword.',
            'issues' => []
        ];
    }
}
