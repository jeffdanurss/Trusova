<?php
class User {
    private PDO $db;
    public function __construct(){
        $this->db = Database::getConnection();
    }

    public function create(string $name, string $email, string $password): int {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $userInsert = $this->db->prepare(
            'INSERT INTO users (name,email,password_hash) VALUES(:name,:email,:hash) RETURNING id'
        );
        $userInsert->execute(
            [
                'name'=>$name,
                'email'=>$email,
                'hash'=>$hash
            ]
        );
        return (int)$userInsert->fetchColumn();
    }

    public function find (int $id): ?array {
        $userFind = $this->db->prepare(
            'SELECT id,name,email FROM users WHERE id= :id'
        );
        $userFind->execute(
            [
                'id'=>$id
            ]
        );
        $user = $userFind->fetch();
        return $user ?: null;
    }

    public function findByEmail (string $email): ?array {
        $emailfind = $this->db->prepare(
            'SELECT * FROM users WHERE email = :email'
        );
        $emailfind->execute(
            [
                'email'=>$email
            ]
        );
        $user = $emailfind->fetch();
        return $user ?:null;
    }

    public function verify(string $email, string $password): ?array{
        $user = $this->findByEmail($email);
        if ($user && password_verify($password, $user['password_hash'])) {
            return $user;
        }
        return null;
    }

}