
<?php
$sourceFolder = './'; // Replace with the actual source folder path
$destinationFolder = './dist'; // Replace with the actual destination folder path

// Create the destination folder if it doesn't exist
if (!is_dir($destinationFolder)) {
    mkdir($destinationFolder, 0777, true);
}

// Get all files in the source folder
$files = scandir($sourceFolder);

// Loop through each file
foreach ($files as $file) {
    if ($file !== '.' && $file !== '..') {
        $sourceFilePath = $sourceFolder . '/' . $file;
        $destinationFilePath = $destinationFolder . '/' . $file;

        // Move the file to the destination folder
        if (rename($sourceFilePath, $destinationFilePath)) {
            echo 'File ' . $file . ' moved successfully.<br>';
        } else {
            echo 'Failed to move file ' . $file . '.<br>';
        }
    }
}
?>

