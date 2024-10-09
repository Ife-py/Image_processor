<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image_processor</title>
    <link rel="stylesheet" href="style/bootstrap.css">
</head>
<body>
    <div class="welcome-text">
        <div class="container">
            <div class="row pt-5">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-primary"><b>Welcome to this image processing page</b></div>
                        <div class="card-body">
                            <form action="image_processor.php" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="formFile" class="form-label mt-4">Upload a picture:</label>
                                    <input class="form-control" type="file" id="formFile" name="image">
                                </div>
                                <button type="submit" class="btn btn-primary">Upload Image</button>    
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-3"></div>
            </div>
        </div>
    </div>
</body>
</html>