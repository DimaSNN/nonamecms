<?php

/**
 * Модуль создания статьи либо каталога
 * При любом запросе из модуля указывается GET параметр ?module=newcat
**/
// При любом запросе из модуля указывается GET параметр ?module=newcat
// для аякс запросов ?module=newcat&noskin=1 тогда офомление подключено не будет

/**
 * требования к переменным

//создание Каталога 
// имя папки латиница. , малый регистр без пробелов. недопускаются никакие символы кроме латиницы, цыфр
$dirname="";
// заголовок статьи. Руссие буквы, цыфры, знаки препинания
$catname="";
// дата создания.  цыфры. спец требования к формату
$date="";

// если в нем еще и статья то

// буквы, цыфры, знаки препинания
$autor="";
// 
$contentTXT="";
**/


/**
 * вывод аякс ответов
**/
if (isset($_GET["noskin"])){
        echo "OK";exit();
    print_r($_POST);
    echo "ajax обработчика нету out"; exit();
    
    
    echo "OK";
}


/**
 * вывод аякс формы
**/

echo $ITCMSNN-> Rfile("$GDir_sys/func/mod/newcat/newcat.html");



?>