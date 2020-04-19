<?php

class COldalagazoCsoport extends CCsoport
{
        function Adatlist_adm_tag()
        {
            $Vissza["URLAP"]=1;    
            $Vissza["LISTA"]=1;    
   
            return $Vissza;    
        }        

}
class CHirCsoport extends CCsoport
{




        function Cimbenev()
        {
            return $this->NevAd();
        }


        function Rendezsql()
        {
            return "VZ_SORREND_I desc ";
        }                


        function Kiemeltekad()
        {
    
            $Honnan=$this->Postgetv_jegyez("Honnan",0);
            $Mennyi=5;    
            $Limit="limit ".$Honnan.",".$Mennyi;
            
            $Csoport=array();
            $Ossz=self::$Sql->Lekerst("select count(VZ_AZON_I) as db from VAZ inner join SZOVEG on VAZ.VZ_TABLA_S='SZOVEG' and VZ_TABLA_AZON_I=SZOVEG.AZON_I where VZ_SZINKRONIZALT_I='1' and VZ_SZULO_I='".$this->AzonAd()."' and AKTIV_I='1' ");
            $Ossz=$Ossz[0]["db"];
            $Azon=self::$Sql->Lekerst("select VZ_AZON_I from VAZ inner join SZOVEG on VAZ.VZ_TABLA_S='SZOVEG' and VZ_TABLA_AZON_I=SZOVEG.AZON_I where VZ_SZINKRONIZALT_I='1' and VZ_SZULO_I='".$this->AzonAd()."' and AKTIV_I='1' order by VZ_SORREND_I desc ".$Limit);
            if ($Azon)
            {
                foreach ($Azon as $egy)
                {
                    $Csoport[]=$this->Futtat($egy["VZ_AZON_I"])->Adatlistkozep_publ(false);
                }
            }
            $Vissza["Pager"]=$this->Tordeles("Honnan",$Honnan,$Ossz,$Mennyi,DEF_PHP);
            $Vissza["CSOP"]=$Csoport;

            
            return $Vissza;
            
            
        }
        

        function Lista_fut()
        {
            $Nyil=0;
            $Nyil=1;
            
            $Data["Lista"]=$this->Futtat($this->Gyerekparam("CSOPORT!DOKUMENTUM","VZ_SORREND_I desc",1,null,"",$Nyil))->Adatlist_adm();
            
            $Data=array_merge($Data,$this->Adat_adm());
            $Vissza["Tartalom"]=$this->Sablonbe("Lista",$Data);
                    
            $Vissza["Cim"]=$this->AdminNevAd();
            return $this->Sablonbe("Oldal_admin",$Vissza);
        }
        
        function UjCsoport_fut()
        {
                $Obj=$this->UjObjektumLetrehoz("CAlHirCsoport","CSOPORT");
//                return $Obj->UrlapKi_fut();
                return $this->Futtat($Obj)->UrlapKi_fut();
        }
        
        function Adatlist_adm_tag()
        {
            $Vissza["URLAP"]=1;    
            $Vissza["LISTA"]=1;    
        //    $Vissza["TEKINT"]=1;    
   
            return $Vissza;    
        }        

        function Ujcsopnev()
        {
            return "Új hír";
        }
        
        function Adat_adm_menu()
        {
            $Vissza=parent::Adat_adm_menu();
            $ITEM["Nev"]=$this->Ujcsopnev();
            $ITEM["Link"]=$this->EsemenyHozzad("UjCsoport_fut");
            $Vissza[]=$ITEM;
            return $Vissza;
             
        }
                

        
}

class CAlHirCsoport extends CHirCsoport
{


        function Kelllistakep()
        {
            return true;
        }
        
        function Listakepmeret()
        {
              
            return "p370-257";
        }        

   /*     function Urlapplusz(&$Form)
        {
        }

        function Urlapplusz_tarol()
        {
            
        }*/
        
        
        function UjCsoport_fut()
        {
                $Obj=$this->UjObjektumLetrehoz("CHirdoksi","CSOPORT");
//                return $Obj->UrlapKi_fut();
                return $this->Futtat($Obj)->UrlapKi_fut();
        }
        

        function Ujcsopnev()
        {
            return "Új hír";
        }        
        
                
       function Adatlist_adm_tag()
        {
            $Vissza["URLAP"]=1;    
//            $Vissza["LISTA"]=1;    
            $Vissza["TOROL"]=1;    
   
            return $Vissza;    
        }        
        
}

class CStatAlHirCsoport extends CAlHirCsoport
{
        
       function Adatlist_adm_tag()
        {
            $Vissza["URLAP"]=1;    
            $Vissza["LISTA"]=1;    
   
            return $Vissza;    
        }        
        
}

class CHirdoksi extends CAlHirCsoport
{


       function Adatlist_adm_tag()
        {
            $Vissza["URLAP"]=1;   
   
            $Vissza["TOROL"]=1;    
   
            return $Vissza;    
        }    
        
        function Hirdoksi()
        {
            return true;
        }
        
        function Cimbenev()
        {
            return $this->NevAd();
        }

        function Sliderbemutat()
        {
            $Vissza["Link"]=$this->EsemenyHozzad("");
        
        
            $Vissza["Szoveg"]=$this->TablaAdatKi("BEVEZETO_S");
            
            $Vissza["Nev"]=$this->NevAd();
            $Vissza["Datum"]=$this->TablaAdatKi("MEGJELEN_DAT_S");
            $Vissza["Kep"]=$this->Eleres_allom("FELSOKEP");
              
            return $Vissza;
        }
        
        function Urlapplusz(&$Form)
        {
            $Form->Textbox("Megjelenő dátum:","MEGJELEN_DAT_S",$this->TablaAdatKi("MEGJELEN_DAT_S"),"");
            $Form->Textbox("Nyitólapi sorrend: (nagyobbak jelennek meg elöl)","NYITO_SORREND_I",$this->TablaAdatKi("NYITO_SORREND_I"),"");
            
            $Form->Area("Bevezető:","BEVEZETO_S",$this->TablaAdatKi("BEVEZETO_S"),"");
            $Form->Checkbox("Felső sliderben megjelenik:","NYITO_JELEN_I",$this->TablaAdatKi("NYITO_JELEN_I"),"");
            $Form->Allomanybe("Felső sliderbe kép (1920x648)","Felsokep",$this->Eleres_allom("FELSOKEP"),"");
        }

        function Urlapplusz_tarol()
        {
            $this->TablaAdatBe("NYITO_SORREND_I",$this->Postgetv("NYITO_SORREND_I"));
            $this->TablaAdatBe("MEGJELEN_DAT_S",$this->Postgetv("MEGJELEN_DAT_S"));
            $this->TablaAdatBe("BEVEZETO_S",$this->Postgetv("BEVEZETO_S"));
            $this->TablaAdatBe("NYITO_JELEN_I",$this->Postgetv("NYITO_JELEN_I",1));
            $this->Allom_tarol("Felsokep","FELSOKEP");    
            
        }
        
        
}

        
class CStatikusCsoport extends CCsoport
{


        function UjCsoport_fut()
        {
                $Obj=$this->UjObjektumLetrehoz("CStatikusAlCsoport","CSOPORT");
//                return $Obj->UrlapKi_fut();
                return $this->Futtat($Obj)->UrlapKi_fut();
        }
        
     
        
         function Lista_fut()
        {
  //          $Honnan=$this->Postgetv_jegyez("Honnan",0);
            
            $Data["Lista"]=$this->Futtat($this->Gyerekparam("CSOPORT!DOKUMENTUM!CSOPORT_AR","VZ_SORREND_I",1,null,null,1))->Adatlist_adm();
            $Data=array_merge($Data,$this->Adat_adm());
            
            $Vissza["Tartalom"]=$this->Sablonbe("Lista",$Data);
//            $Vissza["Tartalom"].=$this->Tordeles("Honnan",$Honnan,$Data["Lista"]["Ossz"],10);
                    
            $Vissza["Cim"]=$this->AdminNevAd();
            return $this->Sablonbe("Oldal_admin",$Vissza);
        }
        
        
        function Ujcsopnev()
        {
            return "Új csoport";
        }            
                

        
        function Adat_adm_menu()
        {
            $Vissza=parent::Adat_adm_menu();
            $ITEM["Nev"]=$this->Ujcsopnev();
            $ITEM["Link"]=$this->EsemenyHozzad("UjCsoport_fut");
            $Vissza[]=$ITEM;
            return $Vissza;
             
        }
        

      /*  function Urlapplusz(&$Form)
        {
            $Form->Allomanybe("Listás kép 330x330","Listakep",$this->Eleres_allom("LISTAKEP"),"");
            
        }

        function Urlapplusz_tarol()
        {
            $this->Allom_tarol("Listakep","LISTAKEP",null,"CMultimedia");            
        }        
*/
        

        function Adatlist_adm_tag()
        {
            $Vissza["URLAP"]=1;    
            $Vissza["LISTA"]=1;    
            $Vissza["TEKINT"]=1;    
            return $Vissza;    
        }        

        function UjDokumentum()
        {
                $Obj=$this->UjObjektumLetrehoz("CDokumentum","DOKUMENTUM");
                return $Obj->UrlapKi_fut();
        }
       

}

class CStatikusAlCsoport extends CStatikusCsoport
{

        function Adatlist_adm_tag()
        {
            $Vissza["URLAP"]=1;    
            $Vissza["LISTA"]=1;    
            $Vissza["TOROL"]=1;    
            $Vissza["TEKINT"]=1;    
            return $Vissza;    
        }
       

}

class CPartCsoport extends CCsoport
{
    
        function Adat_adm_menu()
        {
            $Vissza=parent::Adat_adm_menu();
            $ITEM["Nev"]="Új partner";
            $ITEM["Link"]=$this->EsemenyHozzad("UjCsoport_fut");
            $Vissza[]=$ITEM;
            return $Vissza;
             
        }
        
        function Adatlist_adm_tag()
        {
            $Vissza["URLAP"]=1;    
            $Vissza["LISTA"]=1;    
            return $Vissza;    
        }
        
        function Kelllistakep()
        {
            return false;
        }
        
        function UjCsoport_fut()
        {
                $Obj=$this->UjObjektumLetrehoz("CAlPartCsoport","CSOPORT");
                return $this->Futtat($Obj)->UrlapKi_fut();
        }
        

        function Urlapplusz(&$Form)
        {
            $Form->Allomanybe("Listás kép","Listakep",$this->Eleres_allom("LISTAKEP"),"");
            
        }

        function Urlapplusz_tarol()
        {
            $this->Allom_tarol("Listakep","LISTAKEP",null);            
        }        

              
                
}

class CAlPartCsoport extends CPartCsoport
{

        function Adatlist_adm_tag()
        {
            $Vissza["URLAP"]=1;    
            $Vissza["LISTA"]=1;    
            $Vissza["TOROL"]=1;    
            $Vissza["TEKINT"]=1;    
            return $Vissza;    
        }
       

}

  
class CMenucsoport extends CStatikusCsoport
{
    function Jelenhetnavba()
    {
        return false;
    }  

        function Adatlist_adm_tag()
        {
            $Vissza["LISTA"]=1;    
            return $Vissza;    
        }  
        
        function UjCsoport_fut()
        {
                $Obj=$this->UjObjektumLetrehoz("CStatikusAlCsoport","CSOPORT");
//                return $Obj->UrlapKi_fut();
                return $this->Futtat($Obj)->UrlapKi_fut();
        }
}


class CSzolgCsoport extends CCsoport
{
    
        function Adatlist_adm_tag()
        {
            $Vissza["URLAP"]=1;    
            $Vissza["LISTA"]=1;    
            return $Vissza;    
        }
            
     function Urlapki_fut()
        {

                $Tarol=$this->EsemenyHozzad("Tarol");

                $Form=new CForm("CsoportForm",$this->EsemenyHozzad("Tarol"),"");

  
                $Form->Textbox("Név magyar:","NEV_HU_S",$this->TablaAdatKi("NEV_HU_S"),"");
                $Form->Textbox("Név angol:","NEV_EN_S",$this->TablaAdatKi("NEV_EN_S"),"");

                self::Urlaplink($Form);


                $Akttag="aktív+1!szerkesztés alatt+2";
                if ($this->Lehetinaktiv())
                {
                    $Akttag="inaktív+0!".$Akttag;
                }
                $Form->Radio("Aktív","AKTIV_I",$this->TablaAdatKi("AKTIV_I"),"",$Akttag);




                $Form->Checkbox("Nyitólapon megjelenik:","NYITO_JELEN_I",$this->TablaAdatKi("NYITO_JELEN_I"),"");
                $Form->Areack("Nyitólapi bevezető magyar","BEVEZETO_NYIT_HU_S",$this->TablaAdatKi("BEVEZETO_NYIT_HU_S"),"","","150");
                $Form->Areack("Nyitólapi bevezető angol","BEVEZETO_NYIT_EN_S",$this->TablaAdatKi("BEVEZETO_NYIT_EN_S"),"","","150");

  //              $Form->Areack("Bevezető magyar","BEVEZETO_HU_S",$this->TablaAdatKi("BEVEZETO_HU_S"),"","","150");
//                $Form->Areack("Bevezető angol","BEVEZETO_EN_S",$this->TablaAdatKi("BEVEZETO_EN_S"),"","","150");




                $Form->Areack("Leírás magyar","SZOVEG_HU_S",$this->TablaAdatKi("SZOVEG_HU_S"),"");
                $Form->Areack("Leírás angol","SZOVEG_EN_S",$this->TablaAdatKi("SZOVEG_EN_S"),"");

                $Form->Allomanybe("Listás kép","Listakep",$this->Eleres_allom("LISTAKEP"),"");
                


                $BUTTONNEV="Submit";
                $ERTEK="Megtekint";
                $JAVA="";


                if ($this->Sessad("Aktfelh")->Jogosultsag()>=100)
                {
                    $Form->Textbox("Dokumentumnál extra tartalomra jön be:","BEILLESZTES_S",$this->TablaAdatKi("BEILLESZTES_S"),"");
                }



                $Form->Gomb("Megtekint","return true","submit","Submit");
                $Form->Szabad2(" ",$Form->Gomb_s("Tárol","return true","submit","Submit")." ".$Form->Gomb_s("Mégsem","location.href='".$this->VisszaEsemenyAd()."'","button"));

                $Tartalom=$Form->OsszeRak();
                $Cim=$this->NevAd()." szerkesztése";
                return $this->Sablonbe("Oldal_admin",array("Cim"=>$Cim,"Tartalom"=>$Tartalom));
        }        
       
        function NevAd()
        {
            return $this->TablaAdatKi("NEV_".$this->Nyelvadpub()."_S");
        }
               
    function Tarol_fut()
        {


            $Submit=$this->Postgetv("Submit");
            $this->TablaAdatBe("KERESO_TITLE_HU_S",$this->Postgetv("KERESO_TITLE_HU_S"));
            $this->TablaAdatBe("KERESO_KEY_HU_S",$this->Postgetv("KERESO_KEY_HU_S"));
            $this->TablaAdatBe("KERESO_DESC_HU_S",$this->Postgetv("KERESO_DESC_HU_S"));

            $this->TablaAdatBe("NYITO_JELEN_I",$this->Postgetv("NYITO_JELEN_I",1));
            $this->TablaAdatBe("AKTIV_I",$this->Postgetv("AKTIV_I"));
            $this->TablaAdatBe("NEV_HU_S",$this->Postgetv("NEV_HU_S"));
            $this->TablaAdatBe("NEV_EN_S",$this->Postgetv("NEV_EN_S"));
            $this->TablaAdatBe("SZOVEG_HU_S",$this->Postgetv("SZOVEG_HU_S"));
            $this->TablaAdatBe("SZOVEG_EN_S",$this->Postgetv("SZOVEG_EN_S"));
            

            $this->TablaAdatBe("BEVEZETO_NYIT_HU_S",$this->Postgetv("BEVEZETO_NYIT_HU_S"));
            $this->TablaAdatBe("BEVEZETO_NYIT_EN_S",$this->Postgetv("BEVEZETO_NYIT_EN_S"));
//            $this->TablaAdatBe("BEVEZETO_HU_S",$this->Postgetv("BEVEZETO_HU_S"));
//            $this->TablaAdatBe("BEVEZETO_EN_S",$this->Postgetv("BEVEZETO_EN_S"));
            

            
            
            $this->Allom_tarol("Listakep","LISTAKEP");              
            
            
            if($this->Sessad("Aktfelh")->Jogosultsag()>=99)
            {
                $this->TablaAdatBe("BEILLESZTES_S",$this->Postgetv("BEILLESZTES_S"));
                
            }
            $this->Urlapplusz_tarol();
            $this->Urlaplinktarol();

            $this->Szinkronizal();
            
//            if ($Submit=="Megtekint")return $this->Mutat_pb_fut();
            if ($Submit=="Megtekint")
            {
                return $this->Futtat($this)->Mutat_pb_fut();
            }

             $this->VisszaLep();
            
        }   
        
        function UjCsoport_fut()
        {
                $Obj=$this->UjObjektumLetrehoz("CAlSzolgCsoport","CSOPORT");
//                return $Obj->UrlapKi_fut();
                return $this->Futtat($Obj)->UrlapKi_fut();
        }
        
        
        function Adat_adm_menu()
        {
            $Vissza=parent::Adat_adm_menu();
            $ITEM["Nev"]="Új szolgáltatás";
            $ITEM["Link"]=$this->EsemenyHozzad("UjCsoport_fut");
            $Vissza[]=$ITEM;
            return $Vissza;
             
        }                  
}


class CAlSzolgCsoport extends CSzolgCsoport
{
            
        function Adat_adm_menu()
        {
             
        }
        
        function Adatlist_adm_tag()
        {
            $Vissza["URLAP"]=1;    
            $Vissza["TOROL"]=1;    
            return $Vissza;    
        }
                
        function Nyitolapiadat()
        {
            $Vissza["Link"]=$this->EsemenyHozzad("");
            $Vissza["Szoveg"]=$this->TablaAdatKi("BEVEZETO_NYIT_".$this->Nyelvadpub()."_S");
            $Vissza["Nev"]=$this->NevAd();
            $Vissza["Kep"]=$this->Eleres_allom("LISTAKEP");
            
              
            return $Vissza;
            
        }


        function Adatlistkozep_publ($Almenukell=false)
        {
            $Vissza["Link"]=$this->EsemenyHozzad("");
        
        
            $Vissza["Bevezeto"]=$this->TablaAdatKi("SZOVEG_".$this->Nyelvadpub()."_S");
            $Vissza["Datum"]=$this->TablaAdatKi("MEGJELEN_DAT_S");
            
            $Vissza["Nev"]=$this->NevAd();
            $Vissza["Kep"]=$this->Eleres_allom("LISTAKEP");
           // $Vissza["Almenuk"]=$this->Almenulistapubl();
              
            return $Vissza;
        }
}


        
class CKapcscsoport extends CStatikusCsoport
{

        function Mutat_pb_fut()
        {
                $NEV=$this->NevAd();
                $SZOVEG=$this->SzovegCserel();
                
                $Ered=$this->Futtat($this->Gyerekparam("CSOPORT",null,1,null," and AKTIV_I='1' ".$this->Hozzafersql()))->Adatlistkozep_publ();
                
                
                $SZOVEG.=$this->Sablonbe("Menumunkateruletre",$Ered["Eredm"]);

                $Kapcsform=$this->KapcsolUrlapKi($this);

                $Vissza["Tartalom"]=$SZOVEG;
                $Vissza["Cim"]=$NEV;
                $Vissza["Kapcsform"]=$Kapcsform;
                //$Vissza["Vissza"]=$this->VisszaEsemenyAd();
                

                return $this->Sablonbe("Oldalkapcsolat",$Vissza);

        }        
}        
  
class CNyitoscsoport extends CStatikusCsoport
{
        function Listameretez()
        {
            return false;
        }


        function Urlapplusz(&$Form)
        {
            $Form->Areack("Bevezető:","BEVEZETO_S",$this->TablaAdatKi("BEVEZETO_S"),"");
        }

        function Urlapplusz_tarol()
        {
            $this->TablaAdatBe("BEVEZETO_S",$this->Postgetv("BEVEZETO_S"));    
        }
        
       function Adatlist_adm_tag()
        {
            $Vissza["URLAP"]=1;    
            $Vissza["LISTA"]=1;    
            return $Vissza;    
        } 
        
        function Kelllistakep()
        {
            return true;
        }
        
        function Listakepmeret()
        {
//            return "p271-191";
        }
               
}

class CCsomajcsoport extends CStatikusCsoport
{
        function UjCsoport_fut()
        {
                $Obj=$this->UjObjektumLetrehoz("CAlCsomajcsoport","CSOPORT");

                return $this->Futtat($Obj)->UrlapKi_fut();
        }
        
        function Tobbnyelvu()
        {
            return true;
        }
        
        function Linkgeneral()
        {
            $Szul=$this->SzuloObjektum();
            
            $Vissza["Link"]=$Szul->EsemenyHozzad("")."#K".$this->AzonAd();
            $Vissza["Ujablak"]=false;
            return $Vissza;
        }
            

        function Csomagokad()
        {
            $Vissza=array();
            $Nyelv=$this->Nyelvadpub();
            $Azon=self::$Sql->Lekerst("select VZ_AZON_I from VAZ inner join SZOVEG on VAZ.VZ_TABLA_S='SZOVEG' and VZ_TABLA_AZON_I=SZOVEG.AZON_I where VZ_SZINKRONIZALT_I='1' and VZ_OBJEKTUM_S='CAl2Csomajcsoport' and AKTIV_I='1' order by NEV_".$Nyelv."_S");
            if ($Azon)
            {
                foreach ($Azon as $egy)
                {
                    $Vissza[]=$this->Futtat($egy["VZ_AZON_I"])->Adatlistkozep_publ(false);
                }
            }
            return $Vissza;
            
        }
                

        
        
}

class CAlCsomajcsoport extends CCsomajcsoport
{
        function Adatlist_adm_tag()
        {
            $Vissza["URLAP"]=1;    
            $Vissza["LISTA"]=1;    
            $Vissza["TOROL"]=1;    
            return $Vissza;    
        }
        
        function Adatlistkozep_publ($Almenukell=false)
        {
            return parent::Adatlistkozep_publ(true);
        }

        
        /*
        function Urlapplusz(&$Form)
        {
            $Form->Areack("Bevezető:","BEVEZETO_S",$this->TablaAdatKi("BEVEZETO_S"),"");
        }

        function Urlapplusz_tarol()
        {
            $this->TablaAdatBe("BEVEZETO_S",$this->Postgetv("BEVEZETO_S"));    
        }
                
        */
        
        function UjCsoport_fut()
        {
                $Obj=$this->UjObjektumLetrehoz("CAl2Csomajcsoport","CSOPORT");

                return $this->Futtat($Obj)->UrlapKi_fut();
        }
        
        
        
        function Almenufelt()
        {
            $Nyelv=$this->Nyelvadpub();
            
            $Felt=" and CSAKMAGYAR_I='0' ";
            if ($Nyelv=="HU")$Felt="";
            
            return $Felt;
        }        
        
        

                
       
}

class CAl2Csomajcsoport extends CCsomajcsoport
{
        function Kelllistakep()
        {
            return true;
        }
        
        
        function Linkgeneral()
        {
           
            $Vissza["Link"]=$this->EsemenyHozzad("");
            $Vissza["Ujablak"]=false;
            return $Vissza;
        }
           
           
        function SzovegCserel()
        {
            $Vissza=parent::SzovegCserel();
            
            $Sablon=new CSablon();
            
            $Data=$this->Tombre(FOGLAL_TIPUS);
            $Vissza.="<br>
             <div id='foglalas-urlap'>
             ";
             $Foglink=$this->Futtat(Focsop_azon)->EsemenyHozzad("Foglallap_pb_fut");
             $AZON=$this->AzonAd();
             
            foreach ($Data as $item)
            {  
//                $Vissza.=$Sablon->Gombcsinal($item[0],"location.href='';","submit");
                
                $Vissza.="<a style=\"margin: 3px\" href='$Foglink?FOGLAL_TIPUS=".$item[1]."&CSOM=$AZON' class='next_btn'>".$item[0]."</a>
";
            }
            $Vissza.="</div>
            <br>";
            return $Vissza;
        }           
           
        
        
        function Urlapplusz(&$Form)
        {
            $Form->Checkbox("Kiemelt ajánlatok között megjelenik:","KIEMELT_I",$this->TablaAdatKi("KIEMELT_I"),"");
            $Form->Checkbox("Csak magyar oldalon:","CSAKMAGYAR_I",$this->TablaAdatKi("CSAKMAGYAR_I"),"");
        }

        function Urlapplusz_tarol()
        {
            $this->TablaAdatBe("KIEMELT_I",$this->Postgetv("KIEMELT_I",99));    
            $this->TablaAdatBe("CSAKMAGYAR_I",$this->Postgetv("CSAKMAGYAR_I",99));    
        
        }
                
        
                 
                   
                
        
        function Adatlist_adm_tag()
        {
            $Vissza["URLAP"]=1;    
            $Vissza["LISTA"]=1;    
            $Vissza["TOROL"]=1;    
            return $Vissza;    
        }
                
        function Listakepmeret()
        {
            return "p290-290";
        }    
}


class CNyitoslidcsoport extends CStatikusCsoport
{
        function Listameretez()
        {
            return false;
        }

       function Adatlist_adm_tag()
        {
            $Vissza["URLAP"]=1;    
            $Vissza["LISTA"]=1;    
            return $Vissza;    
        } 
        
        function Adat_adm_menu()
        {

            $ITEM["Nev"]="Új csoport";
            $ITEM["Link"]=$this->EsemenyHozzad("UjCsoport_fut");
            $Vissza[]=$ITEM;
            return $Vissza;
             
        }
        
        function UjCsoport_fut()
        {
                $Obj=$this->UjObjektumLetrehoz("CAlNyitoslidcsoport","CSOPORT");
//                return $Obj->UrlapKi_fut();
                return $this->Futtat($Obj)->UrlapKi_fut();
        }        
}

class CAlNyitoslidcsoport extends CNyitoslidcsoport
{
        function Adat_adm_menu()
        {
        }

        function Urlapplusz(&$Form)
        {
            $Form->Textbox("Név 2. sorba:","BEVEZETO_S",$this->TablaAdatKi("BEVEZETO_S"),"");
            
            //$Form->Allomanybe("Felső kép (940x406)","Listakep",$this->Eleres_allom("LISTAKEP"),"");
             //       $Form->Textbox("Link a képen :","LINK_S",$this->TablaAdatKi("LINK_S"),"");
                
        }

        function Kelllistakep()
        {
            return false;
            
        }

        function Urlapplusz_tarol()
        {
//            $this->Allom_tarol("Listakep","LISTAKEP");
            
            $this->TablaAdatBe("BEVEZETO_S",$this->Postgetv("BEVEZETO_S"));            
        }
        
        function Adatlistkozep_publ($Almenukell=false)
        {
            $Nyito=$Almenukell;
            $LINK_S=$this->TablaAdatKi("LINK_S");
            if ($LINK_S!="")$Vissza["Link"]=$LINK_S;
                    else $Vissza["Link"]="";
            $Vissza["Bevezeto"]=$this->TablaAdatKi("BEVEZETO_S");
            $Vissza["Szoveg"]=$this->TablaAdatKi("SZOVEG_S");
            $Vissza["Nev"]=$this->NevAd();
            $Vissza["Kep"]=$this->Eleres_allom("LISTAKEP");
            if ($Nyito)
            {
                $Vissza["Nyitokep"]=$this->Eleres_allom("Nyitokep");
            }
              
            return $Vissza;
        }
        
       function Adatlist_adm_tag()
        {
            $Vissza["URLAP"]=1;    
            $Vissza["LISTA"]=1;    
            $Vissza["TOROL"]=1;    
            return $Vissza;    
        } 

        
}
                

class CNyitoszovCsoport extends CStatikusCsoport
{

        function Adatlist_adm_tag()
        {
            $Vissza["URLAP"]=1;    
//            $Vissza["LISTA"]=1;    
            $Vissza["TEKINT"]=1;    
            return $Vissza;    
        } 
        
        function Kellbevez()
        {
            return false;
        }
}  
  
?>