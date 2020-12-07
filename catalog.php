<?php
include 'config.php';
include 'functions.php';

$categories = get_cat();
$categories_tree = map_tree($categories);
$categories_menu = categories_to_string($categories_tree);



    $id = (int)$_GET['category'];
    //хлебные крошки
    // return true (array not empty) || return false

    $breadcrumbs_array = breadcrumbs($categories, $id);
        
    if($breadcrumbs_array){
        $breadcrumbs = "<a href='/catalog/'>Главная</a> / ";
        foreach($breadcrumbs_array as $id => $title){
            $breadcrumbs .= "<a href='?category={$id}'>{$title}</a> / ";
        }
        $breadcrumbs = rtrim($breadcrumbs, " / ");
        $breadcrumbs = preg_replace("#(.+)?<a.+>(.+)</a>$#", "$1$2",$breadcrumbs);
    }else{
        $breadcrumbs = "<a href='/catalog/'>Главная</a> / Каталог";

    } 

    // ID дочерних категорий
    $ids = cats_id($categories, $id);
    $ids = !$ids ? $id : rtrim($ids, ",");
    /*===========Пагинация===========*/


    // кол-во товаров на страницу 
    $perpage = 5;

    // общее кол-во товаров
    $count_goods = count_goods($ids);

    //необходимое кол-во страниц
    $count_pages = ceil($count_goods / $perpage);

    //миниум 1 страница
    if (!$count_pages) $count_pages = 1;
    
    //получение текущей страницы из массива GET
    if( isset ($_GET['page'])){
        $page = (int)$_GET['page'];
        if($page < 1) $page = 1;
    }else{
        $page = 1;
    }

    //eсли запрошенная страница больше максимума
    if($page > $count_pages) $page = $count_pages;
    
    //начальная позиция для запроса
    $start_pos = ($page - 1) * $perpage;


    /*===========Пагинация===========*/
    $products = get_products($ids);
  

 
