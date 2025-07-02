<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\Message;

class QueueMonitor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queue:monitor-chat {--refresh=5 : Refresh interval in seconds}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Monitor chat queue system in real-time';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $refreshInterval = (int) $this->option('refresh');
        
        $this->info('Chat Queue Monitor Started');
        $this->info('Press Ctrl+C to stop monitoring');
        $this->newLine();
        
        while (true) {
            $this->clearScreen();
            $this->displayHeader();
            $this->displayQueueStats();
            $this->displayRecentMessages();
            $this->displayRateLimitStats();
            
            sleep($refreshInterval);
        }
    }
    
    private function clearScreen()
    {
        if (PHP_OS_FAMILY === 'Windows') {
            system('cls');
        } else {
            system('clear');
        }
    }
    
    private function displayHeader()
    {
        $this->info('=== CHAT QUEUE MONITOR ===');
        $this->info('Time: ' . now()->format('Y-m-d H:i:s'));
        $this->newLine();
    }
    
    private function displayQueueStats()
    {
        $this->info('📊 QUEUE STATISTICS');
        $this->line('─────────────────────');
        
        // Pending jobs
        $pendingJobs = DB::table('jobs')->count();
        $this->line("Pending Jobs: {$pendingJobs}");
        
        // Failed jobs
        $failedJobs = DB::table('failed_jobs')->count();
        $this->line("Failed Jobs: {$failedJobs}");
        
        // Queue connection
        $queueConnection = config('queue.default');
        $this->line("Queue Driver: {$queueConnection}");
        
        $this->newLine();
    }
    
    private function displayRecentMessages()
    {
        $this->info('💬 RECENT MESSAGES (Last 5)');
        $this->line('─────────────────────────');
        
        $recentMessages = Message::with(['sender', 'receiver'])
            ->latest()
            ->take(5)
            ->get();
            
        if ($recentMessages->isEmpty()) {
            $this->line('No messages found');
        } else {
            foreach ($recentMessages as $message) {
                $time = $message->created_at->format('H:i:s');
                $sender = $message->sender->name ?? 'Unknown';
                $receiver = $message->receiver->name ?? 'Unknown';
                $text = $message->message ? substr($message->message, 0, 30) . '...' : '[Image]';
                
                $this->line("{$time} | {$sender} → {$receiver}: {$text}");
            }
        }
        
        $this->newLine();
    }
    
    private function displayRateLimitStats()
    {
        $this->info('🚦 RATE LIMIT STATUS');
        $this->line('─────────────────────');
        
        // Get active rate limits from cache
        $rateLimitKeys = [];
        
        // This is a simplified version - in production you might want to use Redis SCAN
        for ($userId = 1; $userId <= 10; $userId++) {
            $key = 'chat_rate_limit_' . $userId;
            $attempts = Cache::get($key);
            
            if ($attempts) {
                $rateLimitKeys[] = "User {$userId}: {$attempts}/30 messages";
            }
        }
        
        if (empty($rateLimitKeys)) {
            $this->line('No active rate limits');
        } else {
            foreach ($rateLimitKeys as $limit) {
                $this->line($limit);
            }
        }
        
        $this->newLine();
        $this->line('Refresh every ' . $this->option('refresh') . ' seconds...');
    }
}
