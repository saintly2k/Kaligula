<?php

$slave = [
    "host" => "localhost",
    "user" => "root",
    "pass" => "",
    "table" => "kaligulatorrents"
];

if(!defined('__DB_SERVER')) { define('__DB_SERVER', $slave["host"]); }
if(!defined('__DB_USERNAME')) { define('__DB_USERNAME', $slave["user"]); }
if(!defined('__DB_PASSWORD')) { define('__DB_PASSWORD', $slave["pass"]); }
if(!defined('__DB_DATABASE')) { define('__DB_DATABASE', $slave["table"]); }
if(!defined('__DB_TABLE')) { define('__DB_TABLE', 'peers'); }

?>