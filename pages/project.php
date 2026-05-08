<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../admin/content_helpers.php';

$projects = [];
$loadError = null;

try {
    $connection = getDbConnection();
    $result = $connection->query('SELECT id, title, description, image_path, date FROM projects ORDER BY date DESC, id DESC');

    while ($row = $result->fetch_assoc()) {
        $projects[] = $row;
    }

    $connection->close();
} catch (Throwable $exception) {
    $loadError = $exception->getMessage();
}

$pageTitle = 'Projects - Renz Alvarez';
$activePage = 'project';
require_once __DIR__ . '/../includes/header.php';
?>
<main>
    <section id="projects" class="projects-section">
        <div class="projects-container">
            <div class="projects-header">
                <h2 class="projects-title">Projects</h2>
                <p class="projects-subtitle">// Featured work & case studies</p>
                <a href="/portfolio/admin/manage_projects.php" class="projects-manage-link">Manage Projects</a>
            </div>

            <?php if ($loadError): ?>
                <p class="crud-message crud-message-error">Could not load projects: <?= e($loadError) ?></p>
            <?php elseif (count($projects) === 0): ?>
                <p class="crud-message">No projects yet. Add your first one in the Manage Projects page.</p>
            <?php else: ?>
                <div class="projects-grid">
                    <?php foreach ($projects as $index => $project): ?>
                        <article class="<?= $index === 0 ? 'project-card project-card-large' : 'project-card' ?>">
                            <div class="project-image-container">
                                <?php if (!empty($project['image_path'])): ?>
                                    <img src="<?= e(asset_url($project['image_path'])) ?>" alt="<?= e($project['title']) ?>" class="project-image">
                                <?php else: ?>
                                    <div class="project-image project-image-placeholder">No image</div>
                                <?php endif; ?>
                                <div class="project-gradient-overlay"></div>
                            </div>
                            <div class="project-content">
                                <h3 class="project-title"><?= e($project['title']) ?></h3>
                                <p class="project-description"><?= nl2br(e($project['description'])) ?></p>
                                <p class="project-date">Posted on <?= e($project['date']) ?></p>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>
<?php
$footerYear = 2026;
require_once __DIR__ . '/../includes/footer.php';
