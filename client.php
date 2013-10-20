<?php
    error_reporting(0);
    require_once("class.anaserver.php");
    $lan = array('english'=>'en', 'french'=>'fr');
    $actions = array(0=>'search', 1=>'add');
    
    $en_matcher = new AnaServer($lan['english']); //- for english anagrams
    $fr_matcher = new AnaServer($lan['french']); //- for french anagrams
    
    //-trial 1
    if(isset($_GET["q"])) { 
		$word = $_GET["q"];
		echo "<strong>English</strong><br>";
		$matchs = $en_matcher->__($word);display($matchs);
		echo "<br><strong>French</strong><br>";
		$matchs = $fr_matcher->__($word);display($matchs);
    }
    
    //-trial 2
    $valid = "/^[0-9A-Za-z ,.'-]+$/i";
    if(isset($_POST['name']) && preg_match($valid, $_POST['name']) === 1 && in_array($_POST['action'],$actions) && in_array($_POST['dico'],$lan)) {
		$word = $_POST['name'];
		$a = $_POST['action'];
		$d = $_POST['dico'];
		if($a == "search"){
		  $matchs = $d=="en"?$en_matcher->__($word):$fr_matcher->__($word);
		  display($matchs);
		}
		else { 
		    $dictionary = 'data_'.$d.'.txt';
		    $dico_words = file($dictionary);
		    if(!in_array($word, $dico_words)){
				$word = "\n".$word;
				$fh = fopen($dictionary, 'a') or die();
				if(fwrite($fh, $word)) echo "<br>Done! Thanks for your contribution"; else die("bad!");
				fclose($fh);
			}
			else echo "<br>This word/name already exists in our ".array_search ($d, $lan)." dictionary: Try another word";
		}
	}
		
	function display($entries){
	  if(count($entries)>0) {
		foreach($entries as $e) echo $e[0]." - edit distance:".$e[1]."<br>";
	  }
	  else echo "No match was found<br>";
	}
?>
