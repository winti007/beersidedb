<?php

 /**
 * CForm  - form összerakást segítő osztály. Admin űrlapok ezt használják, publikusak nem biztos. 
 * Táblázatos forma, két oszlopos: bal oldalt felirat jobb oldalt bekérő. Híváskor saját változóba tárolja az űrlap részeit, OsszeRak állítja egybe az egészet.
 */
 
 

class CForm extends CSablon
{
    protected $Formnev;
    protected $Action;
    protected $FormTartalom;
    protected $Oszl1;
    protected $Tavtart;
    protected $Oszl3;
    

 /**
 * __construct  - form konstruktora
 * @param $Formnevi - form neve 
 * @param $Actioni - form action tagja, ha üres DEF_PHP -> main.php lesz 
 */
    public function __construct($Formnevi="",$Actioni="?")
    {
        parent::__construct();
        $this->Formnev=$Formnevi;
        if ($Actioni=="")$Actioni=DEF_PHP;
        $this->Action=$Actioni;
        $this->FormTartalom="";
        
        $this->Oszl1="width='140' align='right' ";
        $this->Tavtart="width='8'  ";
        $this->Oszl3="";
    }  
    
 /**
 * OsszeRak - összeállítja és visszaadja a formot html formába
 * @return string 
 */     
    public function OsszeRak()
    {
        $NAME=$this->Formnev;
        $Action=$this->Action;

        return "<form name='$NAME' id='$NAME' method='post' action='$Action' enctype='multipart/form-data'>
                <table width='100%' cellspacing='0' cellpadding='0' border='0'>
           
                ".$this->FormTartalom."
                
                          </table>
                </form>
                        ";
    }
    

    
 /**
 * TextBox - text -es bekérő. Az egész bekérő tr$Mezonev néven érhető el javascriptel.
 * @param $Felirat bekérő előtti felirat 
 * @param $Mezonev bekérő neve 
 * @param $Ertek bekérő értéke  
 * @param $Tag bekérőbe akármilyen tag pl javascript 
 * @param $Bekerutan bekérő után kiirandó rész  
 */    
        function Textbox($Felirat,$Mezonev,$Ertek,$Tag,$Bekerutan="")
        {
                if ($Bekerutan!="")$Bekerutan="<span class='text'>$Bekerutan</span>";
               
                $Ertek=str_replace('"','&quot;',$Ertek);
                $this->FormTartalom.="
                  <tr height='25' id='tr$Mezonev'>
<td>                
 <table width='100%' border='0' cellspacing='0' cellpadding='0'>
   <tr>     
     <td ".$this->Oszl1." ><span class='text'>$Felirat</span></td>
     <td ".$this->Tavtart."></td>
     <td ".$this->Oszl3."><input type='text' name='$Mezonev' value=\"$Ertek\" id='$Mezonev' $Tag />$Bekerutan</td>
   </tr>
   <tr>
    <td height='5'></td>
   </tr>
  </table>   
  </td>
</tr>

                ";
        }
        
    
 /**
 * Password - jelszó bekérő. Az egész bekérő tr$Mezonev néven érhető el javascriptel.
 * @param $Felirat bekérő előtti felirat 
 * @param $Mezonev bekérő neve 
 * @param $Ertek bekérő értéke  
 * @param $Tag bekérőbe akármilyen tag pl javascript  
 * @param $Bekerutan bekérő után kiirandó rész  
 */    
        function Password($Felirat,$Mezonev,$Ertek,$Tag,$Bekerutan="")
        {
                if ($Bekerutan!="")$Bekerutan="<span class='text'>$Bekerutan</span>";
               
                $Ertek=str_replace('"','&quot;',$Ertek);
                $this->FormTartalom.="  
                  <tr height='25' id='tr$Mezonev'>
<td>                
 <table width='100%' border='0' cellspacing='0' cellpadding='0'>
   <tr>     
     <td ".$this->Oszl1." ><span class='text'>$Felirat</span></td>
     <td ".$this->Tavtart."></td>
     <td ".$this->Oszl3."><input type='password' name='$Mezonev' value=\"$Ertek\" id='$Mezonev' $Tag />$Bekerutan</td>
   </tr>
   <tr>
    <td height='5'></td>
   </tr>
  </table>  
  </td> 
</tr>
                ";
        }
        
 /**
 * Area - textarea bekérő. Az egész bekérő tr$Mezonev néven érhető el javascriptel.
 * @param $Felirat bekérő előtti felirat 
 * @param $Mezonev bekérő neve 
 * @param $Ertek bekérő értéke  
 * @param $Tag bekérőbe akármilyen tag  pl javascript 
 * @param $Bekerutan bekérő után kiirandó rész  
 */    
        function Area($Felirat,$Mezonev,$Ertek,$Tag,$Bekerutan="")
        {
            if ($Bekerutan!="")$Bekerutan="<span class='text'>$Bekerutan</span>";
            
               $this->FormTartalom.="
<tr height='25' id='tr$Mezonev'>
<td>                
 <table width='100%' border='0' cellspacing='0' cellpadding='0'>
   <tr>     
     <td ".$this->Oszl1." ><span class='text'>$Felirat</span></td>
     <td ".$this->Tavtart."></td>
     <td ".$this->Oszl3."><textarea name='$Mezonev'  id='$Mezonev' $Tag  style=\"width: 400px; height: 80px\" />$Ertek</textarea>$Bekerutan</td>
   </tr>
   <tr>
    <td height='5'></td>
   </tr>
  </table> 
  </td>  
</tr>   

                ";
        }  
        
 /**
 * Areack - ckeditoros bekérő. Az egész bekérő tr$Mezonev néven érhető el javascriptel.
 * @param $Felirat bekérő előtti felirat 
 * @param $Mezonev bekérő neve 
 * @param $Ertek bekérő értéke  
 * @param $Tag bekérőbe akármilyen tag   
 * @param $Bekerutan bekérő után kiirandó rész  
 */    
        function Areack($Felirat,$Mezonev,$Ertek,$Tag,$Bekerutan="",$Defmag="500")
        {            
                $Ckedbe=$this->Kozad("Ckeditorbe",0);
                if (!($Ckedbe))
                {             
                    $this->Headbe("<script type='text/javascript' src='".CK_EDITOR."'></script>                        ");                    
                    $this->Kozbe("Ckeditorbe",1);

                }
                    $this->ScriptTarol("                        $(document).ready(function() {
                            

                                CKEDITOR.replace( '$Mezonev',
                                        {
                                                height: $Defmag
                                        });

                        })
");

            if ($Bekerutan!="")$Bekerutan="<span class='text'>$Bekerutan</span>";
            
               $this->FormTartalom.="<tr height='25' id='tr$Mezonev'>
<td>                
 <table width='100%' border='0' cellspacing='0' cellpadding='0'>
   <tr>     
     <td ".$this->Oszl1." ><span class='text'>$Felirat</span></td>
     <td ".$this->Tavtart."></td>
     <td ".$this->Oszl3."><textarea name='$Mezonev'  id='$Mezonev' $Tag />$Ertek</textarea>$Bekerutan</td>
   </tr>
   <tr>
    <td height='5'></td>
   </tr>
  </table> 
  </td>  
</tr>  
                ";
        }                
        
 /**
 * Checkbox - checkbox bekérő. value='on' mindig a bejelölt checkbox értéke. Az egész bekérő tr$Mezonev néven érhető el javascriptel. 
 * @param $Felirat bekérő előtti felirat 
 * @param $Mezonev bekérő neve 
 * @param $Ertek bekérő értéke. Ha értéke 1, akkor lesz checked a bekérő  
 * @param $Tag bekérőbe akármilyen tag pl javascript 
 * @param $Bekerutan bekérő után kiirandó rész  
 */         
       function Checkbox($Felirat,$Mezonev,$Ertek,$Tag,$Bekerutan="")
       {
            $Jelol="";
            if ("$Ertek"=="1")$Jelol="checked";
            if ($Bekerutan!="")$Bekerutan="<span class='text'>$Bekerutan</span>";
            
               $this->FormTartalom.="<tr height='25' id='tr$Mezonev'>
<td>                
 <table width='100%' border='0' cellspacing='0' cellpadding='0'>
   <tr>     
     <td ".$this->Oszl1." ><span class='text'>$Felirat</span></td>
     <td ".$this->Tavtart."></td>
     <td ".$this->Oszl3."><input type='checkbox' name='$Mezonev'  id='$Mezonev' $Tag  $Jelol /> $Bekerutan</td>
   </tr>
   <tr>
    <td height='5'></td>
   </tr>
  </table>
  </td>   
</tr>  
                ";                

       }
       
 /**
 * Kombo_elem - egy kombóboxot ad vissza html-be. A html-t egyből visszaadja, nem FormTartalom -ba gyűjti, hogy másol is fel lehessen használni.  
 * @param $Mezonev bekérő neve 
 * @param $Ertek bekérő értéke.   
 * @param $Tagok kombo option része ezekből lesz feltöltve. Vagy tömb pl
 * Tomb[0][0]="Január"
 * Tomb[0][1]="1"
 * Tomb[1][0]="Február"
 * Tomb[1][1]="2" .. * 
 * vagy string, ez esetbe Tombre függvény alakítja tömbre a stringet.     
 * @param $Tagegyeb komboba akármilyen inner tag pl javascript    
 * @param $Valaszt1 ha a tagokat stringként adtuk át határoló 1 karakter Tombre fgvnek     
 * @param $Valaszt2 ha a tagokat stringként adtuk át határoló 2 karakter Tombre fgvnek    
 */         
   
       static public function Kombo_elem($Mezonev,$Ertek,$Tagok,$Tagegyeb,$Valaszt1=null,$Valaszt2=null)
       {
               $van=strpos($Tagegyeb,"class");
               if ($van===false)$stilus="class='category' ";
                else $stilus="";

                $OPTION_TAG="                                           <select $stilus name='$Mezonev' id='$Mezonev' $Tagegyeb>

                                           ";
                if (!(is_array($Tagok)))
                {
                    $Vaz=new CVaz();
                    $Tagok=$Vaz->Tombre($Tagok,$Valaszt1,$Valaszt2);
                }

                if (is_array($Tagok))
                {
                        $voltlabel=false;
                        foreach ($Tagok as $Egytag)
                        {
                                if ($Egytag[1]=="$Ertek")$Jelolt="selected";
                                                        else $Jelolt="";
                                $Egytag[1]=str_replace("\n","",$Egytag[1]);
                                $Egytag[1]=str_replace("\r","",$Egytag[1]);
                                $OPTION_TAG.="<option value='".$Egytag[1]."' $Jelolt>".$Egytag[0]."</option>
                                        ";
                        }
                }
                $OPTION_TAG.="</select>";
                return $OPTION_TAG;

       }

 /**
 * Kombobox - select bekérő. Az egész bekérő tr$Mezonev néven érhető el javascriptel. 
 * @param $Felirat bekérő előtti felirat 
 * @param $Mezonev bekérő neve 
 * @param $Ertek bekérő értéke.  
 * @param $Egyebtag bekérőbe akármilyen tag pl javascript 
 * @param $Optiontag option tagok tömb vagy string formájába ld Kombo_elem függvény   
 * @param $Bekerutan bekérő után kiirandó rész  
 */         
       function Kombobox($Felirat,$Mezonev,$Ertek,$Egyebtag,$Optiontag,$Bekerutan="")
       {
            $Kombo=$this->Kombo_elem($Mezonev,$Ertek,$Optiontag,$Egyebtag);
            if ($Bekerutan!="")$Bekerutan="<span class='text'>$Bekerutan</span>";            

            $this->FormTartalom.="<tr height='25' id='tr$Mezonev'>
<td>                
 <table width='100%' border='0' cellspacing='0' cellpadding='0'>
   <tr>     
     <td ".$this->Oszl1." ><span class='text'>$Felirat</span></td>
     <td ".$this->Tavtart."></td>
     <td ".$this->Oszl3.">$Kombo $Bekerutan</td>
   </tr>
   <tr>
    <td height='5'></td>
   </tr>
  </table>
  </td>   
</tr>  
                ";                

       }
       
       static function Ontanu_ertek($Mezonev)
       {
            $ERT=CAlap::Postgetv($Mezonev);
            if ("$ERT"=="-1")
            {
                $ERT=CAlap::Postgetv("UJ_".$Mezonev);
            } 
            return $ERT;
       }

        function Ontanulo($Felirat,$Mezonev,$Ertek,$Egyebtag,$SQL_MEZO,$TABLA)
        {
            $item[0]="- -";
            $item[1]="";
            $Tag[]=$item;

            $item[0]="új érték";
            $item[1]="-1";
            $Tag[]=$item;
            $Feltetel="";
            
            $Azon=self::$Sql->Lekerst("select distinct($SQL_MEZO) as dat from $TABLA where $SQL_MEZO<>'' $Feltetel order by $SQL_MEZO");
            if ($Azon)
            {
                foreach ($Azon as $egy)
                {
                    $item[0]=$egy["dat"];
                    $item[1]=$egy["dat"];
                    $Tag[]=$item;
                }
            }
            
            $this->Kombobox($Felirat,$Mezonev,$Ertek,$Egyebtag." onchange=\"Ontanulallit('$Mezonev');\" ",$Tag);
            $this->Textbox("Új ".$Felirat,"UJ_".$Mezonev,"","");
            
            $this->ScriptTarol("
   $(document).ready(function() {            
            Ontanulallit('$Mezonev');
              });
            ");
            if (!(isset($GLOBALS["Ontanuljava"])))
            {
                $GLOBALS["Ontanuljava"]=1;
                $this->ScriptTarol("
function Ontanulallit(mnev)
{
       ert=$('#'+mnev).val();
       if (ert=='-1')
       {
            $('#trUJ_'+mnev).show();
       }else
       {
        $('#trUJ_'+mnev).hide();
       }
}");
            }
        }
    
                


    
       
       
/**
 * Radio_elem - radio elemeket ad vissza html-be. A html-t egyből visszaadja, nem FormTartalom -ba gyűjti, hogy másol is fel lehessen használni.  
 * @param $Mezonev radiok neve 
 * @param $Ertek. Melyik radio checked alapból   
 * @param $Tagok radio elemek ezekből lesznek feltöltve. Vagy tömb pl
 * Tomb[0][0]="Január"
 * Tomb[0][1]="1"
 * Tomb[1][0]="Február"
 * Tomb[1][1]="2" .. * 
 * vagy string, ez esetbe Tombre függvény alakítja tömbre a stringet.     
 * @param $Tagegyeb radiokba akármilyen inner tag pl javascript Több radio esetén mindegyikbe belemegy.   
 * @param $Valaszt1 ha a tagokat stringként adtuk át határoló 1 karakter Tombre fgvnek     
 * @param $Valaszt2 ha a tagokat stringként adtuk át határoló 2 karakter Tombre fgvnek    
 */         
   
       public function Radio_elem($Mezonev,$Ertek,$Tagok,$Tagegyeb,$Valaszt1=null,$Valaszt2=null)
       {

                $OPTION_TAG="";
                if (!(is_array($Tagok)))$Tagok=$this->Tombre($Tagok,$Valaszt1,$Valaszt2);

                if (is_array($Tagok))
                {
                        $voltlabel=false;
                        foreach ($Tagok as $Egytag)
                        {
                                if ($Egytag[1]=="$Ertek")$Jelolt="checked";
                                                        else $Jelolt="";
                                $Egytag[1]=str_replace("\n","",$Egytag[1]);
                                $Egytag[1]=str_replace("\r","",$Egytag[1]);
                                $OPTION_TAG.="<nobr><input type='radio' name='$Mezonev' id='$Mezonev' value='".$Egytag[1]."' $Jelolt $Tagegyeb> ".$Egytag[0]."</nobr> 
                                        ";
                        }
                }
                return $OPTION_TAG;

       }
              
 /**
 * Radio - radio bekérőket generál. Az egész bekérő tr$Mezonev néven érhető el javascriptel. 
 * @param $Felirat bekérő előtti felirat 
 * @param $Mezonev bekérő neve 
 * @param $Ertek bekérő értéke.  
 * @param $Egyebtag bekérőbe akármilyen tag pl javascript. Több radio esetén mindegyikbe belemegy. 
 * @param $Radiotag radio tagok tömb vagy string formájába ld Kombo_elem függvény   
 * @param $Bekerutan bekérő után kiirandó rész  
 */         
       function Radio($Felirat,$Mezonev,$Ertek,$Egyebtag,$Radiotag,$Bekerutan="")
       {
            $Kombo=$this->Radio_elem($Mezonev,$Ertek,$Radiotag,$Egyebtag);
            if ($Bekerutan!="")$Bekerutan="<span class='text'>$Bekerutan</span>";            

            $this->FormTartalom.=" <tr height='25' id='tr$Mezonev'>
<td>                
 <table width='100%' border='0' cellspacing='0' cellpadding='0'>
   <tr>     
     <td ".$this->Oszl1." ><span class='text'>$Felirat</span></td>
     <td ".$this->Tavtart."></td>
     <td ".$this->Oszl3.">$Kombo $Bekerutan</td>
   </tr>
   <tr>
    <td height='5'></td>
   </tr>
  </table>
  </td>   
</tr>       ";                

       }

           
 /**
 * Hidden - hidden tagot generál. 
 * @param $Mezonev hidden neve 
 * @param $Ertek hidden értéke.  
 */         
    
        function Hidden($Mezonev,$Ertek)
        {
                $Ertek=str_replace('"','&quot;',$Ertek);
               $this->FormTartalom.="
                <input type='hidden' name='$Mezonev' id='$Mezonev' value=\"$Ertek\" />
                ";

        }              
       
 /**
 * Allomanybe - állományt kér be. Az egész bekérő tr$Mezonev néven érhető el javascriptel. 
 * @param $Felirat bekérő előtti felirat 
 * @param $Mezonev bekérő neve 
 * @param $Ertek jelenlegi feltöltött állomány elérése string formájában 
 * @param $Egyebtag bekérőbe akármilyen tag pl javascript. 
 
 */        
       function Allomanybe($Felirat,$Mezonev,$Ertek,$Egyebtag,$Torolkell=true)
        {
                $Pluszir="";
                if ($Ertek!="")
                {
                    $Eler=strtolower($Ertek);
                    $Jpge=mb_strpos($Eler,".jpg",0,STRING_CODE);
                    if ($Jpge===false)
                    {
                        $Pluszir.="<tr>     
     <td ".$this->Oszl1." ><span class='text'>Feltöltve</span></td>
     <td ".$this->Tavtart."></td>
     <td ".$this->Oszl3."><a href='$Ertek' target='_blank' >$Ertek</a></td>
   </tr>
   ";
                    }else 
                    {
                        $Pluszir.="<tr>     
     <td ".$this->Oszl1." ><span class='text'>Feltöltve</span></td>
     <td ".$this->Tavtart."></td>
     <td ".$this->Oszl3."><a href='$Ertek' target='_blank' ><img src='".$Ertek."' border=0 width=60></a></td>
   </tr>
   ";

                    }
                    $TOROL_IR="";
                    if ($Torolkell)$TOROL_IR=$this->Gomb_s("$Felirat törlése","if (confirm('Biztos hogy törli?')){ $('#".$Mezonev."_torol').val('1');return true;}else return false;","submit","Submit");                    
                    $Pluszir.="<tr>     
     <td ".$this->Oszl1." ><input type='hidden' name='".$Mezonev."_torol' id='".$Mezonev."_torol' value='0' /></td>
     <td ".$this->Tavtart."></td>
     <td ".$this->Oszl3.">$TOROL_IR</td>
   </tr>
   ";

                    
                }
                $this->FormTartalom.="<tr height='25' id='tr$Mezonev'>
<td>                
 <table width='100%' border='0' cellspacing='0' cellpadding='0'>
   <tr>     
     <td ".$this->Oszl1." ><span class='text'>$Felirat</span></td>
     <td ".$this->Tavtart."></td>
     <td ".$this->Oszl3."><input type='file' name='$Mezonev' id='' $Egyebtag /></td>
   </tr>
   <tr>
     <td height='5'></td>
   </tr>
   $Pluszir
  </table>
  </td>   
</tr>  
         ";            

        }
        
 /**
 * Gomb - gombot ad vissza, külön sorba
 * @param $Felirat gomb felirata 
 * @param $Onclick onclick esemény 
 * @param $Type  típus. button vagy submit lehet  
 * @param $Name neve 
 * @param $Egyebtag akármilyen tag

 */        
        function Gomb($Felirat,$Onclick="",$Type="button",$Name="button2",$Egyebtag='')
        {
            $Gomb=$this->Gombcsinal($Felirat,$Onclick,$Type,$Name,$Egyebtag);
            $this->Szabad2(" ",$Gomb);
        }
        
 /**
 * Gomb_s - gombot ad vissza htmlbe, nem került FormTartalom -ba
 * @param $Felirat gomb felirata 
 * @param $Onclick onclick esemény 
 * @param $Type  típus. button vagy submit lehet  
 * @param $Name neve 
 * @param $Egyebtag akármilyen tag

 */        
        function Gomb_s($Felirat,$Onclick="",$Type="button",$Name="button2",$Egyebtag='')
        {
            $Gomb=$this->Gombcsinal($Felirat,$Onclick,$Type,$Name,$Egyebtag);
            return $Gomb;
        }        

       
                
 /**
 * Szabad1 - teljes szélességű szabad adatot helyez el a formon
 * @param $Adat
 * @param $Id az egész bekérő Id-je. Ha javascriptből mutatni/rejteni kellene.  
 */    
    public function Szabad1($Adat=null,$Id=null)
    {
        if ($Id===null)$Trtag="";
                    else $Trtag="id='$Id'";

        if ($Adat===null)$Adat="";
                    
        
        $this->FormTartalom.="  <tr height='25' $Trtag>
    <td ><span class='text'>$Adat</span></td>
  </tr>

                ";

    }

 /**
 * Szabad2 - két oszlopos szabad adatot helyez el a formon
 * @param $Baloldal bal oldali adat 
 * @param $Jobboldal jobb oldali adat 
 * @param $Id az egész bekérő Id-je. Ha javascriptből mutatni/rejteni kellene.  
 */    
    public function Szabad2($Baloldal=null,$Jobboldal=null,$Id=null,$Sortagba="")
    {
        if ($Id===null)$Trtag="";
                    else $Trtag="id='$Id'";

        if ($Baloldal===null)$Baloldal="";
                    
        if ($Jobboldal===null)$Jobboldal="";

        
        $this->FormTartalom.="  <tr height='25' $Trtag $Sortagba>
<td>                
 <table width='100%' border='0' cellspacing='0' cellpadding='0'>
   <tr>     
     <td ".$this->Oszl1." ><span class='text'>$Baloldal</span></td>
     <td ".$this->Tavtart."></td>
     <td ".$this->Oszl3.">$Jobboldal</td>
   </tr>
   <tr>
     <td height='5'></td>
   </tr>
   
  </table>
  </td>   
</tr>  

                ";

    }
    
    function Formnevad()
    {
        return $this->Formnev;
    }
                    

}



 class CForm2 extends CForm
{
    
    public function OsszeRak()
    {
        $NAME=$this->Formnev;
        $Action=$this->Action;

        return " 
      <div class='formok'>
            
        <form name='$NAME' id='$NAME' method='post' action='$Action' enctype='multipart/form-data' class='form-horizontal' >
                
           
                ".$this->FormTartalom."
                
                
                </form>
                </div>

                        ";
    }  
    
    
    function Szabad_full($Adat)
    {
        $this->Tartbe($Adat);    
    }
    
    public function Szabad1($Adat=null,$Id=null)
    {
        if ($Id===null)$Trtag="";
                    else $Trtag="id='$Id'";

        if ($Adat===null)$Adat="";
                    
        
        $this->Tartbe("  <div class='form-group' $Trtag >
        <label ".$this->Label_css()."  ></label>
       $Adat
      </div>

                ");

    }
    
    
       function Allomanybe($Felirat,$Mezonev,$Ertek,$Egyebtag,$Torolkell=true)
        {
                $Pluszir="";
                if ($Ertek!="")
                {
                    $Eler=strtolower($Ertek);
                    $Jpge=mb_strpos($Eler,".jpg",0,STRING_CODE);
                    if ($Jpge===false)
                    {
                        $Pluszir.="<div class='form-group' id='tr$Mezonev'>
        <label ".$this->Label_css()."  >Feltöltve</label>
        <div ".$this->Inpdiv_css().">
          <a href='$Ertek' target='_blank' >".$Ertek."</a>
        </div>
      </div>
   ";

                        
                    }else 
                    {
                        $Pluszir.="<div class='form-group' id='tr$Mezonev'>
        <label ".$this->Label_css()."  >Feltöltve</label>
        <div ".$this->Inpdiv_css().">
          <a href='$Ertek' target='_blank' ><img src='".$Ertek."' border=0 width=60></a>
        </div>
      </div>
   ";

                    }
                    $TOROL_IR="";
                    if ($Torolkell)$TOROL_IR=$this->Gomb_s("$Felirat törlése","if (confirm('Biztos hogy törli?')){ $('#".$Mezonev."_torol').val('1');return true;}else return false;","submit","Submit");                    
                    $Pluszir.="<div class='form-group' id='tr$Mezonev'>
        <label ".$this->Label_css()."  ></label>
        <div ".$this->Inpdiv_css().">
          <input type='hidden' name='".$Mezonev."_torol' id='".$Mezonev."_torol' value='0' />
          $TOROL_IR
        </div>
      </div>
   ";

                    
                }
                $this->Tartbe(" <div class='form-group' id='tr$Mezonev'>
        <label ".$this->Label_css()."  >$Felirat</label>
        <div ".$this->Inpdiv_css().">
          <input type='file' name='$Mezonev' id='' $Egyebtag />
        </div>
      </div>
      $Pluszir
       
         ");            

        }
            
        
    public function Szabad2($Baloldal=null,$Jobboldal=null,$Id=null,$Sortagba="")
    {
        if ($Id===null)$Trtag="";
                    else $Trtag="id='$Id'";

        if ($Baloldal===null)$Baloldal="";
                    
        if ($Jobboldal===null)$Jobboldal="";

        
        $this->Tartbe(" <div class='form-group' $Trtag $Sortagba>
        <label ".$this->Label_css()."   >$Baloldal</label>
        <div ".$this->Inpdiv_css().">
          $Jobboldal
        </div>
      </div>

                ");

    }
    
       function Radio($Felirat,$Mezonev,$Ertek,$Egyebtag,$Radiotag,$Bekerutan="")
       {
            $Kombo=$this->Radio_elem($Mezonev,$Ertek,$Radiotag,$Egyebtag);
            if ($Bekerutan!="")$Bekerutan="<span class='text'>$Bekerutan</span>";            

            $this->Tartbe("  <div class='form-group' id='tr$Mezonev'>
        <label ".$this->Label_css()."  >$Felirat</label>
        <div ".$this->Inpdiv_css().">
          $Kombo $Bekerutan
        </div>
      </div>
           ");                

       }
           

               
        
        function Gomb($Felirat,$Onclick="",$Type="button",$Name="button2",$Egyebtag='')
        {
            $Gomb=$this->Gombcsinal($Felirat,$Onclick,$Type,$Name,$Egyebtag."");
            $this->Szabad2(" ",$Gomb);
        }
            
        function Area($Felirat,$Mezonev,$Ertek,$Tag,$Bekerutan="")
        {
            if ($Bekerutan!="")$Bekerutan="<span class='text'>$Bekerutan</span>";
            
               $this->Tartbe("      <div class='form-group' id='tr$Mezonev'>
        <label ".$this->Label_css()."  for='email'>$Felirat</label>
        <div ".$this->Inpdiv_css().">
          <textarea class='form-control' rows='5' name='$Mezonev'  id='$Mezonev' $Tag >$Ertek</textarea>$Bekerutan
        </div>
      </div>


                ");
        }  
            
       function Checkbox($Felirat,$Mezonev,$Ertek,$Tag,$Bekerutan="")
       {
            $Jelol="";
            if ("$Ertek"=="1")$Jelol="checked";
            if ($Bekerutan!="")$Bekerutan="<span class='text'>$Bekerutan</span>";
            
               $this->Tartbe("  <div class='form-group' id='tr$Mezonev'>
        <label ".$this->Label_css()."  for='email'>$Felirat</label>
        <div ".$this->Inpdiv_css().">
          <input type='checkbox' name='$Mezonev'  id='$Mezonev' $Tag  $Jelol /> $Bekerutan
        </div>
      </div>
                ");                

       }
       
                   
       function Kombobox($Felirat,$Mezonev,$Ertek,$Egyebtag,$Optiontag,$Bekerutan="")
       {
            $Kombo=$this->Kombo_elem($Mezonev,$Ertek,$Optiontag,$Egyebtag." class='form-control'");
            if ($Bekerutan!="")$Bekerutan="<span class='text'>$Bekerutan</span>";            

            $this->Tartbe(" <div class='form-group' id='tr$Mezonev'>
        <label ".$this->Label_css()."  >$Felirat</label>
        <div ".$this->Inpdiv_css().">
          $Kombo $Bekerutan
        </div>
      </div>
                        
                ");                

       }
               
        function Textbox($Felirat,$Mezonev,$Ertek,$Tag,$Bekerutan="")
        {
                if ($Bekerutan!="")$Bekerutan="<span class='text'>$Bekerutan</span>";
               
                $Ertek=str_replace('"','&quot;',$Ertek);
                $this->Tartbe("      <div class='form-group' id='tr$Mezonev'>
        <label ".$this->Label_css()."  for='$Mezonev'>$Felirat</label>
        <div ".$this->Inpdiv_css().">
          <input class='form-control' id='$Mezonev' name='$Mezonev' value=\"$Ertek\"  $Tag type='text'> $Bekerutan
        </div>
      </div>
                
                ");
        }
        
        function Password($Felirat,$Mezonev,$Ertek,$Tag,$Bekerutan="")
        {
                if ($Bekerutan!="")$Bekerutan="<span class='text'>$Bekerutan</span>";
               
                $Ertek=str_replace('"','&quot;',$Ertek);
                $this->Tartbe("     <div class='form-group' id='tr$Mezonev'>
        <label ".$this->Label_css()."  for='$Mezonev'>$Felirat</label>
        <div ".$this->Inpdiv_css()." >
          <input class='form-control' id='$Mezonev' name='$Mezonev' value=\"$Ertek\"   type='password'> $Bekerutan
        </div>
      </div>
                
                ");

        }
        
    function Tartbe($Tart)
    {
        $this->FormTartalom.=$Tart;
    }        
        
    function Label_css()
    {
        return "class='control-label col-sm-3 text-right'";    
    }
    
    function Inpdiv_css()
    {
        return "class='col-sm-9 col-md-6' ";    
    }        
        
                
}

 class CForm_rendel extends CForm2
{
    var $FormTartalom2;
    var $Oszlop;
    
    
    public function __construct($DATA=array())
    {
        parent::__construct($DATA);
        $this->Oszlop=1;
        $this->FormTartalom2[1]="";
        $this->FormTartalom2[2]="";
        $this->FormTartalom2[3]="";
    }
    
    function Tartbe($Tart)
    {
        $this->FormTartalom2[$this->Oszlop].=$Tart;
    }
    
    
        function Hidden($Mezonev,$Ertek)
        {
                $Ertek=str_replace('"','&quot;',$Ertek);
               $this->Tartbe("
                <input type='hidden' name='$Mezonev' id='$Mezonev' value=\"$Ertek\" />
                ");

        }              
           

    function Oszlopvalt()
    {
        $this->Oszlop++;    
    }        
            
    public function OsszeRak()
    {
        $NAME=$this->Formnev;
        $Action=$this->Action;

        return " 
      <div class='formok'>
        <form name='$NAME' id='$NAME' method='post' action='$Action' enctype='multipart/form-data' class='form-horizontal' >

          <div class='row'>
            <div class='col-sm-6' >
            ".$this->FormTartalom2[1]."
          </div>
          <div class='col-sm-6' >
          ".$this->FormTartalom2[2]."
          </div>
        </div>  
        ".$this->FormTartalom2[3]."
               
                </form>
                </div>

                        ";
    }  
        
    
    function Label_css()
    {
        
    }
    
    function Inpdiv_css()
    {
        
    }
    

    public function Fejlec($Jobboldal=null,$Id=null,$Sortagba="")
    {
        if ($Id===null)$Trtag="";
                    else $Trtag="id='$Id'";

        
                    
        if ($Jobboldal===null)$Jobboldal="";

        
        $this->Tartbe(" <div class='form-group' $Trtag $Sortagba>        
        <div class='form_fejlec'>
          $Jobboldal
        </div>
      </div>

                ");

    }
            
}



 class CForm_mutat extends CForm_rendel
{
    
    public function Szabad2($Baloldal=null,$Jobboldal=null,$Id=null,$Sortagba="")
    {
        if ($Id===null)$Trtag="";
                    else $Trtag="id='$Id'";

        if ($Baloldal===null)$Baloldal="";
                    
        if ($Jobboldal===null)$Jobboldal="";

        
        $this->Tartbe(" <div class='form-group' $Trtag $Sortagba>
        <label ".$this->Label_css()."   >$Baloldal</label>
        <div ".$this->Inpdiv_css().">
          $Jobboldal 
        </div>
      </div>

                ");

    }
    
    function Label_css()
    {
        return "class='col-sm-3 text-right'";    
    }
    
    function Inpdiv_css()
    {
        return "class='col-sm-9 col-md-6' ";    
    }  
        

    
 /*   public function OsszeRak()
    {
        $NAME=$this->Formnev;
        $Action=$this->Action;

        return "<form name='$NAME' id='$NAME' method='post' action='$Action' enctype='multipart/form-data'>
                <table align='center' cellpadding='0' cellspacing='0' class='datas'>
           
                ".$this->FormTartalom."
                
                          </table>
                </form>
                        ";
    }  
    
    public function Szabad2($Baloldal=null,$Jobboldal=null,$Id=null,$Sortagba="")
    {
        if ($Id===null)$Trtag="";
                    else $Trtag="id='$Id'";

        if ($Baloldal===null)$Baloldal="";
                    
        if ($Jobboldal===null)$Jobboldal="";

        
        $this->FormTartalom.="                  <tr>
                    <th>$Baloldal</th>
                    <td>$Jobboldal</td>
                </tr>
                

                ";

    }
*/        
}

?>