<?php

$openVSCode = false;

if (isset($argv[1]) && $argv[1] == "fix") {
    $openVSCode = true;
}

// This script merges all data files into one file
$dirs = [
    "digging",
    "fishing",
    "foraging",
    "gardening",
    "recipes",
    "mining",
    "biomes", 
    "characters", 
    "poi", 
    "quests", 
    "resources", 
    "redeemables", 
    "universes",
    "realms"
];

$requiredKeys = [
    "digging" => ["name", "description", "found"],
    "fishing" => ["name", "description", "found", "rarity", "energy"],
    "foraging" => ["name", "description", "found"],
    // Legacy 'gardening' data set
    "gardening" => ["name", "description", "icon", "type", "found", "biome", "grow", "water", "yield", "seed", "sell", "profit", "coins"],
    // Legacy Recipes data set
    "recipes" => ["name", "description", "icon", "type", "stars", "energy", "price", "ingredients"],
    "mining" => ["name", "description", "found"],
    "biomes" => ["name", "description"],
    "characters" => ["name", "description"],
    "poi" => ["name", "description"],
    "quests" => ["name"],
    "resources" => ["name", "description", "found", "activity", "found"],
    "redeemables" => [/*"name", */"description"],
    "universes" => ["name", "description"],
    "realms" => ["name", "description", "price"],
];

$warnings = 0;
$errors = 0;
foreach ($dirs as $directory) {
    $files = glob($directory . "/*.json");

    foreach ($files as $file) {
        // Read the JSON file
        $json = file_get_contents($file);
        $json = json_decode($json, true);
        if ($json === null) {
            if ($openVSCode) {
                exec("code \"$file\"");
            }
            echo "Error decoding JSON from file: $file\n";
            continue;
        }

        if (
            isset($json['found']) &&
            !empty($json['found']) &&
            is_array($json['found'][0])
        ) {
            echo "Found is array in array in file: $file, autofixing\n";

            $json['found'] = $json['found'][0];
            file_put_contents($file, json_encode($json, JSON_PRETTY_PRINT));

            continue;
        }
        if (
            in_array($directory, ["biomes", "resources", "fishing", "digging", "foraging", "gardening", "mining"]) &&
            !is_numeric($json['price'])
        ) {
            $json['price'] = intVal($json['price']);
            file_put_contents($file, json_encode($json, JSON_PRETTY_PRINT));
            echo "# Error: 'price' is not numeric in file: $file, Tried to autocorrect\n";
            $warnings++;
            continue;
        }

        if (
            in_array($directory, ["resources", "fishing"]) &&
            !isset($json['rarity'])
        ) {
            $json['rarity'] = "none";
            file_put_contents($file, json_encode($json, JSON_PRETTY_PRINT));
            echo "# Error: 'rarity' is missing in file: $file, Tried to autocorrect\n";
            $warnings++;
            continue;
        }

        // Check for required keys
        foreach ($requiredKeys[$directory] as $key) {
            if (!isset($json[$key])) {
                $json['description'] = '';
                file_put_contents($file, json_encode($json, JSON_PRETTY_PRINT));
                echo "# Warning: Missing '$key' key in file: $file (autofix)\n";
                if ($openVSCode) {
                    exec("code \"$file\"");
                }

                $errors++;
            } else {
                if (
                    $key != "found" &&
                    $json[$key] == ""
                ) {
                    echo "# Warning: '$key' key is empty in file: $file\n";
                    if ($openVSCode) {
                        exec("code \"$file\"");
                    }
                    $warnings++;
                }
            }
        }
    }
}

echo "Check completed.\n";
echo "Total data warnings found: $warnings\n";
echo "Total data errors found: $errors\n";
echo "To fix: php check_data.php fix\n";
echo "Fixing is a manual process, files will be opened in VSCode\n";
?>
