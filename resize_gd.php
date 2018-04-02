<?php

// 1_400x400.jpg
// 以下可要可不要，web服务器会重定向没有的资源
// -------------------------------------
$pat = $_GET;
$resstr='';
$iserror=false;
if(!array_key_exists("src",$pat)){
    $iserror=true;
    $resstr = "缺少图片名称";
}
if(!array_key_exists("src",$pat)){
    $iserror=true;
    $resstr = "缺少图片名称";
}
if(!array_key_exists("src",$pat)){
    $iserror=true;
    $resstr = "缺少图片名称";
}
if(!array_key_exists("src",$pat)){
    $iserror=true;
    $resstr = "缺少图片名称";
}
if($iserror){
    echo $resstr;
    exit;
}
// ------------------------------------

$src = $_GET['src']; 
$width = $_GET['w']; 
$height = $_GET['h']; 
$type = $_GET['type']; 


// 原图片所在路径
$image_src = "./uploads/images/";
// 缩略图所在路径
$image_thumb = "./uploads/thumbs/";

$filename = $src.'.'.$type;

$srcfilepath = $image_src.$filename;
$thumbfilepath = sprintf("%s%s_%sx%s.%s",$image_thumb,$src,$width,$height,$type);

if(file_exists($srcfilepath)){
    if(file_exists($thumbfilepath)){
        header('Content-Type: image/'.$type);
        echo file_get_contents($thumbfilepath);
    }else{
        createExistsImage($srcfilepath,$thumbfilepath,$width,$height,$type);
    }
}else{

    $str = $width."x".$height;
    $font_size = ($width*0.3+$height*0.3)/5;
    NoExistsImage($str ,$font_size , $width , $height);

}



function createExistsImage($uploadfile,$thumbfilepath,$dstW,$dstH,$type){

    header('Content-Type: image/'.$type);

    // $dstW = 200; //设定缩略图的宽度 
    // $dstH = 200; //设定缩略图的高度 

    if(strtoupper($type)=='PNG'){
        $src_image = ImageCreateFromPNG($uploadfile);
    }
    if(strtoupper($type)=='JPG'){
        $src_image = ImageCreateFromJPEG($uploadfile);
    }

    //读取JPEG文件并创建图像对象 
    $srcW = ImageSX($src_image); //获得图像的宽 
    $srcH = ImageSY($src_image); //获得图像的高 

    if($dstW>$srcW){
        $dstW=$srcW;
    }
    if($dstH>$srcH){
        $dstH=$srcH;
    }
    $dst_image = ImageCreateTrueColor($dstW,$dstH);
    //创建新的图像对象 
    ImageCopyResized($dst_image,$src_image,0,0,0,0,$dstW,$dstH,$srcW,$srcH);

    //将图像重定义大小后写入新的图像对象 
    if(strtoupper($type)=='PNG'){
        imagepng($dst_image,$thumbfilepath);
    }
    if(strtoupper($type)=='JPG'){
        imagejpeg($dst_image,$thumbfilepath);
    }
    //读取图片
    echo file_get_contents($thumbfilepath);
    //刷新到浏览器
    // imagedestroy($dst_image);

}



function NoExistsImage($str,$font_size,$width,$height){

     $font_name="1.ttf";

     $im = imagecreate($width, $height);

     $ttf_font_size = calculateTextBox($font_size, 0, $font_name, $str);

    //  中点位置，
     $cx = (imagesx($im)/2) - ($ttf_font_size['width']/2);

     $cy = (imagesy($im)/2) + ($ttf_font_size['height']/2);  

     // 背景和文本
     $bg = imagecolorallocate($im, 238, 238, 238);

     $textcolor = imagecolorallocate($im, 170, 170, 170);


     imagettftext($im, $font_size, 0,  $cx+1, $cy+1, $textcolor, $font_name, $str);

     imagettftext($im, $font_size, 0,  $cx, $cy, $textcolor, $font_name, $str);
 

     // 输出图像
     header("Content-type: image/png");

    // if(strtoupper($type) == 'PNG'){
    //     imagepng($im);
    // }
    // if(strtoupper($type) == 'JPG'){
    //      imagejpeg($im);
    // }
     imagepng($im);

     imagedestroy($im);
}

function calculateTextBox($font_size, $font_angle, $font_file, $text) { 
    $box   = imagettfbbox($font_size, $font_angle, $font_file, $text); 
    if( !$box ) 
      return false; 
    $min_x = min( array($box[0], $box[2], $box[4], $box[6]) ); 
    $max_x = max( array($box[0], $box[2], $box[4], $box[6]) ); 
    $min_y = min( array($box[1], $box[3], $box[5], $box[7]) ); 
    $max_y = max( array($box[1], $box[3], $box[5], $box[7]) ); 
    $width  = ( $max_x - $min_x ); 
    $height = ( $max_y - $min_y ); 
    $left   = abs( $min_x ) + $width; 
    $top    = abs( $min_y ) + $height; 
    // to calculate the exact bounding box i write the text in a large image 
    $img     = @imagecreatetruecolor( $width << 2, $height << 2 ); 
    $white   =  imagecolorallocate( $img, 255, 255, 255 ); 
    $black   =  imagecolorallocate( $img, 0, 0, 0 ); 
    imagefilledrectangle($img, 0, 0, imagesx($img), imagesy($img), $black); 
    // for sure the text is completely in the image! 
    imagettftext( $img, $font_size, 
                  $font_angle, $left, $top, 
                  $white, $font_file, $text); 
    // start scanning (0=> black => empty) 
    $rleft  = $w4 = $width<<2; 
    $rright = 0; 
    $rbottom   = 0; 
    $rtop = $h4 = $height<<2; 
    for( $x = 0; $x < $w4; $x++ ) 
      for( $y = 0; $y < $h4; $y++ ) 
        if( imagecolorat( $img, $x, $y ) ){ 
          $rleft   = min( $rleft, $x ); 
          $rright  = max( $rright, $x ); 
          $rtop    = min( $rtop, $y ); 
          $rbottom = max( $rbottom, $y ); 
        } 
    // destroy img and serve the result 
    imagedestroy( $img ); 
    return array( "left"   => $left - $rleft, 
                  "top"    => $top  - $rtop, 
                  "width"  => $rright - $rleft + 1, 
                  "height" => $rbottom - $rtop + 1 ); 
  } 