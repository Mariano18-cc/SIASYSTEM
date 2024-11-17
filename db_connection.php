<?php 
// db connection for hrms
$host = '13.215.191.19'; // IP address of your database server
$username = 'group1'; // Your MySQL username
$password = 'SIA101Projects@'; // Your MySQL password
$dbname = 'group1Database'; // Your database name

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection

if ($conn->connect_error) {
    die("Connection failed: ". $conn->connect_error);

}

try {
    // Create a new PDO instance for database connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle any connection errors
    die("Connection failed: " . $e->getMessage());
}
?>