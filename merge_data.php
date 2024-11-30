<?php

// This script merges all data files into one file

$files = glob("*/*.json");

$data = [];

foreach ($files as $file) {
    $category = explode("/", $file)[0];
    $f = file_get_contents($file);
    $json = json_decode($f, true);

    $data[$category][] = $json;
}

file_put_contents("data.json", json_encode($data));
file_put_contents("data_pp.json", json_encode($data, JSON_PRETTY_PRINT));

?>