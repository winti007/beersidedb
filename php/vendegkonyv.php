<?php


define ("VENDEGSZOLASEGYOLDALON", "20");


class CVendegKonyvCsoport extends CCsoport
{

        function TenylegesKereses($Mit)
        {

                $Vissza=array();
                return $Vissza;
        }
        
        function Tobbnyelvu()
        {
            return true;
        }

        
        function SzerkesztoSorBeallit()
        {
                $this->Szerkeszto["LISTA"]=1;
                $this->Szerkeszto["URLAP"]=1;
                $this->Szerkeszto["TEKINT"]=1;
        }
        
        function Adatlist_adm_tag()
        {
            $Vissza["URLAP"]=1;    
            $Vissza["LISTA"]=1;    
            $Vissza["TEKINT"]=1;    
   
            return $Vissza;    
        } 
                
                

        function SzovegCserel()
        {
                if ($this->Tobbnyelvu())
                {
                    $Szoveg=$this->TablaAdatKi("SZOVEG_".$this->Nyelvadpub()."_S");
                }else
                {
                    $Szoveg=$this->TablaAdatKi("SZOVEG_S");
                }

                $Szoveg=$this->SzovegElkap($Szoveg);
                
                $Param["Link"]=$this->EsemenyHozzad("UjHozzaSzolas_pb_fut");
                $Szoveg.=$this->Sablonbe("Uzenbeker",$Param);
                
                $Ered=$this->Futtatgy("VENDEGKONYVSZOLAS","VZ_AZON_I desc",1,20)->Adatlistkozep_publ(false);
                
                
                $Szoveg.=$this->Sablonbe("Vendegmutat",$Ered["Eredm"]);
                
                $Szoveg.=$Ered["Pager"];



                return $Szoveg;
        }


        function UjHozzaSzolas_pb_fut()
        {
                $NEV=$this->Postgetv("NEV");
            if (!(isset($NEV)))
            {   
                    if ($this->Sessad("Aktfelh")->Jogosultsag()>=99)
                    {
                        return $this->Lista_fut();
                    }else return $this->Mutat_pb_fut();

                
            }
                $Jo=false;        
                if (isset($_SESSION['qaptcha_key']) && !empty($_SESSION['qaptcha_key']))
                {
                        $myVar = $_SESSION['qaptcha_key'];
                        
                        if(isset($_POST[''.$myVar.'']) && empty($_POST[''.$myVar.'']))
                        {
                            $Jo=true;   
                        }
                }
                if ($Jo)
                {            
                    $UjObjektum=$this->UjObjektumLetrehoz("CVendegKonyvSzolas","VENDEGKONYVSZOLAS");
                    
                    $this->Futtat($UjObjektum)->Alapbe($this->Postgetv("NEV"),$this->Postgetv("EMAIL"),$this->Postgetv("UZENET"));
                    
                    if ($this->Sessad("Aktfelh")->Jogosultsag()>=99)
                    {
                        return $this->Lista_fut();
                    }else return $this->Mutat_pb_fut();
                }else
                {
                    $this->ScriptUzenetAd("@@@Hiba az ellenõrzõ kódban!§§§");
                    if ($this->Sessad("Aktfelh")->Jogosultsag()>=99)
                    {
                        return $this->Lista_fut();
                    }else return $this->Mutat_pb_fut();
                }
        }

 
        function Lista_fut()
        {
            $Nyil=0;
            if ($this->Sessad("Aktfelh")->Jogosultsag()>100)$Nyil=1;
            
            $Data["Lista"]=$this->Futtat($this->Gyerekparam("VENDEGKONYVSZOLAS","VZ_AZON_I desc",1,"20","",$Nyil))->Adatlistkozep_publ();
            
            $Data=array_merge($Data,$this->Adat_adm());
            
                $Param["Link"]=$this->EsemenyHozzad("UjHozzaSzolas_pb_fut");
                $Vissza["Tartalom"]=$this->Sablonbe("Uzenbeker",$Param);
            
            $Vissza["Tartalom"].=$this->Sablonbe("Lista",$Data);
//            $Vissza["Tartalom"]=$this->Sablonbe("Lista",$Data);
                    
            $Vissza["Cim"]=$this->AdminNevAd();
            return $this->Sablonbe("Oldal_admin",$Vissza);
        }



}



class CVendegKonyvSzolas extends CVaz_bovit
{
    var $Tabla_nev="VENDEGKONYVSZOLAS";
    

        
       public function Tablasql()
        {

$SQL="
CREATE TABLE VENDEGKONYVSZOLAS(
  `AZON_I` int(11) NOT NULL auto_increment,
  `NEV_S` varchar(50) default '',
  `EMAIL_S` varchar(100) default '',
  `SZOVEG_S` text,
  PRIMARY KEY  (`AZON_I`)
) engine=".MYSQL_ENGINE."
";
    
            return $SQL;
        }

        

            
        function Adatlist_adm_tag()
        {
            $Vissza["URLAP"]=1;    
            $Vissza["LISTA"]=1;    
        //    $Vissza["TEKINT"]=1;    
   
            return $Vissza;    
        } 
              
        function NevAd()
        {
            
        }              
                       
        function Adatlistkozep_publ($Almenukell=false)
        {
            $Vissza=$this->OsszesTablaAdatVissza();
            
            $Vissza["Keszult"]=$this->Keszult();
            $Vissza["Torollink"]=$this->EsemenyHozzad("Torol");
            
              
            return $Vissza;
        }
        
     function Alapbe($NEV,$EMAIL,$UZENET)
     {
                $this->TablaAdatBe("NEV_S",strip_tags($NEV));
                $this->TablaAdatBe("EMAIL_S",strip_tags($EMAIL));
                $this->TablaAdatBe("SZOVEG_S",strip_tags($UZENET));
                $this->Szinkronizal();
                  

      }

        function SzerkesztoSor()
        {
/*1 Sor megmutatás felh*/

                $Torol=$this->EsemenyHozzad("Torol");
                $Keszult=$this->KeszultAd();

                $KESZULT=substr($Keszult,0,10);
                $NEV=$this->TablaAdatKi("NEV");
                $EMAIL=$this->TablaAdatKi("EMAIL");
                $SZOVEG=$this->TagCsere($this->TablaAdatKi("SZOVEG"));

                $Vissza="
<table width='100%' border='0' cellspacing='0' cellpadding='0'>
                            <tr>
                              <td><table width='100%' border='0' cellspacing='0' cellpadding='0' class='pic_border'>
                                <tr>
                                  <td class='bg_color_5'><table width='100%' border='0' cellspacing='0' cellpadding='0'>
                                    <tr>
                                      <td width='18' align='center'><img src='/images/bullet.gif' width='7' height='7'></td>
                                      <td height='19'><span class='text'><b>$NEV</b></span></td>
                                      <td width='170' align='right'><span class='text_sm'>$KESZULT</span></td>
                                      <td width='5'></td>
                                    </tr>
                                  </table></td>
                                </tr>
                                <tr>
                                  <td height='1' class='bg_color_2'></td>
                                </tr>
                                <tr>
                                  <td class='bg_color_3'><table width='100%' border='0' cellspacing='0' cellpadding='0'>
                                    <tr>
                                      <td width='5'></td>
                                      <td valign='top'><table width='100%' border='0' cellspacing='0' cellpadding='0'>
                                        <tr>
                                          <td height='6'></td>
                                        </tr>
                                        <tr>
                                          <td height='22' valign='top'><span class='txt_main'>$SZOVEG</span></td>
                                        </tr>
                                          <tr  >
<td align=right><a href='$Torol' onclick=\"return confirm('Biztos hogy törölni akarja?');\" class=adminmenu><img src='/ikonok/smbdel.gif' border='0' alt='Hozzászólás törlése'>Törlés</a></td>
                                           </tr>
                                        <tr>
                                          <td height='6'></td>
                                        </tr>
                                      </table></td>
                                      <td width='5'></td>
                                    </tr>
                                  </table></td>
                                </tr>
                                <tr>
                                  <td height='1' class='bg_color_2'></td>
                                </tr>
                                <tr>
                                  <td class='bg_color_5'><table width='100%' border='0' cellspacing='0' cellpadding='0'>
                                    <tr>
                                      <td width='18' align='center'><img src='/images/ico_mail2.gif'></td>
                                      <td height='17'><a href='mailto:$EMAIL' class='link_sm'>$EMAIL</a></td>
                                      <td width='110' align='right'><a href='javascript:window.scroll(0,0);void(0);' class='text_sm'>&raquo; lap tetejére &laquo;</a></td>
                                      <td width='5'></td>
                                    </tr>
                                  </table></td>
                                </tr>
                              </table></td>
                            </tr>
                            <tr>
                              <td height='15'></td>
                            </tr>
                          </table>

";
                return $Vissza;
          }

/*          function TagCsere($Szoveg)
          {
                $Szoveg=str_replace(":)","<img src='/images/smile.gif'>",$Szoveg);
                $Szoveg=str_replace(":D","<img src='/images/biggrin.gif'>",$Szoveg);
                $Szoveg=str_replace(";)","<img src='/images/wink.gif'>",$Szoveg);
                $Szoveg=str_replace(":(","<img src='/images/frown.gif'>",$Szoveg);
                return CAlap::TagCsere($Szoveg);

          }*/


        function MutatSor()
        {
/*1 Sor megmutatás felh*/
                $Keszult=$this->KeszultAd();
                $KESZULT=substr($Keszult,0,10);
                $NEV=$this->TablaAdatKi("NEV");
                $EMAIL=$this->TablaAdatKi("EMAIL");
                $SZOVEG=$this->TagCsere($this->TablaAdatKi("SZOVEG"));

                $Vissza="
<table width='100%' border='0' cellspacing='0' cellpadding='0'>
                            <tr>
                              <td><table width='100%' border='0' cellspacing='0' cellpadding='0' class='pic_border'>
                                <tr>
                                  <td class='bg_color_5'><table width='100%' border='0' cellspacing='0' cellpadding='0'>
                                    <tr>
                                      <td width='18' align='center'><img src='/images/bullet.gif' width='7' height='7'></td>
                                      <td height='19'><span class='text'><b>$NEV</b></span></td>
                                      <td width='170' align='right'><span class='text_sm'>$KESZULT</span></td>
                                      <td width='5'></td>
                                    </tr>
                                  </table></td>
                                </tr>
                                <tr>
                                  <td height='1' class='bg_color_2'></td>
                                </tr>
                                <tr>
                                  <td class='bg_color_3'><table width='100%' border='0' cellspacing='0' cellpadding='0'>
                                    <tr>
                                      <td width='5'></td>
                                      <td valign='top'><table width='100%' border='0' cellspacing='0' cellpadding='0'>
                                        <tr>
                                          <td height='6'></td>
                                        </tr>
                                        <tr>
                                          <td height='22' valign='top'><span class='txt_main'>$SZOVEG</span></td>
                                        </tr>
                                        <tr>
                                          <td height='6'></td>
                                        </tr>
                                      </table></td>
                                      <td width='5'></td>
                                    </tr>
                                  </table></td>
                                </tr>
                                <tr>
                                  <td height='1' class='bg_color_2'></td>
                                </tr>
                                <tr>
                                  <td class='bg_color_5'><table width='100%' border='0' cellspacing='0' cellpadding='0'>
                                    <tr>
                                      <td width='18' align='center'><img src='/images/ico_mail2.gif'></td>
                                      <td height='17'><a href='mailto:$EMAIL' class='link_sm'>$EMAIL</a></td>
                                      <td width='110' align='right'><a href='javascript:window.scroll(0,0);void(0);' class='text_sm'>&raquo; lap tetejére &laquo;</a></td>
                                      <td width='5'></td>
                                    </tr>
                                  </table></td>
                                </tr>
                              </table></td>
                            </tr>
                            <tr>
                              <td height='15'></td>
                            </tr>
                          </table>

";
         return $Vissza;
          }

}



?>
