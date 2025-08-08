<?php

namespace App\Console\Commands;

use App\Models\Paste;
use Illuminate\Console\Command;

class CleanupExpiredPastes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pastes:cleanup {--dry-run : Show what would be deleted without actually deleting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up expired pastes from the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredPastes = Paste::where('expires_at', '<', now())->get();
        
        if ($expiredPastes->isEmpty()) {
            $this->info('No expired pastes found.');
            return 0;
        }
        
        if ($this->option('dry-run')) {
            $this->info("Found {$expiredPastes->count()} expired pastes (dry run):");
            foreach ($expiredPastes as $paste) {
                $this->line("- {$paste->slug} (expired: {$paste->expires_at->diffForHumans()})");
            }
            return 0;
        }
        
        $count = $expiredPastes->count();
        
        if ($this->confirm("Are you sure you want to delete {$count} expired pastes?")) {
            Paste::where('expires_at', '<', now())->delete();
            $this->info("Successfully deleted {$count} expired pastes.");
        } else {
            $this->info('Cleanup cancelled.');
        }
        
        return 0;
    }
}
