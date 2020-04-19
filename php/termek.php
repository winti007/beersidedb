<?php





function array_to_xml_gen($Tomb,$Mainname) {

    $xml_student_info = new ExSimpleXMLElement("<?xml version=\"1.0\" encoding='UTF-8'  ?><".$Mainname."></".$Mainname.">");

// function call to convert array to xml
    array_to_xml($Tomb,$xml_student_info);

//saving generated xml file
    $d=$xml_student_info->asXML();
    return $d;
}
    
function array_to_xml($student_info, &$xml_student_info) {
    foreach($student_info as $key => $value) {
        if(is_array($value)) {
            if(!is_numeric($key)){
                $subnode = $xml_student_info->addChild("$key");
                array_to_xml($value, $subnode);
            }
            else{
                array_to_xml($value, $xml_student_info);
            }
        }
        else {
            $Siman=false;
            if(is_numeric($value))$Siman=true;           
            if("$value"=="true")$Siman=true;           
            if("$value"=="false")$Siman=true;
            if ($Siman)$xml_student_info->addChild("$key","$value");           
                  else $xml_student_info->addChildCData("$key","$value");
        }
    }
}


class ExSimpleXMLElement extends SimpleXMLElement
{
    /**
     * Add CDATA text in a node
     * @param string $cdata_text The CDATA value  to add
     */
  private function addCData($cdata_text)
  {
   $node= dom_import_simplexml($this);
   $no = $node->ownerDocument;
   $node->appendChild($no->createCDATASection($cdata_text));
  }

  /**
   * Create a child with CDATA value
   * @param string $name The name of the child element to add.
   * @param string $cdata_text The CDATA value of the child element.
   */
    public function addChildCData($name,$cdata_text)
    {
        $child = $this->addChild($name);
        $child->addCData($cdata_text);
    }

    /**
     * Add SimpleXMLElement code into a SimpleXMLElement
     * @param SimpleXMLElement $append
     */
    public function appendXML($append)
    {
        if ($append) {
            if (strlen(trim((string) $append))==0) {
                $xml = $this->addChild($append->getName());
                foreach($append->children() as $child) {
                    $xml->appendXML($child);
                }
            } else {
                $xml = $this->addChild($append->getName(), (string) $append);
            }
            foreach($append->attributes() as $n => $v) {
                $xml->addAttribute($n, $v);
            }
        }
    }
} 


define ("TERMEKEGYOLDALON", "1300");


class CFoFozdeCsoport extends CCsoport
{
        function Adatlist_adm_tag()
        {
            $Vissza["URLAP"]=1;    
            $Vissza["LISTA"]=1;
            
            return $Vissza;    
        }
        
        function Lista_fut()
        {
            $Nyil=0;
            $Nyil=1;
            
            $Data["Lista"]=$this->Futtat($this->Gyerekparam("CSOPORT!DOKUMENTUM","VZ_SORREND_I",1,null,"",$Nyil))->Adatlist_adm();
            
            $Data=array_merge($Data,$this->Adat_adm());
            $Vissza["Tartalom"]=$this->Sablonbe("Lista",$Data);
                    
            $Vissza["Cim"]=$this->AdminNevAd();
            return $this->Sablonbe("Oldal_admin",$Vissza);
        }
                
        
        function Amerika_azonok()
        {
            $Vissza=array();
            $Ered=$this->Futtatgy("CSOPORT",null,null,null," and AMERIKA_I='1' ")->AzonAd();
            foreach ($Ered["Eredm"] as $egy)
            {
                $Vissza[]=$egy;     
            }
            return $Vissza;
        }
        
        function Komboba($Plusz=false)
        {
            $Vissza[]=array("0"=>"@@@Főzde választó§§§","1"=>"0");
            if (is_array($Plusz))$Vissza=array_merge($Vissza,$Plusz);
            
  //          if (is_array($Plusz))$Vissza[]=$Plusz;
//            if (is_array($Plus2))$Vissza[]=$Plus2;


            $Ered=$this->Futtatgy("CSOPORT",null,null,null,"")->Adatlistkozep_publ(false);
            foreach ($Ered["Eredm"] as $egy)
            {
                $Vissza[]=array("0"=>$egy["Nev"],"1"=>$egy["Azon"]);     
            }
            return $Vissza;

        }        

        function Adat_adm_menu()
        {
            $Vissza=array();

            $ITEM["Nev"]="Új főzde";
            $ITEM["Link"]=$this->EsemenyHozzad("UjCsoport_fut");
            $Vissza[]=$ITEM;
            return $Vissza;
             
        }
        
        function UjCsoport_fut()
        {
                $Obj=$this->UjObjektumLetrehoz("CFozdeCsoport","CSOPORT");
                return $this->Futtat($Obj)->UrlapKi_fut();
                
        }
                
        function Tobbnyelvu()
        {
            return true;
        }            
}


class CFozdeCsoport extends CFoFozdeCsoport
{
        function Adatlist_adm_tag()
        {
            $Vissza["URLAP"]=1;   
            
            $Vissza["TOROL"]=1;
            $Vissza["TEKINT"]=1;
            return $Vissza;    
        }
        
        function Urlapplusz(&$Form)
        {
            $Form->Checkbox("Amerikai:","AMERIKA_I",$this->TablaAdatKi("AMERIKA_I"),"");

        }

        function Urlapplusz_tarol()
        {
            $this->TablaAdatBe("AMERIKA_I",$this->Postgetv("AMERIKA_I",1));            
        }

        function Mutat_pb_fut()
        {
            $Term=$this->ObjektumLetrehoz(Webshop_azon,0);
            $Term->Fozdebe($this);
            return $Term->Mutat_pb_fut();
        }
                    
}


class CWebAruhazCsoport extends CCsoport
{

        function Fotermcsop()
        {
            return true;
        }
           


        function Tobbnyelvu()
        {
            return true;
        }
                    
        function Termekek($Termobjhoz)
        {
            $Vissza=array();
            $Azon=self::$Sql->Lekerst("select VZ_AZON_I from VAZ inner join TERMEK on VAZ.VZ_TABLA_S='TERMEK' and VZ_TABLA_AZON_I=TERMEK.AZON_I where VZ_SZINKRONIZALT_I='1' and VZ_MASOLAT_I='0'  order by NEV_S ");
            if ($Azon)
            {
                foreach ($Azon as $egy)
                {
                    $Vissza[]=$this->Futtat($egy["VZ_AZON_I"])->Masolatallit($Termobjhoz);
                }
            }

            
            return $Vissza;
        }
        
    
        function Xml_general_fut()
        {
            ini_set ( "memory_limit", "118M");
            
            $GLOBALS["Nemkelltrans"]=1;
            
            $TOMB=array();
            $Honnan=0;
            $Mennyi=100;
            do
            {
                $Azon=self::$Sql->Lekerst("select VZ_AZON_I from VAZ inner join TERMEK on VAZ.VZ_TABLA_S='TERMEK' and VZ_TABLA_AZON_I=TERMEK.AZON_I where VZ_SZINKRONIZALT_I='1' and AKTIV_I='1' and VZ_MASOLAT_I='0' order by AZON_I limit $Honnan,$Mennyi");
                if($Azon)
                {
                    foreach ($Azon as $egy)
                    {
                        $Obj=$this->ObjektumLetrehoz($egy["VZ_AZON_I"],0);
                        
                        $Obj->Xmlbe($TOMB);
                        
                    }
                    $Mehet=true;
                }else
                {
                    $Mehet=false;
                }
                $Honnan=$Honnan+$Mennyi;
                
            }while ($Mehet);
            
            $Ital=$TOMB["Ital"];
            
            $Ital_xml=array_to_xml_gen($Ital,"products");
            header("Content-type: text/xml");
            echo $Ital_xml;
            exit;
            
        }
        
      function Nyitolapra()
        {
            $Nyelv=$this->Nyelvadpub();           
            $Kiemelt=array();
            
            $Most=date("Y-m-d H:i:s");
            $Felt=" and MOSTERK_DAT_D>='$Most' ";
//            and MOSTERK_I='1'
            $Azon=self::$Sql->Lekerst("select VZ_AZON_I from VAZ inner join TERMEK on VAZ.VZ_TABLA_S='TERMEK' and VZ_TABLA_AZON_I=TERMEK.AZON_I where VZ_SZINKRONIZALT_I='1' $Felt  and AKTIV_I='1' and VZ_MASOLAT_I='0' order by AZON_I desc");
            if ($Azon)
            {
                foreach ($Azon as $egy)
                {
                    $Kiemelt[]=$this->Futtat($egy["VZ_AZON_I"])->Adatlistkozep_publ(true);
                }
            }
            
            $Param["Termek"]=$Kiemelt;
            $Param["Slider"]=1;
            
            $Vissza=$this->Sablonbe("Termekmutat",$Param);
            
            return $Vissza;
        }

        function Ajanloba()
        {
            
            $Kiemelt=array();
            $Azon=self::$Sql->Lekerst("select VZ_AZON_I from VAZ inner join TERMEK on VAZ.VZ_TABLA_S='TERMEK' and VZ_TABLA_AZON_I=TERMEK.AZON_I where VZ_SZINKRONIZALT_I='1' and AJANLO_I='1' and AKTIV_I='1' and VZ_MASOLAT_I='0' order by rand() limit 0,3");
            if ($Azon)
            {
                foreach ($Azon as $egy)
                {
                    $Kiemelt[]=$this->Futtat($egy["VZ_AZON_I"])->Adatlistkozep_publ(true);
                }
            }
            
            $Param["Termek"]=$Kiemelt;
            $Param["Cim"]="Termék ajánlatunk";
            $Vissza=$this->Sablonbe("Termekmutat2",$Param);
            
            return $Vissza;
        }
        
        function Kedvenc_pb_fut()
        {

            $Honnan=0;           
            
            $Mennyi=9999;
            $Limit=" limit ".$Honnan.",".$Mennyi;
            $Data=$this->SessAd("Aktfelh")->Kedvencek($Limit);
            $Param["Szuronem"]=1;
            
             $Param["Menuszuro"]="";               
                
                
                $Param["Termek"]=$Data["Tetel"];
                
               // $Param["Data"]=$this->Adatlistkozep_publ(false);
                $Param["Pager"]="";
                
                $Vissza["Tartalom"]=$this->Sablonbe("Mutatkozep",$Param);
            
                            
                $Vissza["Cim"]="@@@Kedvencek§§§ ";
               // $Vissza["Vissza"]=$this->VisszaEsemenyAd();
                

//                $Ered=$this->Futtat($this->Gyerekparam("CSOPORT",$this->Rendezsql(),1,null," and AKTIV_I='1' ".$this->Hozzafersql()))->Adatlistkozep_publ(false);
                

            return $this->Sablonbe("Oldal",$Vissza);

  
        }
        
                
        function Kereses_pb_fut()
        {
            $Mitkeres=$this->Postgetv_jegyez("Mitkeres",0);
            $Honnan=$this->Postgetv_jegyez("Honnan",0);
            if ($Mitkeres=="0")$Mitkeres="";
            $Nyelv=$this->Nyelvadpub();
 
            $Felt=" and (NEV_".$Nyelv."_S like ".self::$Sql->Konv("%".$Mitkeres."%","S")." or TIPUS_".$Nyelv."_S like ".self::$Sql->Konv("%".$Mitkeres."%","S")." or LEIRAS_".$Nyelv."_S like ".self::$Sql->Konv("%".$Mitkeres."%","S")." )";
                

                $Param["Menuszuro"]=$this->Szuro();               
                
                $Termek=$this->Termekekad($Param["Menuszuro"]["Felt"].$Felt);
                
                $Param["Termek"]=$Termek["Termek"];
                
                $Param["Data"]=$this->Adatlistkozep_publ(false);
                $Param["Pager"]=$Termek["Pager"];
                $Param["Ujralink2"]=$this->EsemenyHozzad("Kereses_pb_fut");
                $Param["Ujralink"]=$Param["Ujralink2"]."?Honnan=0";
                
                $Vissza["Tartalom"]=$this->SzovegCserel();
                
                $Vissza["Tartalom"].=$this->Sablonbe("Mutatkozep",$Param);
                $Vissza["Tartalom"].=$Termek["Pager"];
                
                            
                $Vissza["Cim"]="@@@Keresés eredménye§§§ $Mitkeres";
               // $Vissza["Vissza"]=$this->VisszaEsemenyAd();
                

//                $Ered=$this->Futtat($this->Gyerekparam("CSOPORT",$this->Rendezsql(),1,null," and AKTIV_I='1' ".$this->Hozzafersql()))->Adatlistkozep_publ(false);
                

            return $this->Sablonbe("Oldal",$Vissza);

//            return $this->Sablonbe("Oldal_termek",$Vissza);
        }
        
        function Termek_karba2_pb_fut()
        {
            ini_set ( "memory_limit", "140M");
            ini_set ( "max_execution_time", "12060");
ini_set ( "display_errors", E_ALL);
ini_set ( "error_reporting", E_ALL);

            require_once "excel/Classes/PHPExcel.php";

            $Tartalom="";
            $FAJL="feltolt2.xls";
            
                        $objPHPExcel = PHPExcel_IOFactory::load($FAJL);
                        $objReader = new PHPExcel_Reader_Excel2007();

                        $objWorksheet=$objPHPExcel->getActiveSheet();
                        $Frissul=0;
                        $Uj=0;
                        $highestRow = $objWorksheet->getHighestRow();
                        for ($row = 2; $row <= $highestRow; $row++)
                        {
                                $KULSO_AZON_S=$objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
                                
                                

                                if (("$KULSO_AZON_S"!="")&&($KULSO_AZON_S!="0"))
                                {
                                    $sql="select VZ_AZON_I from VAZ,TERMEK where VZ_TABLA_S='TERMEK' and VZ_TABLA_AZON_I=AZON_I and VZ_SZINKRONIZALT_I='1' and VZ_AZON_I='$KULSO_AZON_S' ";
                                    $Volt=self::$Sql->Lekerst($sql);
                                    if ($Volt)
                                    {
                                        $Obj=$this->ObjektumLetrehoz($Volt[0]["VZ_AZON_I"],0);
                                        $Frissul++;

                                        $Obj->_Frissit_excbol2($row,$objWorksheet);
                                    
                                    }
                                }else $Tartalom.="$row sor, azonosító nem lehet üres<br>";
    
                        }       
                $Tartalom.="$Uj új felvéve, $Frissul db frissült";                             

                $Cim="Feltöltés erdeménye";
                
                if (isset($GLOBALS["KEPUZEN"]))$Tartalom.="<hr>".$GLOBALS["KEPUZEN"]."<hr>";
                
   
                $Vissza["Tartalom"]=$Tartalom;
                $Vissza["Cim"]=$Cim;
                $Vissza["Vissza"]=$this->EsemenyHozzad("Lista");
                
                return $this->Sablonbe("Oldal",$Vissza);

            
         }
        
                
        function Termek_karba_pb_fut()
        {
            ini_set ( "memory_limit", "140M");
            ini_set ( "max_execution_time", "12060");

            require_once "excel/Classes/PHPExcel.php";

            $Tartalom="";
            $FAJL="feltolt.xlsx";
            
                        $objPHPExcel = PHPExcel_IOFactory::load($FAJL);
                        $objReader = new PHPExcel_Reader_Excel2007();

                        $objWorksheet=$objPHPExcel->getActiveSheet();
                        $Frissul=0;
                        $Uj=0;
                        $highestRow = $objWorksheet->getHighestRow();
                        for ($row = 2; $row <= $highestRow; $row++)
                        {
                                $KULSO_AZON_S=$objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
                                $NEV=trim($objWorksheet->getCellByColumnAndRow(2, $row)->getValue());
                                

                                if (("$KULSO_AZON_S"!="")&&($NEV!=""))
                                {
                                    $sql="select VZ_AZON_I from VAZ,TERMEK where VZ_TABLA_S='TERMEK' and VZ_TABLA_AZON_I=AZON_I and VZ_SZINKRONIZALT_I='1' and KULSO_AZON_S='$KULSO_AZON_S' ";
                                    $Volt=self::$Sql->Lekerst($sql);
                                    if ($Volt)
                                    {
                                        $Obj=$this->ObjektumLetrehoz($Volt[0]["VZ_AZON_I"],0);
                                        $Frissul++;
                                    }else
                                    {
                                        $Obj=$this->UjObjektumLetrehoz("CTermek","TERMEK");

                                        
                                        $Uj++;
                                    }
                                    if (is_object($Obj))
                                    {
                                        $Obj->_Frissit_excbol($row,$objWorksheet);
                                    }
                                }else $Tartalom.="$row sor, azonosító nem lehet üres<br>";
    
                        }       
                $Tartalom.="$Uj új felvéve, $Frissul db frissült";                             

                $Cim="Feltöltés erdeménye";
                
                if (isset($GLOBALS["KEPUZEN"]))$Tartalom.="<hr>".$GLOBALS["KEPUZEN"]."<hr>";
                
   
                $Vissza["Tartalom"]=$Tartalom;
                $Vissza["Cim"]=$Cim;
                $Vissza["Vissza"]=$this->EsemenyHozzad("Lista");
                
                return $this->Sablonbe("Oldal",$Vissza);

            
         }
        
        
     function Csvbe_ment_fut()
        {

//ini_set ( "display_errors", E_ALL);
//ini_set ( "error_reporting", E_ALL);
ini_set ( "max_execution_time", "10060");

            ini_set ( "memory_limit", "640M");
            require_once "excel/Classes/PHPExcel.php";
            $objReader        = new PHPExcel_Reader_Excel5();
            $objexce    = $objReader->load('sablon/termek.xls');
            $objexce->setActiveSheetIndex(0);
            
            $Sor=1;            
            $objexce->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0,$Sor,$this->unhtmlentities("Web azonosító (nem szerkeszthető)"));            
            $objexce->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1,$Sor,$this->unhtmlentities("Külső azonosító (nem szerkeszthető)"));            
            $objexce->setActiveSheetIndex(0)->setCellValueByColumnAndRow(2,$Sor,$this->unhtmlentities("Főzde"));            
            
            $objexce->setActiveSheetIndex(0)->setCellValueByColumnAndRow(3,$Sor,$this->unhtmlentities("Név magyar"));            
            $objexce->setActiveSheetIndex(0)->setCellValueByColumnAndRow(4,$Sor,$this->unhtmlentities("Név angol"));
            $objexce->setActiveSheetIndex(0)->setCellValueByColumnAndRow(5,$Sor,$this->unhtmlentities("Cikkszám"));            
            $objexce->setActiveSheetIndex(0)->setCellValueByColumnAndRow(6,$Sor,$this->unhtmlentities("Ean kód"));
                        
            $objexce->setActiveSheetIndex(0)->setCellValueByColumnAndRow(7,$Sor,$this->unhtmlentities("Kiszerelés"));            
            $objexce->setActiveSheetIndex(0)->setCellValueByColumnAndRow(8,$Sor,$this->unhtmlentities("Alkohol tartalom"));            
            $objexce->setActiveSheetIndex(0)->setCellValueByColumnAndRow(9,$Sor,$this->unhtmlentities("Készlet"));            
            $objexce->setActiveSheetIndex(0)->setCellValueByColumnAndRow(10,$Sor,$this->unhtmlentities("Típus magyar"));            
            $objexce->setActiveSheetIndex(0)->setCellValueByColumnAndRow(11,$Sor,$this->unhtmlentities("Típus angol"));            
            $objexce->setActiveSheetIndex(0)->setCellValueByColumnAndRow(12,$Sor,$this->unhtmlentities("Ár"));            
            $objexce->setActiveSheetIndex(0)->setCellValueByColumnAndRow(13,$Sor,$this->unhtmlentities("Lejárat"));            
            $objexce->setActiveSheetIndex(0)->setCellValueByColumnAndRow(14,$Sor,$this->unhtmlentities("Leírás magyar"));            
            $objexce->setActiveSheetIndex(0)->setCellValueByColumnAndRow(15,$Sor,$this->unhtmlentities("Leírás angol"));            
            $objexce->setActiveSheetIndex(0)->setCellValueByColumnAndRow(16,$Sor,$this->unhtmlentities("Aktív (0 vagy 1)"));            


            
            $KORBE=500;
            $Ittvan=0;
            $sor=2;
            
            do
            {
                
                $Gyerekek=self::$Sql->Lekerst("select VZ_AZON_I from VAZ,TERMEK where VZ_TABLA_S='TERMEK' and VZ_TABLA_AZON_I=AZON_I and VZ_SZINKRONIZALT_I='1'  order by VZ_AZON_I limit $Ittvan,$KORBE");
                $Ittvan=$Ittvan+$KORBE;
                if ($Gyerekek)
                {
                    $Mehet=true;
                    foreach ($Gyerekek as $egy)
                    {
                        $Obj=$this->ObjektumLetrehoz($egy["VZ_AZON_I"],0);
                        $Obj->_Excelbement($objexce,$sor);
                        $sor++;
                    }
                }else $Mehet=false;
            }
            while ($Mehet);

            

            $objWriter        = new PHPExcel_Writer_Excel5($objexce);
            $tmp="upload/".date("U").rand(1000,9000).rand(1000,9000).".xls";
            $objWriter->save($tmp);
            
            @ob_implicit_flush(true);
            //header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Type:   application/vnd.ms-excel; ');
            header('Content-Disposition: attachment;filename="termekek.xls"');
            header('Cache-Control: max-age=0');
            header('Content-Length: ' . filesize($tmp));

            ob_clean();
            flush();
            readfile($tmp);
    
            unlink($tmp);
             exit;
        

                        
        }   
                
        function Xmlfeldolgoz($Xmlobj)
        {
            $ind=0;
            $Tolfr=4000;
            $Igfr=6000;
            if ($Xmlobj->product)
            {
                foreach ($Xmlobj->product as $item)
                {
                    $code=$item->code;
                    $group=$item->group;
                    $subgroup=$item->subgroup;
                    
                    $Frissulhet=false;
                    if (($Tolfr<=$ind)&&($Igfr>=$ind))$Frissulhet=true;
                    
    //                $Frissulhet=false;
  //                  if ($code=="170353")$Frissulhet=true;
//                    echo "code: $code ind $ind<br>";
                    

                    if (($code!="")&&($Frissulhet))
                    {
                        $CSOPNEVEK[0]=$group;
                        $CSOPNEVEK[1]=$subgroup;
                        $Csopba=$this->Csoportcsinal($Hibauzen,0,$CSOPNEVEK,true);
                        


                        if (is_object($Csopba))
                        {
                            $Felt="and CODE_S='".$code."' ";
                            $sql="select VZ_AZON_I,VZ_SZULO_I from VAZ,TERMEK where VZ_TABLA_S='TERMEK' and VZ_TABLA_AZON_I=AZON_I and VZ_SZINKRONIZALT_I='1' $Felt  ";
                            $Azon=self::$Sql->Lekerst($sql);
                            if ($Azon)
                            {
                                  $Obj1=$this->ObjektumLetrehoz($Azon[0]["VZ_AZON_I"],0);
                                  if ($Csopba->AzonAd()!=$Azon[0]["VZ_SZULO_I"])
                                  {
                                    $Obj1->Athelyez($Csopba);  
                                  }
                            }else
                            {
                                $Obj1=$Csopba->UjObjektumLetrehoz("CTermek","TERMEK");    
                            }
                            
                            
                            $Obj1->Adatokfrissit($item);
                            
                            
                        }
                        
                        
                      }
                      $ind++;


                }
            }
            
            
         //Adatokfrissit
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
                        if ($Ujatis)
                        {
                        
                                $CSOP=$this->UjObjektumLetrehoz("CAlWebAruhazCsoport","CSOPORT");
                                $CSOP->TablaAdatBe("NEV_S",$CSOPNEVEK[$hanyas]);
                                $CSOP->Szinkronizal();

                                $CSOP->Defkulsolink();

                        }else
                        {
                                $Hibauzen=$CSOPNEVEK[$hanyas];
                                return false;
                        }


                }
                $hanyas++;
                if (isset($CSOPNEVEK[$hanyas]))$CSOPNEVEK[$hanyas]=trim($CSOPNEVEK[$hanyas]);
                
                if (isset($CSOPNEVEK[$hanyas])&&($CSOPNEVEK[$hanyas]!=""))$Vissza=$CSOP->Csoportcsinal($Hibauzen,$hanyas,$CSOPNEVEK,$Ujatis);
                        else $Vissza=$CSOP;
                return $Vissza;


        }  



       function Termekekad($Felt2="")
       {
            $Vissza=array();
            $Honnan=$this->Postgetv_jegyez("Honnan",0);
            
            $Rendez=$this->Postgetv_jegyez("Rendez",0);
            $Nyelv=$this->Nyelvadpub();
            $Rendsql="NEV_".$Nyelv."_S ";
            $Rendsql="AZON_I desc ";
            $Felt="and VZ_SZULO_I='".$this->_EredetiAzon()."' and AKTIV_I='1' ".$Felt2;
            $sql="select VZ_AZON_I from VAZ,TERMEK where VZ_TABLA_S='TERMEK' and VZ_TABLA_AZON_I=AZON_I and VZ_SZINKRONIZALT_I='1' $Felt  order by $Rendsql limit $Honnan, ".TERMEKEGYOLDALON;
            
            $Azon=self::$Sql->Lekerst($sql);
            
            if ($Azon)
            {
                foreach ($Azon as $item)
                {
                    $Vissza[]=$this->Futtat($item["VZ_AZON_I"])->Adatlistkozep_publ();
                }
            }

            $sql="select count(VZ_AZON_I) as db from VAZ,TERMEK where VZ_TABLA_S='TERMEK' and VZ_TABLA_AZON_I=AZON_I and VZ_SZINKRONIZALT_I='1' $Felt  ";
            $Ossz=self::$Sql->Lekerst($sql);
            $Ossz=$Ossz[0]["db"];
        
//                $Ered=$this->Futtat($this->Gyerekparam("TERMEK","VZ_SORREND_I",1,null," and AKTIV_I='1'"))->Adatlistkozep_publ();
  //              $Vissza=$Ered["Eredm"];
            
            $VTomb["Termek"]=$Vissza;    
    
            $VTomb["Pager"]=$this->Tordeles("Honnan",$Honnan,$Ossz,TERMEKEGYOLDALON);    
            return $VTomb;

            return $Vissza;
       }

        function Fozdebe($Fozobj)
        {
            $AZON=$Fozobj->AzonAd();
            if ($AZON==Amerika_azon)$AZON=1;
            $this->AllapotBe("K_FOZDE",$AZON);    
        }

        function Szuro($Admin=0)
        {
            $Felt="";
            $Nyelv=$this->Nyelvadpub();
            $K_TIPUS=$this->Postgetv_jegyez("K_TIPUS",0);
            if ("$K_TIPUS"=="0")$K_TIPUS="";

            $K_FOZDE=$this->Postgetv_jegyez("K_FOZDE",0);
            if ("$K_FOZDE"=="0")$K_FOZDE="";

            $K_KISZER=$this->Postgetv_jegyez("K_KISZER",0);
            if ("$K_KISZER"=="0")$K_KISZER="";

            $K_ALKOHOL=$this->Postgetv_jegyez("K_ALKOHOL",0);
            if ("$K_ALKOHOL"=="0")$K_ALKOHOL="";
            
            if ($Admin)
            {
                $LEJARAT_TOL=$this->Postgetv_jegyez("LEJARAT_TOL",0);
                if ("$LEJARAT_TOL"=="0")$LEJARAT_TOL="";

                $LEJARAT_IG=$this->Postgetv_jegyez("LEJARAT_IG",0);
                if ("$LEJARAT_IG"=="0")$LEJARAT_IG="";

                $KESZL_TOL=$this->Postgetv_jegyez("KESZL_TOL",0);
                if ("$KESZL_TOL"=="0")$KESZL_TOL="";

                $KESZL_IG=$this->Postgetv_jegyez("KESZL_IG",999999);
                if ("$KESZL_IG"=="0")$KESZL_IG="";
                
            }



            
            if (("$K_TIPUS"!="")&&("$K_TIPUS"!="0"))$Felt.=" and TIPUS_".$Nyelv."_S='$K_TIPUS' ";
            if (("$K_FOZDE"!="")&&("$K_FOZDE"!="0"))
            {
                
                if ("$K_FOZDE"=="3")
                {
                    $Felt.=" and SZAZALEK_F>0 ";    
                }else
                if ("$K_FOZDE"=="2")
                {
                    $Felt.=" and FOZDE_VZ_AZON_I='0' ";    
                }else
                if ("$K_FOZDE"=="1")
                {
                    $TFelt="";
                    $Azonok=$this->Futtat(Fozde_azon)->Amerika_azonok();
                    if ($Azonok)
                    {
                        foreach ($Azonok as $egy)
                        {
                            if ($TFelt!="")$TFelt.=" or ";
                            $TFelt.=" FOZDE_VZ_AZON_I='$egy'  ";
                        }
                    }
                    if ($TFelt!="")$Felt.=" and ($TFelt) ";
                    else $Felt.=" and '1'='2' ";
                }
                else $Felt.=" and FOZDE_VZ_AZON_I='$K_FOZDE' ";
                
            }
            if ($Admin)
            {
                if ($LEJARAT_TOL!="")$Felt.=" and  LEJARAT_D>='$LEJARAT_TOL' ";
                if ($LEJARAT_IG!="")$Felt.=" and  LEJARAT_D<='$LEJARAT_IG' ";
                
                if ("$KESZL_TOL"!="")$Felt.=" and KESZLET_I>='$KESZL_TOL' ";
                if ("$KESZL_IG"!="")$Felt.=" and KESZLET_I<='$KESZL_IG' ";
            }

            
            if (("$K_KISZER"!="")&&("$K_KISZER"!="0"))$Felt.=" and KISZERELES_F='$K_KISZER' ";
            if (("$K_ALKOHOL"!="")&&("$K_ALKOHOL"!="0"))
            {
                if ("$K_ALKOHOL"=="1")$Felt.=" and ALKOHOL_F>='0' and ALKOHOL_F<'4'";    
                if ("$K_ALKOHOL"=="2")$Felt.=" and ALKOHOL_F>='4' and ALKOHOL_F<'4.7'";    
                if ("$K_ALKOHOL"=="3")$Felt.=" and ALKOHOL_F>='4.7' and ALKOHOL_F<'5.5'";    
                if ("$K_ALKOHOL"=="4")$Felt.=" and ALKOHOL_F>='5.5' and ALKOHOL_F<'6.4'";    
                if ("$K_ALKOHOL"=="5")$Felt.=" and ALKOHOL_F>='6.4' and ALKOHOL_F<'18'";    
            }
    
            $Vissza["Admin"]=$Admin;
            if ($Admin)
            {
                $Vissza["LEJARAT_TOL"]=$LEJARAT_TOL;
                $Vissza["LEJARAT_IG"]=$LEJARAT_IG;
                $Vissza["KESZL_TOL"]=$KESZL_TOL;
                $Vissza["KESZL_IG"]=$KESZL_IG;
            }

            $Vissza["K_TIPUS"]=$K_TIPUS;
            $Vissza["K_FOZDE"]=$K_FOZDE;
            $Vissza["K_KISZER"]=$K_KISZER;
            $Vissza["K_ALKOHOL"]=$K_ALKOHOL;
            $Vissza["K_TIPUS_TAG"]=$this->Tagokad("TIPUS_".$Nyelv."_S","TERMEK","@@@Típus választó§§§");
            
            $TAG[]=array(0=>"@@@Amerikai§§§","1"=>"1");
            $TAG[]=array(0=>"@@@Akció§§§","1"=>"3");
            
            //$TAG=array_merge($TAG,$TempTAG);
            
            
            $TAG2=false;
             if ($Admin)
             {
                $TAG[]=array(0=>"nincs főzde","1"=>"2");
             }
            
//            $Vissza["K_FOZDE_TAG"]=$this->Tagokad("FOZDE_".$Nyelv."_S","TERMEK","@@@Főzde választó§§§","",$TAG);
            $Vissza["K_FOZDE_TAG"]=$this->Futtat(Fozde_azon)->Komboba($TAG);
            
          
//            $Vissza["K_KISZER_TAG"]=$this->Tagokad("KISZERELES_F","TERMEK","@@@Kiszerelés választó§§§"," L");
  //          $Vissza["K_ALKOHOL_TAG"]="@@@Alkoholtartalom§§§+!0-4%+1!4-4.7%+2!4.7-5.5+3!5.5-6.4%+4!6.4-18%+5";

            $Vissza["Felt"]=$Felt;

            return $Vissza;
            
        }

        
        function Tagokad($MEZO,$Tabla="TERMEK",$Urnev="- -",$Mertegys="",$TAG=false)
        {
            $CSOP_TAG[]=array(0=>$Urnev,"");
            if(is_array($TAG))$CSOP_TAG[]=$TAG;
            $sql="select distinct($MEZO) as Dat from $Tabla where $MEZO<>'' order by $MEZO ";
            $Csopok=self::$Sql->Lekerst($sql);
            if ($Csopok)
            {
                foreach ($Csopok as $egymap)
                {
                    $CSOP_TAG[]=array(0=>$egymap["Dat"].$Mertegys,"1"=>$egymap["Dat"]);
                }
            }
            return $CSOP_TAG;
        }
                
        function Mutat_pb_fut()
        {
                
                $this->Mutatloggole();
                
                
                $Ered=$this->Futtat($this->Gyerekparam("CSOPORT",$this->Rendezsql(),1,null," and AKTIV_I='1' ".$this->Hozzafersql()))->Adatlistkozep_publ(false);
                $Param["Menuk"]=$Ered["Eredm"];
//                $Param["Data"]=$this->Adatlistkozep_publ(false);
                $Vissza["Almenuk"]=$this->Sablonbe("Menumunkateruletre",$Param);


                $Param["Menuszuro"]=$this->Szuro();
                
                
                $Termek=$this->Termekekad($Param["Menuszuro"]["Felt"]);
                
                $Param["Termek"]=$Termek["Termek"];
                
                $Param["Data"]=$this->Adatlistkozep_publ(false);
                $Param["Pager"]=$Termek["Pager"];
                $Param["Ujralink2"]=$this->EsemenyHozzad("");
                $Param["Ujralink"]=$Param["Ujralink2"]."?Honnan=0";
                
                $Vissza["Tartalom"]=$this->SzovegCserel();
                
                $Vissza["Tartalom"].=$this->Sablonbe("Mutatkozep",$Param);
                $Vissza["Tartalom"].=$Termek["Pager"];
                
                
                $Vissza["Vissza"]=$this->VisszaEsemenyAd();
                                

                $Vissza["Cim"]=$this->NevAd();
                $Vissza["Cim2"]=1;

//                $Ered=$this->Futtat($this->Gyerekparam("CSOPORT",$this->Rendezsql(),1,null," and AKTIV_I='1' ".$this->Hozzafersql()))->Adatlistkozep_publ(false);
                

                return $this->Sablonbe("Oldal",$Vissza);

        }


        function Adat_adm_menu()
        {
            $Vissza=array();
/*
            $ITEM["Nev"]="Új csoport";
            $ITEM["Link"]=$this->EsemenyHozzad("UjCsoport_fut");
            $Vissza[]=$ITEM;
*/

            $ITEM["Nev"]="Új termék";
            $ITEM["Link"]=$this->EsemenyHozzad("Ujtermek_fut");
            $Vissza[]=$ITEM;
            return $Vissza;
             
        }
        
        

        function Adatlist_adm_tag()
        {
            $Vissza["URLAP"]=1;    
            $Vissza["LISTA"]=1;
            $Vissza["TEKINT"]=1;
            return $Vissza;    
        }

    /*    function Rendezbe()
        {
            $Dat=$this->Futtatgy("CSOPORT!DOKUMENTUM","NEV_S",1,null,null,1)->Objad();
            $Ind=0;
            foreach ($Dat["Eredm"] as $item)
            {
                $item->SorrendBe($Ind);
                $Ind++;
                
            }

            $this->Futtatgy("CSOPORT!DOKUMENTUM","NEV_S",1,null,null,1)->Rendezbe();
        }
        */

         function Lista_fut()
        {
//            if (isset($_GET["Tesz"]))$this->Rendezbe();
            
            $Data["Lista"]=$this->Futtatgy("CSOPORT!DOKUMENTUM","VZ_SORREND_I",1,null,null,1)->Adatlist_adm();
            //$Data["Menu"]=$this->Adat_adm_menu();
                        
            $Param["Menuszuro"]=$this->Szuro(1);
            
//            $Param["Ujralink"]=$this->EsemenyHozzad("Lista_fut")."?HonnanLi=0";
            
                $Param["Ujralink2"]=$this->EsemenyHozzad("Lista_fut");
                $Param["Ujralink"]=$Param["Ujralink2"]."?HonnanLi=0";

            
             
            $Vissza["Tartalom"]=$this->Sablonbe("Szurok",$Param);
                                    
            $Vissza["Tartalom"].=$this->Sablonbe("Lista",$Data);


            $Vissza["Tartalom"].=$this->Lista_seged(false);

                    
            $Vissza["Cim"]=$this->AdminNevAd();
            return $this->Sablonbe("Oldal_admin",$Vissza);
        }        


         function Lista_seged($Ajax)
        {
            $Param["Menuszuro"]=$this->Szuro(1);
            
//            $Param["Ujralink"]=$this->EsemenyHozzad("Lista_fut")."?HonnanLi=0";
            
                $Param["Ujralink2"]=$this->EsemenyHozzad("Lista_fut");
                $Param["Ujralink"]=$Param["Ujralink2"]."?HonnanLi=0";            

            $Felt="";
            $Felt=$Param["Menuszuro"]["Felt"];
            $Data2["Lista"]=$this->Futtatgy("TERMEK","VZ_SORREND_I",1,null,$Felt,1)->Adatlist_adm();

            
            $Data2=array_merge($Data2,$this->Adat_adm());


            if ($Ajax)$Hova="Lista_Uj_seged";
                else $Hova="Lista";             
            $Tartalom=$this->Sablonbe($Hova,$Data2);
            return $Tartalom;

        }        


        function UjCsoport_fut()
        {
                $Obj=$this->UjObjektumLetrehoz("CAlWebAruhazCsoport","CSOPORT");
                return $this->Futtat($Obj)->UrlapKi_fut();
                
        }
        
        function Felsomenube($Kinyit=0)
        {
            $Vissza["Link"]=$this->EsemenyHozzad("");
            $Vissza["Ujablak"]=false;
            
            $Vissza["Nev"]=$this->NevAd();
            $Vissza["Futotte"]=$this->Mostfutotte();
            
            $Vissza["Azon"]=$this->AzonAd();
            
            
            if ($Kinyit<3)
            {
                //$Futott=$this->Mostfutobj();
                    $Ered=$this->Futtat($this->Gyerekparam("CSOPORT",null,null,null," and AKTIV_I='1'"))->Felsomenube($Kinyit+1);
                    $Vissza["Menu"]=$Ered["Eredm"];
                    
            }
           
            return $Vissza;
        }       
        
      
        function Ujtermek_fut()
        {
               $Obj=$this->UjObjektumLetrehoz("CTermek","TERMEK");
               return $this->Futtat($Obj)->UrlapKi_fut();
        }      

     
}

class CAlWebAruhazCsoport extends CWebAruhazCsoport
{




        function Fotermcsop()
        {
            return false;
        }




        function Adatlist_adm_tag()
        {
            $Vissza["URLAP"]=1;    
            $Vissza["TOROL"]=1;
            $Vissza["LISTA"]=1;
            $Vissza["TEKINT"]=1;
            return $Vissza;    
        }




}





class CTermek extends CVaz_bovit
{

        var $Tabla_nev="TERMEK";

        function Adatlist_adm_tag()
        {
            $Vissza["URLAP"]=1;   
            $Vissza["TOROL"]=1;
            return $Vissza;    
        }

        
        function Kedvelbe_pb_fut()
        {
            $AZON=$this->AzonAd();
            if ($this->SessAd("Aktfelh")->Jogosultsag()>0)
            {
                $this->SessAd("Aktfelh")->Kedvencbe($this);
            }    
            $this->ScriptUzenetAd("@@@Kedvencekhez hozzáadva!§§§");       
            return $this->VisszaLep();    
                        
        }
                
        function Kedvelki_pb_fut()
        {
            $AZON=$this->AzonAd();
            if ($this->SessAd("Aktfelh")->Jogosultsag()>0)
            {
                $this->SessAd("Aktfelh")->Kedvki($this);
            }    
            $this->ScriptUzenetAd("@@@Kedvencekből eltávolítva!§§§");       
            return $this->VisszaLep();    
                        
        }
           


           
        function Masolatallit($Termobj)
        {
            $Vissza["Nev"]=$this->NevAd();
            $Vissza["Azon"]=$this->AzonAd();
            
            $Vank=0;
            if ($this->LinkLetezik($Termobj))$Vank=1;

            $Vissza["Vankapcs"]=$Vank;
              
            return $Vissza;
        }     
            
        
        function Kosarnevad()
        {
          $FOZ_NEV=$this->Fozdeneve();
            
                $Nyelv=$this->Nyelvadpub();
                return $FOZ_NEV." ".$this->TablaAdatKi("NEV_".$Nyelv."_S");
        }            
            
        function NevAd()
        {
                $Nyelv=$this->Nyelvadpub();
                return $this->TablaAdatKi("NEV_".$Nyelv."_S");
        }


            
        function AdminNevAd()
        {
            $AKTIV_I=$this->TablaAdatKi("AKTIV_I");
            
            $KESZLET_I=$this->TablaAdatKi("KESZLET_I");
            $Vissza=$this->Kosarnevad()." készleten: ".$KESZLET_I;
            if ($AKTIV_I!="1")$Vissza="<font color=gray>$Vissza</font>";
                    else $Vissza="<font coor=green><strong>$Vissza</strong></font>";
            return $Vissza;
        }

        function Azonositoad()
        {
                return $this->CikkszamAd();
        }

        function CikkszamAd()
        {
                return "";
        }


        function Metatags_seged2()
        {
            $Vissza["Title"]="";
            $Vissza["Key"]="";
            $Vissza["Desc"]="";
            $Nyelv=$this->Nyelvadpub();
            
            $Vissza["Title"]=$this->TablaAdatKi("KERESO_TITLE_".$Nyelv."_S");    
            $Vissza["Key"]=$this->TablaAdatKi("KERESO_KEY_".$Nyelv."_S");    
            $Vissza["Desc"]=$this->TablaAdatKi("KERESO_DESC_".$Nyelv."_S");   
            
            
            
            
            $KEP_IR="";
            $Data=$this->Futtat($this->Gyerekparam("LISTAKEP"))->Eredkep();
            if ($Data["Ossz"]>0)$KEP_IR=$Data["Eredm"][0];
            
            
            if ($KEP_IR!="")$GLOBALS["METAKEP"]=OLDALCIM2.$KEP_IR;
            $GLOBALS["METAURL"]=OLDALCIM2.$this->EsemenyHozzad("");
            

            
            
            return $Vissza;
        }
          
  
        function Metatags_seged2_gener()
        {
            $Sz=$this->SzuloObjektum();
            $Vissza["Title"]=$Sz->NevAd()." ".$this->NevAd();
            $Vissza["Key"]=$Vissza["Title"];
            $Vissza["Desc"]=$Vissza["Title"];
            //$Vissza["Key"]=$this->TablaAdatKi("SZOVEG_S");
//            $Vissza["Key"]=$this->TablaAdatKi("SZOVEG_S");
//            $Vissza["Desc"]=$this->TablaAdatKi("SZOVEG_S");
            
            
            return $Vissza;
        }
        
        function Mennyiseghozza($Mennyi)
        {
            $KESZLET_I=$this->TablaAdatKi("KESZLET_I");
            $KESZLET_I=$KESZLET_I+$Mennyi;
            
            $this->TablaAdatBe("KESZLET_I",$KESZLET_I);
            $this->TablaTarol();    
        }
        
        function Maxkeszlet()
        {
            $KESZLET_I=$this->TablaAdatKi("KESZLET_I");
            return $KESZLET_I;
        }
        
        function Mennyisegad()
        {
            return $this->Maxkeszlet();
        }
          
        function Kosarba_pb_fut()
        {
            if ($this->SessAd("Aktfelh")->Jogosultsag()<99)
            {
            if (!($this->Aktive()))
            {
                return "";
                $this->ScriptUzenetAd("@@@Nem létező termék§§§");
            }
            }
                $Mennyiseg=$this->Postgetv("Mennyiseg");
                if (!(isset($Mennyiseg)))$Mennyiseg=1;
//                if (!(isset($Mennyiseg)))return $this->Mutat_pb_fut();
                
                if ($this->Maxkeszlet()>=$Mennyiseg)
                {
                    $Meret="";
                    $Szin="";
                    if (!(isset($Mennyiseg)))$Mennyiseg=1;
                    if (!(isset($Meret)))$Meret="";
                    if (!(isset($Szin)))$Szin="";
                    if (is_numeric($Mennyiseg))
                    {
                            if ($Mennyiseg>0)
                            {
                                    $this->Futtat(Kosar_azon)->Kosarbatesz($this,$Mennyiseg,$Meret,$Szin);
                                    //$this->ScriptUzenetAd("@@@Sikeresen belekerült a kosárba!§§§");
                            }else
                            {
                            $this->ScriptUzenetAd("@@@A mennyiség 0-nál nagyobb lehet!§§§");
                                }
                    }else $this->ScriptUzenetAd("@@@A mennyiség csak szám lehet!§§§");
                }else
                {
                    $this->ScriptUzenetAd("@@@Nem rendelhet ennyit, nincs ennyi készleten!§§§");
                }
                
                $Reszlet=$this->Postgetv("Reszlet");
                if (isset($Reszlet)&&("$Reszlet"=="1"))
                {
                    return $this->Futtat($this)->Mutat_pb_fut();
                }
                $Data=$this->Adatlistkozep_publ(true);
                $Data["Ajaxos"]=1;
                $Vissza=$this->Sablonbe("Termsor",$Data)."<script>$('#kosdiv').load('".$this->EsemenyHozzad("Felul_kosar_pb_fut")."');</script>";
                
                
                
                return $Vissza;

        }
        
        function Felul_kosar_pb_fut()
        {
                $Data["Kosdata"]=$this->Futtat(Kosar_azon)->Kosarinfo();
                $Vissza=$this->Sablonbe("Kosar_felul",$Data);
                $Vissza=$this->Futtat(Nyelv_azon)->Cserel($Vissza);
                echo $Vissza;
                exit;
                
            
        }   
        
        function Aktive()
        {
            return $this->TablaAdatKi("AKTIV_I");
        }
        

        function Mutat_pb_fut()
        {
                if ($this->SessAd("Aktfelh")->Jogosultsag()<99)
                {
                if (!($this->Aktive()))
                {
                    $Vissza["Tartalom"]="";
                    $Vissza["Cim"]="@@@Nem létező termék§§§";
                    
                    return $this->Sablonbe("Oldal_uni",$Vissza);    
                }
                }
                
                $this->Mutatloggole();                    
                
                
                $NEV=$this->NevAd();
                

                $DATA=$this->Adatlistkozep_publ(false);              
                $SZOVEG=$this->Sablonbe("Mutat",$DATA);


                $Vissza["Tartalom"]=$SZOVEG;
                $Vissza["Cim"]="";
                
                $Vissza["Vissza"]=$this->VisszaEsemenyAd();
               // $Vissza["Kosarlink"]=$this->EsemenyHozzad("Kosarba_pb");
                

                return $this->Sablonbe("Oldal_uni",$Vissza);

        }
 


        function ArAd()
        {
            $Vissza=$this->TablaAdatKi("AR_F");
            $SZAZALEK_F=$this->TablaAdatKi("SZAZALEK_F");
            if ($SZAZALEK_F!="")
            {
                $Vissza=$Vissza-($Vissza*($SZAZALEK_F/100));
            }
            
            return $Vissza;
        }
        
        
      /*  function NettoArAd()
        {
            $Vissza=$this->TablaAdatKi("AR_F");
            return $Vissza;
        }
        */   
        
        function Fozdeneve()
        {
            $FOZ_NEV="";
            $FOZDE_VZ_AZON_I=$this->TablaAdatKi("FOZDE_VZ_AZON_I");
            if (($FOZDE_VZ_AZON_I!="")&&($FOZDE_VZ_AZON_I!="0"))
            {
                $Objl=$this->ObjektumLetrehoz($FOZDE_VZ_AZON_I,0);
                
                
                if ($Objl->Uresobjektum())
                {
                    
                }else
                {
                    $FOZ_NEV=$Objl->NevAd();
                }        
            }
            return $FOZ_NEV;
         }             
        

                
        function Adatlistkozep_publ($Lista=true)
        {
            $Vissza=$this->OsszesTablaAdatVissza();
            if ($this->SessAd("Aktfelh")->Jogosultsag()>0)
            {
                
                    if ($this->SessAd("Aktfelh")->Kedvbevane($this))
                    {
                        $Kedvlink=$this->EsemenyHozzad("Kedvelki_pb_fut");
                        $Vissza["Kedvki"]=$Kedvlink;                        
                    }else
                    {
                        $Kedvlink=$this->EsemenyHozzad("Kedvelbe_pb_fut");
                        $Vissza["Kedvbe"]=$Kedvlink;
                        
                        
                    } 
                
            }
                        
            $Nyelv=$this->Nyelvadpub();
            
            $FOZ_NEV="";
                            
            $Vissza["FOZDE_S"]=$this->Fozdeneve();
            
            $Vissza["TIPUS_S"]=$this->TablaAdatKi("TIPUS_".$Nyelv."_S");
            $Vissza["LEIRAS_S"]=$this->TablaAdatKi("LEIRAS_".$Nyelv."_S");
            $Kos=$this->ObjektumLetrehoz(Kosar_azon,0);
            $Vissza["KOSBA_VAN"]=$Kos->Kosarbavan($this);
            
            
           if ($Lista)
            {
                $Kep=$this->Eleres_allom("LISTAKEP");
//                $Eredm=$this->Futtatgy("KEPEK",null,null,1)->Listakep();
  //              if ($Eredm["Ossz"]>0)$Kep=$Eredm["Eredm"][0];
            }else
            {
                $Dat=$this->Futtatgy("TERMEK",null,null,null," and AKTIV_I='1' ")->Adatlistkozep_publ();
                $Vissza["Kapcsterm"]=$Dat["Eredm"];

                
                $Kep=array();
                $Vissza["Vissza"]=$this->VisszaEsemenyAd();
                
                
/*
                $Item["Listakep"]=$this->Eleres_allom("LISTAKEP");
                $Item["Eredkep"]=$this->Eleres_allom("ERED_LISTAKEP");
                */
                
//                $Kep[]=$Item;
                
                $Eredm=$this->Futtatgy("LISTAKEP",null,null)->Reszletbekepek();
                if ($Eredm["Ossz"]>0)$Kep=$Eredm["Eredm"];
                
                
                
            }
            
            $Vissza["Kep"]=$Kep;
            $Vissza["Nev"]=$this->NevAd();
/*            $Vissza["Leiras"]=$this->TablaAdatKi("SZOVEG_".$this->Nyelvadpub()."_S");

            $Vissza["Meret"]=$this->Meretekad();
            $Vissza["Szin"]=$this->Szinekad();
           
*/

            $Vissza["Link"]=$this->EsemenyHozzad("");

            $Vissza["Kosarlink"]=$this->EsemenyHozzad("Kosarba_pb_fut");
            
            $Vissza["NETTO_AR"]=$this->TablaAdatKi("AR_F");
            $Vissza["AR_F"]=$this->ArAd();
            $Vissza["Azon"]=$this->AzonAd();
            
            


            
/*            $this->ArAd($NETTO,$BRUTTO);
            $Vissza["Netto"]=$NETTO;              
            $Vissza["Brutto"]=$BRUTTO;
  */                        
              
            return $Vissza;
        }
        
        function Szinekad()
        {
            $Vissza=array();
            $Meret=$this->TablaAdatKi("SZIN_S");
            $Db=explode("\n",$Meret);
            foreach ($Db as $egy)
            {
                $egy=trim($egy);
                $egy=str_replace("\n","",$egy);
                $egy=str_replace("\r","",$egy);
                if ($egy!="")
                {
                    $Data=explode(";",$egy);
                    $Vissza[]=$Data;
                }
            }    
            return $Vissza;
        }
        
        function Meretekad()
        {
            $Vissza=array();
            $Meret=$this->TablaAdatKi("MERET_S");
            $Db=explode("\n",$Meret);
            foreach ($Db as $egy)
            {
                $egy=trim($egy);
                $egy=str_replace("\n","",$egy);
                $egy=str_replace("\r","",$egy);
                
                if ($egy!="")$Vissza[]=$egy;
            }    
            return $Vissza;
        }
        

        function Listakepad()
        {
            $Vissza=$this->Eleres_allom("LISTAKEP");
            return $Vissza;
        }




        function Szamra_ex($ERTEK)
        {
            $ERTEK=str_replace(".",",",$ERTEK);
            return $ERTEK;
        }

        function _Excelbement(&$objexce,$Sor)
        {
            
            $objexce->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0,$Sor,$this->unhtmlentities($this->AzonAd()));            
            $objexce->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1,$Sor,$this->unhtmlentities($this->TablaAdatKi("KULSO_AZON_S")));            
            $objexce->setActiveSheetIndex(0)->setCellValueByColumnAndRow(2,$Sor,$this->unhtmlentities($this->Fozdeneve()));            
            $objexce->setActiveSheetIndex(0)->setCellValueByColumnAndRow(3,$Sor,$this->unhtmlentities($this->TablaAdatKi("NEV_HU_S")));
            $objexce->setActiveSheetIndex(0)->setCellValueByColumnAndRow(4,$Sor,$this->unhtmlentities($this->TablaAdatKi("NEV_EN_S")));
            
            $objexce->setActiveSheetIndex(0)->setCellValueByColumnAndRow(5,$Sor,$this->unhtmlentities($this->TablaAdatKi("CIKKSZ_S")));
            $objexce->setActiveSheetIndex(0)->setCellValueByColumnAndRow(6,$Sor,$this->unhtmlentities($this->TablaAdatKi("EAN_S")));

            $objexce->setActiveSheetIndex(0)->setCellValueByColumnAndRow(7,$Sor,$this->Szamra_ex($this->TablaAdatKi("KISZERELES_F")));
            $objexce->setActiveSheetIndex(0)->setCellValueByColumnAndRow(8,$Sor,$this->Szamra_ex($this->TablaAdatKi("ALKOHOL_F")));
            $objexce->setActiveSheetIndex(0)->setCellValueByColumnAndRow(9,$Sor,$this->unhtmlentities($this->TablaAdatKi("KESZLET_I")));
            
            $objexce->setActiveSheetIndex(0)->setCellValueByColumnAndRow(10,$Sor,$this->unhtmlentities($this->TablaAdatKi("TIPUS_HU_S")));
            $objexce->setActiveSheetIndex(0)->setCellValueByColumnAndRow(11,$Sor,$this->unhtmlentities($this->TablaAdatKi("TIPUS_EN_S")));

            $objexce->setActiveSheetIndex(0)->setCellValueByColumnAndRow(12,$Sor,$this->Szamra_ex($this->TablaAdatKi("AR_F")));
            $objexce->setActiveSheetIndex(0)->setCellValueByColumnAndRow(13,$Sor,$this->unhtmlentities($this->TablaAdatKi("LEJAR_D")));
            $objexce->setActiveSheetIndex(0)->setCellValueByColumnAndRow(14,$Sor,$this->unhtmlentities($this->TablaAdatKi("LEIRAS_HU_S")));
            $objexce->setActiveSheetIndex(0)->setCellValueByColumnAndRow(15,$Sor,$this->unhtmlentities($this->TablaAdatKi("LEIRAS_EN_S")));
            $objexce->setActiveSheetIndex(0)->setCellValueByColumnAndRow(16,$Sor,$this->unhtmlentities($this->TablaAdatKi("AKTIV_I")));
        }




       function _Frissit_excbol($row,$objWorksheet)
        {   
                $this->TablaAdatBe("KULSO_AZON_S",$objWorksheet->getCellByColumnAndRow(0, $row)->getValue());
                $this->TablaAdatBe("FOZDE_VZ_AZON_I",$objWorksheet->getCellByColumnAndRow(1, $row)->getValue());

            
                $NEV=trim($objWorksheet->getCellByColumnAndRow(2, $row)->getValue());
                $this->TablaAdatBe("NEV_HU_S",$NEV);
                $this->TablaAdatBe("NEV_EN_S",$NEV);

                $this->TablaAdatBe("CIKKSZ_S",$objWorksheet->getCellByColumnAndRow(3, $row)->getValue());
                $this->TablaAdatBe("EAN_S",$objWorksheet->getCellByColumnAndRow(4, $row)->getValue());

                $KISZERELES=$objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
                $KISZERELES=str_replace("l","",$KISZERELES);
                $KISZERELES=str_replace("L","",$KISZERELES);
                $KISZERELES=trim($KISZERELES);
            
                $this->TablaAdatBe("KISZERELES_F",$KISZERELES);
                $ALK=$objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
                
                $ALK=str_replace("%","",$ALK);
                $ALK=trim($ALK);
                $ALK=str_replace(",",".",$ALK);
                $ALK=$ALK*100;
                $this->TablaAdatBe("ALKOHOL_F",$ALK);
                $this->TablaAdatBe("MENNYDB_S",$objWorksheet->getCellByColumnAndRow(8, $row)->getValue());

                $this->TablaAdatBe("TIPUS_HU_S",$objWorksheet->getCellByColumnAndRow(9, $row)->getValue());
                $this->TablaAdatBe("TIPUS_EN_S",$objWorksheet->getCellByColumnAndRow(9, $row)->getValue());

                $AR=$objWorksheet->getCellByColumnAndRow(10, $row)->getValue();
                $AR=str_replace("Ft","",$AR);
                $AR=str_replace(" ","",$AR);
                $AR=trim($AR);

                $this->TablaAdatBe("AR_F",$objWorksheet->getCellByColumnAndRow(10, $row)->getValue());
                $this->TablaAdatBe("LEJAR_D",$objWorksheet->getCellByColumnAndRow(11, $row)->getValue());

                $this->TablaAdatBe("LEIRAS_HU_S",$objWorksheet->getCellByColumnAndRow(12, $row)->getValue());
                $this->TablaAdatBe("LEIRAS_EN_S",$objWorksheet->getCellByColumnAndRow(13, $row)->getValue());
                $this->TablaAdatBe("AKTIV_I",$objWorksheet->getCellByColumnAndRow(14, $row)->getValue());
            
            $this->Szinkronizal();

          }
          
       function _Frissit_excbol2($row,$objWorksheet)
        {   
            
                $this->TablaAdatBe("MENNYDB_S",$objWorksheet->getCellByColumnAndRow(9, $row)->getValue());
            $this->Szinkronizal();

          }          

        function Xmlbe(&$TOMB)
        {
            $TOMB["Ital"][]=$this->Xml_ital();    
        }

    function Xml_ital()
    {
        $Data=$this->Adatlistkozep_publ(false);
        $Kepir="";
        if (isset($Data["Kep"][0]))$Kepir=$Data["Kep"][0]["Eredkep"];
        
        $Vissza["identifier"]=$this->AzonAd();
        $Vissza["name"]=$Data["FOZDE_S"]." ".$Data["NEV_HU_S"];
        $Vissza["price"]=$this->ArAd();
        $Vissza["product_url"]=OLDALCIM2."/italkereso".$this->EsemenyHozzad("");
        $Vissza["category"]="Sör";

        $Vissza["image_url"]=OLDALCIM2.$Kepir;
        $Vissza["description"]=$this->unhtmlentities($Data["LEIRAS_S"]);
        
        $Vissza["manufacturer"]=$Data["FOZDE_S"];
        $Vissza["delivery_time"]="3 nap";
        $Vissza["delivery_cost"]=SZALL_KLTS;
        
        $Term["product"]=$Vissza;        
        return $Term;
    }
                


       function Urlapki_fut()
        {
                $Cim="Termék szerkesztése";
                $Tarol=$this->EsemenyHozzad("Tarol");

                $Vissza=$this->VisszaEsemenyAd();



                $this->ScriptTarol("
                function Ellenor()
                {
                        if(document.TermekForm.NEV_HU_S.value=='')
                        {
                                alert('A név mező nem lehet üres!');
                                document.TermekForm.NEV_HU_S.focus();
                        }else
                        return true;
                        return false;
                }
                
                $(document).ready(function(){
                $('#LEJARAT_D').datepicker({ dateFormat: 'yy-mm-dd', firstDay: 1});
                $('#MOSTERK_DAT_D').datetimepicker({ dateFormat: 'yy-mm-dd', firstDay: 1});
                
                
                })


                ");



                $Form=new CForm2("TermekForm","?","");
                self::Urlaplink($Form);
                $Form->Hidden("Esemeny_Uj","$Tarol");

                  $Form->Textbox("Név magyar:","NEV_HU_S",$this->TablaAdatKi("NEV_HU_S"),"");
                  $Form->Textbox("Név angol:","NEV_EN_S",$this->TablaAdatKi("NEV_EN_S"),"");

                  $Form->Textbox("Kiszerelés: (L)","KISZERELES_F",$this->TablaAdatKi("KISZERELES_F"),"");
                  $Form->Textbox("Alkoholtartalom: (%)","ALKOHOL_F",$this->TablaAdatKi("ALKOHOL_F"),"");

                  $Form->Textbox("Bruttó ár: (Ft)","AR_F",$this->TablaAdatKi("AR_F"),"");
                  $Form->Textbox("Akció százalék: (%)","SZAZALEK_F",$this->TablaAdatKi("SZAZALEK_F"),"");

                  $Form->Textbox("Készlet: (db)","KESZLET_I",$this->TablaAdatKi("KESZLET_I"),"");
                  $Form->Textbox("Lejárat (eeee-hh-nn):","LEJARAT_D",$this->TablaAdatKi("LEJARAT_D"),"class='datepicker' data-provide='datepicker' ");
                 

                 
  //               $Form->Ontanulo("Főzde magyar","FOZDE_HU_S",$this->TablaAdatKi("FOZDE_HU_S"),"","FOZDE_HU_S","TERMEK");
//                 $Form->Ontanulo("Főzde angol","FOZDE_EN_S",$this->TablaAdatKi("FOZDE_EN_S"),"","FOZDE_EN_S","TERMEK");

                 $Form->Ontanulo("Típus magyar","TIPUS_HU_S",$this->TablaAdatKi("TIPUS_HU_S"),"","TIPUS_HU_S","TERMEK");
                 $Form->Ontanulo("Típus angol","TIPUS_EN_S",$this->TablaAdatKi("TIPUS_EN_S"),"","TIPUS_EN_S","TERMEK");
                 
                 $Tagok=$this->Futtat(Fozde_azon)->Komboba();
                 
                 $Form->Kombobox("Főzde:","FOZDE_VZ_AZON_I",$this->TablaAdatKi("FOZDE_VZ_AZON_I"),"",$Tagok);
                 
                 $Form->Areack("Leírás magyar:","LEIRAS_HU_S",$this->TablaAdatKi("LEIRAS_HU_S"),"");
                 $Form->Areack("Leírás angol:","LEIRAS_EN_S",$this->TablaAdatKi("LEIRAS_EN_S"),"");



                $Form->Radio("Tempest termék:","TEMPEST_I",$this->TablaAdatKi("TEMPEST_I"),"",TEMPEST_TAG);
                $Form->Textbox("Most érkezetteknél megjelenik eddig:","MOSTERK_DAT_D",$this->TablaAdatKi("MOSTERK_DAT_D"),"");
//                $Form->Checkbox("Most érkezett:","MOSTERK_I",$this->TablaAdatKi("MOSTERK_I"),"");
                
                


                $Form->Checkbox("Aktív:","AKTIV_I",$this->TablaAdatKi("AKTIV_I"),"");

                $Form->Szabad2("","Keresőknek szóló információk magyar");

    
                $Form->Area("Title:","KERESO_TITLE_HU_S",$this->TablaAdatKi("KERESO_TITLE_HU_S"),"");
                $Form->Area("Keywords:","KERESO_KEY_HU_S",$this->TablaAdatKi("KERESO_KEY_HU_S"),"");
                $Form->Area("Description:","KERESO_DESC_HU_S",$this->TablaAdatKi("KERESO_DESC_HU_S"),"");

                $Form->Szabad2("","Keresőknek szóló információk angol");
                $Form->Area("Title:","KERESO_TITLE_EN_S",$this->TablaAdatKi("KERESO_TITLE_EN_S"),"");
                $Form->Area("Keywords:","KERESO_KEY_EN_S",$this->TablaAdatKi("KERESO_KEY_EN_S"),"");
                $Form->Area("Description:","KERESO_DESC_EN_S",$this->TablaAdatKi("KERESO_DESC_EN_S"),"");



                $Form->Allomanybe("Listás kép","Listakep",$this->Eleres_allom("LISTAKEP"),"");
                $Form->Szabad2(" ",$Form->Gomb_s("Kapcsolódó termékek szerkesztése","return true","submit","Submit"));


    
                $Form->Szabad2(" ",$Form->Gomb_s("Tárol","return true","submit","Submit")." ".$Form->Gomb_s("Mégsem","location.href='".$this->VisszaEsemenyAd()."'","button"));

                $Tartalom=$Form->OsszeRak();
                $Tartalom.=$this->Markapcsolva();
                $Cim=$this->NevAd()." szerkesztése";
                return $this->Sablonbe("Oldal_admin",array("Cim"=>$Cim,"Tartalom"=>$Tartalom));
        }
       
        function Kapcsotorol_fut()
        {
                $Hova=$this->Postgetv("Hova");
               if ($this->_Masolate())
               {
                    $this->Torolcsakvaz();
               }else
               {
                    
               }
               $Vaz=new CVaz();
               return $Vaz->Futtat($Hova)->Kapcstermlista_fut();            
        }
        
        function Beallit_fut()
        {
            $Hova=$this->Postgetv("Hova");   
            $Obj=$this->ObjektumLetrehoz($Hova,0);
            
            $Van=$Obj->Kapcsterm($this);
            if ($Van)
            {
                
            }else
            {
                $this->Masolatcsinal($Obj,"TERMEK");    
            }
            return $this->Futtat($Obj)->Kapcstermlista_fut();
        }
        
       function Kapcssorir($Hovaterm)
       {
            $LINK1="";
            $LINK2="";
            $Van=$Hovaterm->Kapcsterm($this);
            if ($Van)
            {
                $LINK1="Beállítva";
                $Torol=$Van->EsemenyHozzad("Kapcsotorol_fut")."?Hova=".$Hovaterm->AzonAd();
                $LINK2="<a href='$Torol'>Kapcsolat töröl</a>";
            }else
            {
                $Be=$this->EsemenyHozzad("Beallit_fut")."?Hova=".$Hovaterm->AzonAd();
                $LINK2=" <a href='$Be'>Beállít</a>";
            }
            $Vissza="
            <tr height='20'>
              <td>".$this->AdminNevAd()."</td>
              <td width='90'>".$LINK1."</td>
              <td width='140'>".$LINK2."</td>
            </tr>
            <tr>
              <td colspan='2'><hr></td>
            </tr>
            ";
            return $Vissza;
       }
       
       function Kapcsterm($Melyik)
       {
            $Vissza=false;
            $Dat=$this->Futtatgy("TERMEK",null,null,null," and VZ_TABLA_AZON_I='".$Melyik->TablaAdatKi("AZON_I")."' ")->Objad();
            if ($Dat["Ossz"]>0)$Vissza=$Dat["Eredm"]["0"];
            return $Vissza;
       }
    
        function Ujkep_fut()
        {
                $Obj=$this->UjObjektumLetrehoz("CTermekKep","KEPEK");
                $this->Futtat($Obj)->Fajltarol($this->Filev("Allomany","tmp_name"),$this->Filev("Allomany","name"),MAXALLOMANYMERET,$this->Postgetv("Nev"));
                return $this->Futtat($this)->Kepeklista_fut();
        }
        
        function Markapcsolva()
        {
            $Dat=$this->Futtatgy("TERMEK",null,null,null)->Objad();
            $Azon=$Dat["Eredm"];
            $Tartalom="";
            if ($Azon)
            {
                $Tartalom.="<table cellpadding='2' cellspacing='2' width=400 border='1' >";
                foreach ($Azon as $egy)
                {
                    $Tartalom.=$egy->Kapcssorir($this);
                }
                $Tartalom.="</table>";
            }
            if ($Tartalom!="")$Tartalom="Hozzákapcsolt termékek <br>$Tartalom";
            return $Tartalom;            
        }
        
        function Kapcstermlista_fut()
        {
            $K_FOZDE_VZ_AZON_I=$this->Postgetv_jegyez("K_FOZDE_VZ_AZON_I",0);
            $Mitkeres=$this->Postgetv_jegyez("Mitkeres",0);
            if ("$Mitkeres"=="0")$Mitkeres="";
            
                 $Tagok=$this->Futtat(Fozde_azon)->Komboba();
                 
            
            $Form=new CForm2("Ujtermkep","","");
            $Form->Hidden("Esemeny_Uj",$this->EsemenyHozzad("Kapcstermlista_fut"));
            $Form->Hidden("Honnan",0);
            $Form->Kombobox("Főzde:","K_FOZDE_VZ_AZON_I",$K_FOZDE_VZ_AZON_I,"",$Tagok);

            $Form->Textbox("Keresendő kifejezés:","Mitkeres",$Mitkeres,"");           
            $Form->Gomb("Keresés","return true","submit");
            
            $Tartalom=$Form->OsszeRak();
            

            $Vissza=array();
            $Felt="";
            if (("$Mitkeres"!="")&&("$Mitkeres"!="0"))
            {
                $Felt.=" and (NEV_HU_S like '%$Mitkeres%' or NEV_EN_S like '%$Mitkeres%' or TIPUS_HU_S like '%$Mitkeres%' )";
            }
            if (("$K_FOZDE_VZ_AZON_I"!="")&&("$K_FOZDE_VZ_AZON_I"!="0"))$Felt.=" and FOZDE_VZ_AZON_I='$K_FOZDE_VZ_AZON_I'"; 
            
            $Sql="select VZ_AZON_I from VAZ inner join TERMEK on VAZ.VZ_TABLA_S='TERMEK' and VZ_TABLA_AZON_I=TERMEK.AZON_I where VZ_SZINKRONIZALT_I='1' and VZ_MASOLAT_I='0' $Felt order by NEV_HU_S ";
            $Azon=self::$Sql->Lekerst($Sql);

            $Tartalom.="<table cellpadding='2' cellspacing='2' width=400 border='1' >";
            if ($Azon)
            {
                foreach ($Azon as $egy)
                {
                    $Tartalom.=$this->Futtat($egy["VZ_AZON_I"])->Kapcssorir($this);
                }
            }
            $Tartalom.="</table>";



            $Tartalom.=$Form->Gombcsinal("Vissza","location.href='".$this->EsemenyHozzad("UrlapKi_fut")."'","button");

                         
             $Cim=$this->NevAd()." kapcsolódó termékek szerkesztése";
             return $this->Sablonbe("Oldal_admin",array("Cim"=>$Cim,"Tartalom"=>$Tartalom));
            
            
        }        

        function Kepeklista_fut()
        {
             $Tartalom="";
            $this->ScriptTarol("
function Ellenor()
{
    if (document.Ujallomany.Allomany.value=='')
    {
        alert('Az állomány nem lehet üres!');
    }else return true;
    return false;
}");
            $Data["Lista"]=$this->Futtatgy("KEPEK","VZ_SORREND_I",null,null,null,1)->Adatlist_adm();

            
            $Form=new CForm("Ujtermkep","","");
            $Form->Hidden("Esemeny_Uj",$this->EsemenyHozzad("Ujkep_fut"));
            $Form->Textbox("Név:","Nev","","");
           
            $Form->Allomanybe("Kép forrása:","Allomany","","");
            $Form->Gomb("Feltölt","return Ellenor()","submit");
            
            $Tartalom=$Form->OsszeRak();
            $Tartalom.=$this->Sablonbe("Lista",$Data);
            
                         
             $Cim=$this->NevAd()." képek szerkesztése";
             return $this->Sablonbe("Oldal_admin",array("Cim"=>$Cim,"Tartalom"=>$Tartalom,"Vissza"=>$this->EsemenyHozzad("UrlapKi_fut")));
            
                
        }    

            

        
        function Tarol_fut()
        {
            $Submit=$this->Postgetv("Submit");
                        $this->TablaAdatBe("NEV_HU_S",$this->Postgetv("NEV_HU_S"));
                        $this->TablaAdatBe("NEV_EN_S",$this->Postgetv("NEV_EN_S"));
                        $this->TablaAdatBe("ALKOHOL_F",$this->Postgetv("ALKOHOL_F"));
                        $this->TablaAdatBe("KISZERELES_F",$this->Postgetv("KISZERELES_F"));
                        
                        $this->TablaAdatBe("AR_F",$this->Postgetv("AR_F"));

                        $this->TablaAdatBe("AKTIV_I",$this->Postgetv("AKTIV_I",1));
                        $this->TablaAdatBe("MOSTERK_DAT_D",$this->Postgetv("MOSTERK_DAT_D"));
                        $this->TablaAdatBe("TEMPEST_I",$this->Postgetv("TEMPEST_I"));
                        

                        $this->TablaAdatBe("FOZDE_VZ_AZON_I",$this->Postgetv("FOZDE_VZ_AZON_I"));
                        $this->TablaAdatBe("KESZLET_I",$this->Postgetv("KESZLET_I"));
                        $this->TablaAdatBe("LEJARAT_D",$this->Postgetv("LEJARAT_D"));
                       

                        
                        $this->TablaAdatBe("LEIRAS_HU_S",$this->Postgetv("LEIRAS_HU_S"));
                        $this->TablaAdatBe("LEIRAS_EN_S",$this->Postgetv("LEIRAS_EN_S"));

  //                      $this->TablaAdatBe("FOZDE_HU_S",CForm::Ontanu_ertek("FOZDE_HU_S"));
//                        $this->TablaAdatBe("FOZDE_EN_S",CForm::Ontanu_ertek("FOZDE_EN_S"));
                        $this->TablaAdatBe("TIPUS_HU_S",CForm::Ontanu_ertek("TIPUS_HU_S"));
                        $this->TablaAdatBe("TIPUS_EN_S",CForm::Ontanu_ertek("TIPUS_EN_S"));
                        

                        $this->TablaAdatBe("KERESO_TITLE_HU_S",$this->Postgetv("KERESO_TITLE_HU_S"));
                        $this->TablaAdatBe("KERESO_KEY_HU_S",$this->Postgetv("KERESO_KEY_HU_S"));
                        $this->TablaAdatBe("KERESO_DESC_HU_S",$this->Postgetv("KERESO_DESC_HU_S"));
                        $this->TablaAdatBe("KERESO_TITLE_EN_S",$this->Postgetv("KERESO_TITLE_EN_S"));
                        $this->TablaAdatBe("KERESO_KEY_EN_S",$this->Postgetv("KERESO_KEY_EN_S"));
                        $this->TablaAdatBe("KERESO_DESC_EN_S",$this->Postgetv("KERESO_DESC_EN_S"));
                        $this->TablaAdatBe("SZAZALEK_F",$this->Postgetv("SZAZALEK_F"));

                        
                        

        $this->Urlaplinktarol();


           $this->Szinkronizal();
            
            $this->Allom_tarol("Listakep","LISTAKEP",null,"CTermekKep");
            
//            if ($Submit=="Megtekint")return $this->Mutat_pb_fut();
            if ($Submit=="Termékek felvitele")
            {
                return $this->Futtat($this)->Termek_lista_fut();
            }
            if ($Submit=="Képek szerkesztése")
            {
                return $this->Futtat($this)->Kepeklista_fut();
            }
            if ($Submit=="Kapcsolódó termékek szerkesztése")
            {
                return $this->Futtat($this)->Kapcstermlista_fut();
            }
            


            return $this->VisszaLep();
            
        }

  
        function Adat_adm_menu()
        {
            $Vissza=array();
            $ITEM["Nev"]="Új termék";
            $ITEM["Link"]=$this->EsemenyHozzad("Ujtermek_fut");
            $Vissza[]=$ITEM;
           
            return $Vissza;
             
        }

        

                     
             
function Kepbetolt($ALLOMANY,$ALLOMANY_NAME)
{
                               
        $Eredm=$this->Futtatgy("LISTAKEP")->Fajltarol($ALLOMANY,$ALLOMANY_NAME,MAXALLOMANYMERET,"");
        if ($Eredm["Ossz"]<1)
        {
            $Ujobj=$this->UjObjektumLetrehoz("CTermekKep","LISTAKEP");
            $Ujobj->Fajltarol($ALLOMANY,$ALLOMANY_NAME,MAXALLOMANYMERET,"");

        }

    

}
                        
        public function Tablasql()
        {          


            $SQL="


CREATE TABLE `TERMEK` (
  `AZON_I` int(13) NOT NULL auto_increment,
  `NEV_HU_S` varchar(170) DEFAULT '',
  `NEV_EN_S` varchar(170) DEFAULT '',
  `KISZERELES_F` decimal(10,2) DEFAULT '0.00',
  `ALKOHOL_F` decimal(9,2) DEFAULT '0.00',
  `TIPUS_HU_S` varchar(170) DEFAULT '',
  `TIPUS_EN_S` varchar(170) DEFAULT '',
  `FOZDE_HU_S` varchar(100) DEFAULT '',
  `FOZDE_EN_S` varchar(100) DEFAULT '',
  `LEIRAS_HU_S` text,
  `LEIRAS_EN_S` text,
  `AR_F` decimal(12,2) DEFAULT '0.00',
  `MOSTERK_I` tinyint(1) DEFAULT '0',
  `AKTIV_I` tinyint(1) DEFAULT '1',
  `CIKKSZ_S` varchar(90) DEFAULT '',
  `EAN_S` varchar(90) DEFAULT '',
  `MENNYDB_S` varchar(70) DEFAULT '',
  PRIMARY KEY  (`AZON_I`)  
) ENGINE=innodb;

alter table TERMEK add column KERESO_TITLE_HU_S text;
alter table TERMEK add column KERESO_KEY_HU_S text;
alter table TERMEK add column KERESO_DESC_HU_S text;
alter table TERMEK add column KERESO_TITLE_EN_S text;
alter table TERMEK add column KERESO_KEY_EN_S text;
alter table TERMEK add column KERESO_DESC_EN_S text;
alter table TERMEK add column SZAZALEK_F decimal(12,2) DEFAULT '0.00';
alter table  `TERMEK` add column MOSTERK_DAT_D datetime default '1900-01-01 00:00:00'
";
          
/*
  <product>
    <code>197309</code>
    <productno>EC92000010231</productno>
    <group>Mosogató, csaptelep</group>
    <subgroup>Blanco inox mos.</subgroup>
    <image>http://webaruhaz.gamper.hu/index.php?q=termekek/image/197309/</image>
    <name>MOSOGATÓ BLANCOCLARON 550-U 517221 EXC.NÉLKÜL</name>
    <status>Készleten</status>
    <unit>db</unit>
    <rate>27</rate>
    <price>141188.98</price>
  </product>
*/

            return $SQL;    
        }       



        

}

class CTermekKep extends CMultimedia
{

 
         function Adatlist_adm_tag()
        {
            $Vissza["TOROL"]=1;
            $Vissza["EGYEB"][0]["Nev"]="<img src='".$this->Listakep()."' width='60'>";
            $Vissza["EGYEB"][0]["Link"]="#";
            
            return $Vissza;    
        }
        
        function SzerkesztoSorBeallit()
        {
                $this->Szerkeszto["TOROL"]=1;
                $this->Szerkeszto["TEKINT_UJ"]=1;
        }
        

        
        
        function Fajltarol($Allomany,$Allomany_nev,$Limit,$NEV=null)
        {
            $this->TablaAdatBe("NEV_S",$NEV);
            $Jo=true;
            $Eredm=$this->Futtat($this->Gyerekparam("LISTA"))->Fajltarol($Allomany,$Allomany_nev,$Limit,"k260-350");

            if ($Eredm["Ossz"]==0)
            {
                $Obj=$this->UjObjektumLetrehoz("CAlapAllomany","LISTA");
                $Jo=(($Jo)&&($Obj->Fajltarol($Allomany,$Allomany_nev,$Limit,"k260-350")));
            }


/*
            $Eredm=$this->Futtat($this->Gyerekparam("NAGYKEP"))->Fajltarol($Allomany,$Allomany_nev,$Limit,"k640-640");

            if ($Eredm["Ossz"]==0)
            {
                $Obj=$this->UjObjektumLetrehoz("CAlapAllomany","NAGYKEP");
                $Jo=(($Jo)&&($Obj->Fajltarol($Allomany,$Allomany_nev,$Limit,"k640-640")));
            }
            */

            $Eredm=$this->Futtat($this->Gyerekparam("EREDETIKEP"))->Fajltarol($Allomany,$Allomany_nev,$Limit);
            if ($Eredm["Ossz"]==0)
            {
                $Obj=$this->UjObjektumLetrehoz("CAlapAllomany","EREDETIKEP");
                $Jo=(($Jo)&&($Obj->Fajltarol($Allomany,$Allomany_nev,$Limit)));
            }
            
            if ($Jo)     $this->Szinkronizal();
            
        }
        
        function Reszletbekepek()
        {

            $Vissza["Listakep"]=$this->Listakep();
            $Vissza["Eredkep"]=$this->Eredkep();
            $Vissza["Nev"]=$this->NevAd();
            return $Vissza;
        }

        function Listakep2()
        {
                return $this->Eleres_allom("LISTA2");
        }

        function Listakep()
        {
                return $this->Eleres_allom("LISTA");
        }

        function Nagykep()
        {
                return $this->Eleres_allom("NAGYKEP");

        }

        function Eredkep()
        {
                return $this->Eleres_allom("EREDETIKEP");

        }


}


        
?>