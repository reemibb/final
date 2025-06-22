<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");

$conn = new mysqli("localhost", "root", "", "final_asp");

// Check connection
if ($conn->connect_error) {
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

// Get JSON data from the request
$data = json_decode(file_get_contents("php://input"));

// Validate input
if (!isset($data->rating) || !is_numeric($data->rating) || $data->rating < 1 || $data->rating > 5) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid rating value']);
    exit();
}

// Prepare data for insertion
$rating = intval($data->rating);
$feedback = isset($data->feedback) ? mysqli_real_escape_string($conn, $data->feedback) : '';
$userId = isset($data->user_id) && is_numeric($data->user_id) ? intval($data->user_id) : 0;

// SQL query to insert the rating
$sql = "INSERT INTO ratings (user_id, rating, feedback) VALUES (?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iiss", $userId, $rating, $feedback);

// Execute the query and send response
if ($stmt->execute()) {
    http_response_code(201);
    echo json_encode([
        'success' => true,
        'message' => 'Rating submitted successfully',
        'id' => $conn->insert_id
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error submitting rating: ' . $conn->error
    ]);
}

// Close statement and connection
$stmt->close();
$conn->close();
?>
