<?php

// I'm funky!

function glyph($glyph, $title, $anim = "") {
    return "<span class='fa-solid fa-".$glyph." fa-".$anim."' aria-hidden='true' title='".$title."'></span>";
}

function check($type, $val) {
    require("db.php");
    require("conn.php");
    require("account.php");
    if($type=="logged") {
        if($loggedin==false && ($val!="login" && $val!="signup")) {
            header("Location: login.php");
        }
    }
    if($type=="admin") {
        if($val!=1) {
            header("Location: home.php");
        }
    }
}

function clean($data) {
    $data = htmlspecialchars($data);
    $data = strip_tags($data);
    $data = stripslashes($data);
    $data = trim($data);
    $data = str_replace("'", "\'", $data); 
    return $data;
}

function bbconvert($text) {
	
	// Always ensure that user inputs are scanned and filtered properly.
	$text  = htmlspecialchars($text, ENT_QUOTES);

	// BBcode array
	$find = array(
		'~\[b\](.*?)\[/b\]~s',
		'~\[i\](.*?)\[/i\]~s',
		'~\[s\](.*?)\[/s\]~s',
		'~\[u\](.*?)\[/u\]~s',
		'~\[center\](.*?)\[/center\]~s',
		'~\[br\]~s',
		'~\[hr\]~s',
		'~\[quote\](.*?)\[/quote\]~s',
		'~\[size=(.*?)\](.*?)\[/size\]~s',
		'~\[color=(.*?)\](.*?)\[/color\]~s',
		'~\[url\]((?:ftp|https?)://.*?)\[/url\]~s',
		'~\[img\](https?://.*?\.(?:jpg|jpeg|gif|png|bmp))\[/img\]~s'
	);

	// HTML tags to replace BBcode
	$replace = array(
		'<b>$1</b>',
		'<i>$1</i>',
		'<s>$1</s>',
		'<span style="text-decoration:underline;">$1</span>',
        '<center>$1</center>',
        '<br>',
        '<hr>',
		'<pre>$1</'.'pre>',
		'<span style="font-size:$1px;">$2</span>',
		'<span style="color:$1;">$2</span>',
		'<a href="$1" target="_blank">$1</a>',
		'<img src="$1" alt="$1" />'
	);

	// Replacing the BBcodes with corresponding HTML tags
	return preg_replace($find,$replace,$text);
}

function count_it($type, $id) {
    require("db.php");
    require("conn.php");
    require("account.php");
    if($type=="torrents") {
        $c = $conn->query("SELECT COUNT(*) AS total FROM `torrents` WHERE `user`='$id'")->fetch_assoc();
        return $c["total"];
    }
}

?>
