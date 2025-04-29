# 🛡️ Search Words and Meanings in MySQL Database

This project provides a secure PHP script designed to query a MySQL database for specific words or meanings, apply optional filters, and output structured results in a clean JSON format.

---

## 📚 Features

- Secure database connection (`config.php` for credentials management).
- Dynamic search mode:
  - Word ➔ Meaning
  - Meaning ➔ Word
- Optional filtering by additional fields (e.g., gender, age group).
- JSON output ready for API responses or web applications.
- Sanitization and validation of user input.
- Clean error handling to prevent exposure of sensitive system information.

---

## 🚀 Usage

1. Clone or download this repository.
2. Create a `config.php` file in the root directory based on the provided template.
3. Update the database credentials in `config.php`.
4. Deploy `search_word.php` on your PHP-enabled server.
5. Configure the default search parameters inside the script or extend it to receive POST parameters.
6. Access `search_word.php` through your browser or API client to receive JSON output.

---

## ⚙️ Configuration

**config.php**
```php
<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'your_username_here');
define('DB_PASSWORD', 'your_password_here');
define('DB_NAME', 'your_database_here');
define('DB_CHARSET', 'utf8mb4');
?>
```

---

## 🛠️ Script Behavior

- **Input:** Search term (`$palabra`) and search direction (`$buscar`).
- **Filtering:** Optional filter field (e.g., gender).
- **Output:** Structured JSON showing the primary search field, grouped counts, and related subcategories.

---

## 🔒 Security Best Practices

- Do not upload your `config.php` file to public repositories.
- Use `.gitignore` to exclude sensitive configuration files.
- Always validate and sanitize any external user input if making the script dynamic.
- Handle errors internally without exposing server or database details.

---

## 🖥️ Output Example

```json
[
  {
    "palabra": "tirar",
    "value": 25,
    "genero": [
      {
        "masculino": 15,
        "significado": {
          "lanzar": 12,
          "empujar": 3
        }
      },
      {
        "femenino": 10,
        "significado": {
          "empujar": 10
        }
      }
    ]
  }
]
```
*(The actual fields depend on your database schema and filtering logic.)*

---

## 📜 License

This project is open-source and free to use under the MIT License.

---

> _"Always learning. Always adapting."_ 🚀