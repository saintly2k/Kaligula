<?php

require("requires.php");

$page = "admin";

check("logged", "admin");
check("admin", $user["level"]);

$error = false;
$error_msg = "";

if(empty($_GET["tab"])) header("Location: ?tab=home");
$tab = clean(mysqli_real_escape_string($conn, $_GET["tab"]));

if($tab=="site-config") {
    if(isset($_POST["edit_site_config"])) {
        $stitle = clean(mysqli_real_escape_string($conn, $_POST["title"]));
        $sslogan = clean(mysqli_real_escape_string($conn, $_POST["slogan"]));
        $sregistration = clean(mysqli_real_escape_string($conn, $_POST["registration"]));
        $sfontawesome = clean(mysqli_real_escape_string($conn, $_POST["fontawesome"]));
        $scookie = clean(mysqli_real_escape_string($conn, $_POST["cookie"]));
        $surl = clean(mysqli_real_escape_string($conn, $_POST["url"]));
        $sannounce = clean(mysqli_real_escape_string($conn, $_POST["announce"]));

        $sql = "UPDATE `config` SET `title`='$stitle', `slogan`='$sslogan', `registration`='$sregistration', `fontawesome`='$sfontawesome', `cookie`='$scookie', `url`='$surl', `announce`='$sannounce'";

        if(mysqli_query($conn, $sql)) {} else { echo "Error updating config: " . mysqli_error($conn); }
        header("Location: ?tab=site-config");
    }
}

if($tab=="categories") {
    $categories = $conn->query("SELECT * FROM `categories` ORDER BY `id` ASC");
    
    if(isset($_POST["add_cat"])) {
        $cimage = clean(mysqli_real_escape_string($conn, $_POST["image"]));
        $cname = clean(mysqli_real_escape_string($conn, $_POST["name"]));
        
        $check = $conn->query("SELECT * FROM `categories` WHERE `name`='$cname' LIMIT 1");
        
        if(mysqli_num_rows($check)!=1) {
            $sql = "INSERT INTO `categories`(`name`,`image`) VALUES('$cname', '$cimage')";
            if(mysqli_query($conn, $sql)) {} else { $error = true; $error_msg = "Error adding category: " . mysqli_error($conn); }
        } else {
            $error = true;
            $error_msg = "A category with this name already exists.";
        }
        
        header("Location: ?tab=categories");
    }
    
    if(isset($_POST["edit_cat"])) {
        $cid = clean(mysqli_real_escape_string($conn, $_POST["id"]));
        $cimage = clean(mysqli_real_escape_string($conn, $_POST["image"]));
        $cname = clean(mysqli_real_escape_string($conn, $_POST["name"]));
        $cname2 = clean(mysqli_real_escape_string($conn, $_POST["name2"]));
        
        if($cname==$cname2) {
            $check = $conn->query("SELECT * FROM `categories` WHERE `id`='99999' LIMIT 1");
        } else {
            $check = $conn->query("SELECT * FROM `categories` WHERE `name`='$cname' LIMIT 1");
        }
        
        if(mysqli_num_rows($check)!=1) {
            $sql = "UPDATE `categories` SET `name`='$cname', `image`='$cimage' WHERE `id`='$cid'";
            if(mysqli_query($conn, $sql)) {} else { $error = true; $error_msg = "Error editing category: " . mysqli_error($conn); }
        } else {
            $error = true;
            $error_msg = "A category with this name already exists.";
        }
        
        header("Location: ?tab=categories&error=$error&msg=$error_msg");
    }
    
    if(isset($_POST["del_cat"])) {
        $cid = clean(mysqli_real_escape_string($conn, $_POST["id"]));
        
        $sql = "DELETE FROM `categories` WHERE `id`='$cid'";
        if(mysqli_query($conn, $sql)) {} else { $error = true; $error_msg = "Error deleting category: " . mysqli_error($conn); }
        
        header("Location: ?tab=categories&error=$error&msg=$error_msg");
    }
}

if($tab=="stats") {
    $totalusers = $conn->query("SELECT COUNT(*) AS total FROM `user`")->fetch_assoc(); $totalusers = $totalusers["total"];
    $totaltorrents = $conn->query("SELECT COUNT(*) AS total FROM `torrents`")->fetch_assoc(); $totaltorrents = $totaltorrents["total"];
    $totalpeers = $conn->query("SELECT COUNT(*) AS total FROM `peers`")->fetch_assoc(); $totalpeers = $totalpeers["total"];
}

if($tab=="upgrade") {
    $lversion = file_get_contents("https://cdn.henai.eu/Kaligula/version.txt");
    
    if(isset($_GET["action"]) && $_GET["action"]=="do") {
        // Perform update
        
        header("Location: ?tab=update");
    }
}

if(isset($_GET["error"]) && $_GET["error"]==true) {
    $error = true;
    $error_msg = clean(mysqli_real_escape_string($conn, $_GET["msg"]));
}

include("parts/header.php");

?>

<title><?= $config["title"]." - ".$config["slogan"] ?></title>

<script src="assets/admin.js"></script>

<?php include("parts/menu.php"); ?>

<div class="row">

    <div class="col-sm-2">

        <ul class="nav nav-pills nav-stacked">
            <li role="presentation" <?php if($tab=="home") echo "class='active'"; ?>><a href="?tab=home">Home</a></li>
            <li role="presentation" <?php if($tab=="site-config") echo "class='active'"; ?>><a href="?tab=site-config">Site config</a></li>
            <li role="presentation" <?php if($tab=="categories") echo "class='active'"; ?>><a href="?tab=categories">Categories</a></li>
            <li role="presentation" <?php if($tab=="stats") echo "class='active'"; ?>><a href="?tab=stats">Stats</a></li>
            <li role="presentation" <?php if($tab=="upgrade") echo "class='active'"; ?>><a href="?tab=upgrade">Upgrade</a></li>
        </ul>

    </div>

    <div class="col-sm-10">

        <?php if($tab=="home") { ?>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Home</h3>
            </div>
            <div class="panel-body">
                <p>Welcome to the Admin panel of <span class="badge">Kaligula <?= $version ?></span>. Here you can do:</p>
                <ul>
                    <li>Edit the Site configuration</li>
                    <li>Edit current Categories</li>
                    <li>View total stats of the Tracker</li>
                    <li>Upgrade your system</li>
                </ul>
                <p><a href="https://github.com/kaligula-eu/Kaligula" target="_blank">If you are missing any features, make sure to create an issue on the GitHub.</a></p>
            </div>
        </div>

        <?php } elseif($tab=="site-config") { ?>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Site config</h3>
            </div>
            <div class="panel-body">
                <form method="post" name="edit_site_config" class="form-horizontal">
                    <div class="form-group">
                        <label for="title" class="col-sm-2 control-label">Title</label>
                        <div class="col-sm-10">
                            <input id="title" type="text" name="title" value="<?= $config["title"] ?>" required class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="slogan" class="col-sm-2 control-label">Slogan</label>
                        <div class="col-sm-10">
                            <input id="slogan" type="text" name="slogan" value="<?= $config["slogan"] ?>" required class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="registration" class="col-sm-2 control-label">Registration</label>
                        <div class="col-sm-10">
                            <select id="registration" required class="form-control selectpicker" name="registration">
                                <option value="1" <?php if($config["registration"]==true) echo "selected"; ?>>Open</option>
                                <option value="0" <?php if($config["registration"]==false) echo "selected"; ?>>Closed</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="fontawesome" class="col-sm-2 control-label">FontAwesome JS</label>
                        <div class="col-sm-10">
                            <input id="fontawesome" type="text" name="fontawesome" value="<?= $config["fontawesome"] ?>" required class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cookie" class="col-sm-2 control-label">Cookie</label>
                        <div class="col-sm-10">
                            <input id="cookie" type="text" name="cookie" value="<?= $config["cookie"] ?>" required class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="url" class="col-sm-2 control-label">URL</label>
                        <div class="col-sm-10">
                            <input id="url" type="text" name="url" value="<?= $config["url"] ?>" required class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="announce" class="col-sm-2 control-label">Announce</label>
                        <div class="col-sm-10">
                            <input id="announce" type="text" name="announce" value="<?= $config["announce"] ?>" required class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <button class="form-control btn btn-success" type="submit" name="edit_site_config"><?= glyph("rotate", "Update Config", "spin") ?> Update Config</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php } elseif($tab=="categories") { ?>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Categories</h3>
            </div>
            <div class="panel-body">
                <?php if($error==true) { ?>
                <div class="alert alert-warning alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong><?= glyph("triangle-exclamation", "Error", "fade") ?></strong> <?= $error_msg ?>
                </div>
                <?php } ?>
                <div class="table-responsive">
                    <?php if(mysqli_num_rows($categories)!=0) { ?>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th style="width: 1%">#</th>
                                <th style="width: 10%">Preview</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th style="width: 20%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($categories as $c) { ?>
                            <tr id="normalCon-<?= $c["id"] ?>" style="display: table-row">
                                <form class="form-inline" name="del_cat" method="post">
                                    <td><?= $c["id"] ?></td>
                                    <input type="number" readonly style="display: none" name="id" value="<?= $c["id"] ?>">
                                    <td><img src="assets/cats/<?= $c["image"] ?>" style="height: 100%; max-width: 100%" alt="Invalid"></td>
                                    <td><?= $c["image"] ?></td>
                                    <td><?= $c["name"] ?></td>
                                    <td>
                                        <button style="width: 49%; display: inline" type="button" id="btnxd1-<?= $c["id"] ?>" onclick="showEditCon(<?= $c["id"] ?>);" class="btn btn-success btn-sm"><?= glyph("pencil", "Edit Category") ?></button>
                                        <button style="width: 49%; display: inline" type="button" id="btnxd2-<?= $c["id"] ?>" onclick="showDelBtn(<?= $c["id"] ?>);" class="btn btn-danger btn-sm"><?= glyph("circle-minus", "Delete Category") ?></button>
                                        <button style="width: 49%; display: none" type="submit" name="del_cat" id="btnxd3-<?= $c["id"] ?>" class="btn btn-danger btn-sm"><?= glyph("trash-can", "Delete Category") ?></button>
                                        <button style="width: 49%; display: none" type="button" id="btnxd4-<?= $c["id"] ?>" onclick="hideDelBtn(<?= $c["id"] ?>);" class="btn btn-success btn-sm"><?= glyph("xmark", "Cancel") ?></button>
                                    </td>
                                </form>
                            </tr>
                            <tr id="editCon-<?= $c["id"] ?>" style="display: none">
                                <form class="form-inline" name="edit_cat" method="post">
                                    <td><?= $c["id"] ?></td>
                                    <input type="number" readonly style="display: none" name="id" value="<?= $c["id"] ?>">
                                    <input type="text" readonly style="display: none" name="name2" value="<?= $c["name"] ?>">
                                    <td><img src="assets/cats/<?= $c["image"] ?>" style="height: 100%; max-width: 100%" alt="Invalid"></td>
                                    <td>
                                        <div class="input-group">
                                            <div class="input-group-addon" style="padding: 11px">assets/cats/</div>
                                            <input required type="text" style="margin-bottom: -15px" name="image" class="form-control" value="<?= $c["image"] ?>" placeholder="yourfile.png">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input required type="text" style="margin-bottom: -15px" class="form-control" name="name" id="name" value="<?= $c["name"] ?>" placeholder="Name of category">
                                        </div>
                                    </td>
                                    <td>
                                        <button style="width: 49%" type="submit" name="edit_cat" class="btn btn-success btn-sm"><?= glyph("check", "Make Changes") ?></button>
                                        <button style="width: 49%" type="button" onclick="hideEditCon(<?= $c["id"] ?>);" class="btn btn-danger btn-sm"><?= glyph("xmark", "Dismiss Changes") ?></button>
                                    </td>
                                </form>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <?php } else { ?>
                <p>There are no categories at the moment.</p>
                <?php } ?>
                <form class="form-inline" name="add_cat" method="post">
                    <div class="form-group">
                        <label class="sr-only" for="exampleInputAmount">Image File</label>
                        <div class="input-group">
                            <div class="input-group-addon">assets/cats/</div>
                            <input required type="text" name="image" class="form-control" placeholder="yourfile.png">
                        </div>
                        <div class="form-group">
                            <label class="sr-only" for="name">Name</label>
                            <input required type="text" class="form-control" name="name" id="name" placeholder="Name of category">
                        </div>
                    </div>
                    <button type="submit" name="add_cat" class="btn btn-primary"><?= glyph("circle-plus", "Add Category", "flip") ?> Add Category</button>
                </form>
            </div>
        </div>

        <?php } elseif($tab=="stats") { ?>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Stats</h3>
            </div>
            <div class="panel-body">
                <p><?= glyph("users", "Total Users") ?> <b><?= $totalusers ?></b> total Users</p>
                <p><?= glyph("arrow-right-arrow-left", "Total Torrents") ?> <b><?= $totaltorrents ?></b> total Torrents</p>
                <p><?= glyph("seedling", "Total Peers") ?> <b><?= $totalpeers ?></b> total Peers</p>
            </div>
        </div>

        <?php } elseif($tab=="upgrade") { ?>

        <p>Current: <span class="label label-success"><?= $version ?></span></p>
        <p>Latest: <?php if($version!=$lversion) { echo '<span class="label label-danger">'; } else { echo '<span class="label label-success">'; } ?><?= $lversion ?><?= '</span>' ?></p>

        <?php if($version!=$lversion) { ?>
        <p><a href="?tab=upgrade&action=do">Perform update!</a></p>
        <?php } ?>
        <p>Everything's fine!</p>
        <?php } else { ?>

        <p>Hmm... whatever you tried searching for, is not to be found.</p>

        <?php } ?>

    </div>

</div>

<?php include("parts/footer.php"); ?>
