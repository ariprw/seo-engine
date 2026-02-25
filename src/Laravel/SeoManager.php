<?php
namespace Ari\SeoEngine\Laravel;

use Ari\SeoEngine\Core\Engine\SeoEngine;

class SeoManager
{
    protected $seoEngine;

    public function __construct(SeoEngine $seoEngine)
    {
        $this->seoEngine = $seoEngine;
    }

    public function analyze($post)
    {
        return $this->seoEngine->analyze($post);
    }
}