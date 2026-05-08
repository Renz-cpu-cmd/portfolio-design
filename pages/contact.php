<?php
$pageTitle = 'Contact - Renz Alvarez';
$activePage = 'contact';
$loadMainScript = true;
require_once __DIR__ . '/../includes/header.php';
?>
<main id="contact" class="contact-section">
    <div class="contact-container">
        <div class="contact-header">
            <h2 class="contact-title">Let's Connect</h2>
            <p class="contact-subtitle">// Ready to collaborate? Drop me a message</p>
        </div>

        <div class="contact-grid">
            <div class="contact-form-wrapper">
                <div class="contact-form-box">
                    <h3 class="form-title">
                        <span class="send-icon">📬</span> Send a Message
                    </h3>

                    <form id="contactForm" class="contact-form">
                        <div class="form-group">
                            <label for="name" class="form-label">
                                <span class="prompt-symbol">&gt;</span> Your Name
                            </label>
                            <input type="text" id="name" name="name" class="form-input" placeholder="John Doe" required />
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">
                                <span class="prompt-symbol">&gt;</span> Your Email
                            </label>
                            <input type="email" id="email" name="email" class="form-input" placeholder="john@example.com" required />
                        </div>

                        <div class="form-group">
                            <label for="message" class="form-label">
                                <span class="prompt-symbol">&gt;</span> Message
                            </label>
                            <textarea id="message" name="message" class="form-textarea" placeholder="Tell me about your project..." rows="6" required></textarea>
                        </div>

                        <button type="submit" class="submit-btn" id="submitBtn">
                            <span class="btn-text">Send Message</span>
                            <span class="loader" id="loader" style="display: none;"></span>
                        </button>
                    </form>
                </div>
            </div>

            <div class="contact-info-wrapper">
                <div class="contact-info-box">
                    <h3 class="info-title">Contact Info</h3>
                    <div class="info-list">
                        <div class="info-item">
                            <div class="info-icon">✉️</div>
                            <div>
                                <div class="info-label">Email</div>
                                <div class="info-value">alvarezrenz237@gmail.com</div>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon">📞</div>
                            <div>
                                <div class="info-label">Phone</div>
                                <div class="info-value">+63 09123456789</div>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon">📍</div>
                            <div>
                                <div class="info-label">Location</div>
                                <div class="info-value">Urdaneta City, Pangasinan</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="social-links-box">
                    <h3 class="social-title">Social Media</h3>
                    <div class="social-links">
                        <a href="https://github.com/Renz-cpu-cmd" target="_blank" rel="noopener noreferrer" class="social-link">
                            <span class="social-icon">🐙</span>
                            <span class="social-label">GitHub</span>
                        </a>
                        <a href="https://linkedin.com/in/renz-alvarez-347494369" target="_blank" rel="noopener noreferrer" class="social-link">
                            <span class="social-icon">💼</span>
                            <span class="social-label">LinkedIn</span>
                        </a>
                        <a href="https://twitter.com" target="_blank" rel="noopener noreferrer" class="social-link">
                            <span class="social-icon">𝕏</span>
                            <span class="social-label">Twitter</span>
                        </a>
                        <a href="https://gmail.com" target="_blank" rel="noopener noreferrer" class="social-link">
                            <span class="social-icon">📧</span>
                            <span class="social-label">Email</span>
                        </a>
                    </div>
                </div>

                <div class="availability-badge">
                    <div class="badge-content">
                        <div class="pulse-dot"></div>
                        <span class="available-text">AVAILABLE FOR WORK</span>
                    </div>
                    <p class="badge-subtitle">Open to internships & projects</p>
                </div>
            </div>
        </div>

        <div class="contact-footer">
            <div class="footer-content">
                <p class="footer-text">© 2026 Renz Alvarez. Built with PHP, CSS & Vanilla JS</p>
                <div class="footer-credit">
                    <span>Made with</span>
                    <span class="heart">♥</span>
                    <span>by a BSIT Student</span>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
document.getElementById('contactForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const form = e.target;
    const submitBtn = document.getElementById('submitBtn');
    const loader = document.getElementById('loader');
    const btnText = submitBtn.querySelector('.btn-text');

    // Disable button and show loader
    submitBtn.disabled = true;
    loader.style.display = 'inline-block';
    btnText.style.display = 'none';

    const formData = new FormData(form);

    fetch('/portfolio/admin/handle_contact.php', {
        method: 'POST',
        body: formData
    })
    .then(async response => {
        const text = await response.text();
        try {
            return JSON.parse(text);
        } catch (e) {
            console.error('Server returned non-JSON response:', text);
            throw new Error('Invalid server response');
        }
    })
    .then(data => {
        if (data.status === 'success') {
            alert(data.message, 'Message Sent');
            form.reset();
        } else {
            alert('Error: ' + data.message, 'Transmission Failed');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An unexpected error occurred. Please try again later.', 'System Error');
    })
    .finally(() => {
        // Re-enable button and hide loader
        submitBtn.disabled = false;
        loader.style.display = 'none';
        btnText.style.display = 'inline';
    });
});
</script>
<?php
$footerYear = 2026;
$footerText = 'Renz Alvarez. Built with HTML, CSS & Vanilla JS';
require_once __DIR__ . '/../includes/footer.php';
