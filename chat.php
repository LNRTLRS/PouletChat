<?php 
include_once("assets/php/functions.php"); 
session_start();
if(!isset($_SESSION["currentChannel"])) {
    $_SESSION["currentChannel"] = 1; 
}
if(isset($_GET)){
    foreach($_GET as $p => $v) {
        //interesting switch case maybe?
    }
}
if(isset($_GET["cid"])) {
    $_SESSION["currentChannel"] = $_GET["cid"];
} 
if(isset($_GET["logout"])) {
    logOut("index.php");
}
if(isset($_POST["userID"])) {
    $_SESSION["userID"] = $_POST["userID"];
}
if(!isLoggedIn()) {
    header("Location: index.php");
}
if(isset($_POST["Message"])) {
    sendMessage($_SESSION["currentChannel"], $_SESSION["User"], $_POST["Message"]);
    header("Location: " . $_SERVER['PHP_SELF']);
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>PouletChat</title>
        <link rel="stylesheet" type="text/css" href="assets/css/main.css" />
        <script src="assets/js/jquery.js"></script>
        <script>
        function scrollMessages() {
            var element = document.getElementById("messages");
            element.scrollTop = element.scrollHeight;
        }
        </script>
    </head>
    <body onload="scrollMessages()">
        <div class="content">
            <nav>
                <a href="chat.php"><div class="logo"></div></a>
                <a href="#"><div class="hamburger">
                    <div class="dropcont">
                        <?php getChannelsList(); ?>
                        <a href="?logout=true">Log out</a><br />
                    </div>
                </div></a>
                <div class="channelName"><?php echo getChannelName($_SESSION['currentChannel']); ?></div>
            </nav>
            <div class="chatContent">
                <div class="users">
                    <?php generateUsersList(getUsersList()); ?>
                    <div class="online">
                    </div>
                    <div class="offline">
                    </div>
                </div>
                <div class="chat">
                    <div class="messages" id="messages">
                        <?php getMessages($_SESSION['currentChannel']); ?>
                        <div id="newestMessage"></div>
                    </div>
                    <div class="messageInput">
                        <form autocomplete="off" action="chat.php" method="post" id="messageSendForm">
                            <div class="messageContent">
                                <input type="text" class="formVal" id="messageText" placeholder="Remember, be nice!" name="Message" autofocus></input>
                            </div>
                            <div class="send">
                                <input type="submit" id="sendButton" value="">
                            </div>
                        </form>
                    </div>
                </div>      
            </div>
        </div>
    </body>
</html>