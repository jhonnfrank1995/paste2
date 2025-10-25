<p align="center">
  <img src="public/assets/img/logo.svg" alt="PasteX Pro Logo" width="300">
</p>
<h1 align="center">PasteX Pro</h1>
<p align="center">
  <strong>A modern, secure, and portable PHP script for sharing code and text.</strong>
</p>
<p align="center">
  <a href="#"><img src="https://img.shields.io/badge/php-8.2%2B-blue.svg" alt="PHP Version"></a>
  <a href="#"><img src="https://img.shields.io/badge/license-MIT-green.svg" alt="License"></a>
</p>

---

**PasteX Pro** is a self-hosted pastebin solution built for developers, sysadmins, and anyone who needs a powerful tool to share and manage text snippets. It's designed to be lightweight, secure, and incredibly easy to deploy on any modern web hosting that supports PHP and MySQL/SQLite.

## ✨ Features

- **Modern Stack:** Built with PHP 8.2+ and modern frontend practices.
- **Syntax Highlighting:** Beautiful code rendering for dozens of languages.
- **Flexible Visibility:** Public, unlisted, and private (user-only) pastes.
- **Security First:**
  - Password-protected pastes.
  - Optional "Burn After Read" functionality.
  - Zero-Knowledge client-side encryption support.
  - Built-in protection against CSRF, XSS, and SQLi.
- **Easy Deployment:** Upload the code, run the web installer, and you're done!
- **Admin Panel:** A comprehensive dashboard to manage pastes, users, and site settings.
- **REST API:** A simple API to create and manage pastes programmatically.
- **Theming:** Ships with beautiful light and dark themes.

## 🚀 Installation

Getting PasteX Pro running is simple. See the detailed **[INSTALL.md](INSTALL.md)** file for a step-by-step guide.

## 📚 Documentation

- **[UPGRADE.md](UPGRADE.md):** How to upgrade to a new version.
- **[SECURITY.md](SECURITY.md):** Our security policy and how to report vulnerabilities.
- **API Documentation:** A full OpenAPI (YAML) schema is available in `resources/schemas/openapi.yaml`.

## 🛠️ Development

Interested in contributing? We'd love your help!

1.  **Fork the repository.**
2.  **Install dependencies:** `composer install`
3.  **Run the QA script** to check your changes before committing: `./tools/qa.sh`
4.  **Submit a pull request.**

## 📄 License

PasteX Pro is open-source software licensed under the **[MIT license](LICENSE)**.