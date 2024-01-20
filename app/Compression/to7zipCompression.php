<?php

namespace App\Compression;

use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Phar;
use PharData;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use ZipArchive;

class to7zipCompression implements CompressionInterface
{
    /**
     * @throws Exception
     */
    public function compress(string $inputPath, string $outputPath): void
    {
        $process = new Process(['7z', 'a', $outputPath . '.7z', Storage::path($inputPath)]);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    }
}
