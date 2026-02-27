<?php
namespace Ari\SeoEngine\Core\Analyzers;

use Ari\SeoEngine\Core\Contracts\AnalyzerInterface;

class LinkAnalyzer implements AnalyzerInterface
{
    public function analyze(array $data): array
    {
        $content = $data['content'] ?? '';
        $baseUrl = rtrim($data['baseUrl'] ?? '', '/');
        
        preg_match_all('/<a\s+href="([^"]+)"/i', $content, $matches);
        $links = $matches[1] ?? [];

        $internalLinks = 0;
        $externalLinks = 0;

        foreach ($links as $link) {
            $link = trim($link);
            if (!$link) continue;

            if (strpos($link, $baseUrl) === 0) {
                $internalLinks++;
            } elseif (filter_var($link, FILTER_VALIDATE_URL)) {
                $externalLinks++;
            }
        }

        $score = 0;
        $issues = [];

        if ($internalLinks >= 1) {
            $score += min(50, $internalLinks * 10);
        } else {
            $issues[] = "Jumlah link internal kurang dari 1. Tambahkan lebih banyak link internal untuk meningkatkan SEO.";
        }

        if ($externalLinks >= 1) {
            $score += min(50, $externalLinks * 10);
        } else {
            $issues[] = "Jumlah link eksternal kurang dari 1. Tambahkan lebih banyak link eksternal untuk meningkatkan SEO.";
        }

        if ($score >= 70) $status = 'good';
        elseif ($score >= 30) $status = 'ok';
        else $status = 'bad';

        return [
            'type' => 'link',
            'totalLinks' => count($links),
            'internalLinks' => $internalLinks,
            'externalLinks' => $externalLinks,
            'score' => $score,
            'status' => $status,
            'issues' => $issues
        ];
    }
}
