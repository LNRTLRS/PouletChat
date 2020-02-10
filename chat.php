<?php 
session_start();
include_once("assets/php/functions.php"); 
function getUsersList() {
    foreach(getUsers() as $user) {
        echo "<div class='user'>" . $user['username'] . "</div>";
    }
}
function getChannelsList() {
    foreach(getChannels() as $channel) {
        echo "<a href='?cid=" . $channel['id'] . "'>" . $channel['channelName'] . "</a><br />";
    }
}
if(!isset($_SESSION["currentChannel"])) {
    $_SESSION["currentChannel"] = 1; 
}
function generateMessages() {
    if(getMessages($_SESSION['currentChannel'])) {
        foreach(getMessages($_SESSION['currentChannel']) as $message) {
            echo "<hr /><div class='message'><span class='time'>" . $message['creationDate'] . "</span><br />" . getUserInformation($message['creatorID'])['username'] . " said: " . $message['messageContent'] . "</div>";
        }
    } else {
        echo "Nothing to see here... Yet.";
    }
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
if(isset($_POST["userID"]) && $_POST["Password"]) {
    if(password_verify($_POST["Password"], getUserInformation($_POST["userID"])["password"])){
        $_SESSION["userID"] = $_POST["userID"];
    } else {
        header("Location: login.php?e=pw");
        exit;
    }
} else if(!isLoggedIn()) {
    header("Location: index.php");
}
//if(!isLoggedIn()) {
//    header("Location: index.php");
//}
if(isset($_POST["Message"])) {
    createMessage($_SESSION["currentChannel"], $_SESSION["userID"], $_POST["Message"]);
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
        function refreshMessages() {
            $("#messages").load(location.href + " #messages>*", "");
        }
        $("#messageSendForm").submit(function(event) {
            event.preventDefault();
            var post_url = $(this).attr("action");
            var request_method = $(this).attr("method");
            var form_data = $(this).serialize();
            $.ajax({
                url: post_url,
                type: request_method,
                data: form_data,
                success: function() {
                    refreshMessages();
                    scrollMessages();
                }
            })
        })
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
                <div class="channelName"><?php echo getChannelInformation($_SESSION['currentChannel'])['channelName']; ?></div>
            </nav>
            <div class="chatContent">
                <div class="users">
                    <?php getUsersList(); 
                    ?>
                    <div class="online">
                    </div>
                    <div class="offline">
                    </div>
                </div>
                <div class="chat">
                    <div class="messages" id="messages">
                        <?php generateMessages(); ?>
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