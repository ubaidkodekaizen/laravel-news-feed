<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\S3Service;
use ReflectionClass;

class CheckFfmpeg extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ffmpeg:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if FFmpeg is installed and accessible';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking FFmpeg installation...');
        $this->newLine();

        // Check using S3Service method
        $s3Service = app(S3Service::class);
        $reflection = new ReflectionClass($s3Service);
        $method = $reflection->getMethod('getFfmpegPath');
        $method->setAccessible(true);
        $ffmpegPath = $method->invoke($s3Service);

        if ($ffmpegPath) {
            $this->info("✅ FFmpeg found at: {$ffmpegPath}");
            
            // Test FFmpeg version
            exec("{$ffmpegPath} -version 2>&1", $output, $returnCode);
            
            if ($returnCode === 0) {
                $this->info("✅ FFmpeg is working correctly!");
                $this->newLine();
                $this->line("Version information:");
                $this->line(implode("\n", array_slice($output, 0, 3)));
            } else {
                $this->error("❌ FFmpeg found but not working correctly.");
                $this->line("Error: " . implode("\n", $output));
            }
        } else {
            $this->error("❌ FFmpeg not found!");
            $this->newLine();
            $this->line("Please install FFmpeg:");
            $this->line("1. Download from: https://www.gyan.dev/ffmpeg/builds/");
            $this->line("2. Extract to C:\\ffmpeg\\ or C:\\xampp\\ffmpeg\\");
            $this->line("3. Add to PATH or install in one of the checked locations");
            $this->newLine();
            $this->line("Checked locations:");
            $this->line("- In PATH (ffmpeg command)");
            $this->line("- C:\\ffmpeg\\bin\\ffmpeg.exe");
            $this->line("- C:\\xampp\\ffmpeg\\bin\\ffmpeg.exe");
            $this->line("- " . base_path('ffmpeg/bin/ffmpeg.exe'));
        }

        return $ffmpegPath ? 0 : 1;
    }
}
