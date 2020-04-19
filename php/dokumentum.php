<?php
class CDokumentum extends CSzoveg
{


        function Adatlist_adm_tag()
        {
            $Vissza["URLAP"]=1;    
            $Vissza["TEKINT"]=1;    
            $Vissza["TOROL"]=1;    
            return $Vissza;    
        }
}

class CStatikusDokumentum extends CSzoveg
{
        function Adatlist_adm_tag()
        {
            $Vissza["URLAP"]=1;    
            $Vissza["TEKINT"]=1;    
            return $Vissza;    
        }

}


?>
