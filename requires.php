<?php

session_start();

if(!file_exists(".installed")) {
    header("Location: install.php");
}

$page = "";
$version = file_get_contents(".installed");

require("core/db.php");

if(!defined('__DB_SERVER')) { define('__DB_SERVER', $slave["host"]); }
if(!defined('__DB_USERNAME')) { define('__DB_USERNAME', $slave["user"]); }
if(!defined('__DB_PASSWORD')) { define('__DB_PASSWORD', $slave["pass"]); }
if(!defined('__DB_DATABASE')) { define('__DB_DATABASE', $slave["table"]); }
if(!defined('__DB_TABLE')) { define('__DB_TABLE', 'peers'); }

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