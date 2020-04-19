<?php
/** 
 * A WordPress fő konfigurációs állománya
 *
 * Ebben a fájlban a következő beállításokat lehet megtenni: MySQL beállítások
 * tábla előtagok, titkos kulcsok, a WordPress nyelve, és ABSPATH.
 * További információ a fájl lehetséges opcióiról angolul itt található:
 * {@link http://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php} 
 *  A MySQL beállításokat a szolgáltatónktól kell kérni.
 *
 * Ebből a fájlból készül el a telepítési folyamat közben a wp-config.php
 * állomány. Nem kötelező a webes telepítés használata, elegendő átnevezni 
 * "wp-config.php" névre, és kitölteni az értékeket.
 *
 * @package WordPress
 */

// ** MySQL beállítások - Ezeket a szolgálatótól lehet beszerezni ** //
/** Adatbázis neve */
define('DB_NAME', 'c5116beerdb');

/** MySQL felhasználónév */
define('DB_USER', 'c5116beerjuzer');

/** MySQL jelszó. */
define('DB_PASSWORD', 'W7XmR89Nz9YKa9gU');

/** MySQL  kiszolgáló neve */
define('DB_HOST', 'localhost');

/** Az adatbázis karakter kódolása */
define('DB_CHARSET', 'utf8mb4');

/** Az adatbázis egybevetése */
define('DB_COLLATE', '');


  define('FORCE_SSL_ADMIN', true);


/**#@+
 * Bejelentkezést tikosító kulcsok
 *
 * Változtassuk meg a lenti konstansok értékét egy-egy tetszóleges mondatra.
 * Generálhatunk is ilyen kulcsokat a {@link http://api.wordpress.org/secret-key/1.1/ WordPress.org titkos kulcs szolgáltatásával}
 * Ezeknek a kulcsoknak a módosításával bármikor kiléptethető az összes bejelentkezett felhasználó az oldalról. 
 *
 * @since 2.6.0
 */
define('AUTH_KEY', '8eB4C-)UBB;)sT0`L8PCr-bWeztz=L=BL*2OS675nT.=XFT#Yx+NRW-hMph#VXa~');
define('SECURE_AUTH_KEY', 'rS3NpL@YV/2,+bd7G~8*>Y5lwj+0i[aPbnB$}vsvH]msJNo=bo)}Sy>h}}{&`OF/');
define('LOGGED_IN_KEY', '8Fe/&J,<r6+0 8 9^XV`02)mON`]0?t,,yW[lOZ.Gc7Tk(j9Vw)f&]1u[v}Ryt>e');
define('NONCE_KEY', 'u,rMgq3OQ >>w``;~eErD)95)LO64(PF/xqEN0($i1nY*n1OlDHWeuJ [v-oo@(2');
define('AUTH_SALT',        '?Qgc(fgm^0w#X5N :cI0/K.EH;}_Vs,R(D-bLDLOKB`2]QL@Ex>bzS%S?qr@^<Zi');
define('SECURE_AUTH_SALT', '&eLun+g};Vp;;O;H>+of3cFmL-1G0K~WOccWU(;Cce@nMEDJ|u?e`y}_f/&GH)Bd');
define('LOGGED_IN_SALT',   '$]F#K<}r5jz+Fbak5a?8529{m+[ :GmEgrF -#S^*{Qn[B%3GnTb!.jH9EqxLT/~');
define('NONCE_SALT',       'T[}@tEsM<9QSAK8Xd=%u*9*C,Go/d3d7nzgcL3/P=)8.mly+G5/!Q tf8]1|q4J8');

/**#@-*/

/**
 * WordPress-adatbázis tábla előtag.
 *
 * Több blogot is telepíthetünk egy adatbázisba, ha valamennyinek egyedi
 * előtagot adunk. Csak számokat, betűket és alulvonásokat adhatunk meg.
 */
$table_prefix  = 'beerside_';

/**
 * Fejlesztőknek: WordPress hibakereső mód.
 *
 * Engedélyezzük ezt a megjegyzések megjelenítéséhez a fejlesztés során. 
 * Erősen ajánlott, hogy a bővítmény- és sablonfejlesztők használják a WP_DEBUG
 * konstansot.
 */
define('WP_DEBUG', false);

/* Ennyi volt, kellemes blogolást! */

/** A WordPress könyvtár abszolút elérési útja. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Betöltjük a WordPress változókat és szükséges fájlokat. */
require_once(ABSPATH . 'wp-settings.php');
