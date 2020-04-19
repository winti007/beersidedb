<?php



class CNyelvElagazCsoport extends CCsoport
{
       
       function Jelenhetnavba()
        {
            return false;
        }
       

        function Urlapki_fut()
        {

                $Tarol=$this->EsemenyHozzad("Tarol");

                $Form=new CForm2("CsoportForm",$this->EsemenyHozzad("Tarol"),"");
                $Form->Textbox("Név:","NEV_S",$this->TablaAdatKi("NEV_S"),"");
                self::Urlaplink($Form);
                
                $Form->Szabad2("","Keresőknek szóló információk");
                $Form->Area("Title:","KERESO_TITLE_HU_S",$this->TablaAdatKi("KERESO_TITLE_HU_S"),"");
                $Form->Area("Keywords:","KERESO_KEY_HU_S",$this->TablaAdatKi("KERESO_KEY_HU_S"),"");
                $Form->Area("Description:","KERESO_DESC_HU_S",$this->TablaAdatKi("KERESO_DESC_HU_S"),"");
                $Form->Szabad2(" ",$Form->Gomb_s("Tárol","return true","submit","Submit")." ".$Form->Gomb_s("Mégsem","location.href='".$this->VisszaEsemenyAd()."'","button"));

                $Tartalom=$Form->OsszeRak();
                $Cim=$this->NevAd()." szerkesztése";
                return $this->Sablonbe("Oldal_admin",array("Cim"=>$Cim,"Tartalom"=>$Tartalom));
        }


        function Tarol_fut()
        {

            $Submit=$this->Postgetv("Submit");
            $this->TablaAdatBe("KERESO_TITLE_HU_S",$this->Postgetv("KERESO_TITLE_HU_S"));
            $this->TablaAdatBe("KERESO_KEY_HU_S",$this->Postgetv("KERESO_KEY_HU_S"));
            $this->TablaAdatBe("KERESO_DESC_HU_S",$this->Postgetv("KERESO_DESC_HU_S"));

            $this->TablaAdatBe("NEV_S",$this->Postgetv("NEV_S"));
            $this->Urlaplinktarol();

            $this->Szinkronizal();
            
            return $this->Futtat($this)->Lista_fut();
            
        }  
        
        function Adat_adm_menu()
        {
            $Vissza[0]["Nev"]="Kulcsszavak szerkesztése";
            $Vissza[0]["Link"]=$this->EsemenyHozzad("UrlapKi");
            return $Vissza;
        }


}
class CNyelvForditCsoport extends CCsoport
{
/*A nyelveknél ezek az osztályok vannak:
-CNyelvForditCsoport - Minden szót tartalmaz. Ő csinálja a forditás, karbantartást.
    -CTobbNyelvuSzovegCsoport -Egy adott szó van benne az összes nyelven. Adott szóról visszaadja az aktuális nyelvüt. Kapcsolódik a CNyelvForditCsoport-hoz.
        -CEgyszo - Kapcsolódik a CTobbNyelvuSzovegCsoport-hoz, egy szó egy nyelven.     */

        
 
        

 /**
 * _Nyelvbe  - nyelvet állítja be, ha nem létezik default nyelvet, vagy Lang get/post paraméterből
 * a kódok  NYELVEK paraméter értékeit vehetik fel
 
 */         
        protected function _Nyelvbe()
        {
                $Lang=$this->Postgetv("Lang");
                if (isset($Lang))
                {
                        $Nyelvek=explode("!",NYELVEK);
                        if (in_array($Lang,$Nyelvek))
                        {
                                $this->Sessbe("Nyelv",$Lang);
                        }
                }
                $Nyelv=$this->Sessad("Nyelv");
                if ("$Nyelv"=="")
                {
                    $this->Sessbe("Nyelv","HU");
                }
        }
        
 /**
 * Nyelvadpubl  - visszaadja az aktuális nyelv kódját, a kódok  NYELVEK paraméter értékeit vehetik fel
 * @return srring 
 */         
        public function Nyelvadpubl()
        {
            
            $this->_Nyelvbe();
            $Nyelv=$this->Sessad("Nyelv");
            return $Nyelv;
        }

        
        function Cserel($Szoveg)
        {
            $Nyelv=$this->Nyelvadpubl();
            
            $KezdJel="@@";
            $KezdJel.="@";
            $VegJel="§§";
            $VegJel.="§";
            $KezdPozicio=strpos($Szoveg,$KezdJel);
            $VegPozicio=strpos($Szoveg,$VegJel);

                                
                preg_match_all('/'.$KezdJel.'(?s).*'.$VegJel.'/sUim',$Szoveg,$SzovegSzet);
                if ($SzovegSzet)
                {
                        $Tomb=$SzovegSzet[0];
                        foreach ($Tomb as $egy)
                        {
                            $Helyettesitendo=$egy;
                            
                            $Megvan=$this->Futtat($this->Gyerekparam("CSOPORT",null,null,null," and NEV_S='$Helyettesitendo'"))->SzovegAktNyelvenVissza();
                            $Mehetszam=$Megvan["Ossz"];
//                            var_dump($Megvan["Eredm"][0]);
  //                          echo "$Helyettesitendo <br>";

                            if ($Mehetszam>0)
                            {
                                $Szoveg=str_replace($Helyettesitendo,$Megvan["Eredm"][0],$Szoveg);    
                            }else
                            {
                                $TobbNyelvSzoveg=$this->UjObjektumLetrehoz("CTobbNyelvuSzovegCsoport","CSOPORT");
                                $TobbNyelvSzoveg->Beallit($Helyettesitendo);
                                
                                $Szoveg=str_replace($Helyettesitendo,"Nincs szöveg!",$Szoveg);    
                            }   

                                                            
                        }
                }
                
              
                return $Szoveg;
        }



        function Lista_fut()
        {
                
                $Mitkeres=$this->Postgetv_jegyez("Mitkeres","");


                $Form=new CForm2("Keres","","");
                $Form->Hidden("Esemeny_Uj",$this->EsemenyHozzad("Lista"));
                $Form->Hidden("HonnanLi",0);
                $Form->TextBox(" ","Mitkeres",$Mitkeres,"","<input type=submit value='Keresés' class='butt_kosarba formbutt'>");


                
                $Vissza["Tartalom"]=$Form->OsszeRak();
                $Felt="";
                if ($Mitkeres!="")
                {
                    $Mitkeres=strtolower($Mitkeres);
                    $Felt=" and (LOWER(NEV_S) like '%$Mitkeres%') ";
                }

                $Cim=$this->NevAd();

                $Data["Lista"]=$this->Futtatgy("CSOPORT","NEV_S",1,70,$Felt)->Adatlist_adm();

                $Data=array_merge($Data,$this->Adat_adm());
            
                $Vissza["Tartalom"].=$this->Sablonbe("Lista",$Data);


                    
                $Vissza["Cim"]=$this->AdminNevAd();
                return $this->Sablonbe("Oldal_admin",$Vissza);
    

        }
        
        function Adat_adm_menu()
        {
            $Vissza=array();
               if ($this->Sessad("Aktfelh")->Jogosultsag()>99)
               {
                       $Vissza[0]["Link"]=$this->EsemenyHozzad("Beolvas");
                       $Vissza[0]["Nev"]="Karbantartó rutin futtatása";
               }
            return $Vissza;
            
        }

        function KonyvtarakAd($Konyvtar)
        {

          $handle = opendir($Konyvtar);
          $vissza=false;
          while (false !== ($file = readdir($handle)))
          {
                if ($file != "." && $file != "..")
                {
                        if (is_dir($Konyvtar.chr(47).$file))
                        {
                                $vissza.=$this->KonyvtarakAd($Konyvtar.chr(47).$file);
                        }
                        else
                        {
                                if (mb_substr($file,strlen($file)-4)==".php")
                                {
                                       $vissza.="$Konyvtar".chr(47)."$file!";
                                }
                        }
                 }
          }
          closedir($handle);
          return $vissza;
        }


        function Beolvas_fut()
        {
                $this->AllapotBe("OsszSzoveg",array());
                $this->AllapotBe("AktualisFajl","0");
                $Fordit=$this->EsemenyHozzad("Feldolgozas");
                
                $Filek=$this->KonyvtarakAd(getcwd()."/php/");
                if ($Filek)
                {
                        $Filek=mb_substr($Filek,0,strlen($Filek)-1,STRING_CODE);
                }
                $PhpFajlok=explode("!",$Filek);
                $this->AllapotBe("PhpFajlok",$PhpFajlok);
                $this->ScriptTarol("
                function Mehet()
                {
                        location.href='$Fordit';
                }
                setTimeout('Mehet();',700);

               ");
                return $this->Sablonbe("Oldal_admin",array());
        }

        function Feldolgozas_fut()
        {

                $PhpFajlok=$this->AllapotKi("PhpFajlok");
                $OsszSzoveg=$this->AllapotKi("OsszSzoveg");
                if (!$OsszSzoveg)$Osszszoveg=false;

                $AktualisFajl=$this->AllapotKi("AktualisFajl");
                if (($AktualisFajl)&&((!$PhpFajlok)||(!isset($PhpFajlok[$AktualisFajl]))))
                {
                        return $this->RegiTorol();
                }

                $KezdJel="@@";
                $KezdJel.="@";
                $VegJel="§§";
                $VegJel.="§";
                $db=count($PhpFajlok);
                $Tartalom="";
                for ($v=0;$v<$db;$v++)
                {
                        $Tartalom.="<br>";
                        if ($AktualisFajl==$v)
                        {
                                $Tartalom.="<b>";
                        }
                        $Tartalom.=$PhpFajlok[$v];
                        if ($AktualisFajl==$v)
                        {
                                $Tartalom.="</b>";
                        }
                }

                if (!$Forras=fopen($PhpFajlok[$AktualisFajl],"r"))
                {
                        $Tartalom.= "Nem tudom megnyitni a fájlt - ".$PhpFajlok[$AktualisFajl]."!<br>";
                }
                else
                {
                        $Meret=filesize($PhpFajlok[$AktualisFajl]);
                        $FajlTartalom = fread($Forras, $Meret);

//                        $KezdPozicio=mb_strpos($FajlTartalom,$KezdJel,null,STRING_CODE);
                        $KezdPozicio=mb_strpos($FajlTartalom,$KezdJel);
                        $VegPozicio=mb_strpos($FajlTartalom,$VegJel);

                        preg_match_all('/'.$KezdJel.'(?s).*'.$VegJel.'/sUim',$FajlTartalom,$SzovegSzet);
                        if ($SzovegSzet)
                        {
                            $Tomb=$SzovegSzet[0];
                            foreach ($Tomb as $egy)
                            {
                                $Helyettesitendo=$egy;
                                $FajlTartalom=str_replace($Helyettesitendo,"Kamu szöveg",$FajlTartalom);
                                $OsszSzoveg[]=$Helyettesitendo;
                            }
                        }


                        fclose($Forras);
               }
               $Fordit=$this->EsemenyHozzad("Feldolgozas");
               $this->AllapotBe("AktualisFajl",$this->AllapotKi("AktualisFajl")+1);
               $this->AllapotBe("OsszSzoveg",$OsszSzoveg);
               $this->ScriptTarol("
                function Mehet()
                {
                        location.href='$Fordit';
                }
                setTimeout('Mehet();',700);

               ");
                return $this->Sablonbe("Oldal_admin",array("Tartalom"=>$Tartalom));               
        }

        function RegiTorol()
        {
                $OsszSzoveg=$this->AllapotKi("OsszSzoveg");
                if (!$OsszSzoveg)$Osszszoveg=false;


                $Tartalom="Kész van! Most törlöm a régieket.<br>";
                
                $Ossz=$this->Futtat($this->Gyerekparam("CSOPORT"))->Karbatarthoz();
                $OsszSzovegAdatbazis=$Ossz["Eredm"];
                $Mennyivan=0;
                if ((isset($OsszSzoveg))&&(is_array($OsszSzoveg)))
                {
                        $OsszSzoveg=array_unique($OsszSzoveg);
                        foreach ($OsszSzoveg as $Szo)
                        {
                                $volt=0;
                                if ($OsszSzovegAdatbazis)
                                {
                                        $db=count($OsszSzovegAdatbazis);
                                        $c=0;
                                        while (($c<$db)&&($volt==0))
                                        {
                                                if ($OsszSzovegAdatbazis[$c]["Def"]=="$Szo")
                                                {
                                                        $volt=1;
                                                        $OsszSzovegAdatbazis[$c]["Volt"]=1;
                                                }
                                                $c++;
                                        }
                                        if ("$volt"=="0")$Mennyivan++;

                                }
                                if ($volt==0)
                                {
                                      $TobbNyelvSzoveg=$this->UjObjektumLetrehoz("CTobbNyelvuSzovegCsoport","CSOPORT");
                                      $TobbNyelvSzoveg->Beallit($Szo);
                                }
                        }
                }
                if ($Ossz["Ossz"]>0)
                {
                        $db=count($OsszSzovegAdatbazis);
                        if ($Mennyivan==$db)
                        {
                                echo "Valószinü hiba, mindent töröltem volna";
                                exit;
                        }
                        for ($c=0;$c<$db;$c++)
                        {
                                if ($OsszSzovegAdatbazis[$c]["Volt"]==0)
                                {
                                        $OsszSzovegAdatbazis[$c]["Obj"]->RekurzivTorol();
                                }
                        }
                }

                $OsszSzovegAdatbazis=NULL;
                $this->AllapotBe("OsszSzoveg",0);
                $Tartalom.="Kész van!<br>";

                $Link=$this->EsemenyHozzad("Lista_fut");
               $this->ScriptTarol("
                function Mehet()
                {
                        location.href='$Link';
                }
                setTimeout('Mehet();',1000);

               ");
                return $this->Sablonbe("Oldal_admin",array("Tartalom"=>$Tartalom));
        }

}

class CTobbNyelvuSzovegCsoport extends CCsoport
{
/*A nyelveknél ezek az osztályok vannak:
-CNyelvForditCsoport - Minden szót tartalmaz. Ő csinálja a forditás, karbantartást.
    -CTobbNyelvuSzovegCsoport -Egy adott szó van benne az összes nyelven. Adott szóról visszaadja az aktuális nyelvüt.
                               Kapcsolódik a CNyelvForditCsoport-hoz.
        -CEgyszo - Kapcsolódik a CTobbNyelvuSzovegCsoport-hoz, egy szó egy nyelven.     */

        var $Tabla_nev="FONYELV";                


        public function Tablasql()
        {
//elsofutasba is benne van            
            $SQL="

CREATE TABLE IF NOT EXISTS `FONYELV` (
  `AZON_I` int(13) NOT NULL AUTO_INCREMENT,
  `NEV_S` varchar(255) DEFAULT '',
  PRIMARY KEY (`AZON_I`)

) ENGINE=".MYSQL_ENGINE."§ 

ALTER TABLE `FONYELV` CHANGE `NEV_S` `NEV_S` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL§

create index NEV_S on FONYELV(NEV_S)";
            return $SQL;    
        }    

        function Karbatarthoz()
        {
            $Vissza["Obj"]=$this;
            $Vissza["Volt"]=0;
            $Ered=$this->Futtat($this->Gyerekparam("DEF"))->TartalomAd();
            $Vissza["Def"]=$Ered["Eredm"][0];
            return $Vissza;
        }



        function Beallit($NevAzon)
        {

                $KezdJel="@@";
                $KezdJel.="@";
                $VegJel="§§";
                $VegJel.="§";
                            
                $this->TablaAdatBe("NEV_S",$NevAzon);
                $this->Szinkronizal();
                $NevAzon=str_replace($KezdJel,"",$NevAzon);
                $NevAzon=str_replace($VegJel,"",$NevAzon);
                
                $EgySzoveg=$this->UjObjektumLetrehoz("CEgySzoveg","DEF");
                $EgySzoveg->TartalomBe($NevAzon);
        }



        function SzovegAktNyelvenVissza()
        {

                $Vissza="";
                $AktNyelvSzoveg=$this->Futtat($this->Gyerekparam($this->Nyelvadpub()))->TartalomAd();
                if ($AktNyelvSzoveg["Ossz"]>0)
                {
                    $Vissza=$AktNyelvSzoveg["Eredm"][0];    
                }
                if ($Vissza=="")
                {
                    $AktNyelvSzoveg=$this->Futtat($this->Gyerekparam("DEF"))->TartalomAd();
                    if ($AktNyelvSzoveg["Ossz"]>0)
                    {
                        $Vissza=$AktNyelvSzoveg["Eredm"][0];
                    }else $Vissza="";   
                }
                return $Vissza;

        }




        function Urlapki_fut()
        {

                $OsszNyelv=explode("!",NYELVEK);

                $NyelvDb=count($OsszNyelv);

                $Esemeny_id=$this->EsemenyHozzad("Tarol_fut");
                
                $Form=new CForm2("ForditasForm","","");
                $Form->Hidden("Esemeny_Uj",$Esemeny_id);
                $Form->Szabad2(" ","HU: Magyar  EN:Angol");
                $Form->Szabad2(" ","DEF: Alapértelmezett érék, akkor jelenik meg ha nincs fordítás");

                $Ered=$this->Futtat($this->Gyerekparam("DEF"))->Tartalomad();
                $Form->Szabad2("Default:",$Ered["Eredm"][0]);

                for($v=0;$v<$NyelvDb;$v++)
                {
                    $Nyelven=$this->Futtat($this->Gyerekparam($OsszNyelv[$v]))->Tartalomad();
                    if($Nyelven["Ossz"]>0)$Nyelvszov=$Nyelven["Eredm"][0];
                                     else $Nyelvszov="";
                        $Form->Textbox($OsszNyelv[$v],"Szavak[]",$Nyelvszov,"size=40");

                }
                $Form->Szabad2(" ",$Form->Gomb_s("Tárol","return true","Submit")." ".$Form->Gomb_s("Mégsem","location.href='".$this->VisszaEsemenyAd()."'"));

                $Cim="Fordítás";
                $Tartalom=$Form->OsszeRak(" ");

                return $this->Sablonbe("Oldal_admin",array("Cim"=>$Cim,"Tartalom"=>$Tartalom));
        }

        function Tarol_fut()
        {
                $Szavak=$this->Postgetv("Szavak");

                        $OsszNyelv=explode("!",NYELVEK);
                        $NyelvDb=count($OsszNyelv);
                        for($v=0;$v<$NyelvDb;$v++)
                        {
                            $Elem=$this->Futtat($this->Gyerekparam($OsszNyelv[$v]))->TartalomBe($Szavak[$v]);
                            if($Elem["Ossz"]<1)
                            {
                                $Elem=$this->UjObjektumLetrehoz("CEgySzoveg",$OsszNyelv[$v]);
                                $Elem->TartalomBe($Szavak[$v]);
                            }
                        }
                
                $Vissza=$this->VisszaLep();
                return $Vissza;

        }

        function Adatlist_adm_tag()
        {
            $Vissza["URLAP"]=1;
            return $Vissza;    
        }
        
        function Adminnevad()
        {
            $Vissza="";
            $Volt=$this->Futtat($this->Gyerekparam("DEF"))->TartalomAd();
            if ($Volt["Ossz"]>0)$Vissza.=$Volt["Eredm"][0];


            $Nincsnyelv=false;                 
                $OsszNyelv=explode("!",NYELVEK);
                $NyelvDb=count($OsszNyelv);
                for($v=0;$v<$NyelvDb;$v++)
                {
                    $Volt=$this->Futtat($this->Gyerekparam($OsszNyelv[$v]))->TartalomAd();
                    if ($Volt["Ossz"]>0)
                    {
                        $Vissza.=" ".$Volt["Eredm"][0];   
                    }else $Nincsnyelv=true;

                }

                if ($Nincsnyelv)
                {
                        $Vissza="<font color=red>$Vissza</font>";
                }
                                            
            return $Vissza;    
        }        
        
  

}


class CEgySzoveg extends CVaz
{
/*A nyelveknél ezek az osztályok vannak:
-CNyelvForditCsoport - Minden szót tartalmaz. Ő csinálja a forditás, karbantartást.
    -CTobbNyelvuSzovegCsoport -Egy adott szó van benne az összes nyelven. Adott szóról visszaadja az aktuális nyelvüt. Kapcsolódik a CNyelvForditCsoport-hoz.
        -CEgyszo - Kapcsolódik a CTobbNyelvuSzovegCsoport-hoz, egy szó egy nyelven.     */
    var $Tabla_nev="NYELV";

        public function Tablasql()
        {
            $SQL="
CREATE TABLE `NYELV` (
  `AZON_I` int(11) NOT NULL auto_increment,
  `TARTALOM_S` text,
   PRIMARY KEY  (`AZON_I`)
) ENGINE=".MYSQL_ENGINE."

";
            return $SQL;    
        }    


        function TartalomBe($Tartalom)
        {
                $Tartalom=str_replace("\"","`",$Tartalom);
                $Tartalom=str_replace("'","`",$Tartalom);

                $this->TablaAdatBe("TARTALOM_S",$Tartalom);
                $this->Szinkronizal();

        }


        function TartalomAd()
        {
            $Vissza=$this->TablaAdatKi("TARTALOM_S");
            return $Vissza;

        }


}


?>
