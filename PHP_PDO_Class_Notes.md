# PHP MySQL Database Connectivity Using PDO - Complete Class Notes

## Table of Contents
1. [Introduction to PDO](#introduction-to-pdo)
2. [Database Connection Setup](#database-connection-setup)
3. [PDO Configuration Attributes](#pdo-configuration-attributes)
4. [Understanding Prepared Statements](#understanding-prepared-statements)
5. [Creating and Executing Prepared Statements](#creating-and-executing-prepared-statements)
6. [Complete Insert Example Walkthrough](#complete-insert-example-walkthrough)
7. [Error Handling](#error-handling)
8. [Best Practices](#best-practices)

---

## 1. Introduction to PDO

### What is PDO?
**PDO** stands for **PHP Data Objects**. It is a database access layer that provides a uniform interface for accessing different types of databases (MySQL, PostgreSQL, SQLite, etc.).

### Why Use PDO?
## 15. Practice Exercises

### Basic Exercises:
1. Create a form and PHP script to insert a book record (title, author, price)
2. Modify the student insert to also include email and phone number
3. Create a script that inserts 5 students using a loop
4. Add validation to check if age is between 15 and 30
5. Create error handling for different types of database errors

### Advanced Exercises (After mastering basics):
6. Modify your `bindParam()` calls to explicitly specify data types
7. Create a script with NULL values and use `PDO::PARAM_NULL`
8. Test the performance difference between auto-detection and explicit types
9. Handle large integers using `PDO::PARAM_INT` explicitly
10. Create a file upload system using `PDO::PARAM_LOB`atabase Independent** - Works with multiple database systems
- ✅ **Security** - Supports prepared statements to prevent SQL injection
- ✅ **Object-Oriented** - Clean, modern coding style
- ✅ **Error Handling** - Better exception handling
- ✅ **Flexibility** - More features than older `mysqli` extension

### PDO vs mysqli
| Feature | PDO | mysqli |
|---------|-----|--------|
| Database Support | Multiple databases | MySQL only |
| Prepared Statements | Yes | Yes |
| Object-Oriented | Yes | Yes (and procedural) |
| Named Parameters | Yes | No |
| Recommended | ✅ Yes | Only for MySQL-specific projects |

---

## 2. Database Connection Setup

### Step 1: Define Database Credentials

```php
<?php
// Database configuration
$host = 'localhost';      // Database server address
$dbname = 'school_db';    // Database name
$username = 'root';       // MySQL username
$password = '';           // MySQL password (empty for XAMPP default)
?>
```

### Step 2: Create DSN (Data Source Name)

**DSN** = **Data Source Name** - A connection string containing database information.

```php
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
```

**DSN Components:**
- `mysql:` - Database driver (MySQL)
- `host=$host` - Server location (localhost)
- `dbname=$dbname` - Specific database to connect to
- `charset=utf8mb4` - Character encoding (supports all Unicode characters including emojis)

### Step 3: Create PDO Connection Object

```php
try {
    // Create PDO instance
    $pdo = new PDO($dsn, $username, $password);
    echo "Connected successfully!";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
```

**Explanation:**
- `new PDO()` - Creates a new PDO connection object
- First parameter: DSN string
- Second parameter: Username
- Third parameter: Password
- If connection fails, it throws a `PDOException`

---

## 3. PDO Configuration Attributes

After creating a PDO connection, you should configure it using the `setAttribute()` method.

### Why Use setAttribute()?
- ✅ **Beginner-Friendly** - Each setting on its own line
- ✅ **Clear and Readable** - Easy to understand what each does
- ✅ **Easy to Modify** - Can comment out or add attributes easily

### Essential Attributes

#### 3.1 Error Mode

```php
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
```

**What it does:** Controls how PDO reports errors.

**Three Error Modes:**
1. `PDO::ERRMODE_SILENT` - No errors shown (default, not recommended)
2. `PDO::ERRMODE_WARNING` - Shows PHP warnings
3. `PDO::ERRMODE_EXCEPTION` - Throws exceptions ✅ **BEST PRACTICE**

**Why use EXCEPTION mode?**
- Allows you to catch errors with `try-catch` blocks
- Provides detailed error messages for debugging
- Makes code more robust and maintainable

**Example:**
```php
try {
    $pdo->query("SELECT * FROM non_existent_table");
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
}
```

#### 3.2 Default Fetch Mode

```php
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
```

**What it does:** Sets the default format for fetching data from database.

**Common Fetch Modes:**

| Mode | Returns | Example Access |
|------|---------|----------------|
| `PDO::FETCH_ASSOC` ✅ | Associative array | `$row['name']` |
| `PDO::FETCH_NUM` | Numeric array | `$row[0]` |
| `PDO::FETCH_BOTH` | Both (uses more memory) | `$row['name']` or `$row[0]` |
| `PDO::FETCH_OBJ` | Object | `$row->name` |

**Why use FETCH_ASSOC?**
- Column names as keys - more readable
- Uses less memory than FETCH_BOTH
- Easy to remember: `$row['column_name']`

**Example:**
```php
// With FETCH_ASSOC
$row = ['id' => 1, 'name' => 'John', 'age' => 20];
echo $row['name'];  // Output: John

// With FETCH_NUM
$row = [1, 'John', 20];
echo $row[1];  // Output: John (but which field is index 1?)
```

#### 3.3 Emulate Prepares

```php
$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
```

**What it does:** Controls whether PDO uses real or emulated prepared statements.

**Options:**
- `true` - PHP handles prepared statements (emulation)
- `false` - MySQL handles prepared statements (real) ✅ **BEST PRACTICE**

**Why set to false?**
- ✅ Better security - MySQL enforces data/SQL separation
- ✅ Better performance - MySQL can cache prepared statements
- ✅ Type safety - MySQL enforces proper data types
- ✅ More reliable - Direct MySQL handling

---

## 4. Understanding Prepared Statements

### What are Prepared Statements?

A **prepared statement** is a feature used to execute the same SQL statement repeatedly with high efficiency and security.

### The Problem Without Prepared Statements

**Dangerous Code (NEVER DO THIS!):**
```php
// ❌ BAD - Vulnerable to SQL Injection
$name = $_POST['name'];
$sql = "INSERT INTO students (name) VALUES ('$name')";
$pdo->query($sql);
```

**What if user enters:** `'; DROP TABLE students; --`

**Resulting SQL:**
```sql
INSERT INTO students (name) VALUES (''); DROP TABLE students; --')
```
**Result:** Your entire `students` table is deleted! 💀

### The Solution: Prepared Statements

Prepared statements separate SQL structure from data, making SQL injection impossible.

---

## 5. Understanding Real vs Emulated Prepared Statements

### Real Prepared Statements (ATTR_EMULATE_PREPARES = false) ✅

**How it works:**

```
Step 1: PHP sends SQL template to MySQL
   ↓
   "INSERT INTO students (name, age) VALUES (?, ?)"
   
Step 2: MySQL parses and compiles the statement
   ↓
   MySQL creates execution plan and stores it
   
Step 3: PHP sends parameters separately
   ↓
   Parameter 1: "John Doe"
   Parameter 2: 20
   
Step 4: MySQL binds values and executes
   ↓
   Values are NEVER treated as SQL code
   ✅ SECURE
```

**Key Point:** SQL structure and data are **completely separated** at the MySQL level.

### Emulated Prepared Statements (ATTR_EMULATE_PREPARES = true) ⚠️

**How it works:**

```
Step 1: PHP keeps SQL template locally
   ↓
   "INSERT INTO students (name, age) VALUES (?, ?)"
   
Step 2: PHP substitutes placeholders itself
   ↓
   PHP: "INSERT INTO students (name, age) VALUES ('John Doe', 20)"
   
Step 3: PHP sends complete SQL to MySQL
   ↓
   MySQL receives: "INSERT INTO students (name, age) VALUES ('John Doe', 20)"
   
Step 4: MySQL executes as regular query
   ↓
   No preparation phase, no separation
   ⚠️ Less secure
```

**Key Point:** PHP handles substitution, MySQL just executes the final query.

### Visual Comparison

#### Real Prepared Statements (Better):
```
PHP                           MySQL
----                          -----
prepare() -----------------> Parse & Compile SQL
                              Store in memory
                              
bindParam()
execute() ------------------> Bind values separately
                              Execute prepared statement
                              (Data NEVER mixed with SQL) ✅
```

#### Emulated Prepared Statements:
```
PHP                           MySQL
----                          -----
prepare()     (stores locally)
bindParam()   (stores locally)
execute() ------------------> Receives complete SQL
            "...VALUES('John', 20)"
                              Execute as normal query
                              (Data already in SQL) ⚠️
```

---

## 6. Creating and Executing Prepared Statements

### Method 1: Using Named Placeholders (Recommended for Beginners)

Named placeholders use `:parameter_name` format.

```php
// Step 1: Prepare SQL with named placeholders
$sql = "INSERT INTO students (name, age) VALUES (:name, :age)";
$stmt = $pdo->prepare($sql);

// Step 2: Bind parameters (PDO automatically detects data types)
$stmt->bindParam(':name', $name);
$stmt->bindParam(':age', $age);

// Step 3: Set values
$name = "John Doe";
$age = 20;

// Step 4: Execute
$stmt->execute();
```

**Advantages:**
- ✅ Self-documenting - clear what each placeholder represents
- ✅ Order doesn't matter
- ✅ Can reuse same parameter multiple times
- ✅ PDO automatically detects data types from PHP variables

### Method 2: Using Positional Placeholders

Positional placeholders use `?` format.

```php
// Step 1: Prepare SQL with ? placeholders
$sql = "INSERT INTO students (name, age) VALUES (?, ?)";
$stmt = $pdo->prepare($sql);

// Step 2: Bind parameters by position (1-indexed)
$stmt->bindParam(1, $name);
$stmt->bindParam(2, $age);

// Step 3: Set values
$name = "John Doe";
$age = 20;

// Step 4: Execute
$stmt->execute();
```

**Note:** Position starts at 1, not 0!

### Method 3: Direct Execution with Array (Quickest)

```php
// Prepare SQL
$sql = "INSERT INTO students (name, age) VALUES (:name, :age)";
$stmt = $pdo->prepare($sql);

// Execute with array of values
$stmt->execute([
    ':name' => 'John Doe',
    ':age' => 20
]);
```

### Advanced: Specifying Parameter Data Types (Optional)

**For Beginners:** You can omit data types - PDO automatically detects them!

```php
// Simple approach (recommended for beginners)
$stmt->bindParam(':name', $name);
$stmt->bindParam(':age', $age);
```

**For Advanced Users:** You can explicitly specify data types for better control:

```php
// Advanced approach with explicit data types
$stmt->bindParam(':name', $name, PDO::PARAM_STR);
$stmt->bindParam(':age', $age, PDO::PARAM_INT);
```

#### Why Specify Data Types?

| Reason | Explanation |
|--------|-------------|
| **Performance** | Slightly faster - PDO doesn't need to detect type |
| **Large integers** | Ensures correct handling of big numbers |
| **NULL values** | Explicitly specify `PDO::PARAM_NULL` |
| **Binary data** | Use `PDO::PARAM_LOB` for large objects |
| **Clarity** | Makes code more self-documenting |

#### Available Data Type Constants:

| Constant | Description | Example Use Case |
|----------|-------------|------------------|
| `PDO::PARAM_STR` | String data | Names, addresses, text |
| `PDO::PARAM_INT` | Integer data | Age, IDs, counts |
| `PDO::PARAM_BOOL` | Boolean data | Active status (true/false) |
| `PDO::PARAM_NULL` | NULL value | Optional empty fields |
| `PDO::PARAM_LOB` | Large object | Images, files |

#### How PDO Auto-Detects Types:

When you don't specify a type, PDO looks at your PHP variable:

```php
$age = 20;              // PHP integer → PDO uses PDO::PARAM_INT
$name = "John";         // PHP string → PDO uses PDO::PARAM_STR
$active = true;         // PHP boolean → PDO uses PDO::PARAM_BOOL
$notes = null;          // PHP null → PDO uses PDO::PARAM_NULL
```

**For 99% of cases, auto-detection works perfectly!**

#### Examples:

**Basic (Recommended for Learning):**
```php
$stmt->bindParam(':email', $email);
$stmt->bindParam(':age', $age);
$stmt->bindParam(':active', $active);
```

**Advanced (With Explicit Types):**
```php
$stmt->bindParam(':email', $email, PDO::PARAM_STR);
$stmt->bindParam(':age', $age, PDO::PARAM_INT);
$stmt->bindParam(':active', $active, PDO::PARAM_BOOL);
```

Both approaches work correctly and are secure!

---

## 7. Complete Insert Example Walkthrough

Let's analyze the complete flow of inserting a student record:

### HTML Form (student_form.html)
```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Registration Form</title>
</head>
<body>
    <h1>Student Registration Form</h1>
    
    <form action="insert_student.php" method="POST">
        <label for="roll_number">Roll Number:</label>
        <input type="text" id="roll_number" name="roll_number" required>
        <br><br>

        <label for="name">Student Name:</label>
        <input type="text" id="name" name="name" required>
        <br><br>

        <label for="age">Age:</label>
        <input type="number" id="age" name="age" required min="1" max="100">
        <br><br>

        <label for="date_of_birth">Date of Birth:</label>
        <input type="date" id="date_of_birth" name="date_of_birth" required>
        <br><br>

        <button type="submit">Register Student</button>
    </form>
</body>
</html>
```

### PHP Insert Script (insert_student.php)

```php
<?php
// ==========================================
// SECTION 1: Database Configuration
// ==========================================
$host = 'localhost';
$dbname = 'if0_39995906_class';
$username = 'root';
$password = '';

// ==========================================
// SECTION 2: Check if Form is Submitted
// ==========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // ==========================================
    // SECTION 3: Get and Validate Input Data
    // ==========================================
    
    // Get form data and remove extra whitespace
    $roll_number = trim($_POST['roll_number']);
    $name = trim($_POST['name']);
    $age = (int)$_POST['age'];  // Convert to integer
    $date_of_birth = $_POST['date_of_birth'];
    
    // Validate that all fields have values
    if (empty($roll_number) || empty($name) || empty($age) || empty($date_of_birth)) {
        die("Error: All fields are required!");
    }
    
    // ==========================================
    // SECTION 4: Database Operations
    // ==========================================
    try {
        // Step 1: Create DSN (Data Source Name)
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
        
        // Step 2: Create PDO connection
        $pdo = new PDO($dsn, $username, $password);
        
        // Step 3: Configure PDO attributes
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        
        // Step 4: Prepare SQL statement with placeholders
        $sql = "INSERT INTO student (roll_number, name, age, date_of_birth) 
                VALUES (:roll_number, :name, :age, :date_of_birth)";
        
        $stmt = $pdo->prepare($sql);
        
        // Step 5: Bind parameters (PDO automatically detects data types)
        $stmt->bindParam(':roll_number', $roll_number);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':age', $age);
        $stmt->bindParam(':date_of_birth', $date_of_birth);
        
        // Step 6: Execute the prepared statement
        $stmt->execute();
        
        // Step 7: Get the last inserted ID
        $lastInsertId = $pdo->lastInsertId();
        
        // Step 8: Show success message
        echo "Success! Student ID: " . $lastInsertId;
        
    } catch (PDOException $e) {
        // ==========================================
        // SECTION 5: Error Handling
        // ==========================================
        
        // Check for specific error types
        if ($e->getCode() == 23000) {
            // Duplicate entry error (unique constraint violation)
            echo "Error: Roll number already exists!";
        } else {
            // Other database errors
            echo "Database Error: " . $e->getMessage();
        }
    }
    
} else {
    // If someone tries to access this file directly without submitting form
    header('Location: student_form.html');
    exit;
}
?>
```

### Flow Explanation

```
User fills form
    ↓
Submits form (POST method)
    ↓
PHP receives data in $_POST array
    ↓
Sanitize data (trim whitespace)
    ↓
Validate data (check if empty)
    ↓
Connect to database (PDO)
    ↓
Configure PDO attributes
    ↓
Prepare SQL with placeholders
    ↓
Bind parameters to placeholders
    ↓
Execute statement
    ↓
Get last inserted ID
    ↓
Show success message
```

---

## 8. Error Handling

### Try-Catch Block

Always wrap database operations in a `try-catch` block:

```php
try {
    // Database operations here
    $pdo = new PDO($dsn, $username, $password);
    // ... more code
    
} catch (PDOException $e) {
    // Handle errors here
    echo "Error: " . $e->getMessage();
}
```

### Common Error Codes

| Error Code | Meaning | Solution |
|------------|---------|----------|
| 23000 | Duplicate entry / Unique constraint violation | Check for existing records |
| 42S02 | Table doesn't exist | Create the table |
| 42000 | Syntax error in SQL | Check your SQL syntax |
| HY000 | General error | Check connection details |

### Handling Specific Errors

```php
try {
    // Database operations
    
} catch (PDOException $e) {
    // Check error code
    if ($e->getCode() == 23000) {
        echo "Duplicate entry! Record already exists.";
    } else {
        echo "Database error: " . $e->getMessage();
    }
}
```

### The die() Function

`die()` stops script execution immediately and displays a message:

```php
if (empty($name)) {
    die("Error: Name is required!");
}
// Code below will not execute if die() is called
```

**Note:** In production code, use proper error pages instead of `die()`.

---

## 9. Best Practices

### ✅ DO's

1. **Always use prepared statements**
   ```php
   // Good
   $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
   $stmt->execute([':id' => $id]);
   ```

2. **Use setAttribute() for configuration**
   ```php
   // Clear and beginner-friendly
   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   ```

3. **Validate and sanitize input**
   ```php
   $name = trim($_POST['name']);
   if (empty($name)) {
       die("Name is required");
   }
   ```

4. **Use try-catch for error handling**
   ```php
   try {
       // Database code
   } catch (PDOException $e) {
       // Handle error
   }
   ```

5. **Separate HTML forms from PHP logic**
   - `student_form.html` - HTML form only
   - `insert_student.php` - PHP processing only

6. **Use descriptive variable names**
   ```php
   $student_name = $_POST['name'];  // Good
   $sn = $_POST['name'];            // Bad
   ```

### ❌ DON'Ts

1. **Never concatenate user input into SQL**
   ```php
   // ❌ DANGEROUS - SQL Injection vulnerability
   $sql = "SELECT * FROM users WHERE name = '$name'";
   ```

2. **Don't use options array in PDO constructor (for beginners)**
   ```php
   // ❌ Confusing for beginners
   $pdo = new PDO($dsn, $user, $pass, [
       PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
   ]);
   ```

3. **Don't mix HTML and PHP in the same file (for learning)**
   ```php
   // ❌ Confusing - keep them separate
   <form>...</form>
   <?php /* database code */ ?>
   ```

4. **Don't ignore errors**
   ```php
   // ❌ Bad - errors are silent
   $pdo->query($sql);
   ```

5. **Don't use mysql_* or mysqli_* functions**
   ```php
   // ❌ Deprecated/Limited
   mysql_connect();  // Removed in PHP 7
   mysqli_connect(); // Use PDO instead
   ```

---

## 10. Common Functions Reference

### Connection Functions

```php
// Create connection
$pdo = new PDO($dsn, $username, $password);

// Set attribute
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Get attribute
$errorMode = $pdo->getAttribute(PDO::ATTR_ERRMODE);
```

### Statement Functions

```php
// Prepare statement
$stmt = $pdo->prepare($sql);

// Bind parameter
$stmt->bindParam(':name', $variable, PDO::PARAM_STR);

// Execute statement
$stmt->execute();

// Execute with array
$stmt->execute([':name' => 'John']);

// Fetch single row
$row = $stmt->fetch();

// Fetch all rows
$rows = $stmt->fetchAll();

// Row count
$count = $stmt->rowCount();
```

### Utility Functions

```php
// Get last inserted ID
$id = $pdo->lastInsertId();

// Begin transaction
$pdo->beginTransaction();

// Commit transaction
$pdo->commit();

// Rollback transaction
$pdo->rollBack();
```

---

## 11. Summary Checklist

When writing PHP PDO code, ensure:

- [ ] Database credentials are defined
- [ ] DSN string is properly formatted
- [ ] PDO connection is created inside try-catch
- [ ] `setAttribute()` is used for ERRMODE, FETCH_MODE, EMULATE_PREPARES
- [ ] Prepared statements are used for ALL queries with user input
- [ ] Parameters are bound with appropriate data types
- [ ] Input is validated and sanitized
- [ ] Errors are caught and handled properly
- [ ] HTML forms and PHP logic are in separate files

---

## 12. Complete Working Example

**Database Table:**
```sql
CREATE TABLE student (
    id INT AUTO_INCREMENT PRIMARY KEY,
    roll_number VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    age INT NOT NULL,
    date_of_birth DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

**Form:** `student_form.html`
```html
<!DOCTYPE html>
<html>
<head>
    <title>Student Form</title>
</head>
<body>
    <h1>Add Student</h1>
    <form action="insert_student.php" method="POST">
        <input type="text" name="roll_number" placeholder="Roll Number" required><br>
        <input type="text" name="name" placeholder="Name" required><br>
        <input type="number" name="age" placeholder="Age" required><br>
        <input type="date" name="date_of_birth" required><br>
        <button type="submit">Submit</button>
    </form>
</body>
</html>
```

**Processing:** `insert_student.php` (see Section 7 for complete code)

---

## 13. Key Concepts to Remember

1. **PDO = PHP Data Objects** - Database access layer
2. **DSN = Data Source Name** - Connection string
3. **Prepared Statements** - Separate SQL structure from data
4. **setAttribute()** - Configure PDO behavior (use this for beginners)
5. **ATTR_ERRMODE** - Controls error reporting
6. **ATTR_DEFAULT_FETCH_MODE** - Controls data format
7. **ATTR_EMULATE_PREPARES** - Real (false) vs Emulated (true) statements
8. **bindParam()** - Bind variables to placeholders
9. **execute()** - Run the prepared statement
10. **try-catch** - Handle database errors

---

## 14. Practice Exercises

1. Create a form and PHP script to insert a book record (title, author, price)
2. Modify the student insert to also include email and phone number
3. Create a script that inserts 5 students using a loop
4. Add validation to check if age is between 15 and 30
5. Create error handling for different types of database errors

---

## 15. Additional Resources

- PHP PDO Documentation: https://www.php.net/manual/en/book.pdo.php
- SQL Injection Prevention: https://owasp.org/www-community/attacks/SQL_Injection
- Prepared Statements Guide: https://www.php.net/manual/en/pdo.prepared-statements.php

---

**End of Class Notes**

*These notes cover everything discussed about PHP MySQL connectivity using PDO. Study them along with the `insert_student.php` example for complete understanding.*

---

**Created for:** Web Programming Course (CST463)  
**Topic:** PHP PDO Database Connectivity  
**Date:** October 7, 2025
