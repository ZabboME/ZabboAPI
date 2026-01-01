# 🛡️ ZabboAPI Hardened Core

![Security](https://img.shields.io/badge/Security-Hardened-success?style=for-the-badge&logo=securityscorecard)
![PHP](https://img.shields.io/badge/PHP-7.4%20%7C%208.x-blue?style=for-the-badge&logo=php)
![Status](https://img.shields.io/badge/Status-Production--Ready-brightgreen?style=for-the-badge)

A high-performance, minimalist, and ultra-secure RCON API for retro-gaming servers. This version has been manually audited, stripped of unnecessary bloat, and hardened against common web vulnerabilities.

## ✨ Key Features

*   **🔒 Injection Proof**: 100% usage of PDO and MySQLi Prepared Statements. SQL injection is impossible.
*   **🛡️ RCON Safety**: All commands sent to the game server are JSON-encoded and strictly validated.
*   **✅ Strict Validation**: Extensive regex and type-checking for every input parameter.
*   **🚀 Production Optimized**: Minimalist codebase with all comments removed for performance and clarity.
*   **🌐 Cross-Origin Ready**: Optimized CORS configuration for access from any domain (scoped by SSO).
*   **📁 Server Hardened**: Pre-configured `web.config` and `.htaccess` to block sensitive file access.

## 🚀 Quick Start

1.  **Clone the Repo**:
    ```bash
    git clone https://github.com/groundmanage2022/ZabboAPI-Hardened-Core.git
    ```

2.  **Configure Credentials**:
    Edit `config.php` with your database and RCON details.

3.  **Secure Your Server**:
    Ensure `web.config` (for IIS) or `.htaccess` (for Apache) is active in your root directory.

## 🛡️ Security Implementation

| Layer | Protection Mechanism |
| :--- | :--- |
| **Database** | PDO Prepared Statements + Parameter Binding |
| **RCON** | JSON Encoding + Defense-in-Depth Validation |
| **Input** | Regex Whitelisting + Numeric Range Checking |
| **Privilege** | Strict Command Whitelist (:about, :help, etc.) |
| **Infrastucture** | Server-side blocking of .env, .git, and config files |

## 📁 Project Structure

*   `index.php`: Main API entry point and validation logic.
*   `Rcon.php`: Secure RCON communication layer.
*   `User.php`: Database interaction using prepared statements.
*   `audio.php`: Secure audio upload handler with MIME validation.
*   `web.config`: IIS-specific security and routing rules.
*   `.htaccess`: Apache-specific security and routing rules.

## 🤝 Contributing

This is the "Hardened Core" version. If you find any potential edge cases for injection, please open an issue immediately.

---

**Developed for performance. Hardened for security.**
