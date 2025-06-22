<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "final_asp");

if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed"]);
    exit;
}

// Get top 2 ratings with user information
$query = "SELECT r.id, r.rating, r.feedback, r.user_id, r.created_at, 
          u.firstname, u.lastname 
          FROM ratings r 
          LEFT JOIN users u ON r.user_id = u.id 
          ORDER BY r.id DESC 
          LIMIT 2";

$result = $conn->query($query);

if (!$result) {
    echo json_encode(["error" => "Query failed: " . $conn->error]);
    exit;
}

$ratings = [];

while ($row = $result->fetch_assoc()) {
    $ratings[] = [
        'id' => $row['id'],
        'rating' => $row['rating'],
        'feedback' => $row['feedback'],
        'user_id' => $row['user_id'],
        'created_at' => $row['created_at'],
        'firstname' => $row['firstname'],
        'lastname' => $row['lastname'] ? substr($row['lastname'], 0, 1) . '.' : '',
    ];
}

echo json_encode($ratings);
$conn->close();
?>
