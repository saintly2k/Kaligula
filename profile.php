<?php

require("requires.php");

$page = "profile";

check("logged", "profile");

$un = clean(mysqli_real_escape_string($conn, $_GET["name"]));
$profile = $conn->query("SELECT * FROM `user` WHERE `username`='$un'")->fetch_assoc();

include("parts/header.php");

if(!empty($profile["id"])) {

?>

<title>Profile of <?= $profile["username"] ?> | <?= $config["title"] ?></title>

<?php include("parts/menu.php"); ?>

<div class="row">
    <div class="col-sm-2">
        <img src="<?= $profile["image"] ?>" style="width: 100%; max-height: 1000px">
    </div>
    <div class="col-sm-10">
        <h3><?= $profile["username"] ?></h3>
        <p>
            <?= glyph("calendar-day", "Joined at") ?> <?= $profile["joined"] ?><br>
            <?= glyph("file-arrow-up", "Uploaded x Torrents") ?> <?= count_it("torrents", $profile["id"]) ?>
        </p>
    </div>
</div>

<?php include("parts/footer.php"); ?>

<?php } else { ?>

<title>Profile not found! | <?= $config["title"] ?></title>

<?php include("parts/menu.php"); ?>

<p>The profile you requested does not exist!</p>

<?php include("parts/footer.php"); ?>

<?php } ?>