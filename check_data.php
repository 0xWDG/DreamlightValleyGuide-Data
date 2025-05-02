<?php

// This script merges all data files into one file
$dirs = [
    "fishing",
    "biomes", 
    "characters", 
    "poi", 
    "quests", 
    "resources", 
    "redeemables", 
    "universes"
];

$requiredKeys = [
    "fishing" => ["name", "description", "found"],
    "biomes" => ["name", "description"],
    "characters" => ["name", "description"],
    "poi" => ["name", "description"],
    "quests" => ["name"],
    "resources" => ["name", "description", "found"],
    "redeemables" => ["name", "description"],
    "universes" => ["name", "description"]
];

$errors = 0;
foreach ($dirs as $directory) {
    $files = glob($directory . "/*.json");

    foreach ($files as $file) {
        // Read the JSON file
        $json = file_get_contents($file);
        $json = json_decode($json, true);
        if ($json === null) {
            echo "Error decoding JSON from file: $file\n";
            continue;
        }

        // Check for required keys
        foreach ($requiredKeys[$directory] as $key) {
            if (!isset($json[$key])) {
                echo "# Missing '$key' key in file: $file\n";
                echo "e \"$file\"" . PHP_EOL;
                $errors++;
            }
        }
    }
}

echo "Check completed.\n";
echo "Total data errors found: $errors\n";
?>
