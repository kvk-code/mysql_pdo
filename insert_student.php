<?php
/**
 * Student Insert Script using PHP PDO
 * This script handles the insertion of student data into the database
 */

// Database configuration
$host = 'localhost';
$dbname = 'if0_39995906_class';
$username = 'root';  // Change this to your MySQL username
$password = '';      // Change this to your MySQL password

// Check if form is submitted via POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Get form data and sanitize
    $roll_number = trim($_POST['roll_number']);
    $name = trim($_POST['name']);
    $age = (int)$_POST['age'];
    $date_of_birth = $_POST['date_of_birth'];
    
    // Validate input data
    if (empty($roll_number) || empty($name) || empty($age) || empty($date_of_birth)) {
        die("Error: All fields are required!");
    }
    
    try {
        // Create PDO connection
        // Note: For InfinityFree hosting, port specification is required
        $dsn = "mysql:host=$host;port=3306;dbname=$dbname;charset=utf8mb4";
        $pdo = new PDO($dsn, $username, $password);
        
        // Set PDO attributes
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        
        // Prepare SQL statement with placeholders
        $sql = "INSERT INTO student (roll_number, name, age, date_of_birth) 
                VALUES (:roll_number, :name, :age, :date_of_birth)";
        
        $stmt = $pdo->prepare($sql);
        
        // Bind parameters (PDO automatically detects data types)
        $stmt->bindParam(':roll_number', $roll_number);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':age', $age);
        $stmt->bindParam(':date_of_birth', $date_of_birth);
        
        // Execute the statement
        $stmt->execute();
        
        // Success message
        echo "<!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Success</title>
        </head>
        <body>
            <h1>Success!</h1>
            <p>Student registered successfully.</p>
            <p><strong>Roll Number:</strong> $roll_number</p>
            <p><strong>Name:</strong> $name</p>
            <p><strong>Age:</strong> $age</p>
            <p><strong>Date of Birth:</strong> $date_of_birth</p>
            <br>
            <a href='student_form.html'>Register Another Student</a>
        </body>
        </html>";
        
    } catch (PDOException $e) {
        // Handle database errors
        echo "<!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Error</title>
        </head>
        <body>
            <h1>Error!</h1>
            <p>Could not insert student record.</p>
            <p><strong>Error Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>
            <br>
            <a href='student_form.html'>Go Back</a>
        </body>
        </html>";
    }
    
} else {
    // If accessed directly without POST data
    header('Location: student_form.html');
    exit;
}
?>
