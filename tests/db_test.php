<?php
require_once 'config.php';
require_once 'includes/db_connect.php';

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Database Connection Test</h1>";

try {
    // Test the connection
    echo "<p>Connecting to database: " . DB_NAME . " on " . DB_HOST . "...</p>";
    
    // Check if connection is successful
    if ($pdo) {
        echo "<p style='color: green;'>✓ Database connection successful!</p>";
        
        // Test a simple query
        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        echo "<h2>Database Tables:</h2>";
        echo "<ul>";
        foreach ($tables as $table) {
            echo "<li>" . htmlspecialchars($table) . "</li>";
        }
        echo "</ul>";
        
        // Test a simple insert and select
        echo "<h2>Testing Insert and Select:</h2>";
        
        // Create a temporary test table if it doesn't exist
        $pdo->exec("CREATE TABLE IF NOT EXISTS test_table (
            id INT AUTO_INCREMENT PRIMARY KEY,
            test_data VARCHAR(100),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
        
        // Insert a test record
        $testData = "Test data: " . date('Y-m-d H:i:s');
        $stmt = $pdo->prepare("INSERT INTO test_table (test_data) VALUES (?)");
        $result = $stmt->execute([$testData]);
        
        if ($result) {
            echo "<p style='color: green;'>✓ Test INSERT successful!</p>";
            
            // Select the record back
            $stmt = $pdo->query("SELECT * FROM test_table ORDER BY id DESC LIMIT 5");
            $records = $stmt->fetchAll();
            
            echo "<p>Last 5 test records:</p>";
            echo "<ul>";
            foreach ($records as $record) {
                echo "<li>" . htmlspecialchars($record['test_data']) . " (ID: " . $record['id'] . ")</li>";
            }
            echo "</ul>";
        } else {
            echo "<p style='color: red;'>✗ Test INSERT failed!</p>";
        }
    }
} catch (PDOException $e) {
    echo "<p style='color: red;'>✗ Database error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>

<p><a href="index.php">Return to homepage</a></p>
