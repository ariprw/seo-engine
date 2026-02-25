<?php
namespace Ari\SeoEngine\Core\Analyzers;

use Ari\SeoEngine\Core\Contracts\AnalyzerInterface;

class LinkAnalyzer implements AnalyzerInterface
{
    public function analyze(array $data): array
    {
        $content = $data['content'] ?? '';
        $baseUrl = $data['baseUrl'] ?? '';

        preg_match_all('/<a href="([^"]+)"/', $content, $matches);
        $links = $matches[1] ?? [];
        $internalLinks = 0;
        $externalLinks = 0;

        foreach ($links as $link) {
            if (strpos($link, $baseUrl) === 0) {
                $internalLinks++;
            } else {
                if (filter_var($link, FILTER_VALIDATE_URL) && (strpos($link, 'http://') === 0 || strpos($link, 'https://') === 0)) {
                    $externalLinks++;
                }
            }
        }

        $status = 'good';
        $issues = [];

        if ($internalLinks < 1) {
            $status = 'bad';
            $issues[] = 'Jumlah link internal kurang dari 1. Tambahkan lebih banyak link internal untuk meningkatkan SEO.';
        }

        if ($externalLinks < 1) {
            $status = 'bad';
            $issues[] = 'Jumlah link eksternal kurang dari 1. Tambahkan lebih banyak link eksternal untuk meningkatkan SEO.';
        }

        return [
            'type' => 'link',
            'internalLinks' => $internalLinks,
            'externalLinks' => $externalLinks,
            'status' => $status,
            'issues' => $issues
        ];
    }
}
