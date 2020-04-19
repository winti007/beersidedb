<?php

session_start();


ini_set ( "display_errors", E_ALL);
ini_set ( "error_reporting", E_ALL);

if (!(isset($_SESSION["Data"])))
{
    for ($c=1;$c<41;$c++)
    {
    $_SESSION["Data"][]=array($c,"Sor ".$c);
    }
}

echo "ok";

if (isset($_GET["Kozep"]))
{
    Rendez();
    echo Listaz();
    exit;
}else
{
    echo Oldal();
}


function Elemet($Azon)
{
    $Vissza=false;
    foreach ($_SESSION["Data"]as $item)
    {
        if ($Azon=="div".$item[0])$Vissza=$item;
    }
    return $Vissza;

}

function Rendez()
{

    if (!(isset($_GET["Honnan"])))return ""; 

    $Honnan=$_GET["Honnan"];
    $Hova=$_GET["Hova"];

    $Be=array();
    foreach ($_SESSION["Data"]as $item)
    {
        $Nv="div".$item[0];
        if ("$Nv"=="$Hova")
        {
            $Be[]=$item;

            $Mit=Elemet($Honnan);
            if (is_array($Mit))$Be[]=$Mit;


        }else
        if ("$Nv"!="$Honnan")
        {
            $Be[]=$item;
        }       
    }
    $_SESSION["Data"]=$Be;
        
}

function Oldal()
{
    return "<!DOCTYPE HTML>
<html>
<head>
<style>

.dv {
    width: 350px;
    height: 25px;
    padding: 10px;
    border: 1px solid #aaaaaa;
}

.koz {
    width: 350px;
    height: 20px;
    padding: 10px;
    border: 1px solid #e8e8e8;
}
</style>
<script>
function allowDrop(ev) {
    ev.preventDefault();
}

function drag(ev) {
    ev.dataTransfer.setData('text', ev.target.id);
}

function drop(ev) {
    ev.preventDefault();
    var honnan = ev.dataTransfer.getData('text');
    ev.target.appendChild(document.getElementById(honnan));

  //  alert(honnan);
    hova=ev.target.id;
//    alert(hova);
    link='/pr.php?Kozep=1&Honnan='+honnan+'&Hova='+hova;
    link=link+'&t='+ Math.random()
    
    Ujratolt(link);
}

function Ujratolt(link) {
    var xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == XMLHttpRequest.DONE) {   // XMLHttpRequest.DONE == 4
           if (xmlhttp.status == 200) {
               document.getElementById('lista').innerHTML = xmlhttp.responseText;
           }
           else if (xmlhttp.status == 400) {
              alert('There was an error 400');
           }
           else {
               alert('something else other than 200 was returned, status:'+xmlhttp.status);
           }
        }
    };

    xmlhttp.open('GET', link, true);
    xmlhttp.send();
}

</script>
</head>
<body>

<span id='lista'>
".Listaz()."
</span>
</body>
</html>
";
}

function Listaz()
{
    $Kiir="<div ondrop='drop(event)'  ondragover='allowDrop(event)' style=\"height: 25px\">ff</div>";
    foreach ($_SESSION["Data"]as $item)
    {
        $Kiir.="<div id='div".$item[0]."' class='dv' draggable='true' ondragstart='drag(event)'    >".$item[1]." </div>
        <br>
";
        $Kiir.="<div id='idediv".$item[0]."' ondrop='drop(event)' ondragover='allowDrop(event)' style=\"height: 25px\">ff</div>";
    }
    return $Kiir;    
    return "
<div id='div1' ondrop='drop(event)' ondragover='allowDrop(event)'  class='dv' draggable='true' ondragstart='drag(event)'  >sor 1 </div>
<br>
<div id='div2' class='dv' ondrop='drop(event)' ondragover='allowDrop(event)'  draggable='true' ondragstart='drag(event)' >sor 2</div>
<br>
<div id='div3' class='dv' ondrop='drop(event)' ondragover='allowDrop(event)'  draggable='true' ondragstart='drag(event)'  >sor 3 </div>
<br>
<div id='div4' class='dv' ondrop='drop(event)' ondragover='allowDrop(event)'  draggable='true' ondragstart='drag(event)' >sor 4</div>
";
}


?>