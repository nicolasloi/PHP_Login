<?php

use PhpExercice\Auth\Auth;
use PhpExercice\Db\Db;

require_once '../src/boot.php';

$auth = new Auth(new Db());

// vérifie si un utilisateur est connecté si oui -> envoie sur dashboard
if ($auth->check()) {
    header('Location: dashboard.php');
    die();
}

// vérifie si le formulaire est "submit"
if (isset($_POST['submit'])) {
    // utilise la methode register de Auth aevc les infos du formulaire
    $auth->register($_POST['email'], $_POST['password']);

    // redirige l'utilisateur vers dashboard
    header('Location: login.php');
    die();
}


require '../src/components/header.php'
?>

    <h1>Se créer un nouveau compte !</h1>

    <form action="register.php" method="post">
        <label for="email">Email</label>
        <input name="email" id="email" type="email">

        <label for="password">Password</label>
        <input name="password" id="password" type="password">
        <input type="submit" name="submit" value="Se connecter">
    </form>

<?php
require '../src/components/footer.php';