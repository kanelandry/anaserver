<?php
class AnaServer {

    private $language	= 'en';
	public  $results    = array();
	private $allowed    = "/[^a-z0-9]/i";
	
	public function __construct($language){
		$this->language = $language;
	}
	
	public function find($str, $lin){
		$regex = $this->regExp($str);
		$tmp = strtolower(preg_replace($this->allowed,"",trim($lin)));
        //echo $str." = ".$tmp;
	    if (preg_match($regex, $tmp) && (strlen($str) == strlen($tmp)))
		{ array_push($this->results,array(0=>$lin,1=>levenshtein($str, $tmp)));}
    }
    
	public function __($str) {	
		try{
			$str= preg_replace($this->allowed,"",trim($str));
			if (!array_key_exists($this->language, $this->lang)) {
	 
				$lines = @file('data_'.$this->language.'.txt',FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
				foreach ($lines as $line){
					$string = strtolower($str);
					$this->find($string, $line);
				}
			}
			return $this->results;
		}
		catch(Exception $e){
	        return array(0 => "Couldn't read the dictionary");
	    }
   }
    
   private static function regExp($string){
	  $regchars = preg_split('//',$string, -1, PREG_SPLIT_NO_EMPTY); 
	  $regex = "/";
	  foreach($regchars as $char) $regex .= "(?=.*".$char.")";
	   return $regex."/";
   }

}
?>
