<?php

$str = "Nama saya daus Nama saya";

$nonce = 0;

$start = microtime(true);
$hash = getHash($str, $nonce);
echo $hash."\n";

while (strpos($hash, "000000") !== 0){
    $nonce++;
    $hash = getHash($str, $nonce);
    // echo $hash."\n";
}
echo $hash."\n";

function getHash($str, $nonce){
    return md5(json_encode([
        "nonce" => $nonce,
        "data" => $str
    ]));
}
$time_elapsed_secs = microtime(true) - $start;

echo $nonce."\n";
echo $time_elapsed_secs."\n";