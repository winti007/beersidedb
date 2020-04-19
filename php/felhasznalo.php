<?php

class CFelhasznalo extends CKapcsform
{

    var $Tabla_nev="FELHASZNALO";
        
 /**
 * Ckeditor_jog_be - ckeditor file kezelőjéhez jogosultságot beállítja CKEDITOR_JOG sessionba
 */
        public function Ckeditor_jog_be()
        {
                if ($this->Jogosultsag()>=99)$this->Sessionbe("CKEDITOR_JOG",1);
                                        else $this->Sessionbe("CKEDITOR_JOG",0);
        }
                


function Kedvurit()
{
    $AZON=$this->AzonAd();
    self::$Sql->Modosit("delete from KEDVENC_TERM where FELH_VZ_AZON_I='$AZON' ");
}

function Kedvbevane($Term)
{
    $AZON=$this->AzonAd();
    $Volt=self::$Sql->Lekerst("select * from KEDVENC_TERM where FELH_VZ_AZON_I='$AZON' and TERMEK_TB_AZON_I='".$Term->TablaAdatKi("AZON_I")."' ");

    if ($Volt)return true;
    return false;
}

function Kedvencbe($Termobj)
{
    if (!($this->Kedvbevane($Termobj)))
    {
        $TBAZON=$Termobj->TablaAdatKi("AZON_I");
        $AZON=$Termobj->AzonAd();
        $FELH_VZ_AZON=$this->AzonAd();
        $FELH_TB_AZON=$this->TablaAdatKi("AZON_I");
        self::$Sql->Modosit("insert into KEDVENC_TERM (TERMEK_TB_AZON_I,TERMEK_VZ_AZON_I,FELH_VZ_AZON_I,FELH_TB_AZON_I) values ('$TBAZON','$AZON','$FELH_VZ_AZON','$FELH_TB_AZON');");
    }
    
}

function Kedvki($Termobj)
{
    
    $AZON=$this->AzonAd();
    self::$Sql->Modosit("delete from KEDVENC_TERM where FELH_VZ_AZON_I='$AZON'  and TERMEK_TB_AZON_I='".$Termobj->TablaAdatKi("AZON_I")."' ");
    
}

function Kedvencek($Limit="")
{
    $Vissza=false;
    $AZON=$this->AzonAd();
    $Ossz=self::$Sql->Lekerst("select count(FELH_VZ_AZON_I) as db from KEDVENC_TERM where FELH_VZ_AZON_I='$AZON' ");
    $Ossz=$Ossz[0]["db"];
    
    $Azonok=self::$Sql->Lekerst("select * from KEDVENC_TERM where FELH_VZ_AZON_I='$AZON' $Limit");
    if ($Azonok)
    {
        foreach ($Azonok as $egy)
        {
            $Sql="select VZ_AZON_I from VAZ,TERMEK where VZ_TABLA_S='TERMEK' and VZ_TABLA_AZON_I=AZON_I and VZ_SZINKRONIZALT_I='1' and VZ_TABLA_AZON_I='".$egy["TERMEK_TB_AZON_I"]."'";
            $Termazon=self::$Sql->Lekerst($Sql);
                                //$Obj=$this->ObjektumLetrehoz_HibaNelkul($egy["AZON"],0);
            if ($Termazon)
            {
                $Obj=$this->ObjektumLetrehoz($Termazon[0]["VZ_AZON_I"],0);
                $Vissza[]=$Obj->Adatlistkozep_publ();
            }
            
        }
    }
    $Vtomb["Tetel"]=$Vissza;
    $Vtomb["Db"]=$Ossz;

    return $Vtomb;
}


        function NevAd()
        {
            
                $Vissza=$this->TablaAdatKi("SZAML_NEV_S")." ".$this->TablaAdatKi("SZAML_KERNEV_S");
                $Vissza=trim($Vissza);
                
                if ($Vissza=="")$Vissza=$this->TablaAdatKi("LOGIN_S");
                return $Vissza;
        }

        function Nagykeres()
        {
            return 0;
        }

        function EmailAd()
        {
                $Vissza=$this->TablaAdatKi("EMAIL_S");
                return $Vissza;
        }
        
        function Sessionad()
        {
            return "";
            
        }        

        function Jogokad()
        {
            return array();
        }
        
        
 /**
 * Ujjelszogeneral - új jelszót generál és elküldi az email címre
 */
    
        function Ujjelszogeneral()
        {
                
                $JELSZO="";
                $kodbetu="qwertzuiopasdfghjklyxcvbnmQWERTZUIOPASDFGHJKLYXCVBNM";
                $betuhossz=strlen($kodbetu);
                for ($c=0;$c<5;$c++)
                {
                        $rand=rand(0,$betuhossz-1);
                        $JELSZO.=substr($kodbetu,$rand,1);
                }
                $this->TablaAdatBe("JELSZO_S",md5($JELSZO));
                $this->TablaTarol();

                $Targy=MAIL_TARGY." Jelszó úrjaküldése";
                $Nev=$this->TablaAdatKi("LOGIN");

                $Jelszo=$this->TablaAdatKi("JELSZO");
                $EMAIL=$this->TablaAdatKi("EMAIL_S");
                $Uzenet="Email cím: $EMAIL <br>Jelszó: $JELSZO";
                $this->EmailKuld($Targy,$Uzenet);
        }

   
        function FelsoBelepmenu()
        {
            
            $Vissza=array();
            if ($this->Jogosultsag()>=99)
            {
                $Vissza=$this->Belepmenu();
/*                $FelhCsoport=$this->ObjektumLetrehoz(2,0);
                $Be=$FelhCsoport->EsemenyHozzad("Belepablak_pb_fut");
                $Vissza[]=array("Nev"=>"@@@FIÓKOM§§§","Link"=>"#");*/
            }else
            if ($this->Jogosultsag()>0)
            {
                
                return $this->Belepmenu();
                $FelhCsoport=$this->ObjektumLetrehoz(2,0);
                $Be=$FelhCsoport->EsemenyHozzad("Mutat_fut");
                $Vissza[]=array("Nev"=>"@@@FIÓKOM§§§","Link"=>"$Be");
            }else
            {
                $FelhCsoport=$this->ObjektumLetrehoz(2,0);
                $Be=$FelhCsoport->EsemenyHozzad("Belepablak_pb_fut");
                $Vissza[]=array("Nev"=>"@@@BEJELENTKEZÉS§§§","Link"=>$Be);
            }

            
            return $Vissza;
        }
           
        function Belepmenu()
        {
            $Vissza=array();
            return $Vissza;
        }
        
       
       function Adatfrissit_rendbol($DATA)
       {
            $Vissza=false;
            $Vissza=(($Vissza)||($this->Egyfrissit("SZAML_NEV_S",$DATA)));
            $Vissza=(($Vissza)||($this->Egyfrissit("SZAML_IRSZAM_S",$DATA)));
            $Vissza=(($Vissza)||($this->Egyfrissit("SZAML_VAROS_S",$DATA)));
            $Vissza=(($Vissza)||($this->Egyfrissit("SZAML_CIM_S",$DATA)));
            $Vissza=(($Vissza)||($this->Egyfrissit("SZALL_NEV_S",$DATA)));
            $Vissza=(($Vissza)||($this->Egyfrissit("SZALL_IRSZAM_S",$DATA)));
            $Vissza=(($Vissza)||($this->Egyfrissit("SZALL_VAROS_S",$DATA)));
            $Vissza=(($Vissza)||($this->Egyfrissit("SZALL_CIM_S",$DATA)));
            $Vissza=(($Vissza)||($this->Egyfrissit("TELSZAM_S",$DATA)));
            $Vissza=(($Vissza)||($this->Egyfrissit("SZAML_KERNEV_S",$DATA)));
            $Vissza=(($Vissza)||($this->Egyfrissit("SZAML_CEGNEV_S",$DATA)));
            $Vissza=(($Vissza)||($this->Egyfrissit("ADOSZAM_S",$DATA)));
            $Vissza=(($Vissza)||($this->Egyfrissit("SZALL_KERNEV_S",$DATA)));
            $Vissza=(($Vissza)||($this->Egyfrissit("SZALL_CEGNEV_S",$DATA)));
            
            
            if ($Vissza)
            {
                $this->Tablatarol();  
                
                $this->Sessbe("Aktfelh",$this); 
            }
             
       }
       
       function Egyfrissit($MEZO,$DATA)
       {
            $Vissza=0;
            if ($this->TablaAdatKi($MEZO)=="")
            {
                $this->TablaAdatBe($MEZO,$DATA[$MEZO]);
                $Vissza=1;
            }
            return $Vissza;
       }
               

        function BelepesTortent_alap($Alert=true)
        {
            $this->Sessbe("Aktfelh",$this);
        }

        function BelepesTortent($Alert=true)
        {
            $this->Sessbe("Aktfelh",$this);
        }

        function Kijelentkezes()
        {
            if (isset($_SESSION["Cookifile"]))$_SESSION["Cookifile"]="";
            $this->AllapotBe("Bedata",0);
        }

        function Jogosultsag()
        {
                return 0;
        }
}


class CSenkiFelhasznalo extends CFelhasznalo
{

        function BelepesTortent($Alert=true)
        {
            parent::BelepesTortent($Alert);
            
        }
        
        function Belepmenu()
        {
            $Vissza=array();
            
            if ($this->Jogosultsag()>0)
            {
                $FelhCsoport=$this->ObjektumLetrehoz(2,0);
                $KiLink=$FelhCsoport->EsemenyHozzad("Kijelentkezes_pb_fut");
                $Vissza[]=array("Nev"=>$this->NevAd(),"Link"=>"#");
                $Vissza[]=array("Nev"=>"@@@Kilépés§§§","Link"=>$KiLink);
            }

            
            return $Vissza;
        }
        

        function Jogosultsag()
        {
            return 0;
        }
                
        function NevAd()
        {

            return "";
            
        }
        

                

}

class CAdminFelhasznalo extends CFelhasznalo
{


        function Lista()
        {
                $Fo=$this->ObjektumLetrehoz(1,0);
                return $Fo->Lista();
        }

        function Jogokad()
        {
            $Vissza=array();
            $Tomb=$this->Tombre(JOG_MENU);
            foreach ($Tomb as $egy)
            {
                if ($egy[1]!="")
                {
                    $Vissza[$egy[1]]=$egy[1];
                }
            }
            return $Vissza;
        }
        
        function Belepmenu()
        {
            $Fo=$this->ObjektumLetrehoz(1,0);
            $Lista=$Fo->EsemenyHozzad("Lista");
//            $Vissza[]=array("Nev"=>"Főoldal","Link"=>"/main.php");
            $Vissza[]=array("Nev"=>"Adminisztráció","Link"=>$Lista);
            
            $FelhCsoport=$this->ObjektumLetrehoz(2,0);
            $KiLink=$FelhCsoport->EsemenyHozzad("Kijelentkezes_pb_fut");
            $Vissza[]=array("Nev"=>"@@@Kilépés§§§","Link"=>$KiLink);

            
            return $Vissza;
        }
        

        function BelepesTortent($Alert=true)
        {
            parent::BelepesTortent($Alert);

            $this->ScriptUzenetAd("Adminisztrátor belépett az oldalra.");
            $Fo=$this->ObjektumLetrehoz(Focsop_azon,0);
            return $this->Futtat(Focsop_azon)->Lista_fut();
                
        }


        function Jogosultsag()
        {
                return 99;
        }
}
class CDebugFelhasznalo extends CAdminFelhasznalo
{



        function Jogosultsag()
        {
                return 100;
        }

}
class CSimaFelhasznalo extends CFelhasznalo
{


        static function Adatoktarol(&$Obj,$Mailis=true)
        {
            $Obj->TablaAdatBe("SZAML_NEV_S",$Obj->Postgetv("SZAML_NEV_S"));
            $Obj->TablaAdatBe("SZAML_IRSZAM_S",$Obj->Postgetv("SZAML_IRSZAM_S"));
            $Obj->TablaAdatBe("SZAML_VAROS_S",$Obj->Postgetv("SZAML_VAROS_S"));
            $Obj->TablaAdatBe("SZAML_CIM_S",$Obj->Postgetv("SZAML_CIM_S"));

            $Obj->TablaAdatBe("SZALL_NEV_S",$Obj->Postgetv("SZALL_NEV_S"));
            $Obj->TablaAdatBe("SZALL_IRSZAM_S",$Obj->Postgetv("SZALL_IRSZAM_S"));
            $Obj->TablaAdatBe("SZALL_VAROS_S",$Obj->Postgetv("SZALL_VAROS_S"));
            $Obj->TablaAdatBe("SZALL_CIM_S",$Obj->Postgetv("SZALL_CIM_S"));
            $Obj->TablaAdatBe("TELSZAM_S",$Obj->Postgetv("TELSZAM_S"));

            $Obj->TablaAdatBe("SZAML_KERNEV_S",$Obj->Postgetv("SZAML_KERNEV_S"));
            $Obj->TablaAdatBe("SZAML_CEGNEV_S",$Obj->Postgetv("SZAML_CEGNEV_S"));
            $Obj->TablaAdatBe("ADOSZAM_S",$Obj->Postgetv("ADOSZAM_S"));
            $Obj->TablaAdatBe("SZALL_KERNEV_S",$Obj->Postgetv("SZALL_KERNEV_S"));
            $Obj->TablaAdatBe("SZALL_CEGNEV_S",$Obj->Postgetv("SZALL_CEGNEV_S"));


            if ($Mailis)$Obj->TablaAdatBe("EMAIL_S",$Obj->Postgetv("EMAIL_S"));
            $Obj->Tablatarol();        
        }

        

        function Belepmenu()
        {
//            $Vissza[]=array("Nev"=>"Főoldal","Link"=>"/main.php");

            
            
            
            
            $Vissza[]=array("Nev"=>$this->NevAd()." profil","Link"=>$this->EsemenyHozzad("Profil_fut"));
            

            $FelhCsoport=$this->ObjektumLetrehoz(2,0);
            $KiLink=$FelhCsoport->EsemenyHozzad("Kijelentkezes_pb_fut");
            $Vissza[]=array("Nev"=>"@@@Kilépés§§§","Link"=>$KiLink);

            
            return $Vissza;
        }
                


         function Aktivalas_pb_fut()
        {
              $AktivaloKod=$this->Postgetv("AktivaloKod");

                $Tartalom="";
                if ($this->TablaAdatKi("AKTIVALO_KOD_S")=="Aktiválva")
                {
                        $this->ScriptUzenetAd("Már aktiválta magát!");
                        $Uzenet="Már aktiválta magát!";
                }
                else if ($AktivaloKod==$this->TablaAdatKi("AKTIVALO_KOD_S"))
                {
                        $this->TablaAdatBe("AKTIVALO_KOD_S","Aktiválva");
                        $this->TablaAdatBe("AKTIV_I",1);
                        $this->Szinkronizal();
                        $Uzenet="Sikeres aktiválta magát. Most már bejelentkezhet!";
//                        $$this->ScriptUzenetAd("Sikeres aktiválta magát. Most már bejelentkezhet!");
                }else
                {
                        $Uzenet="Rossz aktiváló kód!";
                }
                header("Refresh:5; url=".OLDALCIM."main.php",true);
                /*
<head><META HTTP-EQUIV='Refresh'
      CONTENT='3; URL=".OLDALCIM."main.php'>
      </head>
       <body>                
                */
                if ($Uzenet!="")$this->ScriptUzenetAd($Uzenet);
                return $this->Futtat(Focsop_azon)->Mutat_pb_fut();
                
                $Vissza="
$Uzenet <br>
                <p align=center><a href='".OLDALCIM."main.php' class=publikuslink>Vissza a nyitólapra</a>";
                return MunkaTerulet("Aktiválás",$Vissza);

        }

        function Regisztral_pb_fut()
        {
            
            $Data=$this->OsszesTablaAdatVissza();
            $Data["Rendkuld"]=$this->EsemenyHozzad("Regisztral_pb_tarol");
            $Vissza=$this->Sablonbe("Regisztral",$Data);
            
            return $this->Sablonbe("Oldal",array("Tartalom"=>$Vissza));

    
        }
        
        function Regadatbe($LOGIN,$EMAIL,$JELSZO)
        {
            $this->TablaAdatBe("LOGIN_S",$LOGIN);
            $this->TablaAdatBe("EMAIL_S",$EMAIL);
            $this->TablaAdatBe("AKTIV_I",1);
            $this->TablaAdatBe("JELSZO_S",md5($JELSZO));
            $this->Szinkronizal();

            
        }
        
        function Facedatabe($Data)
        {
            $EMAIL=$Data["email"];
            if($this->EmailCimLetzik($EMAIL,$this->TablaAdatKi("AZON_I")))
            {
                
            }else
            {
                $this->TablaAdatBe("EMAIL_S",$EMAIL);    
            }
            $this->TablaAdatBe("SZAML_NEV_S",$Data["first_name"]);
            $this->TablaAdatBe("SZAML_KERNEV_S",$Data["last_name"]);
            $this->TablaAdatBe("LOGIN_S",$Data["name"]);
            $this->TablaAdatBe("FACE_ID_I",$Data["id"]);
            $this->TablaAdatBe("AKTIV_I",1);
            $this->Szinkronizal();
              
        }

        
        function Konstrveg_uj()
        {
            $this->TablaAdatBe("SESSID_S",session_id());
        }

        function Regisztral_pb_tarol_fut()
        {
                $this->Adatoktarol($this);

                $Jo=1;
                $EMAIL=$this->Postgetv("EMAIL_S");
                $JELSZO=$this->Postgetv("JELSZO_S");
                $JELSZO2=$this->Postgetv("JELSZO_S2");
                if($this->EmailCimLetzik($EMAIL,$this->TablaAdatKi("AZON_I")))
                {
                        $this->ScriptUzenetAd("@@@A választott e-mail címmel már valaki regisztrált nálunk. Válasszon másikat!§§§");
                        $Jo=0;
                }else
                if ($EMAIL=="")
                {
                        $this->ScriptUzenetAd("@@@Az e-mail mező nem lehet üres!§§§");
                        $Jo=0;
                }else
                if (($JELSZO=="")||($JELSZO2==""))
                {
                        $this->ScriptUzenetAd("@@@A jelszó mező nem lehet üres!§§§");
                        $Jo=0;
                }else
                if ($JELSZO!=$JELSZO2)
                {
                        $this->ScriptUzenetAd("@@@A jelszó mezőknek egyeznie kell!§§§");
                        $Jo=0;
                }
                    
                
                if ($Jo)
                {
                    $Kod="";
                    for($i=0;$i<12;$i++)
                    {
                            $Kod.=chr(rand(65,90));
                    }
                    $this->TablaAdatBe("AKTIVALO_KOD_S",$Kod);
                    $this->TablaAdatBe("JELSZO_S",md5($this->Postgetv("JELSZO_S")));
                    $this->Szinkronizal();
                                
                    $Aktival=$this->EsemenyHozzad("Aktivalas_pb_fut");
                    $Targy=MAIL_TARGY." Értesités regisztrációról";
                    $Szoveg="Sikeresen regisztrálta magát az oldalunkra.<br>
                                Név: ".$this->NevAd()."<br>
                                E-mail cím: ".$this->TablaAdatKi("EMAIL_S")."<br>
                                ";
                    $Szoveg.="<br>Az aktiváláshoz kattintson az alábbi linkre:<a target='_blank' href=".OLDALCIM."$Aktival?AktivaloKod=".$this->TablaAdatKi("AKTIVALO_KOD_S").">Aktiválás</a>
                                <br>
                                Ha az aktiválás során technikai nehézségbe üközik, az <a href=mailto:".OLDALEMAIL.">".OLDALEMAIL."</a> címen kérhet segítséget";

                    $Maildat["Targy"]=$Targy;
                    $Maildat["Szoveg"]=$Szoveg;
                        $Szoveg=$this->Sablonbe("Hirlevelbe",$Maildat);
                        $this->EmailKuld($Targy,$Szoveg);

                        $kinek  = "<".OLDALEMAIL.">";

                        $Tema=MAIL_TARGY." Új felhasználó regisztrálása";
                        $LEVEL_SZOVEG="
                        Felhasználó neve: ".$this->NevAd()."<br>
                        E-mail cím: ".$this->TablaAdatKi("EMAIL_S")."<br>

                        ";
                        $this->Mailkuld($Tema,$LEVEL_SZOVEG,OLDALEMAIL);

                        $this->ScriptUzenetAd("A regisztráció sikerült. Az aktiváláshoz kérjük ellenőrizze a postaládáját.");
                        $Eredmeny=$this->VisszaLep();
                }
                else
                {
                    $this->TablaTarol();
                    $Eredmeny=$this->Futtat($this)->Regisztral_pb_fut();
                }

                return $Eredmeny;
        }
        
        public function Hozzafer($Feladat)
        {
            if (mb_substr($Feladat,mb_strlen($Feladat)-7,7,STRING_CODE)=="_pb_fut")return true;
            
            $AZON=$this->SessAd("Aktfelh")->AzonAd();
            $FELHASZNALO_VZ_AZON_I=$this->AzonAd();
            
            $Vissza=false;
            $JOG=$this->SessAd("Aktfelh")->Jogosultsag();
            if ($JOG>=99)$Vissza=true;
            else
            if (("$FELHASZNALO_VZ_AZON_I"=="$AZON")&&($JOG>0))$Vissza=true;
            {
                $SESSID_S=$this->TablaAdatKi("SESSID_S");
                $Rege=mb_strpos($Feladat,"Regisztral");
                if (!($Rege===false))
                {
                    if ("$SESSID_S"==session_id())$Vissza=true;
                }
            }
                   

            return $Vissza;
        }

        function Profil_tarol_fut()
        {
                $this->Adatoktarol($this,false);
                
                $Jo=1;
                $EMAIL=$this->Postgetv("EMAIL_S");
                $JELSZO_S=$this->Postgetv("JELSZO_S");
                if ($JELSZO_S!="")$this->TablaAdatBe("JELSZO_S",md5($JELSZO_S));
                
                $this->TablaAdatBe("HIRLEVEL_I",$this->Postgetv("HIRLEVEL_I",1));
                
                if ($Jo)
                {
                    $this->Szinkronizal();
                }else
                {
                    $this->Szinkronizal();
                }
                    
                    $this->Futtat(Kosar_azon)->Kosardatafrissul();
                                    
                return $this->VisszaLep();            
        }

        function Profil_fut()
        {
            $Data=$this->OsszesTablaAdatVissza();
            $Data["Rendkuld"]=$this->EsemenyHozzad("Profil_tarol");
            $Data["Visszalink"]=$this->VisszaEsemenyAd();
            
            $Vissza=$this->Sablonbe("Profil",$Data);
            
            return $this->Sablonbe("Oldal",array("Tartalom"=>$Vissza));
        }

        function Urlapki_fut()
        {
            $Data=$this->OsszesTablaAdatVissza();
            $Data["Rendkuld"]=$this->EsemenyHozzad("Urlapki_tarol");
            $Data["Visszalink"]=$this->VisszaEsemenyAd();
            
            $Vissza=$this->Sablonbe("Urlapki",$Data);
            
            return $this->Sablonbe("Oldal",array("Tartalom"=>$Vissza));


        }
        
        function Urlapki_tarol_fut()
        {
            $Elozmail=$this->TablaAdatKi("EMAIL_S");
                $this->Adatoktarol($this);
                
                $Jo=1;
                $this->TablaAdatBe("AKTIV_I",$this->Postgetv("AKTIV_I",1));
                $this->TablaAdatBe("HIRLEVEL_I",$this->Postgetv("HIRLEVEL_I",1));
                
                $LOGIN_S=$this->Postgetv("LOGIN_S");
                
                
                
                $EMAIL=$this->Postgetv("EMAIL_S");
                $JELSZO_S=$this->Postgetv("JELSZO_S");
                if ($JELSZO_S!="")$this->TablaAdatBe("JELSZO_S",md5($JELSZO_S));
                if ($LOGIN_S!="")
                {
                    if($this->LoginLetzik($LOGIN_S,$this->TablaAdatKi("AZON_I")))
                    {
                            $this->ScriptUzenetAd("A választott belépési név már foglalt. Válasszon másikat!");
                            $Jo=0;
                    }
                }
                if($this->EmailCimLetzik($EMAIL,$this->TablaAdatKi("AZON_I")))
                {
                        $this->ScriptUzenetAd("@@@A választott e-mail címmel már valaki regisztrált nálunk. Válasszon másikat!§§§");
                        $Jo=0;
                }else
                if ($EMAIL=="")
                {
                        $this->ScriptUzenetAd("@@@Az e-mail mező nem lehet üres!§§§");
                        $Jo=0;
                }
                
                if ($Jo)
                {
                    $this->TablaAdatBe("LOGIN_S",$LOGIN_S);
                    $this->Szinkronizal();
                }else
                {
                    $this->TablaAdatBe("EMAIL_S",$Elozmail);
                    $this->Szinkronizal();
                }
                    
                                    
                return $this->VisszaLep();

        }

        function JelszoUrlapKi()
        {
                if (($this->AzonAd()!=$_SESSION["AktivFelhasznalo"]->AzonAd()))return CVaz::AlapFeladat();

                $this->AllapotBe("Pozicio","Felhasználó adatlapja");
                $this->AllapotBe("Feladat","JelszoUrlapKi");
                $this->Nullaz();

                $ProfilTarol=$this->EsemenyHozzad("JelszoTarol");
                $Megsem=$this->VisszaEsemenyAd();

                $this->ScriptTarol("
        function Ellenor()
        {
        if(document.Regisztral.UJ_JELSZO.value=='')
        {
                alert(\"@@@Az új jelszó mező nem lehet üres!§§§\");
                document.Regisztral.UJ_JELSZO.focus();
        }
        else if(document.Regisztral.UJ_JELSZO.value!=document.Regisztral.UJ_JELSZO2.value)
        {
                alert(\"@@@A jelszó mezőknek egyeznie kell!§§§\");
                document.Regisztral.JELSZO.focus();
        }
        else return true;

        return false;
        }

                ");
                $Form=new CForm("Regisztral","","");
                $Form->FormTagHidden("Esemeny_Uj",$ProfilTarol);

                $Form->FormTagPassword("*@@@Régi jelszó§§§:","REGI_JELSZO","","");
                $Form->FormTagPassword("*@@@Jelszó§§§:","UJ_JELSZO","","");
                $Form->FormTagPassword("*@@@Jelszó mégegyszer§§§:","UJ_JELSZO2","","");

                $Form->FormTagCsakSzoveg(" ","<img src='@@@/images/tarol.gif§§§' style=\"cursor:hand\" onclick=\"if (Ellenor())document.Regisztral.submit();\">&nbsp;<img onclick=\"location.href='$Megsem'\" style=\"cursor:hand\" src='@@@/images/megsem.gif§§§' >",1);
                $Tartalom=$Form->OsszeRak();


                $Cim="@@@Jelszó változtatás§§§";

                return Munkaterulet($Cim,$Tartalom);
        }

        function JelszoTarol()
        {

                if (($this->AzonAd()!=$_SESSION["AktivFelhasznalo"]->AzonAd()))return CVaz::AlapFeladat();

                $REGI_JELSZO=Postgetvaltozo("REGI_JELSZO");
                $UJ_JELSZO=Postgetvaltozo("UJ_JELSZO");
                $UJ_JELSZO2=Postgetvaltozo("UJ_JELSZO2");

                $Jo=true;
                if (md5($REGI_JELSZO)!=$this->TablaAdatKi("JELSZO"))
                {
                        if ($Jo)$this->ScriptUzenetAd("A régi jelszó nem egyezik!");
                        $Jo=false;
                }
                if ($UJ_JELSZO=="")
                {
                        if ($Jo)$this->ScriptUzenetAd("Az új jelszó nem lehet üres!");
                        $Jo=false;
                }
                if ($UJ_JELSZO!=$UJ_JELSZO2)
                {
                        if ($Jo)$this->ScriptUzenetAd("Az új jelszók nem egyeznek!");
                        $Jo=false;
                }
                if ($Jo)
                {
                        $this->TablaAdatBe("JELSZO",md5($UJ_JELSZO));
                        $this->TablaTarol();
                        $_SESSION["AktivFelhasznalo"]=$this;

                        $this->ScriptUzenetAd("Jelszó átírva!");
                        return $this->VisszaLep();
                }else return $this->JelszoUrlapKi();
        }

        function ProfilUrlapKi()
        {
                if (($this->AzonAd()!=$_SESSION["AktivFelhasznalo"]->AzonAd()))return CVaz::AlapFeladat();

                $this->AllapotBe("Pozicio","Felhasználó adatlapja");
                $this->AllapotBe("Feladat","ProfilUrlapKi");

                $this->Nullaz();
                $EMAIL=$this->Tabla->AdatKi("EMAIL");
                $LOGIN=$this->Tabla->AdatKi("LOGIN");

                $ProfilTarol=$this->EsemenyHozzad("ProfilTarol");
                $Megsem=$this->VisszaEsemenyAd();
                $NEV=$this->TablaAdatKi("NEV");
                $CIM=$this->TablaAdatKi("CIM");
                $TELSZAM=$this->TablaAdatKi("TELSZAM");

                $this->ScriptTarol("
        function Ellenor()
        {
        if(document.Regisztral.EMAIL.value=='')
        {
                alert(\"Az e-mail mező nem lehet üres!\");
                document.Regisztral.EMAIL.focus();
        }
        else return true;

        return false;
        }
                ");
                $Form=new CForm("Regisztral","","");
                $Form->FormTagHidden("Esemeny_Uj",$ProfilTarol);
                $Form->FormTagHidden("Submitg","");
                $Form->FormTagTextBox("*@@@Belépési név§§§:","LOGIN","$LOGIN","disabled=true");

                $Form->FormTagCsakSzoveg(" ","@@@Számlázási cím§§§ ");
                $Form->FormTagTextBox("@@@Név§§§:","NEV","$NEV","");

                $Form->FormTagTextBox("@@@Irányítószám§§§:","SZAML_IRSZAM",$this->TablaAdatKi("SZAML_IRSZAM"),"");
                $Form->FormTagTextBox("@@@Város§§§:","SZAML_VAROS",$this->TablaAdatKi("SZAML_VAROS"),"");
                $Form->FormTagTextArea("@@@Cím§§§:","SZAML_CIM",$this->TablaAdatKi("SZAML_CIM"),"");

                $this->ScriptTarol("
function Cimmasol()
{
        document.getElementById('SZALL_NEV').value=document.getElementById('NEV').value;
        document.getElementById('IRSZAM').value=document.getElementById('SZAML_IRSZAM').value;
        document.getElementById('VAROS').value=document.getElementById('SZAML_VAROS').value;
        document.getElementById('CIM').value=document.getElementById('SZAML_CIM').value;
}
                ");

                $Form->FormTagCsakSzoveg(" ","@@@Szállítási cím§§§ <input type=button value='Számlázási cím másolása' onclick=\"Cimmasol();\">");
                $Form->FormTagTextBox("Név:","SZALL_NEV",$this->TablaAdatKi("SZALL_NEV"),"");

                $Form->FormTagTextBox("@@@Irányítószám§§§:","IRSZAM",$this->TablaAdatKi("IRSZAM"),"");

                $Form->FormTagTextBox("@@@Város§§§:","VAROS",$this->TablaAdatKi("VAROS"),"");
                $Form->FormTagTextArea("@@@Cím§§§:","CIM","$CIM","");

                $Form->FormTagTextBox("@@@Telefonszám§§§:","TELSZAM","$TELSZAM","");
                $Form->FormTagTextBox("*@@@E-mail§§§:","EMAIL","$EMAIL","disabled=true");
                $Form->FormTagSubmit(" ","Submit2","@@@Jelszó megváltoztatása§§§","onclick=\"document.Regisztral.Submitg.value='@@@Jelszó megváltoztatása§§§';return true;\"");

//                $Form->FormTagCsakSzoveg("","<p align=center><img src='@@@/images/tarol.gif§§§' style=\"cursor:hand\" onclick=\"if (Ellenor())document.Regisztral.submit();\">&nbsp;<img onclick=\"location.href='$Megsem'\" style=\"cursor:hand\" src='@@@/images/megsem.gif§§§' >",1);
//
                $Form->FormTagCsakSzoveg(" ",Gombcsinal("Tárol","return Ellenor();","submit")." ".Gombcsinal("Mégsem","location.href='$Megsem'"));

                $Tartalom=$Form->OsszeRak("");
                if (CIMKEP)
                {
                        $Cim=$this->CimPublikus();
                }else
                {
                        $Cim="@@@Profil szerkesztése§§§";
                }

                return Munkaterulet($Cim,$Tartalom);
        }


        function Tarol()
        {
                if (!($this->Hozzaferadmin()))return CVaz::AlapFeladat();

              $AKTIV=Postvaltozo("AKTIV");
              $EMAIL=Postvaltozo("EMAIL");
              $JELSZO=Postvaltozo("JELSZO");
              $JELSZO2=Postvaltozo("JELSZO2");
              $NEV=Postvaltozo("NEV");
              $CIM=Postvaltozo("CIM");
              $TELSZAM=Postvaltozo("TELSZAM");

                if (($JELSZO!="")&&($JELSZO2!="")&&($JELSZO==$JELSZO2))
                {
                        $this->TablaAdatBe("JELSZO",md5($JELSZO));
                }

                if($AKTIV=="on")
                {
                        $this->TablaAdatBe("AKTIV",1);
                }
                else
                {
                        $this->TablaAdatBe("AKTIV",0);
                }
                $this->TablaAdatBe("NEV",$NEV);
                $this->TablaAdatBe("CIM",$CIM);
                $this->TablaAdatBe("TELSZAM",$TELSZAM);
                $this->TablaAdatBe("VAROS",Postvaltozo("VAROS"));
                $this->TablaAdatBe("SZALL_NEV",Postvaltozo("SZALL_NEV"));
                
                $this->TablaAdatBe("IRSZAM",Postvaltozo("IRSZAM"));
                $this->TablaAdatBe("SZAML_VAROS",Postvaltozo("SZAML_VAROS"));
                $this->TablaAdatBe("SZAML_IRSZAM",Postvaltozo("SZAML_IRSZAM"));
                $this->TablaAdatBe("SZAML_CIM",Postvaltozo("SZAML_CIM"));

                $Jo=1;

                if (($JELSZO!=$JELSZO2)&&($JELSZO!="")&&($JELSZO2!=""))
                {
                        $this->ScriptUzenetAd("A jelszó mezőknek egyeznie kell.");
                        $Jo=0;
                }
                if ($Jo)
                {
                        $this->TablaSzinkronizal();
                        $this->ScriptUzenetAd("Az adatok sikeresen módosítva.");
                        $Eredmeny=$this->VisszaLep();
                }
                else
                {
                        $this->TablaTarol();
                        $Eredmeny=$this->SzerkesztoUrlapKi();
                }

                return $Eredmeny;
        }


        function ProfilTarol()
        {

                if (($this->AzonAd()!=$_SESSION["AktivFelhasznalo"]->AzonAd()))return CVaz::AlapFeladat();

              $EMAIL=Postvaltozo("EMAIL");
              $JELSZO=Postvaltozo("JELSZO");
              $JELSZO2=Postvaltozo("JELSZO2");
              $NEV=Postvaltozo("NEV");
              $CIM=Postvaltozo("CIM");
              $TELSZAM=Postvaltozo("TELSZAM");
              $Submit=Postvaltozo("Submitg");

                $this->TablaAdatBe("NEV",$NEV);
                $this->TablaAdatBe("CIM",$CIM);
                $this->TablaAdatBe("TELSZAM",$TELSZAM);

                $this->TablaAdatBe("VAROS",Postvaltozo("VAROS"));
                $this->TablaAdatBe("IRSZAM",Postvaltozo("IRSZAM"));
                $this->TablaAdatBe("SZAML_VAROS",Postvaltozo("SZAML_VAROS"));
                $this->TablaAdatBe("SZAML_IRSZAM",Postvaltozo("SZAML_IRSZAM"));
                $this->TablaAdatBe("SZAML_CIM",Postvaltozo("SZAML_CIM"));
                $this->TablaAdatBe("SZALL_NEV",Postvaltozo("SZALL_NEV"));

                $Jo=1;
                $this->TablaTarol();

                $Nyelvobj=$this->ObjektumLetrehoz(NYELVAZON,0);
                $Jelszoir=$Nyelvobj->Cserel("@@@Jelszó megváltoztatása§§§");

                if ($Jo)
                {
                        if (isset($Submit)&&($Submit==$Jelszoir))
                        {
                                $_SESSION["AktivFelhasznalo"]=$this;
                                $Eredmeny=$this->JelszoUrlapKi();
                        }else
                        {
                                $this->ScriptUzenetAd("Adatok tárolva!");
                                $_SESSION["AktivFelhasznalo"]=$this;
                                $Eredmeny=$this->AlapFeladat();
                        }
                }
                else
                {
                        $Eredmeny=$this->ProfilUrlapKi();
                }

                return $Eredmeny;
        }


        

        
        function Adatlist_adm_tag()
        {
        //    $Vissza["URLAP"]=1;    
            $Vissza["URLAP"]=1;    
            $Vissza["TOROL"]=1;    
            return $Vissza;    
        }

        
        
        
        function BelepesTortent($Alert=true)
        {
            parent::BelepesTortent($Alert);

                $NEV=$this->NevAd();
                if ($Alert)$this->ScriptUzenetAd("$NEV @@@üdvözöljük az oldalon!§§§");
                
                
            $this->Futtat(Kosar_azon)->Kosardatafrissul();

            return $this->Futtat(Focsop_azon)->Mutat_pb_fut();
            
            return $this->Futtat(Termek_azon($this->Nyelvadpub()))->Mutat_pb_fut();
                
        }

        function Leiratkoz_pb_fut()
        {
            $EMAILBE=base64_decode($this->Postgetv("HIRLEVEL_EMAIL"));
            $KAPCSOLAT_EMAIL=$this->TablaAdatKi("EMAIL_S");
            if($EMAILBE==$KAPCSOLAT_EMAIL)
            {
                $HIRLEVEL_I=$this->TablaAdatKi("HIRLEVEL_I");
                if ($HIRLEVEL_I)
                {
                    $this->TablaAdatBe("HIRLEVEL_I",0);
                    $this->Szinkronizal();
                    $this->ScriptUzenetAd("Sikeresen leiratkozott!");        
                }else
                {
                    $this->ScriptUzenetAd("Nincs is feliratkozva!");
                }
            }else
            {
                $this->ScriptUzenetAd("Hiba a leiratkozási linkbe!");
            }
            return $this->Futtat(Focsop_azon)->Mutat_pb_fut();

            
        }

        function HirlevEmailKuld($Targy,$Uzenet,$IratkLink="")
        {
                $KAPCSOLAT_EMAIL=$this->TablaAdatKi("EMAIL_S");
                $IratkLink=$this->EsemenyHozzad("Leiratkoz_pb_fut");

//                $EMAIL=$this->NevAd();
/*                $Uzenet=$Uzenet."<br><br><a href='".OLDALCIM."'>".OLDALCIM."</a>";*/
                $Leiratkoz="
                <a href='".OLDALCIM."$IratkLink?HIRLEVEL_EMAIL=".base64_encode($KAPCSOLAT_EMAIL)."' target='_blank' class=link>Leiratkozás a hírlevélről</a>
                ";
                $Uzenet=str_replace("<!--leiratk-->",$Leiratkoz,$Uzenet);

               
                $this->Mailkuld($Targy,$Uzenet,$KAPCSOLAT_EMAIL,$this->TablaAdatKi("NEV_S"));

        }
        
        
        function EmailKuld($Targy,$Uzenet)
        {
                                
                $this->Mailkuld($Targy,$Uzenet,$this->TablaAdatKi("EMAIL_S"));
                
        }


 
        function AktivAd()
        {
                return $this->TablaAdatKi("AKTIV");
        }

        function JelszoAd()
        {
                return $this->TablaAdatKi("JELSZO");
        }

        function JelszoKuld()
        {
                $JELSZO=$this->TablaAdatki("JELSZO");
                $Targy="Elfelejtett jelszó";
                $Uzenet="Az ön jelszava: $FE_JELSZO";
                $FordObj=$this->ObjektumLetrehoz(FORDITAZON,0);
                $Targy=$FordObj->Cserel($Targy);
                $Uzenet=$FordObj->Cserel($Uzenet);

                $this->EmailKuld($Targy,$Uzenet);
                $this->ScriptUzenetAd("A jelszót elküldtük a regisztráció során megadott e-mail címre!");
        }


        function Jogosultsag()
        {
                return 10;
        }

}






?>
