<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use ZipArchive;

class BackupFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:files {--type=full : Type of backup (full, incremental)} {--compress=true : Compress the backup file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a backup of application files';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->option('type');
        $compress = $this->option('compress') === 'true';
        
        $this->info("Starting {$type} files backup...");

        try {
            $backupPath = $this->createFilesBackup($type, $compress);
            
            if ($backupPath) {
                $this->info("Files backup created successfully: {$backupPath}");
                
                // Clean up old backups
                $this->cleanupOldBackups();
                
                return Command::SUCCESS;
            } else {
                $this->error('Failed to create files backup');
                return Command::FAILURE;
            }
        } catch (\Exception $e) {
            $this->error('Files backup failed: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    private function createFilesBackup(string $type, bool $compress): ?string
    {
        $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
        $extension = $compress ? 'zip' : 'tar';
        $filename = "backup_files_{$type}_{$timestamp}.{$extension}";
        
        $backupPath = "backups/files/{$filename}";

        // Directories to backup
        $directories = [
            'storage/app/public' => 'storage',
            'storage/app/uploads' => 'uploads',
            'public/images' => 'images',
            'public/css' => 'css',
            'public/js' => 'js',
        ];

        try {
            if ($compress) {
                return $this->createZipBackup($directories, $backupPath);
            } else {
                return $this->createTarBackup($directories, $backupPath);
            }
        } catch (\Exception $e) {
            $this->error("Files backup failed: " . $e->getMessage());
            return null;
        }
    }

    private function createZipBackup(array $directories, string $backupPath): ?string
    {
        $zip = new ZipArchive();
        $tempFile = tempnam(sys_get_temp_dir(), 'backup_');
        
        if ($zip->open($tempFile, ZipArchive::CREATE) !== TRUE) {
            $this->error('Cannot create ZIP file');
            return null;
        }

        foreach ($directories as $sourcePath => $archivePath) {
            if (is_dir($sourcePath)) {
                $this->addDirectoryToZip($zip, $sourcePath, $archivePath);
            }
        }

        $zip->close();
        
        // Move to storage
        $content = file_get_contents($tempFile);
        Storage::put($backupPath, $content);
        unlink($tempFile);
        
        return $backupPath;
    }

    private function addDirectoryToZip(ZipArchive $zip, string $sourcePath, string $archivePath): void
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($sourcePath, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $relativePath = $archivePath . '/' . $iterator->getSubPathName();
                $zip->addFile($file->getRealPath(), $relativePath);
            }
        }
    }

    private function createTarBackup(array $directories, string $backupPath): ?string
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'backup_') . '.tar';
        
        $command = 'tar -cf ' . escapeshellarg($tempFile);
        
        foreach ($directories as $sourcePath => $archivePath) {
            if (is_dir($sourcePath)) {
                $command .= ' -C ' . escapeshellarg(dirname($sourcePath)) . ' ' . escapeshellarg(basename($sourcePath));
            }
        }

        exec($command, $output, $returnCode);
        
        if ($returnCode === 0 && file_exists($tempFile)) {
            $content = file_get_contents($tempFile);
            Storage::put($backupPath, $content);
            unlink($tempFile);
            return $backupPath;
        } else {
            $this->error('TAR command failed');
            return null;
        }
    }

    private function cleanupOldBackups(): void
    {
        $maxBackups = config('backup.max_files', 30);
        $backupPath = 'backups/files/';
        
        $files = Storage::files($backupPath);
        
        if (count($files) > $maxBackups) {
            // Sort files by modification time (oldest first)
            usort($files, function($a, $b) {
                return Storage::lastModified($a) - Storage::lastModified($b);
            });
            
            // Delete oldest files
            $filesToDelete = array_slice($files, 0, count($files) - $maxBackups);
            
            foreach ($filesToDelete as $file) {
                Storage::delete($file);
                $this->info("Deleted old backup: {$file}");
            }
        }
    }
}