<?php

use PhpExercice\Auth\Auth;
use PhpExercice\Db\Db;

require_once '../src/boot.php';

// utilise la methode logout
$auth = new Auth(new Db());
$auth->logout();

// envoie l'utilisateur sur index.php
header('Location: index.php');
    die();
