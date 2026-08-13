# 💼 Portfolio Design & Admin CMS

> **Full-Stack Developer Portfolio & Content Management System.**

[![PHP](https://img.shields.io/badge/PHP-8.x-purple?logo=php)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-Database-blue?logo=mysql)](https://www.mysql.com/)
[![JavaScript](https://img.shields.io/badge/JavaScript-ES6+-yellow?logo=javascript)](https://developer.mozilla.org/en-US/docs/Web/JavaScript)
[![CSS3](https://img.shields.io/badge/CSS3-Modular-blue?logo=css3)](https://www.w3.org/Style/CSS/)

**Portfolio Design** is a dynamic full-stack personal portfolio website featuring an integrated administrative Content Management System (CMS). It empowers developers to showcase work, manage certifications, list projects, and handle incoming contact inquiries through a secure backend interface.

---

## ✨ Features

- 🎨 **Modular Glassmorphism UI**: Custom CSS structure (`about.css`, `certification.css`, `contact.css`, `modal.css`, `project.css`) with interactive modal dialogs and dynamic background animations (`background.js`).
- 🔐 **Admin CMS Panel (`admin/`)**: Complete administrative suite with authentication (`admin_login.php`, `admin_auth.php`, `admin_logout.php`).
- 🛠️ **Project & Certification Management**: Perform CRUD operations on personal projects (`manage_projects.php`, `edit_project.php`, `delete_project.php`) and certifications (`manage_certifications.php`).
- 📬 **Contact Form Handler**: Backend form processor (`handle_contact.php`) for receiving user messages and inquiries.
- 🗄️ **Database Integration**: PDO/MySQL database layer (`config/db.php`) for data persistence.

---

## 🛠 Tech Stack

- **Frontend**: HTML5, CSS3 (Modular Stylesheets), Vanilla JavaScript (ES6+), Modal utilities
- **Backend**: PHP 8+
- **Database**: MySQL / MariaDB
- **Server**: Apache / Nginx / XAMPP / WampServer

---

## 🚦 Quick Start

1. **Clone Repository**:
   ```bash
   git clone https://github.com/Renz-cpu-cmd/portfolio-design.git
   ```
2. **Setup Database**:
   - Import SQL schema into your MySQL server.
   - Configure credentials in `config/db.php`.
3. **Run Locally**:
   ```bash
   php -S localhost:8000
   ```
4. Access site at `http://localhost:8000` and admin panel at `http://localhost:8000/admin/admin_login.php`.
