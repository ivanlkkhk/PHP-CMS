<?php
    // This page is using for process the comment update with the database.
    session_start();
    require 'CaptchasDotNet.php';

    $captchas = new CaptchasDotNet ('demo', 'secret',
                                '/CAPTCHA','3600',
                                'abcdefghkmnopqrstuvwxyz','6',
                                '240','80','000088');

    $password      = $_REQUEST['password'];
    $random_string = $_REQUEST['random'];

    if (!$captchas->validate ($random_string)){
        echo 'The session key (random) does not exist, please go back and reload form.<br/>';
        echo 'In case you are the administrator of this page, ';
        echo 'please check if random keys are stored correct.<br/>';
        echo 'See http://captchas.net/sample/php/ "Problems with save mode"';
    }elseif (!$captchas->verify ($password))
    {
        $errorMsg = 'You entered the wrong password. Aren\'t you human? Please use back button and reload.';
    }
    else
    {
        if ($_POST && !empty($_POST['comment'])) {


        $user_id = $_SESSION['user_id'];
        $card_id = filter_input(INPUT_POST, 'card_id', FILTER_SANITIZE_NUMBER_INT);
        $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (strlen(trim($comment))==0 ){
            $errorMsg = "Comment cannot be blank.";
        }else{
            // Connection to database
            require('connect.php');

            if ($_POST['command'] === 'update_comment') {
                
                    // Prepare the SQL for insert the record to database
                    $query = "INSERT INTO card_comment (card_id, comment, user_id) VALUES (:card_id, :comment, :user_id)";

                    $statement = $db->prepare($query);

                    //  Bind values to the parameters
                    $statement->bindValue(':card_id', $card_id);
                    $statement->bindValue(':comment', $comment);
                    $statement->bindValue(':user_id', $user_id);
            }

            if($statement->execute()){
                // Redirect to the index page.
                $host  = $_SERVER['HTTP_HOST'];
                $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                $extra = 'index.php';
                header("Location: http://$host$uri/$extra");
            }else{
                $errorMsg = 'Not Success';
            }
        }
        
        }else{
            $errorMsg = "Comment cannot be blank.";
        }
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

