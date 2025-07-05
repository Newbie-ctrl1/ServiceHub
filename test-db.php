<?php
try {
    $pdo = new PDO('mysql:host=metro.proxy.rlwy.net;port=37070;dbname=railway', 'root', 'WNPsqSWJVMfZgZKxBUCBLmnDqDjvaQEM');
    echo "Connection successful!\n";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}