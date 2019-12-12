<?php 
include_once("assets/php/functions.php");
session_start(); 
if(isLoggedIn()) {
    header("Location: chat.php");
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>PouletChat - Welcome</title>
        <link rel="stylesheet" type="text/css" href="assets/css/main.css" />
    </head>
    <body>
        <div class="content">
            <div class="landing">
                <h1>Welcome!</h1>
                <a href="login.php">I already have an account</a><br />
                <a href="register.php">I need a new account</a><br />
            </div>
        </div>
    </body>
</html>