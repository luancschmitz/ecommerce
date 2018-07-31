<?php

use Hcode\Model\Product;
use \Hcode\Page;
use \Hcode\Model\Category;

$app->get('/', function() {
    $page = new Page();

    $products = Product::listAll();

    $page->setTpl("index", ['products' => Product::checklist($products)]);

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

