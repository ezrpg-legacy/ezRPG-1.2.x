<?php

define('IN_EZRPG', true);

session_start();
require_once('../init.php');

//$code_length = rand(5,6);
$code_length = 4;
$rand_start = mt_rand(0, 250);
$font = CUR_DIR . '/web/static/fonts/Capture_it.ttf';
$fontSize = 30;
$padding = 10;

$l1 = strtoupper(createKey(1, 1));
$l2 = strtoupper(createKey(1, 1));
$l3 = strtoupper(createKey(1, 1));
$l4 = strtoupper(createKey(1, 1));
$verify_string = $l1 . ' ' . $l2 . ' ' . $l3 . ' ' . $l4;
$real_string = $l1 . $l2 . $l3 . $l4;

$config = unserialize(file_get_contents('../config.php'));

$verify_code = sha1(strtoupper($real_string) . $config['secret'] );
$_SESSION['verify_code'] = $verify_code;
$_SESSION['verify_code2'] = $real_string;
$_SESSION['verify_code3'] = strtoupper($real_string) . $config['secret'];
function makeRBGColor($color, $image)
{
    $color = str_replace("#", "", $color);
    $red = hexdec(substr($color, 0, 2));
    $green = hexdec(substr($color, 2, 2));
    $blue = hexdec(substr($color, 4, 2));
    $out = ImageColorAllocate($image, $red, $green, $blue);

    return $out;
}

$wordBox = imageftbbox($fontSize, 0, $font, $verify_string);

$wordBoxWidth = $wordBox[2];
$wordBoxHeight = $wordBox[1] + abs($wordBox[7]);

$containerWidth = $wordBoxWidth + ($padding * 2);
$containerHeight = $wordBoxHeight + ($padding * 2);

$textX = $padding;
$textY = $containerHeight - $padding;

$captchaImage = imagecreate($containerWidth, $containerHeight);

$red = randColor();
$green = randColor();
$blue = randColor();
$backgroundColor = ImageColorAllocate($captchaImage, $red, $green, $blue);

$rred = 255 - $red;
$rgreen = 255 - $green;
$rblue = 255 - $blue;
$textColor = ImageColorAllocate($captchaImage, $rred, $rgreen, $rblue);

imagefttext($captchaImage, $fontSize, 0, $textX, $textY, $textColor, $font, $verify_string);

$angle = mt_rand(-3, 3);
$captchaImage = imagerotate($captchaImage, $angle, $backgroundColor);


$line = ImageColorAllocate($captchaImage, $rred, $rgreen, $rblue);

for ($i = 0; $i < 10; $i++) {
    $xStart = mt_rand(0, $containerWidth);
    $yStart = mt_rand(0, $containerHeight);
    $xEnd = mt_rand(0, $containerWidth);
    $yEnd = mt_rand(0, $containerHeight);
    imageline($captchaImage, $xStart, $yStart, $xEnd, $yEnd, $line);
}


imagefilter($captchaImage, IMG_FILTER_CONTRAST, 1);
//imagefilter($captchaImage, IMG_FILTER_BRIGHTNESS, 10);
//imagefilter($captchaImage, IMG_FILTER_EDGEDETECT);
imagefilter($captchaImage, IMG_FILTER_GAUSSIAN_BLUR);

header('Content-Type:image/png');
ImagePNG($captchaImage);
?>