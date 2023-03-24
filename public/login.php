<?php

use PhpExercice\Auth\Auth;
use PhpExercice\Db\Db;

require_once '../src/boot.php';

$auth = new Auth(new Db());

// vérifie si auth connecté si oui -> dashboard
if ($auth->check()) {
    header('Location: dashboard.php');
    die();
}

// vérifie si le formulaire est "submit"
if (isset($_POST['submit'])) {
    // il appelle la methode login de Auth pour tenter d'authentifier l'utilisateur. prend le mdp et l'email du form en arguments
    // si ok -> envoie sur dashboard sinon message d'erreur
    if ($auth->login($_POST['email'], $_POST['password'])) {
        header('Location: dashboard.php');
        die();
    }

    echo 'Il y a eu une erreur, veuillez réessayer !';
}


require '../src/components/header.php'
?>

    <h1>Le login</h1>

    <form action="login.php" method="post">
        <label for="email">Email</label>
        <input name="email" id="email" type="email">

        <label for="password">Password</label>
        <input name="password" id="password" type="password">
        <input type="submit" name="submit" value="Se connecter">
    </form>

<?php
require '../src/components/footer.php';
