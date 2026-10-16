<?php

/**
 * Cloud Sahaja — Helper Functions
 */

if (!function_exists('formatBytes')) {
    /**
     * Convert bytes ke human-readable format.
     * Contoh: 1536 → "1.5 KB"
     */
    function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
