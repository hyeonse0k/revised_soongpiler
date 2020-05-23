<?php
  header("Content-type: image/png");
  $x = $_GET['x'];
  $y = $_GET['y'];
  $w = $_GET['w'];
  $h = $_GET['h'];
  $im = imagecreatetruecolor($w, $h);
  $gray=imagecolorallocate($im, 0, 255, 0);
  $red=imagecolorallocate($im, 255, 0, 0);
  $point=array(0, 0, 0, $h, $w, $h, $w, 0);
  imagepolygon($im, $point, 4, $red);
  $px=(imagesx($im) - 7.5 * strlen($string)) / 2;
  imagestring($im, 4, 0, 9, $w, $gray);
  imagepng($im);
  imagedestroy($im);
?>
