<?php
    // This page is using for process the Insert, Update and Delete statement with the database.

    if ($_POST) {
        switch ($_POST['command']) {
            case 'Create':
            case 
    }else{
        $errorMsg = "Both Title and Content must contain at least one character.";
    }



/*

    // Validate the title and content is not empty.
    if ($_POST && !empty($_POST['title']) && !empty($_POST['content'])) {
        
        //  Sanitize user input to escape HTML entities and filter out dangerous characters.
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        // Check the title and content is at least one character long.
        if (strlen(trim($title))==0 || strlen(trim($content))==0 ){
            $errorMsg = "Both Title and Content must contain at least one character.";
        }else{
            // Connection to database
            require('connect.php');

            //  Build the parameterized SQL query and bind to the above sanitized values.
            switch ($_POST['command']) {
                case 'Create':
                    
                    // Prepare the SQL for insert the record to database
                    $query = "INSERT INTO blog (title, content) VALUES (:title, :content)";
                    $statement = $db->prepare($query);

                    //  Bind values to the parameters
                    $statement->bindValue(':title', $title);
                    $statement->bindValue(':content', $content);
                    break;
                case 'Update':
                    // Prepare the SQL for update the record to database

                    $id = $_POST['id'];
                    
                    // Validate the ID must be integer.
                    if(!filter_var($id, FILTER_VALIDATE_INT)){
                        // Redirect to the index page.
                        header('Location: index.php');
                        $host  = $_SERVER['HTTP_HOST'];
                        $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                        $extra = 'index.php';
                        header("Location: http://$host$uri/$extra");
                        exit;
                    }

                    $query = "UPDATE blog SET title = :title, content = :content WHERE id = :id ";
                    $statement = $db->prepare($query);
                    
                    //  Bind values to the parameters
                    $statement->bindValue(':title', $title);
                    $statement->bindValue(':content', $content);
                    $statement->bindValue(':id', $id, PDO::PARAM_INT);
                    break;
                case 'Delete':
                    // Prepare the SQL for delete the record from database

                    $id = $_POST['id'];
                    
                    // Validate the ID must be integer.
                    if(!filter_var($id, FILTER_VALIDATE_INT)){
                        // Redirect to the index page.
                        header('Location: index.php');
                        $host  = $_SERVER['HTTP_HOST'];
                        $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                        $extra = 'index.php';
                        header("Location: http://$host$uri/$extra");
                        exit;
                    }

                    $query = "DELETE FROM blog WHERE id = :id ";
                    $statement = $db->prepare($query);
                    
                    //  Bind values to the parameters
                    $statement->bindValue(':id', $id, PDO::PARAM_INT);
                    break;
            }
            
            //  Execute the INSERT.
            //  execute() will check for possible SQL injection and remove if necessary
            if($statement->execute()){
                echo "Success";
            }

            // Redirect to the index page.
            header('Location: index.php');
            $host  = $_SERVER['HTTP_HOST'];
            $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $extra = 'index.php';
            header("Location: http://$host$uri/$extra");
            exit;

        }

    }else{
        $errorMsg = "Both Title and Content must contain at least one character.";
    }
    */
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
    <div id="wrapper">
        <div id="header">
            <h1>
                <a href="user_admin.php">Dragon Ball Z Dokkan Battle - User Admin</a>
            </h1>
        </div>
        <div id ="all_blogs">
            <H1><?= $errorMsg ?></H1>
        </div>
    </div>
</body>
</html>