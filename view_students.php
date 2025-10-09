<?php
/**
 * Student View Script using PHP PDO
 * This script displays all student records from the database
 */

// Database configuration
$host = 'localhost';
$dbname = 'if0_39995906_class';
$username = 'root';  // Change this to your MySQL username
$password = '';      // Change this to your MySQL password

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>View Students</title>
</head>
<body>
    <h1>Student Records</h1>

<?php
try {
    // Create PDO connection
    // Note: Port specification is required for some hosting providers like InfinityFree
    $dsn = "mysql:host=$host;port=3306;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password);
    
    // Set PDO attributes
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    
    // Prepare SQL SELECT statement
    $sql = "SELECT id, roll_number, name, age, date_of_birth, created_at 
            FROM student 
            ORDER BY created_at DESC";
    
    $stmt = $pdo->prepare($sql);
    
    // Execute the statement
    $stmt->execute();
    
    // Fetch all results
    $students = $stmt->fetchAll();
    
    // Check if there are any records
    if (count($students) > 0) {
        echo "<p><strong>Found " . count($students) . " student record(s).</strong></p>";
        
        echo "<table border='1'>";
        echo "<tr>
                <th>ID</th>
                <th>Roll Number</th>
                <th>Name</th>
                <th>Age</th>
                <th>Date of Birth</th>
                <th>Registered On</th>
              </tr>";
        
        // Loop through each student and display
        foreach ($students as $student) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($student['id']) . "</td>";
            echo "<td>" . htmlspecialchars($student['roll_number']) . "</td>";
            echo "<td>" . htmlspecialchars($student['name']) . "</td>";
            echo "<td>" . htmlspecialchars($student['age']) . "</td>";
            echo "<td>" . htmlspecialchars($student['date_of_birth']) . "</td>";
            echo "<td>" . htmlspecialchars($student['created_at']) . "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
        
    } else {
        echo "<p>No student records found in the database.</p>";
    }
    
} catch (PDOException $e) {
    // Handle database errors
    echo "<h2>Error!</h2>";
    echo "<p>Could not retrieve student records.</p>";
    echo "<p><strong>Error Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>

    <br><br>
    <a href="student_form.html">Add New Student</a> | 
    <a href="view_students.php">Refresh</a>

</body>
</html>
