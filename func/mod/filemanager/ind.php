<?php

/**
 * Модуль файлового менеджера.
 * При любом запросе из модуля указывается GET параметр ?module=
**/
// При любом запросе из модуля указывается GET параметр ?module=newcat
// для аякс запросов ?module=newcat&noskin=1 тогда офомление подключено не будет
//print_r($_GET);


//echo $GDir_sys." *";

if (isset($_POST["patch"])){
    $dir=$GDir_sys;
    if (!isset($_POST["patch"]) or !is_array($_POST["patch"])){die(" неверный patch");}
    foreach ($_POST["patch"] as $key=>$val){
        $val=str_replace(array("\"","\\","/", "."),"",$val);
        if ($val!=""){
            $dir.="/$val";
        }
    }
    
    
    if (!is_dir("$dir")){exit("ERROR: Папки не существует!");}
    $items=scandir($dir);
    $dir1=str_replace(array("../", "./"),"",$dir);
    echo "$dir1 \r\n";
    foreach($items as $num => $val){
        if (is_dir("$dir/$val")){
           $d="d";
        }else{
            $d="-";
        }
        $fn= "$val";
        $dt1=filectime ("$dir/$val");
        $dt2=filemtime ("$dir/$val");
        $rule=fileperms("$dir/$val");
        $rule = "{$d}rw-r--r--";
        echo "$fn $dt1 $dt2 $rule \r\n";
        
    }
    exit();
}





PRINT_R($_POST);

echo <<<DATA


<script type="text/javascript">



// Get the HTTP Object
function getHTTPObject(){
   if (window.ActiveXObject) return new ActiveXObject("Microsoft.XMLHTTP");
   else if (window.XMLHttpRequest) return new XMLHttpRequest();
   else {
      alert("Âàø áðàóçåð îêîí÷àòåëüíî áåñïîëåçåí è íå ïîääåðæèâàåò AJAX.");
      return null;
   }
}  






function GetCat(dir){
    var a = dir.split("/");
    var index;
    var params = 'I=0'
    for (index = 0; index < a.length; ++index) {
        params =params+ '&patch[]=' + encodeURIComponent(a[index]);
    }  

   var httpObject;
   httpObject = getHTTPObject();
   if (httpObject != null) {
      httpObject.open("post", "?module=filemanager&ajax&noskin=1", false);
      httpObject.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
      httpObject.send(params);
   }  
  
  string=httpObject.responseText;
return string;
} 


   
   
  alert(GetCat("func"));
   
   

</script>

DATA;





?>