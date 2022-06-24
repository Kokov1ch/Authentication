<?php

namespace App\Repository;
use App\Mapper\UserMapper;
class UserRepository
{
    private $users;
    private UserMapper $mapper;

    public function __construct()
    {
        $this->mapper=new UserMapper();
        $this->users=$this->mapper->getAll();
    }
    public function getAll() :array
    {
        return $this->users;
    }
    public function getById($id):array
    {
        foreach ($this->users as $it){
            if ($it->id == $id) return array ($it);
        }
        return array();
    }
    public function getByUsername($username) :array
    {
        foreach ($this->users as $it){
            if ($it['phoneNumber'] == $username) return array ($it);
        }
        return array();
    }
    public function deleteById($id){
        $this->mapper->deleteById($id);
        $this->users = $this->mapper->getAll();
    }
    public function add($name, $password){
        $this->mapper->add($name, $password);
        $this->users = $this->mapper->getAll();
    }
}