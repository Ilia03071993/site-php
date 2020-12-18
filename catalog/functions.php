 <?php
  
/**
* Распечатка массива
**/
function print_arr($array){
    echo "<pre>" . print_r($array, true) . "</pre>";
}

/**
 * Получение массива категорий
 **/
function get_cat(){
    global $connection;
    $query = "SELECT * FROM categories";
    $res = mysqli_query($connection, $query); 
    $arr_cat = array(); 
    while($row = mysqli_fetch_assoc($res)){
        $arr_cat[$row['id']] = $row;
    }
    return $arr_cat; 
}
/***
 * Построение дерева
 */
function map_tree($dataset){
    $tree = array();

    foreach ($dataset as $id => &$node){
        if (!$node['parent']){
            $tree[$id] = &$node;
        }else{
            $dataset[$node['parent']]['childs'][$id] = &$node;
        }
    }
    return $tree;
}

/***
 * Дерeво в строку HTML
 **/
function categories_to_string($data){
    $string = null;
    foreach($data as $item){
        $string .= categories_to_template($item);
    }
    return $string;
}

/***
 * Шаблон вывода категорий
**/
function categories_to_template($category){
    ob_start();
    include 'views/category_template.php';
    return ob_get_clean();
}

/**
 * Хлебные крошки
 **/

 function breadcrumbs($array, $id){
     
     if(!$id) return false;

    $count = count($array);
    $breadcrumbs_array = array();
    for($i = 0; $i < $count; $i++){
        if(isset($array[$id])){
            $breadcrumbs_array[$array[$id]['id']] = $array[$id]['title'];
            $id = $array[$id]['parent'];
        }else break;
    }
    return array_reverse($breadcrumbs_array, true);
   
 } 

 /**
  * Получить ID дочерних категорий
  **/
  function cats_id($array, $id){
        if(!$id) return false;
        $data = null;
        foreach($array as $item){
            if($item['parent'] == $id){
                $data .= $item['id'] . ",";
                $data .= cats_id($array, $item['id']);
            }
        }
        return $data;
  }

  /**
   * Получение товаров
   **/
  function get_products($ids,$start_pos,$perpage){
      global $connection;
      if($ids){
          $query = "SELECT * FROM products WHERE  parent IN($ids) ORDER BY title LIMIT $start_pos, $perpage";
        }else{ 
            $query = "SELECT * FROM products ORDER BY title";

        }
        $res = mysqli_query($connection, $query);
        $products = array();
        while($row = mysqli_fetch_assoc($res)){
            $products[] = $row;
        }
        return $products;
  }

/**
 * Получение отдельного товара
**/
function get_one_product($product_alias){
    global $connection;
    $product_alias = mysqli_real_escape_string($connection, $product_alias);
    $query = "SELECT * FROM products WHERE alias = '$product_alias'";
    exit($query);
    $res = mysqli_query($connection, $query);
    return mysqli_fetch_assoc($res);
}

/**
* Кол-во товаров 
**/
function count_goods($ids){
    global $connection;
    if( !$ids ){
        $query = "SELECT COUNT(*) FROM products";
    }else{
        $query = "SELECT COUNT(*) FROM products WHERE parent IN($ids)";
    }
    $res = mysqli_query($connection , $query);
    $count_goods = mysqli_fetch_row($res);
    return $count_goods[0];
}

/**
 * Постраничная навигация
**/

function pagination($page, $count_pages, $modrew = true){
    // << < 3 4 5 6 7 > >>
    $back = null; // ссылка НАЗАД
    $forward = null; // ссылка ВПЕРЕД
    $startpage = null; // ссылка В НАЧАЛО 
    $endpage = null; // ссылка В КОНЕЦ
    $page2left = null; // вторая страница слева
    $page1left = null; // вторая страница справа
    $page2right = null; // первая страница слева
    $page1right = null; // превая страница справа

    $uri = "?";
    if(!$modrew){
    //елси есть параметры в запросе
        if( $_SERVER['QUERY_STRING'] ){
             foreach($_GET as $key => $value){
            
                if ($key != 'page') $uri .= "{$key}=$value&amp;";
            }
        }
            
        }else{
            $url = $_SERVER['REQUEST_URI'];
            $url = explode("?", $url);
            if(isset($url[1]) && $url[1] !=''){
                $params = explode("&",$url[1]);
                foreach($params as $param){
                    if(!preg_match("#page=#",$param)) $uri .= "{$param}&amp;";
                }
            }
        }
        
    
    if($page > 1){
        $back = "<a class='nav-link' href='{$uri}page=" .($page-1) . "'>&lt;</a>";
    }
    if($page < $count_pages){
        $forward = "<a class='nav-link' href='{$uri}page=" .($page+1) . "'>&gt;</a>";
    }
    if($page > 3){
        $startpage = "<a class='nav-link' href='{$uri}page=1'>&laquo</a>";
    }
    if($page < ($count_pages - 2)){
        $endpage = "<a class='nav-link' href='{$uri}page={$count_pages}'>&raquo</a>";
    }
    if($page - 2 > 0){
        $page2left = "<a class='nav-link' href='{$uri}page=" .($page - 2). "'>".($page - 2)."</a>";
    }
    if($page - 1 > 0){
        $page1left = "<a class='nav-link' href='{$uri}page=" .($page - 1). "'>".($page - 1)."</a>";
    }
    if($page + 1 <= $count_pages ){
        $page1right = "<a class='nav-link' href='{$uri}page=" .($page + 1). "'>".($page + 1)."</a>";
    }
     if($page + 2 <= $count_pages ){
        $page2right = "<a class='nav-link' href='{$uri}page=" .($page + 2). "'>".($page + 2)."</a>";
    }

    return $startpage . $back . $page2left . $page1left . '<a class ="nav-active">' . $page . '</a>' . $page1right . $page2right . $forward . $endpage;
}