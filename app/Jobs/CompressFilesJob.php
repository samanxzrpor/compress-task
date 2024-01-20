<?php

namespace App\Jobs;

use App\Compression\CompressionInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CompressFilesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected CompressionInterface $compression,
        protected string $inputPath,
        protected string $outputPath,
    ){}

    public function handle(): void
    {
        $this->compression->compress($this->inputPath, $this->outputPath);
    }
}
