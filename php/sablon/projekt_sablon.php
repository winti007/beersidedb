<?php


class CProjektCsoport_sablon extends CCsoport_sablon  
{
    
    function Menumunkateruletre($Data)
    {
        $Vissza="";
        $Hanyas=1;
        
        $Db=count($Data["Almenuk"]);
        
        $Fodata=$Data;
        if ($Db>0)
        {
            $Db=count($Data["Almenuk"]);
            for ($c=0;$c<$Db;$c++)
            {
                $Vissza.=$this->Menumutatsorp($Data["Almenuk"][$c],$Fodata);
            }
        }
        if (is_array($Fodata))
        {
            $Vissza.=$Fodata["Szoveg"];
        }
        return $Vissza;
    }    


    function Menumutatsorp($Item,&$Fodata)
    {
        if ($Item["Garazs"])
        {
            $Vissza=$this->Mutatsima_garazs($Item,$Fodata);
        }else
        {

            $Vissza=$this->Mutatsima($Item);
        }
        return $Vissza;
    }
    
    function Seged($ERT,$MERT)
    {
        $Vissza=$ERT;
        if ($Vissza=="")return "";
        if ($Vissza=="0")return "";
        if ($Vissza=="0.00")return "";
        $Vissza.=" ".$MERT;
        return $Vissza;
    }
    
    function Mutatsima($Item)
    {
        $Vissza="";   
        foreach ($Item["Ingatlanok"] as $egying)
        {
            
            $MERET1=$this->Seged($egying["ALAPTERULET_HASZN_F"],"m<sup>2</sup>");
            $MERET2=$this->Seged($egying["ALAPTERULET_FULL_F"],"m<sup>2</sup>");
            if (($MERET1!="")&&($MERET2!=""))$MERET1.=" / ";
            
            $ALAP_IR="";
            $KATTINT="";
            if ($egying["Alaprajz"])
            {
                $KATTINT="onclick=\" window.open('".$egying["Alaprajz"]."','_blank',''); \" ";
                $ALAP_IR="<i class='fa fa-file-pdf-o' aria-hidden='true'></i>";
            }
            $STAT=$egying["STATUSZ_I"];
            $Stilst="";
            if ("$STAT"=="2")$Stilst="class='sold'";
            
            $Vissza.="            <tr $KATTINT $Stilst>
                            <td>".$egying["AZONOSITO_S"]."</td>
                            <td>".$egying["EMELET_S"]."</td>
                            <td>".$egying["SZOBASZAM_S"]."</td>
                            <td>".$MERET1.$MERET2." </td>
                            <td>".$egying["ERKELY_S"]."</td>
                            <td class='status'>".$this->Tombert(ING_STATUSZ,$egying["STATUSZ_I"])."</td>
                            <td>".$ALAP_IR."</td>
                        </tr>";
        }
        if ($Vissza!="")
        {
            $Vissza=" <h2 class='build_title' name='epulet".$Item["Azon"]."' id='epulet".$Item["Azon"]."'>".$Item["Nev"]."</h2>
 <table class='forsale'  celspacing='0' cellspadding='0'>
                  <tr>
                        <th class='tac'>Lakás azonosító</th>
                        <th class='tac'>Emelet</th>
                        <th class='tac'>Szobák </th>
                        <th class='tac'>Alapterület</th>
                        <th class='tac'>Erkély </th>
                        <th class='tac' style='width: 150px;'>Státusz</th>
                        <th class='tac'>Lakás alaprajz</th>
                    </tr>
                    $Vissza
                    
                    </table>
                    
                                ";
        }
                                  
            
        /*
        if (is_array($Fodata))
        {
            $Vissza=" <div class='half'>
            $Vissza
            </div>
            <div class='half pd10'>
            ".$Fodata["Szoveg"]."

            </div>
            ";
            $Fodata=null;
        }
        */
        return $Vissza;
    }     
    
    function Mutatsima_garazs($Item,&$Fodata)
    {
        $Vissza="";
        
        foreach ($Item["Ingatlanok"] as $egying)
        {
            
            $MERET1=$this->Seged($egying["ALAPTERULET_HASZN_F"],"m<sup>2</sup>");
            $MERET2=$this->Seged($egying["ALAPTERULET_FULL_F"],"m<sup>2</sup>");
            if (($MERET1!="")&&($MERET2!=""))$MERET1.=" / ";
            
            $STAT=$egying["STATUSZ_I"];
            $Stilst="";
            if ("$STAT"=="2")$Stilst="class='sold'";
            
            $Vissza.="            <tr $Stilst>
                            <td>".$egying["AZONOSITO_S"]."</td>
                            <td>".$MERET1.$MERET2." </td>
                            <td class='status'>".$this->Tombert(ING_STATUSZ,$egying["STATUSZ_I"])."</td>
                        </tr>";
        }
        if ($Vissza!="")
        {
        $Vissza=" <table class='forsale'  celspacing='0' cellspadding='0' name='epulet".$Item["Azon"]."' id='epulet".$Item["Azon"]."'>
                        <tr>
                            <th class='tac' colspan='4' style='background-color: #007748'>".$Item["Nev"]."</th>
                        </tr>
                        <tr>
                            <th class='tac'>Azonosító</th>
                            <th class='tac'>Alapterület</th>
                            <th class='tac' style='width: 150px;'>Státusz</th>
                        </tr>
                        $Vissza
                        
        </table>";
        }                                
            
        
        if (is_array($Fodata))
        {
            $Vissza=" <div class='half'>
            $Vissza
            </div>
            <div class='half pd10'>
            ".$Fodata["Szoveg"]."

            </div>
            ";
            $Fodata=null;
        }
        
        return $Vissza;
    } 
} 
 
?>