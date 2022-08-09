<?php
    // This page is using for delete user_account.
    session_start();
    
    //System user onlly, if not forward to login page.
    if (!($_SESSION['valid'] && $_SESSION['user_type'] === 'S') ){
        header('Location: login.php');  
    }

    if ($_GET && !empty($_GET['id'])) {
        require('connect.php');
        
        // Prepare the SQL for update the record to database

        $query = "DELETE FROM cards_comment WHERE card_id = :id ";
        $statement = $db->prepare($query);
        
        //  Bind values to the parameters
        $statement->bindValue(':id', $_GET['id']);

        if($statement->execute()){
            // Redirect to the index page.
            header('Location: index.php');
            /*
            $host  = $_SERVER['HTTP_HOST'];
            $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $extra = 'user_admin.php';
            header("Location: http://$host$uri/$extra");
              */  
        }else
        {
            $errorMsg = 'Not Success';
            echo $errorMsg;
        }

        $query = "DELETE FROM cards WHERE card_id = :id ";        

        $statement = $db->prepare($query);
        
        //  Bind values to the parameters
        $statement->bindValue(':id', $_GET['id']);

        if($statement->execute()){
            // Redirect to the index page.
            header('Location: index.php');
            /*
            $host  = $_SERVER['HTTP_HOST'];
            $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $extra = 'user_admin.php';
            header("Location: http://$host$uri/$extra");
              */  
        }else
        {
            $errorMsg = 'Not Success';
            echo $errorMsg;
        }

    }else{
        $errorMsg = "User ID not found.";
        
        echo $errorMsg;
        $host  = $_SERVER['HTTP_HOST'];
        $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'index.php';
        header("Location: http://$host$uri/$extra");

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