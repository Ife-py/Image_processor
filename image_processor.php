<?php 
if($_SERVER["REQUEST_METHOD"]!="POST"){
    header('location:index.php');
    exit;
}
if($_SERVER["REQUEST_METHOD"]=="POST"){
    $imageFiles=$_FILES['image'];

    if(empty($imageFiles)){
        echo"<script>
                alert('Please select an Image to Upload');
                window.location.href='index.php';        
            </script>";
        
    }

    if($imageFiles['error']==0){
        
            $imageName=$imageFiles['name'];

            $imageType=$imageFiles['type'];
            $image_info=$imageFiles['tmp_name'];

            $image_data=file_get_contents($image_info);
            $imageResource=imagecreatefromstring($image_data);
            if(in_array($imageType,array('image/jpeg','image/png','image/gif'))){
                // moving the image to a directory
                $uploadDir='uploads/';
                $uploadfile=$uploadDir.$imageName;
                move_uploaded_file($imageFiles['tmp_name'], $uploadfile);
                // resizing the image
                
                $imageWidth=imagesx($imageResource);
                $imageheight=imagesy($imageResource);
                // array containing size for different size
                $sizes=array(
                    'pc'=>array('width'=>700,'height'=>500),
                    'pc_thumbnail'=>array('width'=>200,'height'=>180),
                    'tablet'=>array('width'=>500,'height'=>300),
                    'tablet_thumbnail'=>array('width'=>160,'height'=>80),
                    'mobile_device'=>array('width'=>400,'height'=>200),
                    'mobile_device_thumbnail'=>array('width'=>100,'height'=>50),
                );
                
                
                foreach($sizes as $sizeName=>$sizeDimensions){
                    $width=$sizeDimensions['width'];
                    $height=$sizeDimensions['height'];
                    $newImageResource=imagecreatetruecolor($width,$height);
                    imagecopyresampled($newImageResource,$imageResource,0,0,0,0,$width,$height,$imageWidth,$imageheight);
                    
                    $imagepath='uploads/resized_'.$sizeName.$imageName;
                    imagejpeg($newImageResource,$imagepath,90);
                } 
            }else{
                echo'Invalid image file type';
            }
        }else {
        echo'Error uploading image';
    }
    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/bootstrap.css">
    <style>
    body {
        background-image: linear-gradient(to bottom, #f2f2f2, #fff);
        height: 100vh;
        margin: 0;
        padding: 0;
        }
    .container {
        margin-top: 5rem;
    }
    nav {
        position:fixed;
        float: right;
    }
    </style>
</head>
<body>
    <div class="`container">
        <nav>
            <a href="index.php" class="btn btn-primary">Logout</a>
        </nav>
        <div class="row">
            <div class="col-md-6">
                <p><b>Screensize images for Pc</b></p>
                <?php 
                    $sizeName='pc';
                    $resizedImage='uploads/resized_'.$sizeName.$imageName;
                    echo '<img src="' . $resizedImage . '"class="img-thumbnail" alt="' . $imageName . '">';
                ?>
                <?php 
                    $sizeName='pc_thumbnail';
                    $resizedImage='uploads/resized_'.$sizeName.$imageName;
                                    
                    $start=1;
                    $end=3;
                    for($i=$start;$i<=$end;$i++){
                        echo '<img src="' . $resizedImage .'"alt="'. $imageName . '"class="img-thumbnail">';
                    }
                ?>
            </div>
            <div class="col-md-6">
                <p><b>Screensize images for Tablet</b></p>
                 <?php
                    $sizeName='tablet';
                    $resizedImage='uploads/resized_'.$sizeName.$imageName;
                    echo '<img src="' . $resizedImage . '"class="img-thumbnail" alt="' . $imageName . '">';
                    ?>
                <?php
                    $sizeName='tablet_thumbnail';
                    $resizedImage='uploads/resized_'.$sizeName.$imageName;
                    $start=1;
                    $end=3;
                    for($i=$start;$i<=$end;$i++){
                        echo '<img src="' . $resizedImage . '"class="img-thumbnail" alt="' . $imageName . '">';
                    }
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <p><b>Screensize for mobile devices</b></p>
                <?php 
                    $sizeName='mobile_device';
                    $resizedImage='uploads/resized_'.$sizeName.$imageName;
                    echo '<img src="' . $resizedImage . '"class="img-thumbnail" alt="' . $imageName . '">';
                    $sizeName='mobile_device_thumbnail';
                    $resizedImage='uploads/resized_'.$sizeName.$imageName;
                    $start=1;
                    $end=3;
                    for($i=$start;$i<=$end;$i++){
                        echo '<img src="' . $resizedImage . '"class="img-thumbnail" alt="' . $imageName . '">';
                    }
                ?>
            </div>
            <div class="col-md-4
            "></div>
        </div>
    
    </div>
</body>
</html>