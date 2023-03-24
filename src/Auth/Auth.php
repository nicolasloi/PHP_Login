<?php

// source : Nicoud Bastien

namespace PhpExercice\Auth;

use PhpExercice\Db\Db;

class Auth
{

    // propriété pour intéragir avec la DB
    private Db $db;

    // stock les infos sur l'utilisateur ou false si pas connecté
    private bool|array $user;

    // initialise l'objet Auth
    public function __construct(Db $db)
    {
        // affecte l'objet db à $db
        $this->db = $db;
        // si il y a une session active -> methode loadUser SINON user = false
        isset($_SESSION['user_id']) ? $this->loadUser($_SESSION['user_id']) : $this->user = false;
    }

    // la methode loadUser permets de charger les informations de l'utilisateur depuis la DB
    public function loadUser(int $id): void
    {
        // ici on utlise la methode run de DB pour faire une requete on on fetch ensuite et on stock dans $user
        $this->user = $this->db->run(
            'SELECT id, email, password FROM "user" WHERE id = ?',
            [$id]
        )->fetch();
    }

    // methode pour enregistrer un nouvel utilisateur dans la DB
    public function register(string $username, string $password): void
    {
        // on utlise la methode run de la classe DB pour inserer un nouvel utilisateur dans "user"
        $this->db->run(
            'INSERT INTO "user" (email, password) VALUES (?, ?);',
            [
                $username,
                // ici on Hasg le mdp
                password_hash($password, PASSWORD_DEFAULT)
            ]
        );
    }

    // la methode login vérifie l'email et le mdp pour connecter la personne ou pas
    public function login(string $email, string $password): bool|array
    {
        // vérifier si l'utilisateur est connecté avec la methode check
        if ($this->check()) {
            return $this->user;
        }

        // vérifie si l'utilisateur existe ou pas avec la methode userExists si il exist renvoie les informations dasn $user
        $user = $this->userExists($email);

        // on compare les 2 mdp avec password_verify (permet de controler un mdp haché)
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $this->user = $user;
            return $this->user;
        }

        return false;
    }

    // permet de vérifier si un utlisateur est connecté
    public function check(): bool|array
    {
        return $this->user;
    }

    // la methode userExists permet de vérifer si un utlisateur est connecté à l'aide de son email si aucon revoie false
    public function userExists(string $email): array
    {
        // avec la methode run de DB envoie une requete qui cherche l'utilisateur avec l'email et fetch ensuite
        return $this->db->run(
            'SELECT id, email, password FROM "user" WHERE email = ?',
            [$email]
        )->fetch();
    }

    // permet déconnecte l'utilisateur connecté
    public function logout(): void
    {
        // supprime l'identifiant de l'utilisateur de la variable de session "user_id"
        unset($_SESSION['user_id']);
        // réinitialise la propriété $user sur false -> aucun utilisateur connecté
        $this->user = false;
    }
}
