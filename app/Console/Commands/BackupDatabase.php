<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:database {--type=full : Type of backup (full, incremental)} {--compress=true : Compress the backup file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a backup of the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->option('type');
        $compress = $this->option('compress') === 'true';
        
        $this->info("Starting {$type} database backup...");

        try {
            $backupPath = $this->createBackup($type, $compress);
            
            if ($backupPath) {
                $this->info("Database backup created successfully: {$backupPath}");
                
                // Clean up old backups
                $this->cleanupOldBackups();
                
                return Command::SUCCESS;
            } else {
                $this->error('Failed to create database backup');
                return Command::FAILURE;
            }
        } catch (\Exception $e) {
            $this->error('Backup failed: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    private function createBackup(string $type, bool $compress): ?string
    {
        $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
        $filename = "backup_{$type}_{$timestamp}.sql";
        
        if ($compress) {
            $filename .= '.gz';
        }

        $backupPath = "backups/database/{$filename}";

        // Get database configuration
        $connection = config('database.default');
        $config = config("database.connections.{$connection}");

        if ($config['driver'] === 'sqlite') {
            return $this->backupSqlite($config, $backupPath, $compress);
        } elseif ($config['driver'] === 'mysql') {
            return $this->backupMysql($config, $backupPath, $compress);
        } else {
            $this->error("Unsupported database driver: {$config['driver']}");
            return null;
        }
    }

    private function backupSqlite(array $config, string $backupPath, bool $compress): ?string
    {
        $databasePath = $config['database'];
        
        if (!file_exists($databasePath)) {
            $this->error("SQLite database file not found: {$databasePath}");
            return null;
        }

        try {
            // Copy the SQLite file
            $content = file_get_contents($databasePath);
            
            if ($compress) {
                $content = gzencode($content, 9);
            }
            
            Storage::put($backupPath, $content);
            
            return $backupPath;
        } catch (\Exception $e) {
            $this->error("SQLite backup failed: " . $e->getMessage());
            return null;
        }
    }

    private function backupMysql(array $config, string $backupPath, bool $compress): ?string
    {
        $host = $config['host'];
        $port = $config['port'];
        $database = $config['database'];
        $username = $config['username'];
        $password = $config['password'];

        $command = "mysqldump --host={$host} --port={$port} --user={$username}";
        
        if ($password) {
            $command .= " --password={$password}";
        }
        
        $command .= " --single-transaction --routines --triggers {$database}";
        
        if ($compress) {
            $command .= " | gzip";
        }

        try {
            $output = shell_exec($command);
            
            if ($output) {
                Storage::put($backupPath, $output);
                return $backupPath;
            } else {
                $this->error('mysqldump command failed');
                return null;
            }
        } catch (\Exception $e) {
            $this->error("MySQL backup failed: " . $e->getMessage());
            return null;
        }
    }

    private function cleanupOldBackups(): void
    {
        $maxBackups = config('backup.max_files', 30);
        $backupPath = 'backups/database/';
        
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