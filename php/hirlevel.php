<?php

define ("HIRLEGYKORBE", "50");






class CHirlevelCsoport extends CCsoport
{



        function HirlevelKuld_fut()
        {
                if (FROM_EMAIL=="info@ysolutions.hu")
                {
                        echo "<script>alert('Nincs rendes küldő cim megadva!');</script>";
                        exit;
                }
                
                

                 $KuldHonnan=$this->Postgetv("KuldHonnan");
                 $KuldHanyat=$this->Postgetv("KuldHanyat");

                 if (!isset($KuldHonnan))
                 {
                         $KuldHonnan=$this->AllapotKi("KuldHonnan",0);
                 }
                 $this->AllapotBe("KuldHonnan",$KuldHonnan);

                 if (!isset($KuldHanyat))
                 {
                         $KuldHanyat=$this->AllapotKi("KuldHanyat",0);
                 }
                $this->AllapotBe("KuldHanyat",$KuldHanyat);

                 if ("$KuldHonnan"=="")$KuldHonnan=0;
                 if ("$KuldHanyat"=="")$KuldHanyat=0;


                $Tomb=$this->Futtatgy("DOKUMENTUM")->Hirlevbeadat();
                if ($Tomb["Ossz"]<1)
                {
                        echo "<script>alert('Nincs kiküldendő dokumentum!');</script>";
                        exit;
                }
                $Dokadat=$Tomb["Eredm"][0];
                
                if ("$KuldHonnan"=="0")
                {
                    $this->Futtat(Arch_hirazon)->Hirlevelment($Dokadat["Obj"]);
                }
            /*    $Kozos=new CKozos();
                $Futhat=$Kozos->Futatthat(1,"Hirlevkuldes",0);                
                if (!($Futhat["Futhat"]))
               {
                    
                    
                    $Link=$this->EsemenyHozzad("HirlevelKuld");
                    $Vissza="<script>
                                function tolt()
                                {
                                        location.href='$Link?KuldHonnan=".($KuldHonnan)."&KuldHanyat=$KuldHanyat'
                                }
                                setTimeout('tolt();',25000);
                                </script>
                                
                                ";
                    
                    $Vissza.="A hírlevelek kiküldése több lépcsőben történik. Kérjük ne szakitsa meg a küldést.<br>$KuldHanyat db hírlevél kiküldve. Újraindult ".($KuldHonnan)."-tól.";
                    
                    return $Vissza;
                }*/
                


                
                
                
                $Szoveg=$Dokadat["Szoveg"];
                $Tema=$Dokadat["Nev"];
                
                                

                //$Felt=$this->AllapotKi("Feltetel");
                //if ($Felt=="0")$Felt="";

              //  if ($Felt=="")$Felt=" and 1=2";
                    $Felt=" and HIRLEVEL_I='1' and AKTIV_I='1' ";
                
//                $Felt.=" and EMAIL_S='h.andras@ysolutions.hu'";                 

//                $sql="select VZ_AZON_I from VAZ,EMAILCIM where VZ_TABLA_S='EMAILCIM' and VZ_TABLA_AZON_I=AZON_I and VZ_SZINKRONIZALT_I='1' and VZ_LISTA_S='EMAILCIM' $Felt order by AZON_I limit $KuldHonnan,".HIRLEGYKORBE;

                $sql="select VZ_AZON_I from VAZ,FELHASZNALO where VZ_TABLA_S='FELHASZNALO' and VZ_TABLA_AZON_I=AZON_I and VZ_SZINKRONIZALT_I='1' and VZ_OBJEKTUM_S='CSimaFelhasznalo' $Felt order by AZON_I limit $KuldHonnan,".HIRLEGYKORBE;
                $Gyerekek=self::$Sql->Lekerst($sql);
                
                $Kuldott=false;
                $Link=$this->EsemenyHozzad("Leiratkozkodol");
                if ($Gyerekek)
                {
                        foreach ($Gyerekek as $EgyMail)
                        {
                            $Kuldott=true;
                            $Obj=$this->ObjektumLetrehoz($EgyMail["VZ_AZON_I"],0);
                            $Obj->HirlevEmailKuld($Tema,$Szoveg,$Link);
                            $KuldHanyat++;
                        }
                }

                //$Kozos->Futatthat(0,"",0,$Futhat); 

                if ($Kuldott)
                {
                        $Link=$this->EsemenyHozzad("HirlevelKuld");
                        $Vissza="<script>
                                function tolt()
                                {
                                        location.href='$Link?KuldHonnan=".($KuldHonnan+HIRLEGYKORBE)."&KuldHanyat=$KuldHanyat'
                                }
                                setTimeout('tolt();',25000);
                                </script>
                                ;
                                ";
                                //$Szazalek=round((($KuldHonnan+HIRLEGYKORBE)/$EmailekSzama)*100);
                                $Vissza.="A hírlevelek kiküldése több lépcsőben történik. Kérjük ne szakitsa meg a küldést.<br>$KuldHanyat db hírlevél kiküldve. Újraindult ".($KuldHonnan+HIRLEGYKORBE)."-tól.";

                }else
                {
                    $this->ScriptUzenetAd("$KuldHanyat hírlevél elküldése sikeresen megtörtént.");
                    //$Vissza=$this->Lista_fut();
                    $Vissza=$this->Futtat($this)->Lista_fut();
                    $this->AllapotBe("KuldHonnan",0);
                    $this->AllapotBe("KuldHanyat",0);
                }


                return $Vissza;
        }
        
        
         function Lista_fut()
        {
  //          $Honnan=$this->Postgetv_jegyez("Honnan",0);
            

            $Data0["Lista"]=$this->Futtat($this->Gyerekparam("DOKUMENTUM"))->Adatlist_adm();
            $Vissza["Tartalom"]=$this->Sablonbe("Lista",$Data0);

            $Data2["Lista"]=$this->Futtat($this->Gyerekparam("CSOPORT"))->Adatlist_adm();
            $Vissza["Tartalom"].=$this->Sablonbe("Lista",$Data2);

            $Data["Lista"]=$this->Futtat($this->Gyerekparam("EMAILCIM","EMAIL_S",1,30,null,0))->Adatlist_adm();
            $Data=array_merge($Data,$this->Adat_adm());
            
            $Vissza["Tartalom"].=$this->Sablonbe("Lista",$Data).$this->Adminfelform();
            

//            $Vissza["Tartalom"].=$this->Tordeles("Honnan",$Honnan,$Data["Lista"]["Ossz"],10);
                    
                    $Sab=new CSablon();
            $Vissza["Tartalom"].=$Sab->Gombcsinal("Vissza","location.href='".$this->VisszaEsemenyAd()."'","button");
            $Vissza["Vissza"]=$this->VisszaEsemenyAd();
            return $this->Sablonbe("Oldal_admin",$Vissza);
        }
        
        function Tesztkuld_fut()
        {
            $TESZT_MAIL=$this->Postgetv("TESZT_MAIL");
            
                $Tomb=$this->Futtatgy("DOKUMENTUM")->Hirlevbeadat();
            
                $Dokadat=$Tomb["Eredm"][0];
                
                $Uzenet=$Dokadat["Szoveg"];
                $Targy=$Dokadat["Nev"];

            

//                $EMAIL=$this->NevAd();
/*                $Uzenet=$Uzenet."<br><br><a href='".OLDALCIM."'>".OLDALCIM."</a>";*/
                $Leiratkoz="
                <a href='' target='_blank' class=link>Leiratkozás a hírlevélről</a>
                ";
                $Uzenet=str_replace("<!--leiratk-->",$Leiratkoz,$Uzenet);

               
                $this->Mailkuld($Targy,$Uzenet,$TESZT_MAIL,"");
                $this->ScriptUzenetAd("Hírlevél ".$TESZT_MAIL." címre kiküldve!");
                
                return $this->Futtat($this)->Lista_fut();
            
        }
        
        function Adminfelform()
        {

                $Form=new CForm2("TermekForm","","");
                $Form->Hidden("Esemeny_Uj",$this->EsemenyHozzad("Tesztkuld_fut"));

                $Form->Szabad2(" ","Teszt hírlevél küldés");
                $Form->Textbox("Email cím:","TESZT_MAIL",OLDALEMAIL,"");
                $Form->Szabad2(" ","<input type='submit' value='Hírlevél küldése a fenti címre' class='butt_kosarba formbutt' >");
                
                
                return $Form->OsszeRak();
        }
        
        function Adat_adm_vissza()
        {
            return "";
        }
            
        
                
        function Adat_adm_menu()
        {
            $Vissza=array();

/*            $ITEM["Nev"]="Új csoport";
            $ITEM["Link"]=$this->EsemenyHozzad("UjCsoport_fut");
            $Vissza[]=$ITEM;
            */

            $ITEM["Nev"]="<b>Hírlevél küldése</b>";
            //$ITEM["Link"]=$this->EsemenyHozzad("HirlevelKuldurlap_fut");
            $ITEM["Link"]=$this->EsemenyHozzad("HirlevelKuld_fut");
            $ITEM["Confirm"]=1;
            
            $Vissza[]=$ITEM;



            return $Vissza;
             
        }
            
            
        function HirlevelKuldurlaptarol_fut()
        {
                $Kuldegyszer=array();
                $Kuld=array_keys ($_POST, "checkbox");
                $Felt="";
                if ($Kuld)
                {
                        $csoportdb=count($Kuld);
                        for ($c=0;$c<$csoportdb;$c++)
                        {
                                $Egyazon=substr($Kuld[$c],2);
                                if ($Felt!="")$Felt.=" or ";
                                $Felt.=" (VZ_SZULO_I='$Egyazon' )";
                        }
                }

                if ($Felt!="")$Felt=" and ($Felt) ";
                    else $Felt=" and 1=2 ";
                $this->AllapotBe("Feltetel",$Felt);

                return $this->HirlevelKuld_fut();
        }            
            
     function HirlevelKuldurlap_fut()
        {
            
                $Tarol=$this->EsemenyHozzad("HirlevelKuldurlaptarol_fut");
                
                $Tartalom="
                <table width=100% cellpadding=0 cellspacing=0>
                <form name='Levelkuldform' method='post' action='' enctype='multipart/form-data'>
                <input type=hidden name='Esemeny_Uj' value='$Tarol'>";

                $this->ScriptTarol("
                jelolt=0;
                function Ellenor()
                {
                       if (jelolt==0)
                       {
                        alert('Legalább egy kategóriát ki kell választani!');
                       }else
                       if (document.Levelkuldform.DOKSI.value=='0')
                       {
                        alert('Válassza ki melyik hírlevelet akarja kiküldeni!');
                       }else return true;
                       return false;
                }

                ");
                $Tartalom.="
                 <tr>
                   <td class=text>Válassza ki kiknek küld hírlevelet!
                   </td>
                 </tr>
                      

                                ";
                    $Csopok=$this->Futtatgy("CSOPORT")->ValasztLinkAd(1);
                    foreach ($Csopok["Eredm"] as $egycsop)
                    {
                        $Tartalom.=$egycsop;
                    }
                    
                        
                                                    
                    /*    $Csoportok=$this->GyerekekVissza("CSOPORT","Novekvo",0);
                        if ($Csoportok)
                        {
                                $db=count($Csoportok);
                                for ($c=0;$c<$db;$c++)
                                {
                                        $Tartalom.=$Csoportok[$c]->ValasztLinkAd(1);
                                }
                        }
*/


                $Tartalom.="
                 <tr>
                   <td align=center>
                    <input type=submit name=Submit value='Elküld' onclick=\"return Ellenor();\">
                    <input type=button name=Gomb value='Mégsem' onclick=\"location.href='".$this->EsemenyHozzad("Lista_fut")."'\"></td>
                 </tr>
                </form>
                ";
                $Tartalom.="</table>";
             
                $Cim="Hírlevél küldés";
        
                $Vissza["Cim"]=$Cim;
                $Vissza["Tartalom"]=$Tartalom;
                return $this->Sablonbe("Oldal_admin",$Vissza);

                
                return  MunkaTerulet($Cim,$Tartalom,1);

        }

        function ValasztLinkAd($BeljebbSzam)
        {

                $Beljebb="";
                for ($c=0;$c<$BeljebbSzam;$c++)$Beljebb.="&nbsp;&nbsp;";
                $Tartalom="
                      <tr>
                        <td class=adminmenu>$Beljebb<input onclick=\"if (this.checked)jelolt++; else jelolt--;\" type=checkbox name='ch".$this->AzonAd()."' value='checkbox' >".$this->NevAd()."</td>
                      </tr>  ";;

                return $Tartalom;
        }
                    

        function UjCsoport_fut()
        {
                $Obj=$this->UjObjektumLetrehoz("CAlHirlevelCsoport","CSOPORT");
                return $this->Futtat($Obj)->UrlapKi_fut();
        }
                        
                

                
 
        
        function Eligazit_pb_fut()
        {
                $HIRLEVEL_EMAIL=$this->Postgetv("HIRLEVEL_EMAIL");
                $Igazit=$this->Postgetv("Igazit");

                if (!isset($HIRLEVEL_EMAIL)||((isset($HIRLEVEL_EMAIL))&&($HIRLEVEL_EMAIL=="")))
                {
                        $this->ScriptUzenetAd("Az email mező nem lehet üres!");
                }else
                if ((isset($Igazit))&&($Igazit=="Le"))
                {
                        $Vissza=$this->Leiratkoz();
                }else
                {
                        $Vissza=$this->Felvesz();
                }
                return $this->Futtat(Focsop_azon)->Mutat_pb_fut();
                return $Vissza;
        }


        function Eligazitadmin_pb_fut()
        {
                $HIRLEVEL_EMAIL=$this->Postgetv("HIRLEVEL_EMAIL");
                $Igazit=$this->Postgetv("Igazit");

                if (!isset($HIRLEVEL_EMAIL)||((isset($HIRLEVEL_EMAIL))&&($HIRLEVEL_EMAIL=="")))
                {
                        $this->ScriptUzenetAd("Az email mező nem lehet üres!");
                }else
                if ((isset($Igazit))&&($Igazit=="Le"))
                {
                        $Vissza=$this->Leiratkoz();
                }else
                {
                        $Vissza=$this->Felvesz();
                }
                return $this->Futtat($this)->Lista_fut();
                return $Vissza;
        }
        
        function Felvesz()
        {
                $HIRLEVEL_EMAIL=$this->Postgetv("HIRLEVEL_EMAIL");

                if (!(CEmail::Ervenyes_email_mailer($HIRLEVEL_EMAIL)))
                {

                        $this->ScriptUzenetAd("Az e-mail cím formátuma nem megfelelő!");
                        return "";
                }

                

//                $Ered=$this->Futtat($this->Gyerekparam("EMAILCIM",null,1,null," and EMAIL_S='$HIRLEVEL_EMAIL'"))->AzonAd();
                $sql="select VZ_AZON_I from VAZ,EMAILCIM where VZ_TABLA_S='EMAILCIM' and VZ_TABLA_AZON_I=AZON_I and VZ_SZINKRONIZALT_I='1' and EMAIL_S=".self::$Sql->Konv($HIRLEVEL_EMAIL,"S");
                $Volt=self::$Sql->Lekerst($sql);

                if ($Volt)
                {
                        $this->ScriptUzenetAd("@@@Ilyen e-mail címmel már valaki felirakozott!§§§");
                }
                else
                {
                        $UjObjektum=$this->UjObjektumLetrehoz("CEmail","EMAILCIM");
                        $UjObjektum->Feliratkozas();
                        $this->ScriptUzenetAd("@@@Sikeresen feliratkozott!§§§");
                }
                

        }

        function Leiratkozkodol_fut()
        {
                $HIRLEVEL_EMAIL=$this->Postgetv("HIRLEVEL_EMAIL");
                $this->Leiratkoz(base64_decode($HIRLEVEL_EMAIL),1);
                return $this->Futtat(Focsop_azon)->Mutat_pb_fut();                
        }

        function Leiratkoz($HIRLEVEL_EMAIL="",$Kintrol=0)
        {
                if ($HIRLEVEL_EMAIL=="")$HIRLEVEL_EMAIL=$this->Postgetv("HIRLEVEL_EMAIL");

                $KERES[0]["ERTEK"]=$HIRLEVEL_EMAIL;
                $KERES[0]["MEZO"]="EMAIL";


                $sql="select VZ_AZON_I from VAZ,EMAILCIM where VZ_TABLA_S='EMAILCIM' and VZ_TABLA_AZON_I=AZON_I and VZ_SZINKRONIZALT_I='1' and EMAIL_S=".self::$Sql->Konv($HIRLEVEL_EMAIL,"S");
                $Volt=self::$Sql->Lekerst($sql);

//                $Ered=$this->Futtat($this->Gyerekparam("EMAILCIM",null,1,null," and EMAIL_S='$HIRLEVEL_EMAIL'"))->RekurzivTorol();
                if ($Volt)
                {
                        $this->Futtat($Volt[0]["VZ_AZON_I"])->RekurzivTorol();
                        $Uzenet="Sikeresen leiratkozott a hírlevélről!";
                        $this->ScriptUzenetAd($Uzenet);
                }else
                {
                        $Uzenet="Nem is volt feliratkozva a hírlevélre!";
                        $this->ScriptUzenetAd($Uzenet);
                }
/*
                if ($Kintrol)
                {
                         echo "<html>
<head>
<title>".TITLE."</title><body>
$Uzenet ";
                        exit;
                }
                else return $this->AlapFeladat();
                */
        }





}



class CAlHirlevelCsoport extends CHirlevelCsoport
{

		
        function Adat_adm_menu()
        {
            $Vissza=array();
            
            $ITEM["Nev"]="Címek mentése excelbe ";
            $ITEM["Link"]=$this->EsemenyHozzad("Csvbe_ment_fut");
            $Vissza[]=$ITEM;

            $ITEM["Nev"]="Címek betöltése excelből <br>pl.. <br>email cím<br>email cím";
            $ITEM["Link"]=$this->EsemenyHozzad("TermekfelviszUrlapKi_fut");
            $Vissza[]=$ITEM;
            return $Vissza;
             
        }  
        
       
        function Adminfelform()
        {

                $Form=new CForm("Torolform","","");
                $Form->Hidden("Esemeny_Uj",$this->EsemenyHozzad("Eligazitadmin_pb_fut"));
                $Form->Hidden("Igazit","");

                $Form->Textbox("Név:","HIRLEVEL_NEV","","");
                $Form->Textbox("E-mail cím:","HIRLEVEL_EMAIL","","");

                $Form->Szabad2(" ","<input type=submit value='E-mail cím felvétele' onclick=\"document.Torolform.Igazit.value='Fel';return true;\"> <input type=submit value='E-mail cím törlése' onclick=\"if (confirm('Biztos hogy törli?')){document.Torolform.Igazit.value='Le'}else return false;\">");
                $Tartalom=$Form->OsszeRak();
                return $Tartalom;
            
        }
        


        

       function TermekfelviszUrlapKi_fut()
        {
                $Visz=$this->EsemenyHozzad("Termekfelvisz");
                $this->ScriptTarol("
                        function Ellenor()
                        {
                                if (document.Felvitel.TERMEKFAJL.value=='')
                                {
                                        alert('A fájl neve mező nem lehet üres!');
                                }else return true;
                                return false;
                        }

                ");
                $Form=new CForm("Felvitel","","post");
                $Form->Hidden("Esemeny_Uj",$Visz);
                $Form->Allomanybe("Kérem a fájl nevét:","TERMEKFAJL","","");
                $Form->Szabad2(" ","<input type='submit' value='Feldolgoz' onclick=\"return Ellenor();\"> ");
                

                $Tartalom=$Form->OsszeRak();
                $Cim="Fájl felvitele";
   
                $Vissza["Tartalom"]=$Tartalom;
                $Vissza["Cim"]=$Cim;
                $Vissza["Vissza"]=$this->EsemenyHozzad("Lista");
                
                return $this->Sablonbe("Oldal_admin",$Vissza);
                
                

        }
                        
        function Termekfelvisz_fut()
        {
            
                $FAJL=$this->Filev("TERMEKFAJL","tmp_name");
                $TERMEKFAJLl_name=$this->Filev("TERMEKFAJL","name");

            ini_set ( "memory_limit", "322M");

            require_once "excel/Classes/PHPExcel.php";
            $Tartalom="";
            
                        $objPHPExcel = PHPExcel_IOFactory::load($FAJL);
                        $objReader = new PHPExcel_Reader_Excel2007();

                        $objWorksheet=$objPHPExcel->getActiveSheet();
                        $Frissul=0;
                        $Uj=0;
                        $highestRow = $objWorksheet->getHighestRow();
                        for ($row = 2; $row <= $highestRow; $row++)
                        {
//                                $NEV=$objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
                                $EMAIL=$objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
                                $EMAIL=trim($EMAIL);

                               

                                if (("$EMAIL"!=""))
                                {
                                    $sql="select VZ_AZON_I from VAZ,EMAILCIM where VZ_TABLA_S='EMAILCIM' and VZ_TABLA_AZON_I=AZON_I and VZ_SZINKRONIZALT_I='1' and EMAIL_S='$EMAIL' ";
                                    $Volt=self::$Sql->Lekerst($sql);
                                    if ($Volt)
                                    {
/*                                        $Obj=$this->ObjektumLetrehoz($Volt[0]["VZ_AZON_I"],0);
                                        $Obj->_Frissit_excbol($row,$objWorksheet);

                                        $Frissul++;
                                        */
                                        $Tartalom.="Már van ilyen email cím: $EMAIL<br>";
                                    }else
                                    {
                                        if (!(CEmail::Ervenyes_email_mailer($EMAIL)))
                                        {

                                            $Tartalom.="Az e-mail cím formátuma nem megfelelő: $EMAIL<br>";
                                        }else
                                        {                                        
                                            $Obj=$this->UjObjektumLetrehoz("CEmail","EMAILCIM");
                                            $Obj->_Frissit_excbol($row,$objWorksheet);

                                            $Uj++;
                                        }
                                    }
                                }else $Tartalom.="$row sor, azonosító nem lehet üres<br>";
    
                        }       
                $Tartalom.="$Uj felvéve";                             

                $Cim="Feltöltés erdeménye";
   
                $Vissza["Tartalom"]=$Tartalom;
                $Vissza["Cim"]=$Cim;
                $Vissza["Vissza"]=$this->EsemenyHozzad("Lista");
                
                return $this->Sablonbe("Oldal_admin",$Vissza);

        }
        
      function Csvbe_ment_fut()
        {

//ini_set ( "display_errors", E_ALL);
//ini_set ( "error_reporting", E_ALL);
ini_set ( "max_execution_time", "10060");

            ini_set ( "memory_limit", "640M");
            require_once "excel/Classes/PHPExcel.php";
            $objReader        = new PHPExcel_Reader_Excel5();
            $objexce    = $objReader->load('sablon/ures.xls');
            $objexce->setActiveSheetIndex(0);
            
            
            $Sor=1;            
//            $objexce->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0,$Sor,$this->unhtmlentities("Név"));            
            $objexce->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0,$Sor,$this->unhtmlentities("Email cím"));            
            

            $Felt=" and VZ_SZULO_I='".$this->AzonAd()."' ";
            $Ossz=self::$Sql->Lekerst("select count(VZ_AZON_I) as db from VAZ,EMAILCIM where VZ_TABLA_S='EMAILCIM' and VZ_TABLA_AZON_I=AZON_I and  VZ_SZINKRONIZALT_I='1' $Felt ");
            $Ossz=$Ossz[0]["db"];
            
            $KORBE=500;
            $Ittvan=0;
            $sor=2;
            while ($Ittvan<$Ossz)
            {
                
                $Gyerekek=self::$Sql->Lekerst("select VZ_AZON_I from VAZ,EMAILCIM where VZ_TABLA_S='EMAILCIM' and VZ_TABLA_AZON_I=AZON_I and VZ_SZINKRONIZALT_I='1'  $Felt order by VZ_AZON_I limit $Ittvan,$KORBE");
                $Ittvan=$Ittvan+$KORBE;
                if ($Gyerekek)
                {
                    foreach ($Gyerekek as $egy)
                    {
                        $Obj=$this->ObjektumLetrehoz($egy["VZ_AZON_I"],0);
                        $Obj->_Excelbement($objexce,$sor);
                        $sor++;
                    }
                }
            }

            

            $objWriter        = new PHPExcel_Writer_Excel5($objexce);
            $tmp="upload/".date("U").rand(1000,9000).rand(1000,9000).".xls";
            $objWriter->save($tmp);
            
            @ob_implicit_flush(true);
            //header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Type:   application/vnd.ms-excel; ');
            header('Content-Disposition: attachment;filename="emailcimek.xls"');
            header('Cache-Control: max-age=0');
            header('Content-Length: ' . filesize($tmp));

            ob_clean();
            flush();
            readfile($tmp);
    
            unlink($tmp);
             exit;
        

                        
        }    
        
        function Adatlist_adm_tag()
        {
            $Vissza["URLAP"]=1;    
            $Vissza["LISTA"]=1;
            
                
            $Vissza["TOROL"]=1;    
   
            return $Vissza;    
        }                  
}

class CStatAlHirlevelCsoport extends CAlHirlevelCsoport
{
        function Adatlist_adm_tag()
        {
            $Vissza["URLAP"]=1;    
            $Vissza["LISTA"]=1;    
   
            return $Vissza;    
        }
}

class CArchivcsopCsoport extends CCsoport
{

        function Adat_adm_menu()
        {
            $Vissza=array();
            
            return $Vissza;
             
        }  


    
        function Adatlist_adm_tag()
        {
            $Vissza["URLAP"]=1;    
            $Vissza["LISTA"]=1;    
   
            return $Vissza;    
        }
        
        function Hirlevelment($Dok)
        {
            $Obj=$this->UjObjektumLetrehoz("CMentHirlevDokumentum","DOKUMENTUM");
            $Obj->TablaAdatBe("NEV_S",$Dok->TablaAdatKi("NEV_S"));
            $Obj->TablaAdatBe("SZOVEG_S",$Dok->TablaAdatKi("SZOVEG_S"));
            $Obj->Szinkronizal();
            
        }
}




class CHirlevelDokumentum extends CStatikusDokumentum
{

        function Belemasol_fut($Obj)
        {
            $this->TablaAdatBe("NEV_S",$Obj->TablaAdatKi("NEV_S"));
            $this->TablaAdatBe("SZOVEG_S",$Obj->TablaAdatKi("SZOVEG_S"));
            $this->Szinkronizal();
            return $this->UrlapKi_fut();                
        }

        function Hirlevbeadat()
        {
                $Vissza["Szoveg"]=$this->SzovegCserel();
                $Vissza["Nev"]=$this->NevAd();
                $Vissza["Obj"]=$this;
                return $Vissza;
        }





        function Kellbevez()
        {
            return false;
        }

        function Kellkeres()
        {
            return false;
        }
        
        function Adatlist_adm_tag()
        {
            $Vissza["URLAP"]=1;    
            $Vissza["TEKINTUJ"]=1;    
            $Vissza["TEKINT"]=1;    
//            $Vissza["TEKINTUJ"]=1;

            return $Vissza;    
        }
        
        
        function Kellstatisztika()
        {
                return false;
        }

        function SzovegCserel()
        {
                $Vissza=parent::SzovegCserel();
                $Data["Targy"]=$this->NevAd();
                $Data["Szoveg"]=$Vissza;                
                
                return $this->Sablonbe("Hirlevelbe",$Data);
                
        }


        function Mutat_Alap()
        {
                $SZOVEG=$this->SzovegCserel();
                $SZOVEG=str_replace("<!--leiratk-->","<a class=link>Leiratkozás</a>",$SZOVEG);

                $SZOVEG.="<!--illeszt1-->";

                return $SZOVEG;
        }

        function Mutat_pb_fut()
        {
                $NEV=$this->Kozepennev();
                $SZOVEG=$this->SzovegCserel();

                $SZOVEG=str_replace("<!--leiratk-->","<a href='' >Leiratkozás</a>",$SZOVEG);
                return $SZOVEG;

        }
        

}

class CMentHirlevDokumentum extends CHirlevelDokumentum
{

        function Ujbolkuld_fut()
        {
            return $this->Futtat(Hirdok_azon)->Belemasol_fut($this);
        }
        
        function Adminnevad()
        {
            return $this->NevAd()." ".$this->Keszult();
        }

        function Adatlist_adm_tag()
        {
            $Vissza["URLAP"]=1;
            $Vissza["TEKINTUJ"]=1;    
            $Vissza["TEKINT"]=1;    

            $Vissza["EGYEB"][0]["Nev"]="Hírlevél kiküldése újból";
            $Vissza["EGYEB"][0]["Link"]=$this->EsemenyHozzad("Ujbolkuld_fut");
            $Vissza["EGYEB"][0]["Confirm"]=1;
            
            return $Vissza;    
        }
        
                
        
}

class CEmail extends CVaz_bovit
{

    var $Tabla_nev="EMAILCIM";


       public function Tablasql()
        {
            $SQL="
CREATE TABLE IF NOT EXISTS `EMAILCIM`(
  `AZON_I` int(13) NOT NULL auto_increment,
  `NEV_S` varchar(100) default '',
  `EMAIL_S` varchar(100) default '',
  PRIMARY KEY  (`AZON_I`)
) ENGINE=".MYSQL_ENGINE."


";
            return $SQL;    
        }  
        
        
      function _Excelbement(&$objexce,$Sor)
        {
            
//            $objexce->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0,$Sor,$this->unhtmlentities($this->TablaAdatKi("NEV_S")));            
            $objexce->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0,$Sor,$this->unhtmlentities($this->TablaAdatKi("EMAIL_S")));

        }
        
       function _Frissit_excbol($row,$objWorksheet)
        {   
                
            
//                $this->TablaAdatBe("NEV_S",$objWorksheet->getCellByColumnAndRow(0, $row)->getValue());
                $this->TablaAdatBe("EMAIL_S",trim($objWorksheet->getCellByColumnAndRow(0, $row)->getValue()));

               
                $this->Szinkronizal();
        }        

        function EgyezikEmail($Mivel)
        {
                if ($this->TablaAdatKi("EMAIL")==$Mivel)
                {
                        return 1;
                }
                else
                {
                        return 0;
                }
        }
        function Kellstatisztika()
        {
                return false;
        }

        function Feliratkozas()
        {
                $HIRLEVEL_EMAIL=$this->Postgetv("HIRLEVEL_EMAIL");
                $HIRLEVEL_NEV=$this->Postgetv("HIRLEVEL_NEV");

                $this->TablaAdatBe("EMAIL_S",strip_tags($HIRLEVEL_EMAIL));
                $this->TablaAdatBe("NEV_S",strip_tags($HIRLEVEL_NEV));
                $this->Szinkronizal();
        }

        function FeliratkozasSzovegNelkul()
        {
                $HIRLEVEL_EMAIL=Postgetvaltozo("HIRLEVEL_EMAIL");

                $this->TablaAdatBe("EMAIL",$HIRLEVEL_EMAIL);
                $this->TablaSzinkronizal();
        }

        function SzerkesztoSorBeallit()
        {
                $this->Szerkeszto[0]=1;
        }

        function NevAd()
        {
                $Vissza=$this->TablaAdatKi("NEV_S");
                if ($Vissza!="")$Vissza.=" ";
                $Vissza.=$this->TablaAdatKi("EMAIL_S");
                return $Vissza;
        }

        function Ervenyes_mail()
        {
                $Vissza=true;
                $HIRLEVEL_EMAIL=$this->TablaAdatKi("EMAIL");
                return $this->Ervenyes_email_mailer($HIRLEVEL_EMAIL);
                if ((trim($HIRLEVEL_EMAIL)=="")||(strpos($HIRLEVEL_EMAIL,"@")===false)||(strpos($HIRLEVEL_EMAIL,".")===false))
                {
                        $Vissza=false;
                }
                $Ervtelen="#íÍűŰáÁéÉúÚőŐóÓüÜöÖ$ §'";
                $Ervtelen.='"';
                for($c=0;$c<strlen($Ervtelen);$c++)
                {
                        $kar=substr($Ervtelen,$c,1);
                        $Van=strpos($HIRLEVEL_EMAIL,$kar);
                        if ($Van===false)
                        {
                        }else $Vissza=false;
                }
                return $Vissza;
        }


        function Adatlist_adm_tag()
        {
            $Vissza["TOROL"]=1;    
            return $Vissza;    
        }  
        
        
  function Ervenyes_email_mailer($address) {
    if (function_exists('filter_var')) { //Introduced in PHP 5.2
      if(filter_var($address, FILTER_VALIDATE_EMAIL) === FALSE) {
        return false;
      } else {
        return true;
      }
    } else {
      return preg_match('/^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!\.)){0,61}[a-zA-Z0-9_-]?\.)+[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!$)){0,61}[a-zA-Z0-9_]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/', $address);
    }
  }


        function EmailKuld($Targy,$Uzenet,$IratkLink="")
        {
                $KAPCSOLAT_EMAIL=$this->TablaAdatKi("EMAIL_S");

//                $EMAIL=$this->NevAd();
/*                $Uzenet=$Uzenet."<br><br><a href='".OLDALCIM."'>".OLDALCIM."</a>";*/
                $Leiratkoz="
                <a href='".OLDALCIM."$IratkLink?HIRLEVEL_EMAIL=".base64_encode($KAPCSOLAT_EMAIL)."' target='_blank' class=link>Leiratkozás a hírlevélről</a>
                ";
                $Uzenet=str_replace("<!--leiratk-->",$Leiratkoz,$Uzenet);

               
                $this->Mailkuld($Targy,$Uzenet,$KAPCSOLAT_EMAIL,$this->TablaAdatKi("NEV_S"));

        }


}


?>
