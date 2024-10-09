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

            if(in_array($imageType,array('image/jpeg','image/png','image/gif'))){
                // moving the image to a directory
                $uploadDir='uploads/';
                $uploadfile=$uploadDir.$imageName;
                move_uploaded_file($imageFiles['tmp_name'], $uploadfile);
                
                $images='<img src="'.$uploadfile .'"width=800"height="600" alt=" '. $imageName . ' ">'; 
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
                    echo '<img src="' . $uploadfile . '"width=700"height=500" class="img-thumbnail" alt="' . $imageName . '">';
                ?>
                <?php 
                    $start=1;
                    $end=3;
                    for($i=$start;$i<=$end;$i++){
                        echo '<img src="' . $uploadfile . '"width=200"height=180" class="img-thumbnail" alt="' . $imageName . '">';
                    }
                ?>
            </div>
            <div class="col-md-6">
                <p><b>Screensize images for Tablet</b></p>
                 <?php 
                    echo '<img src="' . $uploadfile . '"width=500"height=300" class="img-thumbnail" alt="' . $imageName . '">';
                    $start=1;
                    $end=3;
                    for($i=$start;$i<=$end;$i++){
                        echo '<img src="' . $uploadfile . '"width=160"height=8
                        0" class="img-thumbnail" alt="' . $imageName . '">';
                    }
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <p><b>Screensize for mobile devices</b></p>
                <?php 
                    echo '<img src="' . $uploadfile . '"width=400"height=200" class="img-thumbnail" alt="' . $imageName . '">';
                    $start=1;
                    $end=3;
                    for($i=$start;$i<=$end;$i++){
                        echo '<img src="' . $uploadfile . '"width=100"height=" class="img-thumbnail" alt="' . $imageName . '">';
                    }
                ?>
            </div>
            <div class="col-md-4
            "></div>
        </div>
    
    </div>
</body>
</html>