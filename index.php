<?php

$requestMethod = $_SERVER['REQUEST_METHOD'];

$filename = isset($_GET['filename']) ? $_GET['filename'] : "";
$secret_key = isset($_GET['key']) ? $_GET['key'] : "";

$response = [];

if ($requestMethod === "GET") {
    $myfile = fopen("upload/$filename", "r") or die("Unable to open file!");

    $response = [
        "data" => json_decode(fread($myfile, filesize("upload/$filename")))
    ];
    fclose($myfile);
} else if ($requestMethod === "POST") {
    $data = file_get_contents("php://input");

    $method = "aes128";
    $iv_length = openssl_cipher_iv_length($method);
    $iv = openssl_random_pseudo_bytes($iv_length);

    $encrypted_message = openssl_encrypt($data, $method, $secret_key, 0, $iv);

    
    $myfile = fopen("upload/$filename", "w");
    
    fwrite($myfile, json_encode([
        "data" => $encrypted_message
    ]));

    $response = [
        "message" => "success",
    ];
}

header("Content-Type: application/json");
echo json_encode($response);

$cipher = "aes-128-gcm";