<?php

$requestMethod = $_SERVER['REQUEST_METHOD'];

$filename = isset($_GET['filename']) ? $_GET['filename'] : "";
$secret_key = isset($_GET['key']) ? $_GET['key'] : "";

$response = [];

$method = "aes128";
$iv_length = openssl_cipher_iv_length($method);
$iv = openssl_random_pseudo_bytes($iv_length);

if ($requestMethod === "GET") {
    $myfile = fopen("upload/$filename", "r") or die("Unable to open file!");
    $fileJSON = json_decode(fread($myfile, filesize("upload/$filename")), true);

    $decrypted_message = my_decrypt($fileJSON['data'], $secret_key);

    $response = [
        "data" => $decrypted_message
    ];
    fclose($myfile);
} else if ($requestMethod === "POST") {
    $data = file_get_contents("php://input");
    
    $myfile = fopen("upload/$filename", "w");
    
    fwrite($myfile, json_encode([
        "data" => my_encrypt($data, $secret_key)
    ]));

    $response = [
        "message" => "success",
    ];
    fclose($myfile);
}

header("Content-Type: application/json");
echo json_encode($response);


function my_encrypt($data, $key) {
    // Remove the base64 encoding from our key
    $encryption_key = base64_decode($key);
    // Generate an initialization vector
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    // Encrypt the data using AES 256 encryption in CBC mode using our encryption key and initialization vector.
    $encrypted = openssl_encrypt($data, 'aes-256-cbc', $encryption_key, 0, $iv);
    // The $iv is just as important as the key for decrypting, so save it with our encrypted data using a unique separator (::)
    return base64_encode($encrypted . '::' . $iv);
}

function my_decrypt($data, $key) {
    // Remove the base64 encoding from our key
    $encryption_key = base64_decode($key);
    // To decrypt, split the encrypted data from our IV - our unique separator used was "::"
    list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
    return openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv);
}