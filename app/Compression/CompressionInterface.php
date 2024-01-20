<?php

namespace App\Compression;

interface CompressionInterface
{
    public function compress(string $inputPath, string $outputPath): void;
}
