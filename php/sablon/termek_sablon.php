<?php


class CWebAruhazCsoport_sablon extends CCsoport_sablon  
{

     function Ujadmlist()
    {
        return true;
    }
    
    function Termekmutat($Data)
    {

        $TERM_IR="";
        
        if ($Data["Termek"])
        {
            if (!(isset($Data["Slider"])))
            {
                $TERM_IR.="<div class='row termekflex'>
                ";
            }

            $Db=count($Data["Termek"]);
            for ($c=0;$c<$Db;$c++)
            {
                $TERM_IR.=$this->Termsor($Data["Termek"][$c]);    
            }
            if (!(isset($Data["Slider"])))
            {
                $TERM_IR.="</div>";
            }
        }
        
        return $TERM_IR;
    }
    
    function Szurok($Data)
    {
       
        $Tipkombo=CForm::Kombo_elem("K_TIPUS",$Data["Menuszuro"]["K_TIPUS"],$Data["Menuszuro"]["K_TIPUS_TAG"],"class='orderby' onchange=\"location.href='".$Data["Ujralink"]."&K_TIPUS='+this.value; \"");
        $Fozkombo=CForm::Kombo_elem("K_FOZDE",$Data["Menuszuro"]["K_FOZDE"],$Data["Menuszuro"]["K_FOZDE_TAG"],"class='orderby' onchange=\"location.href='".$Data["Ujralink"]."&K_FOZDE='+this.value; \" ");

//        $Kiszerkombo=CForm::Kombo_elem("K_KISZER",$Data["Menuszuro"]["K_KISZER"],$Data["Menuszuro"]["K_KISZER_TAG"],"class='orderby' onchange=\"location.href='".$Data["Ujralink"]."&K_KISZER='+this.value; \" ");
  //      $Alkoholkombo=CForm::Kombo_elem("K_ALKOHOL",$Data["Menuszuro"]["K_ALKOHOL"],$Data["Menuszuro"]["K_ALKOHOL_TAG"],"class='orderby' onchange=\"location.href='".$Data["Ujralink"]."&K_ALKOHOL='+this.value; \" ");

        $Pluszir="";  
        $Form0="";
        $Form1="";          
        if ($Data["Menuszuro"]["Admin"])
        {
            $Fozkombo.="<br><br><div class='row' >
            <div class='col-sm-3'>Lejárat:</div> 
            <div class='col-sm-4'><input type='text' class='form-control' name='LEJARAT_TOL' id='LEJARAT_TOL' value='".$Data["Menuszuro"]["LEJARAT_TOL"]."' ></div>

            <div class='col-sm-1'>-</div> 

            <div class='col-sm-4'><input type='text' class='form-control' name='LEJARAT_IG' id='LEJARAT_IG' value='".$Data["Menuszuro"]["LEJARAT_IG"]."' ></div>
           </div> 
            ";
            $Tipkombo.="<br><br>
            <div class='row' >
            <div class='col-sm-3'>Készlet:</div> 
            <div class='col-sm-4'><input type='text' class='form-control' name='KESZL_TOL' id='KESZL_TOL' value='".$Data["Menuszuro"]["KESZL_TOL"]."' ></div>

            <div class='col-sm-1'>-</div> 

            <div class='col-sm-4'><input type='text' class='form-control' name='KESZL_IG' id='KESZL_IG' value='".$Data["Menuszuro"]["KESZL_IG"]."' ></div>
           </div>
           
            ";
            $Pluszir.="<div class='row'>
            <br><p align=center>".$this->Gombcsinal("Keresés","return true","submit")."</p>
            </div>";
            $Form0="<form name='Szukform' action='?' method='post'>
            <input type='hidden' name='Esemeny_Uj' id='Esemeny_Uj' value='".$Data["Ujralink2"]."'>";
            $Form1="</form>
<script>
                
                $(document).ready(function(){
                $('#LEJARAT_TOL').datepicker({ dateFormat: 'yy-mm-dd', firstDay: 1});
                $('#LEJARAT_IG').datepicker({ dateFormat: 'yy-mm-dd', firstDay: 1});
                
                })
                </script>
                            ";

        }            
        $Vissza=" $Form0 <div class='szurok'>
    <div class='row'>
      <div class='col-sm-6'>
      $Fozkombo
      
      </div>
      <div class='col-sm-6'>
       $Tipkombo 
       
      </div>
    </div>
    $Pluszir
  </div>$Form1";
  
        
  
        return $Vissza;
    }
    
    function Mutatkozep($Data)
    {
        $Tartalom="";
        if (isset($Data["Szuronem"])&&($Data["Szuronem"]=="1"))
        {
            
        }else
        {
            $Tartalom.=$this->Szurok($Data);
        }
        $Tartalom.=$this->Termekmutat($Data)."";
        return $Tartalom;

    }
    
    function Tempestkep($Tempest)
    {
        $Vissza="";
        if ("$Tempest"=="1")$Vissza="/templ/images/scottish_gold_award.png";
        if ("$Tempest"=="2")$Vissza="/templ/images/scottish_gold_award2016.png";
        if ("$Tempest"=="3")$Vissza="/templ/images/scottish_silver_award.png";
        if ("$Tempest"=="4")$Vissza="/templ/images/scottish_silver_award2016.png";

        return $Vissza;
        
    }

        function Kedvsor($Data)
        {

            if (isset($Data["Kedvki"])&&($Data["Kedvki"]!=""))
            {
                return "<a href=\"".$Data["Kedvki"]."\" class='kedv' onclick=\"return confirm('Biztos hogy kiveszi a kedvencekből?');\"><img src='/templ/images/kedvel_teli.png'  alt='@@@Kedvencekből eltávolít§§§' title='@@@Kedvencekből eltávolít§§§' style=\"padding-left: 7px; \"   width=25 alt=''/></a>
                        ";
            }

            if (isset($Data["Kedvbe"])&&($Data["Kedvbe"]!=""))
            {
                return "<a href=\"".$Data["Kedvbe"]."\" class='kedv'><img src='/templ/images/kedvel.png'  alt='@@@Kedvencekhez hozzáad§§§' title='@@@Kedvencekhez hozzáad§§§' style=\"padding-left: 7px; \"   width=25 alt=''/></a>
                        ";
            }
            return "<a href=\"javascript:alert('@@@Csak belépett felhasználóknak!§§§');void(0);\" class='kedv'><img src='/templ/images/kedvel.png'  alt='@@@Kedvencekhez hozzáad§§§' title='@@@Kedvencekhez hozzáad§§§' style=\"padding-left: 7px; \"   width=25 alt=''/></a>
                        ";
            
            return "";
        }
        
    function Termsor($item,$Ajax=0)
    {
        $Listakep="";
        $Eredkep="";
        
        if (isset($item["Kep"]))
        {
            $Listakep=$item["Kep"];
        }
        $ERKEP="";
        $KEP_IR="";
        

        $NEV=$item["FOZDE_S"]." ".$item["Nev"];
        $Link=$item["Link"];
       // $Link="#";
        if ($Listakep=="")$Listakep="/templ/images/pic_def.png";


        $SZAZ_IR="";
        if ($item["SZAZALEK_F"]>0)$SZAZ_IR.="<span class='megtakaritas'>-".$this->Szazkerekit($item["SZAZALEK_F"])."%</span>";
        
        $Tempkep=$this->Tempestkep($item["TEMPEST_I"]);
        if ($Tempkep!="")$SZAZ_IR.=" <span class='dij'><img class='img-responsive' src='$Tempkep' width='124' alt=''/></span>";    

 
        if ($Listakep!="")$KEP_IR="<span class='termekkep'>$SZAZ_IR<img src='$Listakep' alt='$NEV' title='$NEV' /></span>";

//if ($Listakep!="")$KEP_IR="<span class='termekkep' style=\"background-url($Listakep);\" ><img src='$Listakep' alt=''/></span>";

        if ($item["KESZLET_I"]<1)
        {
            $AR_IR=$this->ArFormaz($item["AR_F"]);
           
            $KOSAR_IR="";
            $Arst="";
        }else
        if ($item["AR_F"]>0)
        {
            $AR_IR=$this->ArFormaz($item["AR_F"]);
           
            $KOSAR_IR="<a class='butt_kosarba' href=\"javascript:$('#Ujbe".$item["Azon"]."').load('".$item["Kosarlink"]."');void(0);\">@@@KOSÁRBA§§§</a>";
            $Arst="";
        }else
        {
            $AR_IR="0 Ft";
            $Arst=" kikapcs";
            $KOSAR_IR="";
//            $KOSAR_IR="<a class='butt_kosarba kikapcs'  href='javascript:void(0);'>@@@KOSÁRBA§§§</a>";
        }
        $Akt="";
        if ($item["KOSBA_VAN"])$Akt=" active";

        
          if (isset($item["Ajaxos"])&&($item["Ajaxos"]=="1"))
        {
            $Vissza=" <a class='butt_reszletes' href='$Link'   > 
             $KEP_IR
    <h3 class='termekcim'>$NEV".$this->Kedvsor($item)."</h3>
    <span class='termekar".$Arst."'>$AR_IR</span></a>
    <div class='kosargombok".$Akt."'> $KOSAR_IR <a class='butt_kosarba megtekint' href='/Rendeles'>@@@KOSÁR MEGTEKINTÉSE§§§</a> </div>

    
  ";  
        }else
        {
            $Vissza="<div class='col-sm-4 col-md-3 termek' id='Ujbe".$item["Azon"]."' > <a class='butt_reszletes' href='$Link'   > 
             $KEP_IR
    <h3 class='termekcim'>$NEV".$this->Kedvsor($item)."</h3>
    <span class='termekar".$Arst."'>$AR_IR</span></a>
    <div class='kosargombok".$Akt."'> $KOSAR_IR <a class='butt_kosarba megtekint' href='/Rendeles'>@@@KOSÁR MEGTEKINTÉSE§§§</a> </div>
    </div>
    
  ";  
       }
       return $Vissza;
                
    }
    
    function Szazkerekit($AR)
    {
                $Szet=explode(".",$AR);
                if (isset($Szet[1]))
                {
                    $Tized=rtrim($Szet[1], "0");
                    if ($Tized!="") $AR=$Szet[0].".".$Tized;
                        else $AR=$Szet[0];
                }

                return $AR;            
        
    }


}


class CTermek_sablon extends CWebAruhazCsoport_sablon
{
    
    
    function Reszlet_seged($NEV,$ERTEK,$Mertegys="")
    {
        if (($ERTEK=="")||($ERTEK=="0"))return "";
        
        return "            <tr class=''>
              <th> $NEV</th>
              <td >$ERTEK  $Mertegys</td>
            </tr>";
    }
    
    function KapcsTermekmutat($Termek)
    {

        $TERM_IR="";
        
        if ($Termek)
        {
            $TERM_IR.="<h2>Kapcsolódó termékek</h2>
            <div class='row'>
                ";
            

            $Db=count($Termek);
            for ($c=0;$c<$Db;$c++)
            {
                $TERM_IR.=$this->Termsor($Termek[$c]);    
            }
            $TERM_IR.="</div>";
        }
        
        return $TERM_IR;
    }
        
    function Mutat($Data)
    {
        $KEP_IR="";
        $NEV=$Data["Nev"];

        
        if (isset($Data["Kep"]))        
        {
            $ind=0;
            foreach ($Data["Kep"] as $item)
            {
                $Nagykep=$item["Listakep"];
                $Eredkep=$item["Eredkep"];

                if ($Nagykep!="")
                {
                    if ($ind>0)
                    {
                        $KEP_IR.="<a href='$Eredkep' rel='Galery' class='image fancy' style='display: none;' ></a>";
                    }else
                    {

                    $KEP_IR.="<a href='$Eredkep' class='fancy image' rel='Galery'><img src='$Nagykep' alt='$NEV' title='$NEV' /></a>                    
                    ";

                    
                    }
                    $ind++;
                }
            }
        }
        if ($KEP_IR=="")$KEP_IR="<img src='/templ/images/pic_def.png' border='0' alt='$NEV' title='$NEV' />";
        $KOSAR_IR="";
        $AR_IR="";
        if ($Data["KESZLET_I"]<1)
        {
            if ($Data["AR_F"]>0)
            {
                $AR_IR=$this->ArFormaz($Data["AR_F"]);    
            }   
        }else     
        if ($Data["AR_F"]>0)
        {
            $AR_IR=$this->ArFormaz($Data["AR_F"]);
            $rand=rand(0,13000);
            $KOSAR_IR="<form class='cart' method='post' name='Kosform$rand' id='Kosform$rand'  enctype='multipart/form-data' action='".$Data["Kosarlink"]."' >
            <input type='hidden' name='Reszlet' id='Reszlet' value='1'>            
          <div class='quantity'> <span onclick=\"jel=Number(document.Kosform$rand.Mennyiseg.value); if (jel>1){jel--; document.Kosform$rand.Mennyiseg.value=jel} \" class='spinner'>-</span>
            <input step='1' min='1' max='11' name='Mennyiseg' id='Mennyiseg' value='1' title='Mny' class='darabszam' size='4' pattern='[0-9]*' inputmode='numeric' type='number'>
            <span class='spinner' onclick=\"jel=Number(document.Kosform$rand.Mennyiseg.value); jel++; document.Kosform$rand.Mennyiseg.value=jel; \">+</span> </div>
          <button type='submit' class='butt_kosarba' onclick=\"return Kosellenor$rand()\" >@@@Kosárba§§§</button>
        </form><script>
        function Kosellenor$rand()
        {
            menny=$('#Mennyiseg','Kosform$rand').val();
            if (menny>".$Data["KESZLET_I"].")
            {
                alert('@@@Nem rendelhet ennyit, nincs ennyi készleten!§§§');
            }else return true;
            return false;
        }
        </script>";
        }else $KOSAR_IR="";
        $Datair=$this->Reszlet_seged("@@@Kiszerelés§§§:",$Data["KISZERELES_F"],"L");
        $Datair.=$this->Reszlet_seged("@@@Alkoholtartalom§§§:",$Data["ALKOHOL_F"],"%");
        $Datair.=$this->Reszlet_seged("@@@Típus§§§:",$Data["TIPUS_S"]);
        
                
        $SZAZ_IR="";

        $Tempkep=$this->Tempestkep($Data["TEMPEST_I"]);
        if ($Tempkep!="")$SZAZ_IR.=" <span class='dij'><img class='img-responsive' src='$Tempkep' width='124' alt=''/></span>";
        if ($Data["SZAZALEK_F"]>0)$SZAZ_IR.="<span class='megtakaritas'>-".$this->Szazkerekit($Data["SZAZALEK_F"])."%</span>";
        

                
        return "<div class='content-main txt-main'>
  <div class='row'>
    <div class='col-sm-6 termekkep_reszletes'>$KEP_IR $SZAZ_IR</div>
    <div class='col-sm-6 termek_adatlap'>
      <h1 itemprop='name' class='termekcim'>".$Data["FOZDE_S"]." ".$Data["Nev"]."</h1>
      <div class='termekar'>$AR_IR ".$this->Kedvsor($Data)."</div>
      <div itemprop='description'>
        <table class='shop_attributes'>
          <tbody>
           $Datair
          </tbody>
        </table>
        <p>".$Data["LEIRAS_S"]."</p>
        <p class='keszletinfo'>@@@Készleten§§§ </p>
        $KOSAR_IR
        
      </div>
    </div>
  </div>
</div>".$this->KapcsTermekmutat($Data["Kapcsterm"])."
";

        $DAT_IR="";
        if ($Data["AR_F"]>0)
        {


            
            $AR_IR=$this->ArFormaz($Data["AR_F"]);
            //<input type='hidden' name='Esemeny_Uj' id='Esemeny_Uj' value='".$Data["Kosarlink"]."'>
            $DAT_IR.="   <tr>
                                <th>Kosár:</th>
                                <td>
                                    <form class='form no_margin' action='?' method='post' name='Kosform' id='Kosform'>
                                     
                                        <label class='details_basket'><input type='text' name='Mennyiseg' id='Mennyiseg' value='1'  /> ".$Data["UNIT_S"]."</label>
                                        <input type='submit' name='' value='Kosárba' />
                                    </form>
                                </td>
                            </tr>

                    "; 
                                
        }
        

        
        return "<div class='product_details'>
              [[[sablon Navigalosor]]]
                <div class='product_image'>
$KEP_IR                
                        <script type='text/javascript'>
                            if ($(window).width() > 768) {
                                $('#poduct-image').elevateZoom({
                                    zoomWindowFadeIn: 500,
                                    zoomWindowFadeOut: 500,
                                    lensFadeIn: 500,
                                    lensFadeOut: 500
                                });

                            }
                        </script>



                </div>

                <div class='product_description'>
                    <div class='html_edited'>
                        <h1 class='title'><a href='javascript:void(0);'>".$Data["NEV_S"]."</a></h1>

                        <table class='product_table'>
                           $DAT_IR
                           


                        </table>

                    </div>
                </div>

            </div>";

  
        return $Vissza;


    }
    
    function Kepekmutat($Kepek,$Nevi)
    {
        $KEP_IR="";
        $DB=count($Kepek);
        for ($c=0;$c<$DB;)
        {
            $KEP_IR.="<div class='slide'>
            ";
            for ($b=0;$b<4;$b++)
            {
                if (isset($Kepek[$c]))
                {
                    $Nagykep=$Kepek[$c]["Listakep2"];
                    $Eredkep=$Kepek[$c]["Eredkep"];
                    $Nev=$Kepek[$c]["Nev"];
                    if ($Nev=="")$Nev=$Nevi;
                    

                
                    $KEP_IR.="<div class='item'>
                                <a href='$Eredkep' rel='product2' class='fancy' alt='$Nev' title='$Nev'>
                                    <div class='img_holder'>
                                        <a href='$Eredkep' rel='product' class='fancy' alt='$Nev' title='$Nev'><img src='$Nagykep' /></a>
                                    </div>
                                </a>
                            </div>";
                }
                $c++; 
            }
            $KEP_IR.="</div>";
        }
        return "        <div class='section_title'>
            <h2>Galéria</h2>
        </div>

        <div class='wrapper slider'>
            <div class='container'>
                <a class='prev browse left'><i class='icofont icofont-arrow-left'></i></a>
                <div class='scrollable' id='scrollable'>

                    <div class='items'>

                       $KEP_IR

                    </div>
                </div>
                <a class='next browse right'><i class='icofont icofont-arrow-right'></i></a>
            </div>
        </div>";
    }

    
    function Seged_reszl($Felirat,$Ertek)
    {
        if ($Ertek=="")return "";
        return "<p class='info'><span>$Felirat</span> $Ertek</p>";
    }
    
    
 
}

class CRendeloWebAruhazCsoport_sablon extends CCsoport_sablon
{
}

class CAlRendeloWebAruhazCsoport_sablon extends CRendeloWebAruhazCsoport_sablon
{
    function Mutat($DATA)
    {
        $Kep_ir="";
        if ($DATA["Kep"]!="")
        {
            $Kep_ir="<div class='prodimage'>
                            <img src='".$DATA["Kep"]."' />
                        </div>";
        }
        return " $Kep_ir
                        <div class='prod_description html_edited'>
                            <p><strong>".$DATA["Nev"]."</strong></p>
                            ".$DATA["Szoveg"]."
                            
                        </div>
";
    }
    
 
}


?>