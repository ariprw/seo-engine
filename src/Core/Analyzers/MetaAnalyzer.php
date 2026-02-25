<?php
namespace Ari\SeoEngine\Core\Analyzers;

use Ari\SeoEngine\Core\Contracts\AnalyzerInterface;

class MetaAnalyzer implements AnalyzerInterface
{
    public function analyze(array $data): array
    {
        $metaDescription = $data['metaDescription'] ?? '';
        $keyword = strtolower($data['keyword'] ?? '');

        if (empty($metaDescription)) {
            return [
                'type' => 'meta',
                'status' => 'bad',
                'message' => 'Meta description tidak ditemukan.',
                'issues' => ['Artikel tidak memiliki meta description.']
            ];
        }

        $containsKeyword = stripos($metaDescription, $keyword) !== false;

        return [
            'type' => 'meta',
            'status' => $containsKeyword ? 'good' : 'bad',
            'message' => $containsKeyword ? 'Meta description mengandung focus keyword.' : 'Meta description tidak mengandung focus keyword.',
            'issues' => $containsKeyword ? [] : ['Meta description tidak mengandung focus keyword.']
        ];
    }
}
