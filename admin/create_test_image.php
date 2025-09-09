<?php
// Create a simple test image
$width = 200;
$height = 100;

// Create a blank image
$image = imagecreate($width, $height);

// Colors
$background = imagecolorallocate($image, 255, 255, 255); // white
$text_color = imagecolorallocate($image, 0, 0, 0); // black

// Add some text
imagestring($image, 5, 50, 30, 'TEST IMAGE', $text_color);
imagestring($image, 3, 60, 60, 'For Upload Test', $text_color);

// Save as PNG
$filename = 'test_image.png';
imagepng($image, $filename);
imagedestroy($image);

echo "Test image created: " . $filename . "<br>";
echo "Image size: " . filesize($filename) . " bytes<br>";
echo "<img src='" . $filename . "' alt='Test Image'>";
?>
