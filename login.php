<?php 
include_once("assets/php/functions.php");
function generateUsersSelect() {
    foreach(getUsers() as $user) {
        echo "<option value='" . $user['id'] . "'>" . $user['username'] . " </option>";
    }
}
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
            <div class="login">
                <form action="chat.php" method="post">
                    User:
                    <select name="userID">
                        <?php generateUsersSelect(); ?>
                    </select><br />
                    Password: <input type="password" name="Password" /><br />
                    <?php
                    if(isset($_GET["e"])) {
                        echo "<span id='errMsg'>Wrong password, please try again</span><br />";
                    }
                    ?>
                    <input type="submit" value="Log in">
                </form>
                <a href="index.php">Return to previous page</a>
            </div>
        </div>
    </body>
</html>