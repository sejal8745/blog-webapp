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

$email = $dData['email'];
$password = $dData['password'];

$result = "";

if ($email != "" && $password != "") {
    $sql = "SELECT * FROM users WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $result = "Login successful";
    } else {
        $result = "Invalid email or password";
    }

    $stmt->close();
} else {
    $result = "All fields are required!";
}

$conn->close();
$response = array("result" => $result);
echo json_encode($response);
