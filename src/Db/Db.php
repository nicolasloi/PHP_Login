<?php

namespace PhpExercice\Db;

use PDO;

class Db
{
    private \PDO $pdo;

    // les options par default
    private array $options = [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ];

    public function __construct(
        $options = []
    ) {

        // si il y a des options perso ça remplace les anciennes par les nouvelles options.
        $options = array_replace($this->options, $options);
        // à l'aide des variables d'environnement crée une connexion DSN
        $dsn = "pgsql:host={$_ENV['DB_HOST']};
                port={$_ENV['DB_PORT']};
                dbname={$_ENV['DB_NAME']};
                user={$_ENV['DB_USER']};
                password={$_ENV['DB_PASSWORD']}";

        // ici on essaye de crée une nouvelle instance de la classe PDO. si il y a une erreur on la capture et on affiche le message d'erreur
        try {
            $this->pdo = new \PDO(
                $dsn,
                $_ENV['DB_USER'],
                $_ENV['DB_PASSWORD'],
                $this->options
            );
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    // vérifie si il y a des parametres si non -> query || si oui -> prepare
    public function run($queryString, $params = null): false|\PDOStatement
    {
        if (!$params) {
            return $this->query($queryString);
        }
        return $this->prepare($queryString, $params);
    }

    // methode pour executer une requête SQL sans parametre
    public function query(string $queryString): false|\PDOStatement
    {
        return $this->pdo->query($queryString);
    }

    // méthode pour executer une requête SQL avec des paramètres
    public function prepare(string $queryString, array $params): false|\PDOStatement
    {
        $stmt = $this->pdo->prepare($queryString);
        $stmt->execute($params);
        return $stmt;
    }
}
