<?php 
include_once("assets/php/functions.php");
session_start(); 
if(testBackend()) {
    header("Location: index.php");
}
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
        <title>PouletChat - API Error</title>
        <link rel="stylesheet" type="text/css" href="assets/css/main.css" />
        <meta http-equiv="refresh" content="10">
    </head>
    <body>
        <div class="content">
            <div class="landing">
                <h1>API ERROR!</h1>
                <p>The API hasn't been started yet, make sure the API is running before visiting again.</p>
                <p>The page will automatically reload and check again whether or not the API is running.</p>
                <p>Once it is running again, you will be automatically redirected to the chat app.</p>
            </div>
        </div>
    </body>
</html>