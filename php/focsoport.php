<?php

class CMainCsoport extends CCsoport
{
        
/**
 * Futas_indit - futás indításához, közös tár ürítés, tranzakció indítás
*/


        public function Futas_indit($Kelltrans=true)
        {
            $this->Kozurit();
            if(!(is_object($this->Sessad("Aktfelh"))))
            {
                $this->Futtat(Felhcsop_azon)->CsakKijelentkezes();
                $this->Futtat(Felhcsop_azon)->Bejelentkezbeallit();
                
                



            }      


            $Szul=$this->Sessad("Aktfelh")->SzuloObjektum();

                if ($Szul->Uresobjektum())
                {

                    $Obj=$this->ObjektumLetrehoz($this->Sessad("Aktfelh")->AzonAd(),0);
                    
                    $this->Sessbe("Aktfelh",$Obj);                    
                }
                

            
            

            if ($Kelltrans)
            {
                self::$Sql->Inditas();
            }
            $this->Ckeditor_jog_be();
            $this->Karbatarto();
        }

        function Cimelott()
        {
            return "";
        }

        function Ckeditor_jog_be()
        {
                if ($this->Sessad("Aktfelh")->Jogosultsag()>=99)$_SESSION["CKEDITOR_JOG"]=1;
                                                                else $_SESSION["CKEDITOR_JOG"]=0;
        }
        
        function Felsokepad()
        {
            return "";
        }
        
        function Oldalterkep_pb_fut()
        {
                $Obj=$this->ObjektumLetrehoz(Legfelsomenu_azon,0);
                $Vissza=$Obj->AlTerkepgeneral(0);

                $Obj=$this->ObjektumLetrehoz(Felsomenu_azon,0);
                $Vissza.=$Obj->AlTerkepgeneral(0);

                $Obj=$this->ObjektumLetrehoz(Balmenu_azon,0);
                $Vissza.=$Obj->AlTerkepgeneral(0);

                $Obj=$this->ObjektumLetrehoz(Aktual_azon,0);
                $Vissza.=$Obj->AlTerkepgeneral(0);

                return $Vissza;            
        }
        
      
        function Kereses_pb_fut()
        {
            $Mitkeres=$this->Postgetv_jegyez("Mitkeres",0);
            $Honnan=$this->Postgetv_jegyez("Honnan",0);
            if ($Mitkeres=="0")$Mitkeres="";
            $Felt=" and (NEV_S like '%$Mitkeres%' or SZOVEG_S like '%$Mitkeres%') and VZ_NYELV_S='".$this->Nyelvadpub()."' and (VZ_OBJEKTUM_S not like '%CTobbNyelvuSzovegCsoport%' and VZ_OBJEKTUM_S not like '%CNyelvForditCsoport%' ) and VZ_AZON_I<>'".Focsop_azon."' and VZ_AZON_I<>'".Felhcsop_azon."' ";  
//            $Felt=" and (NEV_S like '%$Mitkeres%' or SZOVEG_S like '%$Mitkeres%')  ";  
            $Sablon=array();
            $Mennyi=30;
            $sql="select VZ_AZON_I from VAZ inner join SZOVEG on VAZ.VZ_TABLA_S='SZOVEG' and VZ_TABLA_AZON_I=SZOVEG.AZON_I where VZ_SZINKRONIZALT_I='1' and AKTIV_I='1' $Felt order by NEV_S limit $Honnan,".$Mennyi;
            $Azon=self::$Sql->Lekerst($sql);
            $sql="select count(VZ_AZON_I) as db from VAZ inner join SZOVEG on VAZ.VZ_TABLA_S='SZOVEG' and VZ_TABLA_AZON_I=SZOVEG.AZON_I where VZ_SZINKRONIZALT_I='1' and AKTIV_I='1' $Felt ";
            $Ossz=self::$Sql->Lekerst($sql);
            $Ossz=$Ossz[0]["db"];

            
            if ($Azon)
            {
                foreach ($Azon as $egy)
                {
                    $Sablon[]=$this->Futtat($egy["VZ_AZON_I"])->Adatlistkozep_publ(true);
                }
            }            
            $SZOVEG=$this->Sablonbe("Menumunkateruletre",$Sablon);
            $SZOVEG.=$this->Tordeles("Honnan",$Honnan,$Ossz,$Mennyi);
           // $Vissza=$this->Sablonbe("Termeknyitoakt",$Sablon);
                $Vissza["Tartalom"]=$SZOVEG;
                $Vissza["Cim"]="@@@Keresés eredménye§§§ $Mitkeres";
//                $Vissza["Vissza"]=$this->VisszaEsemenyAd();
                
            return $this->Sablonbe("Oldal",$Vissza);
        }
        
                        
/**
 * Futas_vege - futás végén, ha sikeres, session kiirás, adatbázisba kiirás
 * @param $Sikeres - sikeres volt e
 */
        public function Futas_vege($Sikeres,$Kelltrans=true)
        {
            if ($Sikeres)
            {
                $this->Kiiras_sess();
  //              $this->Kiiras_param();
//                $Futobj=$this->Kozad(KOZ_OBJ,array());
                if ($Kelltrans)
                {      
                    self::$Sql->Vege($Sikeres);
                }
            }else
            {
                if ($Kelltrans)
                {
                    self::$Sql->Vege($Sikeres);
                }
            }
            $this->Kozurit();
        }           

  
/**
 * Mutat_pb_fut - ez a nyitólap
*/
        function Mutat_pb_fut()
        {
                $Param=array();
                
            /*    
                $Param["Nyito1"]=$this->Futtat(Nyito_azon1)->Felsomenube();
                $Param["Nyito2"]=$this->Futtat(Nyito_azon2)->Felsomenube();
                $Param["Nyito3"]=$this->Futtat(Nyito_azon3)->Felsomenube();
                $Param["Nyito4"]=$this->Futtat(Nyito_azon4)->Felsomenube();
                
                    $Obj=$this->ObjektumLetrehoz(Hirek_azon,0);
                    $Ered=$Obj->Futtatgy("CSOPORT","VZ_SORREND_I desc",null,null," and AKTIV_I='1' ")->Adatlistkozep_publ(true);
                    $Param["Hirek"]=$Ered["Eredm"];         
*/
                

                return $this->Sablonbe("Oldal_nyito",$Param);
        }
                

        function Nyitolapon()
        {
            $Csoport=array();
            $Azon=self::$Sql->Lekerst("select VZ_AZON_I from VAZ inner join SZOVEG on VAZ.VZ_TABLA_S='SZOVEG' and VZ_TABLA_AZON_I=SZOVEG.AZON_I where VZ_SZINKRONIZALT_I='1' and NYITON_I='1' and AKTIV_I='1' order by AZON_I asc");
            if ($Azon)
            {
                foreach ($Azon as $egy)
                {
                    $Csoport[]=$this->Futtat($egy["VZ_AZON_I"])->Adatlistkozep_publ(false);
                }
            }

            
            return $Csoport;
            
            
        }


        function Quapcbe_pb_fut()
        {
                 $szam1=rand(0,15);
                 $szam2=rand(0,15);


            $this->AllapotBe("KOD",($szam1+$szam2));
            $Kodir=$szam1."+".$szam2."=";
            $this->AllapotBe("KODIR",$Kodir);

            $Vissza="<form name='Kform' id='Kform' method='post' action='?' target='_parent'>
            <input type='hidden' name='Esemeny_Uj' id='Esemeny_Uj' value='".$this->EsemenyHozzad("Ajanlekuld_pb_fut")."' />
            <p align=center>@@@Kérjük írja be a művelet eredményét:§§§<br><br>
             <input type='text' name='KOD_FORM' id='KOD_FORM' >
            <br><br>
            <img src='".$this->EsemenyHozzad("Kodir_pb")."' border='0' /> <br><br>
            <input type='submit' value='@@@Elküld§§§''>
            </p>
            </form>";
            $DATA["Kodlink"]=$this->EsemenyHozzad("Kodir_pb");
            return $Vissza;
            
        }

        function Ajanlekuld_pb_fut()
        {
            $Jo=true;
            
            $NEV=$this->Postgetv_jegyez("NEV",0);    
            $EMAIL=$this->Postgetv_jegyez("EMAIL",0);    
            $TARGY=$this->Postgetv_jegyez("TARGY",0);    
            $SZOVEG=$this->Postgetv_jegyez("SZOVEG",0);  
            
                $KOD_FORM=$this->Postgetv("KOD_FORM");
                if (!isset($KOD_FORM))$KOD_FORM="";
                $Kod=$this->AllapotKi("KOD");
                if (("$KOD_FORM"!="$Kod")||($KOD_FORM=="")||("$KOD_FORM"=="0")||($Kod=="0")||($Kod==""))
                {
                    //  $this->ScriptUzenetAd("@@@Hiba az ellenőrző kód megadásánál§§§");
                        $Jo=false;
                }
                if (!($Jo))
                {
                    $Kodbe=$this->EsemenyHozzad("Quapcbe_pb_fut");
                    $Plusz="<a href='$Kodbe' id='Qapchbe' class='zoomnyit' rel='iframe' data-fancybox-type='iframe'></a>
                    <script>
$(document).ready(function(){
    
    $('a.zoomnyit').fancybox({
        maxWidth    : 1600,
        maxHeight   : 200,
        padding     : 0,
        fitToView   : false,
        width       : '700',
        height      : '300',
        autoSize    : false,
        closeClick  : false,
        openEffect  : 'none',
        scrolling   : 'none',
        closeEffect : 'none'

    });
                        
                    $('#Qapchbe').click();
    })
                    
                    </script>";
                    return $this->Mutat_pb_fut($Plusz);
                }
                
                $Tema=MAIL_TARGY." @@@ajánlatfelvétel§§§";
                $Level="@@@név§§§: $NEV <br>
                @@@e-mail cím§§§: $EMAIL <br>
                @@@árajánlat tárgya§§§: $TARGY <br>                
                @@@árajánlat szövege§§§ : $SZOVEG ";
                $this->Mailkuld($Tema,$Level,OLDALEMAIL);
                $this->ScriptUzenetAd("@@@Üzenet elküldve!§§§");

                
            return $this->Mutat_pb_fut();                
            
        }

        
        function Adat_adm_vissza()
        {
            
        }

        function Adat_adm_menu()
        {
            $Nyelvek=$this->Tombre(NYELVEK);

            if(count($Nyelvek)<2)
            {
                $Vissza[0]["Nev"]="Kulcsszavak szerkesztése";
                $Vissza[0]["Link"]=$this->EsemenyHozzad("UrlapKi");
                return $Vissza;
            }
        }
        function Metatags_seged()
        {
            $Nyelvek=$this->Tombre(NYELVEK);
            if(count($Nyelvek)<2)
            {
                $Vissza=$this->Metatags_seged2();
            }else
            {

            $Vissza["Title"]="";
            $Vissza["Key"]="";
            $Vissza["Desc"]="";

                $Eredm=$this->Futtat($this->Gyerekparam("CSOPORT",null,0,null," and VZ_NYELV_S='".$this->Nyelvadpub()."' and VZ_OBJEKTUM_S='CNyelvElagazCsoport'"))->Metatags_seged2();


               if ($Eredm["Ossz"]>0) $Vissza=$Eredm["Eredm"][0];
             }                

            
            
            return $Vissza;
        }
 
         
/**
 * RekurzivTorol - üres hogy véletlenül se lehessen törölni
 */
        function RekurzivTorol()
        {
        }


        function Hiba404()
        {
            
                header('Location: ' . OLDALCIM, true, 303);
                exit;
                            header('HTTP/1.0 404 Not Found');
                            echo "<h1>404 Not Found</h1>";
                            echo "The page that you have requested could not be found.";
                                exit();                            
            
        }


        function FaceInit($Data)
        {
            $this->Futas_indit();
        
            $Vissza="";
            if (isset($Data["id"])&&($Data["id"]!="")&&($Data["id"]!="0"))
            {
                $Vissza=$this->Futtat(Felhcsop_azon)->Face_login($Data);
                if ($Vissza!="")
                {
                    $Vissza=$this->Futtat(Nyelv_azon)->Cserel($Vissza);
                    $_SESSION["Korvalaszt"]=1;
                }    
            }

            if ($Vissza=="")$Vissza=$this->Fofutas($this,"Mutat_pb_fut");
            
            $this->Futas_vege(1);
            
            return $Vissza;
        }
        
        
        function Init()
        {
                $Esemeny_Uj=$this->Postgetv("Esemeny_Uj");


                 if (isset($Esemeny_Uj))
                 {
                    $Esemeny=$this->EsemenyFelold($Esemeny_Uj);
                    if (($Esemeny["Objazon"]=="0")||($Esemeny["Objazon"]==""))
                    {
                        $this->Hiba404();
                    }
                 }else
                 {
                    $Esemeny["Objazon"]=0;
                    $Esemeny["Feladat"]="";
                 }
                if($Esemeny["Objazon"]!="0")
                {
                    $Oldal=$this->Fofutas($Esemeny["Objazon"],$Esemeny["Feladat"]);                    
                }else
                {
                    $Oldal=$this->Fofutas($this,"Mutat_pb_fut");
                }
                return $Oldal;
        }
        
        function Mellekfutas($Obj,$Feladat,$Param="",$Param2="")
        {
            
                $this->Futas_indit();
                $Oldal=$Obj->$Feladat($Param,$Param2);
                

                if (Nyelv_azon!="0")
                {
                    $Oldal=$this->Futtat(Nyelv_azon)->Cserel($Oldal);
                }
                

                $this->Futas_vege(1);
            

            return $Oldal;                    
        }
                
        function Fofutas($Objazon,$Feladat)
        {
            $Kelltrans=true;
            if ($Feladat=="Termek_karba_pb_fut")$Kelltrans=false;
            if ($Feladat=="Lista_fut")$Kelltrans=false;
            
            
            if ($Feladat=="Kosarelkuld_fut")
            {
                $Kelltrans=false;
                $GLOBALS["Nemkelltrans"]=1;
            }
            
                        
            if ($this->Fofutoe($Feladat))
            {

                $this->Futas_indit($Kelltrans);
                
                $Oldal=$this->Futtat($Objazon)->$Feladat();

                if (Nyelv_azon!="0")
                {
                    $Oldal=$this->Futtat(Nyelv_azon)->Cserel($Oldal);
                }
                

                $this->Futas_vege(1,$Kelltrans);
            }else $Oldal="";

            return $Oldal;                    
        }


        function Urlapki_fut()
        {

                $Tarol=$this->EsemenyHozzad("Tarol");

                $Form=new CForm("CsoportForm",$this->EsemenyHozzad("Tarol"),"");
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

            $this->Szinkronizal();
            
            return $this->Futtat($this)->Lista_fut();
            
        }  
        
 

        public function Adatvissza_admin()
        {
            return "";
        } 

}


?>
