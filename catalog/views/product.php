<?php defined("CATALOG") or die("Access denied"); ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Каталог</title>
    <link rel="stylesheet" href="<?=PATH?>views/style.css">
</head>
<body>
<a href="/catalog/">Главная</a>
<div class="wrapper">
    <div class="sidebar">
        <ul class="category">
            <?php echo $categories_menu ?>
        </ul>
    </div>
     <div class="content">
     <p><?=$breadcrumbs;?></p>
     <br>
     <hr>
<?php
    print_arr($get_one_product);
?>   
    </div>
</div>
<script src="<?=PATH?>views/js/jquery-1.9.0.min.js"></script>
<script src="<?=PATH?>views/js/jquery.accordion.js"></script>
<script src="<?=PATH?>views/js/jquery.cookie.js"></script>
<script>
  $(document).ready(function(){
    $(".category").dcAccordion();
  });
</script>
</body>


</html>
