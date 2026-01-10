<?php
class UrlHelper {
    /**
     * Get the base URL of the application
     * @return string The base URL
     */
    public static function baseUrl() {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'];
        $baseUrl = $protocol . $host;
        
        // Get the script name and determine the base path
        $scriptName = $_SERVER['SCRIPT_NAME'];
        $basePath = dirname($scriptName);
        
        // Handle the case where the application is in a subdirectory
        if ($basePath !== '/') {
            $baseUrl .= $basePath;
        }
        
        // Ensure the base URL ends with a slash
        if (substr($baseUrl, -1) !== '/') {
            $baseUrl .= '/';
        }
        
        return $baseUrl;
    }
    
    /**
     * Generate a URL relative to the application base
     * @param string $path The path to append to the base URL
     * @return string The complete URL
     */
    public static function url($path = '') {
        $baseUrl = self::baseUrl();
        
        // Remove leading slash from path if present
        if (strlen($path) > 0 && $path[0] === '/') {
            $path = substr($path, 1);
        }
        
        // If path is empty, return base URL
        if (empty($path)) {
            return $baseUrl;
        }
        
        return $baseUrl . $path;
    }
    
    /**
     * Get the current URL path without the base
     * @return string The current path
     */
    public static function getCurrentPath() {
        $requestUri = $_SERVER['REQUEST_URI'];
        $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
        
        // Remove the script directory from the request URI
        $relativePath = str_replace($scriptDir, '', $requestUri);
        
        // Remove query parameters
        $relativePath = explode('?', $relativePath)[0];
        
        // Remove leading slash
        if (strlen($relativePath) > 0 && $relativePath[0] === '/') {
            $relativePath = substr($relativePath, 1);
        }
        
        return $relativePath;
    }
}
?>