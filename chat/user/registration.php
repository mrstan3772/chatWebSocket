<?php
?>
<?php
define('ROOT_PATH', dirname(__DIR__) . '/');

require ROOT_PATH.'/session.php';
?>
<!DOCTYPE html>
<html>
<head lang="FR-fr">
    <meta charset="utf-8" />
    <meta name="description" content="Page d'inscription pour les membres non enregistrés" />
    <?php require_once('../include_header.php'); ?>
    <title>Page d'inscription</title>
</head>

<body>
<form  id="userRegisterForm" class="dropzone" action="validateRegistration.php" method="post">
    <div id="errorMessages">
        <?php
            if (!empty($register_error_message)) {
                foreach($register_error_message as $message) {
                echo '<div class="alert alert-danger"><strong>Erreur : </strong> ' . $message . '</div>';
                }
            }
        ?>
    </div>

    <div class="inputGroup inputGroup1">
        <label for="email">Email</label>
        <input type="text" id="email" class="email" maxlength="256" name="email"/>
    </div>
    <div class="inputGroup inputGroup2">
        <label for="username">Pseudo</label>
        <input type="text" id="username" class="username" maxlength="256" name="username"/>
    </div>
    <div class="inputGroup inputGroup3">
        <label for="username">Catégorie</label>
        <select id="selectCategory" name="selectCategory">
            <option value="1" selected>Étudiant</option>
            <option value="2">Professeur</option>
            <option value="3">Entreprise</option>
        </select>
    </div>
    <div class="inputGroup inputGroup4">
        <label for="password">Mot de passe</label>
        <input type="password" id="password" class="password" name="password" />
    </div>
    <div class="inputGroup inputGroup4">
        <label for="confirmPassword">Confirmation du mot de passe</label>
        <input type="password" id="confirmPassword" class="confirmPassword" name="confirmPassword" />
    </div>
    <div class="inputGroup inputGroup5">
        <div class="dropzone" id="myDropZone"></div>
    </div>
    <div class="inputGroup inputGroup6">
        <button id="registrationButton" type="button" name="btnRegister">S'inscrire</button>
    </div>
    <div class="inputGroup inputGroup7">
        <button type="button" class="loginLocation" name="btnRegister" onclick="window.location.href='login.php'">Déjà un compte</button>
    </div>
</form>
<footer>
    <script src="../cdn/upload_avatar.js"></script>
</footer>
</body>
</html>



