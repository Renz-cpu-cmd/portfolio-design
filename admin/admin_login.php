<?php
require_once __DIR__ . '/admin_auth.php';

ensureAdminSessionStarted();

if (isAdminLoggedIn()) {
    header('Location: manage_projects.php');
    exit;
}

$error = null;
$redirect = trim($_GET['redirect'] ?? $_POST['redirect'] ?? 'manage_projects.php');
$redirect = $redirect !== '' ? $redirect : 'manage_projects.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === ADMIN_USERNAME && $password === ADMIN_PASSWORD) {
        $_SESSION['is_admin'] = true;
        $_SESSION['admin_username'] = ADMIN_USERNAME;

        if (preg_match('/^(https?:)?\/\//i', $redirect)) {
            $redirect = 'manage_projects.php';
        }

        header('Location: ' . $redirect);
        exit;
    }

    $error = 'Invalid credentials. Please try again.';
}

$pageTitle = 'Admin Login - Renz Alvarez';
$activePage = '';
require_once __DIR__ . '/../includes/header.php';
?>
<main class="projects-section">
    <div class="projects-container">
        <div class="projects-header">
            <h2 class="projects-title">Admin Login</h2>
            <p class="projects-subtitle">// Sign in to manage projects and certifications</p>
        </div>

        <?php if ($error): ?>
            <p class="crud-message crud-message-error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>

        <section class="crud-form-wrapper">
            <h3 class="crud-section-title">Enter Credentials</h3>
            <form method="post" class="crud-form">
                <input type="hidden" name="redirect" value="<?= htmlspecialchars($redirect, ENT_QUOTES, 'UTF-8') ?>">

                <label class="crud-label" for="username">Username</label>
                <input class="crud-input" id="username" name="username" type="text" required>

                <label class="crud-label" for="password">Password</label>
                <input class="crud-input" id="password" name="password" type="password" required>

                <div class="crud-actions-row">
                    <button class="project-btn project-btn-primary" type="submit">Login</button>
                    <a class="project-btn project-btn-secondary" href="/portfolio/pages/index.php">Back to Home</a>
                </div>
            </form>
        </section>
    </div>
</main>
<?php
$footerYear = 2026;
require_once __DIR__ . '/../includes/footer.php';
