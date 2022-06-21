<?php

session_start();

if(!file_exists(".installed")) {
    header("Location: install.php");
}

$page = "";
$version = file_get_contents(".installed");

require("core/db.php");
require("core/conn.php");
require("core/funky.php");
require("core/account.php");

if(isset($_GET["logout"])) {
    // Removing token from Database and destroy entire session and so on
    $uid = $user["id"];
    $conn->query("DELETE FROM `sessions` WHERE `user-id`='$uid'");
    setcookie($config["cookie"]."_session", "", time() - 3600, "/", "");
    session_destroy();
    session_unset();
    header("Refresh: 0; url=./login.php");
}

?>