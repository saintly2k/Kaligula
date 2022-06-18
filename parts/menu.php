</head>

<body>

    <div class="container">

        <nav class="navbar navbar-default" style="margin-top: 40px">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="<?= $config["url"] ?>"><?= $config["title"] ?></a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li <?php if($page=="home") echo 'class="active"'; ?>><a href="<?= $config["url"] ?>home.php"><?= glyph("arrow-right-arrow-left", "Torrents") ?> Torrents</a></li>
                        <li <?php if($page=="groups") echo 'class="active"'; ?>><a href="<?= $config["url"] ?>groups.php"><?= glyph("users", "Groups") ?> Groups</a></li>
                        <li <?php if($page=="upload") echo 'class="active"'; ?>><a href="<?= $config["url"] ?>upload.php"><?= glyph("upload", "Upload") ?> Upload</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?= glyph("user", "Account") ?> <?= $user["username"] ?><span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li <?php if($page=="my-profile") echo 'class="active"'; ?>><a href="<?= $config["url"] ?>profile.php?name=<?= $user["username"] ?>"><?= glyph("id-card", "My Profile") ?> My Profile</a></li>
                                <li <?php if($page=="account") echo 'class="active"'; ?>><a href="<?= $config["url"] ?>account.php?tab=settings"><?= glyph("user-gear", "Account") ?> Account</a></li>
                                <li <?php if($page=="my-torrents") echo 'class="active"'; ?>><a href="<?= $config["url"] ?>account.php?tab=torrents.php"><?= glyph("paste", "My Torrents") ?> My Torrents</a></li>
                                <li <?php if($page=="my-invites") echo 'class="active"'; ?>><a href="<?= $config["url"] ?>account.php?tab=invites"><?= glyph("code-fork", "My Invites") ?> My Invites</a></li>
                                <li <?php if($page=="logout") echo 'class="active"'; ?>><a href="?logout"><?= glyph("arrow-right-from-bracket", "Logout") ?> Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                    <form class="navbar-form navbar-right">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Quicksearch Torrent">
                        </div>
                        <button type="submit" class="btn btn-default"><?= glyph("magnifying-glass", "Search") ?> Search</button>
                    </form>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
