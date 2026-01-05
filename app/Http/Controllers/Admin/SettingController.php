<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class SettingController extends Controller
{
    public function index()
    {
        // Get settings from database with fallback to config
        $settings = [
            'site_name' => Setting::getValue('site_name', config('app.name', 'Dapur Sakura')),
            'site_description' => Setting::getValue('site_description', config('app.description', 'Restoran Jepang dan Lokal Terbaik')),
            'site_email' => Setting::getValue('site_email', config('mail.from.address', 'admin@dapursakura.com')),
            'site_phone' => Setting::getValue('site_phone', config('app.phone', '+62 812-3456-7890')),
            'site_address' => Setting::getValue('site_address', config('app.address', 'Jl. Contoh No. 123, Jakarta')),
            'site_logo' => Setting::getValue('site_logo', config('app.logo', '/images/logo.png')),
            'maintenance_mode' => $this->getBooleanSetting('maintenance_mode', config('app.maintenance', false)),
            'registration_enabled' => $this->getBooleanSetting('registration_enabled', config('app.registration_enabled', true)),
            'email_verification_required' => $this->getBooleanSetting('email_verification_required', config('app.email_verification_required', false)),
            'max_file_size' => Setting::getValue('max_file_size', config('app.max_file_size', '2048')),
            'allowed_file_types' => Setting::getValue('allowed_file_types', config('app.allowed_file_types', 'jpg,jpeg,png,gif')),
            'timezone' => Setting::getValue('timezone', config('app.timezone', 'Asia/Jakarta')),
            'notification_email' => Setting::getValue('notification_email', config('mail.notification_email', 'notifications@dapursakura.com')),
            'support_email' => Setting::getValue('support_email', config('mail.support_email', 'support@dapursakura.com')),
            'social_media' => [
                'facebook' => Setting::getValue('social_facebook', config('app.social.facebook', '')),
                'instagram' => Setting::getValue('social_instagram', config('app.social.instagram', '')),
                'twitter' => Setting::getValue('social_twitter', config('app.social.twitter', '')),
                'whatsapp' => Setting::getValue('social_whatsapp', config('app.social.whatsapp', '')),
            ],
        ];

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Helper method to get boolean setting value
     */
    private function getBooleanSetting(string $key, $default = false): bool
    {
        $value = Setting::getValue($key, $default);
        if ($value === null) {
            return (bool)$default;
        }
        return $value === '1' || $value === true || $value === 'true';
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'required|string|max:500',
            'site_email' => 'required|email|max:255',
            'site_phone' => 'required|string|max:20',
            'site_address' => 'required|string|max:500',
            'maintenance_mode' => 'boolean',
            'registration_enabled' => 'boolean',
            'email_verification_required' => 'boolean',
            'max_file_size' => 'required|integer|min:100|max:10240',
            'allowed_file_types' => 'required|string',
            'timezone' => 'required|string',
            'notification_email' => 'required|email|max:255',
            'support_email' => 'required|email|max:255',
            'social_facebook' => 'nullable|url',
            'social_instagram' => 'nullable|url',
            'social_twitter' => 'nullable|url',
            'social_whatsapp' => 'nullable|string',
        ]);

        // Handle logo upload
        if ($request->hasFile('site_logo')) {
            $logo = $request->file('site_logo');
            $logoName = 'logo-' . time() . '.' . $logo->getClientOriginalExtension();
            $logo->storeAs('public/images', $logoName);
            $validated['site_logo'] = '/storage/images/' . $logoName;
        }

        // Save settings to database
        $settingsToSave = [
            'site_name' => $validated['site_name'],
            'site_description' => $validated['site_description'],
            'site_email' => $validated['site_email'],
            'site_phone' => $validated['site_phone'],
            'site_address' => $validated['site_address'],
            'maintenance_mode' => $request->has('maintenance_mode') ? '1' : '0',
            'registration_enabled' => $request->has('registration_enabled') ? '1' : '0',
            'email_verification_required' => $request->has('email_verification_required') ? '1' : '0',
            'max_file_size' => (string)$validated['max_file_size'],
            'allowed_file_types' => $validated['allowed_file_types'],
            'timezone' => $validated['timezone'],
            'notification_email' => $validated['notification_email'],
            'support_email' => $validated['support_email'],
            'social_facebook' => $validated['social_facebook'] ?? '',
            'social_instagram' => $validated['social_instagram'] ?? '',
            'social_twitter' => $validated['social_twitter'] ?? '',
            'social_whatsapp' => $validated['social_whatsapp'] ?? '',
        ];

        // Add logo if uploaded
        if (isset($validated['site_logo'])) {
            $settingsToSave['site_logo'] = $validated['site_logo'];
        }

        // Save all settings
        Setting::setMultiple($settingsToSave);

        // Clear settings cache
        Setting::clearCache();

        return redirect()->route('admin.settings.index')->with('status', 'Pengaturan berhasil diperbarui!');
    }

    public function testEmail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email'
        ]);

        try {
            // Send test email
            \Mail::raw('Ini adalah email test dari Dapur Sakura Admin Panel.', function ($message) use ($request) {
                $message->to($request->test_email)
                        ->subject('Test Email - Dapur Sakura');
            });

            return response()->json([
                'success' => true,
                'message' => 'Email test berhasil dikirim ke ' . $request->test_email
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim email test: ' . $e->getMessage()
            ], 500);
        }
    }

    public function clearCache()
    {
        try {
            \Artisan::call('config:clear');
            \Artisan::call('cache:clear');
            \Artisan::call('view:clear');
            \Artisan::call('route:clear');

            return response()->json([
                'success' => true,
                'message' => 'Cache berhasil dibersihkan!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membersihkan cache: ' . $e->getMessage()
            ], 500);
        }
    }

    public function backupDatabase()
    {
        try {
            // Get database configuration
            $connection = config('database.default');
            $config = config("database.connections.{$connection}");
            
            if ($config['driver'] !== 'mysql') {
                return response()->json([
                    'success' => false,
                    'message' => 'Backup database saat ini hanya mendukung MySQL'
                ], 400);
            }

            $timestamp = now()->format('Y-m-d_H-i-s');
            $filename = "backup_{$timestamp}.sql";
            $backupPath = "backups/database/{$filename}";
            
            // Create backup directory if it doesn't exist
            if (!\Storage::exists('backups/database')) {
                \Storage::makeDirectory('backups/database');
            }

            // Build mysqldump command with proper escaping
            $host = escapeshellarg($config['host']);
            $port = $config['port'] ?? 3306;
            $database = escapeshellarg($config['database']);
            $username = escapeshellarg($config['username']);
            $password = escapeshellarg($config['password']);

            // Use environment variable for password to avoid command line exposure
            $env = [
                'MYSQL_PWD' => $config['password']
            ];

            $command = sprintf(
                'mysqldump --host=%s --port=%d --user=%s --single-transaction --routines --triggers --events %s',
                $host,
                $port,
                $username,
                $database
            );

            // Execute command with environment variables
            $descriptorspec = [
                0 => ['pipe', 'r'],
                1 => ['pipe', 'w'],
                2 => ['pipe', 'w']
            ];

            $process = proc_open($command, $descriptorspec, $pipes, null, $env);

            if (!is_resource($process)) {
                throw new \Exception('Failed to start backup process');
            }

            // Read output
            $output = stream_get_contents($pipes[1]);
            $errors = stream_get_contents($pipes[2]);

            // Close pipes
            fclose($pipes[0]);
            fclose($pipes[1]);
            fclose($pipes[2]);

            $returnCode = proc_close($process);

            if ($returnCode !== 0) {
                throw new \Exception('Backup command failed: ' . ($errors ?: 'Unknown error'));
            }

            if (empty($output)) {
                throw new \Exception('Backup output is empty');
            }

            // Save backup file
            \Storage::put($backupPath, $output);

            // Get file size
            $fileSize = \Storage::size($backupPath);
            $fileSizeFormatted = $this->formatBytes($fileSize);

            // Clean up old backups (keep last 30)
            $this->cleanupOldBackups();

                return response()->json([
                    'success' => true,
                    'message' => 'Database backup berhasil dibuat!',
                'filename' => $filename,
                'path' => $backupPath,
                'size' => $fileSizeFormatted,
                'download_url' => route('admin.settings.download-backup', ['filename' => $filename])
                ]);
        } catch (\Exception $e) {
            \Log::error('Database backup failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat backup database: ' . $e->getMessage()
            ], 500);
        }
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }

    private function cleanupOldBackups()
    {
        $maxBackups = 30; // Keep last 30 backups
        $backupPath = 'backups/database/';
        
        if (!\Storage::exists($backupPath)) {
            return;
        }

        $files = \Storage::files($backupPath);
        
        if (count($files) > $maxBackups) {
            // Sort files by modification time (oldest first)
            usort($files, function($a, $b) {
                return \Storage::lastModified($a) - \Storage::lastModified($b);
            });
            
            // Delete oldest files
            $filesToDelete = array_slice($files, 0, count($files) - $maxBackups);
            
            foreach ($filesToDelete as $file) {
                \Storage::delete($file);
            }
        }
    }

    public function downloadBackup($filename)
    {
        try {
            // Validate filename to prevent directory traversal
            $filename = basename($filename);
            
            if (empty($filename) || !preg_match('/^backup_\d{4}-\d{2}-\d{2}_\d{2}-\d{2}-\d{2}\.sql$/', $filename)) {
                abort(404, 'Invalid backup filename');
            }
            
            $backupPath = "backups/database/{$filename}";
            
            if (!\Storage::exists($backupPath)) {
                abort(404, 'Backup file not found');
            }

            return \Storage::download($backupPath, $filename);
        } catch (\Exception $e) {
            \Log::error('Backup download failed: ' . $e->getMessage());
            abort(500, 'Gagal mengunduh backup: ' . $e->getMessage());
        }
    }
}
