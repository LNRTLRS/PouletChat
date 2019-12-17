<?php
//SETTINGS
$urlChoices = array("http://webservies.be/chat/api", "http://localhost:5000/chatapi");
$listeningUrl = $urlChoices[1]; //Kies op welke service je wilt testen. 1 = testing, 0 = production

function callAPI($method, $url, $data) {
    $curl = curl_init();
    switch($method) {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);
            if($data) {
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data)
                ));
            }
        break;
        default:
            if($data) {
                $url = sprintf("%s?%s", $url, http_build_query($data));
            }
        break;
    }
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    if(curl_errno($curl)) {
        echo curl_error($curl);
    }
    if(!$result) {
        die("Connection failure");
    }
    curl_close($curl);
    return $result;
}
function getUsersList() {
    $usersList = array();
    $users = json_decode(callAPI("GET", $GLOBALS['listeningUrl'] . "/Users", false), 1);
    foreach($users as $u) {
        $usersList[$u["key"]] = $u["name"];
    }
    return $usersList;
}
function getUserInfo() {
    $info = json_decode(callAPI("GET", $GLOBALS['listeningUrl'] . "/Users/" . $_SESSION["User"], false), 1);
    return $info;
}
function getUsername($id) {
    $info = json_decode(callAPI("GET", $GLOBALS['listeningUrl'] . "/Users/$id", false), 1);
    return $info["name"];
}
function generateUsersList($usersList) {
    foreach($usersList as $key => $name) {
        echo "<div class='user'>$name</div>";
    }
}
function generateUsersSelect($usersList) {
    foreach($usersList as $key => $name) {
        echo "<option value='" . $key . "'>" . $name . "</option>";
    }
}
function getChannelName($id) {
    $channelName = json_decode(callAPI("GET", $GLOBALS['listeningUrl'] . "/Channels/$id", false), 1);
    return $channelName["name"];
}
function getChannelsList() {
    $channels = json_decode(callAPI("GET", $GLOBALS['listeningUrl'] . "/Channels", false), 1);
    foreach($channels as $c) {
        echo "<a href='?cid=" . $c["key"] . "'>". $c["name"] ."</a><br />";
    }
}
function getMessages($cid) {
    $messages = json_decode(callAPI("GET", $GLOBALS['listeningUrl'] . "/Channels/$cid/messages", false), 1);
    foreach($messages as $message) {
        echo "<hr /><div class='message'><span class='time'>" . $message['creationDate'] . "</span><br /> " . getUsername($message["user"]) . " said: " . $message['text'] . "</div>";
    }
}
function isLoggedIn() {
    if(isset($_SESSION["User"])) {
        return true;
    } else {
        return false;
    }
}
function logOut($redirLocation) {
    session_unset();
    session_destroy();
    header('Location: ' . $redirLocation);
}
function register($userName, $redirLocation) {
    $dataArray = array(
        "key" => (max(array_keys(getUsersList())) + 1),
        "name" => $userName
    );
    if(!array_key_exists($userName, getUsersList())) {
        $response = json_decode(callAPI("POST", $GLOBALS['listeningUrl'] . "/Users", json_encode($dataArray)), 1);
        header('Location: ' . $redirLocation);
    } else {
        echo "Username already taken";
    }
}
function sendMessage($cid, $user, $message) {
    $now = date("Y-m-d H:i:s");
    $dataArray = array(
        "user" => $user,
        "creationDate" => $now,
        "text" => $message
    );
    $response = json_decode(callAPI("POST", $GLOBALS['listeningUrl'] . "/Channels/$cid/messages", json_encode($dataArray)), 1);
}
?>