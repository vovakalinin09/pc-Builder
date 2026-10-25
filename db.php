<?php
function openDbConnection(): mysqli {
    $host = $_ENV['DB_HOST'] ?? 'db';
    $port = $_ENV['DB_PORT'] ?? 3306;
    $user = $_ENV['DB_USER'] ?? 'root';
    $password = $_ENV['DB_PASS'] ?? 'StrongP@ssw0rd';
    $database = $_ENV['DB_NAME'] ?? 'pcbuilder';
    $conn = new mysqli($host, $user, $password, $database, $port);
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    // Устанавливаем кодировку UTF-8
    $conn->set_charset('utf8mb4');
    return $conn;
}

function pingDbConnection(): bool {
    $conn = openDbConnection();
    $result = $conn->query('SELECT 1');
    $conn->close();
    if (!$result) throw new Exception("Database ping failed");
    return true;
}