<?php

$requestMethod = $_SERVER['REQUEST_METHOD'];

$filename = $_GET['filename'];

$response = [];

if ($requestMethod === "GET") {
    $myfile = fopen("upload/$filename", "r") or die("Unable to open file!");

    $response = [
        "data" => json_decode(fread($myfile, filesize("upload/$filename")))
    ];
    fclose($myfile);
} else if ($requestMethod === "POST") {
    $myfile = fopen("upload/$filename", "w");
    $data = file_get_contents("php://input");
    fwrite($myfile, $data);
    $response = [
        "message" => "success"
    ];
}

header("Content-Type: application/json");
echo json_encode($response);