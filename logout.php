<?php
   session_start();

   session_destroy();

   //echo 'You have cleaned session';
   //header('Location: index.php');
       // Redirect to the index page.
    $host  = $_SERVER['HTTP_HOST'];
    $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $extra = 'index.php';
    header("Location: http://$host$uri/$extra");
?>