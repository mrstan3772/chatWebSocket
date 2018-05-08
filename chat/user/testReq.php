<?php
/**
 * Created by PhpStorm.
 * User: MrStan
 * Date: 09/04/2018
 * Time: 21:41
 */
define('ROOT_PATH', dirname(__DIR__) . '/');
require ROOT_PATH.'/inc/db_connect.php';
$hash = '$2y$10$rqW2pDSfAXxy/tcDiUx5xeJAuARhVH5VhtDY7jf7Nj2GuixqbgGWu';
$username = "MrStan3772";
$password = "P@ssword1";


try {
    $db = DB();
    $query = $db->prepare("SELECT user_id, password FROM dbo.users WHERE (username=:username OR email=:email)");
    $query->bindParam("username", $username, PDO::PARAM_STR);
    $query->bindParam("email", $username, PDO::PARAM_STR);
    $query->execute();
    $res = $query->fetch(PDO:: FETCH_OBJ);
    if ($res->user_id > 0) {
        if (password_verify($password, $res->password)) {
            return $res->user_id;
        }
    } else {
        return false;
    }
} catch (PDOException $e) {
    exit($e->getMessage());
}