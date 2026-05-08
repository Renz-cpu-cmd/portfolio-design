<?php

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function asset_url(?string $path): ?string
{
    if (!$path) {
        return null;
    }

    // If already absolute (starts with / or http), return as-is
    if (strpos($path, '/') === 0 || preg_match('#^https?://#i', $path)) {
        return $path;
    }

    // Normalize to project root under /portfolio/
    return '/portfolio/' . ltrim($path, '/');
}

function uploadPostImage(array $file): ?string
{
    if (!isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Image upload failed. Please try again.');
    }

    if (($file['size'] ?? 0) > 2 * 1024 * 1024) {
        throw new RuntimeException('Image is too large. Maximum size is 2MB.');
    }

    $mimeType = mime_content_type($file['tmp_name']);
    $allowedTypes = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/webp' => 'webp',
    ];

    if (!isset($allowedTypes[$mimeType])) {
        throw new RuntimeException('Only JPG, PNG, GIF, and WEBP images are allowed.');
    }

    $uploadDir = __DIR__ . '/../uploads';
    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0777, true)) {
        throw new RuntimeException('Could not create uploads folder.');
    }

    $filename = uniqid('post_', true) . '.' . $allowedTypes[$mimeType];
    $absolutePath = $uploadDir . '/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $absolutePath)) {
        throw new RuntimeException('Could not save uploaded image.');
    }

    return '/portfolio/uploads/' . $filename;
}

function removePostImage(?string $relativePath): void
{
    if (!$relativePath) {
        return;
    }

    // Only delete files inside the project's uploads folder.
    if (strpos($relativePath, '/portfolio/uploads/') !== 0 && strpos($relativePath, 'uploads/') !== 0) {
        return;
    }
    // Normalize relative to project uploads directory
    if (strpos($relativePath, '/portfolio/uploads/') === 0) {
        $relativePath = substr($relativePath, strlen('/portfolio/'));
    }

    $absolutePath = __DIR__ . '/../' . $relativePath;
    if (is_file($absolutePath)) {
        unlink($absolutePath);
    }
}
