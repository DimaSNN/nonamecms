<?php
/** 
 * Ядро СМС
 * Все значимые функции вынесены в него
**/

class ITCMSNN {
    protected $bufer=array();


   
    /**
     * считать файл в строку
    **/
    function Rfile($file)
    {
        if (!is_readable($file)){ die("Rfile Невозможно считать файл \"$file\", проверьте права на чтение."); }
        if (is_file($file))
        {
            if (filesize($file) == 0){ return ""; }
            $fp = fopen("$file", "r");
            $contents = fread($fp, filesize($file));
            fclose($fp);
            return $contents;
        }else{ 
            die("Rfile Невозможно считать файл \"$file\", его не существует.");
            return false; 
        }
    }



    /**
     * вернет массив данных файла
    **/
    function unserializeU($file,$die_str="<? die(); ?>"){ // строка смерти - запрет пользователям смотреть данные
      if (is_file($file) && is_readable($file)){
    	$data = $this->Rfile($file);
    	$data = str_replace($die_str, "", $data);
    	return unserialize($data);
      }else{
        die("unserializeU Невозможно считать файл \"$file\".");
        return false;
      }
    }


    /**
     * сохранит массив данных в файл
    **/
    function serializeU($file,$mas,$die_str="<? die(); ?>"){
    	if (!is_file($file) or (is_file($file) and is_writable($file))){
        	$fp = fopen($file, "w");
        	if (flock($fp, LOCK_EX)){
        		
        	fwrite ($fp, $die_str);
        	fwrite ($fp, serialize($mas));
        		
        	flock($fp, LOCK_UN);
            return true;
            }else{
                die("serializeU Невозможно считать файл \"$file\".");
                return false;
            }
    	}else{
    	   die("serializeU Невозможно считать файл \"$file\".");
    	   return false;
        }
    }
    
    
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
    /**
     * получить информацию о каталоге
    **/
    function GetInfCat($dir){
        $inf=array();
        if (is_file("$dir/info.txt")){
            $txt=file("$dir/info.txt");
            foreach ($txt as $key=>$val){
                $arr=explode(":",$val,2);
                if (count($arr)==2){
                    if ($arr[0]=="sort"){
                        $inf[$arr[0]]=explode(",",$arr[1]);
                        // подрезка пробелов
                        for ($i=0;$i<count($inf[$arr[0]]); $i++){
                            $inf[$arr[0]][$i]=trim($inf[$arr[0]][$i]);
                        }
                    }else{
                        $inf[$arr[0]]=$arr[1];
                    }
                }
            }
        }
        return $inf;
    }


    /**
     * получить ссылку по относительному пути
     * $dir относительный путь на который сместить
     * уровень определяется по колическву ".."
     **/
    function GetLinkCat($dir){
        $dir=str_replace("\\","/",$dir);

        $arrDir = explode( "/", $dir);
        $arrUrl = explode( "/", $_SERVER['REQUEST_URI']);
        $cnt = 0; //определяет сколько уровней вложенности передано функции
        $cntPath= "."; //для проверки, чтобы не ушло дальше корня
        for($i=0; $i<count($arrDir); $i++){
            if($arrDir[$i]=="..") {
                if(is_file("$cntPath/content_sys.php")) break; //ограничивает глубину
                $cnt++;
                $cntPath.="/..";
            }elseif($arrDir[$i]=="."){}else{break;}// защита от поднятия выше по середине пути
        }
        $url="";
        $maxIdUrl=count($arrUrl)-2;
        //echo $arrUrl[2];
        for($i=($maxIdUrl-$cnt); $i > 0; --$i){
            $url=$arrUrl[$i]."/".$url;
        }
		for($i=$cnt; $i<count($arrDir); $i++){
            if($arrDir[$i]!="." && $arrDir[$i]!="" && $arrDir[$i]!=" " && $arrDir[$i]!=".." && is_dir("$cntPath/$arrDir[$i]") ) {//лобавляем пути после переходов вверх
                $url = $url . $arrDir[$i]."/";
            }
        }
        $url = "http://".$_SERVER['SERVER_NAME']."/".$url;
        return $url;
    }
    

    function GetInfoCat($dir){
        $inf=array();
        $inf["dir"]=$dir;
        $inf["link"]=$this->GetLinkCat($dir);
        $inf["inf"]=$this->GetInfCat($dir);
        return $inf;
    }
    
    
    
    
    /**
     * Сортирует $items по $listFiles причем отсутствующие в $listFiles элементы добавляются в конец а лишние игнорятся
    **/   
    function SortCatalogs($items, $listFiles){
        $out=array(); 
        foreach ($listFiles as $key=>$val){
            if (in_array($val,$items)){$out[]=$val;}
        }
        foreach ($items as $key=>$val){
            if (!in_array($val,$out)){$out[]=$val;}
        }
        return $out;
    }
 
 
    
    /**
    * получить список и информацию о каталогах смс внутри этой папки
    **/
    function GetSubCat($dir){
        $items=scandir($dir);
        // сортировка
        $info=$this->GetInfoCat($dir);
        if (isset($info["inf"]["sort"]) and is_array($info["inf"]["sort"])){
            $items=$this->SortCatalogs($items,$info["inf"]["sort"]);
        }
        //
        $subcat=array();
        for ($i=0; $i<count($items); $i++){
            $tmp=$items[$i];
            if ($tmp != '..' and $tmp != '.' and $tmp != '' and is_dir("$dir/$tmp") and is_file("$dir/$tmp/info.txt")) {// ТУТ указана пренадлежность к смс
                    $subcat[]=$this->GetInfoCat("$dir/$tmp");
            }
        }
        return $subcat;
    }
    
    

    
    
	/** Конструктор класса **/
    public function __construct($cfg=array()){

        /**
         * Поиск путей до корня смс
         * в результате отработки блока образуется массив со всеми параметрами папок расположенных выше текущего каталога
        **/
        $arr_info=array();
        $GDir_sys="."; $cnt_sys=0;        
        // путь относительно корня сервера
        $patch_kor_svr=substr (realpath ("."), strlen($_SERVER["DOCUMENT_ROOT"]));
        //количество каталогов
        $patch_kor_svr=str_replace("\\","/",$patch_kor_svr);
        $maxlvl=substr_count($patch_kor_svr, "/");
        // ограничение по высоте
        if ($maxlvl>10){$maxlvl=10;}
        // текущая
        $arr_info[]=$this->GetInfoCat($GDir_sys);
        // если не в корне то идем до корня и считываем каждый каталог
        while(!is_file("$GDir_sys/content_sys.php") and $cnt_sys<=$maxlvl){
            $cnt_sys++; 
            $GDir_sys="$GDir_sys/..";
            $arr_info[]=$this->GetInfoCat($GDir_sys);
        }
        // развернем чтобы корневой каталог стал нулывым элементом
        $arr_info=array_reverse($arr_info);
        
        
        // в этом массиве лежит вся информация о каталогах расположенных выше текущего.
        // 0 элемент массива - корневой
        // последний  - текущий
        //$arr_info
        // Вывод каждого элемента массива смотреть в функции GetInfoCat
        $this->bufer["nav"]=$arr_info;
        $this->bufer["GDir_sys"]=$GDir_sys;
        /**
         * Получить главное меню        
        **/
        $this->bufer["nav_gen"]=$this->GetSubCat($GDir_sys);
        

        // TODO Сортировка  главного меню


        /**
         *  Отладочный вывод       
        **/
        /*        
        echo "<otl><pre> otl: конструктор класса \r\n ";
        echo "переданы параметры cfg: ";
        print_r($cfg);
        echo "\r\n";
        echo "bufer: ";
        print_r($this->bufer);
        echo "\r\n";
        echo "</pre></otl>";*/
        
    }
    

    /**
     * получить массив для вывода в шаблоне строки навигации на сайте
    **/
    function GetNavBar(){
        $out=array();
        foreach ($this->bufer["nav"] as $level=>$val){
            $r=array();
            $r["name"]=$val["inf"]["name"];
            $r["link"]=$val["link"];
            $out[]=$r;
        }
        return $out;
    }
    
    /**
     * получить массив для вывода в шаблоне главного меню сайта
    **/
    function GetGenMenu(){
        $out=array();
        foreach ($this->bufer["nav_gen"] as $level=>$val){
            $r=array();
            $r["name"]=$val["inf"]["name"];
            $r["link"]=$val["link"];
            $out[]=$r;
        }
        return $out;
    }
    
    
    /**
     * получить массив для вывода в шаблоне меню текущегно каталога.
    **/
    function GetLocalMenu(){
        $arr=$this->GetSubCat(".");
        $out=array();
        foreach ($arr as $level=>$val){
            $r=array();
            $r["name"]=$val["inf"]["name"];
            $r["link"]=$val["link"];
            $out[]=$r;
        }
        return $out;
    }    
    
    function GetName(){
        $n=count($this->bufer["nav"])-1;
        return $this->bufer["nav"][$n]["inf"]["name"];       
    }
  
  
}
?>