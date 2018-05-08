<?php
/**
 * Tutorial: PHP Login Registration system
 *
 * Page : Logout
 */

define('ROOT_PATH', dirname(__DIR__) . '/');


require ROOT_PATH.'/session.php';


$session->remove('user_id');

// Redirect to index.php page
header("Location: login.php");
?>