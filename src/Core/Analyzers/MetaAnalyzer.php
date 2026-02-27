<?php
namespace Ari\SeoEngine\Core\Analyzers;

use Ari\SeoEngine\Core\Contracts\AnalyzerInterface;

class MetaAnalyzer implements AnalyzerInterface
{
    public function analyze(array $data): array
    {
        $metaDescription = trim($data['metaDescription'] ?? '');
        $keyword = strtolower($data['keyword'] ?? '');

        $issues = [];
        $score = 0;

        if (empty($metaDescription)) {
            $issues[] = "Artikel tidak memiliki meta description.";
        } else {
            $score += 30;
        }

        $length = strlen($metaDescription);
        if ($length < 120) {
            $issues[] = "Meta description terlalu pendek ({$length} karakter). Ideal 120–160 karakter.";
        } elseif ($length > 160) {
            $issues[] = "Meta description terlalu panjang ({$length} karakter). Ideal 120–160 karakter.";
        } else {
            $score += 20;
        }

        $containsKeyword = !empty($keyword) && stripos($metaDescription, $keyword) !== false;
        if ($containsKeyword) {
            $score += 50;
        } else {
            $issues[] = "Meta description tidak mengandung focus keyword.";
        }

        if ($score >= 70) {
            $status = 'good';
        } elseif ($score >= 40) {
            $status = 'ok';
        } else {
            $status = 'bad';
        }

        return [
            'type' => 'meta',
            'metaLength' => $length,
            'containsKeyword' => $containsKeyword,
            'score' => $score,
            'status' => $status,
            'issues' => $issues
        ];
    }
}
