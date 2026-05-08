<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/content_helpers.php';
require_once __DIR__ . '/admin_auth.php';

requireAdminAuth();

$error = null;
$status = $_GET['status'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $issuer = trim($_POST['issuer'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $issuedYear = filter_input(INPUT_POST, 'issued_year', FILTER_VALIDATE_INT);
    $certificateUrl = trim($_POST['certificate_url'] ?? '');
    $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
    $newImagePath = null;

    if ($title === '' || $issuer === '' || !$issuedYear) {
        $error = 'Title, issuer, and issued year are required.';
    } else {
        try {
            $newImagePath = uploadPostImage($_FILES['image'] ?? []);
            $certificateUrl = $certificateUrl !== '' ? $certificateUrl : null;

            $connection = getDbConnection();
            $statement = $connection->prepare(
                'INSERT INTO certifications (title, issuer, description, image_path, issued_year, certificate_url, is_featured) VALUES (?, ?, ?, ?, ?, ?, ?)'
            );
            $statement->bind_param('ssssisi', $title, $issuer, $description, $newImagePath, $issuedYear, $certificateUrl, $isFeatured);
            $statement->execute();
            $statement->close();
            $connection->close();

            header('Location: manage_certifications.php?status=created');
            exit;
        } catch (Throwable $exception) {
            removePostImage($newImagePath);
            $error = $exception->getMessage();
        }
    }
}

$certifications = [];

try {
    $connection = getDbConnection();
    $result = $connection->query(
        'SELECT id, title, issuer, description, image_path, issued_year, certificate_url, is_featured
         FROM certifications
         ORDER BY is_featured DESC, issued_year DESC, id DESC'
    );

    while ($row = $result->fetch_assoc()) {
        $certifications[] = $row;
    }

    $connection->close();
} catch (Throwable $exception) {
    $error = $exception->getMessage();
}

$pageTitle = 'Manage Certifications - Renz Alvarez';
$activePage = 'certification';
require_once __DIR__ . '/../includes/header.php';
?>
<main class="projects-section">
    <div class="projects-container">
        <div class="projects-header">
            <h2 class="projects-title">Certification Management</h2>
            <p class="projects-subtitle">// Create, update, and remove certifications</p>
            <a class="project-btn project-btn-secondary" href="/portfolio/pages/certification.php">Back to Certifications</a>
            <a class="project-btn project-btn-danger" href="admin_logout.php">Logout</a>
        </div>

        <?php if ($error): ?>
            <p class="crud-message crud-message-error"><?= e($error) ?></p>
        <?php endif; ?>

        <?php if ($status): ?>
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const status = "<?= e($status) ?>";
                    let msg = "";
                    if (status === 'created') msg = "Certification created successfully!";
                    if (status === 'updated') msg = "Certification updated successfully!";
                    if (status === 'deleted') msg = "Certification deleted successfully!";
                    if (msg) alert(msg, 'Success');
                });
            </script>
        <?php endif; ?>

        <section class="crud-form-wrapper">
            <h3 class="crud-section-title">Add New Certification</h3>
            <form method="post" enctype="multipart/form-data" class="crud-form">
                <label class="crud-label" for="title">Title</label>
                <input class="crud-input" id="title" name="title" type="text" required>

                <label class="crud-label" for="issuer">Issuer</label>
                <input class="crud-input" id="issuer" name="issuer" type="text" required>

                <label class="crud-label" for="issued_year">Issued Year</label>
                <input class="crud-input" id="issued_year" name="issued_year" type="number" min="1900" max="2100" required>

                <label class="crud-label" for="certificate_url">Certificate URL (optional)</label>
                <input class="crud-input" id="certificate_url" name="certificate_url" type="url" placeholder="https://...">

                <label class="crud-label" for="description">Description (optional)</label>
                <textarea class="crud-textarea" id="description" name="description" rows="4"></textarea>

                <label class="crud-label" for="image">Image (optional)</label>
                <input class="crud-input" id="image" name="image" type="file" accept="image/*">

                <label class="crud-label" for="is_featured">
                    <input id="is_featured" name="is_featured" type="checkbox" value="1" checked>
                    Show in featured certification cards
                </label>

                <button class="project-btn project-btn-primary" type="submit">Add Certification</button>
            </form>
        </section>

        <section class="crud-table-wrapper">
            <h3 class="crud-section-title">All Certifications</h3>
            <?php if (count($certifications) === 0): ?>
                <p class="crud-message">No certifications found.</p>
            <?php else: ?>
                <div class="crud-table-scroll">
                    <table class="crud-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Issuer</th>
                                <th>Year</th>
                                <th>Type</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($certifications as $certification): ?>
                                <tr>
                                    <td><?= (int) $certification['id'] ?></td>
                                    <td>
                                        <?php if (!empty($certification['image_path'])): ?>
                                            <img class="crud-thumb" src="<?= e(asset_url($certification['image_path'])) ?>" alt="<?= e($certification['title']) ?>">
                                        <?php else: ?>
                                            <span class="crud-empty">No image</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= e($certification['title']) ?></td>
                                    <td><?= e($certification['issuer']) ?></td>
                                    <td><?= e((string) $certification['issued_year']) ?></td>
                                    <td><?= (int) $certification['is_featured'] === 1 ? 'Featured' : 'Additional' ?></td>
                                    <td>
                                        <div class="crud-actions">
                                            <a class="project-btn project-btn-secondary" href="edit_certification.php?id=<?= (int) $certification['id'] ?>">Edit</a>
                                            <form method="post" action="delete_certification.php" onsubmit="event.preventDefault(); confirm('Are you sure you want to delete this certification?', 'Delete Certification').then(res => { if(res) this.submit(); });">
                                                <input type="hidden" name="id" value="<?= (int) $certification['id'] ?>">
                                                <button class="project-btn project-btn-danger" type="submit">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>
    </div>
</main>
<?php
$footerYear = 2025;
require_once __DIR__ . '/../includes/footer.php';
