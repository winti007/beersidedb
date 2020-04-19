<?php


 /**
 * CMysql - minden mysql adatbázissal kapcsolatos műveletel ezen az osztályon keresztül történik
 * tagok: $Connect - konnekciós link - static hogy mindig ugyanaz, nem kell külön connection-t csinálni, első adatbázisműveletnél automatikusan létrejön   
 */

class CMysql  
{
    private static $Connect=false;    
  


    public function __construct()
    {
    }   
        
    public function __destruct()
   	{
   	}
               
 /**
 * Modosit - egy módosítást/törlést végző Sql parancsot csinál
 * @param $Sql - sql parancs szövegesen
 * @return 0 vagy 1 
 */        
        public function Modosit($Sql)
        {
            $Link=$this->_Link();
            if ($Link)
            {
                $eredmeny = mysqli_query ($Link,"$Sql");
                if (SQL_DEBUG)echo "<!--$Sql-->
";

                $hiba=mysqli_errno($Link);

                if ("$hiba"!="0")
                {
                        if (ADATBAZISNEV=="")
                        {
                            echo mysqli_error($Link);
                            echo "<br>$Sql";
                            exit;
                        }
                        if (Sqlhiba_latszik=="1")
                        {
                            echo mysqli_error($Link);
                            echo "<br>$Sql";
                            exit;
                        }

                        //tesztelés alatt
                
                        exit;
                }

            }
            else $eredmeny=false;
            
            return $eredmeny;
        }
        

 /**
 * Lekerst - egy lekérdezést csinál és asszociatív tömbbe visszaadja az eredményt
 * @param $Sql - sql parancs szövegesen
 * @return array vagy false 
 */
        public function Lekerst($Sql)
        {
            $Link=$this->_Link();
            if ($Link)
            {
                $eredmeny = mysqli_query ($Link,"$Sql");
                if (SQL_DEBUG)echo "<!--$Sql-->
";

                $hiba=mysqli_errno($Link);
                if ("$hiba"!="0")
                {
                        if (ADATBAZISNEV=="")
                        {
                            echo mysqli_error();
                            echo "<br>$SQL";
                        }
                        if (Sqlhiba_latszik=="1")
                        {
                            echo mysqli_error($Link);
                            echo "<br>$Sql";
                            exit;
                        }

                        
                       
                        exit;
                }
            }else return 0;
            if($eredmeny)
            {
                $sorok=mysqli_num_rows($eredmeny);
                if($sorok)
                {
                        for($i=0;$i<$sorok;$i++)
                        {
                                $ADATOK[$i]=mysqli_fetch_assoc ($eredmeny);
                        }
                        return $ADATOK;
                }
                else return 0;
            }
            else return 0;
        }


 /**
 * Leker - mysql lekérdezést segítő függvény, helyette lehet Lekerst -t használni ahol szöveges formába kell parancsot megadni 
 * @param $Options: array 
 * Tabla - Honnan táblanév, kötelező
 * Mit - mit kér le default *
 * Felt - Feltétel, and -al keződdjön pl and NEV like '%s%' 
 * Rendez - Rendezés, pl NEV asc vagy üres
 * Limit - hánytól hányig kérezzen le vesszővel elválasztva, pl 0,10   
 * Group - group by.. 
 * @return false vagy array 
 */        
        public function Leker($Options=array())
        {
            $Rendez="";
            $Tol="";
            $Ig="";
            $Felt="";
            $Group="";
            $Limit="";
            $Tabla=$Options["Tabla"];
            
            if (isset($Options["Group"]))$Group=$Options["Group"];          
            if (isset($Options["Rendez"]))$Rendez=$Options["Rendez"];          
            if (isset($Options["Limit"]))$Limit=$Options["Limit"];          
                      
            if (isset($Options["Felt"]))$Felt=$Options["Felt"];          
            
            
            if (isset($Options["Mit"]))$Mit=$Options["Mit"];
                                   else $Mit="*";
            if ($Group!="")$Group="group by ".$Group;

            if ($Rendez!="")$Rendez=" order by ".$Rendez;
            
            if ($Limit!="")$Limit=" limit $Limit";
            
            if ($Felt!="")
            {
                $Felt=trim($Felt);
                $Felt=mb_substr($Felt,3);
                
                if ($Felt!="")$Felt=" where  ".$Felt;
            }

            $Parancs="select ".$Mit." from ".$Tabla."  $Felt $Group $Rendez $Limit ";
            return $this->Lekerst($Parancs);
        }
        

 /**
 * Beszur - beszúr egy sort a megadott táblába és visszatér a tábla azonosítójával  
 * @param $Tabla - tábla neve
 * @return integer 
 */        
        public function Beszur($Tabla)
        {
            $this->Modosit("insert into $Tabla() values () ");
            return mysqli_insert_id($this->_Link());            
        }

        

 /**
 * Konv - átkonvertálja az átadott adatot mysqlnek megfelelő formátumra
 * pl string esetén a szövegből ' et átalakítja \' -ra  és ' közé rakja a szöveget  
 * @param $Ertek - mit kell átkonvertálni
 * @param $Tipus - milyen típusra. S szöveg, I integer, F float, D dátum, ha egyik se, akkor S
 * @return string 
 */        
        public function Konv($Ertek,$Tipus)
        {
            $Vissza=$Ertek;
            switch ($Tipus)
            {
                default:
                case "S":
                    $Vissza=stripslashes($Vissza);
                   $Vissza=addslashes($Vissza);
//                    $Vissza="'".mysqli_real_escape_string($this->_Link(),$Vissza)."'";
                    $Vissza="'".$Vissza."'";
                
                break;
                case "I":
                    if ($Vissza=="")$Vissza=0;
                    $Vissza=str_replace(",",".",$Vissza);
                  /*  if (!(ctype_digit($Vissza)))
                    {
                        $Vaz=new CVaz();                        
                        $Vaz->ScriptUzenetAd("Nem egész szám ".$Vissza."!");
                        $Vissza=0;
                        
                    }*/
                break;
                case "F":
                    if ($Vissza=="")$Vissza=0;
                    $Vissza=str_replace(",",".",$Vissza);
                    if (!(is_numeric($Vissza)))
                    {
                        $Vaz=new CVaz();
                        $Vaz->ScriptUzenetAd("Nem szám ".$Vissza."!");
                        $Vissza=0;
                        
                    }
                break;
                case "D":
                    if ($Vissza=="")$Vissza="1900-01-01";
                    $Datume=(bool)strtotime($Vissza);
                    if (!($Datume))
                    {
                        $Vaz=new CVaz();
                        $Vaz->ScriptUzenetAd("Nem dátum ".$Vissza."!");
                        $Vissza="1900-01-01";
                        
                    }
                    $Vissza="'".$Vissza."'";
                break;
            }
            return $Vissza;    
        }

 /**
 * Konv - átkonvertálja az átadott adatot mysqlnek megfelelő formátumra 
 * pl string esetén a szövegből ' et átalakítja \' -ra  és ' közé rakja a szöveget  
 * @param $Ertek - mit kell átkonvertálni
 * @param $Mezonev - mezőnév, ebbe van a mező típusa kódolva. Mező típusa, mező nevének utolsó karaktere: i: int, f: float, d: date, s: string 
 * @return string 
 */
        public function Konv_mez($Ertek,$Mezonev)
        {
            $Tipus=$this->Mezotipus($Mezonev);
            return $this->Konv($Ertek,$Tipus);
        }
        
 /**
 * Mezotipus - Mező típusát adja vissza. 
   * @param $Mezonev - mezőnév, ebbe van a mező típusa kódolva. Mező típusa, mező nevének utolsó karaktere: I: int, F: float, D: date, S: string 
 * @return string 
 */
        public function Mezotipus($Mezonev)
        {
            $Kar=mb_substr($Mezonev,mb_strlen($Mezonev)-1,1,"utf-8");
                        
            return $Kar;
        }        


      

 /**
 * _Link - resource id-t adja vissza, ha nincs meghívja Connect -et
 * automatikus meghívódik az első adatbázis műveletkor 
 */    
 
    private function _Link()
    {       
        
        if (!((CMysql::$Connect)))
        {
            if (ADATBAZISNEV!="")
            {
                $MYSQL_LINK=$this->Connect(ADATBAZIS_HOST, ADATBAZIS_USER, ADATBAZIS_PASS,ADATBAZISNEV);                

            }else $MYSQL_LINK=false; 
        }else $MYSQL_LINK=CMysql::$Connect;
        
        return $MYSQL_LINK;

    }
                
 /**
 * Trans_indit - futás elején, tranzakció indítása
 
 */ 
    public function Inditas()
    {
        $this->Modosit("SET autocommit=0");
        $this->Modosit("START TRANSACTION");
        $this->Modosit("Begin");

    }
 
 
  /**
 * Vege - futás vége, attól függően hogy sikeres e minden, tranzakció elfogad vagy elvet
 * @param $Sikeres 0 vagy 1 - sikeres volt e a futás 
 */ 
    public function Vege($Sikeres)
    {
        if ($Sikeres)$this->Modosit("commit");
            else $this->Modosit("rollback");

    }
 
    
 /**
 * Connect - csatlakozik az adatbázishoz, resource id-t belerakja a központi tömbbe.
 * automatikus meghívódik az első adatbázis műveletkor 
 */ 
     public function Connect($ADATBAZIS_HOST, $ADATBAZIS_USER, $ADATBAZIS_PASS,$ADATBAZISNEV)
    {       
        $MYSQL_LINK = mysqli_connect ($ADATBAZIS_HOST, $ADATBAZIS_USER, $ADATBAZIS_PASS);
        if (!$MYSQL_LINK)
        {
            if (ADATBAZISNEV=="")echo "Hiba kapcsolódáskor $ADATBAZIS_HOST, $ADATBAZIS_USER, $ADATBAZIS_PASS ";
            exit;
        }
        CMysql::$Connect=$MYSQL_LINK;
        if ($ADATBAZISNEV!="")$adatbazis=mysqli_select_db ($MYSQL_LINK,$ADATBAZISNEV);

        CMysql::Modosit("SET NAMES utf8;");
        
        
        return $MYSQL_LINK;

    }

 /**
 * Affectrows - előző update által módosított sorok száma
 */ 

    public function Affectrows()
    {
        return mysqli_affected_rows($this->_Link());
    }
        
}

?>