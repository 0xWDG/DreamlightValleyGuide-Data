<?php

// This script will generate the new data type for version 2.5

$items = array(
    'recipes' => glob('./recipes/*.json'),
    'digging' => glob('./digging/*.json'),
    'fishing' => glob('./fishing/*.json'),
    'foraging' => glob('./foraging/*.json'),
    'gardening' => glob('./gardening/*.json'),
    'mining' => glob('./mining/*.json'),
    'snippets' => glob('./snippets/*.json')
);

$resources = array();
foreach ($items as $dir => $files) {
    foreach ($files as $file) {
        $contents = file_get_contents($file);
        $json = json_decode($contents, true);

        if (!isset($resources[$json['name']])) {
            $resources[$json['name']] = $json;
            $resources[$json['name']]['activity'] = array($dir);
            unset($resources[$json['name']]['icon']);

            if (isset($resources[$json['name']]['giftReward'])) {
                $resources[$json['name']]['gift'] = $resources[$json['name']]['giftReward'];
                unset($resources[$json['name']]['giftReward']);
            }

            // if no price add it with 0
            if (!isset($resources[$json['name']]['price'])) {
                $resources[$json['name']]['price'] = 0;
            }

            // if no found add empty array
            if (!isset($resources[$json['name']]['found'])) {
                $resources[$json['name']]['found'] = array();
            }
        } else {
            if (!in_array($dir, $resources[$json['name']]['activity'])) {
                $resources[$json['name']]['activity'][] = $dir;
            }
        }
    }
}

foreach ($resources as $name => $value) {
    $json = json_encode($value, JSON_PRETTY_PRINT);
    file_put_contents("./resources/$name.json", $json);
}

print_r($resources);
?>