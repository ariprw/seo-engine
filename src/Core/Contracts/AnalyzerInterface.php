<?php
namespace Ari\SeoEngine\Core\Contracts;

interface AnalyzerInterface
{
    public function analyze(array $data): array;
}