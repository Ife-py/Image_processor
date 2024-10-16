<?php
try{
    $db=new PDO("mysql:host=localhost;dbname=image_processor","root","");
    $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    // echo "Connected";
}catch(Exception $e){
    echo 'ERROR: '.$e->getMessage();
    exit;
}

?>
<?php 
if($_SERVER["REQUEST_METHOD"]!="POST"){
    header('location:index.php');
    exit;
}
if($_SERVER['REQUEST_METHOD']=='POST'){
    if(isset($_FILES['image'])&&$_FILES['image']['error']==0){
        $image=$_FILES['image'];
        $image_name=$image['name'];
        $image_tmp=$image['tmp_name'];
        $image_size=$image['size'];
        $image_type=mime_content_type($image_tmp);

        // check to see if the image is of valid type
        if(in_array($image_type,['image/jpeg','image/png','image/gif'])){
            $original_image_data=file_get_contents($image_tmp);

            // inserting original image into database
            $stmt = $db->prepare("INSERT INTO images (original_image, resized_image_path) VALUES (:original_image, '')");
            $stmt->bindParam(':original_image', $original_image_data, PDO::PARAM_LOB);
            $stmt->execute();
            $imageId = $db->lastInsertId();
            
            // retrieving the resized image from the database
            $stmt=$db->prepare("SELECT * FROM sizes");
            $stmt->execute();
            $sizes=$stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Resize the image
            list($width, $height) = getimagesize($image_tmp);
            // $newWidth = 300; // New width
            // $newHeight = 200; // New height

            // Load original image based on its type
            switch ($image_type) {
                case 'image/jpeg':
                    $srcImage = imagecreatefromjpeg($image_tmp);
                    break;
                case 'image/png':
                    $srcImage = imagecreatefrompng($image_tmp);
                    break;
                case 'image/gif':
                    $srcImage = imagecreatefromgif($image_tmp);
                    break;
            }
            
            foreach($sizes as $size){
                $newWidth = $size['width'];
                $newHeight=$size['height'];
                
                // creating a new true color image for resizing
                $newImage=imagecreatetruecolor($newWidth,$newHeight);


                    
                // Copy and resize original image into the new image
                imagecopyresampled($newImage, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

                // Save the resized image
                $uploadDir = 'uploads/';
                $resizedImagePath = $uploadDir .$size['device_type']. 'resized_' . $size['image_type'];
                imagejpeg($newImage, $resizedImagePath, 90); // Save as JPEG with quality 90

                // Update the database with the resized image path
                $stmt = $db->prepare("INSERT INTO resized_images(image_id,size_name,resized_image_path)values(:imageId,:size_name,:resized_image_path)");
                $stmt->bindParam(':imageId', $imageId);
                $stmt->bindParam(':size_name', $size['image_type']);
                $stmt->bindParam(':resized_image_path', $resizedImagePath);
                $stmt->execute();

                // Free memory
                imagedestroy($newImage);
                
            }
            imagedestroy($srcImage);

            // echo "Image uploaded and resized successfully!";
        } else {
            echo "Invalid image type. Only JPG, PNG, and GIF are allowed.";
        }
    } else {
        echo "Error in uploading image.";
    }
    
    $stmt = $db->prepare("SELECT * FROM resized_images WHERE image_id=:imageId");
    $stmt->bindParam('imageId',$imageId);

    $stmt->execute();
    $resized_images=$stmt->fetchAll(PDO::FETCH_ASSOC);

    
    // Display the resized image
    // if ($resized_images) {
    //     foreach($resized_images as $image){
    //         echo '<img src="' . $image['resized_image_path'] . '" alt="Resized Image" class="img-thumbnail">';
    //     }
    // } else {
    //     echo "No image found!";
    // }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/bootstrap.css">
</head>
<body>
    <?php
    if ($resized_images) {
        foreach($resized_images as $image){
            echo '<img src="' . $image['resized_image_path'] . '" alt="Resized Image" class="img-thumbnail">';
        }
    } else {
        echo "No image found!";
    }
    ?>
</body>
</html>