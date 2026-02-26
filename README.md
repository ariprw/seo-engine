# SEO Engine

**Hybrid SEO Engine** untuk **PHP & Laravel**.
Bisa dipakai di PHP native atau langsung di Laravel sebagai package reusable.  

---

## Instalasi

### Via Composer (Laravel)

```bash
composer require ari/seo-engine
```

### PHP
```bash
composer require ari/seo-engine
```


## Konfigurasi

### Publikasikan config di Laravel:
```bash
php artisan vendor:publish --tag=seo-config
```

### File konfigurasi: config/seo.php
```bash
return [
    'min_words_for_analysis' => 400,
    'ideal_density_min' => 0.8,
    'ideal_density_max' => 2.0,
];
```


## Cara Pakai

### Di Laravel
```bash
use Ari\SeoEngine\Laravel\Facades\Seo;

$post = [
    'baseUrl' => 'https://contoh.com',
    'slug' => 'judul-artikel',
    'title' => 'Judul Artikel',
    'content' => 'Konten...',
    'keyword' => 'seo',
    'metaDescription' => 'Judul artikel adalah...',
];

$result = Seo::analyze((object) $post);

print_r($result);
```

### Di PHP
```bash
require 'vendor/autoload.php';

use Ari\SeoEngine\Core\Engine\SeoEngine;
use Ari\SeoEngine\Core\Analyzers\KeywordAnalyzer;

$seoEngine = new SeoEngine();
$seoEngine->addAnalyzer(new KeywordAnalyzer());

$result = $seoEngine->analyze([
    'baseUrl' => 'https://contoh.com',
    'slug' => 'judul-artikel',
    'title' => 'Judul Artikel',
    'content' => 'Konten...',
    'keyword' => 'seo',
    'metaDescription' => 'Judul artikel adalah...',
]);

print_r($result);
```
