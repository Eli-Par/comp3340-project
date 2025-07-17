<?php
$files = scandir(__DIR__ . "/../public_html/gallery");

if ($files) {
    $files = array_filter($files, function ($file) {
        return str_ends_with($file,".mp4") || 
            str_ends_with($file, ".png") || 
            str_ends_with($file, ".jpg") ||
            str_ends_with($file, ".jpeg") ||
            str_ends_with($file, ".webp")
        ;
    });
}

header('Content-Type: application/json');
echo json_encode(['success' => true, 'files' => $files]);
?>