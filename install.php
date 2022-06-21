<?php

if(file_exists(".installed")) {
    die("System already installed. Exiting for security purposes because file has not been deleted?");
}

session_start();

//error_reporting(0);
//ini_set('display_errors', 0);
error_reporting(E_ALL);
ini_set('display_errors', 1);

$version = "v0.0.1";

if(empty($_POST["step"])) {
    $step = 1;
} else {
    $step = $_POST["step"];
}

$error = false;
$error_msg = "";

if(isset($_POST["dbconfig"])) {
    $_SESSION["db"]["host"] = $_POST["db_host"];
    $_SESSION["db"]["user"] = $_POST["db_user"];
    $_SESSION["db"]["pass"] = $_POST["db_pass"];
    $_SESSION["db"]["tale"] = $_POST["db_tale"];
    $admin_user = $_POST["admin_user"];
    $admin_pass = $_POST["admin_pass"];
    $admin_pass2 = $_POST["admin_pass2"];
    
    $conn = new mysqli($_SESSION["db"]["host"], $_SESSION["db"]["user"], $_SESSION["db"]["pass"], $_SESSION["db"]["tale"]);
    $conn->set_charset("utf8mb4");

    if($conn->connect_error) {
        $error = true;
        $error_msg = $conn->connect_error;
    }
    
    if($admin_pass!=$admin_pass2) {
        $error = true;
        $error_msg = "Admin Passwords do not match!";
    }
    
    if($admin_user=="admin") {
        $error = true;
        $error_msg = "ARE YOU OUT OF YOUR MIND??? DON'T CALL YOUR USER ADMIN!!!!";
    }
    
    $file = fopen("core/db.php", "w") or die("Unable to open file! Make sure to chmod 755 whole folder and try installing again! If error keeps, create an issue on the GitHub.");
    fwrite($file, "<?php\n");
    fwrite($file, "\n");
    fwrite($file, "\$slave = [\n");
    fwrite($file, "    \"host\" => \"".$_SESSION["db"]["host"]."\",\n");
    fwrite($file, "    \"user\" => \"".$_SESSION["db"]["user"]."\",\n");
    fwrite($file, "    \"pass\" => \"".$_SESSION["db"]["pass"]."\",\n");
    fwrite($file, "    \"table\" => \"".$_SESSION["db"]["tale"]."\"\n");
    fwrite($file, '];');
    fclose($file);
    
    if($error==false) {
        
        // Import Database - https://stackoverflow.com/questions/19751354/how-do-i-import-a-sql-file-in-mysql-database-using-php Thx!
        $filename = 'kaligulatorrents.sql';
        $templine = '';
        $lines = file($filename);
        foreach ($lines as $line) {
            if (substr($line, 0, 2) == '--' || $line == '')continue;
            $templine .= $line;
            if (substr(trim($line), -1, 1) == ';') {
                $conn->query($templine);
                $templine = '';
            }
        }
        
        $admin_pass = password_hash($admin_pass, PASSWORD_BCRYPT);
        $conn->query("INSERT INTO `user`(`username`,`password`,`level`) VALUES('$admin_user','$admin_pass','1')");
        $step = 3;
    } else {
        $step = 2;
    }
}

if(isset($_POST["siteconfig"])) {
    $sql = "INSERT INTO `config`(`title`,`slogan`,`registration`,`fontawesome`,`cookie`,`url`,`announce`) VALUES ('".$_POST["site_title"]."', '".$_POST["site_slogan"]."', '".$_POST["site_registration"]."', '".$_POST["site_fontawesome"]."', '".$_POST["site_cookie"]."', '".$_POST["site_url"]."', '".$_POST["site_announce"]."')";
    
    $conn = new mysqli($_SESSION["db"]["host"], $_SESSION["db"]["user"], $_SESSION["db"]["pass"], $_SESSION["db"]["tale"]);
    $conn->set_charset("utf8mb4");
    
    if(mysqli_query($conn, $sql)) {} else { $error = true; $error_msg = "Error adding config: ".mysqli_error($conn); }
    
    if($error==false) {
        $step = 4;
    } else {
        $step = 3;
    }
}

if(isset($_POST["finish"])) {
    $file = fopen(".installed", "w") or die("Error: Missing permissions to create file (Fix: CHMOD 755 all files)");
    fwrite($file, $version);
    fclose($file);
    unlink(".gitignore");
    unlink("LICENSE");
    unlink("README.md");
    unlink("kaligulatorrents.sql");
    unlink("install.php");
    header("Location: login.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>

    <title>Install Kaligula Torrent software</title>

    <style>
        .wizard {
            margin: 20px auto;
            background: #fff;
        }

        .wizard .nav-tabs {
            position: relative;
            margin: 40px auto;
            margin-bottom: 0;
            border-bottom-color: #e0e0e0;
        }

        .wizard>div.wizard-inner {
            position: relative;
        }

        .connecting-line {
            height: 2px;
            background: #e0e0e0;
            position: absolute;
            width: 80%;
            margin: 0 auto;
            left: 0;
            right: 0;
            top: 50%;
            z-index: 1;
        }

        .wizard .nav-tabs>li.active>a,
        .wizard .nav-tabs>li.active>a:hover,
        .wizard .nav-tabs>li.active>a:focus {
            color: #555555;
            cursor: default;
            border: 0;
            border-bottom-color: transparent;
        }

        span.round-tab {
            width: 70px;
            height: 70px;
            line-height: 70px;
            display: inline-block;
            border-radius: 100px;
            background: #fff;
            border: 2px solid #e0e0e0;
            z-index: 2;
            position: absolute;
            left: 0;
            text-align: center;
            font-size: 25px;
        }

        span.round-tab i {
            color: #555555;
        }

        .wizard li.active span.round-tab {
            background: #fff;
            border: 2px solid #5bc0de;

        }

        .wizard li.active span.round-tab i {
            color: #5bc0de;
        }

        span.round-tab:hover {
            color: #333;
            border: 2px solid #333;
        }

        .wizard .nav-tabs>li {
            width: 25%;
        }

        .wizard li:after {
            content: " ";
            position: absolute;
            left: 46%;
            opacity: 0;
            margin: 0 auto;
            bottom: 0px;
            border: 5px solid transparent;
            border-bottom-color: #5bc0de;
            transition: 0.1s ease-in-out;
        }

        .wizard li.active:after {
            content: " ";
            position: absolute;
            left: 46%;
            opacity: 1;
            margin: 0 auto;
            bottom: 0px;
            border: 10px solid transparent;
            border-bottom-color: #5bc0de;
        }

        .wizard .nav-tabs>li a {
            width: 70px;
            height: 70px;
            margin: 20px auto;
            border-radius: 100%;
            padding: 0;
        }

        .wizard .nav-tabs>li a:hover {
            background: transparent;
        }

        .wizard .tab-pane {
            position: relative;
            padding-top: 50px;
        }

        .wizard h3 {
            margin-top: 0;
        }

        .step1 .row {
            margin-bottom: 10px;
        }

        .step_21 {
            border: 1px solid #eee;
            border-radius: 5px;
            padding: 10px;
        }

        .step33 {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding-left: 10px;
            margin-bottom: 10px;
        }

        .dropselectsec {
            width: 68%;
            padding: 6px 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
            color: #333;
            margin-left: 10px;
            outline: none;
            font-weight: normal;
        }

        .dropselectsec1 {
            width: 74%;
            padding: 6px 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
            color: #333;
            margin-left: 10px;
            outline: none;
            font-weight: normal;
        }

        .mar_ned {
            margin-bottom: 10px;
        }

        .wdth {
            width: 25%;
        }

        .birthdrop {
            padding: 6px 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
            color: #333;
            margin-left: 10px;
            width: 16%;
            outline: 0;
            font-weight: normal;
        }


        /* according menu */
        #accordion-container {
            font-size: 13px
        }

        .accordion-header {
            font-size: 13px;
            background: #ebebeb;
            margin: 5px 0 0;
            padding: 7px 20px;
            cursor: pointer;
            color: #fff;
            font-weight: 400;
            -moz-border-radius: 5px;
            -webkit-border-radius: 5px;
            border-radius: 5px
        }

        .unselect_img {
            width: 18px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        .active-header {
            -moz-border-radius: 5px 5px 0 0;
            -webkit-border-radius: 5px 5px 0 0;
            border-radius: 5px 5px 0 0;
            background: #F53B27;
        }

        .active-header:after {
            content: "\f068";
            font-family: 'FontAwesome';
            float: right;
            margin: 5px;
            font-weight: 400
        }

        .inactive-header {
            background: #333;
        }

        .inactive-header:after {
            content: "\f067";
            font-family: 'FontAwesome';
            float: right;
            margin: 4px 5px;
            font-weight: 400
        }

        .accordion-content {
            display: none;
            padding: 20px;
            background: #fff;
            border: 1px solid #ccc;
            border-top: 0;
            -moz-border-radius: 0 0 5px 5px;
            -webkit-border-radius: 0 0 5px 5px;
            border-radius: 0 0 5px 5px
        }

        .accordion-content a {
            text-decoration: none;
            color: #333;
        }

        .accordion-content td {
            border-bottom: 1px solid #dcdcdc;
        }



        @media(max-width : 585px) {

            .wizard {
                width: 90%;
                height: auto !important;
            }

            span.round-tab {
                font-size: 16px;
                width: 50px;
                height: 50px;
                line-height: 50px;
            }

            .wizard .nav-tabs>li a {
                width: 50px;
                height: 50px;
                line-height: 50px;
            }

            .wizard li.active:after {
                content: " ";
                position: absolute;
                left: 35%;
            }
        }

    </style>

    <script>
        $(document).ready(function() {
            //Initialize tooltips
            $('.nav-tabs > li a[title]').tooltip();

            //Wizard
            $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {

                var $target = $(e.target);

                if ($target.parent().hasClass('disabled')) {
                    return false;
                }
            });

            $(".next-step").click(function(e) {

                var $active = $('.wizard .nav-tabs li.active');
                $active.next().removeClass('disabled');
                nextTab($active);

            });
            $(".prev-step").click(function(e) {

                var $active = $('.wizard .nav-tabs li.active');
                prevTab($active);

            });
        });

        function nextTab(elem) {
            $(elem).next().find('a[data-toggle="tab"]').click();
        }

        function prevTab(elem) {
            $(elem).prev().find('a[data-toggle="tab"]').click();
        }


        //according menu

        $(document).ready(function() {
            //Add Inactive Class To All Accordion Headers
            $('.accordion-header').toggleClass('inactive-header');

            //Set The Accordion Content Width
            var contentwidth = $('.accordion-header').width();
            $('.accordion-content').css({});

            //Open The First Accordion Section When Page Loads
            $('.accordion-header').first().toggleClass('active-header').toggleClass('inactive-header');
            $('.accordion-content').first().slideDown().toggleClass('open-content');

            // The Accordion Effect
            $('.accordion-header').click(function() {
                if ($(this).is('.inactive-header')) {
                    $('.active-header').toggleClass('active-header').toggleClass('inactive-header').next().slideToggle().toggleClass('open-content');
                    $(this).toggleClass('active-header').toggleClass('inactive-header');
                    $(this).next().slideToggle().toggleClass('open-content');
                } else {
                    $(this).toggleClass('active-header').toggleClass('inactive-header');
                    $(this).next().slideToggle().toggleClass('open-content');
                }
            });

            return false;
        });

    </script>

</head>

<body>

    <div class="container">
        <div class="row">
            <section>
                <div class="wizard">
                    <div class="wizard-inner">
                        <div class="connecting-line"></div>
                        <ul class="nav nav-tabs" role="tablist">

                            <li role="presentation" <?php if($step==1) echo "class='active'"; ?>>
                                <a href="#">
                                    <span class="round-tab">
                                        <i class="glyphicon glyphicon-bullhorn"></i>
                                    </span>
                                </a>
                            </li>

                            <li role="presentation" <?php if($step==2) echo "class='active'"; ?>>
                                <a href="#">
                                    <span class="round-tab">
                                        <i class="glyphicon glyphicon-folder-open"></i>
                                    </span>
                                </a>
                            </li>

                            <li role="presentation" <?php if($step==3) echo "class='active'"; ?>>
                                <a href="#">
                                    <span class="round-tab">
                                        <i class="glyphicon glyphicon-pencil"></i>
                                    </span>
                                </a>
                            </li>

                            <li role="presentation" <?php if($step==4) echo "class='active'"; ?>>
                                <a href="#">
                                    <span class="round-tab">
                                        <i class="glyphicon glyphicon-ok"></i>
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="tab-content">
                        <?php if($error==true) { ?>
                        <div class="alert alert-danger" role="alert"><b>Error!!!</b>: <?= $error_msg ?></div>
                        <?php } ?>
                        <div class="tab-pane <?php if($step==1) echo "active"; ?>">
                            <h2 class="text-center">Welcome to Kaligula Torrent tracker software developed by TEAM H33T!</h2>
                            <p>Here you will install and configure Kaligula for the first time in three steps. Fill out Database information, Site information, and click done. THat's it.</p>
                            <p>You will need following:</p>
                            <ul>
                                <li>MySQL Database and its user and password</li>
                                <li>chmod 755 on this whole folder</li>
                                <li>A minute of your time</li>
                            </ul>
                            <ul class="list-inline pull-right">
                                <form method="post" name="step">
                                    <input type="number" name="step" value="2" style="display: none">
                                    <li><button type="submit" class="btn btn-primary prev-step">Start installation!</button></li>
                                </form>
                            </ul>
                        </div>
                        <div class="tab-pane <?php if($step==2) echo "active"; ?>">

                            <form method="post" name="dbconfig" class="form-horizontal">
                                <h2>Database configuration</h2>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="db_host" class="control-label col-sm-3">Host</label>
                                            <div class="col-sm-9">
                                                <input tabindex="1" type="text" required name="db_host" id="db_host" placeholder="localhost" value="<?php if(isset($_POST["db_host"])) echo $_POST["db_host"]; ?>" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="db_pass" class="control-label col-sm-3">Password</label>
                                            <div class="col-sm-9">
                                                <input tabindex="3" type="text" name="db_pass" id="db_pass" placeholder="root" class="form-control" value="<?php if(isset($_POST["db_pass"])) echo $_POST["db_pass"]; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="db_user" class="control-label col-sm-3">User</label>
                                            <div class="col-sm-9">
                                                <input tabindex="2" type="text" required name="db_user" id="db_user" placeholder="root" class="form-control" value="<?php if(isset($_POST["db_user"])) echo $_POST["db_user"]; ?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="db_tale" class="control-label col-sm-3">Table</label>
                                            <div class="col-sm-9">
                                                <input tabindex="4" type="text" required name="db_tale" id="db_tale" placeholder="kaligulatorrents" class="form-control" value="<?php if(isset($_POST["db_tale"])) echo $_POST["db_tale"]; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h2>Admin info configuration</h2>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="admin_user" class="control-label col-sm-3">Username</label>
                                            <div class="col-sm-9">
                                                <input tabindex="5" minlength="3" type="text" required name="admin_user" id="admin_user" placeholder="yourname" class="form-control" value="<?php if(isset($_POST["admin_user"])) echo $_POST["admin_user"]; ?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="admin_pass2" class="control-label col-sm-3">Repeat Password</label>
                                            <div class="col-sm-9">
                                                <input tabindex="7" minlength="8" type="password" required name="admin_pass2" id="admin_pass2" placeholder="password (again)" value="" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="admin_pass" class="control-label col-sm-3">Password</label>
                                            <div class="col-sm-9">
                                                <input tabindex="6" minlength="8" type="password" required name="admin_pass" id="admin_pass" placeholder="password" value="" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <ul class="list-inline pull-right">
                                    <li><button type="submit" name="dbconfig" class="btn btn-primary">Save and continue</button></li>
                                </ul>
                            </form>
                        </div>
                        <div class="tab-pane <?php if($step==3) echo "active"; ?>">

                            <form method="post" name="siteconfig" class="form-horizontal">
                                <h2>Site configuration</h2>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="site_title" class="control-label col-sm-3">Title</label>
                                            <div class="col-sm-9">
                                                <input tabindex="1" type="text" required name="site_title" id="site_title" placeholder="Kaligula" value="<?php if(isset($_POST["site_title"])) echo $_POST["site_title"]; ?>" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="site_registration" class="control-label col-sm-3">Registration</label>
                                            <div class="col-sm-9">
                                                <select tabindex="3" name="site_registration" id="site_registration" class="selectpicker form-control">
                                                    <option value="1" selected>Open</option>
                                                    <option value="0" <?php if(isset($_POST["site_registration"]) && $_POST["site_registration"]==0) echo "selected"; ?>>Closed</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="site_cookie" class="control-label col-sm-3">Cookie</label>
                                            <div class="col-sm-9">
                                                <input tabindex="5" type="text" required name="site_cookie" id="site_cookie" placeholder="kaligula" value="<?php if(isset($_POST["site_cookie"])) echo $_POST["site_cookie"]; ?>" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="site_announce" class="control-label col-sm-3">announce.php</label>
                                            <div class="col-sm-9">
                                                <input tabindex="7" type="text" required name="site_announce" id="site_announce" placeholder="announce.php" value="<?php if(isset($_POST["site_announce"])) echo $_POST["site_announce"]; ?>" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="site_slogan" class="control-label col-sm-3">Slogan</label>
                                            <div class="col-sm-9">
                                                <input tabindex="2" type="text" required name="site_slogan" id="site_slogan" placeholder="Shadow and Light!" class="form-control" value="<?php if(isset($_POST["site_slogan"])) echo $_POST["site_slogan"]; ?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="site_fontawesome" class="control-label col-sm-3">Fontawesome JS</label>
                                            <div class="col-sm-9">
                                                <input tabindex="4" type="text" required name="site_fontawesome" id="site_fontawesome" placeholder="a2f2g2f2.js" class="form-control" value="<?php if(isset($_POST["site_fontawesome"])) echo $_POST["site_fontawesome"]; ?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="site_url" class="control-label col-sm-3">URL with slash</label>
                                            <div class="col-sm-9">
                                                <input tabindex="6" type="text" required name="site_url" id="site_url" placeholder="https://yoursite.com/torrents/" class="form-control" value="<?php if(isset($_POST["site_url"])) echo $_POST["site_url"]; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <ul class="list-inline pull-right">
                                    <li><button type="submit" name="siteconfig" class="btn btn-primary">Save and continue</button></li>
                                </ul>
                            </form>
                        </div>
                        <div class="tab-pane <?php if($step==4) echo "active"; ?>">
                            <h2 class="text-center">Congratulations! You have successfully installed Kaligula!</h2>
                            <form method="post" name="finish">
                                <ul class="list-inline pull-right">
                                    <li><button type="submit" name="finish" class="btn btn-success">Finish installation!</button></li>
                                </ul>
                            </form>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</body>

</html>
