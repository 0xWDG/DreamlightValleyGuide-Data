<?php

$files = glob("*/*.json");

foreach ($files as $file) {
    $json = file_get_contents($file);
    $json = json_decode($json, true);

    if (isset($json['yield'])) {
        $json['yield'] = intVal($json['yield']);
    }
    
    ksort($json);

    file_put_contents($file, json_encode($json, JSON_PRETTY_PRINT));
}

?>
