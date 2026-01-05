<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DeleteUnverifiedUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:delete-unverified 
                            {--force : Force delete without confirmation}
                            {--dry-run : Show what would be deleted without actually deleting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hapus semua user yang belum terverifikasi email (email_verified_at is null)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get all unverified users
        $unverifiedUsers = User::whereNull('email_verified_at')->get();
        
        $count = $unverifiedUsers->count();
        
        if ($count === 0) {
            $this->info('Tidak ada user yang belum terverifikasi.');
            return 0;
        }
        
        // Show users that will be deleted
        $this->info("Ditemukan {$count} user yang belum terverifikasi:");
        $this->newLine();
        
        $tableData = [];
        foreach ($unverifiedUsers as $user) {
            $tableData[] = [
                'ID' => $user->id,
                'Nama' => $user->name,
                'Email' => $user->email,
                'Tanggal Daftar' => $user->created_at->format('d/m/Y H:i'),
                'Role' => $user->roles->pluck('name')->join(', ') ?: 'Tidak ada role'
            ];
        }
        
        $this->table(
            ['ID', 'Nama', 'Email', 'Tanggal Daftar', 'Role'],
            $tableData
        );
        
        // Dry run mode
        if ($this->option('dry-run')) {
            $this->warn('DRY RUN MODE: Tidak ada user yang dihapus.');
            $this->info("Total user yang akan dihapus: {$count}");
            return 0;
        }
        
        // Confirmation
        if (!$this->option('force')) {
            if (!$this->confirm("Apakah Anda yakin ingin menghapus {$count} user yang belum terverifikasi?")) {
                $this->info('Operasi dibatalkan.');
                return 0;
            }
        }
        
        // Delete users
        $this->info('Menghapus user...');
        $bar = $this->output->createProgressBar($count);
        $bar->start();
        
        $deletedCount = 0;
        $errors = [];
        
        DB::beginTransaction();
        try {
            foreach ($unverifiedUsers as $user) {
                try {
                    // Detach all roles first
                    $user->roles()->detach();
                    
                    // Delete user (this will cascade delete related records if configured)
                    $user->delete();
                    $deletedCount++;
                    $bar->advance();
                } catch (\Exception $e) {
                    $errors[] = "Error menghapus user {$user->email}: " . $e->getMessage();
                    $bar->advance();
                }
            }
            
            DB::commit();
            $bar->finish();
            $this->newLine(2);
            
            if ($deletedCount > 0) {
                $this->info("✓ Berhasil menghapus {$deletedCount} user yang belum terverifikasi.");
            }
            
            if (count($errors) > 0) {
                $this->newLine();
                $this->error('Beberapa error terjadi:');
                foreach ($errors as $error) {
                    $this->error("  - {$error}");
                }
            }
            
        } catch (\Exception $e) {
            DB::rollBack();
            $bar->finish();
            $this->newLine(2);
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}
