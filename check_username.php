<?php
include_once("db_connection.php");

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$username = $input['username'];

$stmt = $pdo->prepare("SELECT COUNT(*) AS count FROM Users WHERE userName = ?");
$stmt->execute([$username]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row['count'] > 0) {
    echo json_encode(['available' => false]);
} else {
    echo json_encode(['available' => true]);
}
?>
