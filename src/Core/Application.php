<?php

namespace App\Core;
use App\Controller\MainController;
use App\Model\User;
use App\Repository\UserRepository;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Firebase\JWT\Key;
use Firebase\JWT\JWT;
class Application
{
    private  $controller;
    private  $repository;
    private $logged;
    const salt = 'e8811fd0011bc374342ea655cf1a173661f3533a
                    39ba98a7e73d0fd0744a3d703926710b328b19fe
                    e46941b0e083af6c886dddb94985e6679c2bfebd
                    e1e5420a8b923d442809393f6917414bfea737b9
                    72bbd8e87c751abe38c209196cfe6cc78402ba74
                    2e9bde09cbcdb2c6c93705fcfb9b0f32081887e6
                    7dc1c4d5c83849d6c6944b3e000854d582589158
                    574e675fff968fe04d650c274da83ff21a5aabdb
                    d1091f13fc843072afd4c130a67f7057b828b246
                    732b43741065e9300e1a1787510f024e8297ed02';
    public function __construct(){
        $loader = new FilesystemLoader(dirname(__DIR__,1) . '/View/');
        $twig = new Environment($loader);
        $this->controller = new MainController($twig);
        $this->repository = new UserRepository();
    }

    public function run()
    {
        if (isset($_POST['logout'])) {
            setcookie('token', '', 0);
            $this->controller->logout();
        } else {
            if (isset($_COOKIE['token'])) {
                $checkToken = $this->DecodeToken($_COOKIE['token']);
                $id = $checkToken['id'];
                $user = $this->repository->getById($id);
                if ($user != null) {
                    $this->controller->login($user->username);
                }
            } else {
                if ($_SERVER['REQUEST_URI']!='/login')
                $this->controller->logout();
            }
            if (isset($_POST['register'])) {
                if (isset($_POST['regName']) && isset($_POST['regPassword'])) {
                    $hash = sha1($_POST['regPassword'] . self::salt);
                    $this->repository->add($_POST['regName'], $hash);
                }
            }
            if (isset($_POST['enter'])) {
                if (isset($_POST['loginName']) && isset($_POST['loginPassword'])) {
                    $hash = sha1($_POST['loginPassword'] . self::salt);

                    foreach ($this->repository->getAll() as $user) {
                        if ($user->username == $_POST['loginName'] && $user->password == $hash) {
                            $token = $this->EncodeToken($user);
                            setcookie('token', $token, time() + 36000);
                            $this->controller->login($user->username);
                        }
                    }
                }
            }
        }
    }
    public function EncodeToken($user) : string {
        $data = [
            'id' => $user->id,
            'username' => $user->username
        ];
        return JWT::encode($data, self::salt, 'HS256');
    }

    public function DecodeToken($token) : array {
        return (array)JWT::decode($token, new Key(self::salt, 'HS256'));
    }

}