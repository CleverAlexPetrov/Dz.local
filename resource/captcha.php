<?php
session_start();

$Random = rand(101001, 999999);
$_SESSION['captcha'] = $Random;
//$_SESSION['captcha'] = md5($Random);
$im = imagecreatetruecolor(110, 30);
imagefilledrectangle($im, 0, 0, 110, 30, imagecolorallocate($im, 212, 75, 56));
imagefttext($im, 30, 0, 15, 23, imagecreatetruecolor($im, 255, 255, 255), '/resource/font.ttf', $Random);

header('Expires: Wed, 1 Jan 1997 00:00:00 GMT');
header('Last-Modified: ' . gmdate('D, Y m d H:i:s') . ' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
header('Content-type: image/gif');

imagegif($im);
imagedestroy($im);



