<?php
namespace Ari\SeoEngine\Core\Analyzers;

use Ari\SeoEngine\Core\Contracts\AnalyzerInterface;

class HeadingAnalyzer implements AnalyzerInterface
{
    public function analyze(array $data): array
    {
        $content = $data['content'] ?? '';
        $keyword = strtolower($data['keyword'] ?? '');

        preg_match_all('/<h[1-6]>(.*?)<\/h[1-6]>/', $content, $matches);

        $headings = $matches[1] ?? [];
        $headingCount = count($headings);
        $keywordInHeadings = 0;

        foreach ($headings as $heading) {
            if (stripos($heading, $keyword) !== false) {
                $keywordInHeadings++;
            }
        }

        if ($headingCount == 0) {
            return [
                'type' => 'heading',
                'score' => null,
                'status' => 'bad',
                'issues' => ['Tidak ada heading dalam artikel']
            ];
        }

        if ($keywordInHeadings == 0) {
            return [
                'type' => 'heading',
                'score' => null,
                'status' => 'bad',
                'issues' => ['Focus keyword tidak ditemukan di heading']
            ];
        }

        return [
            'type' => 'heading',
            'score' => $keywordInHeadings,
            'status' => 'good',
            'issues' => []
        ];
    }
}
