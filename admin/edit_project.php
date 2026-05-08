<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/content_helpers.php';
require_once __DIR__ . '/admin_auth.php';

requireAdminAuth();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: manage_projects.php');
    exit;
}

$error = null;
$project = null;

try {
    $connection = getDbConnection();
    $statement = $connection->prepare('SELECT id, title, description, image_path, date FROM projects WHERE id = ?');
    $statement->bind_param('i', $id);
    $statement->execute();
    $result = $statement->get_result();
    $project = $result->fetch_assoc();
    $statement->close();

    if (!$project) {
        $connection->close();
        header('Location: manage_projects.php');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $date = trim($_POST['date'] ?? '');

        if ($title === '' || $description === '' || $date === '') {
            $error = 'Title, description, and date are required.';
        } else {
            $newImagePath = $project['image_path'];
            $uploadedImagePath = null;

            try {
                $uploadedImagePath = uploadPostImage($_FILES['image'] ?? []);
                if ($uploadedImagePath !== null) {
                    $newImagePath = $uploadedImagePath;
                }

                $updateStatement = $connection->prepare('UPDATE projects SET title = ?, description = ?, image_path = ?, date = ? WHERE id = ?');
                $updateStatement->bind_param('ssssi', $title, $description, $newImagePath, $date, $id);
                $updateStatement->execute();
                $updateStatement->close();

                if ($uploadedImagePath !== null) {
                    removePostImage($project['image_path']);
                }

                $connection->close();
                header('Location: manage_projects.php?status=updated');
                exit;
            } catch (Throwable $exception) {
                if ($uploadedImagePath !== null) {
                    removePostImage($uploadedImagePath);
                }
                $error = $exception->getMessage();
            }
        }
    }

    $connection->close();
} catch (Throwable $exception) {
    $error = $exception->getMessage();
}

$pageTitle = 'Edit Project - Renz Alvarez';
$activePage = 'project';
require_once __DIR__ . '/../includes/header.php';
?>
<main class="projects-section">
    <div class="projects-container">
        <div class="projects-header">
            <h2 class="projects-title">Edit Project</h2>
            <p class="projects-subtitle">// Update your portfolio content</p>
        </div>

        <?php if ($error): ?>
            <p class="crud-message crud-message-error"><?= e($error) ?></p>
        <?php endif; ?>

        <?php if ($project): ?>
            <section class="crud-form-wrapper">
                <form method="post" enctype="multipart/form-data" class="crud-form">
                    <label class="crud-label" for="title">Title</label>
                    <input class="crud-input" id="title" name="title" type="text" value="<?= e($project['title']) ?>" required>

                    <label class="crud-label" for="description">Description</label>
                    <textarea class="crud-textarea" id="description" name="description" rows="5" required><?= e($project['description']) ?></textarea>

                    <label class="crud-label" for="date">Date</label>
                    <input class="crud-input" id="date" name="date" type="date" value="<?= e($project['date']) ?>" required>

                    <label class="crud-label" for="image">Replace Image (optional)</label>
                    <input class="crud-input" id="image" name="image" type="file" accept="image/*">

                    <?php if (!empty($project['image_path'])): ?>
                        <p class="crud-label">Current Image</p>
                        <img class="crud-thumb crud-thumb-large" src="<?= e(asset_url($project['image_path'])) ?>" alt="<?= e($project['title']) ?>">
                    <?php endif; ?>

                    <div class="crud-actions-row">
                        <button class="project-btn project-btn-primary" type="submit">Save Changes</button>
                        <a class="project-btn project-btn-secondary" href="manage_projects.php">Back to Manage Projects</a>
                    </div>
                </form>
            </section>
        <?php endif; ?>
    </div>
</main>
<?php
$footerYear = 2024;
require_once __DIR__ . '/../includes/footer.php';
