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
        <h3>
            <?= $profile["username"] ?>
            <?php if($profile["banned"]==true) { ?>
            <span class="label label-danger">Banned</span>
            <small>Reason: <?= $profile["banned_reason"] ?>; Date: <?= $profile["banned_at"] ?></small>
            <?php } ?>
        </h3>
        <p>
            <?= glyph("graduation-cap", "User Level") ?> <?= convert_level("user", $profile["level"]) ?><br>
            <?= glyph("calendar-day", "Joined at") ?> <?= $profile["joined"] ?><br>
            <?= glyph("code-fork", "Invited x Users") ?> <?= count_it("invites", $profile["id"]) ?><br>
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
