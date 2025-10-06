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
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        
        $pdo = new PDO($dsn, $username, $password, $options);
        
        // Prepare SQL statement with placeholders
        $sql = "INSERT INTO student (roll_number, name, age, date_of_birth) 
                VALUES (:roll_number, :name, :age, :date_of_birth)";
        
        $stmt = $pdo->prepare($sql);
        
        // Bind parameters
        $stmt->bindParam(':roll_number', $roll_number, PDO::PARAM_STR);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':age', $age, PDO::PARAM_INT);
        $stmt->bindParam(':date_of_birth', $date_of_birth, PDO::PARAM_STR);
        
        // Execute the statement
        $stmt->execute();
        
        // Get the last inserted ID
        $lastInsertId = $pdo->lastInsertId();
        
        // Success message
        echo "<!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Success</title>
            <style>
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }
                body {
                    font-family: Arial, sans-serif;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    min-height: 100vh;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    padding: 20px;
                }
                .message-container {
                    background: white;
                    padding: 40px;
                    border-radius: 10px;
                    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
                    max-width: 500px;
                    width: 100%;
                    text-align: center;
                }
                .success-icon {
                    font-size: 60px;
                    color: #28a745;
                    margin-bottom: 20px;
                }
                h1 {
                    color: #28a745;
                    margin-bottom: 20px;
                }
                .details {
                    background: #f8f9fa;
                    padding: 20px;
                    border-radius: 5px;
                    margin: 20px 0;
                    text-align: left;
                }
                .details p {
                    margin: 10px 0;
                    color: #555;
                }
                .details strong {
                    color: #333;
                }
                .btn-back {
                    display: inline-block;
                    margin-top: 20px;
                    padding: 12px 30px;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    text-decoration: none;
                    border-radius: 5px;
                    font-weight: bold;
                    transition: transform 0.2s;
                }
                .btn-back:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
                }
            </style>
        </head>
        <body>
            <div class='message-container'>
                <div class='success-icon'>✓</div>
                <h1>Student Registered Successfully!</h1>
                <div class='details'>
                    <p><strong>Student ID:</strong> $lastInsertId</p>
                    <p><strong>Roll Number:</strong> $roll_number</p>
                    <p><strong>Name:</strong> $name</p>
                    <p><strong>Age:</strong> $age</p>
                    <p><strong>Date of Birth:</strong> $date_of_birth</p>
                </div>
                <a href='student_form.html' class='btn-back'>Register Another Student</a>
            </div>
        </body>
        </html>";
        
    } catch (PDOException $e) {
        // Handle database errors
        if ($e->getCode() == 23000) {
            // Duplicate entry error
            echo "<!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Error</title>
                <style>
                    * {
                        margin: 0;
                        padding: 0;
                        box-sizing: border-box;
                    }
                    body {
                        font-family: Arial, sans-serif;
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        min-height: 100vh;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        padding: 20px;
                    }
                    .message-container {
                        background: white;
                        padding: 40px;
                        border-radius: 10px;
                        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
                        max-width: 500px;
                        width: 100%;
                        text-align: center;
                    }
                    .error-icon {
                        font-size: 60px;
                        color: #dc3545;
                        margin-bottom: 20px;
                    }
                    h1 {
                        color: #dc3545;
                        margin-bottom: 20px;
                    }
                    p {
                        color: #555;
                        margin-bottom: 20px;
                    }
                    .btn-back {
                        display: inline-block;
                        margin-top: 20px;
                        padding: 12px 30px;
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        color: white;
                        text-decoration: none;
                        border-radius: 5px;
                        font-weight: bold;
                        transition: transform 0.2s;
                    }
                    .btn-back:hover {
                        transform: translateY(-2px);
                        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
                    }
                </style>
            </head>
            <body>
                <div class='message-container'>
                    <div class='error-icon'>✗</div>
                    <h1>Error: Duplicate Roll Number</h1>
                    <p>The roll number <strong>$roll_number</strong> already exists in the database.</p>
                    <p>Please use a different roll number.</p>
                    <a href='student_form.html' class='btn-back'>Go Back</a>
                </div>
            </body>
            </html>";
        } else {
            // Other database errors
            echo "<!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Error</title>
                <style>
                    * {
                        margin: 0;
                        padding: 0;
                        box-sizing: border-box;
                    }
                    body {
                        font-family: Arial, sans-serif;
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        min-height: 100vh;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        padding: 20px;
                    }
                    .message-container {
                        background: white;
                        padding: 40px;
                        border-radius: 10px;
                        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
                        max-width: 500px;
                        width: 100%;
                        text-align: center;
                    }
                    .error-icon {
                        font-size: 60px;
                        color: #dc3545;
                        margin-bottom: 20px;
                    }
                    h1 {
                        color: #dc3545;
                        margin-bottom: 20px;
                    }
                    p {
                        color: #555;
                        margin-bottom: 10px;
                    }
                    .error-details {
                        background: #f8f9fa;
                        padding: 15px;
                        border-radius: 5px;
                        margin: 20px 0;
                        text-align: left;
                        font-family: monospace;
                        font-size: 14px;
                        color: #dc3545;
                    }
                    .btn-back {
                        display: inline-block;
                        margin-top: 20px;
                        padding: 12px 30px;
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        color: white;
                        text-decoration: none;
                        border-radius: 5px;
                        font-weight: bold;
                        transition: transform 0.2s;
                    }
                    .btn-back:hover {
                        transform: translateY(-2px);
                        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
                    }
                </style>
            </head>
            <body>
                <div class='message-container'>
                    <div class='error-icon'>✗</div>
                    <h1>Database Error</h1>
                    <p>An error occurred while inserting data into the database.</p>
                    <div class='error-details'>" . htmlspecialchars($e->getMessage()) . "</div>
                    <a href='student_form.html' class='btn-back'>Go Back</a>
                </div>
            </body>
            </html>";
        }
    }
    
} else {
    // If accessed directly without POST data
    header('Location: student_form.html');
    exit;
}
?>
