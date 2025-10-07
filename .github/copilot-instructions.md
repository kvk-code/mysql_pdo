# GitHub Copilot Instructions for PHP Backend Development

## Purpose
These instructions ensure that all PHP backend scripts are beginner-friendly and follow educational best practices.

---

## Core Rules for PHP Script Creation

### 1. **Beginner-Friendly Code**
- Write clear, well-commented code that beginners can understand
- Use descriptive variable names (e.g., `$student_name` instead of `$sn`)
- Break complex operations into simple, understandable steps
- Avoid advanced PHP features unless specifically requested
- Include inline comments explaining what each section does

**Example:**
```php
// Get the student name from form and remove extra spaces
$name = trim($_POST['name']);
```

### 2. **Single Responsibility Principle**
- Each PHP file should do ONE job only
- Separate concerns into different files:
  - HTML forms in separate `.html` files
  - Insert operations in separate PHP files (e.g., `insert_student.php`)
  - Update operations in separate PHP files (e.g., `update_student.php`)
  - Delete operations in separate PHP files (e.g., `delete_student.php`)
  - Display/Read operations in separate PHP files (e.g., `view_students.php`)
- DO NOT mix HTML forms and PHP processing in the same file
- This separation helps beginners understand each component independently

**Example Structure:**
```
student_form.html        → HTML form only
insert_student.php       → Handles INSERT only
update_form.html         → HTML update form only
update_student.php       → Handles UPDATE only
```

### 3. **Use setAttribute() for PDO Configuration**
- ALWAYS use `setAttribute()` method instead of options array in PDO constructor
- Set each attribute on a separate line for clarity
- This makes it easier for beginners to understand what each configuration does
- Always include comments explaining what each attribute does

**Correct Approach:**
```php
// Create PDO connection
$pdo = new PDO($dsn, $username, $password);

// Set error mode to throw exceptions
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Set default fetch mode to associative array
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

// Use real prepared statements
$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
```

**Avoid (Too Complex for Beginners):**
```php
// DON'T do this - confusing array syntax
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];
$pdo = new PDO($dsn, $username, $password, $options);
```

---

## Additional Best Practices

### 4. **Always Use Prepared Statements**
- Use parameterized queries with placeholders (`:parameter` or `?`)
- Bind parameters using `bindParam()` or pass array to `execute()`
- Never concatenate user input directly into SQL queries

### 5. **Proper Error Handling**
- Always wrap database operations in `try-catch` blocks
- Provide user-friendly error messages
- Use `PDOException` for catching database errors

### 6. **Input Validation**
- Always validate and sanitize user input
- Check for empty fields before processing
- Use appropriate PHP functions: `trim()`, `htmlspecialchars()`, type casting

### 7. **Clean HTML Output**
- For educational purposes, keep HTML styling minimal unless requested
- Focus on functionality over aesthetics
- Basic HTML structure is sufficient for learning

### 8. **Code Organization**
```php
<?php
// 1. Database configuration at the top
$host = 'localhost';
$dbname = 'database_name';

// 2. Check request method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 3. Get and validate input
    $variable = trim($_POST['field']);
    
    // 4. Database operations in try-catch
    try {
        // Connection
        // SQL preparation
        // Execution
        // Success handling
    } catch (PDOException $e) {
        // Error handling
    }
}
?>
```

---

## Examples to Follow

### Good Example (Beginner-Friendly):
```php
<?php
// Database settings
$host = 'localhost';
$dbname = 'school_db';
$username = 'root';
$password = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $student_name = trim($_POST['name']);
    
    // Check if name is not empty
    if (empty($student_name)) {
        die("Error: Name is required!");
    }
    
    try {
        // Create database connection
        $dsn = "mysql:host=$host;dbname=$dbname";
        $pdo = new PDO($dsn, $username, $password);
        
        // Enable error reporting
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Prepare SQL statement
        $sql = "INSERT INTO students (name) VALUES (:name)";
        $stmt = $pdo->prepare($sql);
        
        // Bind parameter
        $stmt->bindParam(':name', $student_name);
        
        // Execute
        $stmt->execute();
        
        echo "Success!";
        
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
```

---

## Summary Checklist

When creating PHP backend scripts, ensure:
- [ ] Code is beginner-friendly with clear comments
- [ ] Each file has a single, well-defined purpose
- [ ] HTML forms are in separate files from PHP logic
- [ ] PDO attributes are set using `setAttribute()` method
- [ ] Prepared statements are used for all SQL queries
- [ ] Input validation is performed
- [ ] Error handling with try-catch is implemented
- [ ] Code follows the standard organization pattern

---

*These instructions help maintain consistency and educational value across all PHP scripts in this project.*
