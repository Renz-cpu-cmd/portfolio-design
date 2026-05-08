# Portfolio — Renz Alvarez

This repository contains a PHP-based personal portfolio with a small admin panel for managing projects and certifications.

Quick run (local via XAMPP):

1. Place this repository in your XAMPP `htdocs` folder (example path: `C:\xampp\htdocs\portfolio`).
2. Configure database credentials in `config/config.php` to match your MySQL (XAMPP) setup.
3. Create a MySQL database and import any schema you use (tables: `projects`, `certifications`).
4. Start Apache and MySQL via XAMPP Control Panel.
5. Open the site: `http://localhost/portfolio/pages/index.php`.

Admin:
- Admin credentials are in `config/config.php` (`ADMIN_USERNAME` and `ADMIN_PASSWORD`) for local development. Change these before publishing.
- Admin pages: `admin/manage_projects.php`, `admin/manage_certifications.php`.

Notes:
- The old static site (previous GitHub Pages content) is preserved in the `gh-pages-backup` branch.
- Uploaded images are ignored by default via `.gitignore` (`/uploads/`). If you want to track uploaded images, remove `/uploads/` from `.gitignore`.

