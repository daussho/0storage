<?php

$requestMethod = $_SERVER['REQUEST_METHOD'];

$filename = $_GET['filename'];

if ($requestMethod === "GET") {
    echo "GET";
} else if ($requestMethod === "POST") {
    $myfile = fopen("upload/$filename", "w");
    $data = file_get_contents("php://input");
    fwrite($myfile, $data);
    
}

header("Content-Type: application/json");
echo json_encode([
    "message" => "success"
]);