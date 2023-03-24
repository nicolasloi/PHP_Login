<?php

use PhpExercice\Auth\Auth;
use PhpExercice\Db\Db;

require_once '../src/boot.php';


$auth = new Auth(new Db());

// vérifie si l'utilisateur est connecté si non -> login.php.
if (!$auth->check()) {
    header('Location: login.php');
    die();
}

require '../src/components/header.php'
?>

    <h1>Dashboard</h1>

    <a href="logout.php">Logout</a>

<?php
require '../src/components/footer.php';
