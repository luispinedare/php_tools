# 🌳 HTML Tree Builder — Safe and Recursive in PHP

This project provides a flexible and secure PHP function to dynamically build a full HTML tree structure based on structured array inputs.  
It focuses on **clean coding practices**, **input sanitization**, and **reusability**.

---

## 📚 Features

- **Recursive structure building**: Supports deeply nested HTML elements.
- **Flexible element generation**: Supports `<div>`, `<img>`, `<a>`, and any standard HTML tags.
- **Security**: 
  - Automatic escaping of all dynamic content to prevent XSS attacks.
  - No direct HTML injection risks.
- **Clean modular code**: Easy to maintain and extend.
- **Return HTML as string**: Allows you to control output or integrate with templates.

---

## 🚀 Usage

1. Include or require `TreeOfContents.php` in your project.
2. Prepare a structured array describing your desired HTML hierarchy.
3. Call the `TreeOfContents()` function and `echo` the result.

---

## 🛠️ Example

**Input Array:**

```php
$data = [
    [
        "TAG" => "div",
        "CLASS" => "container",
        "CONTENT" => [
            [
                "TAG" => "h1",
                "CONTENT" => "Welcome to My Website"
            ],
            [
                "TAG" => "a",
                "LINK" => "https://example.com",
                "CONTENT" => "Click here!"
            ]
        ]
    ]
];
```

**Calling the function:**

```php
echo TreeOfContents("div", "main-wrapper", $data);
```

**Output:**

```html
<div class="main-wrapper">
    <div class="container">
        <h1>Welcome to My Website</h1>
        <a href="https://example.com">Click here!</a>
    </div>
</div>
```

---

## 🔒 Security Best Practices

- All dynamic outputs (text, attributes) are safely escaped using `htmlspecialchars()`.
- No untrusted input is directly injected into HTML.
- Prepared to handle dynamic content safely even if extended.

---

## 📂 Project Structure

```
/TreeOfContents.php    # Main function file
/README.md             # This documentation
```

---

## 📜 License

This project is open-source and available under the MIT License.

---

> _"Always learning. Always adapting."_ 🚀