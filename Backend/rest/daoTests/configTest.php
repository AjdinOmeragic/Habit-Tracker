<?php
require_once __DIR__ . '/../config.php';

echo "<h1>🔍 Database Data Check</h1>";

try {
    $connection = new PDO(
        "mysql:host=" . Config::DB_HOST() . ";dbname=" . Config::DB_NAME() . ";port=" . Config::DB_PORT(),
        Config::DB_USER(),
        Config::DB_PASSWORD()
    );
    
    echo "✅ <strong>Connected to database!</strong><br><br>";
    
    // Show all data from each table
    $tables = ['users', 'habits', 'habit_completions', 'posts', 'comments', 'post_likes'];
    
    foreach ($tables as $table) {
        echo "<h3>📋 $table</h3>";
        $stmt = $connection->query("SELECT * FROM $table");
        $data = $stmt->fetchAll();
        
        if (count($data) > 0) {
            echo "<pre>";
            print_r($data);
            echo "</pre>";
        } else {
            echo "No data found<br>";
        }
        echo "<hr>";
    }
    
} catch (Exception $e) {
    echo "❌ <strong>Error:</strong> " . $e->getMessage();
}