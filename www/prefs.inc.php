<?php
	function getPrefs(){
		$prefs=array();
		if(isset($_COOKIE['cGraphs']))
			$prefs['cGraphs']=explode("|",$_COOKIE['cGraphs']);
		if(isset($_COOKIE['tz']))
			$prefs['tz']=explode("|",$_COOKIE['tz']);
		if(isset($_COOKIE['gs']))
			$prefs['gs']=explode("|",$_COOKIE['gs']);
		if(isset($_COOKIE['graphPrefs']))
			$prefs['graphPrefs']=explode("|",$_COOKIE['graphPrefs']);
		return $prefs;
	}
	function getGS(){
		$prefs=getPrefs();
		if(isset($prefs['gs'])){
			return $prefs['gs'][0];
		}
		return "day";
	}
	function savePref($n,$v){
		setcookie($n,implode("|",$v),time()+60*60*24*365);
	}
	function graphPrefs(){
		$prefs=getPrefs();
		$prefStr="";
		$graphPrefs=array("fillGraph","drawPoints");
		if(!isset($prefs['graphPrefs'])){
			$prefs['graphPrefs']=array("false","false");
		}
		foreach($prefs['graphPrefs'] as $i=>$thisPref){
			if(isset($graphPrefs[$i])){
				$prefStr.=",{$graphPrefs[$i]}: $thisPref";
			}
		}
		return substr($prefStr,1);
	}
?>
