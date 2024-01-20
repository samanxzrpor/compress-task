<?php

namespace App\Http\Controllers\Api;

use App\Compression\CompressionInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Files\CompressionRequest;
use App\Jobs\CompressFilesJob;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CompressionController extends Controller
{
    public function __construct(protected CompressionInterface $compression){}

    public function compress(CompressionRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $this->getDataToCompress($request->validated());
        try {
            CompressFilesJob::dispatch($this->compression, $data['inputPath'], $data['outputPath']);
            return response()->json([
                'message' => 'File compression request generated.',
                'path'    => $data['downloadUrl'],
            ], 202);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function download(string $fileName): BinaryFileResponse|string
    {
        $format = Str::afterLast($fileName, '.') == 'gz' ? 'tar.gz' : Str::afterLast($fileName, '.');
        $filePath = storage_path('app/public/' . $format) . '/' . $fileName;
        $headers = array(
            'Content-Type: application/pdf',
        );
        if (file_exists($filePath)) {
            return response()->download($filePath, $fileName, $headers);
        } else {
            return 'No file to download';
        }
    }

    private function getDataToCompress(array $data): array
    {
        $inputFile  = $data['file'];
        $toFormat   = $data['format'] ?? config('compress.default_format');
        $filename   = pathinfo($inputFile->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time() . '_' . rand(1000, 9999);
        $inputPath  = Storage::put('files', $inputFile);
        if (!file_exists(storage_path('app/public/' . $toFormat . '/'))) {
            mkdir(storage_path('app/public/' . $toFormat . '/'));
        }
        $outputPath = $inputFile ? storage_path('app/public/' . $toFormat . '/') . $filename : '';
        $downloadUrl = url('api/files/download') . '/' . $filename . '.' . $toFormat;

        return [
            'inputPath' => $inputPath,
            'outputPath' => $outputPath,
            'downloadUrl' => $downloadUrl
        ];
    }
}
