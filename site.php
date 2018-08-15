<?php

use Hcode\Model\Product;
use \Hcode\Page;
use \Hcode\Model\Category;
use \Hcode\Model\Cart;

$app->get('/', function() {
    $page = new Page();

    $products = Product::listAll();

    $page->setTpl("index", ['products' => Product::checklist($products)]);

});

$app->get("/categories/:idcategory", function ($idcategory) {

    $page = (isset($_GET['page'])) ? (int) $_GET['page'] : 1;

    $category = new Category();

    $category->get((int)$idcategory);

    $pagination = $category->getProductsPage($page);

    $pages = [];

    for ($i=1;$i <= $pagination['pages']; $i++) {
        array_push($pages, [
            "link"  =>  '/categories/' . $idcategory . '?page=' . $i,
            "page"  => $i
        ]);
    }

    $page = new Page();

    $page->setTpl("category", array(
        "category"  =>  $category->getValues(),
        "products"  =>  $pagination["data"],
        "pages"     => $pages
    ));
});

$app->get("/products/:desurl", function ($desurl) {
   $product = new Product();

   $product->getFromUrl($desurl);

   $page = new Page();

   $page->setTpl("product-detail", array(
       "product" => $product->getValues(),
       "categories" => $product->getCategories()
   ));
});

$app->get("/cart", function () {
    $cart = Cart::getFromSession();


    $page = new Page();

    $page->setTpl('cart');
});


