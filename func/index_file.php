<?php
header("Content-Type: text/html; charset=utf-8");
$mktime=microtime(false);
/**
* Основной файл CMS 
* Этот файл будет подключен из любого места запуска
* 
* 
* Переменные созданные ранее в index.php
* $GDir_sys="."; относительный путь к корню cms
* $cnt_sys=0;    количество каталогов до корня
* $maxlvl        Максимально возможенная вложенность в данной конфигурации смс
**/

/** 
* Подключаем ядро и стартуем его
**/
include_once ("$GDir_sys/func/interface/general.php");
$ITCMSNN= new ITCMSNN();

/** 
* Оформление смс подключается в этом блоке 
* там же происходи вывод контента
**/
$templdir="$GDir_sys/template";
include ("$templdir/shablon.php");



/**
 * ВЫВОД
**/
// файл index_m не требует вывода оформмления
if (is_file("index_m.php")){include("index_m.php");    exit(); } 

if (!isset($_GET["noskin"]) or $_GET["noskin"]!=1){
    echo $cms_top;
}


if (isset($_GET["module"]) and $_GET["module"]!=""){
    include("$GDir_sys/func/mod/ind.php");
}elseif (is_file("index_inc.php")){
    include("index_inc.php");
}elseif (is_file("content.html")){
    echo $Decada->Rfile("content.html");
}elseif (is_file("content.txt")){
    echo $Decada->Rfile("content.txt");
}elseif(0){
    $local_menu=$ITCMSNN->GetLocalMenu();
    $outnav="start local_menu menu ";
    for($i=0; $i<count($local_menu);$i++){
        $outnav.=<<<DATA
        <a href="{$local_menu[$i]["link"]}">{$local_menu[$i]["name"]}</a>
DATA;
    
    if ($i<count($local_menu)-1){
    $outnav.="|";
    }
    }
    $outnav.="stop ";
    
    echo "OK MENU:";
    echo "$outnav";
}
















if (!isset($_GET["noskin"]) or $_GET["noskin"]!=1){
    // оформление низ
    $time=ceil((microtime(false)-$mktime)*10000)/10000;
    echo $cms_bottom;
    echo "<br><br><otl>otl: Время выполнения $time<otl><br><br>";
}



exit();
?>