<?php

//            _   _   _                 
//           | | | | (_)                
//  ___  ___| |_| |_ _ _ __   __ _ ___ 
// / __|/ _ \ __| __| | '_ \ / _` / __|
// \__ \  __/ |_| |_| | | | | (_| \__ \
// |___/\___|\__|\__|_|_| |_|\__, |___/
//                            __/ |    
//                           |___/     

$urlChoices = array("http://webservies.be/chat/api", "http://localhost:5000/chatapi", "http://localhost:5000/api");
$listeningUrl = $urlChoices[2]; //Kies op welke service je wilt testen. 2 = Eigen API, 1 = testing, 0 = production

//                _ 
//               (_)
//    __ _ _ __  _ 
//   / _` | '_ \| |
//  | (_| | |_) | |
//  \__,_| .__/|_|
//       | |      
//      |_|      

/**
 * This function calls the API with a certain method, url and optional data
 * 
 * @param string $method The method used to call the API (GET or POST)
 * @param string $url The url you want to call the API on, all possible options: /Channels | /Channels/{id} | /Channels/{id}/messages | /Users | /Users{id}
 * @param mixed $data If doing a POST call, this should be your dataArray, if using GET just use false;
 * 
 * @return string Result of query in JSON format
 */
function callAPI($method, $url, $data) {
    global $listeningUrl;
    $curl = curl_init();
    switch($method) {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);
            if($data) {
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json'
                ));
            }
        break;
        default:
            if($data) {
                $url = sprintf("%s?%s", $listeningUrl . $url, http_build_query($data));
            }
        break;
    }
    curl_setopt($curl, CURLOPT_URL, $listeningUrl . $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    if(curl_errno($curl)) {
        echo curl_error($curl);
    }
    if(!$result) {
        //echo "No result from API call<br />";
    }
    curl_close($curl);
    return $result;
}

function testBackend($fromError) {
    global $listeningUrl;
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $listeningUrl . "/Users");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 3);
    $result = curl_exec($curl);
    $uri = $_SERVER['REQUEST_URI'];
    if(curl_getinfo($curl, CURLINFO_HTTP_CODE) == 0) {
        if(!($uri == "/PouletChat/startAPI.php")) {
            header("Location: startAPI.php");
            die;
        }
    } else {
        return true;
    }
}

//                _   
//               | |  
//      __ _  ___| |_ 
//    / _` |/ _ \ __|
//   | (_| |  __/ |_ 
//   \__, |\___|\__|
//   __/ |         
//  |___/          

/**
 * This function returns the channels as Key => Value pair, key being channelID and value being channelName
 * 
 * @return array Array of channels as Key => Value. Key being channelID and value being channelName
 */
function getChannels() {
    $channels = json_decode(callAPI("GET", "/Channels", false), 1);
    return $channels;
}
/**
 * This function returns given channel as Key => Value pair, key being channelID and value being channelName
 * 
 * @param int $channelID This is the ID of the channel you want information about
 * 
 * @return array Information about the channel in a Key => Value array
 */
function getChannelInformation($channelID) {
    $channelInformation = json_decode(callAPI("GET", "/Channels/$channelID", false), 1);
    return $channelInformation;
}
/**
 * This function returns messages in given channel as an array which contains arrays of user information.
 * array[0] = userID
 * array[1] = creationDate
 * array[2] = messageText
 * 
 * @param int $channelID This is the ID of the channel you want the messages from
 * 
 * @return array An array containing arrays of message information
 */
function getMessages($channelID) {
    $messages = json_decode(callAPI("GET", "/Messages/Channel/$channelID", false), 1);
    return $messages;
}
function getAllMessages() {
    $messages = json_decode(callAPI("GET", "/Messages", false), 1);
    return $messages;
  
}
/**
 * This function returns users as an array containing Key => Value pairs.
 * So, the returned value is an array, wherein each item is an array containing the Key => Value where key = userID and value = userName
 * 
 * @return array Array containing arrays of user information
 */
function getUsers() {
    $users = json_decode(callAPI("GET", "/Users", false), 1);
    return $users;
}
/**
 * This function returns information of given userID as a Key => Value array.
 * array[0] = userID
 * array[1] = userName
 * 
 * @param int $userID This is the ID of the user you want information about
 * 
 * @return array An array with one key => value pair. 
 */
function getUserInformation($userID) {
    $userInfo = json_decode(callAPI("GET", "/Users/$userID", false), 1);
    return $userInfo;
}

//                   _   
//                  | |  
//     __   ___  ___| |_ 
//  | '_ \ / _ \/ __| __|
//  | |_) | (_) \__ \ |_ 
//  | .__/ \___/|___/\__|
//  | |                  
//  |_|                  
//TODO: ADD DOCUMENTATION FOR POST FUNCTIONS
function makeChannel($channelName) {
    if(!in_array($channelName, getChannels(), false)) {
        $dataArray = array(
            "key" => (max(array_keys(getChannels())) + 1),
            "name" => $channelName
        );
        callAPI("POST", "/Channels", json_encode($dataArray));
    } else {
        echo "A channel already exists with this name, please choose a different one";
    }
}
function createMessage($channelID, $userID, $messageCont) {
        $now = date("Y-m-d H:i:s");
        $highestMID = 0;
        foreach(getAllMessages() as $message) {
            if($message["id"] >= $highestMID) {
                $highestMID = $message["id"];
            }
        }
        $dataArray = array(
            "ID" => ($highestMID + 1),
            "CreatorID" => $userID,
            "ChannelID" => $channelID,
            "MessageContent" => $messageCont,
            "CreationDate" => $now
        );
        callAPI("POST", "/Messages", json_encode($dataArray));
}
if($listeningUrl == $urlChoices[2]) {
    function createUser($userName, $passWord, $locationAfterRegister) {
            $highestUID = 0;
            foreach(getUsers() as $user) {
                if($user["id"] >= $highestUID) {
                    $highestUID = $user["id"];
                }
            }
            $dataArray = array(
                "ID" => ($highestUID + 1),
                "Username" => $userName,
                "Password" => $passWord
            );
            $_SESSION["userID"] = ($highestUID + 1);
            callAPI("POST", "/Users", json_encode($dataArray));
            header("Location: $locationAfterRegister");
    }
} else {
    function createUser($userName, $locationAfterRegister) {
        if(!in_array($userName, getUsers(), false)) {
            $dataArray = array(
                "key" => (max(array_keys(getUsers())) + 2),
                "name" => $userName
            );
            $_SESSION["userID"] = max(array_keys(getUsers())) + 2;
            callAPI("POST", "/Users", json_encode($dataArray));
            header("Location: $locationAfterRegister");
        } else {
            echo "Username already taken";
        }
    }
}

// OTHER
//TODO: ADD DOCUMENTATION FOR OTHER FUNCTIONS
function logIn($userID) {
    if(array_key_exists($userID, getUsers())) {
        $_SESSION["userID"] = $_POST["userID"];
    } else {
        echo "Invalid userID parameter on logIn function";
    }
}
function isLoggedIn() {
    if(isset($_SESSION["userID"])) {
        return true;
    } else {
        return false;
    }
}
function logOut($locationAfterLogout) {
    session_unset();
    session_destroy();
    header("Location: $locationAfterLogout");
}


?>