<?php
// comments.php

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");
// Include your database connection file

$conn = new mysqli("localhost", "root", "", "blog");

if (mysqli_connect_error()) {
    $response = array("result" => "Database connection error");
    echo json_encode($response);
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Add comment
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $post_id = $data['post_id'];
    $user_id = $data['user_id'];
    $parent_comment_id = $data['parent_comment_id'];
    $comment_text = $data['comment_text'];

    $stmt = $pdo->prepare("INSERT INTO comments (post_id, user_id, parent_comment_id, comment_text) VALUES (?, ?, ?, ?)");
    $stmt->execute([$post_id, $user_id, $parent_comment_id, $comment_text]);

    echo json_encode(['message' => 'Comment added successfully']);
}

// Get comments for a post
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $post_id = $_GET['post_id'];

    $stmt = $pdo->prepare("SELECT * FROM comments WHERE post_id = ?");
    $stmt->execute([$post_id]);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($comments);
}
