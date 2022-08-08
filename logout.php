<?php
   session_start();

   session_destroy();

   echo 'You have cleaned session';
   header('Location: index.php');
?>