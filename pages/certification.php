<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../admin/content_helpers.php';

$featuredCertifications = [];
$additionalCertifications = [];
$loadError = null;

try {
    $connection = getDbConnection();

    $featuredResult = $connection->query(
        'SELECT id, title, issuer, description, image_path, issued_year, certificate_url
         FROM certifications
         WHERE is_featured = 1
         ORDER BY issued_year DESC, id DESC'
    );

    while ($row = $featuredResult->fetch_assoc()) {
        $featuredCertifications[] = $row;
    }

    $additionalResult = $connection->query(
        'SELECT id, title
         FROM certifications
         WHERE is_featured = 0
         ORDER BY issued_year DESC, id DESC'
    );

    while ($row = $additionalResult->fetch_assoc()) {
        $additionalCertifications[] = $row;
    }

    $connection->close();
} catch (Throwable $exception) {
    $loadError = $exception->getMessage();
}

$totalCertifications = count($featuredCertifications) + count($additionalCertifications);

$pageTitle = 'Certifications - Renz Alvarez';
$activePage = 'certification';
$loadMainScript = true;
require_once __DIR__ . '/../includes/header.php';
?>
<main>
    <section id="certifications" class="certifications-section">
        <div class="cert-container">
            <div class="cert-header">
                <h2 class="cert-title">Certifications</h2>
                <p class="cert-subtitle">// Professional credentials & achievements</p>
                <a href="/portfolio/admin/manage_certifications.php" class="projects-manage-link">Manage Certifications</a>
            </div>

            <?php if ($loadError): ?>
                <p class="crud-message crud-message-error">Could not load certifications: <?= e($loadError) ?></p>
            <?php endif; ?>

            <div class="achievements-grid">
                <div class="achievement-card">
                    <div class="achievement-value"><?= e((string) $totalCertifications) ?></div>
                    <div class="achievement-label">Total Certifications</div>
                </div>
                <div class="achievement-card">
                    <div class="achievement-value">500+</div>
                    <div class="achievement-label">Hours of Training</div>
                </div>
                <div class="achievement-card">
                    <div class="achievement-value">15+</div>
                    <div class="achievement-label">Verified Badges</div>
                </div>
            </div>

            <?php if (count($featuredCertifications) > 0): ?>
                <div class="certifications-grid">
                    <?php foreach ($featuredCertifications as $certification): ?>
                        <div class="cert-card">
                            <div class="cert-image-wrapper">
                                <?php if (!empty($certification['image_path'])): ?>
                                    <img src="<?= e(asset_url($certification['image_path'])) ?>" alt="<?= e($certification['title']) ?>" class="cert-image">
                                <?php else: ?>
                                    <div class="project-image project-image-placeholder">No image</div>
                                <?php endif; ?>
                            </div>
                            <div class="cert-content">
                                <h3 class="cert-card-title"><?= e($certification['title']) ?></h3>
                                <p class="cert-issuer"><?= e($certification['issuer']) ?></p>
                                <p class="cert-date">Issued: <?= e((string) $certification['issued_year']) ?></p>
                                <?php if (!empty($certification['certificate_url'])): ?>
                                    <a class="cert-btn" href="<?= e($certification['certificate_url']) ?>" target="_blank" rel="noopener noreferrer">View Certificate</a>
                                <?php else: ?>
                                    <span class="cert-btn cert-btn-disabled">Certification</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="additional-certs">
                <h3 class="additional-title">Additional Certifications</h3>
                <?php if (count($additionalCertifications) === 0): ?>
                    <p class="crud-message">No additional certifications found.</p>
                <?php else: ?>
                    <div class="additional-grid">
                        <?php foreach ($additionalCertifications as $certification): ?>
                            <div class="additional-cert-item">
                                <span class="check-mark">+</span>
                                <span><?= e($certification['title']) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>
<?php
$footerYear = 2026;
require_once __DIR__ . '/../includes/footer.php';
