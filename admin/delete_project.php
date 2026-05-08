<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/content_helpers.php';
require_once __DIR__ . '/admin_auth.php';

requireAdminAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: manage_projects.php');
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: manage_projects.php');
    exit;
}

try {
    $connection = getDbConnection();

    $findStatement = $connection->prepare('SELECT image_path FROM projects WHERE id = ?');
    $findStatement->bind_param('i', $id);
    $findStatement->execute();
    $result = $findStatement->get_result();
    $project = $result->fetch_assoc();
    $findStatement->close();

    if ($project) {
        $deleteStatement = $connection->prepare('DELETE FROM projects WHERE id = ?');
        $deleteStatement->bind_param('i', $id);
        $deleteStatement->execute();
        $deleteStatement->close();

        removePostImage($project['image_path']);
    }

    $connection->close();

    header('Location: manage_projects.php?status=deleted');
    exit;
} catch (Throwable $exception) {
    header('Location: manage_projects.php');
    exit;
}
