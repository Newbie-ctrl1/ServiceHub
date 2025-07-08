<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProfilePhotoService
{
    /**
     * Convert binary profile photo to safe format for broadcasting
     */
    public static function getSafeProfilePhoto($profilePhoto)
    {
        if (!$profilePhoto) {
            return null;
        }
        
        // If it's already a safe string (URL or path), return as is
        if (is_string($profilePhoto) && mb_check_encoding($profilePhoto, 'UTF-8')) {
            // Check if it's a valid URL
            if (filter_var($profilePhoto, FILTER_VALIDATE_URL)) {
                return $profilePhoto;
            }
            
            // Check if it's a valid file path
            if (strpos($profilePhoto, '/') !== false || strpos($profilePhoto, '\\') !== false) {
                return $profilePhoto;
            }
        }
        
        // For binary data, return null to avoid encoding issues in broadcasts
        return null;
    }
    
    /**
     * Convert binary profile photo to data URL for display
     */
    public static function getProfilePhotoDataUrl($profilePhoto, $userId = null)
    {
        if (!$profilePhoto) {
            return null;
        }
        
        // If it's already a URL/path, return as is
        if (is_string($profilePhoto) && mb_check_encoding($profilePhoto, 'UTF-8')) {
            if (filter_var($profilePhoto, FILTER_VALIDATE_URL) || 
                strpos($profilePhoto, '/') !== false || 
                strpos($profilePhoto, '\\') !== false) {
                return $profilePhoto;
            }
        }
        
        // If it's binary data, convert to base64 data URL
        try {
            // Detect image type (default to jpeg)
            $imageType = 'jpeg';
            if (substr($profilePhoto, 0, 4) === '\x89PNG') {
                $imageType = 'png';
            } elseif (substr($profilePhoto, 0, 3) === 'GIF') {
                $imageType = 'gif';
            } elseif (substr($profilePhoto, 0, 4) === 'RIFF') {
                $imageType = 'webp';
            }
            
            return 'data:image/' . $imageType . ';base64,' . base64_encode($profilePhoto);
        } catch (\Exception $e) {
            Log::warning('Failed to convert profile photo to data URL' . ($userId ? ' for user ' . $userId : ''), [
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }
    
    /**
     * Validate if data is safe for JSON encoding
     */
    public static function isSafeForJson($data)
    {
        if ($data === null) {
            return true;
        }
        
        if (is_string($data)) {
            return mb_check_encoding($data, 'UTF-8');
        }
        
        return true;
    }
    
    /**
     * Clean data for safe broadcasting
     */
    public static function cleanForBroadcast($data)
    {
        if ($data === null) {
            return null;
        }
        
        if (is_string($data)) {
            // Check if it's valid UTF-8
            if (!mb_check_encoding($data, 'UTF-8')) {
                return null;
            }
            
            // Remove any control characters except newlines and tabs
            $data = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $data);
            
            return $data;
        }
        
        return $data;
    }
}