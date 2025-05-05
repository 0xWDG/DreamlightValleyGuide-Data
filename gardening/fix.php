<?php

$files = glob("*.json");

foreach ($files as $file) {
    $json = file_get_contents($file);
    $json = json_decode($json, true);

    if (isset($json['waterings'])) {
        $json['waterings'] = intVal($json['waterings']);
    }
    
    if (!isset($json['water'])) {
        $json['water'] = $json['waterings'] ?? 0;
    }

    if (!isset($json['seed'])) {
        $json['seed'] = $json['price'] ?? 0;
    }

    if (!isset($json['sell'])) {
        $json['sell'] = $json['price'] ?? 0;
    }

    if (!isset($json['profit'])) {
        $json['profit'] = $json['sellPrice'] - $json['seedPrice'];
    }

    if (!isset($json['coins'])) {
        $json['coins'] = strval($json['sellPrice'] - $json['seedPrice']);
    } else {
        $json['coins'] = strval($json['coins']);
    }

    ksort($json);

    file_put_contents($file, json_encode($json, JSON_PRETTY_PRINT));
}

?>