<?php

namespace App\Compression;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Phar;
use PharData;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use ZipArchive;

class toTarGzCompression implements CompressionInterface
{
    /**
     * @throws Exception
     */
    public function compress(string $inputPath, string $outputPath): void
    {
        $process = new Process(['tar', '-zcvf', $outputPath . '.tar.gz', Storage::path($inputPath)]);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    }
}
