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

    function cheek_cat(){
        var capt=document.getElementById("inp_cat");
        var val=capt.value;
        document.getElementById("s1info").innerText="";
        if (val==""){
            capt.style.border = "1px solid red";
            return false;
        }else{
            capt.style.border = "1px solid green";
            return true;
        }
    }

    function cheek_name(){
        var capt=document.getElementById("inp_name");
        var val=capt.value;
        document.getElementById("s1info").innerText="";
        if (val==""){
            capt.style.border = "1px solid red";
            return false;
        }else{
            capt.style.border = "1px solid green";
            return true;
        }
    }




    function cancel(){
        location="?";
    }

    function rec_cat(){
        document.getElementById("inp_cat").value=document.getElementById("reccat").innerText;
        cheek_cat();
    }


    function step2(){
        var inp_cat=document.getElementById("inp_cat");
        var inp_name=document.getElementById("inp_name");
        var res=cheek_cat();
        var res1=cheek_name();
        if (!res || !res1){
            document.getElementById("s1info").innerText="Некорректно заполнены некоторые поля";
            return ;
        }
        document.getElementById("dprew").innerText=inp_cat.value;
        document.getElementById("nprew").innerText=inp_name.value;

        document.getElementById("step1").style.display = 'none';
        document.getElementById("step2").style.display = 'block';


    }

    function back_s1(){
        document.getElementById("step2").style.display = 'none';
        document.getElementById("step1").style.display = 'block';
    }

    function s3_create(){
        var params = 'cat=' + encodeURIComponent(document.getElementById("inp_cat").value)+  '&name=' + encodeURIComponent(document.getElementById("inp_name").value);
        var httpObject;
        httpObject = getHTTPObject();
        if (httpObject != null) {
            httpObject.open("post", "?module=newcat&noskin=1", false);
            httpObject.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
            httpObject.send(params);
        }

        string=httpObject.responseText;
        if (string!="OK"){
            alert("чтото пошло не так!\\r\\n"+string);
        }else{
            document.getElementById("step2").style.display = 'none';
            document.getElementById("step3").style.display = 'block';
        }


    }

    function retok(){
        location=document.getElementById("inp_cat").value;
    }
    function retedit(){
        location=document.getElementById("inp_cat").value + "/?module=editarticle";
    }


</script>
<div id="cnt" align="center">
    <br>
Создание нового каталога

<div id="step1">
        <div id="s1info"></div>
        <table>
            <tr>
                <td>Директория <br><em><font size="-1">(латиница без пробелов)</font></em></td>
                <td><input  id="inp_cat" type="text" value="" name="cat" onfocusout="cheek_cat();" />

                    рекомендуемая директория <a href="#" id="reccat" onclick="rec_cat();">01</a>
                </td>
            </tr>
            <tr>
                <td>Имя раздела</td>
                <td><input id="inp_name" type="text" value="" name="name" onfocusout="cheek_name();"  /></td>
            </tr>
            <tr>
                <td colspan=2 align="center">
                    <a href="#" onclick="cancel();">Отмена</a>
                    <a href="#" onclick="step2();">Далее</a></td>
            </tr>
        </table>
    </div>





    <div id="step2" style="display:none;">
        <table>
            <tr>
                <td colspan=2>
    Подтвердите првильность данных</td>
            </tr>

            <tr>
                <td>Директория (латиница без пробелов)</td>
                <td id="dprew">CAT</td>
            </tr>
            <tr>
                <td>Имя раздела</td>
                <td id="nprew">NAME</td>
            </tr>
            <tr>
                <td colspan=2 align="center">
                    <a href="#" onclick="back_s1();">Назад</a>
                    <a href="#" onclick="s3_create();">Создать</a></td>
            </tr>
        </table>
    </div>




    <div id="step3" style="display:none;">
        <table>
            <tr>
                <td colspan=2>
    Успешно создано<br>
                    <a href="#" onclick="retok();">Перейти в созданный каталог</a><br>
                    <a href="#" onclick="cancel();">Остаться в текущем каталоге</a><br>

                    <a href="#" onclick="retedit();">Перейти к добаавлению материалов</a><br>
                </td>
            </tr>
        </table>
    </div>



</div>