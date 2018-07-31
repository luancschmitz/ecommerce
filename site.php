<?php

use \Hcode\Page;
use \Hcode\Model\Category;

$app->get('/', function() {
    $page = new Page();

    $teste = 'luan';

    $page->setTpl("index", ['nome' => $teste]);

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