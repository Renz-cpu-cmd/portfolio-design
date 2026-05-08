<?php
$pageTitle = 'Home - Renz Alvarez | BSIT Student & Developer';
$activePage = 'home';
$loadMainScript = true;
require_once __DIR__ . '/../includes/header.php';
?>
<main>
    <section id="home" class="hero-section">
        <div class="hero-container">
            <div class="hero-left">
                <div class="profile-wrapper">
                    <div class="profile-glow"></div>
                    <div class="profile-border">
                        <div class="profile-overlay"></div>
                        <div class="profile-picture">
                            <img src="/portfolio/image/profile.png" alt="Renz Alvarez Profile" class="profile-img">
                        </div>
                    </div>
                </div>
            </div>

            <div class="hero-right">
                <div class="hero-content">
                    <h1 class="hero-title">
                        Hello, I'm
                        <span class="hero-name">Renz Alvarez</span>
                    </h1>

                    <div class="terminal-box">
                        <div class="terminal-header">
                            <span class="terminal-dot red"></span>
                            <span class="terminal-dot yellow"></span>
                            <span class="terminal-dot green"></span>
                            <span class="terminal-label">terminal</span>
                        </div>
                        <div class="terminal-text">
                            <span class="terminal-prompt">&gt;</span>
                            <span id="typing-text"></span>
                            <span class="cursor" id="cursor">_</span>
                        </div>
                    </div>
                </div>

                <p class="hero-description">
                    Passionate about building innovative solutions through code.
                    Specializing in full-stack development, database design, and
                    creating seamless user experiences.
                </p>

                <div class="tech-stack">
                    <div class="tech-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="tech-icon">
                            <polyline points="16 18 22 12 16 6"></polyline>
                            <polyline points="8 6 2 12 8 18"></polyline>
                        </svg>
                        <span>Frontend</span>
                    </div>
                    <div class="tech-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="tech-icon">
                            <ellipse cx="12" cy="5" rx="9" ry="3"></ellipse>
                            <path d="M3 5v14a9 3 0 0 0 18 0V5"></path>
                            <path d="M3 12a9 3 0 0 0 18 0"></path>
                        </svg>
                        <span>Backend</span>
                    </div>
                    <div class="tech-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="tech-icon">
                            <polyline points="4 17 10 11 4 5"></polyline>
                            <line x1="12" y1="19" x2="20" y2="19"></line>
                        </svg>
                        <span>DevOps</span>
                    </div>
                </div>

                <div class="button-group">
                    <a class="btn btn-primary" href="project.php">
                        View Projects
                    </a>
                    <a class="btn btn-secondary" href="contact.php">
                        Get In Touch
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>
<?php
$footerYear = 2026;
require_once __DIR__ . '/../includes/footer.php';
