<?php

require __DIR__ . '/vendor/autoload.php';

$dataDir = __DIR__ . "/mydb";

$requestMethod = $_SERVER['REQUEST_METHOD'];

$filename = isset($_GET['filename']) ? $_GET['filename'] : "";
$secret_key = isset($_GET['key']) ? $_GET['key'] : "";

$response = [];

$configuration = [
    "auto_cache" => true,
    "cache_lifetime" => null,
    "timeout" => 120,
    "primary_key" => "_id"
];

if ($requestMethod === "GET") {
    
} else if ($requestMethod === "POST") {
    $newsStore = new \SleekDB\Store("news", $dataDir, $configuration);
    $article = [
        "title" => "Google Pixel XL",
        "about" => "Google announced a new Pixel!",
        "author" => [
            "avatar" => "profile-12.jpg",
            "name" => "Foo Bar"
        ]
    ];
    $response[] = $newsStore->insert($article);
}

header("Content-Type: application/json");
echo json_encode($response);