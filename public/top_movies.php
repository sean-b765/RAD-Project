<?php
    
    // TO-DO : Detect viewport size using javascript, pass this to php variable.
    // Adjust PHP GD image accordingly

    require_once '../private/initialise.php';

    // Use PHP GD to create a chart like in AT2.5 - show SearchAmount
    $sql = "SELECT SearchAmount, Title, ID FROM top_searches ORDER BY SearchAmount DESC LIMIT 10";
    $result = query($sql);

    // SearchAmount & Title arrays
    $search_array = array();
    $title_array = array();

    if (mysqli_num_rows($result) > 0) {
        // append top 10 records to arrays. They will be accessible using [0-9]
        //      and will match still
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($search_array, $row["SearchAmount"]);
            array_push($title_array, $row["Title"]);
        }
    }

    // column count (top 10)
    $columns = 10;

    // width / height
    $width = 800;
    $height = 600;
    // height divisor will position the columns along the y-axis, changing their starting height
    $height_divisor = 1.5;

    // padding between columns
    $padding = 5;
    $col_width = $width / $columns;

    // graph color variables & instantiate image resource
    $image     = imagecreatetruecolor($width, $height+50);
    $gray      = imagecolorallocate($image, 0xcc, 0xcc, 0xcc);
    $blue      = imagecolorallocate($image, 0x00, 0x00, 0xcc);
    $red       = imagecolorallocate($image, 0xcc, 0x00, 0x00);
    $gray_lite = imagecolorallocate($image, 0xee, 0xee, 0xee);
    $gray_dark = imagecolorallocate($image, 0x7f, 0x7f, 0x7f);
    $white     = imagecolorallocate($image, 0xff, 0xff, 0xff);
    $green     = imagecolorallocate($image, 0x00, 0xff, 0x00);
    $black     = imagecolorallocate($image, 0x00, 0x00, 0x00);

    // fill background
    imagefill($image, $width, $height, $gray);

    // get the max value
    $max_val = max($search_array);

    for ($i = 0; $i < $columns; $i++) {
        // get the integer value of the search amount
        $amount_searches = intval($search_array[$i]);
        // set the column height accordingly
        $col_height = $amount_searches * 15;
                
        // alter x1, x2, y1, y2 for creating column rectangles
        $x1 = $i * $col_width + 4;
        $y1 = ($height/$height_divisor)- $col_height;
        $x2 = (($i+1) * $col_width) - $padding;
        $y2 = ($height/$height_divisor) - 10;

        // write the columns over the background
        imagefilledrectangle($image, $x1, $y1, $x2, $y2, $blue);
            
        $string = "Top 10 Most Searched";
        // get the width of the string to center it
        $px = (imagesx($image) - 7.5 * strlen($string)) / 2;
        imagestring($image, 9, $px, 20, $string, $red);
            

        // print Title under columns
        // Need to make a new image with only the Title on it,
        //      so we can rotate the Title and make it fit under columns
        $title = $title_array[$i];
        $font_size = 9;
            
        // create the title image
        $title_img = imagecreatetruecolor(200, 100);

        imagefill($title_img, 200, 100, $white);
        $th = imagefontheight($font_size);  // get height / width of string
        $tw = strlen($title) * imagefontwidth($font_size);
            
        // generate string onto new image
        imagestring($title_img, $font_size, 0, 0, $title, $green);
        // rotate new image
        $im2 = imagerotate($title_img, -90.0, 0);

        // crop image to only fit Title text
        $im2 = imagecropauto($im2);
            
        // w x h of title image
        $w = imagesx($im2);
        $h = imagesy($im2);

        // place on original image
        imagecopymerge($image, $im2, $x1, $height/$height_divisor, 0, 0, $w, $h, 100);
        // then print the frequency
        imagestring($image, 9, $x1, $y1, $amount_searches, $white);
    }

    header('Content-type: image/png');
    imagepng($image);
    
?>
