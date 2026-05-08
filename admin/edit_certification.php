<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/content_helpers.php';
require_once __DIR__ . '/admin_auth.php';

requireAdminAuth();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: manage_certifications.php');
    exit;
}

$error = null;
$certification = null;

try {
    $connection = getDbConnection();
    $statement = $connection->prepare(
        'SELECT id, title, issuer, description, image_path, issued_year, certificate_url, is_featured
         FROM certifications
         WHERE id = ?'
    );
    $statement->bind_param('i', $id);
    $statement->execute();
    $result = $statement->get_result();
    $certification = $result->fetch_assoc();
    $statement->close();

    if (!$certification) {
        $connection->close();
        header('Location: manage_certifications.php');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = trim($_POST['title'] ?? '');
        $issuer = trim($_POST['issuer'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $issuedYear = filter_input(INPUT_POST, 'issued_year', FILTER_VALIDATE_INT);
        $certificateUrl = trim($_POST['certificate_url'] ?? '');
        $isFeatured = isset($_POST['is_featured']) ? 1 : 0;

        if ($title === '' || $issuer === '' || !$issuedYear) {
            $error = 'Title, issuer, and issued year are required.';
        } else {
            $newImagePath = $certification['image_path'];
            $uploadedImagePath = null;

            try {
                $uploadedImagePath = uploadPostImage($_FILES['image'] ?? []);
                if ($uploadedImagePath !== null) {
                    $newImagePath = $uploadedImagePath;
                }

                $certificateUrl = $certificateUrl !== '' ? $certificateUrl : null;

                $updateStatement = $connection->prepare(
                    'UPDATE certifications
                     SET title = ?, issuer = ?, description = ?, image_path = ?, issued_year = ?, certificate_url = ?, is_featured = ?
                     WHERE id = ?'
                );
                $updateStatement->bind_param('ssssisii', $title, $issuer, $description, $newImagePath, $issuedYear, $certificateUrl, $isFeatured, $id);
                $updateStatement->execute();
                $updateStatement->close();

                if ($uploadedImagePath !== null) {
                    removePostImage($certification['image_path']);
                }

                $connection->close();
                header('Location: manage_certifications.php?status=updated');
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

$pageTitle = 'Edit Certification - Renz Alvarez';
$activePage = 'certification';
require_once __DIR__ . '/../includes/header.php';
?>
<main class="projects-section">
    <div class="projects-container">
        <div class="projects-header">
            <h2 class="projects-title">Edit Certification</h2>
            <p class="projects-subtitle">// Update your certification details</p>
        </div>

        <?php if ($error): ?>
            <p class="crud-message crud-message-error"><?= e($error) ?></p>
        <?php endif; ?>

        <?php if ($certification): ?>
            <section class="crud-form-wrapper">
                <form method="post" enctype="multipart/form-data" class="crud-form">
                    <label class="crud-label" for="title">Title</label>
                    <input class="crud-input" id="title" name="title" type="text" value="<?= e($certification['title']) ?>" required>

                    <label class="crud-label" for="issuer">Issuer</label>
                    <input class="crud-input" id="issuer" name="issuer" type="text" value="<?= e($certification['issuer']) ?>" required>

                    <label class="crud-label" for="issued_year">Issued Year</label>
                    <input class="crud-input" id="issued_year" name="issued_year" type="number" min="1900" max="2100" value="<?= e((string) $certification['issued_year']) ?>" required>

                    <label class="crud-label" for="certificate_url">Certificate URL (optional)</label>
                    <input class="crud-input" id="certificate_url" name="certificate_url" type="url" value="<?= e((string) ($certification['certificate_url'] ?? '')) ?>">

                    <label class="crud-label" for="description">Description (optional)</label>
                    <textarea class="crud-textarea" id="description" name="description" rows="4"><?= e((string) ($certification['description'] ?? '')) ?></textarea>

                    <label class="crud-label" for="image">Replace Image (optional)</label>
                    <input class="crud-input" id="image" name="image" type="file" accept="image/*">

                    <label class="crud-label" for="is_featured">
                        <input id="is_featured" name="is_featured" type="checkbox" value="1" <?= (int) $certification['is_featured'] === 1 ? 'checked' : '' ?>>
                        Show in featured certification cards
                    </label>

                    <?php if (!empty($certification['image_path'])): ?>
                        <p class="crud-label">Current Image</p>
                        <img class="crud-thumb crud-thumb-large" src="<?= e(asset_url($certification['image_path'])) ?>" alt="<?= e($certification['title']) ?>">
                    <?php endif; ?>

                    <div class="crud-actions-row">
                        <button class="project-btn project-btn-primary" type="submit">Save Changes</button>
                        <a class="project-btn project-btn-secondary" href="manage_certifications.php">Back to Manage Certifications</a>
                    </div>
                </form>
            </section>
        <?php endif; ?>
    </div>
</main>
<?php
$footerYear = 2025;
require_once __DIR__ . '/../includes/footer.php';
