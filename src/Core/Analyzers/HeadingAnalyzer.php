<?php
namespace Ari\SeoEngine\Core\Analyzers;

use Ari\SeoEngine\Core\Contracts\AnalyzerInterface;

class HeadingAnalyzer implements AnalyzerInterface
{
    public function analyze(array $data): array
    {
        $content = $data['content'] ?? '';
        $keyword = strtolower($data['keyword'] ?? '');

        preg_match_all('/<h([1-6])>(.*?)<\/h\1>/', $content, $matches, PREG_SET_ORDER);

        $headingCount = count($matches);
        $keywordInHeadings = 0;
        $h1HasKeyword = false;
        $h2h3HasKeyword = 0;

        foreach ($matches as $match) {
            $level = intval($match[1]);
            $text = strtolower($match[2]);

            if (stripos($text, $keyword) !== false) {
                $keywordInHeadings++;
                if ($level === 1) $h1HasKeyword = true;
                if ($level === 2 || $level === 3) $h2h3HasKeyword++;
            }
        }

        $issues = [];
        $score = 0;

        if ($headingCount === 0) {
            $issues[] = "Tidak ada heading dalam artikel";
        } else {
            $score += 10;
        }

        if (!$h1HasKeyword) {
            $issues[] = "Focus keyword tidak ditemukan di H1";
        } else {
            $score += 40;
        }

        if ($h2h3HasKeyword === 0) {
            $issues[] = "Focus keyword tidak ditemukan di subheading H2/H3";
        } else {
            $score += min(50, $h2h3HasKeyword * 10);
        }

        if ($score >= 70) $status = 'good';
        elseif ($score >= 40) $status = 'ok';
        else $status = 'bad';

        return [
            'type' => 'heading',
            'totalHeadings' => $headingCount,
            'keywordInHeadings' => $keywordInHeadings,
            'h1HasKeyword' => $h1HasKeyword,
            'h2h3HasKeyword' => $h2h3HasKeyword,
            'score' => $score,
            'status' => $status,
            'issues' => $issues
        ];
    }
}
