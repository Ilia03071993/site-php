# site-php
Add file catalog/config.php
```
<?php

define("DBHOST","localhost");
define("DBUSER","root");
define("DBPASS","root");
define("DB","apple");
define("PATH", "http://site-php/catalog/");
define("PERPAGE", 5);
$option_perpage = array(5, 10 , 15);

$connection = @mysqli_connect(DBHOST,DBUSER,DBPASS,DB) or die("Нет соединения с БД");
mysqli_set_charset($connection, "utf8") or die ("Не установлна кодировка соединения");

```
