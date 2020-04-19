<?php
define ("ALKONYV","templ");

if (!(isset($_POST["Formbol"])))
{
//    ".ALKONYV."/css/font-awesome.min.css
    echo"<head>
    <style>
body
{
    font-size: 18px;
    font-family: Arial;    
}
</style>
<script>
function Fajlallit()
{

    if (document.Allitform.Tipus[0].checked)
    {
        document.getElementById('JS_FILE').style.display='inline';
        document.getElementById('CSS_FILE').style.display='none';
    }else
    {
        document.getElementById('JS_FILE').style.display='none';
        document.getElementById('CSS_FILE').style.display='inline';
    }
}
</script>
    </head>
    <body>    
    <form method='post' action='?' name='Allitform' id='Allitform' >
    <input type='hidden' name='Formbol' id='Formbol' value='1' >
    <table width='600' align=center>
    <tr>
     <td>
    <input type='radio'  name='Tipus' id='Tipus' value='0' checked onclick=\"Fajlallit()\"> Js fájlok &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type='radio' name='Tipus' id='Tipus' value='1' onclick=\"Fajlallit()\"> css fájlok <br><br> <br>
<textarea name='CSS_FILE' id='CSS_FILE' style=\"width: 400px; height: 280px\">
".ALKONYV."/css/bootstrap.css 
".ALKONYV."/css/slicknav.min.css 
".ALKONYV."/css/slick.css 
".ALKONYV."/css/slick-theme.css 
".ALKONYV."/css/font-awesome.min.css 
".ALKONYV."/css/listak_kosar_rendeles.css 
".ALKONYV."/css/style2018.css 
".ALKONYV."/css/media2018.css
templ/js/ui/jquery-ui.min.css
templ/js/fancybox/jquery.fancybox.min.css

</textarea>




<textarea name='JS_FILE' id='JS_FILE' style=\"width: 400px; height: 280px\">
templ/js/jquery-1.11.3.min.js
templ/js/bootstrap.js
templ/js/jquery.slicknav.min.js
templ/js/slick.min.js
templ/js/ysolutions.js
templ/js/ui/jquery-ui.min.js
templ/js/fancybox/jquery.fancybox.min.js

</textarea>

 
    <br>
    <br><br> <br>

    <br>
    <input type='submit' value='Fájlok összemásolása'>
    </td>
    </tr>

    </table>    
    
    </form>
    <script>
    Fajlallit();
    </script>
    ";
  /*      <input type='radio'  name='Mobil' id='Mobil' value='0' checked >  mobil (tablet) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
    <input type='radio' name='Mobil' id='Mobil' value='1'> tablet (notmobile, tablet)  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type='radio' name='Mobil' id='Mobil' value='2'> desktop (desktop) 
*/
}else
{
    $Tipus=$_POST["Tipus"];    
//    $Mobil=$_POST["Mobil"];
    $Mobil=2;   
    
    if ("$Tipus"=="0")$Fajlok=$_POST["JS_FILE"];
    if ("$Tipus"=="1")$Fajlok=$_POST["CSS_FILE"];
    
    $Kiir="";
    
    $Fajlok=explode("\n",$Fajlok);
    
    
    foreach ($Fajlok as $egyfajl)
    {
        $egyfajl=str_replace("\n","",$egyfajl);
        $egyfajl=str_replace("\r","",$egyfajl);
        $egyfajl=trim($egyfajl);
        
        
        
        $Eredfajl=$egyfajl;

       
//        $egyfajl=Fajlegeszit($egyfajl,$Mobil,$Tipus);


/*        if (!(is_file($egyfajl)))
        {
            if ($Eredfajl!="")
            {
                copy($Eredfajl,$egyfajl);
            }
        }*/
        if ($egyfajl!="")
        {
            if (is_file($egyfajl))
            {
                $Kiir.=Fajlbe($egyfajl);
            
            }
        }
    }
    if ("$Tipus"=="0")
    {
        $mit="js";        
    }
    if ("$Tipus"=="1")$mit="css";


    if ("$Mobil"=="0")
    {
                $Kiir.=Fajlbe(ALKONYV."/".$mit."/tablet.".$mit);
       
    }
    if ("$Mobil"=="1")
    {

                $Kiir.=Fajlbe(ALKONYV."/".$mit."/notmobile.".$mit);


                $Kiir.=Fajlbe(ALKONYV."/".$mit."/tablet.".$mit,"r");
        
    }
    
    if ("$Mobil"=="2")
    {
                $Kiir.=Fajlbe(ALKONYV."/".$mit."/notmobile.".$mit);

                $Kiir.=Fajlbe(ALKONYV."/".$mit."/desktop.".$mit);
        
    }
     
    
    
    echo $Kiir;
        
}

function Fajlbe($egyfajl)
{
                $f=fopen($egyfajl,"r");
                $Kiir=fread($f,filesize($egyfajl))."
";
                fclose($f);
    return $Kiir;                
    
}

function Fajlegeszit($fajlnev,$Mobil,$Touch,$Tipus)
{
    $mit="";
    if ("$Tipus"=="0")$mit="/js";
    if ("$Tipus"=="1")$mit="/css";
    
    $Mire=$mit;
    if ("$Mobil"=="0")$Mire.="_nemmob";
    if ("$Mobil"=="1")$Mire.="_mob";
    if ("$Touch"=="0")$Mire.="_nemtch";
    if ("$Touch"=="1")$Mire.="_tch";
    
    
    
    return str_replace($mit,$Mire,$fajlnev);
    
    
}

?>
