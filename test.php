<?php 
if($_SERVER['REQUEST_METHOD']=='POST'){
    $image_tmp=$_FILES['image']['tmp_name'];
    $image_name=$_FILES['image']['name'];
    $image_type=$_FILES['image']['type'];
    $image_size=$_FILES['image']['size'];

    if(!empty($image_tmp)&& $image_type=='image/jpeg'){
        $image_path='uploads/'.$image_name;
        move_uploaded_file($image_tmp,$image_path);
    }else{
        echo 'Invalid image file';
        exit;
    }

  $image_data = file_get_contents($image_path);
  $image_width = strpos($image_data, "\xFF\xD8\xFF") ? strpos($image_data, "\xFF\xD8\xFF\xE0\x00\x10\x4A\x46\x49\x46\x00\x01") + 16 : strpos($image_data, "\xFF\xD8\xFF\xE1") + 8;
  $image_width = unpack("H*", substr($image_data, $image_width, 4));
  $image_width = hexdec($image_width[1]);

  $image_height = unpack("H*", substr($image_data, $image_width + 4, 4));
  $image_height = hexdec($image_height[1]);

  $screen_sizes = array(
    'small' => 320,
    'medium' => 640,
    'large' => 1024
  );

  foreach ($screen_sizes as $size_name => $size_width) {
    $new_width = $size_width;
    $new_height = ($image_height / $image_width) * $new_width;

    $resized_image_data = '';
    for ($y = 0; $y < $new_height; $y++) {
      for ($x = 0; $x < $new_width; $x++) {
        $src_x = floor($x * $image_width / $new_width);
        $src_y = floor($y * $image_height / $new_height);
         // Bilinear interpolation
         $src_rgb1 = substr($image_data, ($src_y * $image_width * 3) + ($src_x * 3), 3);
         $src_rgb2 = substr($image_data, (($src_y + 1) * $image_width * 3) + ($src_x * 3), 3);
         $src_rgb3 = substr($image_data, ($src_y * $image_width * 3) + (($src_x + 1) * 3), 3);
         $src_rgb4 = substr($image_data, (($src_y + 1) * $image_width * 3) + (($src_x + 1) * 3), 3);

         $red = ($src_rgb1[0] + $src_rgb2[0] + $src_rgb3[0] + $src_rgb4[0]) / 4;
         $green = ($src_rgb1[1] + $src_rgb2[1] + $src_rgb3[1] + $src_rgb4[1]) / 4;
         $blue = ($src_rgb1[2] + $src_rgb2[2] + $src_rgb3[2] + $src_rgb4[2]) / 4;

         $resized_image_data .= chr($red) . chr($green) . chr($blue);
      }
    }
  }

    // Save the resized image
    $resized_image_path = dirname(__FILE__)."/resized_image_{$size_name}.jpg";
    file_put_contents($resized_image_path, $resized_image_data);
 
    echo'<h2>Original Image</h2>';
    echo"<img src='{$image_path}'alt='Original Image'>";

    echo'<h2>Resized image</h2>';
    foreach($screen_sizes as $size_name=>$size_width){
        $resized_image_path=dirname(__FILE__)."/resized_image_{$size_name}.jpg";
        echo"<img src='{$resized_image_path}'alt='Resized Image{$size_name}'>";
    }
  }
?>