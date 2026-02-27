<?php
namespace Ari\SeoEngine\Core\Analyzers;

use Ari\SeoEngine\Core\Contracts\AnalyzerInterface;

class ImageAnalyzer implements AnalyzerInterface
{
    public function analyze(array $data): array
    {
        $content = $data['content'] ?? '';
        $keyword = strtolower($data['keyword'] ?? '');

        preg_match_all('/<img[^>]+alt="([^"]*)"/i', $content, $matches);
        $images = $matches[1] ?? [];
        $totalImages = preg_match_all('/<img[^>]+>/i', $content);

        $keywordInAlt = 0;
        $imagesWithoutAlt = 0;

        foreach ($images as $altText) {
            if (trim($altText) === '') {
                $imagesWithoutAlt++;
            }
            if (stripos($altText, $keyword) !== false) {
                $keywordInAlt++;
            }
        }

        $imagesWithoutAlt += max($totalImages - count($images), 0);

        $score = 0;
        $issues = [];

        if ($totalImages === 0) {
            $issues[] = "Artikel tidak memiliki gambar.";
        } else {
            $score += 20;
        }

        if ($keywordInAlt > 0) {
            $score += 40;
        } else {
            $issues[] = "Tidak ada gambar dengan alt text yang mengandung focus keyword.";
        }

        if ($imagesWithoutAlt > 0) {
            $issues[] = "Ada {$imagesWithoutAlt} gambar tanpa alt text.";
            $score -= min(20, $imagesWithoutAlt * 5);
        }

        if ($score >= 50) $status = 'good';
        elseif ($score >= 20) $status = 'ok';
        else $status = 'bad';

        return [
            'type' => 'image',
            'totalImages' => $totalImages,
            'keywordInAlt' => $keywordInAlt,
            'imagesWithoutAlt' => $imagesWithoutAlt,
            'score' => $score,
            'status' => $status,
            'issues' => $issues
        ];
    }
}