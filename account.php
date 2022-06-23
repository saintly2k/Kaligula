<?php

require("requires.php");

$page = "account";

check("logged", "account");

$tab = $_GET["tab"];

include("parts/header.php");

?>

<title>Account settings | <?= $config["title"] ?></title>

<?php include("parts/menu.php"); ?>

<?php include("parts/footer.php"); ?>
