<?php

namespace App\Controller;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
class MainController
{
    private Environment $response;
    public function __construct(Environment $response){
        $this->response=$response;
    }
    public function execute()
    {
        echo $this->response->render('main.html.twig');
    }
    public function login($username){
        echo $this->response->render('main.html.twig',['logged'=>true,'username'=>$username]);
    }
    public function logout(){
        echo $this->response->render('main.html.twig',['logged'=>false]);
    }
    public function getAll($data){
        echo $this->response->render('all.html.twig',['data'=>$data]);

    }
    public function getById(){
        echo $this->response->render('getById.html.twig');
    }
    public function getByPhoneNumber(){
        echo $this->response->render('getByPhoneNumber.html.twig');
    }
    public function delete(){
        echo $this->response->render('delete.html.twig');
    }
    public function add(){
        echo $this->response->render('add.html.twig');
    }
}