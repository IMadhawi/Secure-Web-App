# 🍿 Popcorn Opinions – Secure vs Insecure Web Application

**Popcorn Opinions** is a movie review web application built as a university project to demonstrate web security practices. It contains two versions:

- `/secure/` – Implements modern security best practices
- `/insecure/` – Deliberately includes common vulnerabilities for demonstration

Both versions are hosted using [InfinityFree](https://www.infinityfree.com/).

---

## 📖 Overview

This project is designed to:

- Demonstrate the difference between secure and insecure PHP web applications
- Showcase how vulnerabilities like SQL injection, XSS, and weak password storage can be exploited
- Provide examples of mitigation strategies for each vulnerability

### 🔐 Secure Features Implemented:

- Passwords hashed using **bcrypt**
- **Prepared statements** to prevent SQL injection
- **XSS protection** using `htmlspecialchars()`
- **Session cookie hardening** (Secure, HttpOnly, SameSite)
- **HTTPS enforced** with a free SSL certificate
- **Role-based access control** for admin and user functionality

---

## 🌐 Live Demo

- 🔓 Insecure Version: [http://popcornopinionsis.fwh.is/insecure/register_insecure.php](http://popcornopinionsis.fwh.is/insecure/register_insecure.php)
- 🔐 Secure Version: [https://popcornopinions.fwh.is/secure/register_secure.php](https://popcornopinions.fwh.is/secure/register_secure.php)

---

## 🚀 How to Run the Application (via InfinityFree)

> Use these steps for both the secure and insecure versions:

1. Clone this repository
2. Visit [https://www.infinityfree.com/](https://www.infinityfree.com/), register and verify your account
3. Create a hosting account and choose a subdomain (e.g., `popcornopinions`)
4. Open the **Control Panel (cPanel)** for your hosting account
5. Go to **MySQL Databases**, create a new database, and import `db.sql`
6. Use the **Online File Manager** to upload the desired project folder (`secure/` or `insecure/`) and the `/images` folder
7. Install a free SSL certificate from the **"Free SSL Certificate"** tab in InfinityFree for the secure version
8. Access your app in a web browser via your domain

> 🔖 Notes:
> - Add movie data manually into the database
> - All registered users are assigned the `user` role by default. To promote users to `admin`, update their role manually in the database
> update the config.php file with your info for database connection

---

## 🔒 How to Test Security Features

### 1. SQL Injection (Insecure Version)
- Go to the insecure login form
- In the **username** field, enter: `' OR 1=1 #`
- Enter anything in the password field
- ✅ This should bypass login in the **insecure** version but **fail** in the secure one

### 2. XSS (Cross-Site Scripting)
- In the insecure dashboard or review form, input:

  `<script>alert("Hacked!")</script>`

- ✅ **Insecure version**: JavaScript will execute and show a popup
- ✅ **Secure version**: The input is escaped and shown as plain text

### 3. Weak Password Hashing
- 🔓 Insecure version: Passwords are stored using **MD5**, which is fast, unsalted, and easily cracked
- These MD5 hashes can be reversed using tools like [https://crackstation.net](https://crackstation.net)
  - Example: `21232f297a57a5a743894a0e4a801fc3` → `admin`
- 🔐 Secure version: Uses `password_hash()` with **bcrypt**, which is slow and salted — making it resistant to brute-force and rainbow table attacks

### 4. Access Control
- Try editing or deleting reviews:
  - 🔓 Insecure version:
    - Any user can edit or delete **any** review
  - 🔐 Secure version:
    - Users can only **edit their own reviews**
    - Only **admins** and the review's owner can delete a review

### 5. HTTPS Enforcement
- The **secure version** is served over **HTTPS** using a valid SSL certificate
- Any HTTP request is automatically redirected to **HTTPS**

---

## 🗂 Folder Structure

```
/secure              Secure version of the website
/insecure            Insecure version of the website
/db.sql              Database schema and test data
/SecurityReport.md   Security report
/README.md           This file
```

---

## 🙏 Acknowledgment

Special thanks to **Ghaida Alhussain** for her generous guidance and support in helping us explore and set up **InfinityFree** hosting. Her clear instructions and detailed steps made it possible to successfully deploy both versions of this project online.
