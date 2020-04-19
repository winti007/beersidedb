<?php
class CVaz_bovit_sablon extends CSablon  
{
    
    function Lista_Uj($Data)
    {
        $Tartalom="";    
        $Vannyil=false;
        
        $DB=0;
        $Maxtddb=0;
        $Rand=rand(0,13000);
        if (($Data["Lista"]["Eredm"]))
        {
            $DB=$Data["Lista"]["Eredm"];
            $Sorhossz=0;
            if (isset($Data["Nyillink"]))$Vannyil=true;
                    else $Vannyil=false;

            if ($Vannyil)$Tartalom.="
<script>
Drag_honnan=0;

function dragleave()
{
$('.uressor').removeClass('drag');    
}
function allowDrop(ev) 
{
    ev.preventDefault();
    st=ev.target.id;
    $('.uressor').removeClass('drag');
    $('#'+st).addClass('drag');
    
}

function drag(ev) {
    Drag_honnan=ev.target.id;
//    ev.dataTransfer.setData('text', ev.target.id);
}

function drop(ev) {
    ev.preventDefault();
    
    
    hova=ev.target.id;
   
    link='".$Data["Nyillink"]."?Honnan='+Drag_honnan+'&Hova='+hova;
    link=link+'&t='+ Math.random()
    
    Ujratolt(link);
}

function Ujratolt(link) {
    
    
    var xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == XMLHttpRequest.DONE) {   // XMLHttpRequest.DONE == 4
           if (xmlhttp.status == 200) {
               document.getElementById('dvlista$Rand').innerHTML = xmlhttp.responseText;
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
            
            ";       

        }
        
        $Tartalom.="<div id='dvlista$Rand'>".$this->Lista_Uj_seged($Data)."</div>";
        return $Tartalom;
        
    }
    
    function Lista_Uj_seged($Data)
    {
        $Tartalom="";    
        $Vannyil=false;
        
        $DB=0;
        $Maxtddb=0;
        if (($Data["Lista"]["Eredm"]))
        {
            $DB=$Data["Lista"]["Eredm"];
            $Sorhossz=0;
            if (isset($Data["Nyillink"]))$Vannyil=true;
                    else $Vannyil=false;

            if ($Vannyil)$Tartalom.="

            <div class='row adminsor_drag uressor' ondrop='drop(event)' ondragleave='dragleave()' ondragover='allowDrop(event)' id='A1start'  ></div>";       

            foreach ($Data["Lista"]["Eredm"] as $egysor)
            {
                $Tartalom.=$this->Lista_seged_uj($egysor,$Vannyil,$Maxtddb);
            }
        }
        if (isset($Data["Lista"]["Pager"]))
        {
            $Tartalom.=$Data["Lista"]["Pager"];
        }
        
       
        if (isset($Data["Menu"]))
        {
            
            foreach ($Data["Menu"] as $egymenu)
            {
                $Confirm="";
                if ((isset($egymenu["Confirm"]))&&($egymenu["Confirm"]=="1"))$Confirm="onclick=\"return confirm('Biztos?');\" ";

                $Tartalom.="<p><a href='".$egymenu["Link"]."' $Confirm>".$egymenu["Nev"]."</a></p>";
            }
        }

        if (isset($Data["Menuutan"]))$Tartalom.=$Data["Menuutan"];
        if (isset($Data["Vissza"]))
        {
            if ($Data["Vissza"]!="")$Tartalom.="<br>".$this->Gombcsinal("Vissza","location.href='".$Data["Vissza"]."';");
        }
        
        
        return $Tartalom;
        
    }
        
    function Lista_seged_uj($Data,$Vannyil,$Maxtddb)
    {
        

         $Nyilas="";
         $Nyilst="";
        if ($Vannyil)
        {
            $Nyilst="draggable='true' ondragstart='drag(event)'";

            $Nyilas.="<span class='ico-drag' id='AB".$Data["Azon"]."' $Nyilst><img class='adminsor_drag_img' id='AA".$Data["Azon"]."' src='/templ_admin/ikonok/drag.png' border='0' width='35' height='35'></span>
            ";
            
        }
        
        $Vissza="<div class='row adminsor_drag'  id='Honnan".$Data["Azon"]."'  >
         ";
         
         
        if (isset($Data["EGYEB"]))
        {
            foreach ($Data["EGYEB"] as $egytet)
            {
            $Vissza.="<div class='col-xs-3 col-sm-2 adminsor_drag_elemgrp'>
            <a class='adminsor_drag_link' href='".$egytet["Link"]."'>".$egytet["Nev"]."</span></a>
            </div>

";
                
            }
        }        
        if (isset($Data["URLAP"]))
        {
            
            $Vissza.="    <div class='col-xs-3 col-sm-6 adminsor_drag_elemgrp'  >
    $Nyilas
    
    <a ondragstart=\"return false\" class='adminsor_drag_link' href='".$Data["URLAP"]["Link"]."'  ><img class='adminsor_drag_img' src='/templ_admin/ikonok/smbmod.gif' border='0' width='25' height='25'>
    <span  class='adminsor_drag_txt'>".$Data["URLAP"]["Nev"]."</span></a>
    </div>

            ";
        }
        if (isset($Data["LISTA"]))
        {
            $Vissza.="<div class='col-xs-3 col-sm-2 adminsor_drag_elemgrp'>
            <a class='adminsor_drag_link' href='".$Data["LISTA"]["Link"]."'><img class='adminsor_drag_img' src='/templ_admin/ikonok/smbmdir.gif' border='0'  width='25' height='25'><span class='adminsor_drag_txt'>".$Data["LISTA"]["Nev"]."</span></a>
            </div>

";
        }
        
        if (isset($Data["TEKINT"]))
        {
            $Ujablak="";
            
            if ((isset($Data["TEKINT"]["TEKINTUJ"]))&&($Data["TEKINT"]["TEKINTUJ"]=="1"))$Ujablak="target='_blank' ";


            $Vissza.="<div class='col-xs-3 col-sm-2 adminsor_drag_elemgrp'>
            <a class='adminsor_drag_link' href='".$Data["TEKINT"]["Link"]."' $Ujablak><img class='adminsor_drag_img' src='/templ_admin/ikonok/smbview.gif' border='0'  width='25' height='25'><span class='adminsor_drag_txt'>".$Data["TEKINT"]["Nev"]."</span></a></div>

            ";
        }
      //  if (isset($Data["VAGOLAP"]))$Vissza.=$this->Lista_seged_uj2($Data["VAGOLAP"],"","",$Egyhossz);
        
        if (isset($Data["TOROL"]))
        {
            
            
            $Vissza.="<div class='col-xs-3 col-sm-2 adminsor_drag_elemgrp'>
<a class='adminsor_drag_link torol' href='".$Data["TOROL"]["Link"]."' onclick=\"return confirm('Biztos?');\"><img class='adminsor_drag_img' src='/templ_admin/ikonok/smbdel.gif' border='0'  width='25' height='25'><span class='adminsor_drag_txt'>".$Data["TOROL"]["Nev"]."</span></a>
</div>
";
        }

        

        $Vissza.="</div>
";
        if ($Vannyil)$Vissza.="<div class='row adminsor_drag uressor' id='Ho".$Data["Azon"]."' ondrop='drop(event)' ondragleave='dragleave()'  ondragout=\"dragleave();\" ondragover='allowDrop(event)'></div>";
        return $Vissza;
    }




    function Ujadmlist()
    {
        return false;
    }
       
       
    
 /**
 * Lista - admin listát generál
 * Data["Lista"] - gyereklista - gyerek visszatérés tömbbe [Eredm][Pager][Ossz]
 * Data["Menu"]  - menu 
 * Data["Vissza"]  - visszalink 
 */    
    function Lista($Data)
    {
        if ($this->Ujadmlist())return $this->Lista_Uj($Data);
        $Tartalom="";    
        $Vannyil=false;
        
        $DB=0;
        $Maxtddb=0;
        if (($Data["Lista"]["Eredm"]))
        {
            $DB=$Data["Lista"]["Eredm"];
            $Tartalom.="<table cellpadding='3' cellspacing='3' width='100%' border='0' class='admin_table'>";
            $Sorhossz=0;
            foreach ($Data["Lista"]["Eredm"] as $egymenu)
            {
                if ((isset($egymenu["Nyilelott"]))||(isset($egymenu["Nyilutan"])))$Vannyil=true;

                $Hossz=$this->Menudb($egymenu);
                if ($Hossz>$Maxtddb)$Maxtddb=$Hossz;
                
            }            

            foreach ($Data["Lista"]["Eredm"] as $egysor)
            {
                $Tartalom.=$this->Lista_seged($egysor,$Vannyil,$Maxtddb);
            }
            $Tartalom.="</table>";
        }
        if (isset($Data["Lista"]["Pager"]))
        {
            $Tartalom.=$Data["Lista"]["Pager"];
        }
        
       
        if (isset($Data["Menu"]))
        {
            
            foreach ($Data["Menu"] as $egymenu)
            {
                $Confirm="";
                if ((isset($egymenu["Confirm"]))&&($egymenu["Confirm"]=="1"))$Confirm="onclick=\"return confirm('Biztos?');\" ";

                $Tartalom.="<p><a href='".$egymenu["Link"]."' $Confirm>".$egymenu["Nev"]."</a></p>";
            }
        }

        if (isset($Data["Menuutan"]))$Tartalom.=$Data["Menuutan"];
        if (isset($Data["Vissza"]))
        {
            if ($Data["Vissza"]!="")$Tartalom.="<br>".$this->Gombcsinal("Vissza","location.href='".$Data["Vissza"]."';");
        }
        
        
        return $Tartalom;
        
    }
    
    function Menudb($Menu)
    {
        $Vissza=0;
//        if ((isset($Menu["Nyilelott"]))||((isset($Menu["Nyilutan"]))))$Vissza++;
        if (isset($Menu["URLAP"]))$Vissza++;
        if (isset($Menu["LISTA"]))$Vissza++;
        if (isset($Menu["TEKINT"]))$Vissza++;
        if (isset($Menu["VAGOLAP"]))$Vissza++;

        if (isset($Menu["TOROL"]))$Vissza++;
         if (isset($Menu["EGYEB"]))$Vissza=$Vissza+count($Menu["EGYEB"]);
         return $Vissza;
            
    }
    
 
 
/**
 * Lista_seged - admin lista egy sorát generálja 
 */   
    function Lista_seged($Data,$Vannyil,$Maxtddb)
    {
        
        $Vissza="<tr>        ";
        if ($Vannyil)
        {
            $Elotte="";
            if (isset($Data["Nyilelott"]))$Elotte="<a href='".$Data["Nyilelott"]."'><img src='/templ_admin/ikonok/fel.gif' border='0' /></a>";
            $Utana="";
            if (isset($Data["Nyilutan"]))$Utana="<a href='".$Data["Nyilutan"]."'><img src='/templ_admin/ikonok/le.gif' border='0' /></a>";
            $Vissza.="
            <td width='50'>
              <table cellspacing='0' cellpadding='0' border='0' width='100%' >
               <tr>
                <td width='25'>$Elotte</td>
                <td width='25'>$Utana</td>
               </tr>
              </table>
            </td>
            <td >
              <table cellspacing='0' cellpadding='0' border='0' width='100%' >
               <tr>    
            ";
            
        }
        $Egyhossz=(100/$Maxtddb)."%";

        if (isset($Data["URLAP"]))
        {
            if ($Data["URLAP"]["Nev"]=="")$Data["URLAP"]["Nev"]==" ";
            $Vissza.=$this->Lista_seged2($Data["URLAP"],"/templ_admin/ikonok/smbmod.gif","",$Egyhossz);
        }
        if (isset($Data["LISTA"]))$Vissza.=$this->Lista_seged2($Data["LISTA"],"/templ_admin/ikonok/smbmdir.gif","",$Egyhossz);
        
        if (isset($Data["TEKINT"]))
        {
            $Ujablak="";
            
            if ((isset($Data["TEKINT"]["TEKINTUJ"]))&&($Data["TEKINT"]["TEKINTUJ"]=="1"))$Ujablak="target='_blank' ";


            $Vissza.=$this->Lista_seged2($Data["TEKINT"],"/templ_admin/ikonok/smbview.gif","",$Egyhossz,$Ujablak);
        }
        if (isset($Data["VAGOLAP"]))$Vissza.=$this->Lista_seged2($Data["VAGOLAP"],"","",$Egyhossz);
        
        if (isset($Data["TOROL"]))
        {
            $Data["TOROL"]["Nev"]="<font color='red'>".$Data["TOROL"]["Nev"]."</font>";
            $Confirm="onclick=\"return confirm('Biztos?');\" ";
            $Vissza.=$this->Lista_seged2($Data["TOROL"],"/templ_admin/ikonok/smbdel.gif",$Confirm,$Egyhossz);
        }

        if (isset($Data["EGYEB"]))
        {
            foreach ($Data["EGYEB"] as $egytet)
            {
                if (isset($egytet["Confirm"]))$Confirm="onclick=\"return confirm('Biztos?');\" ";
                    else $Confirm="";
                $Vissza.=$this->Lista_seged2($egytet,"",$Confirm,$Egyhossz);
            }
        }        
        
        if ($Vannyil)
        {
            $Vissza.="</tr>
            </table>
           </td> ";
        }
                

        $Vissza.="</tr>
        <tr>
         <td height=5></td>
        </tr>";
        
        return $Vissza;
    }
    
    function Ujablak()
    {
        return "";
    }

/**
 * Lista_seged2 - admin lista sorának egy linkjét generálja 
 */ 
    function Lista_seged2($Data,$Ikon="",$Confirm="",$Egyhossz,$Ujablak="")
    {
        $Vissza="<td width='$Egyhossz'>
        <table cellspacing='0' cellpadding='0' >
        <tr>";

        
        if ($Data["Nev"]!="")
        {
            if ($Data["Link"]!="")
            {
                if ($Ikon!="")$Ikon="<a href='".$Data["Link"]."' $Confirm $Ujablak><img src='$Ikon' border='0' /></a>";
                if ($Ikon!="")$Ikon="<td width='25'>$Ikon</td>
                ";


                $Vissza.=$Ikon."
                <td><a href='".$Data["Link"]."' $Confirm $Ujablak>".$Data["Nev"]."</a></td>";    
            }else
            {
                if ($Ikon!="")$Ikon="<a href='".$Data["Link"]."' $Confirm $Ujablak><img src='$Ikon' border='0' /></a>";
                if ($Ikon!="")$Ikon="<td width='25'>$Ikon</td>";
                $Vissza.=$Ikon."
                <td>".$Data["Nev"]."</td>
                ";    
                
            }
        }
        $Vissza.="</tr>
        </table>";
        return $Vissza;
    }
    
     function Pagerbe($Data)
        {
            $HonnanNev=$Data["HonnanNev"];
            $Honnan=$Data["Honnan"];
            $GyerekSzam=$Data["GyerekSzam"];
            $OldalonHany=$Data["OldalonHany"];
            $Link=$Data["Link"];
                  if ($Honnan+$OldalonHany>$GyerekSzam) $Megjelenik=$GyerekSzam-$Honnan;
                    else if ($Honnan+$OldalonHany<=$GyerekSzam) $Megjelenik=$OldalonHany;

                $i=0;
                
                 $Random=mt_rand();
                $OldalIr="";
                $JelenOldal=1;

                $max=(int)$OldalonHany*5;
                $innen=(int)$Honnan+(int)$OldalonHany;
                  if ($innen<($max))
                  {
                     $Balmehet=5-($innen/$OldalonHany);
                  }
                  else $Balmehet=1;

                $Seged=ceil(($GyerekSzam-$Honnan)/$OldalonHany);
                if ($Seged<5)
                {
                        $Jobbmehet=5-$Seged;
                }else
                {
                        $Jobbmehet=0;
                }

                $Visszapont="";
                $Elorepont="";

                do{
                  $EddigMegy=($i+1)*$OldalonHany;
                  if ($EddigMegy>$GyerekSzam) $EddigMegy=$GyerekSzam;
                  $c=$i*$OldalonHany;
                  $i++;

                $Sorolhatmeg=(($Honnan-((4+$Jobbmehet)*$OldalonHany)<$c)&&($Honnan+((4+$Balmehet)*$OldalonHany)>$c));
              


                  if ($Honnan!=$c)
                  {
                        
                        if (($Honnan-((5+$Jobbmehet)*$OldalonHany)>=$c))$Visszapont="..";
                        if (($Honnan+((5+$Balmehet)*$OldalonHany)<=$c))$Elorepont="..";

//                        if (($Honnan-((5+$Jobbmehet)*$OldalonHany)<$c)&&($Honnan+((5+$Balmehet)*$OldalonHany)>$c))
                        if ($Sorolhatmeg)
                        {
                               if ($OldalIr!="")$OldalIr.=", ";
                                $OldalIr.="<a href='$Link?$HonnanNev=$c' >$i</a> ";
                        }

                  }else
                  {
                        if ($OldalIr!="")$OldalIr.=", ";
                        $OldalIr.="<strong>$i</strong> ";
//                        $OldalIr.="<span class='link_light' ><b>$i</b></span>";
                        if (($OldalonHany*($i))<$GyerekSzam)
                        {
                                $OldalonVan=$OldalonHany;
                                $JelenOldal=$i;
                        }else
                        {
                                $OldalonVan=$OldalonHany-(($OldalonHany*$i)-$GyerekSzam);
                                $JelenOldal=$i;
                        }
                  }

                }
               while (($OldalonHany*($i))<$GyerekSzam);

               $OldalIr="$Visszapont$OldalIr$Elorepont";
                $OsszOldal=ceil($GyerekSzam/$OldalonHany);
                if ($OsszOldal==0)$JelenOldal=1;


                  if ($Honnan+$OldalonHany<$GyerekSzam)
                  {
                        $TovabbLink="<a href='$Link?$HonnanNev=".($Honnan+$OldalonHany)."' class='pager'>&nbsp;&nbsp; @@@Következő§§§ <b>&raquo;</b></a>";
                        $VegeLink="<a class='navi right' href='$Link?$HonnanNev=".(($OsszOldal-1)*$OldalonHany)."'><i class='icofont icofont-rounded-right'></i></a>";
                  }else
                  {
                        $TovabbLink="";
                        $VegeLink="";
                  }
                  if ($Honnan-$OldalonHany>=0)
                  {
                        $VisszaLink="<a href='$Link?$HonnanNev=".($Honnan-$OldalonHany)."' class='pager'><b>&laquo;</b> @@@Előző§§§ &nbsp;&nbsp;</a>";
                        $ElejeLink="<a class='navi left' href='$Link?$HonnanNev=0'><i class='icofont icofont-rounded-left'></i></a>";
                  }
                  else
                  {
                        $VisszaLink="";
                        $ElejeLink="";
                  }



                $Tartalom="
<div class='pager'>                  
                    <div class='pager_data'>
                      $ElejeLink
                       [ $OldalIr ]
                        $VegeLink
                    
                </div>
                </div>
                ";
                if ($OldalonHany<$GyerekSzam)return $Tartalom;
                else return "";
        }
        
}
?>