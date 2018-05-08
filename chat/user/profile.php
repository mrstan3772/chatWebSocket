<?php
/**
 * Tutorial: PHP Login Registration system
 *
 * Page : Profile
 */

define('ROOT_PATH', dirname(__DIR__) . '/');


require ROOT_PATH.'/session.php';

if(empty($session->get('user_id')))
{
    header("Location: login.php");
}


require_once ROOT_PATH.'/inc/db_connect.php';

$db = DB();

require ROOT_PATH . '/inc/library.php';
$app = new DemoLib();
$user = $app->UserDetails($session->get('user_id')); // get user details
$session->set('email', $user->email);
$session->set('username', $user->username);
$session->set('avatar', $user->avatar);
$session->set('category', $user->userCategory);
$session->set('categoryName', $user->categoryName);
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
    <?php require_once('../include_header.php'); ?>
</head>
<body>
<div class="container">
    <div class="well">
        <h2>
            Profile
        </h2>
        <h3>Salut <?php echo $user->username ?>,</h3>
        <p>
            Chat en ligne utilisant le standard webSocket pour une communication full-duplex. Il est actuellement en cours de développement et connaîtra de futures améliorations.
        </p>
        <a href="../index.php" class="btn btn-outline-success btn-lg btn-block">Lancez-vous !</a>
        <a href="logout.php" class="btn btn-primary fixed-bottom" style="border-radius: 0 !important;">Se déconnecter</a>
    </div>
</div>
</body>
</html>