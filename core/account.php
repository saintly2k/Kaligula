<?php

$config = $conn->query("SELECT * FROM `config`")->fetch_assoc();

if(isset($_COOKIE[$config["cookie"]."_session"]) && !empty($_COOKIE[$config["cookie"]."_session"]) && !isset($_GET["logout"])) {
    $token = mysqli_real_escape_string($conn, $_COOKIE[$config["cookie"]."_session"]);
    $checking = $conn->query("SELECT * FROM `sessions` WHERE `token`='$token'");
    if(mysqli_num_rows($checking)<=1) {
        // Perform user-check of all data
        $user = mysqli_fetch_assoc($checking);
        $user = $user["user-id"];
        $user = $conn->query("SELECT * FROM `user` WHERE `id`='$user' LIMIT 1");
        $user = mysqli_fetch_assoc($user);
        $loggedin = true;
    } else {
        // Invalid session! (Hacking attempt or outdated? who knows...)
        $loggedin = false;
    }
} else {
    $loggedin = false;
}

?>