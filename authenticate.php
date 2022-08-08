<?php

  session_start();

  if (isset($_POST['login_button']) && !empty($_POST['user_id']) && !empty($_POST['password'])) {

    // Connection to database
    require('connect.php');

    // Retrieve user information for authentication.
    $query = "SELECT * FROM user WHERE user_id = :user_id and active = 'Y' LIMIT 1";

    // A PDO::Statement is prepared from the query.
    $statement = $db->prepare($query);

    // Bind the :id parameter in the query to the sanitized
    // $id specifying a binding-type of Integer.
    $statement->bindValue('user_id', $_POST['user_id'], PDO::PARAM_STR);
    $statement->execute();
    
    // Fetch the row selected by primary key id.
    $row = $statement->fetch();


//    if ($statement->rowCount()>0 && $_POST['password'] == $row['password']) {
    if ($statement->rowCount()>0 && password_verify($_POST['password'], $row['password'])) {
        $_SESSION['valid'] = true;
        $_SESSION['timeout'] = time();
        $_SESSION['user_id'] = $_POST['user_id'];
        $_SESSION['user_type'] = $row['type'];
        
        //echo 'You have entered valid use name and password';
        header('Location: index.php');
    }else {
        header('Location: login.php?errorMsg=Wrong user id or password.');
        echo 'Wrong user_id or password';
    }
  }
?>
