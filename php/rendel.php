<?php

        function Fiz_logba($STATUSZ,$TRANS_ID,$OSSZEG,$LEIRAS,$REND_AZON=0)
        {
                $Vaz=new CVaz_bovit();
                $NEV="nn";
                $AZON="0";
                $Felh=$Vaz->Sessad("Aktfelh");
                if (isset($Felh))
                {
                    if (is_object($Felh))
                    {
                        $NEV=$Felh->NevAd();
                        $AZON=$Felh->AzonAd();
                    }
                }
                $MIKOR=date("Y-m-d H:i:s");
                $IP=Ipcim();
                

                
                $Vaz::$Sql->Modosit("insert into FIZ_LOG (MIKOR,USER_NEV,USER_VZ_AZON,IP,TRANS_ID,OSSZEG,LEIRAS,STATUSZ,REND_VZ_AZON) values ('$MIKOR','$NEV','$AZON','$IP','$TRANS_ID','$OSSZEG','$LEIRAS','$STATUSZ','".$REND_AZON."') ");
        }
        

 
class CRendelCsoport extends CCsoport
{
        
        function Kosarbavan($Termobj)
        {
            $MERET="";
            $_SESS=session_id();
            $Data=$this->Futtatgy("RENDELES",null,null,null," and SESSID_S='$_SESS' and REND_ALLAPOT_I='0' ")->Kosarbavan($Termobj);
            if ($Data["Ossz"]<1)$Vissza=0;
                else $Vissza=$Data["Eredm"][0];
            return $Vissza;                
        }

        function Kosarbatesz($Termobj,$Mennyiseg,$Meret,$Szin)
        {
            $SZIN="";
            $MERET="";
            $_SESS=session_id();
            $Data=$this->Futtatgy("RENDELES",null,null,null," and SESSID_S='$_SESS' and REND_ALLAPOT_I='0' ")->Kosarbatesz($Termobj,$Mennyiseg,$Meret,$Szin);
            
            
            if ($Data["Ossz"]<1)
            {

                $Obj=$this->UjObjektumLetrehoz("CRendeles","RENDELES");
                $Obj->Kosarbatesz($Termobj,$Mennyiseg,$Meret,$Szin);
                $Obj->Szinkronizal();    
            }
        }
        
        function Kosardatafrissul()
        {
            $_SESS=session_id();
            $Data=$this->Futtatgy("RENDELES",null,null,null," and SESSID_S='$_SESS' and REND_ALLAPOT_I='0' ")->Userdatafrissit();
        }

       
        public function Hozzafer($Feladat)
        {
            return true;
        }

        
        function Mutat_pb_fut()
        {
/*                $Vissza["Tartalom"]="@@@A kosár üres!§§§";
                $Vissza["Kosare"]=1;
                return $this->Sablonbe("Oldal",$Vissza);
                */
              
            $_SESS=session_id();
            $Data=$this->Futtatgy("RENDELES",null,null,null," and SESSID_S='$_SESS' and REND_ALLAPOT_I='0' ")->Mutat();
            
            if ($Data["Ossz"]<1)
            {
                $Vissza["Tartalom"]="@@@A kosár üres!§§§";
                $Vissza["Kosare"]=1;
                return $this->Sablonbe("Oldal",$Vissza);
            }else
            {
                return $Data["Eredm"][0];
            }

        }

        function Kosarinfo()
        {
            $Vissza["DB"]=0;
            $Vissza["ERTEK"]=0;
            $Vissza["Tetelek"]=false;
            $_SESS=session_id();
            $Data=$this->Futtatgy("RENDELES",null,null,null," and SESSID_S='$_SESS' and REND_ALLAPOT_I='0' ")->Kosarinfo();
            
            
            if ($Data["Ossz"]>0)
            {
                $Vissza=$Data["Eredm"][0];    
            }
            $Vissza["Link"]=$this->EsemenyHozzad("");

            return $Vissza;
            
        }
        
         
        function Lista_Alap()
        {
                $Tartalom="";
                
                $Honnan=Postgetvaltozo("Honnan");
                if (!isset($Honnan))
                {
                        $Honnan=$this->AllapotKi("Honnan");
                }
                $this->AllapotBe("Honnan",$Honnan);

                $Felt="1=1 ";
                if ($_SESSION["AktivFelhasznalo"]->Jogosultsag()<99)$Felt.=" and  REND_ALLAPOT>0 ";
                $Felt2="";
                $NEV=$this->TablaAdatKi("NEV");
                $Nevebe=$this->AllapotKi("Nevebe");
                $Visszalink="";
                if (is_object($Nevebe))
                {
                        $Felt.=" and FELHASZNALO_VZ_AZON='".$Nevebe->AzonAd()."' ";
                        $NEV.=" ".$Nevebe->NevAd();
                        $Visszalink=$Nevebe->EsemenyHozzad("UrlapKi");
                }
                else
                {
                        if ($_SESSION["AktivFelhasznalo"]->Jogosultsag()<99)$Felt.=" and FELHASZNALO_VZ_AZON='".$_SESSION["AktivFelhasznalo"]->AzonAd()."' ";
                }

                if ($_SESSION["AktivFelhasznalo"]->Jogosultsag()>=99)
                {
                        $KSORSZAM=$this->Valtozosessbe("KSORSZAM");
                        $KCIKKSZAM=$this->Valtozosessbe("KCIKKSZAM");
                        if ($KCIKKSZAM=="0")$KCIKKSZAM="";
                        if ($KSORSZAM=="0")$KSORSZAM="";
                        $Form=new CForm("Szukitform","","");
                        $Form->FormTagHidden("Esemeny_Uj",$this->EsemenyHozzad("Lista"));
                        $Form->FormTagHidden("Honnan",0);
                        $Form->FormTagCsakSzoveg(" ","Keresés");
                        $Form->FormTagTextBox("Rendelés száma:","KSORSZAM",$KSORSZAM,"");
                        $Form->FormTagTextBox("Termék cikkszáma:","KCIKKSZAM",$KCIKKSZAM,"");
                        $Form->FormTagCsakSzoveg(" ","<input type='submit' value='Keresés'>");
                        $Tartalom.=$Form->OsszeRak();

                        if ($KSORSZAM!="")$Felt.=" and SORSZAM like '%".$KSORSZAM."%'";
                        if ($KCIKKSZAM!="")$Felt2.=" and RENDELESTERMEK.CIKKSZAM like '%".$KCIKKSZAM."%'";
                }


                        $Tartalom.="<table width=90%>";
                if ($Felt2!="")
                {
                        if ($Felt!="")$Felt=" and $Felt ";
                        $Gyerekek=false;
                        $Azonok=Vegrehajt("select VZ_AZON from VAZ,RENDELES,RENDELESTERMEK where VZ_TABLA='RENDELES' and VZ_TABLA_AZON=RENDELES.AZON and VZ_SZINKRONIZALT='1' and VZ_SZULO='".$this->AzonAd()."' and RENDELESTERMEK.RENDELES_TB_AZON=RENDELES.AZON  $Felt $Felt2 order by VZ_SORREND desc limit $Honnan,25");
                        $Ossz=Vegrehajt("select count(VZ_AZON) as db from VAZ,RENDELES,RENDELESTERMEK where VZ_TABLA='RENDELES' and VZ_TABLA_AZON=RENDELES.AZON and VZ_SZINKRONIZALT='1' and VZ_SZULO='".$this->AzonAd()."' and RENDELESTERMEK.RENDELES_TB_AZON=RENDELES.AZON  $Felt $Felt2 ");
                        $Ossz=$Ossz[0]["db"];
                        if ($Azonok)
                        {
                                foreach ($Azonok as $egy)
                                {
                                        $Gyerekek[]=$this->ObjektumLetrehoz($egy["VZ_AZON"],0);
                                }
                        }
                }else
                {
                    
                        $Gyerekek=$this->GyerekekVisszaMezoSorrend("RENDELES","VZ_SORREND","Csokkeno",1,$Honnan,25,$Felt);
                        $Ossz=$this->GyerekekSzama("RENDELES",$Felt);
                }
                

                        if($Gyerekek)
                        {
                                $GyerekSzam=count($Gyerekek);
                                for($i=0;$i<$GyerekSzam;$i++)
                                {
                                      $Tartalom.="
                                        <tr>
                                          <td width=97%>".$Gyerekek[$i]->SzerkesztoSor()."</td>
                                        </tr>
                                          ";
                                }
                        }

                        $Tartalom.="</table>";
                        $Tartalom.=$this->Tordeles("Honnan",$Honnan,$Ossz,"",25);


                $Tartalom.=$this->VisszaLinkAd($Visszalink);
                return MunkaTerulet("@@@Orders§§§",$Tartalom);
        }
        
        
       function Lista_fut()
        {
  //          $Honnan=$this->Postgetv_jegyez("Honnan",0);
            
            $K_REND_ALLAPOT=$this->Postgetv_jegyez("K_REND_ALLAPOT",0);

//                $Felt="and  (REND_ALLAPOT_I>0 or FIZ_MOD_I='3' ) ";
                $Felt="and  (REND_ALLAPOT_I>0 ) and SORSZAM_S<>''  ";
                if ($this->Sessad("Aktfelh")->Jogosultsag()==0)$Felt.="  and 1=2 ";
                else if ($this->Sessad("Aktfelh")->Jogosultsag()<99)$Felt.="  and FELHASZNALO_VZ_AZON_I='".$this->Sessad("Aktfelh")->AzonAd()."'";

            if (($K_REND_ALLAPOT!="")&&($K_REND_ALLAPOT!="0"))$Felt.=" and REND_ALLAPOT_I='$K_REND_ALLAPOT' ";

            $Mennyi=30;
            $Data["Lista"]=$this->Futtat($this->Gyerekparam("RENDELES","VZ_SORREND_I desc",1,$Mennyi,$Felt))->Adatlist_adm();
            
            $Form=new CForm2("Szukitform","");
            $Form->Hidden("Esemeny_Uj",$this->EsemenyHozzad("Lista_fut"));
            $Form->Hidden("HonnanLi",0);
            $Form->Kombobox("Állapot:","K_REND_ALLAPOT",$K_REND_ALLAPOT,"",REND_ALLAPOT_K);
            $Form->Szabad2(" ",$Form->Gomb_s("Keres","return true","submit","Submit"));
            
            
            $Vissza["Tartalom"].=$Form->OsszeRak();
            
            $Vissza["Tartalom"].=$this->Sablonbe("Lista",$Data);
            

//            $Vissza["Tartalom"].=$this->Tordeles("Honnan",$Honnan,$Data["Lista"]["Ossz"],10);
                    
            $Vissza["Cim"]=$this->AdminNevAd();
            $Vissza["Vissza"]=$this->VisszaEsemenyAd();
            return $this->Sablonbe("Oldal_admin",$Vissza);
        }
        
        
                
       function Lista_fut_old()
        {
  //          $Honnan=$this->Postgetv_jegyez("Honnan",0);
            


//                $Felt="and  (REND_ALLAPOT_I>0 or FIZ_MOD_I='3' ) ";
                $Felt="and  (REND_ALLAPOT_I>0 ) ";
                if ($this->Sessad("Aktfelh")->Jogosultsag()<99)$Felt.="  and FELHASZNALO_VZ_AZON_I='".$this->Sessad("Aktfelh")->AzonAd()."'";

            $Data["Lista"]=$this->Futtat($this->Gyerekparam("RENDELES","VZ_SORREND_I desc",1,40,$Felt))->Adatlist_adm();
            
            
            $Vissza["Tartalom"]=$this->Sablonbe("Lista",$Data);
            

//            $Vissza["Tartalom"].=$this->Tordeles("Honnan",$Honnan,$Data["Lista"]["Ossz"],10);
                    
            $Vissza["Cim"]=$this->AdminNevAd();
            $Vissza["Vissza"]=$this->VisszaEsemenyAd();
            return $this->Sablonbe("Oldal_admin",$Vissza);
        }
        
         
        

        

}

class CRendeles extends CVaz_bovit
{
    var $Tabla_nev="RENDELES";

       function Jelenhetnavba()
        {
            return false;
        }
        
        
    function Alakit($Vissza)
        {
            $Vissza=str_replace("\n","",$Vissza);
            $Vissza=trim($Vissza);
            return $Vissza;
        }
        
                

                
    public function Tablasql()
    {
        $SQL="
CREATE TABLE IF NOT EXISTS `RENDELES` (
  `AZON_I` int(13) NOT NULL AUTO_INCREMENT,
  `SORSZAM_S` varchar(30) DEFAULT '',
  `SZAML_NEV_S` varchar(200) default '',
  `SZAML_VAROS_S` varchar(200)  default '',
  `SZAML_IRSZAM_S` varchar(10) default '',
  `SZAML_CIM_S` varchar(150)  default '',
  `SZALL_NEV_S` varchar(200) default '',
  `SZALL_VAROS_S` varchar(200)  default '',
  `SZALL_IRSZAM_S` varchar(10) default '',
  `SZALL_CIM_S` varchar(150)  default '',
  `EMAIL_S` varchar(50) default '',
  `TELSZAM_S` varchar(20) default '',
  `MEGJEGYZES_S` text,
  `FELHASZNALO_VZ_AZON_I` int(13) DEFAULT '0',
  `FELHASZNALO_TB_AZON_I` int(13) DEFAULT '0',
  `SESSID_S` varchar(40) DEFAULT '',
  `REND_ALLAPOT_I` tinyint(1) DEFAULT '0',
  `REND_ALLAPOT_MIKOR0_D` datetime default '1900-01-01 00:00',
  `REND_ALLAPOT_MIKOR1_D` datetime default '1900-01-01 00:00',
  `REND_ALLAPOT_MIKOR2_D` datetime default '1900-01-01 00:00',
  `SZALL_MOD_I` tinyint(1) DEFAULT '0',
  `FIZ_MOD_I` tinyint(1) DEFAULT '0',
  PRIMARY KEY  (`AZON_I`)
) ENGINE=".MYSQL_ENGINE."§

create index REND_ALLAPOT_I on RENDELES(REND_ALLAPOT_I)§
create index SORSZAM_S on RENDELES(SORSZAM_S)§
create index FELHASZNALO_TB_AZON_I on RENDELES(FELHASZNALO_TB_AZON_I)§
create index FELHASZNALO_VZ_AZON_I on RENDELES(FELHASZNALO_VZ_AZON_I)§
create index SESSID_S on RENDELES(SESSID_S)§

alter table RENDELES add column AFA_F decimal(10,2) default '0.00';


alter table RENDELES add column REND_ALLAPOT_MIKOR3_D datetime default '1900-01-01 00:00:00';
alter table RENDELES add column REND_ALLAPOT_MIKOR4_D datetime default '1900-01-01 00:00:00';

";
    return $SQL;

}


        public function Hozzafer($Feladat)
        {
            
            if ($this->SessAd("Aktfelh")->Jogosultsag()>=99)return true;
            
            $Vissza=false;
            $ALLAPOT=$this->TablaAdatKi("REND_ALLAPOT_I");
            if ("$ALLAPOT"=="0")
            {
                $SESSID_S=$this->TablaAdatKi("SESSID_S");
                $USERSESS=session_id();
                if (("$SESSID_S"=="$USERSESS")&&($USERSESS!=""))$Vissza=true;   
            }else
            {
                $AZON=$this->SessAd("Aktfelh")->AzonAd();
                $FELHASZNALO_VZ_AZON_I=$this->TablaAdatKi("FELHASZNALO_VZ_AZON_I");

                if (("$FELHASZNALO_VZ_AZON_I"=="$AZON")&&($FELHASZNALO_VZ_AZON_I!="0"))$Vissza=true;   

            }
            if ($Vissza)
            {
                $Kosarvolt=mb_strpos($Feladat,"Kosar");
                if (!($Kosarvolt===false))
                {
                    if ("$ALLAPOT"!="0")$Vissza=false;
                }
            }
            
                   

            return $Vissza;
        }


       function Konstrveg_uj()
       {
            $this->TablaAdatBe("FELHASZNALO_VZ_AZON_I",$this->Sessad("Aktfelh")->AzonAd());
            $this->TablaAdatBe("FELHASZNALO_TB_AZON_I",$this->Sessad("Aktfelh")->TablaAdatKi("AZON_I"));
            $this->TablaAdatBe("SESSID_S",session_id());
            $this->TablaAdatBe("REND_ALLAPOT_I",0);
            $this->TablaAdatBe("REND_ALLAPOT_MIKOR0_D",date("Y-m-d H:i:s"));
            $this->TablaAdatBe("AFA_F",AFA);
            $this->TablaAdatBe("SZALL_MOD_I",1);
                      
            $this->Tablatarol();
            $this->Userdatafrissit();
       }
       
       function Vissza_friss_user()
       {
            $Obj=$this->ObjektumLetrehoz($this->TablaAdatKi("FELHASZNALO_VZ_AZON_I"),0);
            
            if ($Obj->Uresobjektum())
            {
                
            }else
            {
                
                
                if ($Obj->Jogosultsag()=="10")
                {
                    $Obj->Adatfrissit_rendbol($this->OsszesTablaAdatVissza());
                }    
            }
       }

       
       function Userdatafrissit()
       {

            $this->TablaAdatBe("FELHASZNALO_VZ_AZON_I",$this->Sessad("Aktfelh")->AzonAd());
            $this->TablaAdatBe("FELHASZNALO_TB_AZON_I",$this->Sessad("Aktfelh")->TablaAdatKi("AZON_I"));

           $Obj=$this->ObjektumLetrehoz($this->TablaAdatKi("FELHASZNALO_VZ_AZON_I"),0);
           
           $Frissit=false;
            if ($Obj->Uresobjektum())$Frissit=false;
            else
            {
                if ($Obj->Jogosultsag()>0)$Frissit=true; 
            }
        
        
            if ($Frissit)
            {
                $Data=$Obj->OsszesTablaAdatVissza();

                $this->TablaAdatBe("SZAML_NEV_S",$Data["SZAML_NEV_S"]);
                $this->TablaAdatBe("SZAML_IRSZAM_S",$Data["SZAML_IRSZAM_S"]);
                $this->TablaAdatBe("SZAML_VAROS_S",$Data["SZAML_VAROS_S"]);
                $this->TablaAdatBe("SZAML_CIM_S",$Data["SZAML_CIM_S"]);

                $this->TablaAdatBe("SZALL_NEV_S",$Data["SZALL_NEV_S"]);
                $this->TablaAdatBe("SZALL_IRSZAM_S",$Data["SZALL_IRSZAM_S"]);
                $this->TablaAdatBe("SZALL_VAROS_S",$Data["SZALL_VAROS_S"]);
                $this->TablaAdatBe("SZALL_CIM_S",$Data["SZALL_CIM_S"]);
                $this->TablaAdatBe("TELSZAM_S",$Data["TELSZAM_S"]);
                $this->TablaAdatBe("EMAIL_S",$Data["EMAIL_S"]);


                $this->TablaAdatBe("SZAML_KERNEV_S",$Data["SZAML_KERNEV_S"]);
                $this->TablaAdatBe("SZAML_CEGNEV_S 	",$Data["SZAML_CEGNEV_S"]);
                $this->TablaAdatBe("ADOSZAM_S",$Data["ADOSZAM_S"]);
                $this->TablaAdatBe("SZALL_KERNEV_S",$Data["SZALL_KERNEV_S"]);
                $this->TablaAdatBe("SZALL_CEGNEV_S",$Data["SZALL_CEGNEV_S"]);
                $this->Tablatarol();        
                
                //$this->Tablatarol();
            }
       }
       

       
       function Kosarbatesz($Termobj,$Mennyiseg,$Meret,$Szin)
       {
            $this->Termekhozza($Termobj,$Mennyiseg,$Meret,$Szin);
            
       } 
       
       function Kosarmutat1()
       {
            $Vissza=$this->Kosarfejlec("0");
            $Vissza.=$this->Tetelekmutat("0");
            
            
            return $this->Sablonbe("Oldal_uni",array("Tartalom"=>$Vissza,"Kosare"=>1));
        
       }
       
        function Kosarfejlec($F_AKTIV)
        {   
                return "";
              //  $F_AKTIV=$this->Valtozosessbe("F_AKTIV",0);
                
                $MENUK="@@@Kosár áttekintése§§§!@@@Adatok megadása§§§!@@@Véglegesítés§§§";
                $Menuk=explode("!",$MENUK);
                $Vissza="";
                $Db=count($Menuk);
                for ($c=0;$c<$Db;$c++)
                {
                        if($c>0)$Vissza.="<td align='center'>&nbsp;</td>";
                        if ("$F_AKTIV"=="$c")
                        {
                            //<td width='45' height='48'><img src='/images/".($c+1).".png' alt='' border='0' /></td>
                                $Vissza.=" 
                            <td align='center' class='bg_kosar_head_akt'><b><span class='kosar_title_akt'>$Menuk[$c]</span></b></td>
";
                        }else
                        {
                             //<td width='45'><img src='images/".($c+1).".png' alt='' border='0' /></td>
                               $Vissza.="                           
                            <td align='center' class='bg_kosar_head'><span class='kosar_title'>$Menuk[$c]</span></td>
";

                        }
                }
                $Vissza="<table width='100%' class='basketstep' cellspacing='0' cellpadding='0' border='0'>
                      <tbody>
                          <tr>                          
                                $Vissza
                          </tr>
                         </tbody> 
                        </table>";
                return $Vissza;

         
        }   
        
        function Kosarurit_fut()
        {
            $this->Futtatgy("RENDTERMEK","EXTRATETEL_I asc",1)->RekurzivTorol();
            $this->ScriptUzenetAd("@@@Kosár ürítve§§§");
            return $this->Mutat();
        }
        
        function Kosarfrissit_fut()
        {
            
            $Frissit=$this->Postgetv("Frissit");
            if (isset($Frissit)&&($Frissit=="1"))
            {
                $this->Futtatgy("RENDTERMEK","EXTRATETEL_I asc",1)->Kosarfrissit();
                return $this->Mutat();
            }else
            {
                $Szul=$this->SzuloObjektum();
                return $Szul->Mutat_pb_fut();
            }
        }
        
        function Ertesit_termek_fogy()
        {
            $Uzen="";
            $Tetel=$this->Futtatgy("RENDTERMEK","EXTRATETEL_I asc",1)->Elfogyotte($this->TablaAdatKi("AFA_F"));
            if ($Tetel["Eredm"])
            {
                foreach ($Tetel["Eredm"] as $item)
                {
                    $Uzen.=$item;
                }
            }
            if ($Uzen!="")
            {
                $Targy=MAIL_TARGY." elfogyott termék";
            
                $Uzen=$this->Futtat(Nyelv_azon)->Cserel($Uzen);
                $Targy=$this->Futtat(Nyelv_azon)->Cserel($Targy);
                
                $PARAM["Mailbe"]="Tisztelt Adminisztrátor! <br>
                A következő termék készlete 0. <br>".$Uzen;
                $Vissza=$this->Sablonbe("Mailberendeles",$PARAM);           
                
//                $this->Mailkuld($Targy,$Vissza,"h.andras@ysolutions.hu");
                $this->Mailkuld($Targy,$Vissza,OLDALEMAIL);
            }
            
            
            
        }
        
      function Tetelekmutat($Hol)
        {
            
//hol 0- lista,módosithat, 1 - mutat, 2 -mail
                $Data["Hol"]=$Hol;
                $Data["Tetel"]=$this->Futtatgy("RENDTERMEK","EXTRATETEL_I asc",1)->Adatokad($this->TablaAdatKi("AFA_F"));
                
                $Data["Uritlink"]=$this->EsemenyHozzad("Kosarurit");
                $Data["Visszalink"]=$this->Futtat(Kosar_azon)->EsemenyHozzad("");
                if ("$Hol"=="0")
                {
                    $Data["Kosarfrisslink"]=$this->EsemenyHozzad("Kosarfrissit_fut");
                    
                }
                $Data["Rendellink"]=$this->EsemenyHozzad("Kosarurlap1");
                $Data["AFA_F"]=$this->TablaAdatKi("AFA_F");
                $Data["KUPON_S"]=$this->TablaAdatKi("KUPON_S");
                
                return $this->Sablonbe("Tetelekmutat",$Data);

        }
        
        function Sikeres_fizetes($Transid,$BELSO_AZON)
        {
            $Vissza="";
            $FIZETVE_MIKOR_D=$this->TablaAdatKi("FIZETVE_MIKOR_D");
            if ($FIZETVE_MIKOR_D<="2000-01-01 00:00:00")
            {
                $Most=date("Y-m-d H:i:s");

                $this->Rendelcsinal();

                self::$Sql->Modosit("update BANKI_TRANSAZON set FIZETVE_I='1',FIZETVE_MIKOR_D='".$Most."' where PaymentId='".$Transid."'  ");

                $this->TablaAdatBe("FIZETVE_MIKOR_D",$Most);
                $this->TablaTarol();    


            }
            
            $Vissza="<b>Sikeres fizetés. <br>
            Külső tranzakció azonosító: $Transid <br>
            Belső tranzakció azonosító: $BELSO_AZON </b><br>".$this->Adatlapmutat();
            $Vissza.=$this->Tetelekmutat("1");
                        
            $this->ScriptUzenetAd("@@@Rendelés elküldve!§§§");
            return $this->Sablonbe("Oldal",array("Tartalom"=>$Vissza));

                
        }
        
        function Rendelcsinal()
        {
            $REND_ALLAPOT_I=$this->TablaAdatKi("REND_ALLAPOT_I");
            if ("$REND_ALLAPOT_I"=="0")
            {
                $this->Sorszamgeneral();

                $this->TablaAdatBe("REND_ALLAPOT_I",1);
                $this->TablaAdatBe("REND_ALLAPOT_MIKOR1_D",date("Y-m-d H:i:s"));
                $this->TablaTarol();
            
                $Vissza="@@@Tisztelt Vásárlónk!§§§<br>
@@@Rendelését megkaptuk. Köszönjük!§§§ <br><br>
";
                $FIZ_MOD_I=$this->TablaAdatKi("FIZ_MOD_I");
                if ("$FIZ_MOD_I"=="1")
                {
                    $Nyelv=$this->Nyelvadpub();
                    $Vissza.="".$this->Futtat(Utali_azon($Nyelv))->SzovegCserel()."<br><br>";
                }
                $Vissza.=$this->Adatlapmutat(true);
                $Vissza.=$this->Tetelekmutat("2");

            
                $Targy=MAIL_TARGY." @@@rendelés§§§";
            
                $Vissza=$this->Futtat(Nyelv_azon)->Cserel($Vissza);
                $Targy=$this->Futtat(Nyelv_azon)->Cserel($Targy);
                $PARAM["Mailbe"]=$Vissza;
                $Vissza=$this->Sablonbe("Mailberendeles",$PARAM);           
                $this->Mailkuld($Targy,$Vissza,$this->TablaAdatKi("EMAIL_S"));
                $this->Mailkuld($Targy,$Vissza,OLDALEMAIL);
                
                $this->Ertesit_termek_fogy();
            }
        }
        
        function Bar_transid_general()
        {

                $Vissza=self::$Sql->Beszur("BANKI_TRANSAZON");
                self::$Sql->Modosit("update BANKI_TRANSAZON set MIKOR_D='".date("Y-m-d H:i:s")."',REND_VZ_AZON_I='".$this->AzonAd()."' where AZON_I='".$Vissza."'  ");
                return $Vissza;
            
        }
        
        function Bar_kulso_transid_be($Sajatazon,$Barionazon)
        {
                self::$Sql->Modosit("update BANKI_TRANSAZON set PaymentId='".$Barionazon."' where AZON_I='".$Sajatazon."'  ");
                
        }
        

        function Barion_fizet()
        {
            $this->Sorszamgeneral();
            require_once 'barion/library/BarionClient.php';

            $myPosKey = BARION_POSKEY;

            $myEmailAddress = BARION_EMAIL;
            $Data=$this->Kosarinfo();
            

            $OSSZEG=$Data["ERTEK"];
            
            $TRANZ_AZON=$this->Bar_transid_general();
            
            Fiz_logba("Indit",$TRANZ_AZON,$OSSZEG,"Fizetés indítás",$this->AzonAd());


            $BC = new BarionClient($myPosKey, 2, BarionEnvironment::Prod);

            // create the item model
            $item = new ItemModel();
            $item->Name = "Beerside ".$this->SorszamAd(); 
            $item->Description = $this->TablaAdatKi("SZAML_NEV_S")." ".$this->TablaAdatKi("EMAIL_S"); 
            $item->Quantity = 1;
            $item->Unit = "piece"; // no more than 50 characters
            $item->UnitPrice = $OSSZEG;
            $item->ItemTotal = $OSSZEG;
            $item->SKU = ""; // no more than 100 characters

// create the transaction
            
            $trans = new PaymentTransactionModel();
            $trans->POSTransactionId = $TRANZ_AZON; // 	A tranzakció azonosítója a kezdeményező rendszerében. 
            
            $trans->Payee = BARION_EMAIL; // no more than 256 characters
            $trans->Total = $OSSZEG;
            $trans->Comment = "Vásárlás"; // no more than 640 characters
            $trans->AddItem($item); // add the item to the transaction

// create the request model
            $psr = new PreparePaymentRequestModel();

            $psr->GuestCheckout = true; // we allow guest checkout
            $psr->CallbackUrl=BARION_BACK_URL;
            $psr->RedirectUrl=BARION_BACK_URL;


            $psr->PaymentType = PaymentType::Immediate; // we want an immediate payment
            $psr->FundingSources = array(FundingSourceType::All); // both Barion wallet and bank card accepted
            
            $psr->PaymentRequestId = "FIZETÉS-".$this->SorszamAd(); // A bolt rendszerében a fizetés azonosítója. Az exportált számlatörténetben megjelenik, így könnyedén felhasználható a könyvelés megkönnyítéséhez. Opcionális
            
            $psr->PayerHint = ""; // no more than 256 characters
            $psr->Locale = UILocale::HU; // the UI language will be English 
            $psr->Currency = Currency::HUF;
            $psr->OrderNumber = $this->SorszamAd(); // no more than 100 characters
            $psr->ShippingAddress = "";
            $psr->AddTransaction($trans); // add the transaction to the payment

// send the request
            $myPayment = $BC->PreparePayment($psr);
            
            $this->Bar_kulso_transid_be($TRANZ_AZON,$myPayment->PaymentId);

            if ($myPayment->RequestSuccessful === true) 
            {
  // redirect the user to the Barion Smart Gateway
//  header("Location: " . BARION_WEB_URL_TEST . "?id=" . $myPayment->PaymentId);
        
                Fiz_logba("Átirányít",$TRANZ_AZON,$OSSZEG,serialize($myPayment),$this->AzonAd());
                
                header("Location: " . BARION_WEB_URL_PROD . "?id=" . $myPayment->PaymentId);
  
            }else
            {
                Fiz_logba("Inditási hiba",$TRANZ_AZON,$OSSZEG,serialize($myPayment),$this->AzonAd());
                
                
                $Vissza["Tartalom"]="Hiba fizetés indításakor";
                return $this->Sablonbe("Oldal",$Vissza);
            }            
        }
        
        function Kosarellenor()
        {
            $Vissza=true;
            $SZAML_NEV_S=$this->TablaAdatKi("SZAML_NEV_S");
            if ($SZAML_NEV_S=="")$Vissza=false;
            $SZAML_KERNEV_S=$this->TablaAdatKi("SZAML_KERNEV_S");
            if ($SZAML_KERNEV_S=="")$Vissza=false;
            
            $SZAML_IRSZAM_S=$this->TablaAdatKi("SZAML_IRSZAM_S");
            if ($SZAML_IRSZAM_S=="")$Vissza=false;

            $SZAML_VAROS_S=$this->TablaAdatKi("SZAML_VAROS_S");
            if ($SZAML_VAROS_S=="")$Vissza=false;

            $SZAML_CIM_S=$this->TablaAdatKi("SZAML_CIM_S");
            if ($SZAML_CIM_S=="")$Vissza=false;

            $EMAIL_S=$this->TablaAdatKi("EMAIL_S");
            if ($EMAIL_S=="")$Vissza=false;

            $SZALL_MOD_I=$this->TablaAdatKi("SZALL_MOD_I");
            if (("$SZALL_MOD_I"=="")||("$SZALL_MOD_I"=="0"))$Vissza=false;

            $FIZ_MOD_I=$this->TablaAdatKi("FIZ_MOD_I");
            if (("$FIZ_MOD_I"=="")||("$FIZ_MOD_I"=="0"))$Vissza=false;

            
            return $Vissza;
            
            
            
            
        }
        
        function Kosarelkuld_fut()
        {
            if (!($this->Kosarellenor()))return $this->Kosarurlap1_fut();
            
            $FIZ_MOD_I=$this->TablaAdatKi("FIZ_MOD_I");
            if ("$FIZ_MOD_I"=="3")return $this->Barion_fizet();

            $this->Rendelcsinal();

            $Vissza=$this->Adatlapmutat();
            $Vissza.=$this->Tetelekmutat("1");
                        
            $this->ScriptUzenetAd("@@@Rendelés elküldve!§§§");
            return $this->Sablonbe("Oldal",array("Tartalom"=>$Vissza));
        }
        
        function Adatlapelokep()
        {
            $Data=$this->OsszesTablaAdatVissza();
            $Data["Rendkuld"]=$this->EsemenyHozzad("Kosarelkuld");
            
            $Nyelv=$this->Nyelvadpub();    
            
            $Data["Fogadlink"]=$this->Futtat(Rend_feltazon($Nyelv))->EsemenyHozzad("");
            
            return $this->Sablonbe("Adatlapelokep",$Data);
        }
        
        
        function Adatlist_adm()
        {
            $Vissza=$this->OsszesTablaAdatVissza();
            $Vissza["Urlaplink"]=$this->EsemenyHozzad("UrlapKi");
            $Vissza["KESZULT"]=$this->Keszult();
            $Vissza["TETEL"]=$this->Kosarinfo();
            
            return $Vissza;    
        }
                
        function Adatlist_adm_tag()
        {
            $Vissza["URLAP"]=1;    
            return $Vissza;    
        }
                
                
        function Sikertelenre()
        {
            $this->TablaAdatBe("REND_ALLAPOT_I",5);
            $this->TablaAdatBe("REND_ALLAPOT_MIKOR5_D",date("Y-m-d H:i:s"));
            $this->Szinkronizal();
            $this->Futtatgy("RENDTERMEK","EXTRATETEL_I asc",1)->Mennyvisszaad();
            
        }                
                
        function Adminmod_tarol_fut()
        {
            $Submit=$this->Postgetv("Submit");
            
                
                        
                $Dbok=explode("!",REND_ALLAPOT);
                
                $Mailkell=false;
                
                foreach ($Dbok as $egydb)
                {
                        $Reszek=explode("+",$egydb);
                        $Reszek[0]=$this->Futtat(Nyelv_azon)->Cserel($Reszek[0]);
                        
                        if (isset($Submit)&&($Submit==$Reszek[0]))
                        {
                                $this->TablaAdatBe("REND_ALLAPOT_I",$Reszek[1]);
                                $this->TablaAdatBe("REND_ALLAPOT_MIKOR".$Reszek[1]."_D",date("Y-m-d H:i:s"));
                                $this->Szinkronizal();
                                
                                
                                
                        }
                }
                $REND_ALLAPOT_I=$this->TablaAdatKi("REND_ALLAPOT_I");
                if ("$REND_ALLAPOT_I"=="5")
                {
                    $this->Futtatgy("RENDTERMEK","EXTRATETEL_I asc",1)->Mennyvisszaad();
                }
                $Mailkell=true;
                
                if ($Mailkell)
                {
                    $Vissza=$this->Adatlapmutat(true);
                    $Vissza.=$this->Tetelekmutat("2");

            
                    $Targy=MAIL_TARGY." @@@rendelés§§§";
            
                    $Vissza=$this->Futtat(Nyelv_azon)->Cserel($Vissza);
                    $Targy=$this->Futtat(Nyelv_azon)->Cserel($Targy);
                    $PARAM["Mailbe"]=$Vissza;
                    $Vissza=$this->Sablonbe("Mailberendeles",$PARAM);
                    //$Vissza=$this->Sablonbe("Mailberendeles",$PARAM);           
                    $this->Mailkuld($Targy,$Vissza,$this->TablaAdatKi("EMAIL_S"));
                               


                }


                $this->ScriptUzenetAd("Tárolva!");
                return $this->Futtat($this)->Urlapki_fut();
            
        }
                        
        function Adatlapmutat($Mailbe=false,$Adminmod=false)
        {
            $Data=$this->OsszesTablaAdatVissza();
            
            if ($Adminmod)
            {
                $Allhat=1;

                $REND_ALLAPOT_I=$this->TablaAdatKi("REND_ALLAPOT_I");
                if ("$REND_ALLAPOT_I"=="5")$Allhat=0;
    
            
                $Data["Adminallhat"]=$Allhat;
            

                $Data["Adminfunkc"]=1;
                
                $Data["Rendkuld"]=$this->EsemenyHozzad("Adminmod_tarol");
            }else
            {
                $Data["Rendkuld"]=$this->EsemenyHozzad("Kosarelkuld");
            }
            $Data["Mailbe"]=$Mailbe;
            return $this->Sablonbe("Adatlapmutat",$Data);
        }
        
        
        function Kosarelokep()
        {
            if (!($this->Kosarellenor()))return $this->Kosarurlap1_fut();
            
            $Vissza=$this->Kosarfejlec("2");
            $Vissza.=$this->Adatlapelokep();
            $Vissza.=$this->Tetelekmutat("1");       
                 
            return $this->Sablonbe("Oldal",array("Tartalom"=>$Vissza,"Kosare"=>1));
        }
        
        function Kosartarol1_fut()
        {
            $Formbol=$this->Postgetv("Formbol");
            if (isset($Formbol)&&($Formbol=="1"))
            {
                CSimaFelhasznalo::Adatoktarol($this);
            

                $SZALL_MOD_I=$this->Postgetv("SZALL_MOD_I");
                $this->TablaAdatBe("FIZ_MOD_I",$this->Postgetv("FIZ_MOD_I"));
                $this->TablaAdatBe("SZALL_MOD_I",$SZALL_MOD_I);
                $this->TablaAdatBe("MEGJEGYZES_S",$this->Postgetv("MEGJEGYZES_S"));
                $this->Tablatarol();
                if ("$SZALL_MOD_I"=="5")
                {
                    $this->TablaAdatBe("SZALL_NEV_S","Beer to Go");
                    $this->TablaAdatBe("SZALL_IRSZAM_S","1092");
                    $this->TablaAdatBe("SZALL_VAROS_S","Budapest");
                    $this->TablaAdatBe("SZALL_CIM_S","Ráday u 7.");
                    $this->Tablatarol();
                    
                }else
                {
                    $this->Vissza_friss_user();        
                }
                $this->Kupon_frissit();
            
                $this->Szallklt_szamol();
            }
            
            
            return $this->Futtat($this)->Kosarelokep();
            
                    
        }
        
       function Kupon_frissit()
       {
            $Jo=0;
            $KUPON_S=$this->TablaAdatKi("KUPON_S");
            $this->Futtatgy("RENDTERMEK",null,null,null," and EXTRATETEL_I='2'")->RekurzivTorol();
            

            $Tomb=$this->Kosarinfo();
            $ERT=$Tomb["ALAPERTEK"];
//            $SZALL_AR=$this->Futtat(SZALL_KLTS_AZON)->Kedvezmeny($ERT);
            if (Kuponmod())
            {
                if ($KUPON_S==KUPON_SZOV)
                {
                    $ERT=-1*($ERT*(KUPON_SZAZ/100));
                
                echo "Ok";
                    $this->Termekhozza(false,1,"","",2,$ERT,"@@@Kedvezmény§§§");
                    $Jo=1;
                }    
            }
            return $Jo;
            
       }
               
        function Kosarurlap1_fut()
        {
            if (Kuponmod())
            {
                $KUPON_S=$this->Postgetv("KUPON_S");
                if (isset($KUPON_S))
                {
                    $this->TablaAdatBe("KUPON_S",$KUPON_S);
                    $this->TablaTarol();
                }
                $Jo=$this->Kupon_frissit();
                if ($Jo)$this->ScriptUzenetAd(KUPON_SZAZ."% @@@kuponkód elfogadva!§§§");
                    else 
                    {
                        if ($KUPON_S!="")$this->ScriptUzenetAd("@@@Nincs ilyen kupon!§§§");
                    }

            }else
            {
                $Jo=$this->Kupon_frissit();
            }
            
            $Vissza=$this->Kosarfejlec("1");
            
            $Data=$this->OsszesTablaAdatVissza();
            $Data["Rendkuld"]=$this->EsemenyHozzad("Kosartarol1_fut");
            $Data["Atvet_lehet"]=$this->Atvet_lehet();
            $Vissza.=$this->Sablonbe("Kosarurlap1",$Data);
            
            return $this->Sablonbe("Oldal",array("Tartalom"=>$Vissza,"Kosare"=>1));
        }             

       function Mutat()
       {
            $REND_ALLAPOT_I=$this->TablaAdatKi("REND_ALLAPOT_I");
            if ("$REND_ALLAPOT_I"=="0")
            {
                $Vissza=$this->Kosarmutat1();
            }
            return $Vissza;
                        
       }
       
        function Urlapki_fut()
        {
            $Adminfunk=false;
            if ($this->Sessad("Aktfelh")->Jogosultsag()>=99)$Adminfunk=true;
            
             
            $Tart=$this->Adatlapmutat(false,$Adminfunk);
            $Tart.=$this->Tetelekmutat("3");
            
            $Vissza["Tartalom"]=$Tart;
            $Vissza["Vissza"]=$this->VisszaEsemenyAd();
            
            $Sablon=new CSablon();
            $Vissza["Tartalom"].=$Sablon->Gombcsinal("Vissza","location.href='".$this->VisszaEsemenyAd()."'");
            

//            $Vissza["Tartalom"].=$this->Tordeles("Honnan",$Honnan,$Data["Lista"]["Ossz"],10);
                    
            $Vissza["Cim"]=$this->AdminNevAd();
            return $this->Sablonbe("Oldal_admin",$Vissza);

            
        }       

       
       function Kosarinfo()
        {
            $Vissza["DB"]=0;
            $Vissza["ERTEK"]=0;
            $Vissza["ALAPERTEK"]=0;
            
            $Tetelek=array();
            $Data=$this->Futtatgy("RENDTERMEK")->Kosarinfo($this->TablaAdatKi("AFA_F"));

            if ($Data["Ossz"]>0)
            {
                foreach ($Data["Eredm"] as $egyinfo)
                {
                    $Vissza["DB"]=$Vissza["DB"]+$egyinfo["DB"];
                    $Vissza["ERTEK"]=$Vissza["ERTEK"]+$egyinfo["ERTEK"];
                    $Tetelek[]=$egyinfo["Adatok"];
                    if ($egyinfo["Adatok"]["EXTRATETEL_I"]=="0")
                    {
                        $Vissza["ALAPERTEK"]=$Vissza["ALAPERTEK"]+$egyinfo["ERTEK"];
                    }
                }
            }
//            if ($this->TablaAdatKi("EMAIL_S")=="h.andras@ysolutions.hu")$Vissza["ERTEK"]=10;
            
            $Vissza["Tetelek"]=$Tetelek;
            return $Vissza;
            
        }
        

       
      
        function Sorszamgeneral()
        {
                if ($this->TablaAdatKi("SORSZAM_S")!="")return "";
//sorszám növelése - ütközések figyelése - generátor hiányába
                $PARNEV="RENDEL_SZAM";
                $ERT=$this->ParambolAd($PARNEV);
                if ($ERT=="")$ERT=0;
                $Elozosorszam=$ERT;

                $ERT++;

                
                $ind=0;
                $Joe=false;
                do
                {
                    $Felt="";
                    if ($Elozosorszam>0)$Felt=" and ERTEK_S='$Elozosorszam' ";
                    $Joe=$this->Parambatesz($PARNEV,$ERT,$Felt);
                    if (!$Joe)
                    {
                        $ERT=$this->ParambolAd($PARNEV);
                        if ($ERT=="")$ERT=0;
                        $Elozosorszam=$ERT;
                        $ERT++;
                        
                    }
                    $ind++;
                }while ((!($Joe))&&($ind<40));

                if (!($Joe))die("Hiba sorszám generáláskor");
                
                $Vissza=$ERT;
                while (mb_strlen($Vissza)<5)$Vissza="0$Vissza";

                $this->TablaAdatBe("SORSZAM_S",$Vissza);
                $this->TablaTarol();
                return $Vissza;
        }
        
         
        
        function Deviza()
        {
            return "HUF";
        }         

     
   
        
        function Fizetbe_nev()
        {
            $Vissza=$this->TablaAdatKi("SORSZAM");            
            return $Vissza; 


        }
        
        

        function NevAd()
        {
                return $this->TablaAdatKi("SORSZAM_S");
        }

        function SorszamAd()
        {
                return $this->TablaAdatKi("SORSZAM_S");
        }



        function Allapot_modosit()
        {
                $Submit=Postgetvaltozo("Submit");
                $Dbok=explode("!",REND_ALLAPOT);
                $Mailkell=false;
                foreach ($Dbok as $egydb)
                {
                        $Reszek=explode("+",$egydb);
                        if (isset($Submit)&&($Submit==$Reszek[0]))
                        {
                                $this->TablaAdatBe("REND_ALLAPOT",$Reszek[1]);
                                $this->TablaAdatBe("REND_ALLAPOT_MIKOR".$Reszek[1],date("Y-m-d H:i:s"));
                                $this->TablaTarol();
                        }
                }


                $this->ScriptUzenetAd("Állapotváltozás tárolva!");
                return $this->UrlapKi();

        }
        

       
       function Szallklt_szamol()
       {
            $SZALL_MOD_I=$this->TablaAdatKi("SZALL_MOD_I");
            $this->Futtatgy("RENDTERMEK",null,null,null," and EXTRATETEL_I='1'")->RekurzivTorol();
            
            if ("$SZALL_MOD_I"=="5")return false;

            $Tomb=$this->Kosarinfo();
            $ERT=$Tomb["ERTEK"];
//            $SZALL_AR=$this->Futtat(SZALL_KLTS_AZON)->Kedvezmeny($ERT);
            if ($ERT<SZALL_INGYEN_TOL)
            {
                $this->Termekhozza(false,1,"","",1,SZALL_KLTS,"@@@Szállítási költség§§§");    
            }
            
       }


       function Kosarbavan($Termobj)
       {
            $Vissza=0;
            $Ered=$this->Futtatgy("RENDTERMEK",null,null,null," and TERMEK_VZ_AZON_I='".$Termobj->AzonAd()."'")->AzonAd();
            if ($Ered["Ossz"]>0)$Vissza=1;
            return $Vissza;
       }
            

        function Termekhozza($Termekobj,$Mennyiseg,$MERET,$SZIN,$EXTRATETEL=0,$AR="",$NEV="")
        {
                $Obj=$this->UjObjektumLetrehoz("CRendelestermek","RENDTERMEK");

                $Obj->Alapbe($Termekobj,$this,$Mennyiseg,$MERET,$SZIN,$EXTRATETEL,$AR,$NEV);

        }
        
        
        function Atvet_lehet()
        {
            //csak gls
            $Vissza["7"]="7";
            return $Vissza;
/*
ha benne van a termék: wesselényi vagy rádai

ha nincs benne: postai és rádai
*/            
            $Ered=$this->Futtatgy("RENDTERMEK",null,null,null," and TERMEK_VZ_AZON_I='".Termek_szem_atvet."'")->AzonAd();
            if ($Ered["Ossz"]>0)
            {
                $Vissza["5"]="5";
                $Vissza["6"]="6";
            }else
            {
                $Vissza["1"]="1";
                $Vissza["2"]="2";
                $Vissza["3"]="3";
                $Vissza["4"]="4";
                $Vissza["5"]="5";

                $Vissza["7"]="7";
                $Vissza["8"]="8";

            }
            return $Vissza;
            
            
        }
                
}

class CRendelestermek extends CVaz_bovit
{
    var $Tabla_nev="RENDELESTERMEK";
    
        public function Hozzafer($Feladat)
        {
            $Szul=$this->SzuloObjektum();
            return $Szul->Hozzafer($Feladat);
        }
    
    
       function Jelenhetnavba()
        {
            return false;
        }

    
        function Kosarinfo($AFA)
        {
            $Vissza["DB"]=$this->Darabad();
           // $Vissza["ERTEK"]=$this->ArAd();
            
          /*  $Adatok=$this->OsszesTablaAdatVissza();
            $Obj=$this->ObjektumLetrehoz($this->TablaAdatKi("TERMEK_VZ_AZON_I"),0);
            if ($Obj->Uresobjektum())$Kep="";
                                else $Kep=$Obj->Listakepad();
            
            $Adatok["Listakep"]=$Kep;
            */
            
            $Vissza["Adatok"]=$this->Adatokad($AFA);
            
            $Vissza["ERTEK"]=($Vissza["Adatok"]["BRUTTO"]*$this->TablaAdatKi("DB_I"));
            
            return $Vissza;
        }
    
        function Darabad()
        {
            return $this->TablaAdatKi("DB_I");
        }
    
        function ArAd()
        {
            $DB=$this->TablaAdatKi("DB_I");
            $AR_F=$this->TablaAdatKi("AR_F");
            return ($DB*$AR_F);
        }
    
   public function Tablasql()
    {
        $SQL="
CREATE TABLE IF NOT EXISTS `RENDELESTERMEK` (
  `AZON_I` int(13) NOT NULL AUTO_INCREMENT,
  `CIKKSZAM_S` varchar(40) DEFAULT '',
  `EXTRATETEL_I` tinyint(1) DEFAULT '0',
  `NEV_S` varchar(200) DEFAULT '',
  `SZIN_S` varchar(50) DEFAULT '',
  `MERET_S` varchar(50) DEFAULT '',
  `AR_F` decimal(10,2) DEFAULT '0.00',
  `DB_I` int(11) DEFAULT '0',
  `TERMEK_VZ_AZON_I` int(13) DEFAULT '0',
  `TERMEK_TB_AZON_I` int(13) DEFAULT '0',
  `RENDELES_TB_AZON_I` int(13) DEFAULT '0',
  PRIMARY KEY  (`AZON_I`)
) ENGINE=".MYSQL_ENGINE."§

create index TERMEK_VZ_AZON_I on RENDELESTERMEK(TERMEK_VZ_AZON_I)§
create index TERMEK_TB_AZON_I on RENDELESTERMEK(TERMEK_TB_AZON_I)§
create index RENDELES_TB_AZON_I on RENDELESTERMEK(RENDELES_TB_AZON_I)§
create index EXTRATETEL_I on RENDELESTERMEK(EXTRATETEL_I)
alter table RENDELESTERMEK add column $MENNY_VISSZAMENT_I tinyint default '0'

";
    return $SQL;

}        
  
        function Elfogyotte($AFA)
        {
            $Vissza="";
            $Obj=$this->ObjektumLetrehoz($this->TablaAdatKi("TERMEK_VZ_AZON_I"),0);
            if ($Obj->Uresobjektum())
            {
                
            }else
            {
                $Keszleten=$Obj->Maxkeszlet();
                if ($Keszleten<=0)
                {
                    $TERMLINK=$Obj->EsemenyHozzad("Urlapki_fut");
                    
                    $Vissza="".$this->TablaAdatKi("NEV_S")." <a href='".OLDALCIM.$TERMLINK."' target='_blank'>Termék megnyitása</a><br>
                    ";    
                }
            }
            return $Vissza;
        }
        
        function Adatokad($AFA)
        {
            //$this->LetrehozoBe($Letre);
            $Vissza=$this->OsszesTablaAdatVissza();
            $Nyelv=$this->Nyelvadpub();
            $Vissza["Azon"]=$this->AzonAd();
            $Vissza["NEV"]=$this->TablaAdatKi("NEV_S");
            $Vissza["Torollink"]=$this->EsemenyHozzad("Torol");
            $Vissza["Modositlink"]=$this->EsemenyHozzad("Frissit");
              
            $Obj=$this->ObjektumLetrehoz($this->TablaAdatKi("TERMEK_VZ_AZON_I"),0);
            if ($Obj->Uresobjektum())
            {
                $Kep="";
                $TERMLINK="";
            }
            else 
            {
                $Kep=$Obj->Listakepad();
                $TERMLINK=$Obj->EsemenyHozzad("");
            } 
            
            $Vissza["Termlink"]=$TERMLINK;

            $Vissza["Listakep"]=$Kep;
            $BRUTTO=$this->TablaAdatKi("AR_F");
               
            $NETTO=round($BRUTTO/(1+($AFA/100)));
              
            $Vissza["NETTO"]=$NETTO;
            $Vissza["BRUTTO"]=$BRUTTO;
            
            return $Vissza;
        }  
  
        function Kosarfrissit()
        {
            
            $DB=$this->Postgetv("DB".$this->AzonAd());
            if ("$DB"=="0")
            {
                $this->RekurzivTorol();
            }else
            {
                $MENNYISEG=$DB;
                
                $MENNY_BENT=$this->MennyisegAd();
                if ($MENNYISEG>$MENNY_BENT)
                {
                    $BEVISZ=$MENNYISEG-$MENNY_BENT;
                
                    $Termekobj=$this->Termekobj();
                    if (is_object($Termekobj))
                    {
                        $TERM_MENNYISEG=$Termekobj->Mennyisegad();
                        if ($TERM_MENNYISEG>=$BEVISZ)
                        {
//                            $MENNY_ADAT=$this->TablaAdatKi("DB_I");
                            $this->TablaAdatBe("DB_I",$DB);
                            $this->TablaTarol();
                            
                            $this->_Mennyhozzaad($Termekobj,$BEVISZ*-1);   
                        }else
                        {
                            $this->ScriptUzenetAd("@@@Nincs ennyi raktáron!§§§ @@@Maximum rendelhető:§§§ ".($TERM_MENNYISEG+$MENNY_BENT)."");
                        }
                    }
                }else
                {
                            $this->TablaAdatBe("DB_I",$DB);
                            $this->TablaTarol();

                        $Termekobj=$this->Termekobj();
                        $BEVISZ=$MENNY_BENT-$MENNYISEG;
                        
                        $this->_Mennyhozzaad($Termekobj,$BEVISZ);
                           
                }


//                $this->TablaAdatBe("DB_I",$DB);
  //              $this->TablaTarol();
            }    
        }
        
        function Termekobj()
        {
            $Obj=false;
            $TERMEK_VZ_AZON_I=$this->TablaAdatKi("TERMEK_VZ_AZON_I");
            if ($TERMEK_VZ_AZON_I!="0")
            {
                $Obj=$this->ObjektumLetrehoz($TERMEK_VZ_AZON_I,0);
                if ($Obj->Uresobjektum())$Obj=false;                
                
            }
            return $Obj;
            
        }

        function _Mennyhozzaad($Termek,$Mennyiseg)
        {
            
            if (is_object($Termek))
            {
                $Termek->Mennyiseghozza($Mennyiseg);
            }
            
           
        }



        function RekurzivTorol()
        {
            $this->Mennyvisszaad();
            CVaz_bovit::RekurzivTorol();
        }

        function Mennyvisszaad() 
        {
            $MENNY_VISSZAMENT_I=$this->TablaAdatKi("MENNY_VISSZAMENT_I");
            if ($MENNY_VISSZAMENT_I=="1")return false;
            
            $DB_I=$this->TablaAdatKi("DB_I");
            $TERMEK_VZ_AZON_I=$this->TablaAdatKi("TERMEK_VZ_AZON_I");
            if ($TERMEK_VZ_AZON_I!="0")
            {
                $Obj=$this->ObjektumLetrehoz($TERMEK_VZ_AZON_I,0);
                if ($Obj->Uresobjektum())
                {
                    
                }else
                {
                    
                    $Obj->Mennyiseghozza($DB_I);
                    $this->TablaAdatBe("MENNY_VISSZAMENT_I",1);
                    $this->Szinkronizal();
                    
                }
                
            }
                 
        }

        
        function Alapbe($Termekobj,$Rendobj,$Mennyiseg,$MERET,$SZIN,$EXTRATETEL=0,$AR="",$NEV="")
        {

                if (is_object($Termekobj))
                {
                        $this->TablaAdatBe("TERMEK_VZ_AZON_I",$Termekobj->_EredetiAzon());
                        $this->TablaAdatBe("TERMEK_TB_AZON_I",$Termekobj->TablaAdatKi("AZON_I"));
                        $this->TablaAdatBe("CIKKSZAM_S",$Termekobj->CikkszamAd());
                        $this->TablaAdatBe("NEV_S",$Termekobj->Kosarnevad());
                        
                        
        
                        $this->TablaAdatBe("AR_F",$Termekobj->ArAd());
                        
                        $Termekobj->Mennyiseghozza($Mennyiseg*-1);

//                        $this->TablaAdatBe("GYARTO",$Termekobj->TablaAdatKi("GYARTO"));
  //                      $this->TablaAdatBe("SULY_BRUTT",$Termekobj->SulyAd());
                        

                }else
                {
                        $this->TablaAdatBe("NEV_S",$NEV);
                        $this->TablaAdatBe("AR_F",$AR);
                }
                $this->TablaAdatBe("MENNY_VISSZAMENT_I",0);
//                $this->TablaAdatBe("SZIN_S",$SZIN);
  //              $this->TablaAdatBe("MERET_S",$MERET);

                $this->TablaAdatBe("EXTRATETEL_I",$EXTRATETEL);

                $this->TablaAdatBe("RENDELES_TB_AZON_I",$Rendobj->TablaAdatKi("AZON_I"));

                $this->TablaAdatBe("DB_I",$Mennyiseg);

                $this->Szinkronizal();
        }

        function Frissit_fut()
        {
            $DB=$this->Postgetv("DB");
            if ("$DB"=="0")
            {
                return $this->Torol_fut();
            }else
            {
                $this->TablaAdatBe("DB_I",$DB);
                $this->TablaTarol();
                return $this->VisszaLep();                
            }    
        }
        


        function MennyisegAd()
        {
                return $this->TablaAdatKi("DB_I");
        }

        function MutatSorEmailbe(&$AROSSZES)
        {
                $Egysegar=(int)$this->TablaAdatKi("AR");
                $Ar=$this->ArAd();
                $AROSSZES=$AROSSZES+$Ar;

                if ($this->TablaAdatKi("EXTRATETEL"))
                {
                        $NevIr=$this->TablaAdatKi("NEV_S");
                }else
                {
                        $MERET=$this->TablaAdatKi("MERET");
                        if ($MERET!="")$MERET="(".$MERET.")";

                        $NevIr=$this->NevAd().$MERET;
                }  
                $NevIr.=" ".$this->TablaAdatKi("CIKKSZAM");
                $Mennyiseg=$this->MennyisegAd();
                $Egysegar=$this->ArFormaz($Egysegar);
                $Ar=$this->ArFormaz($Ar);
                $Tartalom="            <tr>
              <td class=\"text\" width=\"35%\">$NevIr </td>
              <td class=\"text\">$Egysegar </td>
              <td class=\"text\" align=\"center\">$Mennyiseg db</td>
              <td class=\"text\" align=\"left\">$Ar </td>
            </tr>
                        ";

                return $Tartalom;
        }



        function NevAd()
        {
            return $this->TablaAdatKi("NEV_S");
        }



        function UrlapKi(&$AROSSZES)
        {
                $Egysegar=(int)$this->TablaAdatKi("AR");
                $Ar=$this->ArAd();
                $AROSSZES=$AROSSZES+$Ar;

                if ($this->TablaAdatKi("EXTRATETEL"))
                {
                        $NevIr=$this->TablaAdatKi("NEV_S");
                }else
                {
                        $MERET=$this->TablaAdatKi("MERET");
                        if ($MERET!="")$MERET="( méret: ".$MERET.")";
                        $NevIr=$this->NevAd()."".$MERET."";
                }
                $Nyelvhez="";


                $Ar=$this->ArFormaz($Ar);
                $Egysegar=$this->ArFormaz($Egysegar);
                $NevIr.=" ".$this->TablaAdatKi("CIKKSZAM");
                $Mennyiseg=$this->TablaAdatKi("DB");
                $Tartalom="
                        <tr>
                           <td width=35% class=text>$NevIr</td>
                           <td class=text>$Egysegar /db</td>
                           <td class=text width=20% align='center'>$Mennyiseg db</td>
                           <td class=text width=20% align='left'>$Ar </td>
                        </tr>
                        ";

                return $Tartalom;

        }


}



function Ipcim()
{
//GLOBALS OFF WORK ROUND
        if (!ini_get('register_globals'))
        {
        $reg_globals = array($_POST, $_GET, $_FILES, $_ENV, $_SERVER, $_COOKIE);
        if (isset($_SESSION)) {
        array_unshift($reg_globals, $_SESSION);
        }
        foreach ($reg_globals as $reg_global) {
        extract($reg_global, EXTR_SKIP);
        }
        }

//FIND THE VISITORS IP
     if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
     {
        $rip = getenv("HTTP_CLIENT_IP");
     }
     else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
     {
        $rip = getenv("HTTP_X_FORWARDED_FOR");
     }
     else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
     {
        $rip = getenv("REMOTE_ADDR");
     }
     else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
     {
        $rip = $_SERVER['REMOTE_ADDR'];
     }
     else
     {
        $rip = "unknown";
     }

//DISPLAY THE VISITORS IP
        return $rip;

}

?>