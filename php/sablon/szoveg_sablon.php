<?php



class CSzoveg_sablon extends CKapcsform_sablon  
{    
        
    function Galeriadok($Data)
    {
        $Vissza="";
        $Db=count($Data);
        
        if ($Db>0)
        {
            foreach ($Data as $egy)
            {
                $NEV=$egy["Nev"];
                
                if ($NEV!="")$NEV=" title='$NEV' ";
                
                $Vissza.="<div class='item'>
                 <div class='border'>
                    <a href='".$egy["Eredeti"]."' class='fancy' rel='Galery' $NEV> <img src='".$egy["Lista"]."'> </a>
                </div>
                    </div>
         ";
                
                
            }
        }
        if ($Vissza!="")
        {
            $Vissza="  <div class='gallery'>
                    $Vissza
                    </div>";
        }
        return $Vissza;
        
    }
    
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
       
                   <ul class='prduct_category'>
              
            ";
            for ($c=0;$c<$Db;$c++)
            {
                $Vissza.=$this->Menumutatsor($Data["Menuk"][$c]);
            }
            $Vissza.="
            </ul>";
        }

        

        return $Vissza;
    } 
    
 

      function Menumutatsor($item)
    {
//        if ($Item["Futotte"])$Jel=" class='current'";
     
                $Kep_ir="";
                if ($item["Kep"]!="")$Kep_ir=$item["Kep"];
                
                if ($Kep_ir!="")$Kep_ir="<img src='$Kep_ir' />";
                $Menu_ir=" <li><a href='".$item["Link"]."'>$Kep_ir ".$item["Nev"]."</a></li>                
               ";

     
        return $Menu_ir;        
    }
    



            
}




class CCsoport_sablon extends CSzoveg_sablon
{
  
  
}



class CHirCsoport_sablon extends CSzoveg_sablon
{

    function Menumunkateruletre($Data)
    {
        $Vissza="";
        
        $Db=count($Data["Menuk"]);
        
        
        if ($Db>0)
        {
            $Vissza.="
    
            <div class='news'>
              
            ";
            for ($c=0;$c<$Db;$c++)
            {
                $Vissza.=$this->Menumutatsor($Data["Menuk"][$c]);
            }
            $Vissza.="</div>
            ";
        }

        

        return $Vissza;
    } 
    
      function Menumutatsor($Item)
    {
     
        return "  <div class='news_item'>
                <h2><a href='javascript:void(0);'>".$Item["Nev"]."</a></h2>
                ".$Item["Szoveg"]."                
            </div>            
       
       ";        
    }
          
  
}


class CStatikusCsoport_sablon extends CCsoport_sablon
{
  
  
}

class CStatikusAlCsoport_sablon extends CStatikusCsoport_sablon
{
  
   
  
}


class CAlHirCsoport_sablon extends CHirCsoport_sablon
{
    function Mutathir($Data)
    {
        $Vissza="";
        $Szov=$Data["Szoveg"];
        if ($Szov!="")
        {
            $Vissza="<div class='blog_left'>
            <p class='date_author'>".$Data["Datum"]."</p>
            <div class='html_edited'>
                <p>$Szov</p>
                <a class='btn_back' href='".$Data["Vlink"]."'>Vissza</a>
                <div id='blog-social'></div>
            </div>
        </div>".$this->Mutatjobbra();
        }
        return $Vissza;
    }
    

}

 
class CMainCsoport_sablon extends CSzoveg_sablon  
{
    
} 
 




?>