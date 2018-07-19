<?php 
session_start();
require_once("vendor/autoload.php");

use \Slim\Slim;
use \Hcode\Page;
use \Hcode\Model\User;
use \Hcode\PageAdmin;


$app = new Slim();

$app->config('debug', true);

$app->get('/', function() {
    $page = new Page();

    $teste = 'luan';

    $page->setTpl("index", ['nome' => $teste]);

});

$app->get('/admin', function() {
    $page = new PageAdmin();

    User::verifyLogin();

    $page->setTpl("index");

});

$app->get("/admin/login", function () {
    $page = new PageAdmin([
        "header" => false,
        "footer" => false
    ]);

    $page->setTpl("login");
});

$app->post("/admin/login", function () {
    User::login($_POST['login'], $_POST['password']);

    header("location: /admin");
});

$app->get("/admin/logout", function () {
   User::logout();

   header("location: /admin/login");
   exit;
});

$app->run();

 ?>