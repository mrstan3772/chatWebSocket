<?php
define('ROOT_PATH', dirname(__DIR__) . '/');

require ROOT_PATH.'/session.php';

require_once ROOT_PATH.'/inc/db_connect.php';


// Application library ( with DemoLib class )
require ROOT_PATH . '/inc/library.php';

$app = new DemoLib();

$register_error_message = array();

if (!empty($_POST)) {
    $email = strip_tags(trim($_POST['email']));
    $username = strip_tags(trim($_POST['username']));
    $category = strip_tags(trim($_POST['selectCategory']));
    $password = strip_tags(trim($_POST['password']));
    $confirmPassword = strip_tags(trim($_POST['confirmPassword']));
    $confirmPassword = strip_tags(trim($_POST['confirmPassword']));
    $categoryPossibility =  array(1, 2, 3);

    if (empty($email)) {
        $email_error_message = 'Le champ "Email" est requis !';
        $register_error_message[0] = $email_error_message;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_error_message = 'Email invalide !';
        $register_error_message[0] = $email_error_message;
    }
    if ((!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) && ($app->isEmail($email))) {
        $email_error_message = 'Email déja utilisé !';
        $register_error_message[0] = $email_error_message;
    }
    if (empty($username)) {
        $username_error_message = 'Le champ "Nom d\'utilisateur" est requis !';
        $register_error_message[1] = $username_error_message;
    }
    if ((!empty($username)) && ($app->isUsername($username))) {
        $username_error_message = 'Ce nom d\'utilisateur est déja pris !';
        $register_error_message[1] = $username_error_message;
    }
    if ((!empty($category)) && (!in_array($category, $categoryPossibility))) {
        $username_error_message = 'Catégorie nom attribué !';
        $register_error_message[2] = $username_error_message;
    }
    if (empty($password)) {
        $password_error_message = 'Le champ "Mot de passe" est requis !';
        $register_error_message[3] = $password_error_message;
    }
    if (empty($confirmPassword)) {
        $confirmPassword_error_message = 'Le champ "Confirmation du mot passe" est requis !';
        $register_error_message[4] = $confirmPassword_error_message;
    }

    if((!empty($password) && !empty($confirmPassword) && ($password != $confirmPassword))){
        $confirmPasswordSame_error_message = 'Les deux mots de passe ne sont pas identiques';
        $register_error_message[5] = $confirmPasswordSame_error_message;
    }
    if (!$_FILES) {
        $upload_error_message = 'Aucune image trouvé !';
        array_push($register_error_message, $upload_error_message);
    }
    if(empty($register_error_message)){
        $user_id = $app->Register($email, $username, $category, $password);
        // set session and redirect user to the profile page
        $session->set('user_id', $user_id);
        //header("Location: profile.php");
    }
}

if (!empty($register_error_message)) {
    foreach($register_error_message as $key=>$message) {
        echo '<div class="alert alert-danger errorForm'.$key.'"><strong>Erreur : </strong> ' . $message . '</div>';
    }
}
?>