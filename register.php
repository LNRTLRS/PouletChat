<?php 
include_once("assets/php/functions.php");
session_start(); 
if(isLoggedIn()) {
    header("Location: chat.php");
}
if(isset($_POST["Username"])) {
    register($_POST["Username"], "chat.php");
    $_SESSION["User"] = $_POST["Username"];
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>PouletChat - Registeren</title>
        <link rel="stylesheet" type="text/css" href="assets/css/main.css" />
    </head>
    <body>
        <div class="content">
            <div class="register">
                <form action="register.php" method="post">
                    Username: <input type="text" name="Username" />
                    <input type="submit" value="Register">
                </form>
                <a href="index.php">Return to previous page</a>
            </div>
        </div>
    </body>
</html>