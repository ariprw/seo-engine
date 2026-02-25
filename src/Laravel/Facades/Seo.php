<?php
namespace Ari\SeoEngine\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

class Seo extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'seo';
    }
}