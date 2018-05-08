<?php
/**
 * Created by PhpStorm.
 * User: MrStan
 * Date: 05/05/2018
 * Time: 18:44
 */

require(__DIR__.'../../inc/db_connect.php');
$db = DB();
$req = $db->query("SELECT * FROM CHAT.wsMessages");
$msg = $req->fetchAll();
print_r($msg);
