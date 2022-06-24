<?php

namespace App\Mapper;
use App\Model\User;
use PDO;
class UserMapper
{
    private $connection;
    public function __construct()
    {
        $this->connection = new PDO ('mysql:host=localhost:3306;dbname=auth', 'root', 'dfdb7kjy3000');
    }
    public function getAll(){
        $query = 'SELECT * from user';
        $sql = $this->connection->prepare($query);
        $sql->execute();
        $results = $sql->fetchAll();
        $func = fn($x) => self::ConvertToUser($x);
        return array_map($func, $results);
    }
    public function getById($id){
        $query = 'SELECT * from user WHERE id=:id';
        $sql = $this->connection->prepare($query);
        $sql->bindParam('id', $id,PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetchAll();
    }
    public function getByUsername($username)
    {
        $query = 'SELECT * from user WHERE username=:username';
        $sql = $this->connection->prepare($query);
        $sql->bindParam('phoneNumber', $username,PDO::PARAM_STR);
        $sql->execute();
        return $sql->fetchAll();
    }
    public function deleteById($id){
        $query = 'DELETE FROM `auth`.`user` WHERE id=:id;';
        $sql = $this->connection->prepare($query);
        $sql->bindParam('id', $id,PDO::PARAM_INT);
        $sql->execute();
    }
    public function add($name, $password){
        $query = 'INSERT INTO `auth`.`user` (`username`, `password`) VALUES (:name, :password);';
        $sql = $this->connection->prepare($query);
        $sql->bindParam('name', $name,PDO::PARAM_STR);
        $sql->bindParam('password', $password,PDO::PARAM_STR);
        $sql->execute();
    }
    private function ConvertToUser($result) : User
    {
        $user = new User();
        $user->id = $result['id'];
        $user->username = $result['username'];
        $user->password = $result['password'];
        return $user;
    }
}