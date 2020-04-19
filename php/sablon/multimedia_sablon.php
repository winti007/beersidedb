<?php


class CGaleriacsoport_sablon extends CCsoport_sablon  
{
    
    function Menumunkateruletre($Data)
    {
        $Vissza="";
        
        
        if (isset($Data["Almenuk"]))$Data["Menuk"]=$Data["Almenuk"];
        
        if (isset($Data["Menuk"]))$Db=count($Data["Menuk"]);
        else
        {
            $Db=0;
        }
            
        
        
        if ($Db>0)
        {
            $Vissza.="
       
                <div class='gallery_list'>
              
            ";
            for ($c=0;$c<$Db;$c++)
            {
                $Vissza.=$this->Menumutatsor($Data["Menuk"][$c]);
            }
            $Vissza.="
            </div>";
        }

        

        return $Vissza;
    } 
    
 

      function Menumutatsor($item)
    {
//        if ($Item["Futotte"])$Jel=" class='current'";
     
                $Kep_ir="";
                if ($item["Kep"]!="")$Kep_ir=$item["Kep"];
                
                if ($Kep_ir!="")$Kep_ir="<a href='".$item["Link"]."'><img src='$Kep_ir' /></a>";
                $Menu_ir="  <div class='item'>
       $Kep_ir
         <h4><a href='".$item["Link"]."'>".$item["Nev"]."</a></h4>
    </div>";

     
        return $Menu_ir;        
    }
    
        
    function Kepekmutat($Param)
    {
        $CSOPNEV=$Param["Adat"]["Nev"];
        $Data=$Param["Kepek"];
        
        $Data=$Data["Eredm"];
        $Vissza="";
        $Db=count($Data);
        
        if ($Db>0)
        {
            $Vissza="    
            <div class='gallery_list'>";
            for ($b=0;$b<$Db;)
            {
                for ($c=0;$c<3;$c++)
                {
                    if (isset($Data[$b]))
                    {
                        $NEV=$Data[$b]["Nev"];
                        if ("$NEV"=="")$NEV=$CSOPNEV;
                        if ($NEV!="")$NEV=" title='$NEV' ";

                        $Vissza.="   <div class='item'>
        <a href='".$Data[$b]["Eredeti"]."' class='fancy' rel='Galery' $NEV>
            <img src='".$Data[$b]["Lista"]."' />
        </a>
        
    </div>
    
                
         ";
                    
//                    <h4><a href='?page=galeria-reszletek'>Csoport 1</a></h4>
                    $b++;
                    }
                }
                
            }
            $Vissza.="</div>
            ";
        }
        return $Vissza;
    }    


            
}

class CPartnercsoport_sablon extends CCsoport_sablon  
{
    function Kepekmutat($Param)
    {
        $CSOPNEV=$Param["Adat"]["Nev"];
        $Data=$Param["Kepek"];
        
        $Data=$Data["Eredm"];
        $Vissza="";
        $Db=count($Data);
        
        if ($Db>0)
        {
            $Vissza="    
            <div class='partners'>";
            for ($b=0;$b<$Db;)
            {
                for ($c=0;$c<3;$c++)
                {
                    if (isset($Data[$b]))
                    {
                        $NEV=$Data[$b]["Nev"];
                        $LINK=$Data[$b]["LINK_S"];
                        $LINKIR="";
                        if ($LINK!="")
                        {
                            $LINKIR=" <p><a href='$LINK' target='_blank'>$LINK</a></p>";    
                        }
                        if ($LINK=="")$LINK="javascript:void(0);";

                        $Vissza.="      <div class='item'>
                    <a href='$LINK' target='_blank' class='image'><img src='".$Data[$b]["Lista"]."' /></a>
                    <div class='text'>
                        <h2><a href='$LINK' target='_blank' class='text'>$NEV</a></h2>
                        $LINKIR
                    </div>
                </div> 
                
         ";
                    
//                    if ("$b"=="2")$Vissza.="<br class='cf clear' >";
                    $b++;
                    }
                }
                
            }
            $Vissza.="</div>
            ";
        }
        return $Vissza;
    }    


            
}


?>