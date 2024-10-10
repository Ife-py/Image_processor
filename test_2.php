<?php
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header('location:index.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $imageFiles = $_FILES['image'];

    if (empty($imageFiles)) {
        echo "<script>
                alert('Please select an Image to Upload');
                window.location.href='index.php';        
            </script>";
    }

    if ($imageFiles['error'] == 0) {
        $imageName = $imageFiles['name'];
        $imageType = $imageFiles['type'];
        $image_info=$imageFiles['tmp_name'];
        $image_data=file_get_contents($image_info);
        $imageResource=imagecreatefromstring($image_data);

        // if($image_info){
        //     $imageWidth=$image_info[0];
        //     $imageheight=$image_info[1];
        //     echo"Image dimensions:$imageWidth x $imageheight";
        // }else{
        //     echo "Failed to retrieve image dimensions";
        // }
        
        // new dimensions
        $imageWidth=imagesx($imageResource);
        $imageheight=imagesy($imageResource);

        $newWidth = 200;
        $newHeight = 100;

        $newImageResource=imagecreatetruecolor($newWidth,$newHeight);
        
        imagecopyresampled($newImageResource,$imageResource,0,0,0,0,$newWidth,$newHeight,$imageWidth,$imageheight);

        $imagepath='uploads/resized_'.$imageName;
        imagejpeg($newImageResource,$imagepath,90);
        
        echo'<img src="'.$imagepath.'"alt="'.$imageName.'">';
        
       exit;
    }
}
?>    