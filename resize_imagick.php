<?php
// echo $_SERVER['DOCUMENT_ROOT'];
function getThumbImg($src,$w,$h,$type) { 
  global $thumbs; 
  return $_SERVER['DOCUMENT_ROOT'].$thumbs.$src.'_'.$w.'_'.$h.'.'.$type; 
} 
function imgResize($f,$th,$w,$h,$t) { 
  $imagick = new Imagick(); 

  // echo $_SERVER['DOCUMENT_ROOT'].'/'.$f;
  // die;
  $imagick->readImage($_SERVER['DOCUMENT_ROOT'].'/'.$f); 
  $width = $imagick->getImageWidth(); 
  $height = $imagick->getImageHeight(); 
  if(!$w||!$h||$w>=$width||$h>=$height){ 
    header('Content-Type:image/'.$t); 
    echo file_get_contents($_SERVER['DOCUMENT_ROOT'].'/'.$f); 
  }else{ 
    $imagick->stripImage(); 
    $imagick->cropThumbnailImage($w, $h); 
    $imagick->writeImage($th); 
    header('Content-Type:image/'.$t); 
    echo $imagick->getImageBlob(); 
    $imagick->clear(); 
    $imagick->destroy(); 
  } 
} 
$uploadDir = "uploads/images/"; 
$thumbs = "uploads/thumbs/"; 
$src = $_GET['src']; 
$width = $_GET['w']; 
$height = $_GET['h']; 
$type = $_GET['type']; 
$imgFile = $uploadDir.$src.'.'.$type; 
$notFound = '不好意思，该图片不存在或已被删除!'; 
$thumb = getThumbImg($src,$width,$height,$type); 
echo $thumb;
if(file_exists($imgFile)){ 
  if(file_exists($thumb)){ 
    header('Content-Type:image/'.$type); 
    echo file_get_contents($thumb); 
  }else{ 
    imgResize($imgFile,$thumb,$width,$height,$type); 
  } 
}else{ 
  header("HTTP/1.0 404 Not Found"); 
  header("status: 404"); 
  echo $notFound; 
}  