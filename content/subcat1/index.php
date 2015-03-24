<?php
$GDir_sys="."; $cnt_sys=0;

// путь относительно корня сервера
$patch_kor_svr=substr (realpath ("."), strlen($_SERVER["DOCUMENT_ROOT"]));
//количество каталогов
$patch_kor_svr=str_replace("\\","/",$patch_kor_svr);
$maxlvl=substr_count($patch_kor_svr, "/");
unset($patch_kor_svr);
// поиск корня
while(!is_file("$GDir_sys/content_sys.php") and $cnt_sys<=$maxlvl){
    $cnt_sys++; $GDir_sys="$GDir_sys/..";
}
if (!is_file("$GDir_sys/func/index_file.php")){exit("<hr>CMS повреждена.<br> Отсутствует файл \"func/index_file.php\"<hr>");}
if ($cnt_sys>$maxlvl){echo "Error 777";}
include ("$GDir_sys/func/index_file.php");
?>