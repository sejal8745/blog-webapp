<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");

$conn = new mysqli("localhost", "root", "", "blog");

if (mysqli_connect_error()) {
    $response = array("result" => "Database connection error");
    echo json_encode($response);
    exit();
}

$eData = file_get_contents("php://input");
$dData = json_decode($eData, true);

// $id = $dData['id'];
$username = $dData['username'];
$email = $dData['email'];
$password = $dData['password'];
$result = "";
error_reporting(E_ALL);
ini_set('display_errors', '1');

if ($username != "" && $email != "" && $password != "") {
    $sql = "INSERT INTO users (username, email, password) VALUES ( ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        $result = "You have registered successfully!";
    } else {
        $result = "Registration failed: " . $stmt->error;
    }

    $stmt->close();
} else {
    $result = "All fields are required!";
}

$conn->close();
$response = array("result" => $result);
echo json_encode($response);
