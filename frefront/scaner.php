
<?php
$folderPath = '.'; // Replace with the actual folder path
$addedPath = '/dist/index.html';

// Get all files in the folder
$files = scandir($folderPath);

$newContent ='';

// Loop through each file
foreach ($files as $file) {
    if ($file !== '.' && $file !== '..') {
        $filePath = $folderPath . '/' . $file;
        $fileName = basename($file);

        // Generate the href link
        $link = '<li><a href="' ."frefront/". $filePath . $addedPath. '">' . $fileName .'</a></li>';

        // Output the link
        echo $link . '<br>';
        $newContent .= $link . '<br>';
    }
}



// Write the new content to the file
file_put_contents('freefrontlinks.html', $newContent);
?>