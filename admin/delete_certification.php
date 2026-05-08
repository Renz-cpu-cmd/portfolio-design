<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/content_helpers.php';
require_once __DIR__ . '/admin_auth.php';

requireAdminAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: manage_certifications.php');
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: manage_certifications.php');
    exit;
}

try {
    $connection = getDbConnection();

    $findStatement = $connection->prepare('SELECT image_path FROM certifications WHERE id = ?');
    $findStatement->bind_param('i', $id);
    $findStatement->execute();
    $result = $findStatement->get_result();
    $certification = $result->fetch_assoc();
    $findStatement->close();

    if ($certification) {
        $deleteStatement = $connection->prepare('DELETE FROM certifications WHERE id = ?');
        $deleteStatement->bind_param('i', $id);
        $deleteStatement->execute();
        $deleteStatement->close();

        removePostImage($certification['image_path']);
    }

    $connection->close();

    header('Location: manage_certifications.php?status=deleted');
    exit;
} catch (Throwable $exception) {
    header('Location: manage_certifications.php');
    exit;
}
