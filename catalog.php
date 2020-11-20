<?php
$categories = get_cat();
$categories_tree = map_tree($categories);
$categories_menu = categories_to_string($categories_tree);


if(isset($_GET['category'])){
    $id = (int)$_GET['category'];
    //хлебные крошки
    // return true (array not empty) || return false
    $breadcrumbs_array = breadcrumbs($categories, $id);
}   