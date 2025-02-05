<?php
$folderPath = './'; // Replace with the actual folder path

// Get all files in the folder
$files = scandir($folderPath);

// Loop through each file
foreach ($files as $file) {
    if ($file !== '.' && $file !== '..') {
        $filePath = $folderPath . '/' . $file;
        $fileName = basename($file);

        // Generate the href link
        $link = '<a href="' . $filePath . '">' . $fileName . '</a>';

        // Output the link
        echo $link . '<br>';
    }
}
?>