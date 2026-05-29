<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;

class PintHelper
{
    /**
     * Check if a PHP file follows PSR-12 standards
     */
    public static function isPsr12Compliant($filePath)
    {
        if (!File::exists($filePath)) {
            return false;
        }
        
        $path = base_path('vendor/bin/pint');
        if (DIRECTORY_SEPARATOR === '\\') {
            $path = base_path('vendor/bin/pint.bat');
        }
        
        exec("\"$path\" --test " . escapeshellarg($filePath) . " 2>&1", $output, $returnCode);
        
        return $returnCode === 0;
    }
    
    /**
     * Get code complexity score (0-100)
     */
    public static function getCodeComplexity($filePath)
    {
        if (!File::exists($filePath)) {
            return 0;
        }
        
        $content = File::get($filePath);
        $lines = explode("\n", $content);
        
        $complexity = 0;
        $nestingLevel = 0;
        
        foreach ($lines as $line) {
            if (preg_match('/(if|for|foreach|while|switch|catch)/', $line)) {
                $complexity++;
                $nestingLevel++;
            }
            if (str_contains($line, '}')) {
                $nestingLevel = max(0, $nestingLevel - 1);
            }
        }
        
        // Normalize to 0-100 scale
        $score = max(0, 100 - ($complexity * 5));
        
        return min(100, $score);
    }
    
    /**
     * Get formatting suggestions
     */
    public static function getFormattingSuggestions($filePath)
    {
        $suggestions = [];
        
        if (!File::exists($filePath)) {
            return $suggestions;
        }
        
        $content = File::get($filePath);
        $lines = explode("\n", $content);
        
        foreach ($lines as $index => $line) {
            $lineNumber = $index + 1;
            
            // Check for trailing spaces
            if (preg_match('/\s+$/', $line)) {
                $suggestions[] = "Line {$lineNumber}: Remove trailing whitespace";
            }
            
            // Check for incorrect indentation (assuming 4 spaces)
            if (preg_match('/^\t/', $line)) {
                $suggestions[] = "Line {$lineNumber}: Use spaces instead of tabs for indentation";
            }
            
            // Check for missing spaces after control structures
            if (preg_match('/\b(if|for|foreach|while|switch)\(/', $line)) {
                $suggestions[] = "Line {$lineNumber}: Add space after control structure keyword";
            }
        }
        
        return $suggestions;
    }
}