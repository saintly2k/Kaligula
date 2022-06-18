<?php

require("requires.php");

$page = "home";

check("logged", "home");

include("parts/header.php");

?>

<title><?= $config["title"]." - ".$config["slogan"] ?></title>

<?php include("parts/menu.php"); ?>

haha

<?php include("parts/footer.php"); ?>