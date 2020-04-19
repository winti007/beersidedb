<?php


class CRendelCsoport_sablon extends CCsoport_sablon  
{
    function Lista($Data)
    {
        
        
        $Rendek=$Data["Lista"]["Eredm"];
        $Vissza="<table width=100% cellpadding='3' cellspacing='0' border='1' >
<tr>
        <td>Sorszám</td>
        <td>Rendelve</td>
        <td>Ki rendelte</td>
        <td>Összeg</td>        
        <td>Állapot</td>
        </tr>
                ";
    

        if ($Rendek)
        {
            foreach ($Rendek as $item)
            {
                $Vissza.=$this->Lista_seged($item);
            }
        }
        $Vissza.="</table>".$Data["Lista"]["Pager"];
        return $Vissza;
    }
    
    function Lista_seged($Item)
    {
        
        $ALL_IR="";
        $REND_ALLAPOT=$Item["REND_ALLAPOT_I"];
        $ALL_IR=$this->Tombert(REND_ALLAPOT,$REND_ALLAPOT);
        
        $KATT="";
        if (isset($Item["Urlaplink"]))$KATT="onclick=\"location.href='".$Item["Urlaplink"]."'\" style=\"cursor:pointer\" ";

        
        $Vissza="<tr $KATT height='30' >
        <td>".$Item["SORSZAM_S"]."</td>
        <td>".$Item["KESZULT"]."</td>
        
        <td>".$Item["SZAML_NEV_S"]."</td>
        <td>".$this->ArFormaz($Item["TETEL"]["ERTEK"])."</td>
        <td>".$ALL_IR."</td>
        </tr>";
        return $Vissza;
    }           
}

class CRendeles_sablon extends CVaz_bovit_sablon  
{
       
    function Tetelekmutatmail($Data)
    {
      $Tetel=$Data["Tetel"]["Eredm"];
            


                    
                            
            $Tartalom="  
            <table width='100%' cellpadding='0' cellspacing='0' class='prducts'>
                <tr>
                    <th>@@@Termék§§§</th>
                    <th class='tar'>@@@Bruttó ár§§§</th>
                    <th>@@@Mennyiség§§§</th>
                    <th class='tar'>@@@Fizetendő§§§</th>
                </tr>
                                         
            
                ";
            $TET_IR="";
            $ind=1;
            $Ossz_ar=0;
            $Ossz_db=0;
            $Netto=0;
            foreach ($Tetel as $egytetel)
            {
                $Mennyiseg=$egytetel["DB_I"];
                $Ossz_db=$Ossz_db+$Mennyiseg;
                
                $Ar=$Mennyiseg*$egytetel["BRUTTO"];
                $Netto=$Netto+($Mennyiseg*$egytetel["NETTO"]);
                $Ossz_ar=$Ossz_ar+$Ar;
                $Tartalom.=$this->Egytetelekmutat_mail($egytetel,$ind);
                $ind++;                        
            }                        

            
            $Tartalom.="</table>
            
            <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                          <tr>
                            <td width='8' height='78'></td>
                            <td align='right'><span class='kosar_price_l'>
                              
                              @@@Összesen nettó§§§: <strong>".$this->Arformaz($Netto)."</strong><br />
                              @@@Áfa§§§: <strong>".$this->Arformaz($Ossz_ar-$Netto)."</strong><br />
                              @@@Összesen bruttó§§§: <strong>".$this->Arformaz($Ossz_ar)."</strong><br />
                              </span></td>
                            <td width='16'></td>
                            </tr>
                          </table>
                
            
            ";
            return $Tartalom;        
    }
    
    function Tetelekmutat($Data)
    {
            
            $Hol=$Data["Hol"];
            if ("$Hol"=="2")return $this->Tetelekmutatmail($Data);
            $FEJ0="";
            if ("$Hol"=="0")$FEJ0="<div class='kosar_torles'><a class='fa fa-2x fa-trash-o rejtett' href='#'></a></div>";
            $Tetel=$Data["Tetel"]["Eredm"];
            
            if ($Tetel)
            {
                $Tdb=count($Tetel);
                
            }else $Tdb=0;
            
            if ($Tdb<1)return "<div class='content-main txt-main'>
  <h2 class='text-center kiszallitas'>@@@Jelenleg üres a bevásárlókosár!§§§</h2>
  </div>
  ";
            
            $Form0="";            
            $Form1="";
            if ("$Hol"=="0")
            {
                $Form0="<div class='content-main txt-main'>
                <h2 class='text-center kiszallitas'>@@@A KISZÁLLÍTÁS 15.000 -FT RENDELÉSI ÖSSZEG FELETT INGYENES.§§§</h2>
                <form method='post' action='".$Data["Kosarfrisslink"]."' name='Kosarform' id='Kosarform' >
                <input type='hidden' name='Frissit' id='Frissit' value='1' >
                </div>";
                $Form1="</form>
                ";
            }
        
            $Tartalom=" 
  
  $Form0
  <div class='kosarbox'>
    <div class='row kosar_elem cimsor'>
      <div class='col-xs-4 col-sm-2 kosarkep'></div>
      <div class='col-xs-8 col-sm-10 kosar_elemek'>
        <div class='row'>
          <div class='kosar_megnevezes'>@@@Termék§§§</div>
          <div class='kosar_egysegar cim'>@@@Ár§§§</div>
          <div class='kosar_mennyiseg'>@@@Mennyiség§§§</div>
          <div class='kosar_osszesen cim'>@@@Összeg§§§</div>
          $FEJ0
        </div>
      </div>
    </div>
            
                ";
            $TET_IR="";
            $ind=1;
            $Ossz_ar=0;
            $Ossz_db=0;
            $Netto=0;
            $Extratet=array();
            foreach ($Tetel as $egytetel)
            {
                $EXTRA=$egytetel["EXTRATETEL_I"];
                if (($EXTRA=="0")||($EXTRA=="2"))
                {
                    $Mennyiseg=$egytetel["DB_I"];
                    $Ossz_db=$Ossz_db+$Mennyiseg;
                
                    $Ar=$Mennyiseg*$egytetel["BRUTTO"];
                    $Netto=$Netto+($Mennyiseg*$egytetel["NETTO"]);
                    $Ossz_ar=$Ossz_ar+$Ar;

                    $Tartalom.=$this->Egytetelekmutat($egytetel,$ind,$Hol);
                }else $Extratet[]=$egytetel;
                $ind++;                        
            }                        
            $Gombok="";
            $Frissit="";
            if ("$Hol"=="0")
            {
                $Frissit="          <div class='row'>
            <div class='col-xs-12'><a class='butt_kosarba frisites' href='javascript:document.Kosarform.submit();void(0);'>@@@KOSÁR FRISSÍTÉSE§§§</a></div>
          </div>
";

                if (Kuponmod())
                {
                    
                    $Gombok.="<form action='?' method='post'>
                    <input type='hidden' name='Esemeny_Uj' value='".$Data["Rendellink"]."'>    
                    <div class='row'>
            <div class='col-xs-12'>Kedvezményre jogosító kupon: <br ><input type='text' name='KUPON_S' value='".$Data["KUPON_S"]."'  class='form-control'> <br></div>
          </div>
          <div class='row'>
            <div class='col-xs-12'><input type='submit'  class='butt_kosarba formbutt' value='@@@TOVÁBB A PÉNZTÁRHOZ§§§' ></div>
          </div>
          </form>
          
          ";
                }else
                {
                
                $Gombok.="          <div class='row'>
            <div class='col-xs-12'><a class='butt_kosarba nagy' href='".$Data["Rendellink"]."'>@@@TOVÁBB A PÉNZTÁRHOZ§§§</a></div>
          </div>
";
                }

/*                $Gombok="<input type='button' class='btn' onclick=\"if (confirm('@@@Biztos?§§§'))location.href='".$Data["Uritlink"]."'\" value='@@@Kosár ürítés§§§'>

                    <a href='".$Data["Visszalink"]."' class='btn red'>@@@Vissza§§§</a>
                    <a href='".$Data["Rendellink"]."' class='btn fright'>@@@Megrendelés§§§</a>";
                    */
            }
            
            $Tartalom.="  <div class='osszeg_total'>
      <div class='row'>
        <div class='col-sm-7'></div>
        <div class='col-sm-5'>
          $Frissit
          <div class='kosar_vegosszeg text-center'>@@@KOSÁR VÉGÖSSZEG§§§</div>
          <div class='row'>
            <div class='col-xs-6 col-sm-4 reszosszeg-bal'>@@@Részösszeg:§§§</div>
            <div class='col-xs-6 col-sm-8 reszosszeg-jobb'>".$this->Arformaz($Ossz_ar)." </div>
          </div>
          ";
          if ("$Hol"=="0")
          {
                $Tartalom.="<div class='row '>
            <div class='col-xs-6 col-sm-4 reszosszeg-bal'>
              <div class='border-top'>@@@Szállítás:§§§</div>
            </div>
            <div class='col-xs-6 col-sm-8 reszosszeg-jobb'>
              <div class='border-top'>@@@A szállítási költségek a címed megadását követően kerülnek kiszámításra.§§§</div>
            </div>
          </div>"; 
          }else
          {
            
              foreach ($Extratet as $egytetel)
              {
                
                    $Mennyiseg=$egytetel["DB_I"];
                    $Ossz_db=$Ossz_db+$Mennyiseg;
                
                    $Ar=$Mennyiseg*$egytetel["BRUTTO"];
                    $Netto=$Netto+($Mennyiseg*$egytetel["NETTO"]);
                    $Ossz_ar=$Ossz_ar+$Ar;
                
                    $Tartalom.=$this->Egytetelekmutat_szall($egytetel,$ind,$Hol);   
              }
          }
          
          
          
          $Tartalom.="
          <div class='row'>
            <div class='col-xs-6 col-sm-4 reszosszeg-bal'>
              <div class='border-top vegosszeg'>@@@Összeg:§§§</div>
            </div>
            <div class='col-xs-6 col-sm-8 reszosszeg-jobb '>
              <div class='border-top vegosszeg'>".$this->Arformaz($Ossz_ar)."</div>
            </div>
          </div>
         $Form1
          
          $Gombok
        </div>
      </div>
    </div>
  </div>
  

            ";

            return $Tartalom;                
    }
    
    function Egytetelekmutat_mail($egytetel,$ind)
    {
        $Mennyiseg=$egytetel["DB_I"];
                
        $Ar=$Mennyiseg*$egytetel["BRUTTO"];
        

        $KEP_IR="";
        if ($egytetel["Listakep"]!="")$KEP_IR="<img src='".$egytetel["Listakep"]."' class='prod_image' alt='".$egytetel["NEV"]."' />";
        return "                <tr class='odd'>
                    <td>".$egytetel["NEV_S"]." ".$egytetel["CIKKSZAM_S"]." ".$this->Meretszinir($egytetel)."</td>
                    <td class='tar'> ".$this->Arformaz($egytetel["BRUTTO"])." </td>
                    <td class='tac'>$Mennyiseg</td>
                    <td class='tar'> ".$this->Arformaz($Ar)."</td>
                </tr>
                 <tr>
                            <td></td>
                            <td height='8'></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            </tr>";
        
    }
    
    function Meretszinir($DATA)
    {
        $Vissza="";
        if (isset($DATA["MERET_S"]))
        {
            if ($DATA["SZIN_S"]!="")
            {
                $Vissza.="@@@Size§§§: ".$DATA["MERET_S"]." ";
            }
        }
        if (isset($DATA["SZIN_S"]))
        {
            if ($DATA["SZIN_S"]!="")
            {
                $Vissza.=" @@@Color§§§: ".$DATA["SZIN_S"]." ";
            }
        }
        return $Vissza;
    }
    
    
    function Egytetelekmutat_szall($egytetel,$ind,$Hol)
    {
        $Mennyiseg=$egytetel["DB_I"];
                
        $Ar=$Mennyiseg*$egytetel["BRUTTO"];
        
            return "<div class='row '>
            <div class='col-xs-6 col-sm-4 reszosszeg-bal'>
              <div class='border-top'>".$egytetel["NEV"]."</div>
            </div>
            <div class='col-xs-6 col-sm-8 reszosszeg-jobb'>
              <div class='border-top'>".$this->Arformaz($Ar)."</div>
            </div>
          </div>";         
    }
    
    function Egytetelekmutat($egytetel,$ind,$Hol)
    {
        $Mennyiseg=$egytetel["DB_I"];
                
        $Ar=$Mennyiseg*$egytetel["BRUTTO"];
        

        $KEP_IR="";
        if ($egytetel["Listakep"]!="")$KEP_IR="<img src='".$egytetel["Listakep"]."' class='img-responsive' alt='".$egytetel["NEV"]."'  />";
        $EXTRA=$egytetel["EXTRATETEL_I"];
        
        if ($EXTRA!="0")$Hol=1;
        
        $LINK="javascript:void(0)";
        if ($egytetel["Termlink"]!="")$LINK=$egytetel["Termlink"];
        if ("$Hol"=="0")
        {
                $MEZO="DB".$egytetel["Azon"]."";
                return "    <div class='row kosar_elem'>
      <div class='col-xs-4 col-sm-2 kosarkep'>$KEP_IR</div>
      <div class='col-xs-8 col-sm-10 kosar_elemek'>
        <div class='row'>
          <div class='kosar_megnevezes'><span class='nevlink'><a href='$LINK'>".$egytetel["NEV"]." ".$this->Meretszinir($egytetel)."</a></span></div>
          <div class='kosar_egysegar'><i>@@@Brutó egységár:§§§</i> <span class='akciosar'>".$this->Arformaz($egytetel["BRUTTO"])."</span></div>
          <div class='kosar_mennyiseg'>
            <div class='quantity'> <span class='spinner' onclick=\"jel=Number(document.Kosarform.$MEZO.value); if (jel>0){jel--; document.Kosarform.$MEZO.value=jel} \">-</span>
              <input step='1' min='1' max='11' name='$MEZO' id='$MEZO' value='$Mennyiseg' title='Mny' class='darabszam' size='4' pattern='[0-9]*' inputmode='numeric' type='number'>
              <span class='spinner' onclick=\"jel=Number(document.Kosarform.$MEZO.value); jel++; document.Kosarform.$MEZO.value=jel; \">+</span> </div>
          </div>
          <div class='kosar_osszesen'><i>@@@Bruttó összesen:§§§</i>".$this->Arformaz($Ar)."</div>
          <div class='kosar_torles'><a class='fa fa-2x fa-trash-o' onclick=\"return confirm('@@@Biztos?§§§');\" href='".$egytetel["Torollink"]."'></a></div>
        </div>
      </div>
    </div>
                ";
                
        }else
        {
                return "  <div class='row kosar_elem'>
      <div class='col-xs-4 col-sm-2 kosarkep'>$KEP_IR</div>
      <div class='col-xs-8 col-sm-10 kosar_elemek'>
        <div class='row'>
          <div class='kosar_megnevezes'><span class='nevlink'><a href='$LINK'>".$egytetel["NEV"]." ".$this->Meretszinir($egytetel)."</a></span></div>
          <div class='kosar_egysegar'><i>@@@Brutó egységár:§§§</i> <span class='akciosar'>".$this->Arformaz($egytetel["BRUTTO"])."</span></div>
          <div class='kosar_mennyiseg'>
            <div class='quantity'> 
              $Mennyiseg
              </div>
          </div>
          <div class='kosar_osszesen'><i>@@@Bruttó összesen:§§§</i>".$this->Arformaz($Ar)."</div>
          
        </div>
      </div>
    </div>
                ";
                
        }  
        
                
     
    }
    
    function Gls_valaszt()
    {
        return "<link rel='STYLESHEET' type='text/css' href='//online.gls-hungary.com/psmap/default.css'>

<script src='//maps.googleapis.com/maps/api/js?v=3&key=AIzaSyC4ScTF8P1Ht3FrTB74UsHs0RIK4q0mBw4' type='text/javascript'></script>

<script src='//online.gls-hungary.com/psmap/psmap.js' type='text/javascript'></script>

<style>
#big-canvas {
width: 100%;
height: 400px;

}

</style>
<script>
var glsMap=0;
function initGLSPSMap()
{
    glsMap = new GLSPSMap();
    glsMap.init('HU', 'big-canvas', '1116,Budapest,HU', 0);
    google.maps.event.trigger(glsMap, 'resize');
    
    
}
function glsPSMap_OnSelected_Handler(data) 
{
    
    

    
    tel=data.phone;
//    if (tel!=null)
  

		  $('#SZALL_NEV_S').val(data.name+'');
		  $('#SZALL_KERNEV_S').val(data.pclshopid);
          
		  $('#SZALL_IRSZAM_S').val(data.zipcode);
		  $('#SZALL_VAROS_S').val(data.city);
		  $('#SZALL_CIM_S').val(data.address);

		  
		  $('#SZALL_CEGNEV_S').val('');


          
		  $('#spvalasztott_postapont').html( data.name + ' '+data.pclshopid+' ' +data.zipcode +' '+ data.city +' '+ data.address );
          

          
          $('#postapontvalasztoapi').html('');

}


</script>

<div id='big-canvas' ></div>

";
//$(document).ready(initGLSPSMap);
    }
              
    function Kosarurlap1uj($Data)
    {
        $this->ScriptTarol("
function Ellenor()
{
    
    if (Adatellenor())
    {
        if (document.Adatform.FIZ_MOD_I.value=='')
        {
            alert('A fizetési mód nem lehet üres!');
            document.Adatform.FIZ_MOD_I.focus();
        }else
        if (document.Adatform.SZALL_MOD_I.value=='')
        {
            alert('A szállítási mód nem lehet üres!');
            document.Adatform.SZALL_MOD_I.focus();
        }else return true;
        return false;
            
    }else return false;
}

function Cimmasol()
{
        document.getElementById('SZALL_NEV_S').value=document.getElementById('SZAML_NEV_S').value;
        document.getElementById('SZALL_KERNEV_S').value=document.getElementById('SZAML_KERNEV_S').value;
        document.getElementById('SZALL_IRSZAM_S').value=document.getElementById('SZAML_IRSZAM_S').value;
        document.getElementById('SZALL_VAROS_S').value=document.getElementById('SZAML_VAROS_S').value;
        document.getElementById('SZALL_CIM_S').value=document.getElementById('SZAML_CIM_S').value;
        document.getElementById('SZALL_CEGNEV_S').value=document.getElementById('SZAML_CEGNEV_S').value;
}


function Atvetvalt()
{
    szmod=document.Adatform.SZALL_MOD_I.value;
    
    
    if (szmod=='8')
    {
        $('#big-canvas').show();
        if (typeof glsMap === 'object')
        {
            
        }else
        {
            
            initGLSPSMap();
        }
        
        $('#postapontvalasztoapi').hide();   

        $('#Atvhelyir').show();    
        $('#Atvhelyir0').show();    

    }else
    if ((szmod=='1')||(szmod=='7'))
    {
        $('#big-canvas').hide();
        $('#postapontvalasztoapi').hide();        

        
        $('#Atvhelyir0').hide();    
        $('#Atvhelyir').hide();    
        $('#sp_cimmasol').show();    
        $('#trSZALL_NEV_S').show();    
        $('#trSZALL_IRSZAM_S').show();    
        $('#trSZALL_VAROS_S').show();    
        $('#trSZALL_CIM_S').show();    
        $('#trSZALL_KERNEV_S').show();    
        $('#trSZALL_CEGNEV_S').show();    

    }else
    if ((szmod=='2')||(szmod=='3')||(szmod=='4'))
    {
        $('#big-canvas').hide();
        
        $('#Atvhelyir').show();    
        $('#Atvhelyir0').show();    
        szat=Number(szmod)-1;
        Postalatszik(szat);
        $('#sp_cimmasol').hide();        

        $('#trSZALL_NEV_S').hide();    
        $('#trSZALL_IRSZAM_S').hide();    
        $('#trSZALL_VAROS_S').hide();    
        $('#trSZALL_CIM_S').hide();    

        $('#trSZALL_KERNEV_S').hide();    
        $('#trSZALL_CEGNEV_S').hide();    
        
    }else
    {
        $('#postapontvalasztoapi').hide();
        
        $('#big-canvas').hide();
        
        $('#sp_cimmasol').hide();
        $('#Atvhelyir').hide();
        $('#Atvhelyir0').hide();
        $('#trSZALL_NEV_S').hide();    
        $('#trSZALL_IRSZAM_S').hide();    
        $('#trSZALL_VAROS_S').hide();    
        $('#trSZALL_CIM_S').hide();    
        
        $('#trSZALL_KERNEV_S').hide();    
        $('#trSZALL_CEGNEV_S').hide();    
                
    }
    
}


$(document).ready(function() {                    
                    Atvetvalt();
}) 

");
/*
        $('#trSZALL_NEV_S').show();        
        $('#trSZALL_IRSZAM_S').show();        
        $('#trSZALL_VAROS_S').show();        
        $('#trSZALL_CIM_S').show();    

*/
        $Form=new CForm_rendel("Adatform");
        $Form->Hidden("Esemeny_Uj",$Data["Rendkuld"]);
        $Form->Hidden("Formbol",1);
        
        $Felhsab=new CSimaFelhasznalo_sablon();


        $Form->Fejlec("@@@Számlázási adatok§§§ ");
        
        $Form->TextBox("*@@@Vezetéknév§§§:","SZAML_NEV_S",$Data["SZAML_NEV_S"],"");
        $Form->TextBox("*@@@Keresztnév§§§:","SZAML_KERNEV_S",$Data["SZAML_KERNEV_S"],"");        
        $Form->TextBox("@@@Cégnév§§§:","SZAML_CEGNEV_S",$Data["SZAML_CEGNEV_S"],"");
        
        $Form->TextBox("*@@@Irányítószám§§§:","SZAML_IRSZAM_S",$Data["SZAML_IRSZAM_S"],"");
        $Form->TextBox("*@@@Város§§§:","SZAML_VAROS_S",$Data["SZAML_VAROS_S"],"");
        $Form->TextBox("*@@@Utca, házszám§§§:","SZAML_CIM_S",$Data["SZAML_CIM_S"],"");

        
        $Form->TextBox("*@@@Telefonszám§§§:","TELSZAM_S",$Data["TELSZAM_S"],"");
        $Form->TextBox("*@@@E-mail§§§:","EMAIL_S",$Data["EMAIL_S"],"");

        $Form->TextBox("@@@Adószám§§§:","ADOSZAM_S",$Data["ADOSZAM_S"],"");
                
        $Formnev="Adatform";
        $this->ScriptTarol("
function Adatellenor()
{
    
    szmod=document.Adatform.SZALL_MOD_I.value;
    
    if ((szmod=='2')||(szmod=='3')||(szmod=='4')||(szmod=='8'))pval=1;
        else pval=0;

    if ((szmod=='1')||(szmod=='7'))hazhoz=1;
        else hazhoz=0;
    
    
    if (document.$Formnev.SZAML_NEV_S.value=='')
        {
            alert('@@@A számlázási név nem lehet üres!§§§');
            document.$Formnev.SZAML_NEV_S.focus();
        }else 
        if (document.$Formnev.SZAML_KERNEV_S.value=='')
        {
            alert('@@@A számlázási keresztnév nem lehet üres!§§§');
            document.$Formnev.SZAML_KERNEV_S.focus();
        }else 
        if (document.$Formnev.SZAML_IRSZAM_S.value=='')
        {
            alert('@@@A számlázási irányítószám nem lehet üres!§§§');
            document.$Formnev.SZAML_IRSZAM_S.focus();
        }else 
        if (document.$Formnev.SZAML_VAROS_S.value=='')
        {
            alert('@@@A számlázási város nem lehet üres!§§§');
            document.$Formnev.SZAML_VAROS_S.focus();
        }else 
        if (document.$Formnev.SZAML_CIM_S.value=='')
        {
            alert('@@@A számlázási utca, házszám nem lehet üres!§§§');
            document.$Formnev.SZAML_CIM_S.focus();
        }else
        if ((hazhoz)&&(document.$Formnev.SZALL_NEV_S.value==''))
        {
            alert('@@@A szállítási név nem lehet üres!§§§');
            document.$Formnev.SZALL_NEV_S.focus();
        }else
        if ((hazhoz)&&(document.$Formnev.SZALL_IRSZAM_S.value==''))
        {
            alert('@@@A szállítási irányítószám nem lehet üres!§§§');
            document.$Formnev.SZALL_IRSZAM_S.focus();
        }else
        if ((hazhoz)&&(document.$Formnev.SZALL_VAROS_S.value==''))
        {
            alert('@@@A szállítási város nem lehet üres!§§§');
            document.$Formnev.SZALL_VAROS_S.focus();
        }else
        if ((hazhoz)&&(document.$Formnev.SZALL_CIM_S.value==''))
        {
            alert('@@@A szállítási utca,házszám  nem lehet üres!§§§');
            document.$Formnev.SZALL_CIM_S.focus();
        }else
      if (document.$Formnev.TELSZAM_S.value=='')
        {
            alert('@@@A telefonszám nem lehet üres!§§§');
            document.$Formnev.TELSZAM_S.focus();
        }else
      if (document.$Formnev.EMAIL_S.value=='')
        {
            alert('@@@Az email nem lehet üres!§§§');
            document.$Formnev.EMAIL_S.focus();
        }else
        if ((pval)&&($('#spvalasztott_postapont').html()==''))
        {
            alert('Kérjük válassza ki hová küldjük a rendelést!');
        }else
                
        return true;
        
        return false;         
}
");
        
        
        $Form->Hidden("FIZ_MOD_I","");
        $Form->Hidden("SZALL_MOD_I","");
        
//        $Form->Hidden("SZALL_MOD_I",$Data["SZALL_MOD_I"]);

                $Httpcim="http";
             //   $Jsbe="/templ/js/postapont-api.js";
                $Jsbe="https://www.posta.hu/static/internet/app/postapont/javascripts/postapont-api.js";
            
//            <script type='text/javascript' src='//maps.googleapis.com/maps/api/js?sensor=false&language=hu&region=HU&key=AIzaSyBZVwBKZB_Thc79IwoxDp7v7FfJ5AmNtuQ'></script>
        $Valaszt=" 
                <script type='text/javascript' src='$Jsbe'></script>
<link rel='stylesheet' type='text/css' href='https://www.postapont.hu/static/css/postapont-api.css'>

<script type='text/javascript'>
function Postalatszik(tipus)
{
    
//tipus: 1 - postapont, 2 csomagautomata, 3 postán marado
    
 
            if ($('#postapontvalasztoapi').html()!='')$('#postapontvalasztoapi').html('');
            
    if ($('#postapontvalasztoapi').html()=='')
    {
    	ppapi.linkZipField('SZAML_IRSZAM_S'); //<-- A megrendelő form input elemének a megjelölése (beállítása a kiválasztó számára)
	 //<-- PostaPont választó API beillesztése ( ilyen azonosítóval rendelkező DOM objektumba)
     
    
     $('#postapontvalasztoapi').show();
    
     
          ppapi.insertMap('postapontvalasztoapi');
        	ppapi.onSelect = function(data){ //<-- Postapont kiválasztásra bekövetkező esemény lekötése
		
	 
		  $('#SZALL_NEV_S').val(data['name']);
		  $('#SZALL_IRSZAM_S').val(data['zip']);
		  $('#SZALL_VAROS_S').val(data['county']);
		  $('#SZALL_CIM_S').val(data['address']);

		  $('#SZALL_KERNEV_S').val('');
		  $('#SZALL_CEGNEV_S').val('');


          
		  $('#spvalasztott_postapont').html( data['name'] + ' ' +data['zip'] +' '+ data['county'] +' '+ data['address'] );
          

          
          $('#postapontvalasztoapi').html('');
	   };
    }else
    {
        
        $('#postapontvalasztoapi').show();
    }
        
      if (tipus=='1')
    {
        ppapi.setMarkers('10_posta', false);
        ppapi.setMarkers('20_molkut', true);
        ppapi.setMarkers('30_csomagautomata', false);
        ppapi.setMarkers('50_coop', true);
        ppapi.setMarkers('40_postapontplusz', false);
    }
    if (tipus=='3')
    {
        ppapi.setMarkers('10_posta', true);
        ppapi.setMarkers('40_postapontplusz', true);
        
        ppapi.setMarkers('20_molkut', false);
        ppapi.setMarkers('30_csomagautomata', false);
        ppapi.setMarkers('50_coop', false);
    }
    if (tipus=='2')
    {
        
        ppapi.setMarkers('10_posta', false);
        ppapi.setMarkers('20_molkut', false);
        ppapi.setMarkers('30_csomagautomata', true);
        ppapi.setMarkers('50_coop', false);
        ppapi.setMarkers('40_postapontplusz', false);
    } 
     
 }
 $(document).ready(function() {


    })
</script>

     <div class='kiszallitasi_modok'>
        
        <ul>
";

        $Vetek=$this->Tombre(SZALL_MOD2);

        $Fizmodok=$this->Tombre(FIZ_MOD);
        
        $Atvet_lehet=$Data["Atvet_lehet"];
        
        foreach ($Vetek as $egyvet)
        {
            if (($egyvet[1]!="0")&&(isset($Atvet_lehet[$egyvet[1]])))
            {
                $Valaszt.="<li><span class='atvetelmod'>".$egyvet[0]."<i class='fa fa-chevron-circle-down' aria-hidden='true'></i> </span>
            <ul>
";
                foreach ($Fizmodok as $egyfiz)
                {
                    if ($egyfiz[1]!="0")
                    {
                        $Valaszt.=" <li>
                <label class='radio-inline'><span class='atvetel'>".$egyfiz[0]."</span>
                  <input name='optradio' type='radio' onclick=\" $('#FIZ_MOD_I').val('".$egyfiz[1]."');$('#SZALL_MOD_I').val('".$egyvet[1]."');Atvetvalt();\" > <span class='osszeg'>&nbsp;</span>
                  </label>
              </li>";    
                    }
                }
                $Valaszt.="</ul>
            </li>";
            }
        }
        
        $Valaszt.="</ul>
        </div>
<div id='postapontvalasztoapi'></div>        
".$this->Gls_valaszt();
        $Form->Oszlopvalt();

        $Form->Fejlec("@@@Szállítási adatok§§§");

        

        
      

        $Form->Szabad1("<span id='sp_cimmasol'><input type=button value='@@@Számlázási cím másolása§§§' class='butt_kosarba formbutt' onclick=\"Cimmasol();\"></span>");

        $Form->Szabad_full($Valaszt);

        $be="Kérem a szállítási cím kiválasztása után ellenőrizze, hogy az Ön által kiválasztott cím megjelenik-e a \" Választott átvevő hely\" mellett! Ha nem, akkor kérem válassza ki újra a kézbesítési címet, ellenkező esetben nem tudjuk csomagját hova küldjük. Köszönjük!";
        $Form->Szabad1("<span style=\"color:red\">".$be."</span>","Atvhelyir0");

        $Form->Szabad2("@@@Választott átvevő hely§§§:","<span id='spvalasztott_postapont'></span>","Atvhelyir");
        
        $Form->TextBox("@@@Vezetéknév§§§:","SZALL_NEV_S",$Data["SZALL_NEV_S"],"");
        $Form->TextBox("*@@@Keresztnév§§§:","SZALL_KERNEV_S",$Data["SZALL_KERNEV_S"],"");
        $Form->TextBox("@@@Cégnév§§§:","SZALL_CEGNEV_S",$Data["SZALL_CEGNEV_S"],"");

        $Form->TextBox("@@@Irányítószám§§§:","SZALL_IRSZAM_S",$Data["SZALL_IRSZAM_S"],"");
        $Form->TextBox("@@@Város§§§:","SZALL_VAROS_S",$Data["SZALL_VAROS_S"],"");
        $Form->TextBox("@@@Utca, házszám§§§:","SZALL_CIM_S",$Data["SZALL_CIM_S"],"");

        
        
//        $Form->Kombobox("Átvétel:","SZALL_MOD_I",$Data["SZALL_MOD_I"],"onchange=\"Atvetvalt();\" ",SZALL_MOD);

        //$Form->Hidden("FIZ_MOD_I","0");
//        $Form->Szabad2("Fizetési mód:","<span id='spfizmod'></span>");

  //      $Form->Kombobox("Fizetési mód:","FIZ_MOD_I",$Data["FIZ_MOD_I"],"",FIZ_MOD);
        $Form->Area("@@@Megjegyzés:§§§","MEGJEGYZES_S",$Data["MEGJEGYZES_S"],"");
        $Form->Gomb("@@@Rendelés§§§","return Ellenor()","submit");
        
        return $Form->OsszeRak();        
    }

    function Kosarurlap1($Data)
    {
        return $this->Kosarurlap1uj($Data);
        $this->ScriptTarol("
function Ellenor()
{
    
    if (Adatellenor())
    {
        if (document.Adatform.FIZ_MOD_I.value=='')
        {
            alert('A fizetési mód nem lehet üres!');
            document.Adatform.FIZ_MOD_I.focus();
        }else
        if (document.Adatform.SZALL_MOD_I.value=='')
        {
            alert('A szállítási mód nem lehet üres!');
            document.Adatform.SZALL_MOD_I.focus();
        }else return true;
        return false;
            
    }else return false;
}

function Cimmasol()
{
        document.getElementById('SZALL_NEV_S').value=document.getElementById('SZAML_NEV_S').value;
        document.getElementById('SZALL_KERNEV_S').value=document.getElementById('SZAML_KERNEV_S').value;
        document.getElementById('SZALL_IRSZAM_S').value=document.getElementById('SZAML_IRSZAM_S').value;
        document.getElementById('SZALL_VAROS_S').value=document.getElementById('SZAML_VAROS_S').value;
        document.getElementById('SZALL_CIM_S').value=document.getElementById('SZAML_CIM_S').value;
        document.getElementById('SZALL_CEGNEV_S').value=document.getElementById('SZAML_CEGNEV_S').value;
}


function Atvetvalt()
{
    szmod=document.Adatform.SZALL_MOD_I.value;
    
    if (szmod=='1')
    {
        
        $('#Atvhelyir0').hide();    
        $('#Atvhelyir').hide();    
        $('#sp_cimmasol').show();    
        $('#trSZALL_NEV_S').show();    
        $('#trSZALL_IRSZAM_S').show();    
        $('#trSZALL_VAROS_S').show();    
        $('#trSZALL_CIM_S').show();    
        $('#trSZALL_KERNEV_S').show();    
        $('#trSZALL_CEGNEV_S').show();    

    }else
    if ((szmod=='2')||(szmod=='3')||(szmod=='4'))
    {
        $('#Atvhelyir').show();    
        $('#Atvhelyir0').show();    
        szat=Number(szmod)-1;
        Postalatszik(szat);
        $('#sp_cimmasol').hide();        

        $('#trSZALL_NEV_S').hide();    
        $('#trSZALL_IRSZAM_S').hide();    
        $('#trSZALL_VAROS_S').hide();    
        $('#trSZALL_CIM_S').hide();    

        $('#trSZALL_KERNEV_S').hide();    
        $('#trSZALL_CEGNEV_S').hide();    
        
    }else
    {
        $('#sp_cimmasol').hide();
        $('#Atvhelyir').hide();
        $('#Atvhelyir0').hide();
        $('#trSZALL_NEV_S').hide();    
        $('#trSZALL_IRSZAM_S').hide();    
        $('#trSZALL_VAROS_S').hide();    
        $('#trSZALL_CIM_S').hide();    
        
        $('#trSZALL_KERNEV_S').hide();    
        $('#trSZALL_CEGNEV_S').hide();    
                
    }
    
}


$(document).ready(function() {                    
                    Atvetvalt();
}) 

");
/*
        $('#trSZALL_NEV_S').show();        
        $('#trSZALL_IRSZAM_S').show();        
        $('#trSZALL_VAROS_S').show();        
        $('#trSZALL_CIM_S').show();    

*/
        $Form=new CForm_rendel("Adatform");
        $Form->Hidden("Esemeny_Uj",$Data["Rendkuld"]);
        $Form->Hidden("Formbol",1);
        
        $Felhsab=new CSimaFelhasznalo_sablon();


        $Form->Fejlec("@@@Számlázási adatok§§§ ");
        
        $Form->TextBox("*@@@Vezetéknév§§§:","SZAML_NEV_S",$Data["SZAML_NEV_S"],"");
        $Form->TextBox("*@@@Keresztnév§§§:","SZAML_KERNEV_S",$Data["SZAML_KERNEV_S"],"");        
        $Form->TextBox("@@@Cégnév§§§:","SZAML_CEGNEV_S",$Data["SZAML_CEGNEV_S"],"");
        
        $Form->TextBox("*@@@Irányítószám§§§:","SZAML_IRSZAM_S",$Data["SZAML_IRSZAM_S"],"");
        $Form->TextBox("*@@@Város§§§:","SZAML_VAROS_S",$Data["SZAML_VAROS_S"],"");
        $Form->TextBox("*@@@Utca, házszám§§§:","SZAML_CIM_S",$Data["SZAML_CIM_S"],"");

        
        $Form->TextBox("@@@Telefonszám§§§:","TELSZAM_S",$Data["TELSZAM_S"],"");
        $Form->TextBox("*@@@E-mail§§§:","EMAIL_S",$Data["EMAIL_S"],"");

        $Form->TextBox("@@@Adószám§§§:","ADOSZAM_S",$Data["ADOSZAM_S"],"");
                
        $Formnev="Adatform";
        $this->ScriptTarol("
function Adatellenor()
{
    
    szmod=document.Adatform.SZALL_MOD_I.value;
    
    if ((szmod=='2')||(szmod=='3')||(szmod=='4'))pval=1;
        else pval=0;

    if ((szmod=='1'))hazhoz=1;
        else hazhoz=0;
    
    
    if (document.$Formnev.SZAML_NEV_S.value=='')
        {
            alert('@@@A számlázási név nem lehet üres!§§§');
            document.$Formnev.SZAML_NEV_S.focus();
        }else 
        if (document.$Formnev.SZAML_KERNEV_S.value=='')
        {
            alert('@@@A számlázási keresztnév nem lehet üres!§§§');
            document.$Formnev.SZAML_KERNEV_S.focus();
        }else 
        if (document.$Formnev.SZAML_IRSZAM_S.value=='')
        {
            alert('@@@A számlázási irányítószám nem lehet üres!§§§');
            document.$Formnev.SZAML_IRSZAM_S.focus();
        }else 
        if (document.$Formnev.SZAML_VAROS_S.value=='')
        {
            alert('@@@A számlázási város nem lehet üres!§§§');
            document.$Formnev.SZAML_VAROS_S.focus();
        }else 
        if (document.$Formnev.SZAML_CIM_S.value=='')
        {
            alert('@@@A számlázási utca, házszám nem lehet üres!§§§');
            document.$Formnev.SZAML_CIM_S.focus();
        }else
        if ((hazhoz)&&(document.$Formnev.SZALL_NEV_S.value==''))
        {
            alert('@@@A szállítási név nem lehet üres!§§§');
            document.$Formnev.SZALL_NEV_S.focus();
        }else
        if ((hazhoz)&&(document.$Formnev.SZALL_IRSZAM_S.value==''))
        {
            alert('@@@A szállítási irányítószám nem lehet üres!§§§');
            document.$Formnev.SZALL_IRSZAM_S.focus();
        }else
        if ((hazhoz)&&(document.$Formnev.SZALL_VAROS_S.value==''))
        {
            alert('@@@A szállítási város nem lehet üres!§§§');
            document.$Formnev.SZALL_VAROS_S.focus();
        }else
        if ((hazhoz)&&(document.$Formnev.SZALL_CIM_S.value==''))
        {
            alert('@@@A szállítási utca,házszám  nem lehet üres!§§§');
            document.$Formnev.SZALL_CIM_S.focus();
        }else
      if (document.$Formnev.EMAIL_S.value=='')
        {
            alert('@@@Az email nem lehet üres!§§§');
            document.$Formnev.EMAIL_S.focus();
        }else
        if ((pval)&&($('#spvalasztott_postapont').html()==''))
        {
            alert('Kérjük válassza ki hová küldjük a rendelést!');
        }else
                
        return true;
        
        return false;         
}
");
        
        
        $Form->Hidden("FIZ_MOD_I","");
        $Form->Hidden("SZALL_MOD_I","");
        
//        $Form->Hidden("SZALL_MOD_I",$Data["SZALL_MOD_I"]);

                $Httpcim="http";
             //   $Jsbe="/templ/js/postapont-api.js";
                $Jsbe="https://www.posta.hu/static/internet/app/postapont/javascripts/postapont-api.js";
            
        $Valaszt=" <script type='text/javascript' src='//maps.googleapis.com/maps/api/js?sensor=false&language=hu&region=HU&key=AIzaSyBZVwBKZB_Thc79IwoxDp7v7FfJ5AmNtuQ'></script>
                <script type='text/javascript' src='$Jsbe'></script>
<link rel='stylesheet' type='text/css' href='https://www.postapont.hu/static/css/postapont-api.css'>

<script type='text/javascript'>
function Postalatszik(tipus)
{
    
//tipus: 1 - postapont, 2 csomagautomata, 3 postán marado
    
 
            if ($('#postapontvalasztoapi').html()!='')$('#postapontvalasztoapi').html('');
            
    if ($('#postapontvalasztoapi').html()=='')
    {
    	ppapi.linkZipField('SZAML_IRSZAM_S'); //<-- A megrendelő form input elemének a megjelölése (beállítása a kiválasztó számára)
	 //<-- PostaPont választó API beillesztése ( ilyen azonosítóval rendelkező DOM objektumba)
     
    
     $('#postapontvalasztoapi').show();
    
     
          ppapi.insertMap('postapontvalasztoapi');
        	ppapi.onSelect = function(data){ //<-- Postapont kiválasztásra bekövetkező esemény lekötése
		
	 
		  $('#SZALL_NEV_S').val(data['name']);
		  $('#SZALL_IRSZAM_S').val(data['zip']);
		  $('#SZALL_VAROS_S').val(data['county']);
		  $('#SZALL_CIM_S').val(data['address']);

		  $('#SZALL_KERNEV_S').val('');
		  $('#SZALL_CEGNEV_S').val('');


          
		  $('#spvalasztott_postapont').html( data['name'] + ' ' +data['zip'] +' '+ data['county'] +' '+ data['address'] );
          

          
          $('#postapontvalasztoapi').html('');
	   };
    }else
    {
        
        $('#postapontvalasztoapi').show();
    }
        
      if (tipus=='1')
    {
        ppapi.setMarkers('10_posta', false);
        ppapi.setMarkers('20_molkut', true);
        ppapi.setMarkers('30_csomagautomata', false);
        ppapi.setMarkers('50_coop', true);
        ppapi.setMarkers('40_postapontplusz', false);
    }
    if (tipus=='3')
    {
        ppapi.setMarkers('10_posta', true);
        ppapi.setMarkers('40_postapontplusz', true);
        
        ppapi.setMarkers('20_molkut', false);
        ppapi.setMarkers('30_csomagautomata', false);
        ppapi.setMarkers('50_coop', false);
    }
    if (tipus=='2')
    {
        
        ppapi.setMarkers('10_posta', false);
        ppapi.setMarkers('20_molkut', false);
        ppapi.setMarkers('30_csomagautomata', true);
        ppapi.setMarkers('50_coop', false);
        ppapi.setMarkers('40_postapontplusz', false);
    } 
     
 }
 $(document).ready(function() {


    })
</script>

     <div class='kiszallitasi_modok'>
        
        <ul>
";

        if (isset($_GET["Szalluj"]))$Vetek=$this->Tombre(SZALL_MOD2);
                               else $Vetek=$this->Tombre(SZALL_MOD);
        $Fizmodok=$this->Tombre(FIZ_MOD);
        
        $Atvet_lehet=$Data["Atvet_lehet"];
        
        foreach ($Vetek as $egyvet)
        {
            if (($egyvet[1]!="0")&&(isset($Atvet_lehet[$egyvet[1]])))
            {
                $Valaszt.="<li><span class='atvetelmod'>".$egyvet[0]."<i class='fa fa-chevron-circle-down' aria-hidden='true'></i> </span>
            <ul>
";
                foreach ($Fizmodok as $egyfiz)
                {
                    if ($egyfiz[1]!="0")
                    {
                        $Valaszt.=" <li>
                <label class='radio-inline'><span class='atvetel'>".$egyfiz[0]."</span>
                  <input name='optradio' type='radio' onclick=\" $('#FIZ_MOD_I').val('".$egyfiz[1]."');$('#SZALL_MOD_I').val('".$egyvet[1]."');Atvetvalt();\" > <span class='osszeg'>&nbsp;</span>
                  </label>
              </li>";    
                    }
                }
                $Valaszt.="</ul>
            </li>";
            }
        }
        
        $Valaszt.="</ul>
        </div>
<div id='postapontvalasztoapi'></div>        
";
        $Form->Oszlopvalt();

        $Form->Fejlec("@@@Szállítási adatok§§§");

        

        
      

        $Form->Szabad1("<span id='sp_cimmasol'><input type=button value='@@@Számlázási cím másolása§§§' class='butt_kosarba formbutt' onclick=\"Cimmasol();\"></span>");

        $Form->Szabad_full($Valaszt);

        $be="Kérem a szállítási cím kiválasztása után ellenőrizze, hogy az Ön által kiválasztott cím megjelenik-e a \" Választott átvevő hely\" mellett! Ha nem, akkor kérem válassza ki újra a kézbesítési címet, ellenkező esetben nem tudjuk csomagját hova küldjük. Köszönjük!";
        $Form->Szabad1("<span style=\"color:red\">".$be."</span>","Atvhelyir0");

        $Form->Szabad2("@@@Választott átvevő hely§§§:","<span id='spvalasztott_postapont'></span>","Atvhelyir");
        
        $Form->TextBox("@@@Vezetéknév§§§:","SZALL_NEV_S",$Data["SZALL_NEV_S"],"");
        $Form->TextBox("*@@@Keresztnév§§§:","SZALL_KERNEV_S",$Data["SZALL_KERNEV_S"],"");
        $Form->TextBox("@@@Cégnév§§§:","SZALL_CEGNEV_S",$Data["SZALL_CEGNEV_S"],"");

        $Form->TextBox("@@@Irányítószám§§§:","SZALL_IRSZAM_S",$Data["SZALL_IRSZAM_S"],"");
        $Form->TextBox("@@@Város§§§:","SZALL_VAROS_S",$Data["SZALL_VAROS_S"],"");
        $Form->TextBox("@@@Utca, házszám§§§:","SZALL_CIM_S",$Data["SZALL_CIM_S"],"");

        
        
//        $Form->Kombobox("Átvétel:","SZALL_MOD_I",$Data["SZALL_MOD_I"],"onchange=\"Atvetvalt();\" ",SZALL_MOD);

        //$Form->Hidden("FIZ_MOD_I","0");
//        $Form->Szabad2("Fizetési mód:","<span id='spfizmod'></span>");

  //      $Form->Kombobox("Fizetési mód:","FIZ_MOD_I",$Data["FIZ_MOD_I"],"",FIZ_MOD);
        $Form->Area("@@@Megjegyzés:§§§","MEGJEGYZES_S",$Data["MEGJEGYZES_S"],"");
        $Form->Gomb("@@@Rendelés§§§","return Ellenor()","submit");
        
        return $Form->OsszeRak();
    }
    
    function Adatlapelokep($Data)
    {
        $Vissza=$this->Adatlapmutat($Data);
        
        $Vissza.="
        
        <table width=100%>
         <tr>
          <td align=center>

         
<div class='checkbox checkbox-danger'>
                            <input id='FOGAD' name='FOGAD' class='styled' value='on' type='checkbox'>
                            <label onclick=\"window.open('".$Data["Fogadlink"]."','_blank','');\"> @@@Rendelési feltételek elfogadása§§§ </label>
                        </div>
                                 
        ".$this->Gombcsinal("@@@Rendelés§§§","return RKuld()","Button")."</td>
        </tr>
        </table>
        
<script>
function RKuld()
{
     if (!(document.getElementById('FOGAD').checked))
    {
        alert('A feltételek elfogadása kötelező!');
        
    }else
    {
        location.href='".$Data["Rendkuld"]."';
           
    }
  
    
}
</script>        ";


        
        return $Vissza;
        $Form=new CForm("Adatform");
        $Form->Hidden("Esemeny_Uj",$Data["Rendkuld"]);
        $Felhsab=new CSimaFelhasznalo_sablon();
        
        if ($Data["SORSZAM_S"]!="")$Form->Szabad2("@@@Sorszám§§§:",$Data["SORSZAM_S"]);
        $Felhsab->Adatmutat($Form,$Data);
        $Form->Szabad2("Fizetési mód:",$this->Tombert(FIZ_MOD,$Data["FIZ_MOD_I"]));
        $Form->Szabad2("Szállítási mód:",$this->Tombert(SZALL_MOD,$Data["SZALL_MOD_I"]));
        $Form->Szabad2("Megjegyzés:",$Data["MEGJEGYZES_S"]);
        $Form->Gomb("@@@Rendelés§§§","return Ellenor()","submit");
        return $Form->OsszeRak();
    } 
    
    function Adatlapmutat_mail($Data)
    {
        $SORSZIR="";       
        if ($Data["SORSZAM_S"]!="")$SORSZIR.="<b>@@@Rendelés sorszáma§§§:</b> ".$Data["SORSZAM_S"];

        
        $REND_ALLAPOT=$Data["REND_ALLAPOT_I"];
        $SORSZIR.=" @@@Rendelés állapota§§§: ".$this->Tombert(REND_ALLAPOT,$REND_ALLAPOT);

       
        return "$SORSZIR<table width='100%' border='0' cellspacing='0' cellpadding='0'>
  <tr>
    <td width='50%' valign='top'><table width='280' border='1' align='left' cellpadding='3' cellspacing='0'>
  <tbody>
    <tr>
      <td colspan='2' ><strong>@@@Számlázási adatok§§§</strong></td>
    </tr>
    <tr>
      <td width='100'>@@@Név§§§</td>
      <td>".$Data["SZAML_NEV_S"]." ".$Data["SZAML_KERNEV_S"]."</td>
    </tr>
    <tr>
      <td width='100'>@@@Cégnév§§§</td>
      <td>".$Data["SZAML_CEGNEV_S"]."</td>
    </tr>
    <tr>
      <td width='100'>@@@Irányítószám§§§</td>
      <td>".$Data["SZAML_IRSZAM_S"]."</td>
    </tr>
    <tr>
      <td width='100'>@@@Város§§§</td>
      <td>".$Data["SZAML_VAROS_S"]."</td>
    </tr>
    <tr>
      <td width='100'>@@@Utca, házszám§§§</td>
      <td>".$Data["SZAML_CIM_S"]."</td>
    </tr>
    <tr>
      <td width='100'>@@@Telefonszám§§§</td>
      <td>".$Data["TELSZAM_S"]."</td>
    </tr>
    <tr>
      <td width='100'>@@@E-mail§§§</td>
      <td>".$Data["EMAIL_S"]."</td>
    </tr>
    <tr>
      <td width='100'>@@@Adószám§§§</td>
      <td>".$Data["ADOSZAM_S"]."</td>
    </tr>
    <tr>
      <td >@@@Fizetési mód§§§</td>
      <td>".$this->Tombert(FIZ_MOD,$Data["FIZ_MOD_I"])."</td>
    </tr>
    <tr>
      <td width='100'>@@@Átvétel§§§</td>
      <td>".$this->Tombert(SZALL_MOD,$Data["SZALL_MOD_I"])."</td>
    </tr>

  </tbody>
</table></td>
    <td width='50%' valign='top'><table width='280' border='1' align='right' cellpadding='3' cellspacing='0'>
  <tbody>
    <tr>
      <td colspan='2' ><strong>@@@Szállítási adatok§§§</strong></td>
    </tr>
    <tr>
      <td width='100'>@@@Név§§§</td>
      <td>".$Data["SZALL_NEV_S"]." ".$Data["SZALL_KERNEV_S"]."</td>
    </tr>
    <tr>
      <td width='100'>@@@Cégnév§§§</td>
      <td>".$Data["SZALL_CEGNEV_S"]."</td>
    </tr>   
    <tr>
      <td >@@@Irányítószám§§§</td>
      <td>".$Data["SZALL_IRSZAM_S"]."</td>
    </tr>
    <tr>
      <td >@@@Város§§§</td>
      <td>".$Data["SZALL_VAROS_S"]."</td>
    </tr>
    <tr>
      <td >@@@Utca, házszám§§§</td>
      <td>".$Data["SZALL_CIM_S"]."</td>
    </tr>
  </tbody>
</table></td>
  </tr>
</table><br>".$Data["MEGJEGYZES_S"]."".$this->Aruhazadat()."<br><br>";
        
        
    }
    
    




    
    function Aruhazadat()
    {
//2051 Biatorbágy, Czipri Bíró u.7        
        return "<br><br><table cellspacing='0' cellpadding='3' width='100%' border='1'>
  <tbody>
    <tr>
      <td colspan='2' ><strong>@@@Áruház adatai§§§</strong></td>
    </tr>
    <tr>
      <td width='200'>@@@Cégnév§§§:</td>
      <td>BogiMedical Kft. </td>
    </tr>
    <tr>
      <td width='200'>@@@Székhely§§§</td>
      <td>2051 Biatorbágy, Rákóczi u.23/A </td>
    </tr>
    <tr>
      <td width='200'>@@@Telephely§§§</td>
      <td>2051 Biatorbágy, Rákóczi u.23/A</td>
    </tr>
    <tr>
      <td width='200'>@@@Cégjegyzékszám§§§</td>
      <td>13-09-153192</td>
    </tr>
    <tr>
      <td width='200'>@@@Adószám§§§</td>
      <td>23763090-2-13</td>
    </tr>
    <tr>
      <td>@@@Telefonszám§§§</td>
      <td>+36 70 469 7986</td>
    </tr>
    <tr>
      <td>@@@E-mail§§§</td>
      <td><a href='mailto:info@beerside.hu'>info@beerside.hu</a></td>
    </tr>
    <tr>
      <td>@@@Bankszámla§§§</td>
      <td>10918001-00000085-80640007</td>
    </tr>    
    <tr>
      <td width='200'>@@@Működési engedélyszám§§§</td>
      <td>SZ-37/2/2017</td>
    </tr>
    <tr>
      <td width='200'>@@@Weboldal§§§</td>
      <td><a style='text-decoration:none; color: #000;' href='#'>http://beerside.hu</a></td>
    </tr>
  </tbody>
</table>";
    }

    function Adatlapmutat($Data)
    {
        if (isset($Data["Mailbe"]))
        {
            if ($Data["Mailbe"])return $this->Adatlapmutat_mail($Data);
        }
        $Felhsab=new CSimaFelhasznalo_sablon();

        $Form=new CForm_mutat("Adatform");
        $Form->Fejlec("@@@Számlázási adatok§§§ ");
        

        $Form->Szabad2("@@@Vezetéknév§§§:",$Data["SZAML_NEV_S"]);
        $Form->Szabad2("@@@Keresztnév§§§:",$Data["SZAML_KERNEV_S"]);
        $Form->Szabad2("@@@Cégnév§§§:",$Data["SZAML_CEGNEV_S"]);


        
        $Form->Szabad2("*@@@Irányítószám§§§:",$Data["SZAML_IRSZAM_S"]);
        $Form->Szabad2("*@@@Város§§§:",$Data["SZAML_VAROS_S"]);
        $Form->Szabad2("*@@@Utca, házszám§§§:",$Data["SZAML_CIM_S"]);

        
        $Form->Szabad2("@@@Telefonszám§§§:",$Data["TELSZAM_S"]);
        $Form->Szabad2("*@@@E-mail§§§:",$Data["EMAIL_S"]);
        
        $Form->Szabad2("@@@Adószám§§§:",$Data["ADOSZAM_S"]);
        
        $Form->Oszlopvalt();

        $Form->Fejlec("@@@Szállítási adatok§§§");

     //   $Form->Szabad_full($Valaszt);
        
        
        $Form->Szabad2("@@@Átvétel§§§:",$this->Tombert(SZALL_MOD,$Data["SZALL_MOD_I"]));
        $Form->Szabad2("@@@Fizetési mód§§§:",$this->Tombert(FIZ_MOD,$Data["FIZ_MOD_I"]));
        
        $Form->Szabad2("@@@Vezetéknév§§§:",$Data["SZALL_NEV_S"]);
        $Form->Szabad2("@@@Keresztnév§§§:",$Data["SZALL_KERNEV_S"]);
        $Form->Szabad2("@@@Cégnév§§§:",$Data["SZALL_CEGNEV_S"]);
        
        $Form->Szabad2("@@@Irányítószám§§§:",$Data["SZALL_IRSZAM_S"]);
        $Form->Szabad2("@@@Város§§§:",$Data["SZALL_VAROS_S"]);
        $Form->Szabad2("@@@Utca, házszám§§§:",$Data["SZALL_CIM_S"]);

        
        
  //      $Form->Kombobox("Fizetési mód:","FIZ_MOD_I",$Data["FIZ_MOD_I"],"",FIZ_MOD);
        $Form->Szabad2("@@@Megjegyzés:§§§",$Data["MEGJEGYZES_S"]);
        
     if (isset($Data["Adminfunkc"]))
        {
                
            $Form->Hidden("Esemeny_Uj",$Data["Rendkuld"]);
            

            $REND_ALLAPOT=$Data["REND_ALLAPOT_I"];
            for ($b=0;$b<$REND_ALLAPOT;$b++)
            {
                if ($Data["REND_ALLAPOT_MIKOR".$b."_D"]>"2000-01-01 00:00")
                {
                    $Form->Szabad2($this->Tombert(REND_ALLAPOT,$b),$Data["REND_ALLAPOT_MIKOR".$b."_D"]);
                }
            }       
                             
            $Form->Szabad2($this->Tombert(REND_ALLAPOT,$REND_ALLAPOT),"".$Data["REND_ALLAPOT_MIKOR".$REND_ALLAPOT."_D"]);


          //  $Form->Szabad2(" ","<input type='submit' value='Tárol' id='Submit' name='Submit'>");

            if (isset($Data["Adminallhat"])&&($Data["Adminallhat"]))
            {
            $GOMB_IR="";
            $Dbok=explode("!",REND_ALLAPOT);
            foreach ($Dbok as $egydb)
            {
                $Reszek=explode("+",$egydb);
                if ($REND_ALLAPOT!=$Reszek[1])
                {
                    if ($Reszek[1]>0)$GOMB_IR.="<input type='submit' value='$Reszek[0]' id='Submit' name='Submit' class='butt_kosarba formbutt' style=\"display: inline\"> ";
                }else
                {
                    $GOMB_IR.="<input type='button' value='$Reszek[0]' class='butt_kosarba formbutt'  style=\"display: inline\" onclick=\"alert('A rendelés már ebben az állapotban van!');\" > ";
                }
            }
            $Form->Szabad2("",$GOMB_IR);
            }
           // $Vegere=$Form->OsszeRak();
        }else
        {
            
        }

        
                
                $SORSZIR="";
        if ($Data["SORSZAM_S"]!="")$SORSZIR.="<b>@@@Rendelés sorszáma§§§:</b> ".$Data["SORSZAM_S"];
                
        return $SORSZIR.$Form->OsszeRak();

        
        

        $Datamut=$Felhsab->Adatmutat2($Data);
        return "@@@Sorszám§§§: ".$Data["SORSZAM_S"]."
        
        <table width='100%' border='0' cellspacing='0' cellpadding='5'>
                          <tr>
                            <td width='350' valign='top'><table width='100%' border='0' cellspacing='0' cellpadding='0'>
                              <tr>
                                <td height='24' class='bg_kosar_top'><table width='100%' border='0' cellspacing='0' cellpadding='0'>
                                  <tr>
                                    <td width='6'></td>
                                    <td height='24'><span class='kosar_head_2'>@@@Fizetési mód§§§</span></td>
                                    <td width='28' align='center'></td>
                                  </tr>
                                </table></td>
                              </tr>
                              <tr>
                                <td height='14'></td>
                              </tr>
                              <tr>
                                <td height='20'><span class='kosar_text'>[X] ".$this->Tombert(FIZ_MOD,$Data["FIZ_MOD_I"])."</span></td>
                              </tr>
                              <tr>
                                <td height='14'></td>
                              </tr>
                              <tr>
                                <td height='12'></td>
                              </tr>
                            </table>
                             ".$Datamut["Bal"]."</td>
                            <td>&nbsp;</td>
                            <td width='350' valign='top'><table width='100%' border='0' cellspacing='0' cellpadding='0'>
                              <tr>
                                <td height='24' class='bg_kosar_top'><table width='100%' border='0' cellspacing='0' cellpadding='0'>
                                  <tr>
                                    <td width='6'></td>
                                    <td height='24'><span class='kosar_head_2'>@@@Átvétel§§§</span></td>
                                    <td width='28' align='center'></td>
                                  </tr>
                                </table></td>
                              </tr>
                              <tr>
                                <td height='14'></td>
                              </tr>
                              <tr>
                                <td><table width='100%' border='0' cellspacing='0' cellpadding='0'>
                                  <tr>
                                    <td width='6' ></td>
                                    <td width='328' height='20'><span class='kosar_text'>[X] ".$this->Tombert(SZALL_MOD,$Data["SZALL_MOD_I"])."</span></td>
                                    <td align='center'></td>
                                  </tr>
                                  <tr>
                                    <td></td>
                                    <td height='14'></td>
                                    <td></td>
                                  </tr>
                                </table></td>
                              </tr>
                              <tr>
                                <td height='12'></td>
                              </tr>
                            </table>
                              ".$Datamut["Jobb"]."
</td>
                            </tr>
                          </table>
                          @@@Megjegyzés§§§: ".$Data["MEGJEGYZES_S"];

        $Felhsab=new CSimaFelhasznalo_sablon();
        $Felhsab->Adatmutat($Form,$Data);
        $Form->Szabad2("Fizetési mód:",$this->Tombert(FIZ_MOD,$Data["FIZ_MOD_I"]));
        return $Form->OsszeRak();
    }     
} 

class CRendelestermek_sablon extends CVaz_bovit_sablon  
{
 
} 


 
?>