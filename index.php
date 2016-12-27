<?php
function reorderPictureArray($Array, $sFile)
{
    $sArray = null;
    if (($handle = fopen($sFile, 'r')) != FALSE) {
        while (($data = fgetcsv($handle, 0, ";")) != FALSE) {
            $sArray[] = $data;
        }
        $newArray = array();
        foreach ($Array as $index => $value) {
            foreach ($sArray[0] as $sIndex => $sValue) {
                if (strpos($Array[$index], $sValue) !== false) {
                    $newArray[$sIndex] = $value;
                }
            }
        }
        ksort($newArray);
        return $newArray;
    }
}

$catalog = $_GET["catalog"];
if ($catalog == '') {
    $catalog = 'catalog01';
}
//Get picture ID in group
$picid = $_GET["picid"];
if ($picid == '') {
    $picid = 0;
}

//Read in all picture names (filenames)
$imageDir = './' . $catalog . '/pictures/';
$pictureArray = array();
if ($handle = opendir($imageDir)) {
    while (false !== ($file = readdir($handle))) {
        if (substr($file, 0, 1) != '.') {
            $pictureArray[] = $file;
        }
    }
    closedir($handle);
    //$pictureArray = array_values($pictureArray);
}
//var_dump($pictureArray);

// Find all catalogs in current folder and put it into array $catalogArray
$catalogDir = './';
$catalogArray = array();
if ($handle = opendir($catalogDir)) {
    while (false !== ($file = readdir($handle))) {
        if (substr($file, 0, 1) != '.' && substr($file, 0, 7) == 'catalog') {
            $catalogArray[] = $file;
        }
    }
    closedir($handle);
}
//var_dump($catalogArray);

?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Picture Album using Bootstrap</title>
    <meta name="description" content="General picture album display"/>
    <meta name="author" content="Arne Skaanes"/>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script   src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="local.css" rel="stylesheet">

</head>
<body>
<div class="container">
    <h1 class="">General picture album</h1>
    <h2 class="">Using picture-box and bootstrappene</h2>
    <p>Her kommer litt vanlig tekst</p>
    <div class="container">

        <?php
        $fullFilename = sprintf("./%s/%s/%s", $catalog, 'pictures', $pictureArray[$picid]);
        printf('<img class="fullSize" src="./%s" alt="filename" >', $fullFilename);

        // Try to print out selected picture EXIF info
        $exifArray = exif_read_data($fullFilename);
//        var_dump($exifArray);
        if ($exifArray['DateTimeOriginal'] != '') {
            printf('Date: %s <br/>', $exifArray['DateTimeOriginal']);
            printf('Date: %s <br/>', $exifArray['ImageDescription']);
        }

        $thumbnailDir = "thumbnails";
        $i = 0;
        foreach ($pictureArray as $thumbString) {
            printf('<a href="index.php?catalog=%s&picid=%s">', $catalog, $i);
            printf('<img class="%s" src="./%s/%s/%s" alt="thumbnail" >', ($picid == $i) ? 'img-rounded imgThumbSelected' : 'img-rounded', $catalog, $thumbnailDir, $thumbString);
            printf('</a>');
            $i++;
        }
        ?>
    </div>
</div>
<div class="clearAll"></div>
</body>
</html>
