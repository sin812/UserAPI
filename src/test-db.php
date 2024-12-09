<?php
require 'db.php';

try {
    $db = connectToDatabase();
    echo "Database connected successfully!";
} catch (Exception $e) {
    echo "Failed to connect to the database: " . $e->getMessage();
}

