<?php
class ImageHelper {
    private static $imageDir = 'images/';
    private static $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
    /**
     * Get all available images from the images directory
     */
    public static function getAllImages() {
        $images = [];
        $imagePath = dirname(__DIR__, 2) . '/' . self::$imageDir;
        
        if (is_dir($imagePath)) {
            $files = scandir($imagePath);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                    if (in_array($extension, self::$allowedExtensions)) {
                        $images[] = $file;
                    }
                }
            }
        }
        
        return $images;
    }
    
    /**
     * Get a random image from the images directory
     */
    public static function getRandomImage() {
        $images = self::getAllImages();
        if (empty($images)) {
            return null;
        }
        
        return $images[array_rand($images)];
    }
    
    /**
     * Get multiple random images
     */
    public static function getRandomImages($count = 3) {
        $images = self::getAllImages();
        if (empty($images)) {
            return [];
        }
        
        // Shuffle and take the requested count
        shuffle($images);
        return array_slice($images, 0, min($count, count($images)));
    }
    
    /**
     * Get images by type/pattern
     */
    public static function getImagesByPattern($pattern = '') {
        $images = self::getAllImages();
        if (empty($pattern)) {
            return $images;
        }
        
        return array_filter($images, function($image) use ($pattern) {
            return strpos(strtolower($image), strtolower($pattern)) !== false;
        });
    }
    
    /**
     * Get school building images (sch*.jpg pattern)
     */
    public static function getSchoolImages() {
        return self::getImagesByPattern('sch');
    }
    
    /**
     * Get student activity images (sd*.jpg pattern)  
     */
    public static function getStudentImages() {
        return self::getImagesByPattern('sd');
    }
    
    /**
     * Get a random hero image
     */
    public static function getRandomHeroImage() {
        // Prefer school building images for hero
        $schoolImages = self::getSchoolImages();
        if (!empty($schoolImages)) {
            return $schoolImages[array_rand($schoolImages)];
        }
        
        // Fallback to any random image
        return self::getRandomImage();
    }
    
    /**
     * Get images for gallery or carousel
     */
    public static function getGalleryImages($count = 6) {
        $allImages = self::getAllImages();
        if (empty($allImages)) {
            return [];
        }
        
        // Mix school and student images
        shuffle($allImages);
        return array_slice($allImages, 0, min($count, count($allImages)));
    }
    
    /**
     * Get the full URL path for an image
     */
    public static function getImageUrl($filename) {
        if (empty($filename)) {
            return '';
        }
        
        // Return relative path from web root
        return self::$imageDir . $filename;
    }
    
    /**
     * Check if an image exists
     */
    public static function imageExists($filename) {
        if (empty($filename)) {
            return false;
        }
        
        $imagePath = dirname(__DIR__, 2) . '/' . self::$imageDir . $filename;
        return file_exists($imagePath);
    }
    
    /**
     * Get image info (dimensions, size, etc.)
     */
    public static function getImageInfo($filename) {
        if (!self::imageExists($filename)) {
            return null;
        }
        
        $imagePath = dirname(__DIR__, 2) . '/' . self::$imageDir . $filename;
        $info = getimagesize($imagePath);
        $filesize = filesize($imagePath);
        
        return [
            'filename' => $filename,
            'width' => $info[0] ?? 0,
            'height' => $info[1] ?? 0,
            'mime_type' => $info['mime'] ?? '',
            'size' => $filesize,
            'size_formatted' => self::formatBytes($filesize),
            'url' => self::getImageUrl($filename)
        ];
    }
    
    /**
     * Format bytes to human readable format
     */
    private static function formatBytes($bytes, $precision = 2) {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
    
    /**
     * Get random placeholder image for content
     */
    public static function getPlaceholderImage($type = 'general') {
        switch ($type) {
            case 'hero':
            case 'banner':
                return self::getRandomHeroImage();
                
            case 'school':
            case 'building':
                $schoolImages = self::getSchoolImages();
                return !empty($schoolImages) ? $schoolImages[array_rand($schoolImages)] : self::getRandomImage();
                
            case 'student':
            case 'activity':
                $studentImages = self::getStudentImages();
                return !empty($studentImages) ? $studentImages[array_rand($studentImages)] : self::getRandomImage();
                
            default:
                return self::getRandomImage();
        }
    }
    
    /**
     * Generate image gallery HTML
     */
    public static function generateGalleryHtml($images, $cssClass = 'gallery-item') {
        if (empty($images)) {
            return '';
        }
        
        $html = '';
        foreach ($images as $image) {
            $info = self::getImageInfo($image);
            if ($info) {
                $html .= sprintf(
                    '<img src="%s" alt="Gallery Image" class="%s" loading="lazy">',
                    htmlspecialchars($info['url']),
                    htmlspecialchars($cssClass)
                );
            }
        }
        
        return $html;
    }
    
    /**
     * Get cached random images (to avoid different images on each page load)
     */
    public static function getCachedRandomImages($key, $count = 3, $ttl = 3600) {
        $cacheFile = sys_get_temp_dir() . '/image_cache_' . md5($key) . '.json';
        
        // Check if cache exists and is still valid
        if (file_exists($cacheFile)) {
            $cacheData = json_decode(file_get_contents($cacheFile), true);
            if ($cacheData && (time() - $cacheData['timestamp']) < $ttl) {
                return $cacheData['images'];
            }
        }
        
        // Generate new random images
        $images = self::getRandomImages($count);
        
        // Save to cache
        $cacheData = [
            'timestamp' => time(),
            'images' => $images
        ];
        file_put_contents($cacheFile, json_encode($cacheData));
        
        return $images;
    }
}
?>
