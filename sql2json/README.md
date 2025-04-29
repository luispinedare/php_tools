# 🛡️ Export MySQL Data to JSON Format

This project provides a simple and secure PHP script to query a MySQL database and export the results into a clean, structured JSON format.  
It is designed to perform groupings and counts dynamically, supporting nested structures up to three levels.

---

## 📚 Features

- Secure database connection (credentials stored separately in `config.php`).
- SQL injection mitigation (column name sanitization and safe value escaping).
- Dynamic grouping and counting at one, two, or three levels.
- JSON output ready for API responses, dashboards, or data analysis.
- Clean and modular code structure for easy maintenance.

---

## 🚀 Usage

1. Clone or download this repository.
2. Create a `config.php` file in the root directory based on the provided template.
3. Update the database credentials in `config.php`.
4. Deploy `export_json.php` on your PHP-enabled server.
5. Access `export_json.php` through your browser or API client to receive JSON output.

---

## ⚙️ Configuration

**config.php**
```php
<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database_name');
define('DB_CHARSET', 'utf8mb4');
?>
```

---

## 🔒 Security Best Practices

- Do not upload your `config.php` file to public repositories.
- Use `.gitignore` to exclude sensitive configuration files.
- Always validate and sanitize any user inputs if dynamic values are allowed.

---

## 🖥️ Output Example

```json
[
  {
    "primary_field": "Value",
    "value": 42,
    "secondary_field": [
      {
        "subcategory": 20,
        "tertiary_field": {
          "item1": 10,
          "item2": 10
        }
      }
    ]
  }
]
```

*(The fields depend on your database schema and the configuration inside the script.)*

---

## 📜 License

This project is open-source and free to use under the MIT License.

---

> _"Always learning. Always adapting."_ 🚀
