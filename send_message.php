<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "final_asp");

if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed"]);
    exit;
}

// Read JSON input from Angular
$data = json_decode(file_get_contents("php://input"), true);

// Sanitize and extract values
$user_id = isset($data['user_id']) && is_numeric($data['user_id']) ? intval($data['user_id']) : null;
$name = trim($data['name'] ?? '');
$email = trim($data['email'] ?? '');
$subject = trim($data['subject'] ?? '');
$message = trim($data['message'] ?? '');

// Basic validation
if ($name && $email && $subject && $message) {

    // Prepare SQL
    $stmt = $conn->prepare("INSERT INTO messages (user_id, name, email, subject, message) VALUES (?, ?, ?, ?, ?)");
    
    // Bind values (user_id can be null)
    $stmt->bind_param("issss", $user_id, $name, $email, $subject, $message);

    if ($stmt->execute()) {
        echo json_encode([
            "success" => true,
            "message" => "Message saved successfully"
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "error" => "Database error: " . $stmt->error
        ]);
    }

    $stmt->close();
} else {
    // One or more fields are missing
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "error" => "Please fill in all required fields."
    ]);
}

$conn->close();
?>
