<?php




 /**
 * CTabla  - tábla egy rekordját(sorát) kezelő osztály.
 * Belső privát tagok: 
 * TABLA_NEV - tábla neve
 * TABLA_AZON - tábla azonosítója
 * ADATOK - rekord mezői asszociatív tömbe
 * VALTOZ_ADAT azok a mezők amelyekbe változás történt (adatbe). Módosításkor csak ezeket kell visszaírni az adatbázisba.
  */
 
class CTabla extends CAlap 
{
        private $TABLA_NEV;
        private $TABLA_AZON;
        private $ADATOK=false;
        private $VALTOZ_ADAT=array();

       

/**
* __construct  - objekutm létrehozásakor automatikusan lefut
* @param        TABLA_NEV: tábla neve  
*               TABLA_AZON: tábla azonosítója, ha TABLA_AZON=0 akkor új sort beszúr a táblába  
*               ADATOK: nem kötelező, ha van ilyen, akkor az adatok már rendelkezésre állnak (select-ből)  
*                 
*/
        public function __construct($TABLA_NEV,$TABLA_AZON,$ADATOK=false)
        {
            parent::__construct();
            $this->TABLA_NEV=$TABLA_NEV;
            $AZON=$TABLA_AZON;
            if ("$AZON"=="0")
            {
                $this->_RekordAdd();
            }else
            {
                $this->TABLA_AZON=$AZON;
            }
            
            if (is_array($ADATOK))
            {
                $Index2=$this->_Kozazonad();
                
                $Adat=$this->Kozadtomb(KOZ_OBJ,$Index2);
                $Van=$Adat["Van"];
                if ("$Van"=="1")
                {
                    $this->ADATOK=$Adat["Ertek"];
                    if ($this->_Torolte())
                    {
                        die("Nem létező rekord ".$this->TABLA_NEV." ".$this->AzonAd());
//                        echo "delete from VAZ where VZ_TABLA_S='".$this->TABLA_NEV."' and VZ_TABLA_AZON_I='".$this->AzonAd()."';<br>";               
                    }
                }else
                {
                    if (!(isset($GLOBALS["Nemkelltrans"])))$this->Kozbetomb(KOZ_OBJ,$ADATOK,$Index2);
                    
//                    $this->Kozbetomb(KOZ_OBJ,$ADATOK,$Index2);
                    $this->ADATOK=$ADATOK;
                }
                                
                                
            }
                            
            
        }
               
   
 /**
 * _Kozazonad  - visszaadja közös tár 2. indexének azonosítóját. tábla+_+táblaazonosító
 * @return string
 */
        private function _Kozazonad()
        {
            $TABLA_NEV=$this->TABLA_NEV;
            $AZON=$this->AzonAd();
            $AZON_NEV=$this->AzonNev();
            return $TABLA_NEV."_".$AZON;    
        }
            

 /**
 * AzonNev  - visszaadja a tábla azonosító mező nevét. VAZ esetén VZ_AZON_I, minden más tábla esetén AZON_I
 * @return string 
 */ 
        function AzonNev()
        {
                if ($this->TABLA_NEV=="VAZ")
                {
                        $Vissza="VZ_AZON_I";
                }else
                {
                        $Vissza="AZON_I";
                }
                return $Vissza;
        }

 /**
 * AzonAd  - visszaadja a tábla azonosítóját
 * @return int 
 */ 
        public function AzonAd()
        {
            return $this->TABLA_AZON;
        }
        


/**
 * _Torolte  - törölt e a rekord,VALTOZ_ADAT-ba létezik __TOROLTE index
 * @return 0 vagy 1
 */
        private function _Torolte()
        {
            $Vissza=0;
            
            if (isset($this->VALTOZ_ADAT[TOROLT_INDEX]))
            {
                $TOROLT=$this->VALTOZ_ADAT[TOROLT_INDEX];
                if ("$TOROLT"=="1")$Vissza=1;
            }
            return $Vissza;
                
        }


/**
 * _Olvas  - ha közös tárba már benne vannak az adatok, lekéri onnan, különben beolvassa adatbázisból, beállítja ADATOK tömböet
*/       protected function _Olvas()
        {
                $Index2=$this->_Kozazonad();
                $Adat=$this->Kozadtomb(KOZ_OBJ,$Index2);

                $Van=$Adat["Van"];
                if ("$Van"=="1")
                {
                    $this->ADATOK=$Adat["Ertek"];
                    if ($this->_Torolte())
                    {
                        die("Nem létező rekord ".$this->TABLA_NEV." ".$this->AzonAd());
//                        echo "delete from VAZ where VZ_TABLA_S='".$this->TABLA_NEV."' and VZ_TABLA_AZON_I='".$this->AzonAd()."';<br>";               
                    }
                }else
                {
                    $AZON=$this->AzonAd();
                    $AZON_NEV=$this->AzonNev();
                    
                    $Data=self::$Sql->Leker(array(
                    "Tabla"=>$this->TABLA_NEV,
                    "Felt"=>" and $AZON_NEV=".self::$Sql->Konv_mez($AZON,$AZON_NEV))
                    );
                    if ($Data)
                    {
                        $this->ADATOK=$Data[0];
//                        $this->Kozbetomb(KOZ_OBJ,$Data[0],$Index2);
                        if (!(isset($GLOBALS["Nemkelltrans"])))$this->Kozbetomb(KOZ_OBJ,$Data[0],$Index2);
                    
                    }else
                    {
                        die("Nem létező rekord ".$this->TABLA_NEV." ".$this->AzonAd());         
//                        echo "delete from VAZ where VZ_TABLA_S='".$this->TABLA_NEV."' and VZ_TABLA_AZON_I='".$this->AzonAd()."';<br>";               
                    }
                }
                    
        }

        
        
/**
 * _Betolt  - betölti az adatokat ha még nem lenne betöltve, csak akkor kell meghívni ha az adatokhoz nyúlunk
 * @return array 
*/        
        private function _Betolt()
        {
            $Tomb=$this->ADATOK;
            if (!(is_array($Tomb)))
            {
                $this->_Olvas();
            }
        }             

/**
 * OsszesAdatVissza  - összes adatot visszaadja a táblából. 
 * @return array 
*/  
        public function OsszesAdatVissza()
        {
            $this->_Betolt();
           
            $Data=$this->ADATOK;
            $Vissza=array();
            foreach ($Data as $Mezonev => $ertek)
            {
                $Vissza[$Mezonev]=$this->AdatKi($Mezonev);
            }
            return $Vissza;
        }
        

/**
 * Lemasol  - lemásol egy CTabla objektumot
* @param $Mitobj - CTabla objektum, amit le akarunk másolni  
*/          
        public function Lemasol($Mitobj)
        {
            $this->_Betolt();
            $ADAT=$this->ADATOK;
            $AZON_NEV=$this->AzonNev();
            foreach ($ADAT as $kulcs => $ertek)
            {
                if (("$AZON_NEV"!="$kulcs")&&(TOROLT_INDEX!="$kulcs"))
                {
                    $this->AdatBe($kulcs,$Mitobj->AdatKi($kulcs));    
                }
            }

        }
        
/**
 * Szinkronizal  - adatbázisba végrehajtja a módosításokat vagy töröl TOROLT_INDEX alapján.  
*/
        public function Szinkronizal()
        {
            $AZON=$this->AzonAd();
            $AZON_NEV=$this->AzonNev();

            $Ertekek="";
            foreach ($this->VALTOZ_ADAT as $Mezonev => $Mezoertek)
            {
                if (("$Mezonev"==TOROLT_INDEX))
                {
                    $SQL="delete from $this->TABLA_NEV  where($AZON_NEV=".self::$Sql->Konv_mez($AZON,$AZON_NEV).")";
                    $this->Sql->Modosit($SQL);
                }else                    
                if ("$Mezonev"!="$AZON_NEV")
                {
                    if ($Ertekek!="")$Ertekek.=",";
                    $Ertekek.=$Mezonev."=".self::$Sql->Konv_mez($Mezoertek,$Mezonev);
                }                
                unset($this->VALTOZ_ADAT[$Mezonev]);
            }
            if ($Ertekek!="")
            {
                if (!($this->_Torolte()))
                {
                    $SQL="update $this->TABLA_NEV set $Ertekek where($AZON_NEV=".self::$Sql->Konv_mez($AZON,$AZON_NEV).")";

                    self::$Sql->Modosit($SQL);
                }
            }
            $this->_Olvas();

        }



/**
* Torol  - törli a sort
* közös tárba a módosítások már ott vannak
*/
       public function Torol()
        {
            $AZON=$this->AzonAd();
            $AZON_NEV=$this->AzonNev();
            
            $SQL="delete from $this->TABLA_NEV  where($AZON_NEV=".self::$Sql->Konv_mez($AZON,$AZON_NEV).")";
            self::$Sql->Modosit($SQL);
            
            $this->AdatBe(TOROLT_INDEX,1);
        }

        
/**
 * AdatKi  - Visszaad egy mező értéket
* @param $Mezonev - mező neve 
* @return string  
*/          
        public function AdatKi($Mezonev)
        {
            $Index2=$this->_Kozazonad();
            $Tomb=$this->Kozadtomb(KOZ_OBJ,$Index2);
            if ($Tomb["Van"]=="1")
            {
                $Vissza=$Tomb["Ertek"][$Mezonev];
                return $Vissza;
            }
            $this->_Betolt();
       
            return $this->ADATOK[$Mezonev];
        }


/**
 * AdatBe  - Betölt egy mezőbe értéket
* @param $Mezonev - mező neve 
* @param $Mit - érték 
*/          
        public function AdatBe($Mezonev,$Mit)
        {
            
            
            $this->_Betolt();


            //$this->Kozbetomb(KOZ_OBJ,$Mit,$this->_Kozazonad(),$Mezonev);
            if (!(isset($GLOBALS["Nemkelltrans"])))$this->Kozbetomb(KOZ_OBJ,$Mit,$this->_Kozazonad(),$Mezonev);
                        
            $this->VALTOZ_ADAT[$Mezonev]=$Mit;
                      
            
        }


/**
 * _RekordAdd  - hozzáad egy új sort. Ez azonnnal végrehajtódik az adatbázisba is - hogy felvegye az adatbázis default értékeit. 
*/ 
        protected function _RekordAdd()
        {
            $AZON=self::$Sql->Beszur($this->TABLA_NEV);
            $this->TABLA_AZON=$AZON;
        }


        
/**
 * Vanilyenmezo  - Visszaadja hogy létezik e a táblához ilyen mező. Kereső szavak generáláshoz használatos 
* @param $Mezonev - mező neve 
* @return boolean 0 v 1  
*/          
        public function Vanilyenmezo($Mezonev)
        {
            $Vissza=0;
            $Index2=$this->_Kozazonad();
            $Tomb=$this->Kozadtomb(KOZ_OBJ,$Index2);
            if ($Tomb["Van"]=="1")
            {
                if (isset($Tomb["Ertek"][$Mezonev]))$Vissza=1;
            }
            $this->_Betolt();
       
            if (isset($this->ADATOK[$Mezonev]))$Vissza=1;
            return $Vissza; 
        }


}


 

 /**
 * CVaz - minden objektum ebből származik. Áll 1 rekord VAZ táblába és 1 rekord saját táblába. 
 * $Vaz - CTabla osztályra épülő objektum VAZ táblára   
 * $Tabla - CTabla osztályra épülő objektum saját táblára
 * $Tabla_nev - saját tábla neve stringként, VAZ-ból származó osztályokba meg kell adni. VAZ táblába is tároljuk
 * $Def_feladat - default feladat neve, ez el van rejtve url-be - ha simán objektumot hívjuk függvény nélkül -  ez a függvény fut le     
 */
 
class CVaz extends CAlap
{
    private $Vaz;
    private $Tabla;
    protected $Tabla_nev="Nincs megadva";
    protected $Def_feladat="Mutat_pb_fut";
    

 /**
 * __construct - objektum létrehozáskor automatikus meghívódik. Objektumokat létrehozni ObjektumLetrehoz függvénnyel lehet.
 * 4 fajtája lehet: 
 * 1. meglévő objektum létrehozása VAZ adatok - alapján. (VZ_AZON -ból létrehozva) 
 * 2. meglévő objektum létrehozása összes adatból (select-ből jönnek az adatok, VZ_ el kezdődő mezők VAZ mezők)
 * 3. új létrehozás - VAZ és saját táblába új sorokat kell beszúrni
 * 4. üres csupasz VAZ létrehozása adatok nélkül, futás legelején
 * VAZ ból származó osztályoknak __construct eljárást nem kell felülírni, új létrehozáskor a függvény végén meghívódik: Konstrveg_uj eljárás, meglévő létrehozáskor Konstrveg eljárás amiket felül lehet írni. Új esetén lefut Konstrveg_uj és Konstrveg is.  
 * @param $DATA - array indexei: 
 *                       VAZ: - VAZ tábla adatai asszociatív tömbbe, 1. típus létrehozáskor 
 *                       OSSZES: - összes tábla adatai egy asszociatív tömbbe, 2. típus létrehozáskor
 *                       UJ:  3. típus létrehozáskor
 *                       SZULO: 3. típus - új esetén szülőobjektum
 *                       LISTA: 3. típus - új esetén listatípus
 *                       URIT: ha létezik és 1-es, akkor az állapotot üríteni kell  
 *    
 */    
    public function __construct($DATA=array())
    {
        parent::__construct();
        
        $this->Tablaletre();
        
        if (isset($DATA["VAZ"]))
        {
            $this->Vaz=new CTabla("VAZ",$DATA["VAZ"]["VZ_AZON_I"],$DATA["VAZ"]);            
            $this->Tabla=new CTabla($DATA["VAZ"]["VZ_TABLA_S"],$DATA["VAZ"]["VZ_TABLA_AZON_I"]);
            
        }
        if (isset($DATA["OSSZES"]))
        {
            $Vazdata=array();
            $Tabladata=array();
            foreach ($DATA["OSSZES"] as $Mezonev => $ertek)
            {
                if (mb_substr($Mezonev,0,3,"utf-8")=="VZ_")$Vazdata[$Mezonev]=$ertek;
                                    else $Tabladata[$Mezonev]=$ertek;
                    
            }
            
            
            $this->Vaz=new CTabla("VAZ",$Vazdata["VZ_AZON_I"],$Vazdata);            
            $this->Tabla=new CTabla($Vazdata["VZ_TABLA_S"],$Vazdata["VZ_TABLA_AZON_I"],$Tabladata);
            
        }
        if (isset($DATA["UJ"]))
        {
            $this->__construct_seged_uj($DATA["SZULO"],$DATA["LISTA"]);
            $this->Konstrveg_uj();
        }
        $this->Konstrveg();
        
        if (isset($DATA["URIT"]))
        {
            if ($DATA["URIT"]=="1")
            {
                $this->AllapotUrit();
            }
        }        
    }
    
    function __destruct()
    {
        if (!($this->Uresobjektum()))
        {

            $this->Objektumkozostarba($this);
        }
    }
    
   
    
/**
 * __construct_seged_uj  - új objektum létrehozása segédfüggvény. VAZ és saját táblába beszúr új sort
* @param $SzuloObj - szülőobjektum 
* @param $Lista - VAZ lista típus 
*/ 
  
    public function __construct_seged_uj($SzuloObj,$Lista)
    {
            
      
        if($this->Tabla_nev==="Nincs megadva")
        {
            echo"<font color=red>Nincs megadva a tábla neve ($this->Tabla_nev)</font>";
            exit;
        }
                
        $this->Vaz=new CTabla("VAZ","0");            
        $this->Tabla=new CTabla($this->Tabla_nev,"0");
        $TABLA_AZON=$this->Tabla->AzonAd();
                

        $Szulo=$SzuloObj->_EredetiAzon();
        
        if ($Szulo!=$this->AzonAd())$SzuloObj=$this->ObjektumLetrehoz($Szulo,0);
        
        $SORREND=$SzuloObj->MaxSorszam()+1;

        $KESZULT=date("Y-m-d H:i:s");
        $OBJEKTUM_TIPUS=get_class($this);
                
        $this->Vaz->AdatBe("VZ_SZULO_I",$Szulo);
        $this->Vaz->AdatBe("VZ_KESZULT_D",$KESZULT);
        $this->Vaz->AdatBe("VZ_TABLA_S",$this->Tabla_nev);
        $this->Vaz->AdatBe("VZ_TABLA_AZON_I",$TABLA_AZON);
        $this->Vaz->AdatBe("VZ_OBJEKTUM_S",$OBJEKTUM_TIPUS);
        $this->Vaz->AdatBe("VZ_LISTA_S",$Lista);
        $this->Vaz->AdatBe("VZ_SORREND_I",$SORREND);
        $this->Vaz->AdatBe("VZ_NYELV_S",$SzuloObj->Nyelvad());
        
        $this->Vaz->AdatBe("VZ_FELHASZNALO_I",$this->Sessad("Aktfelh")->AzonAd());
        $this->Defkulsolink();
        $this->LetrehozoBe($SzuloObj);

    }

  

             
    
/**
 * Konstrveg_uj  - új objektum létrehozása végén fut le. Pl ha valami default érték nem  adatbázisból kellene hogy jöjjön, itt lehetne beállítani
*/ 
    public function Konstrveg_uj()
    {
        
    }
    
/**
 * Konstrveg  - objektum létrehozása végén fut le. Ha valamit kell csinálni ilyenkor,  felül lehet bírálni
*/ 
    public function Konstrveg()
    {
        
    }    


/**
 * Tablaletre  - az objektum tábláit létrehozza az adatbázisban, ha még nem létezne - Tablasql -al kérdezi le az sql parancsot
*/ 
  
    private function Tablaletre()
    {
        if (Nemkell_tablaletrehoz=="1")return true;
        
        $Sql=$this->Tablasql();
        if (($Sql!="")&&($this->Tabla_nev!="")&&($this->Tabla_nev!="Nincs megadva"))
        {
            $Vantabla=self::$Sql->Lekerst("SHOW TABLE STATUS like '".$this->Tabla_nev."'");
            if (!($Vantabla))
            {
                $Sorok=explode("§",$Sql);
                foreach ($Sorok as $Egy)
                {
                    self::$Sql->Modosit($Egy);
                }
            }
        }
            
    }

    
/**
 * Tablasql  - az objektum táblá/i/nak sql parancsa create table -el. Ezt kell felülírni későbbi osztályokba
*/ 
  
    public function Tablasql()
    {
        return "";    
    }    
        
    
 /**
 * ObjektumLetrehoz - objektum létrehozása VAZ.VZ_AZON_I vagy tömb alapján, ha tömb akkor az adatokat tartalmazza a tömb - gyerekvisszaból  
 * @param $Objdata - VAZ tábla azonosító vagy tömb az adatokkal - gyerekvissza hívásból 
 * @param $Urit 0 vagy 1:  1 esetén üríti az objektum állapotát 
 * @return object - vmi hiba esetén (pl sql) üres CVaz objektumot ad vissza    
 */
    public function ObjektumLetrehoz($Objdata,$Urit=0)
    {
        
        $Tarbavolt=false;
        if (is_array($Objdata))
        {
            $Volte=$this->Kozadtomb(KOZ_OBJ,$Objdata["VZ_AZON_I"]);
            if ($Volte["Van"]=="1")
            {
                $Eredmeny=$Volte["Ertek"];
                $Tarbavolt=true;   
            }else
            {
                $OBJEKTUM=$Objdata["VZ_OBJEKTUM_S"];
                $Eredmeny=new $OBJEKTUM(
                array(
                "OSSZES"=>$Objdata,
                "URIT"=>$Urit
                ));
                
            }                                         
        }else
        {
            $Volte=$this->Kozadtomb(KOZ_OBJ,$Objdata);
            if ($Volte["Van"]=="1")
            {
                $Eredmeny=$Volte["Ertek"];
                
                $Tarbavolt=true;   
            }else
            {
                $Data=self::$Sql->Leker(
                array(
               "Tabla"=>"VAZ",
               "Felt"=>" and VZ_AZON_I=".self::$Sql->Konv_mez($Objdata,"VZ_AZON_I")
               )
               );

                if($Data)
                {

                    $OBJEKTUM=$Data[0]["VZ_OBJEKTUM_S"];
                    $Eredmeny=new $OBJEKTUM(
                array(
            "VAZ"=>$Data[0]
            ));
                               
                        
                }else $Eredmeny=new CVaz();
               
            }
            
        }
        if (!($Tarbavolt))
        {
            if ($Eredmeny)$this->Objektumkozostarba($Eredmeny);
        }

        return $Eredmeny;
    }
    

 /**
 * UjObjektumLetrehoz - Új objektumot hoz létre  
 * @param $ObjektumTipus - objektumtípus
 * @param $Lista - VZ_LISTA_S - Lista
 * @return object 
 */ 
 
        function UjObjektumLetrehoz($ObjektumTipus,$Lista)
        {            
            
                $UjObjektum=new $ObjektumTipus(array(
                "UJ"=>"1",
                "SZULO"=>$this,
                "LISTA"=>$Lista
                ),$ObjektumTipus);
                
                $this->Objektumkozostarba($UjObjektum);
                $UjObjektum->Tablatarol();
    
                return $UjObjektum;
        }

         
 /**
 * Objektumkozostarba - közös tárba teszi a létrehozott objektumot hogy később is kéznél legyen  
 * @param $Objektum - objektum
 */ 
 
        function Objektumkozostarba($Objektum)
        {

            if (!($Objektum->Uresobjektum()))
            {
                if (!(isset($GLOBALS["Nemkelltrans"])))$this->Kozbetomb(KOZ_OBJ,$Objektum,$Objektum->AzonAd());
            }
                 
        }


 /**
 * Uresobjektum - üres CVaz objektum e az objektum. Ha Vaz tagja nem objektum akkor üres 
 * @return 0 vagy 1
 */ 
    public function Uresobjektum()
    {
        if (is_object($this->Vaz))$Vissza=false;
                             else $Vissza=true;
        return $Vissza;
    }

 /**
 * Init - ez akkor fut le, ha valamiért mainobjektumot nem tudja main.php létrehozni. Normális esetben ez azt jelenti hogy legelső futás - még nincs adatbázis és létre kell hozni.  
 * @return 0 vagy 1
 */
    function Init()
    {
        $Obj=new CElsofutas();
        return $Obj->Mutat();
        
    }
            

 /**
 * Azonosit - objektum azonosítója, ha azt akarjuk eldönteni hogy két objektum ugyanaz e, ezt kell összehasonlítani egymással 
 * @return string    
 */
    
    public function Azonosit()
    {
        $TABLA_AZON=$this->Vaz->AdatKi("VZ_TABLA_AZON_I");
        $TABLA=$this->Vaz->AdatKi("VZ_TABLA_S");
        return ($TABLA_AZON.$TABLA);
    }


 /**
 * Egyezike - az objektum megegyezik e egy másik objektummal 
 * @return string    
 */
    function Egyezike($Mivel)
    {
        return ($this->EgyezoObjektum($this,$Mivel));
    }
        


    public function Tobbnyelvu()
    {
        return false;
    }         


 /**
 * Defkulsolink - külső elérést generál az objektumnak, ha van  NevAd() függvény abból ha nincs táblanév + tábla azonosítóból
 */
 
    
    public function Defkulsolink()
    {
        $NEV="";

            $NYELV=$this->Nyelvad();
            if ($this->Tobbnyelvu()&&($this->Vanilyenmezo("NEV_".$NYELV."_S")))
            {
                $NEV=$this->TablaAdatKi("NEV_".$NYELV."_S");
            }

        if ($NEV=="")
        {
            if (method_exists($this,"NevAd"))
            {
            
                $NEV=$this->NevAd();
            
            }
        }
            
        if ($NEV=="")
        {
            $NEV=$this->Tabla_nev;
            $Azon=$this->TablaAdatKi("AZON_I");
            $NEV.=$Azon;
        }
        $NEV=$this->_Defkulso_alakit($NEV);
        $this->Vaz->AdatBe("VZ_KULSO_LINK_S",$NEV);
    }
    
 /**
 * _Defkulso_alakit - érvényes külső elérésre alakítja az átadott nevet. Ékezeteket kiveszi, ha már van ilyen random számot tesz a végére. 
 */    
    private function _Defkulso_alakit($NEV)
    {
            $Lehet=$this->_Kulsolinkbe_lehet();
            $NEV=$this->unhtmlentities($NEV);
            $mit = array (" ", "[", "]", "+", ";", "á", "é", "í", "ó", "ö", "ő", "ú", "ü", "ű","Á","Ű","É","Ú","Ő","Ó","Ü","Ö","Í");
            $mire= array ("_", "_", "_", "_", "_", "a", "e", "i", "o", "o", "o", "u", "u", "u","a","u","e","u","o","o","u","o","i");
            $NEV = str_replace($mit, $mire, $NEV);
            if (mb_strlen($NEV,STRING_CODE)>70)$NEV=mb_substr($NEV,0,66,STRING_CODE);

            $Hossz=mb_strlen($NEV,STRING_CODE);
            $NEVJO=$NEV;
                $Jo=true;
                for ($c=0;$c<$Hossz;$c++)
                {
                        $Kar=mb_substr($NEV,$c,1,STRING_CODE);
                        $Temp=mb_strpos($Lehet,$Kar,0,STRING_CODE);
                        if ($Temp===false)
                        {
                                $NEVJO=str_replace($Kar,"",$NEVJO);
                        }

                }
                $Volt=$this->Voltilyenkulso($NEVJO);
                $Index=0;
                while ((("$Volt"!="0")||(mb_strlen($NEVJO,STRING_CODE)==0)))
                {
                        $NEVJO.=rand(0,9);
                        $Index++;
                        $Volt=$this->Voltilyenkulso($NEVJO);
                        if ($Index>16)die("Hiba név generáláskor");
                }

                return $NEVJO;

      }
        
 /**
 * _Kulsolinkbe_lehet - külső link/elérés milyen karaktereket tartalmazhat
  * @return string 
 */        
        private function _Kulsolinkbe_lehet()
        {
            return "yxcvbnmasdfghjklqwertzuiopYXCVBNMASDFGHJKLQWERTZUIOP0123456789_";
        }
                    

 /**
 * Voltilyenkulso - van e már ilyen külső elérés az adatbázisban
 * @return 0 vagy objektum  azonosító
 */
        function Voltilyenkulso($VZ_KULSO_LINK,$Magalehet=false)
        {
                $Felt="";

                $VZ_AZON=$this->AzonAd();
                if (!($Magalehet))$Felt.="and VZ_AZON_I<>'$VZ_AZON'";

                $Volt=self::$Sql->Lekerst("select VZ_AZON_I from VAZ where VZ_KULSO_LINK_S='$VZ_KULSO_LINK'  $Felt");
                $Vissza=0;
                if ($Volt)
                {
                    $Vissza=$Volt[0]["VZ_AZON_I"];
//                        $Volt=$this->ObjektumLetrehoz($Volt[0]["VZ_AZON_I"]);
                }
                return $Vissza;
        }


 /**
 * _Kulsoelerlehet - külső elérésbe lehet e ,/ jel előtt 
 * @return boolean  
 */
        public function _Kulsoelerlehet()
        {
                return true;
        }

 
    function Nyelvbe($NYELV)
    {
        $this->Vaz->AdatBe("VZ_NYELV_S",$NYELV);
        $this->Vaz->Szinkronizal();
        
    }

        
/**
* Masolatcsinal  - objektumból csinál egy másolatot 
* @param $SzuloObj - szülőobjektum
* @param $Lista - lista típus
*/
        public function Masolatcsinal($SzuloObj,$Lista)
        {

                $Szulo=$SzuloObj->AzonAd();
                $SORREND=$SzuloObj->MaxSorszam()+1;
                $TABLA_AZON=$this->TablaAdatKi("AZON_I");
                $Vaz=new CTabla("VAZ",0);

                $Vaz->AdatBe("VZ_SZULO_I",$Szulo);
                $Vaz->AdatBe("VZ_KESZULT_D",date("Y-m-d H:i:s"));
                $Vaz->AdatBe("VZ_TABLA_S",$this->Tabla_nev);
                $Vaz->AdatBe("VZ_TABLA_AZON_I",$TABLA_AZON);
                $Vaz->AdatBe("VZ_OBJEKTUM_S",get_class($this));
                $Vaz->AdatBe("VZ_LISTA_S",$Lista);
                $Vaz->AdatBe("VZ_SORREND_I",$SORREND);
                $Vaz->AdatBe("VZ_SZINKRONIZALT_I",1);
                $Vaz->AdatBe("VZ_FELHASZNALO_I",$this->Sessad("Aktfelh")->AzonAd());
                $Vaz->AdatBe("VZ_MASOLAT_I",1);
                $Vaz->AdatBe("VZ_NYELV_S",$SzuloObj->Nyelvad());
                $Vaz->Szinkronizal();
                
                $Obj=$this->ObjektumLetrehoz($Vaz->AzonAd());
                $Obj->Defkulsolink();
                $Obj->Szinkronizal();       
        }

/**
* LinkLetezik  - létezik e link egy objektumhoz 
* @param $SzuloObj - szülőobjektum
* @param $Lista - nem kötelező, lista típus
* @return 0 vagy object 
*/

        function LinkLetezik($SzuloObj,$Lista="")
        {
                $ListaFelt=$this->_ListaSzetszedo($Lista);
                if (is_object($SzuloObj))
                {
                        $SZULO_AZON=$SzuloObj->AzonAd();
                        $SzuloFeltetel="VZ_SZULO_I='$SZULO_AZON' and";
                }
                else
                {
                        $SzuloFeltetel="";
                }
                $TABLA_AZON=$this->Vaz->AdatKi("VZ_TABLA_AZON_I");
                $TABLA=$this->Vaz->AdatKi("VZ_TABLA_S");
                $SQL="SELECT VZ_AZON_I from VAZ where $SzuloFeltetel VZ_TABLA_S='$TABLA' and VZ_TABLA_AZON_I='$TABLA_AZON' and VZ_MASOLAT_I='1' $ListaFelt";
                $Eredmeny=self::$Sql->Lekerst($SQL);
                if ($Eredmeny)
                {
                        $Eredmeny=$this->ObjektumLetrehoz($Eredmeny[0]["VZ_AZON_I"],0);
                }
                return $Eredmeny;
        }

        
/**
 * _EredetiAzon  - objektum azonosítója. Ha másolatról van szó, akkor a nem másolati objektum azonosítóját adja vissza. Gyerekeket nem másolathoz kötjük.
* @return int 
*/
        function _EredetiAzon()
        {
                if ($this->Vaz->AdatKi("VZ_MASOLAT_I"))
                {
                        $TABLA_AZON=$this->Vaz->AdatKi("VZ_TABLA_AZON_I");
                        $TABLA=$this->Vaz->AdatKi("VZ_TABLA_S");
                        $AZON=self::$Sql->Lekerst("select VZ_AZON_I from VAZ where VZ_TABLA_AZON_I='$TABLA_AZON' and VZ_TABLA_S='$TABLA' and VZ_MASOLAT_I=0");
                        if ($AZON)
                        {
                                $AZON=$AZON[0]["VZ_AZON_I"];
                        }else
                        {
                                $AZON=$this->Vaz->AdatKi("VZ_AZON_I");
                        }

                }
                else
                {
                        $AZON=$this->Vaz->AdatKi("VZ_AZON_I");
                }
                return $AZON;

        }
        
 /**
 * SorrendAd - VAZ sorrendet adja vissza
 */        
              
        function SorrendAd()
        {
                return $this->Vaz->AdatKi("VZ_SORREND_I");
        }        
 
 /**
 * Objtipusad - objektum típusát adja vissza VAZ táblából
 */       
              
        function Objtipusad()
        {
                return $this->Vaz->AdatKi("VZ_OBJEKTUM_S");
        }
        
        
        


                


 /**
 * Futtat - Futtatásra előkészít. 
 * Futtatásra példa: $this->Futtat(1)->UrlapKi_Fut($Param1,$Param2);
 * Figyelem sorrend két futas egymásba ágyazva esetén: első fut -> elindul második-> második vége-> első vége
 *         sorrend  két fofuto futás: első fut-> első vége, 2. fut -> 2. vége. A 2. futás elején még nem futtatjuk a feladatot, csak eltesszük és az első végén fogjuk futtatni (CFuttato -ba). 
 * A paramétereket nem lehet referenciaként megadni és max 7 paramétere lehet egy futtató feladatnak.  
 * @param $Mit - VAZ.VZ_AZON_I azonosító vagy objektum vagy tömb ami _Gyerekvissza bejövő paraméter lesz
 * ha tömb:  Használható erre a bejövő paraméterre Gyerekparam() függvény ahol felsorolással lehet megadni a paramétereket vagy tömb indexei ezek:
 *                 Lista: - VZ_LISTA listatípus 
 *                 Rendez - sorrend, adatbázis szintaktika pl NEV asc  
 *                 Urit - 0 vagy 1, állapotot ürítse vagy ne. Szabály: ha állandó design elemhez pl felső menü generálunk, akkor nem kell, különben igen.
 *                 Limit - hánytól hányig kérezzen le vesszővel elválasztva, pl 0,10  
 *                 Felt - feltétel, adatbázis szintaktika and -el kezdődjön. pl and NEV like '%aa%'. Használható hasonlításhoz self::$Sql->Konv_mez függvénye  
 *                 Nyil - 0 vagy 1. Nyilak kellenek e. Ha kell akkor objektum Nyilelotte,Nyilutana állapotába beállitja  előtte/utána lévő objektumot  
 *                 Egyt - 0 vagy 1, alapból 0. Egytáblás lekérdezés legyen e. Nem biztos hogy kelleni fog. Ha nagyobb táblából kérdezzük le a gyerekeket, tábla összekapcsolás nélkül csináljuk e
 *                  - talán gyorsabb mysqlnek. Ilyenkor figyelni arra hogy Felt és Rendez paraméterbe ne legyenek tábla specifikus dolgok.
 * ha a bejövő paraméter egy objektum akkor a meghívandó függvény visszatérés az objektum visszatérése, ha a bejövő tömb akkor visszamegy tömb: 
 *                                      ["Ossz"]= összes objektum száma amire végre kell hajtani a feladatot, pager miatt nem biztos hogy ez az objektumok száma
 *                                      ["Eredm"]= tömb, végrehajtott objektumonként függvény visszatérési értéke
 *                                      ["Pager"]= ha limit-el hívtuk és szükséges visszaad pager-t is html formába      
 * több info róla ld CFuttato osztály
 * @return object - CFuttato objektummal tér vissza  
 */
        public function Futtat($Mit)
        {
            $Nemfut=false;
            
            $Eltesz=false;
            $Futobjszam=$this->Kozad("Futobjszam",0);
            if($Futobjszam>0)$Eltesz=true;
            
            if ($Eltesz)
            {
                $Futtat=new CFuttato($Mit,$this);
            }else
            {
               $Futtat=$this->Futtat_betesz($Mit);
            }
            return $Futtat;
            
        }
        
        
/**
 * Futtatgy - Futtatásra előkészít gyerekeket. Futtat -ot hívja meg gyerekek futtatási paraméterével
 * @param $Lista - VZ_LISTA listatípus 
 * @param $Rendez - sorrend, adatbázis szintaktika pl NEV asc  
 * @param $Urit - 0 vagy 1, állapotot ürítse vagy ne. Szabály: ha állandó design elemhez pl felső menü generálunk, akkor nem kell, különben igen.
 * @param $Limit - hány elemet kérdezzen le pl 10  def: nincs -minden vissza. 
 *                 Ha adunk meg valamit, azt hogy honnan kérdezzen le, automatikus - pager generálja. Honnannev=futtató felada első 2. karatere+honnan  
 * @param $Felt - feltétel, adatbázis szintaktika and -el kezdődjön. pl and NEV like '%aa%'. Használható hasonlításhoz self::$Sql->Konv_mez függvénye  
 * @param $Nyil - 0 vagy 1. Nyilak kellenek e. Ha kell akkor objektum Nyilelotte,Nyilutana állapotába beállitja  előtte/utána lévő objektumot             
 * @param $Egyt - 0 vagy 1, alapból 0. Egytáblás lekérdezés legyen e. Nem biztos hogy kelleni fog. Ha nagyobb táblából kérdezzük le a gyerekeket, tábla összekapcsolás nélkül csináljuk e
 *  - talán gyorsabb mysqlnek. Ilyenkor figyelni arra hogy Felt és Rendez paraméterbe ne legyenek tábla specifikus dolgok. 
 *  visszamegy tömb:
 *                                      ["Ossz"]= összes objektum száma amire végre kell hajtani a feladatot, pager miatt nem biztos hogy ez az objektumok száma
 *                                      ["Eredm"]= tömb, végrehajtott objektumonként függvény visszatérési értéke      
 *                                      ["Pager"]= ha limit-el hívtuk és szükséges visszaad pager-t is html formába      
 * több info róla ld Futtat függvény ill CFuttato osztály
 * @return object - CFuttato objektummal tér vissza  
 */
        
        public function Futtatgy($Lista=null,$Rendez=null,$Urit=null,$Limit=null,$Felt=null,$Nyil=null,$Egyt=null)
        {
            return $this->Futtat($this->Gyerekparam($Lista,$Rendez,$Urit,$Limit,$Felt,$Nyil,$Egyt));
        }             
      
/**
* Futtat_betesz - Futtatásra előkészít. Létrehoz CFuttato objektumot, beleteszi a futtatandó objektumot/gyerekeket
* Futtatásra példa: $this->Futtat(1)->UrlapKi_Fut($Param1,$Param2);
* @return object - CFuttato objektummal tér vissza  
*/

        public function Futtat_betesz($Mit)
        {
                $Futtat=new CFuttato($this->Futtat_objad($Mit));
                return $Futtat;            
        }
        
/**
* Futtat_objad - CFuttato -nak létrehozza az objektumot vagy a gyerekeket
* @param $Mit - VAZ.VZ_AZON_I azonosító vagy objektum vagy tömb ami _Gyerekvissza bejövő paraméter lesz
*         ha tömb:  Használható erre a bejövő paraméterre Gyerekparam() függvény ahol felsorolással lehet megadni a paramétereket
* @return object vagy gyerekvissza paramétere a  
*/

        public function Futtat_objad($Mit)
        {
                if (is_array($Mit))
                {
                    $Vissza=$this->_Gyerekvissza($Mit);
                }else
                {
                    if (is_object($Mit))$Vissza=$Mit;
                                   else $Vissza=$this->ObjektumLetrehoz($Mit,0);
                }
                return $Vissza;            
        }        
          
          


 /**
 * Gyerekparam - Ha a _Gyerekvissza függvényhez a paraméterek átadása így könnyebb lenne nem asszociatív tömb formájába, hanem paraméterek felsorolása a függvényhez
 * @param $Lista - VZ_LISTA listatípus 
 * @param $Rendez - sorrend, adatbázis szintaktika pl NEV asc  
 * @param $Urit - 0 vagy 1, állapotot ürítse vagy ne. Szabály: ha állandó design elemhez pl felső menü generálunk, akkor nem kell, különben igen.
 * @parm  Limit - hány elemet kérdezzen le pl 10  def: nincs -minden vissza. 
 *                 Ha adunk meg valamit, azt hogy honnan kérdezzen le, automatikus - pager generálja. Honnannev=futtató felada első 2. karatere+honnan  
 * @param $Felt - feltétel, adatbázis szintaktika and -el kezdődjön. pl and NEV like '%aa%'. Használható hasonlításhoz self::$Sql->Konv_mez függvénye  
 * @param $Nyil - 0 vagy 1. Nyilak kellenek e. Ha kell akkor objektum Nyilelotte,Nyilutana állapotába beállitja  előtte/utána lévő objektumot             
 * @param $Egyt - 0 vagy 1, alapból 0. Egytáblás lekérdezés legyen e. Nem biztos hogy kelleni fog. Ha nagyobb táblából kérdezzük le a gyerekeket, tábla összekapcsolás nélkül csináljuk e
 *  - talán gyorsabb mysqlnek. Ilyenkor figyelni arra hogy Felt és Rendez paraméterbe ne legyenek tábla specifikus dolgok. 
 * @return array, amit _Gyerekvissza használ  
 */
        public function Gyerekparam($Lista=null,$Rendez=null,$Urit=null,$Limit=null,$Felt=null,$Nyil=null,$Egyt=null)
        {
            $Vissza=array();
            if ($Lista===null)$Vissza["Lista"]="";
                           else $Vissza["Lista"]=$Lista; 
            if ($Rendez===null)$Vissza["Rendez"]="VZ_SORREND_I asc";
                           else $Vissza["Rendez"]=$Rendez; 
            if ($Urit===null)$Vissza["Urit"]=0;
                           else $Vissza["Urit"]=$Urit; 
            if ($Limit===null)$Vissza["Limit"]="";
                           else $Vissza["Limit"]=$Limit; 
            if ($Felt===null)$Vissza["Felt"]="";
                           else $Vissza["Felt"]=$Felt; 
            if ($Nyil===null)$Vissza["Nyil"]=0;
                           else $Vissza["Nyil"]=$Nyil; 
            if ($Egyt===null)$Vissza["Egyt"]=0;
                           else $Vissza["Egyt"]=$Egyt; 
            

            return $Vissza;    
        }
        
 /**
 * _Gyerekvissza - Gyerekeket adja vissza 
 * @param $Param - array: paraméterek tömb formájában. Használható erre a bejövő paraméterre Gyerekparam() függvény ahol felsorolással lehet megadni a paramétereket
 *                 Lista: - VZ_LISTA listatípus def minden 
 *                 Rendez - sorrend, adatbázis szintaktika pl NEV asc. Def VZ_SORREND  
 *                 Urit - 0 vagy 1, def 0. állapotot ürítse vagy ne. Ha üríti akkor létrehozó be lesz állítva. Szabály: ha állandó design elemhez pl felső menü generálunk, akkor nem kell, ha listához amibe megjelenik linkként igen. 
 *                 Limit - hány elemet kérdezzen le pl 10  def: nincs -minden vissza. 
 *                 Ha adunk meg valamit, azt hogy honnan kérdezzen le, automatikus - pager generálja. Honnannev=futtató felada első 2. karatere+honnan  
 *                 Felt - feltétel, adatbázis szintaktika and -el kezdődjön. pl and NEV like '%aa%'. Használható hasonlításhoz self::$Sql->Konv_mez függvénye. Def nincs.  
 *                 Nyil - 0 vagy 1. Nyilak kellenek e. Ha kell akkor objektum Nyilelotte,Nyilutana állapotába beállitja  előtte/utána lévő objektumot. Def nincs  
 *                 Egyt - 0 vagy 1, def
  0. Egytáblás lekérdezés legyen e. Nem biztos hogy kelleni fog. Ha nagyobb táblából kérdezzük le a gyerekeket, tábla összekapcsolás nélkül csináljuk e
 *                  - talán gyorsabb mysqlnek. Ilyenkor figyelni arra hogy Felt és Rendez paraméterbe ne legyenek tábla specifikus dolgok. 
 * @return array: ["Ossz"]=összes találat száma
 *                ["Obj"]=létrehozott objektumok, limit-es lekérdezéskor nem ugyanaz mint Ossz
 *                ["Pager"]= ha limit-el hívtuk és szükséges visszaad pager-t is html formába   
 */         
        private function _Gyerekvissza($Param=array())
        {
         
         
                if (isset($Param["Lista"]))$Lista=$Param["Lista"];
                                      else $Lista="";
                if (isset($Param["Rendez"]))$Rendez=$Param["Rendez"];
                                      else $Rendez="VZ_SORREND_I asc";
                if (isset($Param["Urit"]))$Urit=$Param["Urit"];
                                      else $Urit=0;
                if (isset($Param["Limit"]))$Limit=$Param["Limit"];
                                      else $Limit="";
                if (isset($Param["Felt"]))$Felt=$Param["Felt"];
                                      else $Felt="";
                if (isset($Param["Nyil"]))$Nyil=$Param["Nyil"];
                                      else $Nyil=0;
                if (isset($Param["Egyt"]))$Egyt=$Param["Egyt"];
                                      else $Egyt=0;

                $Honnannev="";
                $Honnan=0;
                if ($this->Sessad("Aktfelh")->Jogosultsag()>99)$Nyil=1;
                
                if ($Limit!="")
                {
                    $Feladat=$this->Alap_fut_nevad();
                    $Honnannev="Honnan".mb_substr($Feladat,0,2,STRING_CODE);

                    $Honnan=$this->Postgetv_jegyez($Honnannev,0);                    

                    $Limitfelt="$Honnan,".$Limit;
                }else $Limitfelt="";
                $Listafeltetel=$this->_ListaSzetszedo($Lista);

                $AZON=$this->_EredetiAzon();

                $Vissza=array();
                $Szam=0;
                

                $Tablak=self::$Sql->Leker(array(
                "Mit"=>"VZ_TABLA_S",
                "Tabla"=>"VAZ",
                "Group"=>"VZ_TABLA_S",
                "Felt"=>"and VZ_SZULO_I=".self::$Sql->Konv_mez($AZON,"VZ_SZULO_I")." ".$Listafeltetel,
                ));
                if ($Tablak)
                {
                        $TablaDb=count($Tablak);
                        if ($TablaDb>1)
                        {
//                            if ($Limitfelt!="")die("Hiba, több táblás limit");    
                        }
                        if ($Egyt)
                        {
                            for ($c=0;$c<$TablaDb;$c++)
                            {

                                $Gyerekek=self::$Sql->Leker(array(
"Tabla"=>"VAZ ",
"Felt"=>"and VZ_SZULO_I=".self::$Sql->Konv_mez($AZON,"VZ_SZULO_I")." and VZ_SZINKRONIZALT_I=".self::$Sql->Konv_mez(1,"VZ_SZINKRONIZALT_I").$Listafeltetel.$Felt,
"Rendez"=>$Rendez,
"Limit"=>$Limitfelt 
)
);
                                if ($Limitfelt!="")
                                {
                                    $Egyszam=self::$Sql->Leker(array(
"Tabla"=>"VAZ ",
"Felt"=>"and VZ_SZULO_I=".self::$Sql->Konv_mez($AZON,"VZ_SZULO_I")." and VZ_SZINKRONIZALT_I=".self::$Sql->Konv_mez(1,"VZ_SZINKRONIZALT_I").$Listafeltetel.$Felt,
"Mit"=>"count(VZ_AZON_I) as db " 
)
);
                                    $Szam=$Szam+$Egyszam[0]["db"];
                                }else
                                {
                                    if ($Gyerekek)$Szam=$Szam+count($Gyerekek);
                                }
                                    if ($Gyerekek)
                                    {
                                        foreach ($Gyerekek as $EgyGyerek)
                                        {
                                            $Vissza[]=$this->ObjektumLetrehoz($EgyGyerek,$Urit);
                                        }
                                    }
                                    
                            }
                            
                        }else
                        {
                               for ($c=0;$c<$TablaDb;$c++)
                               {
                                        $Gyerekek=self::$Sql->Leker(array(
"Tabla"=>"VAZ inner join ".$Tablak[$c]["VZ_TABLA_S"]." on VAZ.VZ_TABLA_S=".self::$Sql->Konv_mez($Tablak[$c]["VZ_TABLA_S"],$Tablak[$c]["VZ_TABLA_S"])." and ".$Tablak[$c]["VZ_TABLA_S"].".AZON_I=VAZ.VZ_TABLA_AZON_I ",
"Felt"=>"and VZ_SZULO_I=".self::$Sql->Konv_mez($AZON,"VZ_SZULO_I")." and VZ_SZINKRONIZALT_I=".self::$Sql->Konv_mez(1,"VZ_SZINKRONIZALT_I").$Listafeltetel.$Felt,
"Rendez"=>$Rendez,
"Limit"=>$Limitfelt 
)
);
      
                                    if ($Limitfelt!="")
                                    {
                                        $Egyszam=self::$Sql->Leker(array(
"Tabla"=>"VAZ inner join ".$Tablak[$c]["VZ_TABLA_S"]." on VAZ.VZ_TABLA_S=".self::$Sql->Konv_mez($Tablak[$c]["VZ_TABLA_S"],$Tablak[$c]["VZ_TABLA_S"])." and ".$Tablak[$c]["VZ_TABLA_S"].".AZON_I=VAZ.VZ_TABLA_AZON_I ",
"Felt"=>"and VZ_SZULO_I=".self::$Sql->Konv_mez($AZON,"VZ_SZULO_I")." and VZ_SZINKRONIZALT_I=".self::$Sql->Konv_mez(1,"VZ_SZINKRONIZALT_I").$Listafeltetel.$Felt,
"Mit"=>"count(VZ_AZON_I) as db "
)
);
                                        $Szam=$Szam+$Egyszam[0]["db"];
                                    }else
                                    {
                                         if ($Gyerekek)$Szam=$Szam+count($Gyerekek);    
                                    }
                                    if ($Gyerekek)
                                    {
                                        foreach ($Gyerekek as $EgyGyerek)
                                        {
                                            $Vissza[]=$this->ObjektumLetrehoz($EgyGyerek,$Urit);
                                        }
                                    }
                                }
                            
                        }                                      
                       
                }
                $this->_Gyerekvisszanyilallit($Nyil,$Vissza);
                $Visszatomb["Obj"]=$Vissza;
                $Visszatomb["Ossz"]=$Szam;
                if ($Limitfelt!="")
                {
                    if ($Szam>$Limit)
                    {
                        $Pager=$this->Tordeles($Honnannev,$Honnan,$Szam,$Limit);
                    }else $Pager="";
                    
                }else $Pager=""; 
                $Visszatomb["Pager"]=$Pager;

                return $Visszatomb;
        }


 /**
 * _Gyerekvisszanyilallit - beállítja az átadott gyerek objektumokba nyilas rendezéshez előtte,utána lévő gyereket.
 * Ha kell akkor objektum Nyilelotte,Nyilutana állapotába beállitja  előtte/utána lévő objektumot.   
 * @param $Nyil - nyilas rendezés 0 v 1 
 * @param $Gyerekek - tömb objektumokkal, ez megy vissza is
 */
        private function _Gyerekvisszanyilallit($Nyil,&$Gyerekek)
        {
                if ($Nyil)
                {
                    
                    
                        $GyerekSzam=count($Gyerekek);
                        
                        for($i=0;$i<$GyerekSzam;$i++)
                        {
                                
                                if ($i==0)
                                {
                                        $Elotte=0;
                                }
                                else
                                {
                                        $Elotte=$Gyerekek[$i-1]->AzonAd();
                                }

                                if ($i==$GyerekSzam-1)
                                {
                                        $Utana=0;
                                }
                                else
                                {
                                        $Utana=$Gyerekek[$i+1]->AzonAd();
                                }
                                $Gyerekek[$i]->AllapotBe("Ujnyil",1);
                                $Gyerekek[$i]->AllapotBe("Nyilelotte",$Elotte);
                                $Gyerekek[$i]->AllapotBe("Nyilutana",$Utana);
                        }
                    
                }else
                {
                        $GyerekSzam=count($Gyerekek);                        
                        for($i=0;$i<$GyerekSzam;$i++)
                        {
                                $Gyerekek[$i]->AllapotBe("Nyilelotte",0);
                                $Gyerekek[$i]->AllapotBe("Nyilutana",0);
                                $Gyerekek[$i]->AllapotBe("Ujnyil",0);
                        }
                }
            
        }


 /**
 * _ListaSzetszedo - Sql feltételt csinál VZ_LISTA_S -re 
 * @param $Lista - Listanév, ! jellel elválasztva lehet több is
 * @return string Mysql feltétel 
 */
        private function _ListaSzetszedo($Lista)
        {
/*A Lista lehet !-el elválasztva több is, ha üres mindegyik megy vissza*/
                $Listafeltetel="";
                if ($Lista!="")
                {
                       $Listak=explode("!",$Lista);
                       if ($Listak)
                       {
                                $db=count($Listak);
                                $Listafeltetel=" and (";
                                for ($v=0;$v<$db;$v++)
                                {
                                        if ($v!=0)
                                        {
                                                $Listafeltetel.=" or ";
                                        }
                                        $Listafeltetel.="  (VZ_LISTA_S=".self::$Sql->Konv_mez($Listak[$v],"VZ_LISTA_S").") ";
                                }
                       $Listafeltetel.=")";
                       }
                }
                return $Listafeltetel;

        }        

       
       function Urlaplink(&$Form)
        {
                if ($this->_Kulsoelerlehet())
                {
                        if ($this->Vaz->AdatKi("VZ_KULSO_KELL_I"))$VZ_KULSO_KELL="checked";
                                                           else $VZ_KULSO_KELL="";
                        $Form->CheckBox("Külső elérésbe szerepel:","VZ_KULSO_KELL_I","",$VZ_KULSO_KELL);
                }
                $TAG0="";
                $TAG1="";
                $TAG2="";
                if($this->Szinkronizalte())
                {
                        $TAG0="checked";
                }else
                {
                        $TAG1="checked";
                }
                $Form->Szabad2("Külső link generálás:","<input type=radio name='LINKGENERAL' Id='LINKGENERAL' onclick=\"Linkgener();\" value='0' $TAG0>Marad a régi <input type=radio name='LINKGENERAL' Id='LINKGENERAL' value='1' $TAG1 onclick=\"Linkgener();\" > Generál <input type=radio name='LINKGENERAL' onclick=\"Linkgener();\" Id='LINKGENERAL' value='2' $TAG2> Én adom meg ");
                $Form->TextBox("Külső elérés /csak angol abc és számok lehetnek/:","VZ_KULSO_LINK_S",$this->Vaz->AdatKi("VZ_KULSO_LINK_S"),"");
                $this->ScriptTarol("
                function Linkgener()
                {
                        
                        ert=$('input[name=LINKGENERAL]:checked').val();
                        
                        if (ert=='1')
                        {
                            $('#trVZ_KULSO_LINK_S').hide();
                        }
                        else
                        {
                                $('#trVZ_KULSO_LINK_S').show();                               
                                
                                if (ert=='0')
                                {
                                    $('#VZ_KULSO_LINK_S').prop('disabled', true);
                                }else
                                {
                                    $('#VZ_KULSO_LINK_S').prop('disabled', false);
                                }
                        }
                }
                 $(document).ready(function() {
                Linkgener();
                })
                ");
        }




        


        function Urlaplinktarol()
        {
                $Vissza=true;
                $LINKGENERAL=$this->Postgetv("LINKGENERAL");
                $VZ_KULSO_LINK=$this->Postgetv("VZ_KULSO_LINK_S");


                $this->Vaz->AdatBe("VZ_KULSO_KELL_I",$this->Postgetv("VZ_KULSO_KELL_I",1));

                
                $Jo=true;
                if ("$LINKGENERAL"=="1")
                {
                    $this->Defkulsolink();

                }
                if ("$LINKGENERAL"=="2")
                {
                        $Lehet=$this->_Kulsolinkbe_lehet();
                        $VZ_KULSO_LINK=trim($VZ_KULSO_LINK);
                        $Hossz=strlen($VZ_KULSO_LINK);
                        $Jo=true;
                        for ($c=0;$c<$Hossz;$c++)
                        {
                                $Kar=substr($VZ_KULSO_LINK,$c,1);
                                $Temp=strpos($Lehet,$Kar);
                                if ($Temp===false)$Jo=false;

                        }
                        if ($VZ_KULSO_LINK=="")
                        {
                                $Vissza=false;
                                $this->ScriptUzenetAd("A külső link nem lehet üres!");
                        }else
                        if ($this->Voltilyenkulso($VZ_KULSO_LINK)!="0")
                        {
                                $Vissza=false;
                                $this->ScriptUzenetAd("Már van ilyen külső elérés, válasszon másikat!");
                        }else
                        if ($Jo)
                        {
                                $this->Vaz->AdatBe("VZ_KULSO_LINK_S",$VZ_KULSO_LINK);
                        }else
                        {
                                $this->ScriptUzenetAd("Külső elérésbe csak angol abc és számok lehetnek!");
                                $Vissza=false;
                        }
                        $this->Vaz->Szinkronizal();
                }
                return $Vissza;
        }

 /**
 * ListaAd - visszaadja lista típust - VZ_LISTA_S -t 
 * @return string  
 */
        public function ListaAd()
        {
                return $this->Vaz->AdatKi("VZ_LISTA_S");
        }
 
 /**
 * Fel_fut - nyilas sorrendnél felfele mozgatja az objektumot.   
 * @return string  
 */
        function Fel_fut()
        {
                $Elotte=$this->Postgetv("Mit");
                $ElotteObj=$this->ObjektumLetrehoz($Elotte);
                
                if (!($ElotteObj->Uresobjektum()))
                {
                        $ElotteSorrend=$ElotteObj->SorrendKi();
                        $ElotteObj->SorrendBe($this->SorrendKi());
                        $this->SorrendBe($ElotteSorrend);
                }
                return $this->VisszaLep();
        }

 /**
 * Le_fut - nyilas sorrendnél lelfele mozgatja az objektumot.   
 * @return string  
 */
        function Le_fut()
        {
                $Utana=$this->Postgetv("Mit");

                $UtanaObj=$this->ObjektumLetrehoz($Utana,0);
                
                if (!($UtanaObj->Uresobjektum()))
                {
                        $UtanaSorrend=$UtanaObj->SorrendKi();
                        $UtanaObj->SorrendBe($this->SorrendKi());
                        $this->SorrendBe($UtanaSorrend);
                }
                return $this->VisszaLep();
        }

 /**
 * SorrendBe - sorrendet tárolja - nyilas sorrend írhatja felül az alap sorrendet   
 */
        function SorrendBe($Sorrend)
        {
                $this->Vaz->AdatBe("VZ_SORREND_I",$Sorrend);
                $this->Vaz->Szinkronizal();
        }

 /**
 * SorrendKi - sorrendet adja vissza   
* @return int  
*/
        function SorrendKi()
        {
                return $this->Vaz->AdatKi("VZ_SORREND_I");
        }

/**
 * objektum nyelve
 * @return string
*/    
    public function Nyelvad()
    {
        return $this->Vaz->AdatKi("VZ_NYELV_S");
    }
    
 /**
 * Karbatarto - törli az 1 napnál régebbi nem szinkronizalt sorokat 
 */
        public function Karbatarto()
        {
                $Regiek=self::$Sql->Lekerst("select VZ_AZON_I from VAZ where (DATE_SUB(now(), INTERVAL '1' DAY)>VZ_KESZULT_D) and (VZ_SZINKRONIZALT_I=0) and VZ_KESZULT_D>'2002-12-12 12:12' limit 0,30");
                if ($Regiek)
                {
                        $db=count($Regiek);
                        for ($c=0;$c<$db;$c++)
                        {
                                $Regi=$this->ObjektumLetrehoz($Regiek[$c]["VZ_AZON_I"],0);
                                if ($Regi)
                                {
                                        $Regi->RekurzivTorol();
                                }
                                
                        }
                }

                                
                            

        }

 /**
 * Tablatarol - adatbázisba beírja a változásokat saját táblába. 
 */        
        public function Tablatarol()
        {
            if (is_object($this->Tabla))$this->Tabla->Szinkronizal();
            $this->Vaz->Szinkronizal();
            
        }
        
        
/**
 * Szinkronizált e az objektum
 * @return integer 
 */
        public function Szinkronizalte()
        {
            return $this->Vaz->AdatKi("VZ_SZINKRONIZALT_I");
        }
        

/**
 * _Masolate - az objekutm másolat e vagy nem
 * @return integer 
 */     
        public function _Masolate()
        {
                return $this->Vaz->AdatKi("VZ_MASOLAT_I");
        }        

       
/**
 * Kulsolinkkell - Külső linkbe megjelenik
 * @return integer 
 */         
        public function Kulsolinkkell()
        {
            if ($this->_Kulsoelerlehet())
            {
                $Vissza=$this->Vaz->AdatKi("VZ_KULSO_KELL_I");
            }
            else $Vissza=false;
            return $Vissza;
        }


/**
 * Kulsolinkad - Külső link
 * @return string 
 */         
        public function Kulsolinkad()
        {
            return $this->Vaz->AdatKi("VZ_KULSO_LINK_S");
        }



 /**
 * RekurzivTorol_seged - törli az objektumot. Ha másolat akkor csak a másolatot, eredeti objektum ilyenkor megmarad.
 */ 
        public function RekurzivTorol_seged()
        {
               if ($this->_Masolate())
               {
                    $this->Torolcsakvaz();
               }else
               {
                    $this->RekurzivTorol();
               }

        }
        
 /**
 * Torolcsakvaz - törli az objektumot VAZ táblából. Saját táblából még azért nem lehet, mert másolat esetén még létezhet másik VAZ tábla hozzá. 
 */ 
        public function Torolcsakvaz()
        {
            $this->Vaz->Torol();
        }        
              
 /**
 * RekurzivTorol - törli teljesen az objektumot. Gyerekei közötti szabály: ha a gyerek másolat, akkor csak a másolatot, ha a gyerek nem másolat akkor teljes törlés 
 */                
        function RekurzivTorol()
        {
                $Eredm=$this->Futtat($this->Gyerekparam("",null,0,"200"))->RekurzivTorol_seged();
                if ($Eredm["Ossz"]-200>0)$Ossz=$Eredm["Ossz"]-200;
                                    else $Ossz=0;
              
                while ($Ossz>0)
                {
                    $Eredm=$this->Futtat($this->Gyerekparam("",null,0,"200"))->RekurzivTorol_seged();
                    if ($Eredm["Ossz"]-200>0)$Ossz=$Eredm["Ossz"]-200;
                                        else $Ossz=0;
                }
                $AZON=$this->TablaAdatKi("AZON_I");
                $Tabla_nev=$this->Tabla_nev;
                
                $Azonok=self::$Sql->Lekerst("select VZ_AZON_I from VAZ where VZ_TABLA_AZON_I='$AZON' and VZ_TABLA_S='$Tabla_nev' ");
                if ($Azonok)
                {
                    foreach ($Azonok as $egy)
                    {
                        $this->Futtat($egy["VZ_AZON_I"])->Torolcsakvaz();
                    }
                }
                $this->Tabla->Torol();
        }


 /**
 * Torol_fut - törli az objektumot és visszalép. Hogy van e jogunk törölni, Hozzafer_torol függvény adja vissza.   
 */                
   
        public function Torol_fut()
        {
                if (!($this->Hozzafer_torol()))return CVaz::AlapFeladat();


                $this->RekurzivTorol();
                
                $Letrehozo=$this->Letrehozoobj();
                

                return $this->VisszaLep();
        }

        function MaxSorszam()
        {
                $AZON=$this->Vaz->AdatKi("VZ_AZON_I");
                $MaxEredmeny=self::$Sql->Lekerst("SELECT MAX(VZ_SORREND_I)AS SORREND from VAZ where VZ_SZULO_I='$AZON'");
                $Max=$MaxEredmeny[0]["SORREND"];
                return $Max;
        }

 /**
 * AzonAd - objektum VAZ azonosítóját adja vissza
 *@return int   
 */ 
        function AzonAd()
        {
            
            $AZON=$this->Vaz->AzonAd();
            return $AZON;
        }



 /**
 * SzuloObjektum - visszaadja a szülőobjektumot
 * @return object 
 */  
        function SzuloObjektum()
        {
                $SZULO=$this->Vaz->AdatKi("VZ_SZULO_I");
                $SzuloObjektum=$this->ObjektumLetrehoz($SZULO);
                return $SzuloObjektum;
        }
        
        function EredSzuloObjektum()
        {
                $SZULO=$this->Vaz->AdatKi("VZ_SZULO_I");
                $SzuloObjektum=$this->ObjektumLetrehoz($SZULO);
                
                $Erazon=$SzuloObjektum->_EredetiAzon();
                if ("$Erazon"!="$SZULO")$SzuloObjektum=$this->ObjektumLetrehoz($Erazon);
                
                return $SzuloObjektum;
        }
        
        

 /**
 * SzuloObjektumok - visszaadja a szülőobjektumokat tömbbe
 * @return array of object 
 */  
        function SzuloObjektumok()
        {
            $Vissza=array();
            $Vissza[]=$this;

            if ($this->AzonAd()!=Focsop_azon)
            {
                $Szulo=$this->SzuloObjektum();
                $Vissza=array_merge($Vissza,$Szulo->SzuloObjektumok());
            }
            return $Vissza;
        }

 /**
 * LetrehozoBe - beállítja a létrehozót, a létrehozo az előzőleg futó objektum lesz
 * @return object 
 */
        function LetrehozoBe($Obj)
        {
                return $this->AllapotBe("Letrehozo",$Obj->AzonAd());
        }

 /**
 * Letrehozoobj - visszaadja a létrehozó objektumot, ha nincs ilyen mainobjektumot
 * @return object 
 */
        function Letrehozoobj()
        {
            $Letrehozo=$this->AllapotKi("Letrehozo");
            $LetrehozoObjektum=$this->ObjektumLetrehoz($Letrehozo,0);
            
            
            if ($LetrehozoObjektum->Uresobjektum())
            {
                $LetrehozoObjektum=$this->ObjektumLetrehoz(Focsop_azon,0);
            }
            return $LetrehozoObjektum;
        }
        
 /**
 * Letrehozovane - van e létrehozó objektum 
 * @return 0 vagy 1  
 */
        function Letrehozovane()
        {
            $Letrehozo=$this->AllapotKi("Letrehozo");
            if (($Letrehozo!="")&&($Letrehozo!="0"))$Vissza=true;
                else $Vissza=false;
            return $Vissza;
        }        


 /**
 * VisszaEsemenyAd - visszalépő url linket ad vissza
 * @return string 
 */
        public function VisszaEsemenyAd()
        {
                $Letrehozo=$this->Letrehozoobj();
                
                $Eredmeny=$Letrehozo->EsemenyHozzad("Alap_fut");
                
                return $Eredmeny;
        }

               
 /**
 * VisszaLep - visszalép. Létrehozo objektumot létrehozza és alapfeladatatát futtatja
 * @return alapfeladat visszatérésével 
 */ 
        public function VisszaLep()
        {
                $Letrehozo=$this->Letrehozoobj();
                return $this->Futtat($Letrehozo)->Alap_fut();
        }
        



 /**
 * EsemenyHozzad - url-t generál. $Def_feladat nem jelenik meg az url-be, ha függvény nélkül hívunk valamit, $Def_feladat fut le.
 * Mivel csak olyan feladat futhat aminek a vége _fut, ezért a függvény az $Feladat -ot kiegészíti _fut -al ha nem lenne  
 * @param $Feladat - futtatandó feladat
 * @return string 
 */        
        public function EsemenyHozzad($Feladat="")
        {
               if ($Feladat!="")
               {
                if (mb_substr($Feladat,mb_strlen($Feladat)-4,4,STRING_CODE)!="_fut")$Feladat.="_fut";
                
               } 
               if (("$Feladat"==$this->Def_feladat))$Feladat="";
               
               
                $VZ_KULSO_LINK=$this->Kulsolinkad();
                if ($this->AzonAd()!=Focsop_azon)
                {
                        $Szulo=$this->SzuloObjektum();
                        
                        
                        if ($Szulo->Uresobjektum())
                        {
                            
                            $Szulo=$this;
                            $SZ_VZ_KULSO_KELL=$this->Kulsolinkkell();
                            $SZ_VZ_KULSO_LINK=$this->Kulsolinkad();
                            
                        }else
                        {
                            $SZ_VZ_KULSO_KELL=$Szulo->Kulsolinkkell();
                            $SZ_VZ_KULSO_LINK=$Szulo->Kulsolinkad();
                        }
                        
                }
                else
                {
                        $Szulo=$this;
                        $SZ_VZ_KULSO_KELL=$this->Kulsolinkkell();
                        $SZ_VZ_KULSO_LINK=$this->Kulsolinkad();
                }
                
                $Vissza="/".$VZ_KULSO_LINK;
                if ($Feladat!="")$Vissza.=ESEMVALASZTO.$Feladat;
                $Vissza.=".html";
                
                $ind=0;
                
                
                while (($Szulo->AzonAd()!=Focsop_azon)&&($ind<2))
                {
                        if ($SZ_VZ_KULSO_KELL)$Link=$SZ_VZ_KULSO_LINK;
                                else $Link="";
                        if ($Link!="")
                        {
                                $Vissza="/$Link$Vissza";
                                $ind++;
                        }
                        $Szulo=$Szulo->SzuloObjektum();
                        
                        if ($Szulo->Uresobjektum())
                        {
                            $ind=10;
                            $Szulo=$this->ObjektumLetrehoz(Focsop_azon,0);    
                        }else
                        {

                            $SZ_VZ_KULSO_KELL=$Szulo->Kulsolinkkell();
                            $SZ_VZ_KULSO_LINK=$Szulo->Kulsolinkad();
                        }
                }
                
                return $Vissza;
        }

 /**
 * EsemenyFelold - url-ből objektumot és feladatot generál. Url általános kinézete pl: Fo-Mutat_pb_fut
 * Ha az url-be nincs futtatandó az $Def_feladat ot adja vissza
 * @param $Esemeny_Id: url string 
 * @return array: Objazon: objektum azonosító
 *                Feladat: feladat 
 */
        function EsemenyFelold($Esemeny_Id)
        {
                $Darab=explode("/",$_SERVER["DOCUMENT_ROOT"]);
                $Utols=array_pop($Darab);

                $Mit[]="/^$Utols\//";
                $Mire[]="";
                $Esemeny_Id=preg_replace($Mit,$Mire,$Esemeny_Id);
                $Esemeny_Id=str_replace(".html","",$Esemeny_Id);

                $Dbok=explode("/", $Esemeny_Id);
                $Eredmeny["Objazon"]=0;
                $Eredmeny["Feladat"]="";
                foreach ($Dbok as $Egyut)
                {
                        $Adat=explode(ESEMVALASZTO,$Egyut);
                        $Azon=$Adat[0];
                        if (isset($Adat[1]))$Feladat=$Adat[1];
                                else
                                {
                                        $Feladat="";
                                }
                        if (trim($Azon)!="")
                        {
                            if($Feladat=="")$Feladat=$this->Def_feladat;
                            $Objazon=$this->Voltilyenkulso($Azon,true);

                            if ($Objazon!="0")
                            {
                                $Eredmeny["Objazon"]=$Objazon;
                                $Eredmeny["Feladat"]=$Feladat;
                            }
                        }

                }
                return $Eredmeny;

        }


 /**
 * Keszult - Mikor készült az objektum
 * @return datetime 
 */        function Keszult()
        {
                $KESZULT=$this->Vaz->AdatKi("VZ_KESZULT_D");
                return $KESZULT;
        }


 /**
 * Postgetv_jegyez - bejövő paramétert url/formból ezen keresztül veszünk át és jegyzünk meg az objektumhoz
 * @param $Valtozo - formba lévő bekérő neve
 * @return string 
 */
    
        public function Postgetv_jegyez($Valtozonev,$Def="",$Kod=0)
        {
                $Adat=$this->Postgetv("$Valtozonev");
                if (!(isset($Adat)))
                {
                        $AZON=$this->_Allapotazon();
                        $Tomb=$this->Sessadtomb($AZON,$Valtozonev);
                        if ($Tomb["Van"]=="0")
                        {
                                if (("$Def"!=""))
                                {
                                        $Adat=$Def;
                                }else $Adat="";
                        }else $Adat=$Tomb["Ertek"];


                }else if ($Kod)$Adat=base64_decode($Adat);
                $this->AllapotBe($Valtozonev,$Adat);
                return $Adat;
        }
        
      
 /**
 * _Allapotazon - objektum állapotát milyen session indexen tárolja azt adja vissza
 * @return string 
 */        
        private function _Allapotazon()
        {
                $AZON=$this->AzonAd();
                return "Objall".$AZON;
        }      

 /**
 * AllapotBe - objektumhoz eltárol egy paramétert sessionbe
 * @param $ParameterNev - paraméter neve
 * @param $ParameterErtek - paraméter értéke
 */        
        public function AllapotBe($ParameterNev,$ParameterErtek)
        {
                if (is_object($ParameterErtek))$ParameterErtek=$this->Objclone($ParameterErtek);

                $AZON=$this->_Allapotazon();
                $this->Sessbetomb($AZON,$ParameterErtek,$ParameterNev);

        }

 /**
 * AllapotUrit - objektumhoz állapotát törli
 */        
        public function AllapotUrit()
        {
                $AZON=$this->_Allapotazon();
                $this->Sessbe($AZON,"null");               
        }

 /**
 * AllapotKi - objektumhoz visszaad egy paramétert
 * @param $ParameterNev - paraméter neve
 * @return paraméter értéke
 */        
        public function AllapotKi($ParameterNev)
        {
                $AZON=$this->_Allapotazon();
                $Tomb=$this->Sessadtomb($AZON,$ParameterNev);
                if ($Tomb["Van"]=="0")$Vissza=false;
                                 else $Vissza=$Tomb["Ertek"];
                
                return $Vissza;
        }
      


 /**
 * Tombevan - objektum az átadott tömbbe van e
 * @param $Tomb tömb objektumokkal
 * @return 0 vagy 1
 */  
        public function Tombevane($Tomb)
        {
                $Vissza=0;
                if ($Tomb)
                {
                        $db=count($Tomb);
                        for ($c=0;$c<$db;$c++)
                        {
                                $Vissza=$Vissza||$this->Egyezike($Tomb[$c]);
                        }
                }
                return $Vissza;
        }

        
 /**
 * TablaAdatKi  - visszaad táblából egy mező értékét
 * @param $Mit - mező neve 
 */ 
        function TablaAdatKi($Mit)
        {
                return $this->Tabla->AdatKi($Mit);
        }


 /**
 * TablaAdatBe - objektum saját táblájába elhelyez egy értéket. Adatbázisba kiirás csak futás végén
 * @param $Mibe - mező neve
 * @param $Mit - mező értéke
 */ 
        function TablaAdatBe($Mibe,$Mit)
        {
                $this->Tabla->AdatBe($Mibe,$Mit);
        }
        
/**
 * Vanilyenmezo  - Visszaadja hogy létezik e a táblához ilyen mező. Kereső szavak generáláshoz használatos 
* @param $Mezonev - mező neve 
* @return boolean 0 v 1  
*/          
        public function Vanilyenmezo($Mezonev)
        {
            return $this->Tabla->Vanilyenmezo($Mezonev);
        }


 /**
 * TablaSzinkronizal  - Szinkronizált bitet beállítja az objektumhoz és tárolja az adatokat 
 */ 
        function Szinkronizal()
        {
                $VZ_SZINKRONIZALT=$this->Vaz->AdatKi("VZ_SZINKRONIZALT_I");
                if ($VZ_SZINKRONIZALT!="1")$this->Vaz->AdatBe("VZ_SZINKRONIZALT_I",1);
                if (is_object($this->Tabla))$this->Tabla->Szinkronizal();
                $this->Vaz->Szinkronizal();
        }
        

/**
* Alap_fut_nevad  - Az objektum utoljára futtatott metódusának a nevét adja vissza, vagy ha még olyan nem volt akkor Def_feladat -ot. 
* Alap_fut függvény fogja az itt visszaadott metódust futtatni.     
* @return string - metódus neve
*/
        final public function Alap_fut_nevad()
        {
                $Feladat=$this->Futottfeladat();
                //$Futotte=$this->Kozadtomb("Futottmar",$this->AzonAd());

                if (($Feladat!="")&&($Feladat!="Alap_fut"))
                {                    
                    $Vissza=$Feladat;
                }
                else
                {
                    $Vissza=$this->Def_feladat;
                }
                
                return $Vissza;            
        }
        

/**
* Alap_fut  - Alap_fut_nevad által visszaadott metódust futtatja. Használatos pl: más objektumba visszatéréskor vagy esetleg pagernél   
*/
        final public function Alap_fut()
        {
                $Feladat=$this->Alap_fut_nevad();
//                $this->Kozbetomb("Futottmar",1,$this->AzonAd());
                $Vissza=$this->$Feladat();
                return $Vissza;            
        }
                
        function DebugErtekekBe($Lista,$Obj)
        {
                $this->Vaz->AdatBe("VZ_LISTA_S",$Lista);
                $this->Vaz->AdatBe("VZ_OBJEKTUM_S",$Obj);
                $this->Vaz->Szinkronizal();
        }



        function DebugBekero()
        {
                $Tarol=$this->EsemenyHozzad("DebugUjObjektum");
                $Tartalom=$this->DebugForm($Tarol,"","");
                return $Tartalom;
        }




        


        function EddigiElertUt($Ut,$Db=0)
        {
                $Volt=$this->Tombevane($Ut);

                if (($Volt))return $Ut;

                $MagaTomb[]=$this;
                $Ut=array_merge($MagaTomb,$Ut);
                $Db++;
                if (($this->AzonAd()!=1)&&($Db<20))
                {
//                        $Letrehozo=$this->LetrehozoObjektum();
                        $Letrehozo=$this->SzuloObjektum();
                        return $Letrehozo->EddigiElertUt($Ut,$Db);
                }
                else
                {
                        return $Ut;
                }

        }

/**
 * Futottfeladat - az objektum utoljára futott feladata
 * @return string 
 */     
   
        public function Futottfeladat()
        {
                return $this->AllapotKi("Feladat");
        }
     
        

/**
 * Futottfeladatbe - futtato objektum hívja, beállítja az utoljára futott feladatot. 
 */     
   
        public function Futottfeladatbe($Feladat)
        {
                $this->AllapotBe("Feladat",$Feladat);
//                $this->Kozbetomb("Futottmar",1,$this->AzonAd());
        }        






        
/**
 * AdatokMasol - objektum saját táblájának összes adatát másolja egy másik objektumból. Csak ugyanolyan tábla objektumokra lehet.      
 * @param $MinekObj objektum amit le akarunk másolni 
*/         
        public function AdatokMasol($MinekObj)
        {
            $this->Tabla->Lemasol($MinekObj->Tabla); 
            $this->Szinkronizal();           
        }


/**
 * ObjektumLemasol - Lemásol egy objektumot az objektumba, minden gyerekével.     
 * @param $Mitmasol objektum amit le akarunk másolni 
 * @return object másolt objektum 
 */ 
        public function ObjektumLemasol($Mitmasol)
        {
                if (!($this->_Masolate()))
                {
                    $Masolt=$this->UjObjektumLetrehoz($Mitmasol->Objtipusad(),$Mitmasol->ListaAd("VZ_LISTA"));
                    
                    $Masolt->Futtatgy()->RekurzivTorol();
                    
                    
                    
                    $Masolt->Vaz->AdatBe("VZ_KULSO_KELL_I",$Mitmasol->Kulsolinkkell());                
                    $Masolt->AdatokMasol($Mitmasol);
                    $Masolt->Defkulsolink();
                    

                    $Mitmasol->Futtatgy(null,null,null,null," and VZ_AZON_I<>'".$Masolt->AzonAd()."'")->ObjektumLemasol_gyerek($Masolt);
                }else $Masolt=$this;   
                return $Masolt;


        }
        
        
        function ObjektumLemasol_gyerek($Szuloobj)
        {
            return $Szuloobj->ObjektumLemasol($this);    
        }

/**
* OsszesTablaAdatVissza - objektum saját táblájának összes adatát adja vissza tömbként       
* @return array  
*/
        function OsszesTablaAdatVissza()
        {
                $Vissza=$this->Tabla->OsszesAdatVissza();
                return $Vissza;
        }


 /**
 * Athelyez - áthelyezi az objektumot másik szülő alá
 * @param $SzuloObj - objektum, ahová át akarjuk helyezni
 */  
        function Athelyez($SzuloObj)
        {
                $Szulo=$SzuloObj->AzonAd();
                $SORREND=$SzuloObj->MaxSorszam()+1;
                $this->Vaz->AdatBe("VZ_SZULO_I",$Szulo);
                $this->Vaz->AdatBe("VZ_SORREND_I",$SORREND);
                $this->Vaz->AdatBe("VZ_NYELV_S",$SzuloObj->Nyelvad());

                $this->Vaz->Szinkronizal();
                $this->LetrehozoBe($SzuloObj);

        }
        





  



 /**
 * Masolatokad - Visszaadja a másolatokat az objektumból
 * @return 0 vagy tömb objektumokkal   
 */
        public function Masolatokad()
        {
                $TABLA_AZON=$this->Vaz->AdatKi("VZ_TABLA_AZON_I");
                $TABLA=$this->Vaz->AdatKi("VZ_TABLA_S");
                $SQL="SELECT VZ_AZON_I from VAZ where  VZ_TABLA_S='$TABLA' and VZ_TABLA_AZON_I='$TABLA_AZON' and VZ_MASOLAT_I='1'";
                $Eredmeny=self::$Sql->Lekerst($SQL);
                $Vissza=false;
                if ($Eredmeny)
                {
                        foreach ($Eredmeny as $Egy)
                        {
                                $Vissza[]=$this->ObjektumLetrehoz($Egy["VZ_AZON_I"],0);
                        }
                }
                return $Vissza;
        }



 

 /**
 * getClassHierarchy - visszaadja az objektum hierarchiáját tömbbe - objektum osztála, szülő osztálya
 * @return array   
 */
 
    private function getClassHierarchy() 
    {
        $object=$this;        
        $hierarchy = array();
        $class = get_class($object);
        do 
        {
            $hierarchy[] = $class;
        } while (($class = get_parent_class($class)) !== false);
        return $hierarchy;
    }


 /**
 * Sablonbe - Betölt egy sablont, betöltendő sablon metódus: Sablon_+metódus neve, sablonba lévő sablont is betölti.
 * Sablon osztály: objektum osztálya(felmegy szülő osztályokon is)+_sablon, ha nincs akkor CSablon osztályba. 
 * @param $Sabnev sablon neve
 * @param $Data array sablon adatok
 * @return string html   
 */ 
        public function Sablonbe($Sabnev,$Data)
        {


            
            $Objnev="";
            $Objhier=$this->getClassHierarchy($this);

            foreach ($Objhier as $class)
            {
                if ($Objnev=="")
                {
                    $Sabosztlesz=$class."_sablon";
                    if (class_exists($Sabosztlesz))$Objnev=$Sabosztlesz;
                }    
            }
            
            
            if ($Objnev=="") 
            {
                $Objnev="CSablon";
            }     
      
            $Obj=new $Objnev();
            $Vissza=$Obj->$Sabnev($Data);
            
            preg_match_all('/\[\[\[sablon (?s).*\]\]\]/sUim',$Vissza,$SzovegSzet);
            if ($SzovegSzet)
            {
                $SzovegSzet=$SzovegSzet[0];
                foreach ($SzovegSzet as $Sablonnev)
                {
                    $Erednev=$Sablonnev;
                    $Sablonnev=str_replace("[[[sablon ","",$Sablonnev);
                    $Sablonnev=str_replace("]]]","",$Sablonnev);
                    $Sablontart=$this->Sablonbe($Sablonnev,$this->Sablondef($Sablonnev));
                    $Vissza=str_replace($Erednev,$Sablontart,$Vissza);
                }
            }

            
            return $Vissza;
        }
        
}
?>
