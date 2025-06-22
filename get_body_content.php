<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "final_asp");

if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed"]);
    exit;
}

$result = $conn->query("SELECT name, content FROM body_content");

$content = [];

while ($row = $result->fetch_assoc()) {
    $content[$row['name']] = $row['content'];
}

echo json_encode($content);
$conn->close();
?>
