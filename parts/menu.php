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
                        <li class="active"><a href="<?= $config["url"] ?>">Torrents</a></li>
                        <li><a href="<?= $config["url"] ?>upload">Upload</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Not logged in! <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="<?= $config["url"] ?>login">Login</a></li>
                                <li><a href="<?= $config["url"] ?>singup">Signup</a></li>
                            </ul>
                        </li>
                    </ul>
                    <form class="navbar-form navbar-right">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Quicksearch Torrent">
                        </div>
                        <button type="submit" class="btn btn-default">Search</button>
                    </form>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
