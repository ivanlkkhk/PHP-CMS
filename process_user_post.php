<?php
    // This page is using for process the Insert, Update and Delete statement with the database.

    if ($_POST && !empty($_POST['user_id']) && !empty($_POST['name'] && !empty($_POST['password']))) {
        $user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (strlen(trim($user_id))==0 || strlen(trim($name))==0 || strlen(trim($password))==0){
            $errorMsg = "User ID, Name or Password cannot be blank.";
        }else{
            // Connection to database
            require('connect.php');

            $type = $_POST['type'];
            if (!isset($_POST['active']) && empty($_POST['active']))
            {
                $active = 'N';
            }else{
                $active = 'Y';
            }


            switch ($_POST['command']) {
                case 'create_user':
                    // Prepare the SQL for insert the record to database
                    $query = "INSERT INTO user (user_id, name, type, password, active) VALUES (:user_id, :name, :type, :password, :active)";
                    $statement = $db->prepare($query);

                    $password = password_hash($password, PASSWORD_DEFAULT);

                    //  Bind values to the parameters
                    $statement->bindValue(':user_id', $user_id);
                    $statement->bindValue(':name', $name);
                    $statement->bindValue(':type', $type);
                    $statement->bindValue(':password', $password);
                    $statement->bindValue(':active', $active);
                    break;
                    
                case 'update_user':
                    // Prepare the SQL for update the record to database
                    
                    $query = "UPDATE user SET name = :name, type = :type, password = :password, modify_date = now(), active =:active WHERE user_id = :id ";
                    $statement = $db->prepare($query);
                    
                    $password = password_hash($password, PASSWORD_DEFAULT);

                    //  Bind values to the parameters
                    $statement->bindValue(':name', $name);
                    $statement->bindValue(':type', $type);
                    $statement->bindValue(':password', $password);
                    //$statement->bindValue(':modify_date', $modify_date);
                    $statement->bindValue(':active', $active);
                    $statement->bindValue(':id', $user_id);
                    break;
                
            }

            if($statement->execute()){
                // Redirect to the index page.
                //header('Location: user_admin.php');
                $host  = $_SERVER['HTTP_HOST'];
                $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                $extra = 'user_admin.php';
                header("Location: http://$host$uri/$extra");
                    
            }else
            {
                $errorMsg = 'Not Success';
            }
        }
    }else{
        $errorMsg = "User ID, Name or Password cannot be blank.";
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