<?php

/**
 * rhTools
 * 
 * @author Ralf Hertsch (ralf.hertsch@phpmanufaktur.de)
 * @link http://phpmanufaktur.de
 * @copyright 2007 - 2011
 * @license GNU GPL (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: include.php 6 2011-07-19 16:16:32Z phpmanufaktur $
 * 
 * FOR VERSION- AND RELEASE NOTES PLEASE LOOK AT INFO.TXT!
 */

// try to include LEPTON class.secure.php to protect this file and the whole CMS!
if (defined('WB_PATH')) {	
	if (defined('LEPTON_VERSION')) include(WB_PATH.'/framework/class.secure.php');
} elseif (file_exists($_SERVER['DOCUMENT_ROOT'].'/framework/class.secure.php')) {
	include($_SERVER['DOCUMENT_ROOT'].'/framework/class.secure.php'); 
} else {
	$subs = explode('/', dirname($_SERVER['SCRIPT_NAME']));	$dir = $_SERVER['DOCUMENT_ROOT'];
	$inc = false;
	foreach ($subs as $sub) {
		if (empty($sub)) continue; $dir .= '/'.$sub;
		if (file_exists($dir.'/framework/class.secure.php')) { 
			include($dir.'/framework/class.secure.php'); $inc = true;	break; 
		} 
	}
	if (!$inc) trigger_error(sprintf("[ <b>%s</b> ] Can't include LEPTON class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
}
// end include LEPTON class.secure.php

if(!file_exists(WB_PATH .'/modules/'.basename(dirname(__FILE__)).'/languages/' .LANGUAGE .'.php')) {
  require_once(WB_PATH .'/modules/'.basename(dirname(__FILE__)).'/languages/EN.php'); }
else {
  require_once(WB_PATH .'/modules/'.basename(dirname(__FILE__)).'/languages/' .LANGUAGE .'.php'); }

// DIE if php Version is wrong
if (version_compare(PHP_VERSION, '5.2.0') === -1) {
  die("Sorry, but this module needs at minimum PHP 5.2.0, you are using PHP ".PHP_VERSION.". Programm execution stopped."); }

if (!defined('tools_error_link_by_page_id')) define('tools_error_link_by_page_id', "Cant't get the filename for page ID <strong>%d</strong> from WB Settings");
if (!defined('tools_error_link_row_empty')) define('tools_error_link_row_empty', "There exists no entry for page ID <strong>%d</strong> in WB Settings");

require_once(WB_PATH. '/framework/functions.php');
require_once(WB_PATH. '/framework/functions-utf8.php');

if (!class_exists('rhTools')) {

	class rhTools {

	  const   	unkownUser = 'UNKNOWN USER';
	  const   	unknownEMail = 'unknown@user.tld';
	  private 	$error;

		protected $namedHTMLEntities = array(
			'£' => '&pound;',
			'¤' => '&curren;',
			'¥' => '&yen;',
			'€' => '&euro;',
			'¦' => '&brvbar;',
			'§' => '&sect;',
			'¨' => '&uml;',
			'©' => '&copy;',
			'ª' => '&ordf;',
			'«' => '&laquo;',
			'¬' => '&not;',
			'­' => '&shy;',
			'®' => '&reg;',
			'¯' => '&macr;',
			'°' => '&deg;',
			'±' => '&plusmn;',
			'²' => '&sup2;',
			'³' => '&sup3;',
			'´' => '&acute;',
			'µ' => '&micro;',
			'¶' => '&para;',
			'·' => '&middot;',
			'¸' => '&cedil;',
			'¹' => '&sup1;',
			'º' => '&ordm;',
			'»' => '&raquo;',
			'¼' => '&frac14;',
			'½' => '&frac12;',
			'¾' => '&frac34;',
			'¿' => '&iquest;',
			'À' => '&Agrave;',
			'Á' => '&Aacute;',
			'Â' => '&Acirc;',
			'Ã' => '&Atilde;',
			'Ä' => '&Auml;',
			'Å' => '&Aring;',
			'Æ' => '&AElig;',
			'Ç' => '&Ccedil;',
			'È' => '&Egrave;',
			'É' => '&Eacute;',
			'Ê' => '&Eirc;',
			'Ë' => '&Euml;',
			'Ì' => '&Igrave;',
			'Í' => '&Iacute;',
			'Î' => '&Icirc;',
			'Ï' => '&Iuml;',
			'Ð' => '&ETH;',
			'Ñ' => '&Ntilde;',
			'Ò' => '&Ograve;',
			'Ó' => '&Oacute;',
			'Ô' => '&Ocirc;',
			'Õ' => '&Otilde;',
			'Ö' => '&Ouml;',
			'×' => '&times;',
			'Ø' => '&Oslash;',
			'Ù' => '&Ugrave;',
			'Ú' => '&Uacute;',
			'Û' => '&Ucirc;',
			'Ü' => '&Uuml;',
			'Ý' => '&Yacute;',
			'Þ' => '&Thorn;',
			'ß' => '&szlig;',
			'à' => '&agrave;',
			'á' => '&aacute;',
			'â' => '&acirc;',
			'ã' => '&atilde;',
			'ä' => '&auml;',
			'å' => '&aring;',
			'æ' => '&aelig;',
			'ç' => '&ccedil;',
			'è' => '&egrave;',
			'é' => '&eacute;',
			'ê' => '&ecirc;',
			'ë' => '&euml;',
			'ì' => '&igrave;',
			'í' => '&iacute;',
			'î' => '&icirc;',
			'ï' => '&iuml;',
			'ð' => '&eth;',
			'ñ' => '&ntilde;',
			'ò' => '&ograve;',
			'ó' => '&oacute;',
			'ô' => '&ocirc;',
			'õ' => '&otilde;',
			'ö' => '&ouml;',
			'÷' => '&divide;',
			'ø' => '&oslash;',
			'ù' => '&ugrave;',
			'ú' => '&uacute;',
			'û' => '&ucirc;',
			'ü' => '&uuml;',
			'ý' => '&yacute;',
			'þ' => '&thorn;',
			'ÿ' => '&yuml;',
			'š' => '&scaron;',
			'Š' => '&Scaron;',
	  );
	  
	  /**
	   * Konstruktor
	   *
	   */
	  public function __construct() {
	    $this->error = '';
	  }

	  /**
	   * Setzt einen Fehler
	   *
	   * @param STR $error
	   */
	  public function setError($error) {
	    $this->error = $error;
	  }

	  /**
	   * Gibt einen gesetzten Fehler zurueck
	   *
	   * @return STR
	   */
	  public function getError() {
	    return $this->error;
	  }

	  /**
	   * Fehlerstatus, gibt TRUE zurueck wenn ein Fehler gesetzt ist
	   *
	   * @return BOOL
	   */
	  public function isError() {
	    return (bool) !empty($this->error);  }

	  /**
	   * Return Version of class rhTools
	   *
	   * @return FLOAT
	   */
	  public function getVersion() {
	    // read info.php into array
	    $info_text = file(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/info.php');
	    if ($info_text == false) {
	      return -1; }
	    // walk through array
	    foreach ($info_text as $item) {
	      if (strpos($item, '$module_version') !== false) {
	        // split string $module_version
	        $value = split('=', $item);
	        // return floatval
	        return floatval(ereg_replace('([\'";,\(\)[:space:][:alpha:]])', '', $value[1])); } }
	    return -1;
	  }

	  /**
	   * Ueberprueft ob das Frontend aktiv ist
	   *
	   * @return BOOLEAN
	   */
	  public function isFrontendActive() {
	  	// Wenn Session Variable gesetzt ist, diese verwenden...
	  	if (isset($_SESSION['FRONTEND'])) {
	  		$_SESSION['FRONTEND'] == 'active' ? $result = true : $result = false; 	}
	  	else {
	  	  // Ansonsten wird geprueft ob die class admin geladen ist.
	  	  // Methode hat den Haken, dass sie nicht zuverlaessig funktioniert,
	  	  // wenn der angemeldete User gleichzeitig das Front- und Backend
	  	  // geoeffnet hat.
	    	$classes = get_declared_classes();
	    	in_array('admin', $classes) ? $result = false : $result = true; }
	    return $result;
	  }

	  /**
	   * Ueberprueft, ob der User angemeldet ist
	   *
	   * @return BOOL
	   */
	  public function isAuthenticated() {
	  	global $wb;
	  	global $admin;
	  	if ($this->isFrontendActive()) {
	  		return (bool) $wb->is_authenticated(); 	}
	  	else {
	  		return (bool) $admin->is_authenticated(); 	}
	  } // isAuthenticated()

	  /**
	   * Liest die WB Einstellungen aus und gibt sie in $settings zurueck
	   *
	   * @param REFERENCE &$settings
	   * @return BOOLEAN
	   */
	  public function getWBSettings(&$settings) {
	    global $database ;
	    global $sql_result ;
	    $settings = array();
	    $thisQuery = "SELECT * FROM " . TABLE_PREFIX . "settings" ;
	    $oldErrorReporting = error_reporting(0) ;
	    $sql_result = $database->query($thisQuery) ;
	    error_reporting($oldErrorReporting) ;
	    if($database->is_error()) {
	      $this->error = sprintf('[%s - %s] SETTINGS: %s', __METHOD__, __LINE__, $database->get_error());
	      return false ;  }
	    else {
	      for($i = 0 ; $i < $sql_result->numRows() ; $i ++) {
	        $dummy = $sql_result->fetchRow() ;
	        $settings[$dummy['name']] = $dummy['value'] ; }
	      return true;
	    }
	  } // getWBSettings

	  /**
	   * Ermittelt den Username im Front- oder Backend
	   *
	   * @return STRING
	   */
	  public function getUsername() {
	    global $wb;
	    global $admin;
	    if ($this->isFrontendActive()) {
	      if ($wb->is_authenticated()) {
	        return $wb->get_username();  }
	      else {
	        return self::unkownUser; }}
	    else {
	      if ($admin->is_authenticated()) {
	        return $admin->get_username(); }
	      else {
	        return self::unkownUser; }}
	  }

	  /**
	   * Gibt den Anzeigenamen des momentanen angemeldeten Benutzer
	   * im Front- und Backend zurueck
	   *
	   * @return STR
	   */
	  public function getDisplayName() {
	    global $wb;
	    global $admin;
	    if (is_object($wb)) {
	    	if ($wb->is_authenticated()) {
	        return $wb->get_display_name();  }
	      else {
	        return self::unkownUser; 
	      }
	    }
	    elseif (is_object($admin)) {
	    	if ($admin->is_authenticated()) {
	        return $admin->get_display_name(); }
	      else {
	        return self::unkownUser;
	      }
	    } 
	    else {
	    	return self::unkownUser;
	    }
	    /*
	    if ($this->isFrontendActive()) {
	      if ($wb->is_authenticated()) {
	        return $wb->get_display_name();  }
	      else {
	        return self::unkownUser; }}
	    else {
	      if ($admin->is_authenticated()) {
	        return $admin->get_display_name(); }
	      else {
	        return self::unkownUser; }}
	    */
	  } // getDisplayName()

	  /**
	   * Gibt die E-Mail Adresse des angemeldeten Users zurueck
	   *
	   * @return STR
	   */
	  public function getUserEMail() {
	    global $wb;
	    global $admin;
	    if ($this->isFrontendActive()) {
	      if ($wb->is_authenticated()) {
	        return $wb->get_email(); }
	      else {
	        return self::unknownEMail; }}
	    else {
	      if ($admin->is_authenticated()) {
	        return $admin->get_email(); }
	      else {
	        return self::unknownEMail; }}
	  } // getUserEMail()

	  /**
	   * Korrigiert Backslashs in Slashs um z.B. aus einer Windows
	   * Pfadangabe eine Linux taugliche Pfadangabe zu erstellen
	   *
	   * @param STR $this_path
	   * @return STR
	   */
	  public function correctBackslashToSlash($path) {
	  	return trim(str_replace("\\", "/", $path));
	  }

	  /**
	   * Haengt einen Slash an das Ende des uebergebenen Strings
	   * wenn das letzte Zeichen noch kein Slash ist
	   *
	   * @param STR $path
	   * @return STR
	   */
	  public function addSlash($path) {
	  	$path = substr($path, strlen($path)-1, 1) == "/" ? $path : $path."/";
	  	return $path;  }

	  public function removeLeadingSlash($path) {
	  	$path = substr($path, 0, 1) == "/" ? substr($path, 1, strlen($path)) : $path;
	  	return $path;
	  }

	  /**
	   * Gibt den vollstaendigen Pfad auf das /MEDIA Verzeichnis mit
	   * abschliessendem Slash zurueck
	   *
	   * @return STR
	   */
	  public function getMediaPath() {
	  	$result = $this->addSlash(WB_PATH) . $this->removeLeadingSlash($this->addSlash(MEDIA_DIRECTORY));
	  	return $result;
	  }

	  /**
	   * Ermittelt den Realname Link einer Seite an Hand der $page_id
	   *
	   * @param INT $pageID
	   * @param REFERENCE &$fileName
	   * @return BOOLEAN
	   */
	  public function getFileNameByPageID($pageID, &$fileName) {
	    global $database ;
	    global $sql_result ;
	    $fileName = 'ERROR';
	    $settings = array();
	    if (!$this->getWBSettings($settings)) return false;
	    $thisQuery = "SELECT * FROM " . TABLE_PREFIX . "pages WHERE page_id='$pageID'" ;
	    $oldErrorReporting = error_reporting(0) ;
	    $sql_result = $database->query($thisQuery) ;
	    error_reporting($oldErrorReporting) ;
	    if($database->is_error()) {
	      $this->error = sprintf('[%s - %s] PAGES: %s', __METHOD__, __LINE__, $database->get_error());
	      return false;  }
	    elseif ($sql_result->numRows() > 0) {
	      // alles OK, Daten uebernehmen
	      $thisArr = $sql_result->fetchRow() ;
	      if(is_file(WB_PATH . $settings['pages_directory'] . $thisArr['link'] . $settings['page_extension'])) {
	        // $fileName = basename($thisArr['link'] . $settings['page_extension']);
	        $fileName = $this->removeLeadingSlash($thisArr['link'] . $settings['page_extension']);
	        return true ; }
	      else {
	        $this->error = sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(ps_error_link_by_page_id, $pageID));
	        return false ; }}
	    else {
	      // keine Daten
	      $this->error = sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(tools_error_link_row_empty, $pageID));
	      return false ;  }
	  } // getFileNameByPageID

	  /**
	   * Ermittelt die URL einer Seite an Hand der $page_id
	   *
	   * @param INT $pageID
	   * @param REFERENCE $url
	   * @return BOOLEAN
	   */
	  public function getUrlByPageID($pageID, &$url) {
	    if (!$this->getFileNameByPageID($pageID, $url)) return false;
	    $url = WB_URL. PAGES_DIRECTORY. '/'.$url;
	    return true;
	  }

	  /**
	   * Erzeugt einen Link fuer die als page_id angegebene Seite in Abhaengigkeit
	   * davon ob die Funktion im Frontend oder im Backend aufgerufen wird.
	   * Parameter werden als Array in der Form 'KEY' => 'VALUE' uebergeben.
	   *
	   * @param INT $pageID
	   * @param REFERENCE $link
	   * @param ARRAY $params
	   * @return BOOLEAN
	   */
	  public function getPageLinkByPageID($pageID, &$link, $params=array()) {
	    $link = '';
	    if ($this->isFrontendActive()) {
	      // Link fuer Frontend erzeugen
	      if (!$this->getUrlByPageID($pageID, $link)) return false;
	      $start = true;
	      foreach ($params as $key => $value) {
	      	if ($start) {
	      	  $start = false;
	      	  $link .= "?$key=$value"; }
	      	else {
	      	  $link .= "&$key=$value"; }}
	    }
	    else {
	      // Link fuer Backend erzeugen
	      $link = WB_URL.'/admin/pages/modify.php?page_id='.$pageID;
	      foreach ($params as $key => $value) {
	      	$link .= "&$key=$value"; }
	    }
	    return true;
	  } // getPageLinkByPageID

	  /**
	   * Generiert ein neues Passwort der Laenge $length
	   *
	   * @param INT $length
	   * @return STR
	   */
	  public function generatePassword($length=7) {
			$new_pass = '';
			$salt = 'abcdefghjkmnpqrstuvwxyz123456789';
			srand((double)microtime()*1000000);
			$i=0;
			while ($i <= $length) {
				$num = rand() % 33;
				$tmp = substr($salt, $num, 1);
				$new_pass = $new_pass . $tmp;
				$i++; }
			return $new_pass;
	  } // generatePassword()

	  /**
	   * Wandelt einen String in einen Float Wert um.
	   * Geht davon aus, dass Dezimalzahlen mit ',' und nicht mit '.'
	   * eingegeben wurden.
	   *
	   * @param STR $string
	   * @return FLOAT
	   */
	  public function str2float($string) {
	  	$string = str_replace('.', '', $string);
			$string = str_replace(',', '.', $string);
			$float = floatval($string);
			return $float;
	  }

	  public function str2int($string) {
	  	$string = str_replace('.', '', $string);
			$string = str_replace(',', '.', $string);
			$int = intval($string);
			return $int;
	  }

	  /**
	   * Wandelt HTML Entities wie z.B. &auml; in Zeichen zurueck
	   *
	   * @param STR $string
	   * @return STR
	   */
		public function htmlEntities2char($string) {
			foreach ($this->namedHTMLEntities as $key => $value) {
				$string = str_replace($value, $key, $string);	}
			return $string;
		}

		/**
		 * Wandelt Sonderzeichen in HTML Entities um
		 *
		 * @param STR $string
		 * @return STR
		 */
		public function char2htmlEntities($string) {
			foreach ($this->namedHTMLEntities as $key => $value) {
				$string = str_replace($key, $value, $string);
			}
			return $string;
		}

		/**
		 * Ueberprueft die uebergebene E-Mail Adresse auf logische Gueltigkeit
		 *
		 * @param STR $email
		 * @return BOOL
		 */
		public function validateEMail($email) {
			//if(eregi("^([0-9a-zA-Z]+[-._+&])*[0-9a-zA-Z]+@([-0-9a-zA-Z]+[.])+[a-zA-Z]{2,6}$", $email)) {
			// PHP 5.3 compatibility - eregi is deprecated
			if(preg_match("/^([0-9a-zA-Z]+[-._+&])*[0-9a-zA-Z]+@([-0-9a-zA-Z]+[.])+[a-zA-Z]{2,6}$/i", $email)) {
				return true; }
			else {
				return false; }
		}

		/**
		 * Formatiert einen BYTE Wert in einen lesbaren Wert und gibt
		 * einen Byte, KB, MB oder GB String zurueck
		 *
		 * @param INT $byte
		 * @return STR
		 */
		public function bytes2Str($byte) {
	    if ($byte < 1024) {
	      $ergebnis = round($byte, 2). ' Byte'; }
	    elseif ($byte >= 1024 and $byte < pow(1024, 2)) {
	      $ergebnis = round($byte/1024, 2).' KB'; }
	    elseif ($byte >= pow(1024, 2) and $byte < pow(1024, 3)) {
	      $ergebnis = round($byte/pow(1024, 2), 2).' MB'; }
	    elseif ($byte >= pow(1024, 3) and $byte < pow(1024, 4)) {
	      $ergebnis = round($byte/pow(1024, 3), 2).' GB'; }
	    elseif ($byte >= pow(1024, 4) and $byte < pow(1024, 5)) {
	      $ergebnis = round($byte/pow(1024, 4), 2).' TB'; }
	    elseif ($byte >= pow(1024, 5) and $byte < pow(1024, 6)) {
	      $ergebnis = round($byte/pow(1024, 5), 2).' PB'; }
	    elseif ($byte >= pow(1024, 6) and $byte < pow(1024, 7)) {
	      $ergebnis = round($byte/pow(1024, 6), 2).' EB';  }
	    return $ergebnis;
	  } // bytes2Str()

	  /**
	   * Wandelt einen Dateinamen der Sonderzeichen, Umlaute und/oder
	   * Leerzeichen enthaelt in einen Linux faehigen Dateinamen um
	   *
	   * @param STR $string
	   * @return STR
	   */
	  public function cleanFileName($string) {
	  	//init utf8-functions
	    if (WB_VERSION < 2.8) {
	    	init_utf8funcs();
	    }
	    $string = entities_to_7bit($string);
	    // Now replace spaces with page spcacer
	    $string = trim($string);
	    $string = preg_replace('/(\s)+/', '_', $string);
	    // Now remove all bad characters
	    $bad = array(
	    '\'', /* /  */ '"', /* " */ '<', /* < */  '>', /* > */
	    '{', /* { */  '}', /* } */  '[', /* [ */  ']', /* ] */  '`', /* ` */
	    '!', /* ! */  '@', /* @ */  '#', /* # */  '$', /* $ */  '%', /* % */
	    '^', /* ^ */  '&', /* & */  '*', /* * */  '(', /* ( */  ')', /* ) */
	    '=', /* = */  '+', /* + */  '|', /* | */  '/', /* / */  '\\', /* \ */
	    ';', /* ; */  ':', /* : */  ',', /* , */  '?' /* ? */
	    );
	    $string = str_replace($bad, '', $string);
	    // Now convert to lower-case
	    $string = strtolower($string);
	    // If there are any weird language characters, this will protect us against possible problems they could cause
	    $string = str_replace(array('%2F', '%'), array('/', ''), urlencode($string));
	    // Finally, return the cleaned string
	    return $string;
	  } // cleanFileName

	  /**
	   * Generate a globally unique identifier (GUID)
	   * Uses COM extension under Windows otherwise
	   * create a random GUID in the same style
	   */
	  public function createGUID() {
	    if (function_exists('com_create_guid')){
        $guid = com_create_guid();
        $guid = strtolower($guid);
        if (strpos($guid, '{') == 0) {
        	$guid = substr($guid, 1); 
        }
        if (strpos($guid, '}') == strlen($guid)-1) {
        	$guid = substr($guid, 0, strlen($guid)-1);
        }
        return $guid;
	    }
      else {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
        mt_rand( 0, 0x0fff ) | 0x4000,
        mt_rand( 0, 0x3fff ) | 0x8000,
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ) );
      }
	  } // createGUID()

	  /**
	   * Remove the directory $target and all content
	   *
	   * @param STR $target
	   *
	   * @return BOOL
		 */
	  public function removeDirectory($target) {
	    // is a file specified?
      if (is_file($target)) {
        if (is_writable($target)) {
          if (@unlink($target)) {
            return true;
          }
        }
        return false;
      }
      // target is directory?
      if (is_dir($target)) {
        if (is_writeable($target)) {
          foreach (new DirectoryIterator($target) as $_res) {
            if ($_res->isDot()) {
              unset($_res);
              continue;
            }
            if ($_res->isFile()) {
              $this->removeDirectory($_res->getPathName());
            }
            elseif ($_res->isDir()) {
              $this->removeDirectory($_res->getRealPath());
            }
            unset($_res);
          } // foreach
          if (@rmdir($target)) {
            return true;
          }
        }
        return false;
      } // is_dir()
	  } // removeDirectory()

		/**
		 * Find files at the $location with the extension $fileregex
		 * and return them as array
		 * Expl.: findfile(WB_PATH, $fileregex='/\.(php|inc)$/')
		 * returns all files with extension *.php and *.inc
		 *
		 * @param STR $location
		 * @param STR $fileregex
		 *
		 * @return ARRAY
		 */
	  public function findfile($location='',$fileregex='') {
      if (!$location or !is_dir($location) or !$fileregex) {
         return false;
      }
      $matchedfiles = array();
      $all = opendir($location);
      while (false !== ($file = readdir($all))) {
        if (is_dir($location.'/'.$file) and $file <> ".." and $file <> ".") {
          $subdir_matches = $this->findfile($location.'/'.$file,$fileregex);
          $matchedfiles = array_merge($matchedfiles,$subdir_matches);
          unset($file);
        }
        elseif (!is_dir($location.'/'.$file)) {
          if (preg_match($fileregex,$file)) {
            array_push($matchedfiles,$location.'/'.$file);
          }
        }
      }
      closedir($all);
      unset($all);
      return $matchedfiles;
    } // findFile()

    /**
     * Return a String with $maxLength, cutting with respecting space
     * between words
     *
     * @param STR $string
     * @param INT $maxLength
     * @param BOOL $space
     *
     * @return STR
		 */
  	public function strCutting($string, $maxLength, $space=true)  {
      if (strlen($string) > $maxLength) {
        $result = "";
        $string = substr($string, 0, $maxLength-4);
        if ($space) {
          $words = split(" ",$string);
          for ($i=0; $i < count($words)-1; $i++) {
            $result .= $words[$i]." ";
          }
        }
        else {
          $result = $string;
        }
        return $result."...";
      }
      else {
        return $string;
      }
    }

	} // class rhTools

} // class_exists('rhTools')


if (!class_exists('tParser')) {

	class tParser {

	  private $data;
	  private $html;

	  private $namedHTMLEntities = array(
			'ï¿½' => '&pound;',
			'ï¿½' => '&curren;',
			'ï¿½' => '&yen;',
			'ï¿½' => '&euro;',
			'ï¿½' => '&brvbar;',
			'ï¿½' => '&sect;',
			'ï¿½' => '&uml;',
			'ï¿½' => '&copy;',
			'ï¿½' => '&ordf;',
			'ï¿½' => '&laquo;',
			'ï¿½' => '&not;',
			'ï¿½' => '&shy;',
			'ï¿½' => '&reg;',
			'ï¿½' => '&macr;',
			'ï¿½' => '&deg;',
			'ï¿½' => '&plusmn;',
			'ï¿½' => '&sup2;',
			'ï¿½' => '&sup3;',
			'ï¿½' => '&acute;',
			'ï¿½' => '&micro;',
			'ï¿½' => '&para;',
			'ï¿½' => '&middot;',
			'ï¿½' => '&cedil;',
			'ï¿½' => '&sup1;',
			'ï¿½' => '&ordm;',
			'ï¿½' => '&raquo;',
			'ï¿½' => '&frac14;',
			'ï¿½' => '&frac12;',
			'ï¿½' => '&frac34;',
			'ï¿½' => '&iquest;',
			'ï¿½' => '&Agrave;',
			'ï¿½' => '&Aacute;',
			'ï¿½' => '&Acirc;',
			'ï¿½' => '&Auml;',
			'ï¿½' => '&Atilde;',
	    'ï¿½' => '&Aring;',
			'ï¿½' => '&AElig;',
			'ï¿½' => '&Ccedil;',
			'ï¿½' => '&Egrave;',
			'ï¿½' => '&Eacute;',
			'ï¿½' => '&Eirc;',
			'ï¿½' => '&Euml;',
			'ï¿½' => '&Igrave;',
			'ï¿½' => '&Iacute;',
			'ï¿½' => '&Icirc;',
			'ï¿½' => '&Iuml;',
			'ï¿½' => '&ETH;',
			'ï¿½' => '&Ntilde;',
			'ï¿½' => '&Ograve;',
			'ï¿½' => '&Oacute;',
			'ï¿½' => '&Ocirc;',
			'ï¿½' => '&Otilde;',
			'ï¿½' => '&Ouml;',
			'ï¿½' => '&times;',
			'ï¿½' => '&Oslash;',
			'ï¿½' => '&Ugrave;',
			'ï¿½' => '&Uacute;',
			'ï¿½' => '&Ucirc;',
			'ï¿½' => '&Uuml;',
			'ï¿½' => '&Yacute;',
			'ï¿½' => '&Thorn;',
			'ï¿½' => '&szlig;',
			'ï¿½' => '&agrave;',
			'ï¿½' => '&aacute;',
			'ï¿½' => '&acirc;',
			'ï¿½' => '&atilde;',
			'ï¿½' => '&auml;',
			'ï¿½' => '&aring;',
			'ï¿½' => '&aelig;',
			'ï¿½' => '&ccedil;',
			'ï¿½' => '&egrave;',
			'ï¿½' => '&eacute;',
			'ï¿½' => '&ecirc;',
			'ï¿½' => '&euml;',
			'ï¿½' => '&igrave;',
			'ï¿½' => '&iacute;',
			'ï¿½' => '&icirc;',
			'ï¿½' => '&iuml;',
			'ï¿½' => '&eth;',
			'ï¿½' => '&ntilde;',
			'ï¿½' => '&ograve;',
			'ï¿½' => '&oacute;',
			'ï¿½' => '&ocirc;',
			'ï¿½' => '&otilde;',
			'ï¿½' => '&ouml;',
			'ï¿½' => '&divide;',
			'ï¿½' => '&oslash;',
			'ï¿½' => '&ugrave;',
			'ï¿½' => '&uacute;',
			'ï¿½' => '&ucirc;',
			'ï¿½' => '&uuml;',
			'ï¿½' => '&yacute;',
			'ï¿½' => '&thorn;',
			'ï¿½' => '&yuml;',
			'ï¿½' => '&scaron;',
			'ï¿½' => '&Scaron;',
	  );


	  /**
	   * Constructor
	   *
	   */
	  public function __construct() {
	    $this->data = array();
	    $this->html = "";
	  }

 	  /**
	   * Return Version of class rhTools
	   *
	   * @return FLOAT
	   */
	  public function getVersion() {
	    // read info.php into array
	    $info_text = file(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/info.php');
	    if ($info_text == false) {
	      return -1; }
	    // walk through array
	    foreach ($info_text as $item) {
	      if (strpos($item, '$module_version') !== false) {
	        // split string $module_version
	        $value = split('=', $item);
	        // return floatval
	        return floatval(ereg_replace('([\'";,\(\)[:space:][:alpha:]])', '', $value[1])); } }
	    return -1;
	  }

	  /**
	   * Parses a template file and return the parsed file
	   *
	   * @param STRING $templateFile
	   * @return STRING
	   */
	  public function parseTemplateFile($templateFile) {
	    $fileData = file_get_contents($templateFile);
	    return $this->parseTemplateData($fileData);
	  }

	  /**
	   * Parses template data and return the parsed data
	   *
	   * @param STRING $templateData
	   * @return STRING
	   */
	  public function parseTemplateData($templateData) {
	    $searchPattern          = "/\{([a-zA-Z0-9_]+)}/i";
	    $replacementFunction    = array(&$this, 'parseMatchedText');
	    $this->html         		= preg_replace_callback($searchPattern, $replacementFunction, $templateData);
	    return $this->html;
	  }

	  /**
	   * Callback function that returns value of a matching macro
	   *
	   * @param ARRAY $matches
	   * @return STRING
	   */
	  private function parseMatchedText($matches) {
	  	if (isset($this->data[$matches[1]])) {
	    	return $this->data[$matches[1]]; }
	    else {
	    	return sprintf("{%s}", $matches[1]);
	    }
	  }

	  /**
	   * Encode special chars with html entities
	   *
	   * @param REFERENCE STR &$string
	   * @return STR
	   */
	  private function encodeSpecialChars(&$string) {
			foreach ($this->namedHTMLEntities as $key => $value) {
				$string = str_replace($key, $value, $string);
			}
			return $string;
	  } // encodeSpecialChars()

	  /**
	   * Add a KEY/VALUE pair to the data array
	   *
	   * @param STRING $key
	   * @param STRING $value
	   * @param BOOL $encode
	   */
	  public function add($key, $value, $encode=true, $utf8_decode=true) {
	  	if (($utf8_decode == true) && (utf8_check($value) == true)) {
	  		$value = utf8_decode($value); }
	  	if ($encode === true) {
	  		$this->data[$key] = $this->encodeSpecialChars($value); }
	  	else {
	  		$this->data[$key] = $value; }
	  }

	  /**
	   * Delete KEY from data array
	   *
	   * @param STRING $key
	   */
	  public function delete($key) {
	    unset($this->data[$key]);
	  }

	  /**
	   * Clear data array
	   *
	   * @param BOOL $resetHTML
	   */
	  public function clear($resetHTML=false) {
	  	$this->data = array();
	    if ($resetHTML) unset($this->html);
	  }

	  /**
	   * echo's the parsed html string
	   *
	   * @param BOOL $resetHTML
	   */
	  public function echoHTML($resetHTML=true) {
	  	echo $this->html;
	    if ($resetHTML) unset($this->html);
	  }

	  /**
	   * Return the parsed html string
	   *
	   * @param BOOL $resetHTML
	   * @return STRING
	   */
	  public function getHTML($resetHTML=true) {
	   	$result = $this->html;
	    if ($resetHTML) unset($this->html);
	    return $result;
	  }

	}	 // class tParser

} // class_existe('tParser')
?>