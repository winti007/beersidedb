<?php




class CProjektCsoport extends CCsoport
{
        
        function UjCsoport_fut()
        {
                $Obj=$this->UjObjektumLetrehoz("CAlProjektCsoport","CSOPORT");
                return $this->Futtat($Obj)->UrlapKi_fut();
        }


       
        function Felsomenube($Kinyit=0)
        {
            $Vissza["Link"]=$this->EsemenyHozzad("");
            $Vissza["Ujablak"]=false;
            
            $Vissza["Nev"]=$this->NevAd();
            $Vissza["Futotte"]=$this->Mostfutotte();
            
            $Vissza["Azon"]=$this->AzonAd();
            
            /*
            if ($Kinyit<2)
            {
               
                    $Ered=$this->Futtat($this->Gyerekparam("CSOPORT",null,null,null," and AKTIV_I='1'"))->Felsomenube($Kinyit+1);
                    $Vissza["Menu"]=$Ered["Eredm"];
                    
            }*/
           
            return $Vissza;
        } 
        
        function Kereses_pb_fut()
        {
            $SZOBA_TOL=$this->Postgetv_jegyez("SZOBA_TOL");
            $SZOBA_IG=$this->Postgetv_jegyez("SZOBA_IG");
            $MERET_TOL=$this->Postgetv_jegyez("MERET_TOL");
            $MERET_IG=$this->Postgetv_jegyez("MERET_IG");

            $Felt="";
            if (($SZOBA_TOL!="")&&("$SZOBA_TOL"!="0"))$Felt.=" and SZOBASZAM_KERES_I>='$SZOBA_TOL'";
            if (($SZOBA_IG!="")&&("$SZOBA_IG"!="0"))$Felt.=" and SZOBASZAM_KERES_I<='$SZOBA_IG'";
  //          if (($MERET_TOL!="")&&("$MERET_TOL"!="0"))$Felt.=" and ALAPTERULET_HASZN_F>='$MERET_TOL'";
//            if (($MERET_IG!="")&&("$MERET_IG"!="0"))$Felt.=" and ALAPTERULET_HASZN_F<='$MERET_IG'";
            if (($MERET_TOL!="")&&("$MERET_TOL"!="0"))$Felt.=" and ALAPTERULET_FULL_F>='$MERET_TOL'";
            if (($MERET_IG!="")&&("$MERET_IG"!="0"))$Felt.=" and ALAPTERULET_FULL_F<='$MERET_IG'";

          
                $AZON=$this->AzonAd();
                $Vlink=$this->Mutatvisszalink();
                if ($Vlink!="")$Vissza["Vissza"]=$Vlink;
                
                $Vissza["Almenuk"]=$this->Sablonbe("Menumunkateruletre",$this->Adatlistkozep_publ($Felt));



                $Vissza["Tartalom"]="";
                if ($this->Tobbnyelvu())
                {
                    $BEVEZETO=$this->TablaAdatKi("BEVEZETO_".$this->Nyelvadpub()."_S");
                }
                else $BEVEZETO=$this->TablaAdatKi("BEVEZETO_S");
                
                $Vissza["Bevezeto"]=$BEVEZETO;
                $Vissza["Cim"]="Keresés eredménye";
                
                //$Vissza["Vissza"]=$this->VisszaEsemenyAd();
                

                return $this->Sablonbe("Oldal",$Vissza);


        }
              
        function Mutat_pb_fut()
        {
                $NEV=$this->NevAd();
                $SZOVEG=$this->SzovegCserel();
                
                
                $AZON=$this->AzonAd();
                $Vlink=$this->Mutatvisszalink();
                if ($Vlink!="")$Vissza["Vissza"]=$Vlink;
                
                $Vissza["Almenuk"]=$this->Sablonbe("Menumunkateruletre",$this->Adatlistkozep_publ());



                $Vissza["Tartalom"]="";
                if ($this->Tobbnyelvu())
                {
                    $BEVEZETO=$this->TablaAdatKi("BEVEZETO_".$this->Nyelvadpub()."_S");
                }
                else $BEVEZETO=$this->TablaAdatKi("BEVEZETO_S");
                
                $Vissza["Bevezeto"]=$BEVEZETO;
                $Vissza["Cim"]=$NEV;
                
                //$Vissza["Vissza"]=$this->VisszaEsemenyAd();
                

                return $this->Sablonbe("Oldal",$Vissza);

        }
        
        function Adatlistkozep_publ($Ingfelt="")
        {
            $Vissza["Link"]=$this->EsemenyHozzad("");
            
            $Vissza["Bevezeto"]=$this->TablaAdatKi("BEVEZETO_S");      
            $Vissza["Azon"]=$this->AzonAd();      
            $Vissza["Szoveg"]=$this->TablaAdatKi("SZOVEG_S");
            $Vissza["Garazs"]=$this->TablaAdatKi("GARAZSE_I");
            
            $Vissza["Nev"]=$this->NevAd();
            $Vissza["Kep"]=$this->Eleres_allom("LISTAKEP");
            
            $Ered=$this->Futtatgy("CSOPORT",null,null,null," and AKTIV_I='1'")->Adatlistkozep_publ($Ingfelt);
                
            $Vissza["Almenuk"]=$Ered["Eredm"];
            
            $Ered=$this->Futtatgy("INGATLAN",null,null,null,$Ingfelt)->Adatlistkozep_publ();
            $Vissza["Ingatlanok"]=$Ered["Eredm"];
            
              
            return $Vissza;
        }
        
        
        function Adatlist_adm_tag()
        {
                $Vissza["URLAP"]=1;
                $Vissza["LISTA"]=1;
                $Vissza["TEKINT"]=1;
                return $Vissza;
        }
          
          
    

        function Adat_adm_menu()
        {
            $Vissza=array();
            $ITEM["Nev"]="Új csoport";
            $ITEM["Link"]=$this->EsemenyHozzad("UjCsoport_fut");
            $Vissza[]=$ITEM;
            return $Vissza;
             
        }
        
         function Lista_fut()
        {
  //          $Honnan=$this->Postgetv_jegyez("Honnan",0);
            
            $Data["Lista"]=$this->Futtat($this->Gyerekparam("CSOPORT!DOKUMENTUM","VZ_SORREND_I",1,null,null,1))->Adatlist_adm();
           
            $Vissza["Tartalom"]=$this->Sablonbe("Lista",$Data);
            $Data2["Lista"]=$this->Futtat($this->Gyerekparam("INGATLAN","VZ_SORREND_I",1,null,null,1))->Adatlist_adm();
            $Data2=array_merge($Data2,$this->Adat_adm());

            $Vissza["Tartalom"].=$this->Sablonbe("Lista",$Data2);

            $Vissza["Cim"]=$this->AdminNevAd();
            return $this->Sablonbe("Oldal_admin",$Vissza);
        }
        

          

}


class CAlProjektCsoport extends CProjektCsoport
{
//lépcsőház    


        function Ujingatlan_fut()
        {
                $Obj=$this->UjObjektumLetrehoz("CIngatlan","INGATLAN");
                return $this->Futtat($Obj)->UrlapKi_fut();
        }
        
        

       
        function Felsomenube($Kinyit=false)
        {
            $Szul=$this->SzuloObjektum();
            $Azon=$this->AzonAd();
            $Vissza["Link"]=$Szul->EsemenyHozzad("")."#epulet".$Azon;
            $Vissza["Nev"]=$this->NevAd();
            $Vissza["Futotte"]=$this->Mostfutotte();
            
            
            
            if ($Kinyit)
            {
                //$Futott=$this->Mostfutobj();
                $Ered=$this->Futtat($this->Gyerekparam("CSOPORT",null,null,null," and AKTIV_I='1'"))->Felsomenube(false);
                    
                $Vissza["Menu"]=$Ered["Eredm"];
            }
           
            return $Vissza;
        } 
        
        
        function Urlapplusz(&$Form)
        {
            $Form->Checkbox("Garázs e:","GARAZSE_I",$this->TablaAdatKi("GARAZSE_I"),"");     
        }

        function Urlapplusz_tarol()
        {
            $this->TablaAdatBe("GARAZSE_I",$this->Postgetv("GARAZSE_I",1));    
        }

        function Garazse()
        {
            return $this->TablaAdatKi("GARAZSE_I");
        }

        function Adatlist_adm_tag()
        {
                $Vissza["URLAP"]=1;
                $Vissza["LISTA"]=1;
                $Vissza["TEKINT"]=1;
                $Vissza["TOROL"]=1;
                return $Vissza;
        }
                
                
        function Adat_adm_menu()
        {
            $Vissza=array();
            $ITEM["Nev"]="Új ingatlan";
            $ITEM["Link"]=$this->EsemenyHozzad("Ujingatlan");
            $Vissza[]=$ITEM;
            return $Vissza;
             
        }
        


}



class CIngatlan extends CVaz_bovit
{
        var $Tabla_nev="INGATLAN";    
        

function Tablaletre()
{
$SQL="
CREATE TABLE `INGATLAN` (
  `AZON_I` int(13) NOT NULL auto_increment,
  `AZONOSITO_S` char(35) default '',
  `EMELET_S` char(40) default '',
  `SZOBASZAM_S` char(70) default '',
  `ALAPTERULET_HASZN_F` decimal(10,2) default '0.00',
  `ALAPTERULET_FULL_F` decimal(10,2) default '0.00',
  `ERKELY_S` char(70) default '',
  `STATUSZ_I` tinyint(1) default '0',
  `LEIRAS_S` text,
  PRIMARY KEY  (`AZON_I`)
) ENGINE=innodby
";

}


        function NevAd()
        {
            return $this->TablaAdatKi("AZONOSITO_S")." ".$this->TablaAdatKi("EMELET_S");
        }

        function Adatlist_adm_tag()
        {
                $Vissza["URLAP"]=1;
                $Vissza["TOROL"]=1;
                return $Vissza;
        }
          
          
        function Garazse()
        {
            $Szul=$this->SzuloObjektum();
            return $Szul->TablaAdatKi("GARAZSE_I");
        }



        function Adatlistkozep_publ()
        {
            $Vissza=$this->OsszesTablaAdatVissza();
            $Vissza["Alaprajz"]=$this->Eleres_allom("ALAPRAJZ");
            return $Vissza;
        }
        
        function UrlapKi_fut()
        {
                $Cim="Ingatlan szerkesztése";
                $Tarol=$this->EsemenyHozzad("Tarol");



                $this->ScriptTarol("
                function Ellenor()
                {
                        if(document.TermekForm.NEV.value=='')
                        {
                                alert('A név mező nem lehet üres!');
                                document.TermekForm.NEV.focus();
                        }else
                        return true;
                        return false;
                }
                ");



                $Form=new CForm("TermekForm","","");
                $Form->Hidden("Esemeny_Uj","$Tarol");
                $Form->TextBox("Azonosító:","AZONOSITO_S",$this->TablaAdatKi("AZONOSITO_S"),"");
                if ($this->Garazse())
                {
                    $Form->TextBox("Hasznos alapterület (m2):","ALAPTERULET_HASZN_F",$this->TablaAdatKi("ALAPTERULET_HASZN_F"),"");
                    $Form->TextBox("Teljes alapterület (m2):","ALAPTERULET_FULL_F",$this->TablaAdatKi("ALAPTERULET_FULL_F"),"");
                    $Form->Kombobox("Státusz","STATUSZ_I",$this->TablaAdatKi("STATUSZ_I"),"",ING_STATUSZ,"");
                }else
                {
                    $Form->TextBox("Emelet:","EMELET_S",$this->TablaAdatKi("EMELET_S"),"");

                    $Form->TextBox("Szobák száma:","SZOBASZAM_S",$this->TablaAdatKi("SZOBASZAM_S"),"");
                    $Form->TextBox("Szobák száma keresőnek:","SZOBASZAM_KERES_I",$this->TablaAdatKi("SZOBASZAM_KERES_I"),"");
                    $Form->TextBox("Hasznos alapterület (m2):","ALAPTERULET_HASZN_F",$this->TablaAdatKi("ALAPTERULET_HASZN_F"),"");
                    $Form->TextBox("Teljes alapterület (m2):","ALAPTERULET_FULL_F",$this->TablaAdatKi("ALAPTERULET_FULL_F"),"");
                    $Form->TextBox("Erkély:","ERKELY_S",$this->TablaAdatKi("ERKELY_S"),"");
                    $Form->Kombobox("Státusz","STATUSZ_I",$this->TablaAdatKi("STATUSZ_I"),"",ING_STATUSZ,"");
                    $Form->Allomanybe("Alaprajz","ALAPRAJZ",$this->Eleres_allom("ALAPRAJZ"),"");
                }

                    $Form->TextBox("m<sup>2</sup> beszúrás","TT","m<sup>2</sup>","");
                
                $Form->Szabad2(" ",$Form->Gomb_s("Tárol","return Ellenor();","submit","Submit")." ".$Form->Gomb_s("Mégsem","location.href='".$this->VisszaEsemenyAd()."'","button"));

                $Tartalom=$Form->OsszeRak();
                
                return $this->Sablonbe("Oldal_admin",array("Cim"=>$Cim,"Tartalom"=>$Tartalom));

        }
        


  
        function Tarol_fut()
        {
                $this->TablaAdatBe("AZONOSITO_S",$this->Postgetv("AZONOSITO_S"));
                
                if ($this->Garazse())
                {
                }else
                {
                        $this->TablaAdatBe("EMELET_S",$this->Postgetv("EMELET_S"));
                        $this->TablaAdatBe("SZOBASZAM_S",$this->Postgetv("SZOBASZAM_S"));
                        $this->TablaAdatBe("ERKELY_S",$this->Postgetv("ERKELY_S"));
                        $this->TablaAdatBe("SZOBASZAM_KERES_I",$this->Postgetv("SZOBASZAM_KERES_I"));

                        $this->Allom_tarol("ALAPRAJZ","ALAPRAJZ");
                }
            
                        $this->TablaAdatBe("STATUSZ_I",$this->Postgetv("STATUSZ_I"));
                        

                        $ALAPTERULET_HASZN_F=$this->Postgetv("ALAPTERULET_HASZN_F");
                        $ALAPTERULET_HASZN_F=str_replace(",",".",$ALAPTERULET_HASZN_F);
                        $this->TablaAdatBe("ALAPTERULET_HASZN_F",$ALAPTERULET_HASZN_F);

                        $ALAPTERULET_FULL_F=$this->Postgetv("ALAPTERULET_FULL_F");
                        $ALAPTERULET_FULL_F=str_replace(",",".",$ALAPTERULET_FULL_F);
                        $this->TablaAdatBe("ALAPTERULET_FULL_F",$ALAPTERULET_FULL_F);

                        $this->Szinkronizal();


                        $jo=true;
                        if ($jo)
                        {
                                if ($Submit=="Képek szerkesztése")
                                {
                                        $Vissza=$this->KepLista();
                                }else
                                if ($Submit=="Listás kép törlése")
                                {
                                        $this->TablaSzinkronizal();
                                        $Kep=$this->GyerekekVissza("LISTASKEP","Novekvo",0);
                                        if ($Kep)$Kep[0]->RekurzivTorol();

                                        $Vissza=$this->UrlapKi();
                                }else
                                {
                                        $Vissza=$this->VisszaLep();
                                }
                        }else
                        {
                                $Vissza=$this->UrlapKi();
                        }

               

                return $Vissza;

        }
        

        function MutatSor(&$EMELET_NEV)
        {

                $NEV=$this->NevAd();

                $Kep=$this->GyerekekVisszaMezoSorrend("ALAPRAJZ","VZ_SORREND","Novekvo",0,0,1);
                $RAJZ_IR="";
                $KATTINT="";
                if ($Kep)
                {
                    $RAJZ_IR="<img src='/images/pdf.png'>";
//                    $RAJZ_IR="<a href='".$Kep[0]->Eleres()."' target='_blank'><img src='/images/pdf.png'></a>";
                    $KATTINT="window.open('".$Kep[0]->Eleres()."','_blank','')";
                }

                $JEL=$this->TablaAdatKi("JEL");
                $ING_JEL=$this->TablaAdatKi("ING_JEL");
                $SZOBASZAM=$this->TablaAdatKi("SZOBASZAM");
                $ALAPTERULET=$this->TablaAdatKi("ALAPTERULET");
                $ERKELY=$this->TablaAdatKi("ERKELY");
                if ($ERKELY<0.1)$ERKELY="-";
                $VETELAR=$this->TablaAdatKi("VETELAR");
                $STATUSZ=$this->TablaAdatKi("STATUSZ");
                $Reszletes=$this->EsemenyHozzad("MutatAlap");
                $VETELAR_KULCS=$this->TablaAdatKi("VETELAR_KULCS");
                $AR_IR="";
                if ($VETELAR>0)$AR_IR=$this->ArFormaz($VETELAR);

                $AR_IR2="";
                if ($VETELAR_KULCS>0)$AR_IR2=$this->ArFormaz($VETELAR_KULCS);


                if ($KEP_IR!="")
                {
                        $KEP_IR="<a href=\"$Reszletes\"><img src='".$KEP_IR."' border='0' class='pic_border'></a>";
                }
                if ("$STATUSZ"=="1")$Stat="statusz";
                    else $Stat="for_sale";
                    $LINK=$this->EsemenyHozzad("");

//<td>   <b>$AR_IR2</b>  </td>
                $Tartalom="   <tr onmouseout=\"$(this).find('.statusz > span').html('szabad');\" onmouseover=\"$(this).find('.statusz > span').html('részletek >');\" onclick=\" $KATTINT \" style='cursor:pointer;'>
                                <td><b>$EMELET_NEV </b></td>
                                <td>  $JEL  </td>
                                <td>  $ING_JEL  </td>
                                <td>   $SZOBASZAM   </td>
                                <td>   $ALAPTERULET   </td>
                                <td>   $ERKELY   </td>
                                
                                <td>   <b>$AR_IR</b>  </td>
                                <td class='$Stat'><span>".Tombertek(ING_STATUSZ,$STATUSZ)."</span></td>
                                <td>$RAJZ_IR</td>
                            </tr>
                ";
                $EMELET_NEV="";
                return $Tartalom;
        }
          



        function Mutat()
        {
                return $this->MutatAlap();
        }
        
        function Mutat_Alap()
        {
                return $this->MutatAlap();
        }

        function _Adatok(&$LEPCSOHAZ,&$EMELET,&$PROJ_NEVE)
        {
            $Emeletobj=$this->SzuloObjektum();
            $EMELET=$Emeletobj->NevAd();
            $Lepcsoobj=$Emeletobj->SzuloObjektum();
            $LEPCSOHAZ=$Lepcsoobj->NevAd();
            $Projobj=$Lepcsoobj->SzuloObjektum();
            $PROJ_NEVE=$Projobj->NevAd();
            
        }

        function MutatAlap()
        {
                $this->AllapotBe("Feladat","MutatAlap");
               $NEV=$this->NevAd();


                $JEL=$this->TablaAdatKi("JEL");
                $ING_JEL=$this->TablaAdatKi("ING_JEL");
                $SZOBASZAM=$this->TablaAdatKi("SZOBASZAM");
                $ALAPTERULET=$this->TablaAdatKi("ALAPTERULET");
                $ERKELY=$this->TablaAdatKi("ERKELY");
                if ($ERKELY<0.1)$ERKELY="-";
                    else $ERKELY.=" m<sup>2</sup>";
                $VETELAR=$this->TablaAdatKi("VETELAR");
                $STATUSZ=$this->TablaAdatKi("STATUSZ");
                $Reszletes=$this->EsemenyHozzad("MutatAlap");
                
                $AR_IR="";
                if ($VETELAR>0)$AR_IR=$this->ArFormaz($VETELAR);



                $this->_Adatok($LEPCSOHAZ,$EMELET,$PROJ_NEVE);
                if ("$STATUSZ"=="1")$Stat="statusz";
                    else $Stat="for_sale";
                    $LINK=$this->EsemenyHozzad("");

                $Kep=$this->GyerekekVisszaMezoSorrend("ALAPRAJZ","VZ_SORREND","Novekvo",0,0,1);
                $RAJZ_IR="";
                if ($Kep)
                {
                    $RAJZ_IR="                    <table class='details_datatable' border='0' cellspacing='0' cellpadding='0'>  
                        <tr>
                            <th class='tal'><b> $ING_JEL jelű lakásra</b> vonatkozó információk (letölthető)</th>
                        </tr>
                        <tr>
                            <td><a href='".$Kep[0]->Eleres()."' download='".$Kep[0]->Eleres()."' class='link'><img src='/images/pdf_ikon.gif' class='pdf'>Lakáskonyv $LEPCSOHAZ $EMELET $ING_JEL</a> (pdf)</td>
                        </tr>
                    
                    </table>
                    
                    ";
                }
                $LEIRAS=$this->TablaAdatKi("LEIRAS");
                //if ($LEIRAS!="")$LEIRAS="<p>$LEIRAS</p>";


                
                 $Tartalom= "
 <table class='details_datatable' border='0' cellspacing='0' cellpadding='0'>
                        <tr>
                            <th align='left' colspan='2'>$PROJ_NEVE </th>
                        </tr>
						<tr>
							<td>Ingatlan jele:</td>
							<td>".$LEPCSOHAZ." lépcsőház, ".$EMELET."  <b>$ING_JEL</b> jelű lakás</td>
						</tr>
						<tr>
							<td>Alapterület:</td>
							<td>
								<table class='inner_table' border='0' cellspacing='0' cellpadding='0'>
									<tr>
										<td>Lakás:</td><td> <b>$ALAPTERULET m<sup>2</sup></b></td>
									</tr>
									<tr>
										<td>Erkély:</td><td> <b>$ERKELY </b></td>
									</tr>
								</table>
							</td>
						</tr>
                        <tr>
                            <td>Szobaszám:</td>
                            <td >$SZOBASZAM </td>
                        </tr>
                        <tr>
                            <td>Vételár:</td>
                            <td class='tar price'>$AR_IR </td>
                        </tr>
                    </table>
                  $RAJZ_IR

               
					$LEIRAS
                 ".$this->VisszaLinkAd();
                $Cim=$this->NevAd();
                return MunkaTerulet($Cim,$Tartalom);
        }
        

        

}
?>
