<?php

/**
* CKapcsform  - publikus kapcsolati formok    
*/

class CKapcsform  extends CVaz_bovit 
{


     function AjanlElkuld_pb_fut()
      {
            $Jo=true;
              
                
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

            
            $NEV=$this->Postgetv("NEV");
            $TELEFON=$this->Postgetv("TELEFON");
            $EMAIL=$this->Postgetv("EMAIL");
            $UZENET=$this->Postgetv("UZENET");
            $NMETER=$this->Postgetv("NMETER");
            $ERTESIT=$this->Postgetv("ERTESIT",1);
            if ($NEV=="")
            {
                $this->ScriptUzenetAd("@@@A név nem lehet üres!§§§");
                $Jo=false;
            }
            if ($EMAIL=="")
            {
                $this->ScriptUzenetAd("@@@Az email nem lehet üres!§§§");
                $Jo=false;
            }
            if ($UZENET=="")
            {
                $this->ScriptUzenetAd("@@@Az üzenet nem lehet üres!§§§");
                $Jo=false;
            }
            if ($Jo)
            {
                $ERTES_IR="nem";
                if ($ERTESIT)$ERTES_IR="igen";

                $this->ScriptUzenetAd("@@@Üzenet elküldve!§§§");
                $Tema=MAIL_TARGY." ajánlatkérés";
                $Level="Név: $NEV <br>
                Email: $EMAIL <br>
                Telefon: $TELEFON <br>
                Mekkora lakást keres? $NMETER <br>
                Új ütemről értesítést kérek: $ERTES_IR <br>
                
                Üzenet: $UZENET ";
                $this->Mailkuld($Tema,$Level,OLDALEMAIL);
            }
                
                }
            
            if ($Jo)return $this->Futtat(Focsop_azon)->Mutat_pb_fut();
                else
                {
                    $Dokobj=$this->AllapotKi("Dokbol");
                    return $this->Futtat($Dokobj)->Mutat_pb_fut();
                    
                }
             
            
      }
              
   
      
      function KapcsolUrlapKi($Dokobj)
      {
            $DATA["Link"]=$this->EsemenyHozzad("KapcsolElkuld_pb");

          /*       $szam1=rand(0,15);
                 $szam2=rand(0,15);


            $this->AllapotBe("KOD",($szam1+$szam2));
            $Kodir=$szam1."+".$szam2."=";
            $this->AllapotBe("KODIR",$Kodir);
*/

            $this->AllapotBe("Dokbol",$Dokobj->AzonAd());
            $DATA["Kodlink"]=$this->EsemenyHozzad("Kodir_pb");
            
            return $this->Sablonbe("Kapcsurlap",$DATA);            
 
      }
      
      function KapcsolElkuld_pb_fut()
      {
            $Jo=true;
            
  /*              $KOD_FORM=$this->Postgetv("KOD_FORM");
                if (!isset($KOD_FORM))$KOD_FORM="";
                $Kod=$this->AllapotKi("KOD");
                if (("$KOD_FORM"!="$Kod")||($KOD_FORM=="")||("$KOD_FORM"=="0")||($Kod=="0")||($Kod==""))
                {
                      $this->ScriptUzenetAd("@@@Hiba az ellenőrző kód megadásánál§§§");
                        $Jo=false;
                }
*/                
                
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

            
            $NEV=$this->Postgetv("NEV");
            $TELEFON=$this->Postgetv("TELEFON");
            $EMAIL=$this->Postgetv("EMAIL");
            $UZENET=$this->Postgetv("UZENET");
            
            if ($NEV=="")
            {
                $this->ScriptUzenetAd("@@@A név nem lehet üres!§§§");
                $Jo=false;
            }
            if ($EMAIL=="")
            {
                $this->ScriptUzenetAd("@@@Az email nem lehet üres!§§§");
                $Jo=false;
            }
            if ($UZENET=="")
            {
                $this->ScriptUzenetAd("@@@Az üzenet nem lehet üres!§§§");
                $Jo=false;
            }
            if ($Jo)
            {
                $this->ScriptUzenetAd("@@@Üzenet elküldve!§§§");
                $Tema=MAIL_TARGY." @@@kapcsolatfelvétel§§§";
                $Level="Név: $NEV <br>
                Email: $EMAIL <br>
                Telefon: $TELEFON <br>
                
                Üzenet: $UZENET ";
                $this->Mailkuld($Tema,$Level,OLDALEMAIL);
            }
                
                }
            
            if ($Jo)return $this->Futtat(Focsop_azon)->Mutat_pb_fut();
                else
                {
                    $Dokobj=$this->AllapotKi("Dokbol");
                    return $this->Futtat($Dokobj)->Mutat_pb_fut();
                    
                }
             
            
      }

}


?>