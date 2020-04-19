<?php


 /**
 * CVaz_bovit  - bővített osztály VAZ-ból. Olyan metódusok kerülnek ide, amiket sok objektum használ, gyakran használatosak, kényelmesen és gyorsan lehessen használni. 
 * Ebből származtatjuk a többi osztályt.
  */
 
class CVaz_bovit extends CVaz 
{


        function Felsokepad()
        {
            return "";
        }
        
      /*  function Nyelvbe_pb_fut()
        {
ini_set ( "display_errors", E_ALL);
ini_set ( "error_reporting", E_ALL);      
ini_set ( "memory_limit", "28M");      
            $NYELV=$this->Nyelvad();
            $this->Nyelvbe_seged($NYELV);
            

        }*/
        function Nyelvbe_seged($NYELV)
        {
            $this->Nyelvbe($NYELV);

            $this->Futtatgy()->Nyelvbe_seged($NYELV);
        }        
        
/**
* Mostfutotte  - futotte e ebbe a körbe az adott objektum vagy akármelyik gyerek    
*@return boolean
*/
        function Mostfutotte()
        {
            $Vissza=false;
            $Futott=$this->Mostfutobj();
            if (is_object($Futott))
            {
                $Szulok=$Futott->SzuloObjektumok();
                if ($Szulok)
                {
                    foreach ($Szulok as $egy)
                    {
                        if ($egy->Egyezike($this))$Vissza=true;    
                    }
                }
            }
            return $Vissza;
        } 
        


        function Cimelott()
        {
            return "";
        }
        

/**
* Eleres_allom  - gyerekek között állomány elérése.  
* minden állomány állomány táblába VAZ -on keresztül   
*@param  $Lista - lista VZ_LISTA_S alapján
*@param  $Relativ - állomány relatív elérésű e. Ha nem relatív elérés első karaktere / Megjelenítéshez nem relatív kell, Php-nak méretezéshez relatív kell.
*@return string
*/
       public function Eleres_allom($Lista,$Relativ=0)
        {
            if ($Relativ)$Eredm=$this->Futtat($this->Gyerekparam($Lista))->RelativEleres();
                    else $Eredm=$this->Futtat($this->Gyerekparam($Lista))->Eleres();
            if ($Eredm["Ossz"]>0)$Vissza=$Eredm["Eredm"][0];
                else $Vissza="";
            return $Vissza;
        }
        
        
/**
* Allom_tarol  - formból tárol egy állományt, CForm Allomanybe tagjába megadott állományt  
* Pl menühöz listás kép, multimédia objektum több különböző méretű állományai...   
*@param  $Formbanev - formba file tag neve
*@param  $Lista - lista VZ_LISTA_S alapján
*@param  $Meret - ha kép és át kell méretezni. ha m -el kezdődik: magasság szerint pl m200 
*                                              ha k -el kezdődik ilyen keretbe férjen bele pl k200-200
*                                              ha s -el kezdődik ilyen széles legyen pl s200 
*@param  $Hovaobj - milyen objektumba tároljuk alapból CAlapAllomany

*/
       public function Allom_tarol($Formbanev,$Lista,$Meret=null,$Hovaobj=null)
        {
            if ($Hovaobj===null)$Hovaobj="CAlapAllomany";
            if ($Meret===null)$Meret="";
            

            $Torolve=$this->Postgetv($Formbanev."_torol");
            if (isset($Torolve)&&("$Torolve"=="1"))
            {
                $this->Futtat($this->Gyerekparam($Lista))->RekurzivTorol();    
            }else
            {
                $LISTAKEP_name=$this->Filev($Formbanev,"name");
                $LISTAKEP=$this->Filev($Formbanev,"tmp_name");
                if (isset($LISTAKEP_name)&&($LISTAKEP_name!=""))
                {
                    $Eredm=$this->Futtat($this->Gyerekparam($Lista))->Fajltarol($LISTAKEP,$LISTAKEP_name,MAXALLOMANYMERET,$Meret);
                    if ($Eredm["Ossz"]<1)
                    {
                        $Obj=$this->UjObjektumLetrehoz($Hovaobj,$Lista);
                        $Obj->Fajltarol($LISTAKEP,$LISTAKEP_name,MAXALLOMANYMERET,$Meret);
                    }
                }
            }
        }
        
                
/**
* AdminNevAd  - admin listába név, alaból ugyanaz mint NevAd() de felül lehet bírálni   
*@return string
*/
        function AdminNevAd()
        {
                $Vissza=$this->NevAd();
                if ($this->Sessad("Aktfelh")->Jogosultsag()>99)$Vissza.=" (".$this->AzonAd().")";
                return $Vissza;
        }
        
        function Nyilallit_fut()
        {
            $Honnan=$this->Postgetv("Honnan");
            $Hova=$this->Postgetv("Hova");
            
            $Honnan=mb_substr($Honnan,2);
            $Hova=mb_substr($Hova,2);
            
            
            if ("$Hova"=="start")
            {
                $this->Futtat($Honnan)->SorrendBe(1);
                
                $Term=$this->Futtatgy("TERMEK","VZ_SORREND_I",1,null," and VZ_AZON_I<>'".$Honnan."' ")->Objad();
                $Sorrend=2;
                foreach ($Term["Eredm"] as $item)
                {
                    $item->SorrendBe($Sorrend);

                    $Sorrend++;
                }                
            }else
            {
                $Sorrend=1;    
                
                $Term=$this->Futtatgy("TERMEK","VZ_SORREND_I",1,null," and VZ_AZON_I<>'".$Honnan."' ")->Objad();

//                $Term=$this->Futtatgy("TERMEK","VZ_SORREND_I",1,null)->Objad();
                foreach ($Term["Eredm"] as $item)
                {
                    $item->SorrendBe($Sorrend);
                    $Sorrend++;
                    if ($item->AzonAd()=="$Hova")
                    {
                        $this->Futtat($Honnan)->SorrendBe($Sorrend);    
                    }
                    $Sorrend++;

                }                
            }

            
            return $this->Lista_seged(true);
        }


        public function Sablonbe($Sabnev,$Data)        
        {
            if (($Sabnev=="Lista")||($Sabnev=="Lista_Uj_seged")||($Sabnev=="Lista_Uj"))
            {
                $Data["Nyillink"]=$this->EsemenyHozzad("Nyilallit_fut");
                
            }            
            if ($Sabnev=="Kapcsoldalt")
            {
                if ($this->AzonAd()!=Focsop_azon)
                {
                    return $this->Futtat(Focsop_azon)->Sablonbe($Sabnev,$Data);
                } 
                
            }
            if ($Sabnev=="Nyitakttermek")
            {
                if ($this->AzonAd()!=Webaruhaz_azon)
                {
                    return $this->Futtat(Webaruhaz_azon)->Sablonbe($Sabnev,$Data);
                } 
                
            }
            return parent::Sablonbe($Sabnev,$Data);  
        }
        
        function Korvalasz_pb_fut()
        {
            $_SESSION["Korvalaszt"]=1;
            echo "<script>$('.korvalaszto_bg').hide();
            </script>";
            $this->Kerkorvalaszt();
            exit;
        }
        
        function Kerkorvalaszt()
        {
            $Vissza=true;
//            if (isset($_GET["Kormult"]))$_SESSION["Korvalaszt"]=1;
            
            if (isset($_SESSION["Korvalaszt"]))$Vissza=false;
            
            return $Vissza;
        }

 /**
 * Sablondef - alapértelmezett értékeket állít be sablonba lévő sablonok hívásakor - jellemezően ezek az oldal állandó desgin elemei pl header,footer... hez 
 * @param $Sablonnev sablon neve
 * @return array   
 */ 

      public function Sablondef($Sablonnev)
      {
            $Vissza=array();
            
            if (ADATBAZISNEV=="")return $Vissza;
            

            
            switch ($Sablonnev)
            {            
                case "Headadmin":
                case "Head":
                    $Script=$this->Kozad(KOZ_SCRIPT_UZ,array());
                 
                 $Vissza["Scriptuzen"]=implode("\n",$Script);
                 
                 $Script=$this->Kozad(KOZ_SCRIPT,array());
                 $Vissza["Script"]=implode("\n",$Script);
                 $Vissza["Headba"]=implode("\n",$this->Kozad(KOZ_HEAD,array()));

                 $Vissza["Metatags"]=$this->Metatags();
                                 
                 
                break;
                case "Adminbelep":
                 $Menu=$this->Sessad("Aktfelh")->Belepmenu();
                 if (count($Menu)>0)
                 {
                    $Vissza["Menu"]=$Menu;
                 }                 
                break;
                case "Korvalaszto":
                    $Vissza["Kell"]=$this->Kerkorvalaszt();
                    $Vissza["Kelllink"]=$this->EsemenyHozzad("Korvalasz_pb_fut");
                break;
                case "Kosar_felul":
                    $Vissza["Kosdata"]=$this->Futtat(Kosar_azon)->Kosarinfo();
                break;       
                case "inc_header_uj_admin":       
                case "inc_header":
                   if ($this->Sessad("Aktfelh")->Jogosultsag()>0)
                   {
                        $Vissza["Kedvlink"]=$this->Futtat(Webshop_azon)->EsemenyHozzad("Kedvenc_pb_fut");    
                        $KData=$this->Sessad("Aktfelh")->Kedvencek(" limit 0,1");
                        $Vissza["Kedvdb"]=$KData["Db"];    
                   }else
                   {
                    $Vissza["Kedvdb"]=0;
                   }
                   
                   

                    $Nyelv=$this->Nyelvadpub();
                    $Obj=$this->ObjektumLetrehoz(Felso_menu($Nyelv));

                    $Ered=$Obj->Futtatgy("CSOPORT!CSOPORT_ADMIN",null,null,null," and AKTIV_I='1' ")->Adatlistkozep_publ(false);         
                    $Vissza["Menu"]=$Ered["Eredm"];
                    
                    $Vissza["Bemenu"]=$this->Sessad("Aktfelh")->FelsoBelepmenu();
                    
                    
                    
                    
                break;
                case "Nyito_partnerek":
                 $Nyelv=$this->Nyelvadpub();
                 
                 $Obj=$this->ObjektumLetrehoz(Partn_azon($Nyelv));
                 $Dat=$Obj->Futtatgy("CSOPORT!CSOPORT_ADMIN",null,null,null," and AKTIV_I='1' ")->Adatlistkozep_publ(false);
                    
                    $Vissza["Partn"]=$Dat["Eredm"];
                   
                    
                break;
                case "inc_slider":
                   
                    $Obj=$this->ObjektumLetrehoz(Webshop_azon);

                    $Vissza["Html"]=$Obj->Nyitolapra();
                    
                break;
                case "inc_search":
                   
                    $Obj=$this->ObjektumLetrehoz(Webshop_azon);

                    $Vissza["Link"]=$Obj->EsemenyHozzad("Kereses_pb_fut");
                    
                break;
                case "Nyito_mosterk":
                   
                    $Obj=$this->ObjektumLetrehoz(Webshop_azon);

                    $Vissza["Shoplink"]=$Obj->EsemenyHozzad("");
                    $Nyelv=$this->Nyelvadpub();
                    
                    $Vissza["Nyitoszov"]=$this->Futtat(Nyito_azon($Nyelv))->Adatlistkozep_publ();
                    
                    
                break;
                
                
                case "Navigalosor":
                    $Vissza["Menu"]=$this->Navigsorad();
                    
                break;
                case "inc_footer":
                   $Nyelv=$this->Nyelvadpub();
                    $Obj=$this->ObjektumLetrehoz(Also_menu($Nyelv),0);
                    $Ered=$Obj->Futtatgy("CSOPORT",null,null,null," and AKTIV_I='1' ")->Adatlistkozep_publ(true);         
                    $Vissza["Menu"]=$Ered["Eredm"];

                    
                break;
              case "Hirlevel":
                     $Obj=$this->ObjektumLetrehoz(Hirlevel_azon);
                     $Vissza["Hirlevlink"]=$Obj->EsemenyHozzad("Eligazit_pb_fut");
                break;                  
                  
                case "Belepes":
                 $Menu=$this->Sessad("Aktfelh")->Belepmenu();
                 if (count($Menu)>0)
                 {
                    $Vissza["Belepve"]=1;
                    $Vissza["Menu"]=$Menu;
                 }else $Vissza["Belepve"]=0;
                 $Vissza["Beleplink"]=$this->Futtat(Felhcsop_azon)->EsemenyHozzad("Belepes_pb");
                 $Vissza["Jellink"]=$this->Futtat(Felhcsop_azon)->EsemenyHozzad("EmlekeztetoUrlap_pb_fut");
                 $Vissza["Reglink"]=$this->Futtat(Felhcsop_azon)->EsemenyHozzad("Regisztral_pb_fut");
                                  
                break;
                
                
/*                case "Kereso":
                    
                    $Obj=$this->ObjektumLetrehoz(Projekt_azon);
                    $Vissza["Kereslink"]=$Obj->EsemenyHozzad("Kereses_pb_fut");                    
                break;               
                case "Hirlevel":
                     $Obj=$this->ObjektumLetrehoz(Hirlevel_azon);
                     $Vissza["Hirlevlink"]=$Obj->EsemenyHozzad("Eligazit_pb_fut");
                break;  */                
                                
            }
            
            return $Vissza;
      } 

        function Objad()
        {
            return $this;
        }

        function Szinad()
        {
            return "";
        }





/**
 * Adatlist_adm_tag - Visszaadja tömbbe hogy admin szerkesztősorba mik jelenjenek meg - milyen ikonok. Felül kell bírálni későbbi osztályokba
 * Amelyik indexek szerepelnek visszatérésbe, azokat teszi ki
 * @return array: LISTA - Lista_fut
 *                URLAP - Urlapki_fut
 *                TEKINT - $Def_feladat      
 *                TOROL - Torol_fut                  
 *                + ezeken kívül lehet más is EGYEB indexbe, pl 
 *                  EGYEB[0]["Nev"]="Nyomtat"; 
 *                  EGYEB[0]["Link"]="..link"
 *                  EGYEB[1]["Nev"]="Nyomtat2"; 
 *                  EGYEB[1]["Link"]="..link2"
 * 
 */
 
        function Adatlist_adm_tag()
        {
            
        }
        
        function Adatlist_publ($Kellkep=true)
        {
            $Vissza["Link"]=$this->EsemenyHozzad("");
            $Vissza["Nev"]=$this->NevAd();
            if ($Kellkep)$Vissza["Kep"]=$this->Eleres_allom("LISTAKEP");
              
            return $Vissza;
        }
        
 /**
 * Adatlist_adm - Listázó adatok adminisztrációs részre, adatok Adatlist_adm_tag -ból Vágólap linkkel/nyillal kiegészítve. Ha nyilas lekérdezést csináltunk Nyilelotte/Nyilutana -ba van az előtte/utána lévő objektum   
 * @return array   
 */         
        
        public function Adatlist_adm()
        {
                $Tagok=$this->Adatlist_adm_tag();
                
                $NEV=$this->AdminNevAd();
                if (isset($Tagok["URLAP"]))$Eredmeny["URLAP"]=array("Nev"=>$NEV,"Link"=>$this->EsemenyHozzad("Urlapki"));
                                                        else $Eredmeny["URLAP"]=array("Nev"=>$NEV,"Link"=>"");

                if (isset($Tagok["LISTA"]))$Eredmeny["LISTA"]=array("Nev"=>"Lista","Link"=>$this->EsemenyHozzad("Lista"));
                
                
                if ((isset($Tagok["TEKINTUJ"]))&&($Tagok["TEKINTUJ"]=="1"))
                {
                    if (isset($Tagok["TEKINT"]))$Eredmeny["TEKINT"]=array("Nev"=>"Megtekint","Link"=>$this->EsemenyHozzad($this->Def_feladat),"TEKINTUJ"=>"1");
                }else
                {
                    if (isset($Tagok["TEKINT"]))$Eredmeny["TEKINT"]=array("Nev"=>"Megtekint","Link"=>$this->EsemenyHozzad($this->Def_feladat));
                }
                
                if (isset($Tagok["TOROL"]))$Eredmeny["TOROL"]=array("Nev"=>"Töröl","Link"=>$this->EsemenyHozzad("Torol_fut"));
                
                $Nyilelotte=$this->AllapotKi("Nyilelotte",0);
                $Nyilutan=$this->AllapotKi("Nyilutana",0);
                if (($Nyilelotte!="0")||($Nyilutan!="0"))
                {
                    $Obj=$this->ObjektumLetrehoz($Nyilelotte);
                    if (!($Obj->Uresobjektum()))$Eredmeny["Nyilelott"]=$this->EsemenyHozzad("Fel_fut")."?Mit=".$Obj->AzonAd();

                    $Obj=$this->ObjektumLetrehoz($Nyilutan);
                    if (!($Obj->Uresobjektum()))$Eredmeny["Nyilutan"]=$this->EsemenyHozzad("Le_fut")."?Mit=".$Obj->AzonAd();
                    
                }
                if ($this->Sessad("Aktfelh")->Jogosultsag()>99)
                {
                    if ($this->Vagorakhat())
                    {
                        $Eredmeny["VAGOLAP"]=array("Nev"=>"Vágólapra","Link"=>$this->EsemenyHozzad("Vagolapra_fut"));    
                    }
                }
                
                if (isset($Tagok["EGYEB"]))
                {   
                    foreach ($Tagok["EGYEB"] as $ertek)                
                    {
                        if (isset($ertek["Confirm"]))$Eredmeny["EGYEB"][]=array("Nev"=>$ertek["Nev"],"Link"=>$ertek["Link"],"Confirm"=>$ertek["Confirm"]);
                        else $Eredmeny["EGYEB"][]=array("Nev"=>$ertek["Nev"],"Link"=>$ertek["Link"]);    
                    }
                }
                if ($this->Sessad("Aktfelh")->Jogosultsag()>="100")
                {
                    $Eredmeny["EGYEB"][]=array("Nev"=>"[+]","Link"=>$this->EsemenyHozzad("DebugUrlapKi_fut"));
                }
                $Eredmeny["Azon"]=$this->AzonAd();

                

                return $Eredmeny;
        }

        function Vagorakhat()
        {
                $Vissza=true;

                $Data=$this->Sessadtomb("VagoLap",$this->AzonAd());
                if ($Data["Van"]=="1")$Vissza=false;
                return $Vissza;
        }
        
         
 /**
 * Adat_adm - Objektum adatai adminisztrációs részre - főleg listazok hívják   
 * @return array 
 *               ["Vissza"] - visszalink, hívja Adat_adm_vissza, ha üres nem lesz visszalink
 *               ["Menu"] - almenük tömbbe, hívja Adat_adm_menu -t pl [0]["Nev"]="Új csoport"   
 *                                            [0]["Link"]="link"
 *               ["Menuutan"] - menü után megjelenő tetszőleges tartalom 
 */         
        public function Adat_adm()
        {
            $Vissza["Vissza"]=$this->Adat_adm_vissza();
            $Vissza["Menu"]=$this->Adat_adm_menu();
            $Beker="";
            if ($this->Sessad("Aktfelh")->Jogosultsag()>="100")
            {
                $Vaz=new CVaz_bovit();
                $Beker=$Vaz->DebugForm($this->EsemenyHozzad("DebugUjObjektum"));
            }
            $Vissza["Menuutan"]=$Beker;
            
            
            return $Vissza;
                
        }           
          
 /**
 * Adat_adm_menu - Objektum almenüi adminisztrációs részre  
 * @return array kétdimezniós tömb pl [0]["Nev"] - almenü neve
 *                                    [0]["Link"] - link
 *                                             
 */         
        public function Adat_adm_menu()
        {
            $Vissza=array();
            $Data=$this->Sessad("VagoLap",array());
            if (count($Data)>0)
            {
                $Vissza[0]["Nev"]="Vágólapról beilleszt";
                $Vissza[0]["Link"]=$this->EsemenyHozzad("Vagolista_fut");
            }
            return $Vissza;
        } 
        
 /**
 * Adat_adm_vissza - adminisztrációs listazo részre visszalinket ad, ha üres nem lesz vissazlink  
 * @return string - url
 */         
        public function Adat_adm_vissza()
        {
            return $this->VisszaEsemenyAd();
        }                   
                                 
/**
 * Hozzafer_torol - Van e jogunk törölni egy objektumot. Alapból aki Hozzafer az tud, de felülbírálható
 * @return 0 vagy 1   
 */ 
        public function Hozzafer_torol()
        {
            return $this->Hozzafer("Torol_fut");    
        }

        function Hozzaferhetkod()
        {
            return "";
        }


 /**
 * Hozzafer Van e joga a belépett felhasználónak futtatni a feladatot. Ha a feladat nevének a vége ez: _pb_fut, akkor mindenki futtathatja a feladatot.
 * Ha ugy kezdődik a függvény hogy Debug, akkor Debugroot kell hozzá
 * Ha a feladat Alap_fut, akkor lekérdezzük hogy mit fog futtatni Alap_fut (Alap_fut_nevad -al)) és azt ellenőrizzük.
 * Mivel csak olyan feladat futhat aminek a vége _fut, ezért a függvény az $Feladat -ot kiegészíti _fut -al ha nem lenne  
 * @return 0 vagy 1   
 */ 
        public function Hozzafer($Feladat)
        {
             if (mb_substr($Feladat,mb_strlen($Feladat)-4,4,STRING_CODE)!="_fut")$Feladat.="_fut";
         
            if ("$Feladat"=="Xml_general_fut")return true;
                
             if ("$Feladat"=="Alap_fut")$Feladat=$this->Alap_fut_nevad();

             
             if (mb_substr($Feladat,mb_strlen($Feladat)-7,7,STRING_CODE)=="_pb_fut")$Vissza=true;
             else
             {
                $Eleje=mb_substr($Feladat,0,5,STRING_CODE);
                
                if ($Eleje=="Debug")
                {
                    if ($this->Sessad("Aktfelh")->Jogosultsag()<100)
                    {
                            $this->ScriptUzenetAd("@@@Nincs jogosultsága!§§§");
                            $Vissza=false;
                    }
                    else $Vissza=true;
                }else
                {
                    
                    if ($this->Sessad("Aktfelh")->Jogosultsag()<99)
                    {
                            $this->ScriptUzenetAd("@@@Nincs jogosultsága!§§§");
                            $Vissza=false;
                    }
                    else $Vissza=true;
                }
             }
             
             
//            Fel_fut
  //          Le_fut,VagolapraMasol_fut
//BeillesztEligazit_fut
//Beilleszt_fut  
 
                return $Vissza;
        }
        

 /**
 * Tordeles - pagert generál, betölti sablonból
 *@param $Honnannev - honnan neve, ilyen változóba jön át post/get -ként 
 *@param $Honnan - honnan kezdjük a listázást, hol vagyunk 
 *@param $Ossz - összesen hány elem van 
 *@param $Oldalonhany - egy oldalon hány elem jelenik meg 
 * @return 0 vagy 1   
 */ 
        public function Tordeles($Honnannev,$Honnan,$Ossz,$Oldalonhany,$Link="")
        {
            if ($Link=="")$Pagerdat["Link"]=$this->EsemenyHozzad("Alap");
                else $Pagerdat["Link"]=$Link;
            $Pagerdat["Honnan"]=$Honnan;
            $Pagerdat["HonnanNev"]=$Honnannev;
            $Pagerdat["GyerekSzam"]=$Ossz;
            $Pagerdat["OldalonHany"]=$Oldalonhany;
            
            

            $Vissza=$this->Sablonbe("Pagerbe",$Pagerdat);
            return $Vissza;
        }           

                                 
/**
 * DebugForm - objektum debug űrlapját adja vissza.  VZ_LISTA_S és VZ_OBJEKTUM_S -t lehet megadni. Üres VAZ objektum esetén -> új felvitel lesz
 * @param $Tarol - ha új akkor táróllink, különben üres
 * @return form html-be   
 */
        function DebugForm($Tarol="",$Visszakell=false)
        {
                if ($this->Uresobjektum())
                {
                    $VZ_LISTA_S="";    
                    $VZ_OBJEKTUM_S="";
                    //$Tarol=$this->EsemenyHozzad("DebugUjObjektum");
                }else
                {
                    $VZ_LISTA_S=$this->ListaAd();    
                    $VZ_OBJEKTUM_S=$this->Objtipusad();
                    $Tarol=$this->EsemenyHozzad("DebugTarol");
                }
                $this->ScriptTarol("

                function DebEllenor()
                {
                        if(document.DebugForm.VZ_LISTA_S.value=='')alert('A listatípus mező nem lehet üres!');
                        else if(document.DebugForm.VZ_OBJEKTUM_S.value=='')alert('Az objektumtípus mező nem lehet üres!');
                        else return true;
                        return false;
                }
                ");

                
                $Form=new CForm("DebugForm","","");
                $Form->Hidden("Esemeny_Uj",$Tarol);
                $Form->Textbox("Listatipus:","VZ_LISTA_S",$VZ_LISTA_S,"");
                $Form->Textbox("Objektumtipus:","VZ_OBJEKTUM_S",$VZ_OBJEKTUM_S,"");


                $Form->Gomb("Létrehoz","return DebEllenor();","submit");
                if ($Visszakell)$Form->Gomb("Visszalép","location.href='".$this->VisszaEsemenyAd()."'","button");

               $Tartalom=$Form->OsszeRak();
               return $Tartalom;
        }
             
/**
 * Metatags - meta tagokat adja vissza tömbbe 
  * @return array Title
  *               Key
  *               Desc      
 */      
        function Metatags()
        {
            $Vissza["Title"]="";
            $Vissza["Key"]="";
            $Vissza["Desc"]="";
            $Futott=$this->Mostfutobj();
            
            if (is_object($Futott))
            {
                $Vissza=$Futott->Metatags_seged2();
            }
            
            if (($Vissza["Title"]=="")||($Vissza["Key"]=="")||($Vissza["Desc"]==""))
            {
                $Tomb=$this->Futtat(Focsop_azon)->Metatags_seged();
                if ($Vissza["Title"]=="")$Vissza["Title"]=$Tomb["Title"];
                if ($Vissza["Key"]=="")$Vissza["Key"]=$Tomb["Key"];
                if ($Vissza["Desc"]=="")$Vissza["Desc"]=$Tomb["Desc"];
            }
            return $Vissza;
        }
                

        function Metatags_seged2()
        {
            $Vissza["Title"]="";
            $Vissza["Key"]="";
            $Vissza["Desc"]="";
            
            if ($this->Vanilyenmezo("KERESO_TITLE_HU_S"))$Vissza["Title"]=$this->TablaAdatKi("KERESO_TITLE_HU_S");    
            if ($this->Vanilyenmezo("KERESO_KEY_HU_S"))$Vissza["Key"]=$this->TablaAdatKi("KERESO_KEY_HU_S");    
            if ($this->Vanilyenmezo("KERESO_DESC_HU_S"))$Vissza["Desc"]=$this->TablaAdatKi("KERESO_DESC_HU_S");   
            
            
            return $Vissza;
        }
          
                     
        function DebugUjObjektum_fut()
        {
                $LISTATIPUS=$this->Postgetv("VZ_LISTA_S");
                $OBJEKTUMTIPUS=$this->Postgetv("VZ_OBJEKTUM_S");
                $Obj=$this->UjObjektumLetrehoz($OBJEKTUMTIPUS,$LISTATIPUS);
                
                return $this->Futtat($Obj)->UrlapKi_fut();
        }
 
        function DebugTarol_fut()
        {
                $LISTATIPUS=$this->Postgetv("VZ_LISTA_S");
                $OBJEKTUMTIPUS=$this->Postgetv("VZ_OBJEKTUM_S");
                $this->DebugErtekekBe($LISTATIPUS,$OBJEKTUMTIPUS);

                return $this->VisszaLep();
        }

        function DebugUrlapKi_fut()
        {

                $Tarol=$this->EsemenyHozzad("DebugTarol_fut");

                $Tartalom=$this->DebugForm($Tarol,true);
                
                
                
                return $this->Sablonbe("Oldal_admin",array("Tartalom"=>$Tartalom));
        }
     
        
/**
*Mutat_pb_fut - Ha valahol nem lenne, nyitólapi alapfeladat fusson 
*/        
        function Mutat_pb_fut()
        {
                $Vissza=$this->Futtat(Focsop_azon)->Mutat_pb_fut();
                return $Vissza;
        }

        


 /**
 * Vagolap_fut - debugroot-nak vágólapot listázza, sablonba át kell tenni még tartalmat
 */ 
        function Vagolista_fut()
        {
                $Cim=$this->NevAd()." beillesztés vágólapról";

                $Vagolap=$this->Sessad("VagoLap",array());

                if (count($Vagolap)>0)
                {
                    
                        $Tartalom="<table width=100%>
                        <tr><td>&nbsp</td></tr>";
                        $Link=$this->EsemenyHozzad("BeillesztEligazit");
                        

                        foreach ($Vagolap as $Elem)
                        {
                            

                                $Random=rand(0,20000);
                                $Tartalom.="<tr>
                                  <td widht=50% class=adminmenu>".$Elem->AdminNevAd()."</td>
                <form name='VagoForm$Random' method='post' action='$Link' enctype='multipart/form-data'>
                  <input type='hidden' name='Illeszt'>
                  <input type='hidden' name='BeillesztAzon'>
                                  <td width=25%>
                                  <a href='javascript:document.VagoForm$Random.Illeszt.value=\"Objlemasol\";document.VagoForm$Random.BeillesztAzon.value=".$Elem->AzonAd().";document.VagoForm$Random.submit();' class=adminmenu>Másol (új obj. lesz)</a>
                        </td>
                        <td width=20%>
                           <a href='javascript:document.VagoForm$Random.Illeszt.value=\"Athelyez\";document.VagoForm$Random.BeillesztAzon.value=".$Elem->AzonAd().";document.VagoForm$Random.submit();' class=adminmenu>Áthelyez</a>
                                  </td>
                        <td width=20%>
                           <a href='javascript:document.VagoForm$Random.Illeszt.value=\"Masolatcsinal\";document.VagoForm$Random.BeillesztAzon.value=".$Elem->AzonAd().";document.VagoForm$Random.submit();' class=adminmenu>Másolatot csinál (ua marad)</a>
                                  </td>
                                  
                                  </form>
                                </tr>";
                        }
                        $Visszalep=$this->EsemenyHozzad("Lista");
                        $Tartalom.="
                        <tr>
                           <td colspan='3' align='center'><input type=button value='Vissza' onclick=\"location.href='".$this->EsemenyHozzad("Lista")."'; \" >
                           </td></tr>
                           </table>";
                          //$Vissza=MunkaTerulet($Cim,$Tartalom);
                          $Vissza=$this->Sablonbe("Oldal_admin",array("Cim"=>$Cim,"Tartalom"=>$Tartalom));

                }else
                {
                    $this->ScriptTarol("location.href='".$this->EsemenyHozzad("Lista_fut")."';");
                    //header("location: ".$this->EsemenyHozzad("Lista_fut")."");
                    $Vissza="";
                    $this->ScriptUzenetAd("A vágólap üres!");
                    $Vissza=$this->Sablonbe("Oldal_admin",array("Cim"=>"","Tartalom"=>""));


                }

                return $Vissza;
        }
        

 /**
 * Vagolapra_fut - vágólapra másolja az objektumot
 */          
        function Vagolapra_fut()
        {
            $this->Sessbetomb("VagoLap",$this,$this->AzonAd());
            $this->ScriptUzenetAd("Sikeresen bekerült a vágólapra!");
            return $this->VisszaLep();
        }



        
        
/**
 * BeillesztEligazit_fut - vágólapról beillesztés után fut le. Másol/klónol/áthelyez      
*/         
   
        public function BeillesztEligazit_fut()
        {
                $Illeszt=$this->Postgetv("Illeszt");
                $BeillesztAzon=$this->Postgetv("BeillesztAzon");             
                $Data=$this->Sessadtomb("VagoLap",$BeillesztAzon);

                
                if ($Data["Van"]=="1")
                {
                    
                    if ("$Illeszt"=="Masolatcsinal")
                    {
                        $Data["Ertek"]->Masolatcsinal($this,$Data["Ertek"]->ListaAd());
                        $this->ScriptUzenetAd("Másolat létrejött!");
                    }
                    else
                    if ("$Illeszt"=="Objlemasol")
                    {
                            $this->ObjektumLemasol($Data["Ertek"]);
                            $this->ScriptUzenetAd("Az elem sikeresen lemásolva!");
                    }else
                    if ("$Illeszt"=="Athelyez")
                    {
                            $Data["Ertek"]->Athelyez($this);
                            $this->ScriptUzenetAd("Az elem sikeresen átkerült!");
                            $this->Sessbetomb("VagoLap","null",$BeillesztAzon);

                    }
                }else $this->ScriptUzenetAd("Hiba!");
                
                return $this->Futtat($this)->Vagolista_fut();
        }

 


function utfkodol($str)
{

$str= str_replace("ű","&#369;", $str);
$str= str_replace("Ű","&#368;", $str);
$str= str_replace("ő","&#337;", $str);
$str= str_replace("Ő","&#336;", $str);

//$str=CAlap::unhtmlentities($str);
         $g=mb_detect_encoding($str);
          $szov=mb_convert_encoding($str,"UTF-8");


$szov= str_replace("&#369;",chr(197).chr(177), $szov);
$szov= str_replace("&#368;",chr(197).chr(176), $szov);
$szov= str_replace("&#336;",chr(197).chr(144), $szov);
$szov= str_replace("&#337;",chr(197).chr(145), $szov);

$szov= str_replace("%u0151","ő", $szov);

                return $szov;
}

       function Jelenhetnavba()
        {
            return true;
        }

        function NavSzuloObjektumok()
        {
            $Vissza=array();
            

            if ($this->AzonAd()!=Focsop_azon)
            {
                
                    $Vissza[]=$this;
                
                $Szulo=$this->SzuloObjektum();
                $Vissza=array_merge($Vissza,$Szulo->NavSzuloObjektumok());
            }
            return $Vissza;
        }
        

        function Navigsorad()
        {
            $Vissza=array();
            $Futott=$this->Mostfutobj();
            
            
            if (is_object($Futott))
            {
                $Erazon=$Futott->_EredetiAzon();
                if ("$Erazon"!=$Futott->AzonAd())
                {
                    $Futott=$this->ObjektumLetrehoz($Erazon,0);
                }
                
                $Szulok=$Futott->NavSzuloObjektumok();
                if ($Szulok)
                {
                    foreach ($Szulok as $egy)
                    {
                       if ($egy->Jelenhetnavba())$Vissza[]=array("Nev"=>$egy->NevAd(),"Link"=>$egy->EsemenyHozzad("Alap_fut"));
                      //  else $Vissza[]=array("Nev"=>$egy->NevAd(),"Link"=>"");
                    }
                }
            }
            $Vissza=array_reverse($Vissza);
            return $Vissza;
        } 


        public function Karbatarto()
        {
            
                parent::Karbatarto();

               $Regiek=self::$Sql->Lekerst("select AZON_I from RENDELES where (DATE_SUB(now(), INTERVAL '4' hour )>REND_ALLAPOT_MIKOR0_D) and (REND_ALLAPOT_MIKOR0_D>'2002-12-12 12:12' ) and (REND_ALLAPOT_I=0) limit 0,30");
//               $Regiek=self::$Sql->Lekerst("select AZON_I from RENDELES where (DATE_SUB(now(), INTERVAL '2' DAY)>REND_ALLAPOT_MIKOR0_D) and (REND_ALLAPOT_MIKOR0_D>'2002-12-12 12:12' ) and (REND_ALLAPOT_I=0) limit 0,30");
                
                if ($Regiek)
                {
                        $db=count($Regiek);
                        for ($c=0;$c<$db;$c++)
                        {
                                $Vaz=self::$Sql->Lekerst("select VZ_AZON_I from VAZ where VZ_TABLA_S='RENDELES' and VZ_TABLA_AZON_I='".$Regiek[$c]["AZON_I"]."'");
                                $Regi=$this->ObjektumLetrehoz($Vaz[0]["VZ_AZON_I"],0);
                                if ($Regi)
                                {
                                        $Regi->Sikertelenre();
                                }
                                
                        }
                }
                

        }
        
        function Mutatloggole()
        {
            $REQUEST_URI=$_SERVER["REQUEST_URI"];
            $TIPUS="";
            if (!(mb_strpos($REQUEST_URI,"italkereso/")===false))$TIPUS="italkereso";
            if (!(mb_strpos($REQUEST_URI,"ital_jatek/")===false))$TIPUS="ital_jatek";
            
            
            if ($TIPUS!="")
            {                        
                $this->Nezloggol($TIPUS);
            }
            
        }



        function Nezloggol($TIPUS)
        {
            
            $IP=Ipcim();
            $AZON=$this->TablaAdatKi("AZON_I");
            $VZ_AZON=$this->AzonAd();
            $MIKOR=date("Y-m-d H:i:s");
            
            $Update0="MIKOR_D,";
            $Update0.="IPCIM_S,";
            $Update0.="MIH_TABLA_S,";
            $Update0.="TIPUS_S,";
            $Update0.="MIHEZ_TB_AZON_I,";
            $Update0.="MIHEZ_VZ_AZON_I";
            

            
            $Update1="'".$MIKOR."', ";
            $Update1.="'".$IP."', ";
            $Update1.="'".$this->Tabla_nev."', ";
            $Update1.="'".$TIPUS."', ";
            $Update1.="'".$AZON."', ";
            $Update1.="'".$VZ_AZON."' ";
            
            
            
            $Sql="insert into TERMEKNEZ_LOG ($Update0) values ($Update1)  ";

            self::$Sql->Modosit($Sql);
        }       

/*
CREATE TABLE IF NOT EXISTS `TERMEKNEZ_LOG` (
  `AZON_I` int(13) NOT NULL AUTO_INCREMENT,
  `MIKOR_D` datetime default '1900-01-01 00:00:00',
  `IPCIM_S` varchar(40) default '',
  `TERMEK_TB_AZON_I` int(13) default '0',
  `TERMEK_VZ_AZON_I` int(13) default '0',
  PRIMARY KEY  (`AZON_I`)
) ENGINE=innodb
alter table TERMEKNEZ_LOG add column MIH_TABLA_S varchar(30) default '' 
alter table TERMEKNEZ_LOG add column TIPUS_S varchar(30) default '' 
*/        

}

?>