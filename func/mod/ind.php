<?php
/**
 * Этот файл подключается в случае если передан гет параметр module и он не пустой
 * в шаблоне if (isset($_GET["module"]) and $_GET["module"]!=""){include("$GDir_sys/func/mod/ind.php");
 * **/
 
//теперь выбираем конкретный модуль 
if (!isset($_GET["module"]) or $_GET["module"]==""){die("Неправильный вызов");}
$modname=$_GET["module"];

$modname=str_replace("\\","",$modname);
$modname=str_replace("/","",$modname);

if (is_dir("$GDir_sys/func/mod/$modname")){
    if (is_file("$GDir_sys/func/mod/$modname/ind.php")){
        include("$GDir_sys/func/mod/$modname/ind.php");
    }else{
        echo "Модуль $modname поврежден";        
    }
}else{
    echo "Модуль $modname не установлен";
}


?>