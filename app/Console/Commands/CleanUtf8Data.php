<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CleanUtf8Data extends Command
{
    protected $signature = 'chat:clean-utf8';
    protected $description = 'Clean malformed UTF-8 characters from chat messages and user data';

    public function handle()
    {
        $this->info('Starting UTF-8 data cleanup...');
        
        $this->cleanMessages();
        $this->cleanUsers();
        
        $this->info('UTF-8 data cleanup completed!');
    }
    
    private function cleanMessages()
    {
        $this->info('Cleaning message data...');
        
        $messages = Message::all();
        $cleanedCount = 0;
        
        foreach ($messages as $message) {
            $originalMessage = $message->message;
            $originalImageUrl = $message->image_url;
            
            $cleanedMessage = $this->aggressiveUtf8Clean($originalMessage);
            $cleanedImageUrl = $this->aggressiveUtf8Clean($originalImageUrl);
            
            if ($originalMessage !== $cleanedMessage || $originalImageUrl !== $cleanedImageUrl) {
                $message->update([
                    'message' => $cleanedMessage,
                    'image_url' => $cleanedImageUrl
                ]);
                $cleanedCount++;
                $this->line("Cleaned message ID: {$message->id}");
            }
        }
        
        $this->info("Cleaned {$cleanedCount} messages.");
    }
    
    private function cleanUsers()
    {
        $this->info('Cleaning user data...');
        
        $users = User::all();
        $cleanedCount = 0;
        
        foreach ($users as $user) {
            $originalName = $user->name;
            $originalPhoto = $user->profile_photo;
            
            $cleanedName = $this->aggressiveUtf8Clean($originalName);
            $cleanedPhoto = $this->aggressiveUtf8Clean($originalPhoto);
            
            if ($originalName !== $cleanedName || $originalPhoto !== $cleanedPhoto) {
                $user->update([
                    'name' => $cleanedName,
                    'profile_photo' => $cleanedPhoto
                ]);
                $cleanedCount++;
                $this->line("Cleaned user ID: {$user->id}");
            }
        }
        
        $this->info("Cleaned {$cleanedCount} users.");
    }
    
    private function aggressiveUtf8Clean($input)
    {
        if ($input === null || $input === '') {
            return $input;
        }
        
        try {
            // Strategy 1: Standard UTF-8 conversion
            $cleaned = mb_convert_encoding($input, 'UTF-8', 'UTF-8');
            
            // Strategy 2: Remove control characters
            $cleaned = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $cleaned);
            
            // Strategy 3: Remove non-UTF-8 sequences
            $cleaned = preg_replace('/[\x80-\xFF]+/', '', $cleaned);
            
            // Strategy 4: Force UTF-8 encoding from multiple sources
            if (!mb_check_encoding($cleaned, 'UTF-8')) {
                // Try different source encodings
                $encodings = ['UTF-8', 'ISO-8859-1', 'Windows-1252', 'ASCII'];
                foreach ($encodings as $encoding) {
                    try {
                        $test = mb_convert_encoding($input, 'UTF-8', $encoding);
                        if (mb_check_encoding($test, 'UTF-8')) {
                            $cleaned = $test;
                            break;
                        }
                    } catch (\Exception $e) {
                        continue;
                    }
                }
            }
            
            // Strategy 5: Final fallback - keep only ASCII characters
            if (!mb_check_encoding($cleaned, 'UTF-8')) {
                $cleaned = preg_replace('/[^\x20-\x7E]/', '', $input);
            }
            
            // Strategy 6: Ultimate fallback - return safe placeholder
            if (!mb_check_encoding($cleaned, 'UTF-8')) {
                return '[Content contains invalid characters]';
            }
            
            return $cleaned;
            
        } catch (\Exception $e) {
            $this->error('UTF-8 cleaning failed: ' . $e->getMessage());
            return '[Content could not be processed]';
        }
    }
}