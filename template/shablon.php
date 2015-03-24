<?php
/**
 * оформление СМС
**/



$outmain=" main menu start ";
$main_menu=$ITCMSNN->GetGenMenu();
for($i=0; $i<count($main_menu);$i++){
    $outmain.=<<<DATA
    <a href="{$main_menu[$i]["link"]}">{$main_menu[$i]["name"]}</a>
DATA;

if ($i<count($main_menu)-1){
$outmain.=<<<DATA
 | 
DATA;
}
}
$outmain.="stop ";







$nav_menu=$ITCMSNN->GetNavBar();
$outnav="start nav menu ";
for($i=0; $i<count($nav_menu);$i++){
    $outnav.=<<<DATA
    <a href="{$nav_menu[$i]["link"]}">{$nav_menu[$i]["name"]}</a>
DATA;
if ($i<count($nav_menu)-1){$outnav.=" > ";}
}
$outnav.="stop ";

$korlink=$ITCMSNN->GetLinkCat($GDir_sys);
$name=$ITCMSNN->GetName();
// тут части шаблона делятся на 2 половины
$cms_top=<<<DATA
<!doctype html>
<html lang="ru">
	<head>
		<meta charset="utf-8">
		<title>Noname CMS</title>
        <style type="text/css">
        <!--
        	#logo{
        	   text-align:center;
               padding: 10px;
        	}
        	#gmenu{
        	   text-align:center;
               padding: 10px;
        	}
        	#bottom{
        	  border-bottom-style:double; 
              border-bottom-color:red; 
              border-top-style:dotted; 
              border-top-color:green;
        	}
        	#bottoml{
        	   float:left;
               width:20%;
        	}
        	#bottomr{
        	   float:left;
               width:20%;
        	}
        	#bottomc{
        	   float:left;
               width:60%;
        	}
            #namecat{
                 font-size: 150%;
            }
            
        -->
        </style>
	</head>
	<body>
		<div id="top">
            <div id="logo">
                <img src="$korlink/template/default/img/logo.jpg" width="350px." />
            </div>
            <div id="gmenu">$outmain</div>
            <div id="navbar">$outnav</div>
            <div id="namecat">$name</div>
        </div>
        <div id="content"><br><br>
DATA;
$cms_bottom=<<<DATA
<br><br>
</div>
<div id="bottom">
        <div id="bottoml"> </div>
        <div id="bottomc"> </div>
        <div id="bottomr">Noname CMS 2015</div>
</div>
	</body>
</html>
DATA;




?>