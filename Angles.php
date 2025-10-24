<?php
$file="lastAngles.txt";
if(file_exists($file)){
  echo file_get_contents($file);
}
?>
