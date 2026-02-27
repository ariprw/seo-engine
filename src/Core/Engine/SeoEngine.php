<?php
namespace Ari\SeoEngine\Core\Engine;

use Ari\SeoEngine\Core\Analyzers\KeywordAnalyzer;
use Ari\SeoEngine\Core\Analyzers\ReadabilityAnalyzer;
use Ari\SeoEngine\Core\Analyzers\MetaAnalyzer;
use Ari\SeoEngine\Core\Analyzers\HeadingAnalyzer;
use Ari\SeoEngine\Core\Analyzers\LinkAnalyzer;
use Ari\SeoEngine\Core\Analyzers\ImageAnalyzer;
use Ari\SeoEngine\Core\Analyzers\SlugAnalyzer;

class SeoEngine
{
    protected $analyzers = [];

    public function __construct()
    {
        $this->analyzers = [
            new KeywordAnalyzer(),
            new ReadabilityAnalyzer(),
            new MetaAnalyzer(),
            new HeadingAnalyzer(),
            new LinkAnalyzer(),
            new ImageAnalyzer(),
            new SlugAnalyzer()
        ];
    }
    
    protected function mapStatusToColor(string $status): string
    {
        return match($status) {
            'good' => '#00b894',
            'ok'   => '#fdcb6e',
            'bad'  => '#d63031',
            default => '#b2bec3',
        };
    }

    public function analyze(object $post): array
    {
        $results = [];

        $data = [
            'baseUrl' => $post->baseUrl ?? '',
            'slug' => $post->slug ?? '',
            'title' => $post->title ?? '',
            'content' => $post->content ?? '',
            'keyword' => $post->keyword ?? '',
            'metaDescription' => $post->metaDescription ?? '',
        ];

        foreach ($this->analyzers as $analyzer) {
            $result = $analyzer->analyze($data);

            $result['color'] = $this->mapStatusToColor($result['status']);

            $results[$result['type']] = $result;
        }

        return $results;
    }
}