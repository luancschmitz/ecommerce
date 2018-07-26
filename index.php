<?php 
session_start();
require_once("vendor/autoload.php");

use \Slim\Slim;
use \Hcode\Page;
use \Hcode\Model\User;
use \Hcode\Model\Category;
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

$app->get("/admin/users", function () {

    User::verifyLogin();

    $users = User::listAll();

    $page = new PageAdmin();

    $page->setTpl("users", array(
        "users" => $users
    ));
});

$app->get("/admin/users/create", function () {

    User::verifyLogin();

    $page = new PageAdmin();

    $page->setTpl("users-create");
});

$app->get("/admin/users/:iduser/delete", function ($iduser) {
    User::verifyLogin();

    $user = new User();

    $user->get((int)$iduser);

    $user->delete();

    header("location: /admin/users");
    exit;
});

$app->get("/admin/users/:iduser", function ($iduser) {

    User::verifyLogin();

    $user = new User();
    $user->get((int)$iduser);

    $page = new PageAdmin();

    $page->setTpl("users-update", array(
        "user" => $user->getValues()
    ));
});

$app->post("/admin/users/create", function () {
    User::verifyLogin();

    $user = new User();

    $_POST['inadmin'] = (isset($_POST['inadmin'])) ? 1 : 0;

    $user->setData($_POST);

    $user->save();

    header("/admin/users/");
    exit;

});

$app->post("/admin/users/:iduser", function ($iduser) {
    User::verifyLogin();

    $user = new User();

    $user->get($iduser);

    $user->setData($_POST);

    $user->update();

    header("location: /admin/users");
    exit;

});

$app->get("/admin/forgot", function () {
    $page = new PageAdmin([
        "header"        => false,
        "footer"        => false
    ]);

    $page->setTpl("forgot");
});

$app->post("/admin/forgot", function () {
   $user = User::getForgot($_POST["email"]);

   header("location: /admin/forgot/sent");
   exit;
});

$app->get("/admin/forgot/sent", function () {
    $page = new PageAdmin([
        "header"        => false,
        "footer"        => false
    ]);

    $page->setTpl("forgot-sent");
});

$app->get("/admin/forgot/reset", function () {
    $user = User::validForgotDecrypt($_GET['code']);

    $page = new PageAdmin([
        "header"        => false,
        "footer"        => false
    ]);

    $page->setTpl("forgot-reset", array(
        "name" => $user["desperson"],
        "code" => $_GET["code"]
    ));
});

$app->post("/admin/forgot/reset", function () {
    $forgot = User::validForgotDecrypt($_POST['code']);

    User::setForgotUsed($forgot['idrecovery']);

    $user = new User();

    $user->get($forgot['iduser']);


    $password = password_hash($_POST['password'], PASSWORD_BCRYPT, [
        'cost' => 12
    ]);

    $user->setPassword($password);

    $page = new PageAdmin([
        "header"        => false,
        "footer"        => false
    ]);

    $page->setTpl("forgot-reset-success");

});

$app->get("/admin/categories", function () {
    User::verifyLogin();

    $page = new PageAdmin();

    $categories = Category::listAll();

    $page->setTpl("categories", array(
        "categories" => $categories
    ));
});

$app->get("/admin/categories/create", function () {
    User::verifyLogin();

    $page = new PageAdmin();

    $page->setTpl("categories-create");
});

$app->post("/admin/categories/create", function () {
    User::verifyLogin();

    $category = new Category();

    $category->setData($_POST);

    $category->save();

    header("location: /admin/categories");
    exit;
});

$app->get("/admin/categories/:idcategory/delete", function ($idcategory) {
    User::verifyLogin();

    $category = new Category();

    $category->get((int)$idcategory);

    $category->delete();

    header("location: /admin/categories");
    exit;
});

$app->get("/admin/categories/:idcategory/", function ($idcategory) {
    User::verifyLogin();

    $category = new Category();

    $category->get((int)$idcategory);

    $page = new PageAdmin();

    $page->setTpl("categories-update", array(
        "category" => $category->getValues()
    ));

    exit;
});

$app->post("/admin/categories/:idcategory", function ($idcategory) {
    User::verifyLogin();

    $category = new Category();

    $category->get((int)$idcategory);

    $category->setData($_POST);

    $category->save();

    header("location: /admin/categories");
    exit;
});

$app->get("/categories/:idcategory", function ($idcategory) {
   $category = new Category();

   $category->get((int)$idcategory);

   $page = new Page();

   $page->setTpl("category", array(
       "category" => $category->getValues(),
       "products" => array()
   ));
});

$app->run();

 ?>