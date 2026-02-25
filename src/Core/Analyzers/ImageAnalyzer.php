<?php
namespace Ari\SeoEngine\Core\Analyzers;

use Ari\SeoEngine\Core\Contracts\AnalyzerInterface;

class ImageAnalyzer implements AnalyzerInterface
{
    public function analyze(array $data): array
    {
        $content = $data['content'] ?? '';
        preg_match_all('/<img[^>]+alt="([^"]*)"/', $content, $matches);
        $images = $matches[1] ?? [];
        $keyword = strtolower($data['keyword'] ?? '');

        $keywordInAlt = 0;
        foreach ($images as $altText) {
            if (stripos($altText, $keyword) !== false) {
                $keywordInAlt++;
            }
        }

        return [
            'type' => 'image',
            'keywordInAlt' => $keywordInAlt,
            'status' => $keywordInAlt > 0 ? 'good' : 'bad',
            'issues' => $keywordInAlt > 0 ? [] : ['Tidak ada gambar dengan alt text yang mengandung focus keyword.']
        ];
    }
}