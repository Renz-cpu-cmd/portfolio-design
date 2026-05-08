<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/content_helpers.php';
require_once __DIR__ . '/admin_auth.php';

requireAdminAuth();

$error = null;
$status = $_GET['status'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $date = trim($_POST['date'] ?? '');
    $newImagePath = null;

    if ($title === '' || $description === '' || $date === '') {
        $error = 'Title, description, and date are required.';
    } else {
        try {
            $newImagePath = uploadPostImage($_FILES['image'] ?? []);

            $connection = getDbConnection();
            $statement = $connection->prepare('INSERT INTO projects (title, description, image_path, date) VALUES (?, ?, ?, ?)');
            $statement->bind_param('ssss', $title, $description, $newImagePath, $date);
            $statement->execute();
            $statement->close();
            $connection->close();

            header('Location: manage_projects.php?status=created');
            exit;
        } catch (Throwable $exception) {
            removePostImage($newImagePath);
            $error = $exception->getMessage();
        }
    }
}

$projects = [];

try {
    $connection = getDbConnection();
    $result = $connection->query('SELECT id, title, description, image_path, date FROM projects ORDER BY date DESC, id DESC');

    while ($row = $result->fetch_assoc()) {
        $projects[] = $row;
    }

    $connection->close();
} catch (Throwable $exception) {
    $error = $exception->getMessage();
}

$pageTitle = 'Manage Projects - Renz Alvarez';
$activePage = 'project';
require_once __DIR__ . '/../includes/header.php';
?>
<main class="projects-section">
    <div class="projects-container">
        <div class="projects-header">
            <h2 class="projects-title">Project Management</h2>
            <p class="projects-subtitle">// Create, update, and remove portfolio projects</p>
            <a class="project-btn project-btn-secondary" href="/portfolio/pages/project.php">Back to Projects</a>
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
                    if (status === 'created') msg = "Project created successfully!";
                    if (status === 'updated') msg = "Project updated successfully!";
                    if (status === 'deleted') msg = "Project deleted successfully!";
                    if (msg) alert(msg, 'Success');
                });
            </script>
        <?php endif; ?>

        <section class="crud-form-wrapper">
            <h3 class="crud-section-title">Add New Project</h3>
            <form method="post" enctype="multipart/form-data" class="crud-form">
                <label class="crud-label" for="title">Title</label>
                <input class="crud-input" id="title" name="title" type="text" required>

                <label class="crud-label" for="description">Description</label>
                <textarea class="crud-textarea" id="description" name="description" rows="5" required></textarea>

                <label class="crud-label" for="date">Date</label>
                <input class="crud-input" id="date" name="date" type="date" required>

                <label class="crud-label" for="image">Image (optional)</label>
                <input class="crud-input" id="image" name="image" type="file" accept="image/*">

                <button class="project-btn project-btn-primary" type="submit">Add Project</button>
            </form>
        </section>

        <section class="crud-table-wrapper">
            <h3 class="crud-section-title">All Projects</h3>
            <?php if (count($projects) === 0): ?>
                <p class="crud-message">No projects found.</p>
            <?php else: ?>
                <div class="crud-table-scroll">
                    <table class="crud-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($projects as $project): ?>
                                <tr>
                                    <td><?= (int) $project['id'] ?></td>
                                    <td>
                                        <?php if (!empty($project['image_path'])): ?>
                                            <img class="crud-thumb" src="<?= e(asset_url($project['image_path'])) ?>" alt="<?= e($project['title']) ?>">
                                        <?php else: ?>
                                            <span class="crud-empty">No image</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= e($project['title']) ?></td>
                                    <td><?= e($project['description']) ?></td>
                                    <td><?= e($project['date']) ?></td>
                                    <td>
                                        <div class="crud-actions">
                                            <a class="project-btn project-btn-secondary" href="edit_project.php?id=<?= (int) $project['id'] ?>">Edit</a>
                                            <form method="post" action="delete_project.php" onsubmit="event.preventDefault(); confirm('Are you sure you want to delete this project? This action cannot be undone.', 'Delete Project').then(res => { if(res) this.submit(); });">
                                                <input type="hidden" name="id" value="<?= (int) $project['id'] ?>">
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
$footerYear = 2024;
require_once __DIR__ . '/../includes/footer.php';
