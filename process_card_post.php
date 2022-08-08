<?php
    // This page is using for process the Insert and Update statement with the database.

    require('ImageResize.php');
    require('ImageResizeException.php');
    use \Gumlet\ImageResize;


    // file_upload_path() - Safely build a path String that uses slashes appropriate for our OS.
    // Default upload path is an 'uploads' sub-folder in the current folder.
    function file_upload_path($original_filename, $upload_subfolder_name = 'uploads') {
       $current_folder = dirname(__FILE__);
       
       // Build an array of paths segment names to be joins using OS specific slashes.
       $path_segments = [$current_folder, $upload_subfolder_name, basename($original_filename)];
       
       // The DIRECTORY_SEPARATOR constant is OS specific.
       return join(DIRECTORY_SEPARATOR, $path_segments);
    }

    // file_is_an_image() - Checks the mime-type & extension of the uploaded file for "image-ness".
    function file_is_an_image($temporary_path, $new_path) {
        $allowed_mime_types      = ['image/gif', 'image/jpeg', 'image/png'];
        $allowed_file_extensions = ['gif', 'jpg', 'jpeg', 'png'];
        
        $actual_file_extension   = pathinfo($new_path, PATHINFO_EXTENSION);
        
        $file_extension_is_valid = in_array($actual_file_extension, $allowed_file_extensions);

        // Checks only the file extension in the defined mime type list. 
        if ($file_extension_is_valid) {
            $actual_mime_type        = getimagesize($temporary_path)['mime'];
            $mime_type_is_valid      = in_array($actual_mime_type, $allowed_mime_types);
        }
        
        return $file_extension_is_valid && $mime_type_is_valid;
    }


    if ($_POST && !empty($_POST['name']) && !empty($_POST['description'] )) {
        
        $level_id = filter_input(INPUT_POST, 'level_id', FILTER_SANITIZE_NUMBER_INT);
        $category_id = filter_input(INPUT_POST, 'category_id', FILTER_SANITIZE_NUMBER_INT);
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $hp = filter_input(INPUT_POST, 'hp', FILTER_SANITIZE_NUMBER_INT);
        $atk = filter_input(INPUT_POST, 'atk', FILTER_SANITIZE_NUMBER_INT);
        $def = filter_input(INPUT_POST, 'def', FILTER_SANITIZE_NUMBER_INT);
        $icon_path = filter_input(INPUT_POST, 'icon_path', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $image_path = filter_input(INPUT_POST, 'image_path', FILTER_SANITIZE_FULL_SPECIAL_CHARS);


        if (strlen(trim($name))==0 || strlen(trim($description))==0){
            $errorMsg = "Name or Description cannot be blank.";
        }else{
            // Connection to database
            require('connect.php');

            switch ($_POST['command']) {
                case 'create_card':
                    // Prepare the SQL for insert the record to database
                    $query = "INSERT INTO cards (level_id, category_id, name, description, hp, atk, def, icon_path, image_path) VALUES (:level_id, :category_id, :name, :description, :hp, :atk, :def, :icon_path, :image_path)";

                    $statement = $db->prepare($query);

                    //  Bind values to the parameters
                    $statement->bindValue(':level_id', $level_id);
                    $statement->bindValue(':category_id', $category_id);
                    $statement->bindValue(':name', $name);
                    $statement->bindValue(':description', $description);
                    $statement->bindValue(':hp', $hp);
                    $statement->bindValue(':atk', $atk);
                    $statement->bindValue(':def', $def);


                    $image_upload_detected = isset($_FILES['icon_filepath']) && ($_FILES['icon_filepath']['error'] === 0);
                    $upload_error_detected = isset($_FILES['icon_filepath']) && ($_FILES['icon_filepath']['error'] > 0);

                    if ($image_upload_detected) { 
                        // Prepare filenames and path.
                        $image_filename        = $_FILES['icon_filepath']['name'];
                        $thumbnail_image_filename = substr($_FILES['icon_filepath']['name'], 0, -4) . '_thumbnail' . substr($_FILES['icon_filepath']['name'], -4);
                        $temporary_image_path  = $_FILES['icon_filepath']['tmp_name'];
                        $new_image_path        = file_upload_path($image_filename, 'icon_upload');

                        // Move only images (png, jpg, gif) file to new folder.
                        if (file_is_an_image($temporary_image_path, $new_image_path)) {
                            $upload_success = true;
                            $image = new ImageResize($temporary_image_path);

                            // Convert file to thumbnail size (50px).
                            $image->resizeToHeight(50);
                            $image->save(file_upload_path($thumbnail_image_filename, 'icon_upload'));
                            
                            // Move original image to new path.
                            move_uploaded_file($temporary_image_path, $new_image_path);
                        }else{
                            $upload_success = false;
                        }
                        
                    }

                    $image_upload_detected = isset($_FILES['image_filepath']) && ($_FILES['image_filepath']['error'] === 0);
                    $upload_error_detected = isset($_FILES['image_filepath']) && ($_FILES['image_filepath']['error'] > 0);

                    if ($image_upload_detected) { 
                        // Prepare filenames and path.
                        $image_filename        = $_FILES['image_filepath']['name'];
                        $thumbnail_image_filename = substr($_FILES['image_filepath']['name'], 0, -4) . '_thumbnail' . substr($_FILES['image_filepath']['name'], -4);
                        $temporary_image_path  = $_FILES['image_filepath']['tmp_name'];
                        $new_image_path        = file_upload_path($image_filename, 'image_upload');

                        // Move only images (png, jpg, gif) file to new folder.
                        if (file_is_an_image($temporary_image_path, $new_image_path)) {
                            $upload_success = true;
                            $image = new ImageResize($temporary_image_path);

                            // Convert file to thumbnail size (50px).
                            $image->resizeToHeight(50);
                            $image->save(file_upload_path($thumbnail_image_filename, 'image_upload'));
                            
                            // Move original image to new path.
                            move_uploaded_file($temporary_image_path, $new_image_path);
                        }else{
                            $upload_success = false;
                        }
                        
                    }



                    $statement->bindValue(':icon_path', $icon_path);
                    $statement->bindValue(':image_path', $image_path);
                    break;
                    
                case 'update_card':
                    $card_id = filter_input(INPUT_POST, 'card_id', FILTER_SANITIZE_NUMBER_INT);
                    // Prepare the SQL for update the record to database
                    
                    $query = "UPDATE cards SET level_id = :level_id, category_id = :category_id, name = :name, description = :description, hp = :hp, atk = :atk, def = :def, icon_path = :icon_path, image_path = :image_path WHERE card_id = :id ";
                    
                    $statement = $db->prepare($query);
                    
                    //  Bind values to the parameters
                    $statement->bindValue(':level_id', $level_id);
                    $statement->bindValue(':category_id', $category_id);
                    $statement->bindValue(':name', $name);
                    $statement->bindValue(':description', $description);
                    $statement->bindValue(':hp', $hp);
                    $statement->bindValue(':atk', $atk);
                    $statement->bindValue(':def', $def);

                    if (isset($_POST['del_icon']) && !empty($_POST['del_icon'])) {
                        $icon_path = '';
                    }else{
                        $image_upload_detected = isset($_FILES['icon_filepath']) && ($_FILES['icon_filepath']['error'] === 0);
                        $upload_error_detected = isset($_FILES['icon_filepath']) && ($_FILES['icon_filepath']['error'] > 0);

                        if ($image_upload_detected) { 
                            // Prepare filenames and path.
                            $image_filename        = $_FILES['icon_filepath']['name'];
                            $thumbnail_image_filename = substr($_FILES['icon_filepath']['name'], 0, -4) . '_thumbnail' . substr($_FILES['icon_filepath']['name'], -4);
                            $temporary_image_path  = $_FILES['icon_filepath']['tmp_name'];
                            $new_image_path        = file_upload_path($image_filename, 'icon_upload');

                            // Move only images (png, jpg, gif) file to new folder.
                            if (file_is_an_image($temporary_image_path, $new_image_path)) {
                                $upload_success = true;
                                $image = new ImageResize($temporary_image_path);

                                // Convert file to thumbnail size (50px).
                                $image->resizeToHeight(50);
                                $image->save(file_upload_path($thumbnail_image_filename, 'icon_upload'));
                                
                                // Move original image to new path.
                                move_uploaded_file($temporary_image_path, $new_image_path);
                            }else{
                                $upload_success = false;
                            }
                            
                        }

                    }

                    $statement->bindValue(':icon_path', $icon_path);

                    if (isset($_POST['del_image']) && !empty($_POST['del_image'])) {
                        $image_path = '';
                    }else{
                        $image_upload_detected = isset($_FILES['image_filepath']) && ($_FILES['image_filepath']['error'] === 0);
                        $upload_error_detected = isset($_FILES['image_filepath']) && ($_FILES['image_filepath']['error'] > 0);

                        if ($image_upload_detected) { 
                            // Prepare filenames and path.
                            $image_filename        = $_FILES['image_filepath']['name'];
                            $thumbnail_image_filename = substr($_FILES['image_filepath']['name'], 0, -4) . '_thumbnail' . substr($_FILES['image_filepath']['name'], -4);
                            $temporary_image_path  = $_FILES['image_filepath']['tmp_name'];
                            $new_image_path        = file_upload_path($image_filename, 'image_upload');

                            // Move only images (png, jpg, gif) file to new folder.
                            if (file_is_an_image($temporary_image_path, $new_image_path)) {
                                $upload_success = true;
                                $image = new ImageResize($temporary_image_path);

                                // Convert file to thumbnail size (50px).
                                $image->resizeToHeight(50);
                                $image->save(file_upload_path($thumbnail_image_filename, 'image_upload'));
                                
                                // Move original image to new path.
                                move_uploaded_file($temporary_image_path, $new_image_path);
                            }else{
                                $upload_success = false;
                            }
                            
                        }
                    }
                    $statement->bindValue(':image_path', $image_path);
                    $statement->bindValue(':id', $card_id);
                    break;
                
            }

            if($statement->execute()){
                // Redirect to the index page.
                $host  = $_SERVER['HTTP_HOST'];
                $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                $extra = 'index.php';
                header("Location: http://$host$uri/$extra");
            }else
            {
                $errorMsg = 'Not Success';
            }
        }
    }else{
        $errorMsg = "Name or Description cannot be blank.";
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Error Message</title>
    <link rel="stylesheet" type="text/css" href="styles.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <header>
        <div id="heading">
                <div id="mainMenu">
                    <h2>Dragon Ball Z Dokkan Battle - User Admin</h2>
                </div>
            <div id="image">
                <img class="headerImage" src="images/banner.jpg" alt="Dragon Ball Z Dokkan Battle">
            </div>
        </div>  
    </header>

    <section>
        <div id="content">
            <H1><?= $errorMsg ?></H1>
        </div>
<?php 
    //print_r($_POST);
    //echo $active;
    //print_r($statement->errorInfo());
?>
    </section>
</body>
</html>