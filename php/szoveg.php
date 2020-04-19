<?php


class CSzoveg extends CKapcsform
{
        var $Tabla_nev="SZOVEG";



        function Tobbnyelvu()
        {
            return false;
        }

        function Fotermcsop()
        {
            return false;
        }
    
        function NevBe($NEV)
        {
            $this->TablaAdatBe("NEV_S",$NEV);
            $this->Szinkronizal();
        }    
    
        function Csoportcsinal(&$Hibauzen,$hanyas,$CSOPNEVEK,$Ujatis=false)
        {
                $CSOPNEVEK[$hanyas]=stripslashes($CSOPNEVEK[$hanyas]);
                $CSOPNEVEK[$hanyas]=addslashes($CSOPNEVEK[$hanyas]);
                if ($CSOPNEVEK[$hanyas]=="")
                {
                    $Tips=strtolower(get_class($this));
                    if (($this->Fotermcsop()))return false;
                                                    return $this;
                    
                }
                $Csop=$this->Futtatgy("CSOPORT",null,null,null," and NEV_S='$CSOPNEVEK[$hanyas]' ")->Objad();
               
                if ($Csop["Ossz"]>0)$CSOP=$Csop["Eredm"][0];
                else
                {   
                        if ($this->AzonAd()==Felso_azon)$Ujatis=false;
                        if ($Ujatis)
                        {
                        
                                $CSOP=$this->UjObjektumLetrehoz("CAlWebAruhazCsoport","CSOPORT");
                                $CSOP->TablaAdatBe("NEV_S",$CSOPNEVEK[$hanyas]);
                                $CSOP->Szinkronizal();

                                $CSOP->Defkulsolink();

                        }else
                        {
                                $Hibauzen="ujnem".$CSOPNEVEK[$hanyas];
                                return false;
                        }


                }
                $hanyas++;
                if (isset($CSOPNEVEK[$hanyas]))$CSOPNEVEK[$hanyas]=trim($CSOPNEVEK[$hanyas]);
                
                if (isset($CSOPNEVEK[$hanyas])&&($CSOPNEVEK[$hanyas]!=""))$Vissza=$CSOP->Csoportcsinal($Hibauzen,$hanyas,$CSOPNEVEK,$Ujatis);
                        else $Vissza=$CSOP;
                return $Vissza;


        }  
        
        function AlTerkepgeneral($Szint)
        {
                if ($Szint>0)
                {
               $Elo="";
               $Uto="";
               for ($c=0;$c<$Szint;$c++)
               {
                $Elo.="<BLOCKQUOTE dir=ltr style='MARGIN-RIGHT: 0px'>
                ";
                $Uto.="</BLOCKQUOTE>
                ";
               }
               $Link=$this->EsemenyHozzad("Mutat_pb");
               $NEV=$this->NevAd();
               $Tartalom="
               $Elo<a href='$Link'>$NEV</a>$Uto
               ";
               }else $Tartalom="";

              $Ered=$this->Futtat($this->Gyerekparam("CSOPORT",$this->Rendezsql(),1,null," and AKTIV_I='1' "))->Objad($Szint+1);
              $Csoportok=$Ered["Eredm"];
               if ($Csoportok)
               {
                    foreach ($Csoportok as $Egy)
                    {
                        $Tartalom.=$Egy->AlTerkepgeneral($Szint+1);
                    }
               }

               return $Tartalom;
        }
          
          
        function NevAd()
        {
            
            
            if ($this->Tobbnyelvu())
            {
                $Vissza=$this->TablaAdatKi("NEV_".$this->Nyelvadpub()."_S");
            }else
            {
                $Vissza=$this->TablaAdatKi("NEV_S");
            }
            
            return $Vissza;
        }

        
        function Szerksor_beallit()
        {
                $this->Szerkeszto["LISTA"]=1;
        }
             
       function SzovegElkap($SZOVEG)
        {

                $BEILLESZTES=$this->TablaAdatKi("BEILLESZTES_S");

                if ($BEILLESZTES!="")
                {
                        $BeillPontok=explode(BEILLESZTKULSO,$BEILLESZTES);
                        $PontDb=count($BeillPontok);
                        for ($c=0;$c<$PontDb;$c++)
                        {
                                $EgyBeillPont=explode(BEILLESZTBELSO,$BeillPontok[$c]);
                                $BeObj=$this->ObjektumLetrehoz($EgyBeillPont[0],0);

/*                                $VoltItt=$this->AllapotKi($EgyBeillPont[1]."tart");

                                if (isset($VoltItt)&&$VoltItt)
                                {
                                        $BeSzoveg=$VoltItt;
                                }else
                                {*/
                                        $BeSzoveg=call_user_func(array(&$BeObj,$EgyBeillPont[1].""),$this);
                                
                              /*  $EsKezd=strpos($BeSzoveg,"<input type='hidden' name='Esemeny_Uj' id='Esemeny_Uj'");
                                $Esemeny=substr($BeSzoveg,$EsKezd);

                                $EsVeg=strpos($Esemeny,">");
                                if (!($EsKezd===false)&&!($EsVeg===false))
                                {
                                        $EsemenyCsere=substr($Esemeny,0,$EsVeg+1);
                                        $Esemeny=substr($Esemeny,62,$EsVeg-65);
                                        $this->AllapotBe($EgyBeillPont[1],$Esemeny);

                                         $IllesztUtan=$this->EsemenyHozzad("IllesztUtan_pb_fut");
                                         $PluszTag="
                                <input type=hidden name='Esemeny_Uj' value='$IllesztUtan'>
                                  <input type=hidden name='FormbolJott' value='$EgyBeillPont[1]'>";
                                  
                                         $BeSzoveg=str_replace($EsemenyCsere,$PluszTag,$BeSzoveg);
                                }
                                */
                                $SZOVEG=str_replace("&asymp;",$BeSzoveg,$SZOVEG);
                                
                        }
                }
                return $SZOVEG;
        }
                     
        function Oldalbetolt()
        {
            return "Oldal";
        }
 


        function Mutat_ures_pb_fut()
        {
                $NEV=$this->NevAd();
                $SZOVEG=$this->SzovegCserel();
                return $this->Sablonbe("Oldal_ures",$SZOVEG);
        }         
                       
        function Mutatvisszalink()
        {
            return $this->VisszaEsemenyAd();
        }          
        
        function Rendezsql()
        {
            return "VZ_SORREND_I";
        }                
                  
                  
                  
        function Felsokepad()
        {
            $Vissza=$this->Eleres_allom("FELSOKEP");

            if ($Vissza=="")
            {
                $Szulo=$this->SzuloObjektum();
                $Vissza=$Szulo->Felsokepad();
            }
            return $Vissza;            
        }

        function Cimelott()
        {
            $Vissza=$this->Eleres_allom("CIMELOTT");
            if ($Vissza!="")
            {
                $Vissza="<img src='$Vissza' style='width: 100%' >";
            }else
            {
                $Vissza=$this->TablaAdatKi("VIDEO_S");
            }
            if ($Vissza=="")
            {
                $Szulo=$this->SzuloObjektum();
                $Vissza=$Szulo->Cimelott();
            }
            return $Vissza;
            
        }

        function Listazhat()
        {
            $Vissza=true;
            
            
            return $Vissza;
        }
                  
        function Nyitomutat()
        {
                $Ered=$this->Futtat($this->Gyerekparam("CSOPORT",$this->Rendezsql(),1,null," and AKTIV_I='1' ".$this->Hozzafersql()))->Adatlistkozep_publ(false);
                $AZON=$this->AzonAd();
                $Vlink=$this->Mutatvisszalink();
                if ($Vlink!="")$Vissza["Vissza"]=$Vlink;
                
                $Param["Cim"]=$this->NevAd();
                $Param["Menuk"]=$Ered["Eredm"];
                $Param["Data"]=$this->Adatlistkozep_publ(false);
                return $this->Sablonbe("Menumunkateruletre2",$Param);
            
        }                    

        function Almenulistaz($Nyitolap=0)
        {
                $Ered=$this->Futtat($this->Gyerekparam("CSOPORT",$this->Rendezsql(),1,null," and AKTIV_I='1' ".$this->Hozzafersql()))->Adatlistkozep_publ(false);
                $AZON=$this->AzonAd();
                
                $Param["Menuk"]=$Ered["Eredm"];
                if ($Nyitolap)$Param["Nyitolap"]=1;
                $Param["Data"]=$this->Adatlistkozep_publ(false);
                return $this->Sablonbe("Menumunkateruletre",$Param);
        }
                  
        function Kozepennev()
        {
                $NEV=$this->NevAd();
                return $NEV;
        }                  
                  
        function Konyjelzo()
        {
            return "";
        }
                  
        function Mutat_pb_fut()
        {
                $this->Mutatloggole();    
            
                $NEV=$this->Kozepennev();
                $SZOVEG=$this->SzovegCserel();
                
                $Ered=$this->Futtat($this->Gyerekparam("CSOPORT",$this->Rendezsql(),1,null," and AKTIV_I='1' ".$this->Hozzafersql()))->Adatlistkozep_publ(false);
                $AZON=$this->AzonAd();
                $Vlink=$this->Mutatvisszalink();
                if ($Vlink!="")$Vissza["Vissza"]=$Vlink;
                
                $Param["Menuk"]=$Ered["Eredm"];
                $Param["Data"]=$this->Adatlistkozep_publ(false);
                if ($this->Listazhat())$Vissza["Almenuk"]=$this->Sablonbe("Menumunkateruletre",$Param);



                $Vissza["Tartalom"]=$SZOVEG;
                //$Vissza["Cimelott"]=$this->Cimelott();

                
                $Galdat=$this->Futtatgy("GALDOK","VZ_SORREND_I",null,null,null)->Adatlistkozep_publ();
                $Vissza["Tartalom"].=$this->Sablonbe("Galeriadok",$Galdat["Eredm"]);
        

                $Vissza["Cim"]=$NEV;
                $Vissza["Konyjelzo"]=$this->Konyjelzo();;
                
                $Navig=$this->Navigsorad();
                $Vissza["Navigalo"]=$Navig;
                

                $BEILLESZTES=$this->TablaAdatKi("BEILLESZTES_S");
                if ($BEILLESZTES!="")$Vissza["Sima"]=1;                
                
                //$Vissza["Vissza"]=$this->VisszaEsemenyAd();
                

                return $this->Sablonbe("Oldal",$Vissza);

        }

        
      function Hozzafersql()
        {
            return "";
            
        }
        
        function Almenulistapubl()
        {
            return array();
        }
       

        function Adatlist0()
        {
            $Vissza["Link"]=$this->EsemenyHozzad("");
            $Vissza["Nev"]=$this->NevAd();
              
            return $Vissza;
        }
        

/*        function Adatlistkozep_publ2($Almenukell=false)
        {
            return $this->Adatlistkozep_publ2($Almenukell);
        }*/
        
        function Linkgeneral()
        {

            $LINK_S=$this->TablaAdatKi("LINK_S");
            if ($LINK_S!="")
            {
                $Vissza["Link"]=$LINK_S;
                $Vissza["Ujablak"]=true;
            }else
            {
                $Vissza["Link"]=$this->EsemenyHozzad("");
                $Vissza["Ujablak"]=false;
            }
            return $Vissza;
        }
               
        function Adatlistkozep_publ($Almenukell=false)
        {

            $Vissza=$this->Linkgeneral();
            
//            $Vissza["Link"]=$this->EsemenyHozzad("");
            $Vissza["Azon"]=$this->AzonAd();
            
            if ($this->Tobbnyelvu())
            {
                
                $Vissza["Bevezeto"]="";
                $Vissza["Szoveg"]=$this->TablaAdatKi("SZOVEG_".$this->Nyelvadpub()."_S");
            }
            else 
            {
                $Vissza["Bevezeto"]=$this->TablaAdatKi("BEVEZETO_S");      
                $Vissza["Szoveg"]=$this->TablaAdatKi("SZOVEG_S");
            }
            
            
            $Vissza["Nev"]=$this->NevAd();
            $Vissza["Azon"]=$this->AzonAd();
            //$Vissza["Datum"]=$this->TablaAdatKi("MEGJELEN_DAT_S");
            
/*            if ($Eredkep)
            {
                $ERKEP="";
                $Tomb=$this->Futtat($this->Gyerekparam("LISTAKEP"))->EredetiEleres();
                if ($Tomb["Ossz"]>0)$ERKEP=$Tomb["Eredm"][0];
                $Vissza["Kep"]=$ERKEP;
            }
            else*/ 
            $Vissza["Kep"]=$this->Eleres_allom("LISTAKEP");
            
            if ($Almenukell)
            {
                $Ered=$this->Futtatgy("CSOPORT!CSOPORT_AR",null,null,null," and AKTIV_I='1' ".$this->Almenufelt())->Adatlistkozep_publ(0);
                
                $Vissza["Almenuk"]=$Ered["Eredm"];
            }
              
            return $Vissza;
        }
        
        function Almenufelt()
        {
            return "";
        }
        
        function Adatreszlad()
        {
            $Vissza["Link"]=$this->EsemenyHozzad("");
            $Vissza["Szoveg"]=$this->TablaAdatKi("SZOVEG_".$this->Nyelvadpub()."_S");
            $Vissza["Nev"]=$this->NevAd();
            
            $Nagykep="";
            $Eredm=$this->Futtat($this->Gyerekparam("LISTAKEP"))->EredetiEleres();
            if ($Eredm["Ossz"]>0)$Nagykep=$Eredm["Eredm"][0];
            
            $Vissza["Kep"]=$Nagykep;
              
            return $Vissza;
        }        

        
        function AktivAd()
        {
                return $this->TablaAdatKi("AKTIV_I");
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

                return $this->SzovegElkap($Szoveg);

                return $Szoveg;
        }

   


        function Urlapplusz(&$Form)
        {
            
        }

        function Urlapplusz_tarol()
        {
            
        }

        function Lehetinaktiv()
        {
            $Tagok=$this->Adatlist_adm_tag();
                if (isset($Tagok["TOROL"])&&($Tagok["TOROL"]=="1"))$Vissza=true;
                        else $Vissza=false;
                return $Vissza;
        }

        function Hozzaferhetkod()
        {
            return $this->TablaAdatKi("JOG_S");
        }


        function Kellkulsolink()
        {
            $Vissza=false;
            $Szul=$this->SzuloObjektum();
            if (($Szul->AzonAd()=="24")||($Szul->AzonAd()=="13")||($Szul->AzonAd()=="35"))$Vissza=true;
            return $Vissza;
        }

        function Urlapki_fut()
        {

                $Tarol=$this->EsemenyHozzad("Tarol");

                $Form=new CForm2("CsoportForm","?","");
                $Form->Hidden("Esemeny_Uj",$this->EsemenyHozzad("Tarol"));
                $Form->Hidden("Formbol",1);


                if ($this->Tobbnyelvu())
                {
                    $Form->Textbox("Név magyar:","NEV_HU_S",$this->TablaAdatKi("NEV_HU_S"),"");
                  $Form->Textbox("Név angol:","NEV_EN_S",$this->TablaAdatKi("NEV_EN_S"),"");
                }else
                {
                    $Form->Textbox("Név:","NEV_S",$this->TablaAdatKi("NEV_S"),"");
                }
                
                if ($this->Kellkulsolink())
                {
                    $Form->Textbox("Menübe kattintáskor ide visz:","LINK_S",$this->TablaAdatKi("LINK_S"),"");
                }


                self::Urlaplink($Form);

                $Akttag="aktív+1!szerkesztés alatt+2";
                if ($this->Lehetinaktiv())
                {
                    $Akttag="inaktív+0!".$Akttag;
                }
                $Form->Radio("Aktív","AKTIV_I",$this->TablaAdatKi("AKTIV_I"),"",$Akttag);

            if ($this->Kellkeres())
            {
                $Form->Szabad2("","Keresőknek szóló információk");

                $Form->Area("Title:","KERESO_TITLE_HU_S",$this->TablaAdatKi("KERESO_TITLE_HU_S"),"");
                $Form->Area("Keywords:","KERESO_KEY_HU_S",$this->TablaAdatKi("KERESO_KEY_HU_S"),"");
                $Form->Area("Description:","KERESO_DESC_HU_S",$this->TablaAdatKi("KERESO_DESC_HU_S"),"");
            }

                $this->Urlapplusz($Form);

                if ($this->Tobbnyelvu())
                {
                    $Form->Areack("Leírás magyar:","SZOVEG_HU_S",$this->TablaAdatKi("SZOVEG_HU_S"),"");

                    $Form->Areack("Leírás angol:","SZOVEG_EN_S",$this->TablaAdatKi("SZOVEG_EN_S"),"");


                }else
                {
                    if ($this->Kellbevez())
                    {
                        $Form->Areack("Bevezető:","BEVEZETO_S",$this->TablaAdatKi("BEVEZETO_S"),"");
                    }
                    $Form->Areack("Leírás","SZOVEG_S",$this->TablaAdatKi("SZOVEG_S"),"");
                }
                
//                $Form->Areack("Leírás magyar","SZOVEG_HU_S",$this->TablaAdatKi("SZOVEG_HU_S"),"");
  //              $Form->Areack("Leírás angol","SZOVEG_EN_S",$this->TablaAdatKi("SZOVEG_EN_S"),"");

                if ($this->Kelllistakep())
                {
                    $Form->Allomanybe("Listás kép","Listakep",$this->Eleres_allom("LISTAKEP"),"");
                }
                 


            
            

                $BUTTONNEV="Submit";
                $ERTEK="Megtekint";
                $JAVA="";


                if ($this->Sessad("Aktfelh")->Jogosultsag()>=100)
                {
                    $Form->Textbox("Dokumentumnál extra tartalomra jön be:","BEILLESZTES_S",$this->TablaAdatKi("BEILLESZTES_S"),"");
                }



                $Form->Gomb("Megtekint","return true","submit","Submit");
//                $Form->Gomb("Alsó galéria szerkesztése","return true","submit","Submit");
                $Form->Szabad2(" ",$Form->Gomb_s("Tárol","return true","submit","Submit")." ".$Form->Gomb_s("Mégsem","location.href='".$this->VisszaEsemenyAd()."'","button"));

                $Tartalom=$Form->OsszeRak();
                $Cim=$this->NevAd()." szerkesztése";
                return $this->Sablonbe("Oldal_admin",array("Cim"=>$Cim,"Tartalom"=>$Tartalom));
        }

        function Kelllistakep()
        {
            return true;
        }
        
        function Hirdoksi()
        {
            return false;
        }
        
        function Listakepmeret()
        {
              
            return "k800-640";
        }
         
         function Listameretez()
         {
            return true;
         }
         
        function Ujgalkep2_fut()
        {
                $Obj=$this->UjObjektumLetrehoz("CGalkepdok","GALDOK");
                return $this->Futtat($Obj)->Tarol($this->Filev("Allomany","tmp_name"),$this->Filev("Allomany","name"),$this->Postgetv("NEV_S"));

        }


        function Ujslidkep_fut()
        {
                $Obj=$this->UjObjektumLetrehoz("CAllomanykep","FELSO_SLIDER");
                $this->Futtat($Obj)->Fajltarol($this->Filev("Allomany","tmp_name"),$this->Filev("Allomany","name"),MAXALLOMANYMERET);
                return $this->Futtat($this)->Felsoslid_fut();
                
                return $this->Felsoslid_fut();

        }
        
        
        function Felsoslid_fut()
        {
            $this->ScriptTarol("
function Ellenor()
{
    if (document.Ujallomany.Allomany.value=='')
    {
        alert('A kép nem lehet üres!');
    }else return true;
    return false;
}");
            
            $Form=new CForm("Ujallomany","","");
            $Form->Hidden("Esemeny_Uj",$this->EsemenyHozzad("Ujslidkep_fut"));
            $Form->Hidden("NEV_S","");
            
            $Form->Allomanybe("Kép forrása: (856x888)","Allomany","","");
            $Form->Gomb("Feltölt","return Ellenor()","submit");
            
            $Vissza["Tartalom"]=$Form->OsszeRak();


            $Keplist["Lista"]=$this->Futtatgy("FELSO_SLIDER","VZ_SORREND_I",null,null,null,1)->Adatlist_adm();
            $Vissza["Tartalom"].=$this->Sablonbe("Lista",$Keplist);
            $Vissza["Vissza"]=$this->EsemenyHozzad("UrlapKi");
            

                    
            $Vissza["Cim"]=$this->AdminNevAd()." felső sliderképek";
            return $this->Sablonbe("Oldal_admin",$Vissza);                
        }
        
        
        function UjAlsoslidkep_fut()
        {
                $Obj=$this->UjObjektumLetrehoz("CMultimedia","ALSO_SLIDER");
                $this->Futtat($Obj)->Fajltarol($this->Filev("Allomany","tmp_name"),$this->Filev("Allomany","name"),MAXALLOMANYMERET,"p400-400",$this->Postgetv("NEV_S"));
                return $this->Futtat($this)->Alsoslid_fut();

        }
        
        
        function Alsoslid_fut()
        {
            $this->ScriptTarol("
function Ellenor()
{
    if (document.Ujallomany.Allomany.value=='')
    {
        alert('A kép nem lehet üres!');
    }else return true;
    return false;
}");
            
            $Form=new CForm("Ujallomany","","");
            $Form->Hidden("Esemeny_Uj",$this->EsemenyHozzad("UjAlsoslidkep_fut"));
            $Form->Textbox("Kép neve:","NEV_S","","");
            
            $Form->Allomanybe("Kép forrása: (400x400)","Allomany","","");
            $Form->Gomb("Feltölt","return Ellenor()","submit");
            
            $Vissza["Tartalom"]=$Form->OsszeRak();


            $Keplist["Lista"]=$this->Futtatgy("ALSO_SLIDER","VZ_SORREND_I",null,null,null,1)->Adatlist_adm();
            $Vissza["Tartalom"].=$this->Sablonbe("Lista",$Keplist);
            $Vissza["Vissza"]=$this->EsemenyHozzad("UrlapKi");
            

                    
            $Vissza["Cim"]=$this->AdminNevAd()." alsó sliderképek";
            return $this->Sablonbe("Oldal_admin",$Vissza);                
        }
                
                 
         function Listagal_fut()
         {
            $this->ScriptTarol("
function Ellenor()
{
    if (document.Ujallomany.Allomany.value=='')
    {
        alert('A kép nem lehet üres!');
    }else return true;
    return false;
}");
            
            $Form=new CForm("Ujallomany","","");
            $Form->Hidden("Esemeny_Uj",$this->EsemenyHozzad("Ujgalkep2_fut"));
            $Form->Textbox("Kép neve:","NEV_S","","");
            $Form->Allomanybe("Kép forrása:","Allomany","","");
            $Form->Gomb("Feltölt","return Ellenor()","submit");
            
            $Vissza["Tartalom"]=$Form->OsszeRak();


            $Keplist["Lista"]=$this->Futtatgy("GALDOK","VZ_SORREND_I",null,null,null,1)->Adatlist_adm();
            $Vissza["Tartalom"].=$this->Sablonbe("Lista",$Keplist);
            $Vissza["Vissza"]=$this->EsemenyHozzad("UrlapKi");
            

                    
            $Vissza["Cim"]=$this->AdminNevAd()." galéria";
            return $this->Sablonbe("Oldal_admin",$Vissza);            
         }

        function Kellbevez()
        {
            return true;
            $Vissza=false;
            $Szul=$this->SzuloObjektum();
            $AZON=$Szul->AzonAd();
            
            return $Vissza;    
        }

        function Kellkeres()
        {
            return true;
        }

        function Tarol_fut()
        {
            $Formbol=$this->Postgetv("Formbol");
            if (!(isset($Formbol)))return $this->VisszaLep();


            $Submit=$this->Postgetv("Submit");
            
            if ($this->Kellkeres())
            {
            
            $this->TablaAdatBe("KERESO_TITLE_HU_S",$this->Postgetv("KERESO_TITLE_HU_S"));
            $this->TablaAdatBe("KERESO_KEY_HU_S",$this->Postgetv("KERESO_KEY_HU_S"));
            $this->TablaAdatBe("KERESO_DESC_HU_S",$this->Postgetv("KERESO_DESC_HU_S"));
            }

            $this->TablaAdatBe("AKTIV_I",$this->Postgetv("AKTIV_I"));
            if ($this->Tobbnyelvu())
            {
                $this->TablaAdatBe("NEV_EN_S",$this->Postgetv("NEV_EN_S"));
                $this->TablaAdatBe("NEV_HU_S",$this->Postgetv("NEV_HU_S"));

                $this->TablaAdatBe("SZOVEG_HU_S",$this->Postgetv("SZOVEG_HU_S"));
                $this->TablaAdatBe("SZOVEG_EN_S",$this->Postgetv("SZOVEG_EN_S"));

            }else
            {
                $this->TablaAdatBe("NEV_S",$this->Postgetv("NEV_S"));
                $this->TablaAdatBe("SZOVEG_S",$this->Postgetv("SZOVEG_S"));
                    if ($this->Kellbevez())
                    {
                        $this->TablaAdatBe("BEVEZETO_S",$this->Postgetv("BEVEZETO_S"));
                    }
            }
            
            
            if ($this->Kelllistakep())
            {
                $this->Allom_tarol("Listakep","LISTAKEP",$this->Listakepmeret(),"CMultimedia");
            }
            
            
                if ($this->Kellkulsolink())
                {
                    $this->TablaAdatBe("LINK_S",$this->Postgetv("LINK_S"));

                }


                 
            

            
            
            if($this->Sessad("Aktfelh")->Jogosultsag()>=100)
            {
                $this->TablaAdatBe("BEILLESZTES_S",$this->Postgetv("BEILLESZTES_S"));
                
            }
            $this->Urlapplusz_tarol();
            $this->Urlaplinktarol();

            $this->Szinkronizal();
            
//            if ($Submit=="Megtekint")return $this->Mutat_pb_fut();


            if ($Submit=="Termékek csatolása")
            {
                return $this->Futtat($this)->Termcsatol_fut();
            }
            if ($Submit=="Felső sliderképek szerkesztése")
            {
                return $this->Futtat($this)->Felsoslid_fut();
            }
            if ($Submit=="Alsó képek szerkesztése")
            {
                return $this->Futtat($this)->Alsoslid_fut();
            }
            if ($Submit=="Alsó galéria szerkesztése")
            {
                return $this->Futtat($this)->Listagal_fut();
            }
            if ($Submit=="Megtekint")
            {
                return $this->Futtat($this)->Mutat_pb_fut();
            }
    
             $this->VisszaLep();
            
        }      
       
        function Felsomenubenyit()
        {
            return false;
        }

       
        function Felsomenube($Kinyit=0)
        {
                $Vissza["Link"]=$this->EsemenyHozzad("");
                $Vissza["Ujablak"]=false;
            
            $Vissza["Nev"]=$this->NevAd();
            $Vissza["Futotte"]=$this->Mostfutotte();
            
            $Vissza["Azon"]=$this->AzonAd();
            
            
            $Ered=$this->Futtatgy("CSOPORT",null,null,1," and AKTIV_I='1'")->Felsomenube($Kinyit+1);
            $Aldb=$Ered["Ossz"];
            if ($Aldb>0)$Vissza["Aldb"]=1;
                   else $Vissza["Aldb"]=0;
            
        /*    if ($Kinyit<2)
            {
                //$Futott=$this->Mostfutobj();
                    $Ered=$this->Futtat($this->Gyerekparam("CSOPORT",null,null,null," and AKTIV_I='1'"))->Felsomenube($Kinyit+1);
                    $Vissza["Menu"]=$Ered["Eredm"];
                    
            }
           */
            return $Vissza;
        }       


        function Alsomenube($Kinyit=0)
        {
            $Vissza["Link"]=$this->EsemenyHozzad("");
            $Vissza["Ujablak"]=false;
            
            $Vissza["Nev"]=$this->NevAd();
            $Vissza["Futotte"]=$this->Mostfutotte();
            
            $Vissza["Azon"]=$this->AzonAd();
            
            
            if ($Kinyit<2)
            {
                //$Futott=$this->Mostfutobj();
                $Ered=$this->Futtat($this->Gyerekparam("CSOPORT",null,null,null," and AKTIV_I='1'  "))->Felsomenube($Kinyit+1);
                    
                $Vissza["Menu"]=$Ered["Eredm"];
            }
           
            return $Vissza;
        } 
}

class CCsoport extends CSzoveg
{
    
        function Adatlist_adm_tag()
        {
            $Vissza["LISTA"]=1;    
            return $Vissza;    
        }

        function Lista_fut()
        {
            $Nyil=0;
            if ($this->Sessad("Aktfelh")->Jogosultsag()>100)$Nyil=1;
            
            $Data["Lista"]=$this->Futtat($this->Gyerekparam("CSOPORT!DOKUMENTUM","VZ_SORREND_I",1,null,"",$Nyil))->Adatlist_adm();
            
            $Data=array_merge($Data,$this->Adat_adm());
            $Vissza["Tartalom"]=$this->Sablonbe("Lista",$Data);
                    
            $Vissza["Cim"]=$this->AdminNevAd();
            return $this->Sablonbe("Oldal_admin",$Vissza);
        }
        


}

class CDokumentumCsoport extends CCsoport
{
    function Jelenhetnavba()
    {
        return false;
    }    
}


class CFelhasznaloCsoport extends CCsoport
{

        function Adatlist_adm_tag()
        {
        //    $Vissza["URLAP"]=1;    
            $Vissza["LISTA"]=1;    
            return $Vissza;    
        }


        function Regisztral_pb_fut()
        {
                $LOGIN=$this->Postgetv("LOGIN");
                $EMAIL=$this->Postgetv("EMAIL");
                
                $Jo=true;
                if($this->EmailCimLetzik($EMAIL))
                {
                    $this->ScriptUzenetAd("@@@A választott e-mail címmel már valaki regisztrált nálunk. Válasszon másikat!§§§");
                    $Jo=false;        
                }
                if($this->LoginLetzik($LOGIN))
                {
                    $this->ScriptUzenetAd("@@@A választott belépési névvel már valaki regisztrált nálunk. Válasszon másikat!§§§");
                    $Jo=false;        
                }
                
                if ($Jo)
                {
                    $JELSZO=$this->Postgetv("JELSZO");
                    $Obj=$this->UjObjektumLetrehoz("CSimaFelhasznalo","FELHASZNALO");
                    $Obj->Regadatbe($LOGIN,$EMAIL,$JELSZO);
                    
                    
                   
                    
                    $Obj=$this->ObjektumLetrehoz($Obj->AzonAd(),0);                        
                    $Obj->BelepesTortent();
                    
                    $this->ScriptUzenetAd("@@@Sikeres regisztráció!§§§");
                    
                    
                    return $this->Futtat($Obj)->Profil_fut();
                    
                }else
                {
                    return $this->Belepablak_pb_fut();    
                }
            
        }

        
        function EmlekeztetoUrlap_pb_fut()
        {
                $EmlekeztetLink=$this->EsemenyHozzad("Emlekeztetokuld_pb_fut");
                $Megsem=$this->VisszaEsemenyAd();
                $Form=new CForm2("EmlekeztetoForm","","");
                $Form->Hidden("Esemeny_Uj",$EmlekeztetLink);
                $Form->TextBox("@@@E-mail§§§:","EMAIL","","");
                $Form->Szabad2(" ",$Form->Gomb("@@@Új jelszó generálása§§§","return Ellenor()","submit"));
                
                $this->ScriptTarol("
        function Ellenor()
        {
        if(document.EmlekeztetoForm.EMAIL.value=='')alert(\"@@@Az e-mail mező nem lehet üres!§§§\");
        else return true;
        return false;
        }
                ");

                $Tartalom=$Form->OsszeRak();
                 $Vissza["Tartalom"]=$Tartalom;
                // $Vissza["Cim"]=$NEV;
             //   $Vissza["Vissza"]=$this->VisszaEsemenyAd();
                

                return $this->Sablonbe("Oldal",$Vissza);
                

        }

        function Emlekeztetokuld_pb_fut()
        {
                
                $EMAIL=$this->Postgetv("EMAIL");
                $Cim="@@@Jelszó emlékeztető§§§";

                //$Felhasznalok=$this->GyerekekVisszaMezoSorrend("FELHASZNALO","VZ_AZON","Novekvo",0,0,0,"EMAIL='$EMAIL' and EMAIL<>''");
                $Felhasznalok=$this->Futtatgy("FELHASZNALO",null,null,null," and EMAIL_S='$EMAIL' and EMAIL_S<>'' and AKTIV_I='1'")->Ujjelszogeneral();
                
                if($Felhasznalok["Ossz"]>0)
                {
                    $this->ScriptUzenetAd("@@@A jelszót elküldtük az email címre!§§§");
                    
                    $Vissza=$this->Mutat_pb_fut();
                }else
                {
                    $this->ScriptUzenetAd("@@@Nincs ilyen felhasználónk!§§§");


                    $Vissza=$this->Futtat($this)->EmlekeztetoUrlap_pb_fut();
                }
                return $Vissza;

        }
                
        function FelhasznaloSzamAd()
        {
                $Vissza=$this->GyerekekSzama("FELHASZNALO");
                return $Vissza;
        }



        function Ujreg_fut()
        {
                $UjFelhasznalo=$this->UjObjektumLetrehoz("CSimaFelhasznalo","FELHASZNALO");
                return $this->Futtat($UjFelhasznalo)->Urlapki_fut();
        }


        function UjFelhasznalo()
        {
                $UjFelhasznalo=$this->UjObjektumLetrehoz("CSimaFelhasznalo","FELHASZNALO");
                $Tartalom=$UjFelhasznalo->Regisztral();
                return $Tartalom;
        }

/*        function Regisztral_pb_fut()
        {
                $UjFelhasznalo=$this->UjObjektumLetrehoz("CSimaFelhasznalo","FELHASZNALO");
                return $this->Futtat($UjFelhasznalo)->Regisztral_pb_fut();

        }
  */      
        function Bejelentkezsutibe($EMAIL,$BELEPKOD)
        {
              $BELEPKOD_KODOLT=$this->convert($BELEPKOD,COOKIEKODKULCS);
              $EMAIL_KODOLT=$this->convert($EMAIL,COOKIEKODKULCS);
              $BE[0]=$BELEPKOD_KODOLT;
              $BE[1]=$EMAIL_KODOLT;
              $BE_IR=serialize($BE);
              $BE_IR=$this->convert($BE_IR,COOKIEKODKULCS);
              $nev=$this->convert("s_belep",COOKIEKODKULCS);

              $BE_IR=base64_encode($BE_IR);
              setcookie($nev, $BE_IR, time()+5184000);
        }

function convert($text, $key = '') {
    if ($key == '') {
        return $text;
    }

    $key = str_replace(' ', '', $key);
    if (strlen($key) < 8) {
        exit('key error');
    }

    $key_len = strlen($key);
    if ($key_len > 32) {
        $key_len = 32;
    }

    $k = array(); // key array
    for ($i = 0; $i < $key_len; ++$i) {
        $k[$i] = ord($key{$i}) & 0x1F;
    }

    for ($i = 0, $j = 0; $i < strlen($text); ++$i) {
        $e = ord($text{$i});

        if ($e & 0xE0) {
            $text{$i} = chr($e ^ $k[$j]);
        }
        $j = ($j + 1) % $key_len;
    }
    return $text;
}

        function Bejelentkezsutiurit()
        {
              $nev=$this->convert("s_belep",COOKIEKODKULCS);
              
              setcookie($nev, "", time());
        }
       
         
         

        function Bejelentkezbeallit()
        {
                $nev=$this->convert("s_belep",COOKIEKODKULCS);
                if (isset($_COOKIE[$nev]))
                {
                        $BELEPKOD_KODOLT=$_COOKIE[$nev];
                        $BELEPKOD_KODOLT=base64_decode($BELEPKOD_KODOLT);

                        $BELEPKODTOMB=$this->convert($BELEPKOD_KODOLT,COOKIEKODKULCS);
                        if ($BELEPKODTOMB!="")
                        {
                                $TOMB=unserialize($BELEPKODTOMB);
                                $BELEPKOD=$TOMB[0];
                                $EMAIL=$TOMB[1];
                                $BELEPKOD=$this->convert($BELEPKOD,COOKIEKODKULCS);
                                $EMAIL=$this->convert($EMAIL,COOKIEKODKULCS);


                                $Felt=" and (LOGIN_S='$EMAIL' or EMAIL_S='$EMAIL' )and JELSZO_S='".($BELEPKOD)."' and AKTIV_I='1'";
                                
                                $Volt=$this->Futtat($this->Gyerekparam("ADMIN!DEBUGADMIN!FELHASZNALO",null,null,null,$Felt))->BelepesTortent_alap();
                                if ($Volt["Ossz"]>0)$_SESSION["Korvalaszt"]=1;
                                
                        }
                }

/*                if (!isset($_SESSION["AktivFelhasznalo"]))
                {
                        $_SESSION["AktivFelhasznalo"]=$this->ObjektumLetrehoz(4,1);
                }
                */



        }
                 

        function Belepes_pb_fut()
        {
                $LOGIN=$this->Postgetv("LOGIN");
                $JELSZO=$this->Postgetv("JELSZO");
                $LOGIN=stripslashes($LOGIN);
                $LOGIN=addslashes($LOGIN);

                $JELSZO=stripslashes($JELSZO);
                $JELSZO=addslashes($JELSZO);
                
                $Vissza=$this->Futtat($this->Gyerekparam("ADMIN!DEBUGADMIN!FELHASZNALO",null,null,null," and (LOGIN_S='$LOGIN' or EMAIL_S='$LOGIN' )and JELSZO_S='".md5($JELSZO)."' and JELSZO_S<>'' and AKTIV_I='1'"))->BelepesTortent();

                if ($Vissza["Ossz"]==0)
                {
                        $this->ScriptUzenetAd("Hibás belépési név vagy jelszó!");
                        $Tartalom=$this->Mutat_pb_fut();
                    
                }else 
                {
                    $EMLEKEZZ=$this->Postgetv("EMLEKEZZ",1);
                    if ($EMLEKEZZ)
                    {
                        $this->Bejelentkezsutibe($LOGIN,md5($JELSZO));    
                    }
                    
                    $Tartalom=$Vissza["Eredm"][0];
                    
                }
                

                return $Tartalom;

        }

        function Face_login($Data)
        {
            $Vissza="";
            $id=$Data["id"];
            $email=$Data["email"];
        
            $Ered=$this->Futtat($this->Gyerekparam("ADMIN!DEBUGADMIN!FELHASZNALO",null,null,null," and (FACE_ID_I='$id' and AKTIV_I='1' )"))->BelepesTortent();
            
            if ($Ered["Ossz"]<1)
            {
                $Ered=$this->Futtat($this->Gyerekparam("ADMIN!DEBUGADMIN!FELHASZNALO",null,null,null," and (FACE_ID_I='$id' ) "))->AzonAd();
                if ($Ered["Ossz"]<1)
                {
                    $Obj=$this->UjObjektumLetrehoz("CSimaFelhasznalo","FELHASZNALO");
                    $Obj->Facedatabe($Data);
                    $Vissza=$Obj->BelepesTortent();
                }
            }else $Vissza=$Ered["Eredm"][0];
            
            return $Vissza;

        }

                 
        function Mutat_pb_fut()
        {            
            
            return $this->Futtat(Focsop_azon)->Mutat_pb_fut();
        }
        
        function Belepablak_pb_fut()        
        {
                 $Menu=$this->Sessad("Aktfelh")->Belepmenu();
                 if (count($Menu)>0)
                 {
                    $Vissza["Belepve"]=1;
                    $Vissza["Menu"]=$Menu;
                 }else $Vissza["Belepve"]=0;
                 $Vissza["Beleplink"]=$this->Futtat(Felhcsop_azon)->EsemenyHozzad("Belepes_pb");
                 $Vissza["Jellink"]=$this->Futtat(Felhcsop_azon)->EsemenyHozzad("EmlekeztetoUrlap_pb_fut");
                 $Vissza["Reglink"]=$this->Futtat(Felhcsop_azon)->EsemenyHozzad("Regisztral_pb_fut");

            
            
                $Tartalom=$this->Sablonbe("Belepes",$Vissza);
                
                $Tartalom=$this->Sablonbe("Oldal_uni",array("Tartalom"=>$Tartalom));
                return $Tartalom;

                    
                if ($this->Sessad("Aktfelh")->Jogosultsag()>0)
                {
                        $Tartalom=$this->Sessad("Aktfelh")->BelepesTortent();
                }else
                {
                        $Form=new CForm("Belepesablak","","");
                        $Form->Hidden("Esemeny_Uj",$this->EsemenyHozzad("Belepes_pb"));
                        $Form->Textbox("Belépési név:","LOGIN","","");
                        $Form->Password("Jelszó:","JELSZO","","");
                        $Form->Gomb("Belépés","return true","submit");
                        
                        $Sabl=new CSablon();
                        $Form->Szabad2(" ",$Sabl->Facelogin());
                        $Tartalom=$Form->OsszeRak();
                        
                        $Tartalom=$this->Sablonbe("Oldal",array("Cim"=>"Belépés","Tartalom"=>$Tartalom));
                }
                return $Tartalom;
        }
        
        
        function Adat_adm_menu()
        {
            $Vissza=parent::Adat_adm_menu();
            $ITEM["Nev"]="Új felhasználó";
            $ITEM["Link"]=$this->EsemenyHozzad("Ujreg_fut");
            $Vissza[]=$ITEM;
            return $Vissza;
             
        }
                
        function Lista_fut()
        {
            $Data["Lista"]=$this->Futtat($this->Gyerekparam("FELHASZNALO","SZAML_NEV_S",0,null,"",0))->Adatlist_adm();
            $Data=array_merge($Data,$this->Adat_adm());

            
            $Vissza["Tartalom"]=$this->Sablonbe("Lista",$Data);
            $Vissza["Cim"]=$this->AdminNevAd();
            return $this->Sablonbe("Oldal_admin",$Vissza);
        }        

        function Lista_Alap()
        {
                $Honnan=Postgetvaltozo("Honnan");
                if (!isset($Honnan))
                {
                        $Honnan=$this->AllapotKi("Honnan");
                }

                $this->AllapotBe("Honnan",$Honnan);

                $GyerekekSzama=$this->GyerekekSzama("FELHASZNALO");
                if (($Honnan>=$GyerekekSzama)&&("$Honnan"!="0"))
                {
                        $Honnan=$Honnan-FELHEGYOLDALON;
                        $this->AllapotBe("Honnan",$Honnan);
                }

                $NEV=$this->Tabla->AdatKi("NEV");
                $SZOVEG=$this->Tabla->AdatKi("SZOVEG");
                $SZOVEG=$this->TagCsere($SZOVEG);
                $Tartalom="";
                if ($SZOVEG!="")
                {
                        $Tartalom.="$SZOVEG<br>";
                }

                $Tartalom.="<table width='100%' border='0' cellspacing='0' cellpadding='0'>";
                $Gyerekek=$this->GyerekekVisszaMezoSorrend("FELHASZNALO","NEV","Novekvo",0,$Honnan,FELHEGYOLDALON);
                if($Gyerekek)
                {
                        $GyerekSzam=count($Gyerekek);
                        for($i=0;$i<$GyerekSzam;$i++)
                        {
                                $Tartalom.="
                                        <tr>
                                         <td>
                                        ".$Gyerekek[$i]->SzerkesztoSor()."
                                         </td>
                                        </tr>
                                        ";
                        }
                }

                $Tartalom.="</table>";

                $Tordeles= $this->Tordeles("Honnan",$Honnan,$GyerekekSzama,"felhasználó",FELHEGYOLDALON,$NEV);
                if (Debugmod())
                {
                        $Tartalom.=$this->DebugBekero();
                }
                $Tartalom.=$this->VisszaLinkAd().$Tordeles;

                return Munkaterulet($NEV,$Tartalom);
        }


        function Elkuld()
        {
                $TEMA_NEV=Postvaltozo("TEMA_NEV");
                $LEVEL_SZOVEG=Postvaltozo("LEVEL_SZOVEG");

                $Gyerekek=$this->GyerekekVissza("ALAP","Novekvo",0);
                if ($Gyerekek)
                {
                        foreach ($Gyerekek as $EgyMail)
                        {
                               $EgyMail->EmailKuld($TEMA_NEV,$LEVEL_SZOVEG);
                        }
                }
                $this->ScriptUtasitasok->UzenetAd("A levelek sikeresen elküldve!");
                return $this->AlapFeladat();

        }



        function EmlekeztetoUrlapKiAd()
        {
                $EmlekeztetLink=$this->EsemenyHozzad("EmlekeztetoFeldolgoz");
                $Megsem=$this->VisszaEsemenyAd();
                $Form=new CForm("EmlekeztetoForm","","");
                $Form->FormTagHidden("Esemeny_Uj",$EmlekeztetLink);
                $Form->FormTagTextBox("@@@E-mail§§§:","EMAIL","","");
                $Form->FormTagCsakSzoveg(" ","<img src='@@@/images/elkuld.gif§§§' style=\"cursor:hand\" onclick=\"if (Ellenor())document.EmlekeztetoForm.submit();\">&nbsp;<img onclick=\"location.href='$Megsem'\" style=\"cursor:hand\" src='@@@/images/megsem.gif§§§' >");
                $this->ScriptTarol("
        function Ellenor()
        {
        if(document.EmlekeztetoForm.EMAIL.value=='')alert(\"@@@Az e-mail mező nem lehet üres!§§§\");
        else return true;
        return false;
        }
                ");

                $Tartalom=$Form->OsszeRak();
                return $Tartalom;

        }

        function EmlekeztetoFeldolgoz()
        {
                $EMAIL=Postvaltozo("EMAIL");
                $Cim="@@@Jelszó emlékeztető§§§";

                $Felhasznalok=$this->GyerekekVisszaMezoSorrend("FELHASZNALO","VZ_AZON","Novekvo",0,0,0,"EMAIL='$EMAIL' and EMAIL<>''");
                if($Felhasznalok)
                {
                        if ($Felhasznalok[0]->Belephet())
                        {
                                $Felhasznalok[0]->Ujjelszogeneral();
                                $this->ScriptUzenetAd("@@@A jelszót elküldtük az email címre!§§§");
                        }else
                        {
                                $this->ScriptUzenetAd("A felhasználó jelenleg nem aktív!");
                        }
                        $Vissza=CVaz::AlapFeladat();
                }else
                {
                       $this->ScriptUzenetAd("@@@Nincs ilyen felhasználónk!§§§");


                       $Vissza=$this->EmlekeztetoUrlapKiAd();
                }
                return $Vissza;

        }




        function CsakKijelentkezes()
        {

                if (is_object($this->Sessad("Aktfelh")))$this->Sessad("Aktfelh")->Kijelentkezes();
                $this->Sessbe("Aktfelh",$this->ObjektumLetrehoz(Senki_azon));
                
                
        }


        function Kijelentkezes_pb_fut()
        {
                $this->CsakKijelentkezes();
                $this->Bejelentkezsutiurit();
                
                return $this->Sessad("Aktfelh")->Alap_fut();
        }
}


class CAllomanyCsoport extends CCsoport
{

        function Alllistipusa()
        {
                return "ALLOMANY";
        }


        function Ujallomany_fut()
        {
                $Obj=$this->UjObjektumLetrehoz("CIkonAllomany",$this->Alllistipusa());
                return $this->Futtat($Obj)->Tarol($this->Filev("Allomany","tmp_name"),$this->Filev("Allomany","name"),$this->Postgetv("Link"));
        }
        
        function Adatlistkozep_publ($Almenukell=false)
        {
            $Vissza=$this->Futtatgy($this->Alllistipusa())->Adatlist_publ();
            return $Vissza;    
        }
        
        function Termekhez_tarol($Termobjhoz)
        {
            $Vissza=$this->Futtatgy($this->Alllistipusa(),"VZ_SORREND_I",null,null,null,1)->Termekhez_tarol($Termobjhoz);
        }


        function Allomanyok($Termobjhoz)
        {
            $Vissza=$this->Futtatgy($this->Alllistipusa(),"VZ_SORREND_I",null,null,null,1)->Adatlist_publ($Termobjhoz);
            return $Vissza["Eredm"];
        }

        
        function Lista_fut()
        {
            $this->ScriptTarol("
function Ellenor()
{
    if (document.Ujallomany.Allomany.value=='')
    {
        alert('Az állomány nem lehet üres!');
    }else return true;
    return false;
}");
            $Data["Lista"]=$this->Futtatgy($this->Alllistipusa(),"VZ_SORREND_I",null,null,null,1)->Adatlist_adm();
            $Data=array_merge($Data,$this->Adat_adm());
            
            $Form=new CForm("Ujallomany","","");
            $Form->Hidden("Esemeny_Uj",$this->EsemenyHozzad("Ujallomany"));
            $Form->Textbox("Név:","Link","","");
            $Form->Allomanybe("Kép forrása:","Allomany","","");
            $Form->Gomb("Feltölt","return Ellenor()","submit");
            
            $Vissza["Tartalom"]=$Form->OsszeRak();
            $Vissza["Tartalom"].=$this->Sablonbe("Lista",$Data);
                    
            $Vissza["Cim"]=$this->AdminNevAd();
            return $this->Sablonbe("Oldal_admin",$Vissza);
        }        
}

class CSpecAllomanyCsoport extends CAllomanyCsoport
{


        function Alllistipusa()
        {
                return "SPEC_ALLOMANY";
        }
}

?>