<?php
	error_reporting(0);
	require_once "sekrit.php";

	function getBytes($str){
		$str=explode(" ",$str);
		$conv=array("EB"=>1152921504606846976,"PB"=>1125899906842624,"TB"=>1099511627776,"GB"=>1073741824,"MB"=>1048576,"KB"=>1024,"B"=>1);
		$conv=$conv[$str[1]];
		return floatval($str[0])*$conv;
	}
	function toGb($bytes){
		return round($bytes/(1024*1024*1024),2);
	}
	function getByteStr($b){
		$i=0;
		$conv=array("B","KB","MB","GB","TB","PB","EB");
		while(abs($b)>=1024){
			$i++;
			$b/=1024;
		}
		$b=round($b,2);
		return "$b {$conv[$i]}";
	}
	global $OHS;
	if(!isset($OHS)){
	session_set_cookie_params(60*60*24*365);
	session_start();
	if(!isset($_SESSION['whatauth'])||!isset($_SESSION['username'])){
		if(!isset($_POST['pass'])||$_POST['pass']!="$site_pw"){
?>
	<html>
		<head>
			<title></title>
			<style type="text/css">
			body{
				background-color: #343434;
				font-family: Bitstream Vera Sans, Tahoma, sans-serif;
				font-size: 11px;
				color: #757575;
				text-align:center;
				margin-top:20%;
			}
			.head{
				font-size:20pt;
			}
			.body{
				font-size:14pt;
			}
			.err{
				background-color:#ffffff;
				color:#ff0000;
				border:1px solid black;
				width:200px;
				margin:auto;
			}
			</style>
			<script src="js/analytics.js" type="text/javascript" charset="utf-8"></script>
		</head>
		<body>
			<div class="head">what.cd</div>
			<?php
			if(isset($_POST['pass'])) $_GET['err']=2;
			if(isset($_GET['err'])){
			$err=array();
			$err[0]="Undefined Error";
			$err[1]="No data found for provided user";
			$err[2]="Invalid password";
			if(!isset($err[$_GET['err']])) $_GET['err']=0;
			echo "<div class='err'>{$err[$_GET['err']]}</div>";
			}
			?>
			<form class="body" action="index.php" method="post">
				<table align="center" border="0" cellpadding="2" cellspacing="1">
					<tbody><tr valign="top">
						<td align="right">Username&nbsp;</td>
						<td align="left"><input name="username" type="text"></td>
					</tr>
					<tr valign="top">
						<td align="right">Password&nbsp;</td>
						<td align="left"><input name="pass" type="password"></td>
					</tr>
					<tr>
						<td colspan="2" align="right"><input value="Log In!" type="submit"></td>
					</tr>
				</tbody></table>
			</form>
		</body>
	</html>
<?php
		exit();
		}else{
			if(checkPresence($_POST['username'])){
				$_SESSION['whatauth']=1;
				$_SESSION['username']=$_POST['username'];
				header("Location: index.php");
				exit();
			}else{
				unset($_SESSION['username']);
//				header("Location: index.php?err=1");
				exit();
			}
		}
	}
	}
	
	function checkPresence($username){
		global $sqlserver, $sqluser, $sqlpw, $sqldb;
		$sqlconnection=mysql_connect($sqlserver,$sqluser,$sqlpw);
		mysql_select_db($sqldb,$sqlconnection);
		$sqlstr = "SELECT id from usernames WHERE username = '".mysql_real_escape_string($username)."' LIMIT 1";
		$sqlquery=mysql_query($sqlstr,$sqlconnection);
		if(mysql_num_rows($sqlquery)==0){
			return 0;
		}
		return 1;
	}

        function addUser($username, $userid){
		global $sqlserver, $sqluser, $sqlpw, $sqldb;
		$already = checkPresence($username);
		if ($already == 1) {
			return false;
		}
                $sqlconnection=mysql_connect($sqlserver,$sqluser,$sqlpw);
                mysql_select_db($sqldb,$sqlconnection);
                $sqlstr = "INSERT into usernames (username, userid) values('".mysql_real_escape_string($username)."', '".mysql_real_escape_string($userid)."');";
                $sqlquery=mysql_query($sqlstr,$sqlconnection);
		$newid = mysql_insert_id();
                return true;
        }
	
	
	function getData($information,$duration="day"){
		global $sqlserver, $sqluser, $sqlpw, $sqldb;
		switch($duration){
			case "week":
				$startDate = date("Y-m-d H:i:s",time()-(60*60*24*7)+(2*60*60));
				break;
			case "month":
				$startDate = date("Y-m-d H:i:s",time()-(60*60*24*30)+(2*60*60));
				break;
			case "year":
				$startDate = date("Y-m-d H:i:s",time()-(60*60*24*365)+(2*60*60));
				break;
			case "day":
			default:
				$startDate = date("Y-m-d H:i:s",time()-(60*60*24)+(2*60*60));
				break;
}
	$offset=0;
	if(isset($_COOKIE['tz'])){
		$d=explode("|",$_COOKIE['tz']);
		$offset+=intval($d[0]);
		$offset*=60*60;
	}
		$data=array();
		$sqltable="statistics";
		$information=str_split($information);
		$dbmap=array("u"=>"uploaded , upType",
					 "d"=>"downloaded , downType",
					 "r"=>"ratio",
					 "b"=>"buffer , buffType",
					 "p"=>"uploads",
					 "s"=>"snatched",
					 "l"=>"leeching",
					 "e"=>"seeding",
					 "f"=>"forumPosts",
					 "c"=>"torrentComments",
					 //"t"=>"date",
					 "h"=>"hourlyRatio"
					 );
		$sqlstr="SELECT";
		foreach($information as $i){
			$sqlstr.=" {$dbmap[$i]} , ";
		}
		$sqlconnection=mysql_connect($sqlserver,$sqluser,$sqlpw);
		mysql_select_db($sqldb,$sqlconnection);
		$sqlstr.="date\nFROM $sqltable\nWHERE username = '".mysql_real_escape_string($_SESSION['username'])."' AND `date` > '$startDate'\nORDER BY `date` ASC";
		$sqlquery=mysql_query($sqlstr,$sqlconnection);
		if(mysql_num_rows($sqlquery)==0){

		}
		while($r=mysql_fetch_assoc($sqlquery)){
			$date=strtotime($r['date']);
			$thisrow=array();
			foreach($r as $thisri=>$thisrv){
				switch($thisri){
					case "uploaded":
						$thisrow["uploaded"]=getBytes("$thisrv {$r['upType']}");
						break;
					case "downloaded":
						$thisrow["downloaded"]=getBytes("$thisrv {$r['downType']}");
						break;
					case "buffer":
						$thisrow["buffer"]=getBytes("$thisrv {$r['buffType']}");
						break;
					case "date":
						$thisrow["date"]=strtotime($thisrv)+$offset;
						break;
					default:
						if(strstr($thisri,"Type")) continue;
						$thisrow[$thisri]=floatval($thisrv);
				}
				
			}
			$data[$thisrow["date"]]=$thisrow;
		}
		mysql_close($sqlconnection);
		return $data;
	}

	function timeSpan($username,$duration="day"){
		global $sqlserver, $sqluser, $sqlpw, $sqldb;
		$data=array();
		switch($duration){
			case "week":
				$startDate = date("Y-m-d H:i:s",time()-60*60*24*7);
				break;
			case "month":
				$startDate = date("Y-m-d H:i:s",time()-60*60*24*30);
				break;
			case "year":
				$startDate = date("Y-m-d H:i:s",time()-60*60*24*365);
				break;
			case "day":
			default:
				$startDate = date("Y-m-d H:i:s",time()-60*60*24);
				break;
		}
		$sqlconnection=mysql_connect($sqlserver,$sqluser,$sqlpw);
		mysql_select_db($sqldb,$sqlconnection);
		$sth=mysql_query("SELECT * FROM statistics WHERE username = '$username' && date < '$startDate' ORDER BY date DESC LIMIT 1", $sqlconnection);
		$substh=mysql_query("SELECT * FROM statistics WHERE username = '$username' ORDER BY date DESC LIMIT 1", $sqlconnection);
		$past = mysql_fetch_assoc($sth);
		$present = mysql_fetch_assoc($substh);
		foreach($present as $i=>$v){
			switch($i){
				case "uploaded":
					$data[$i]=getBytes("{$present['uploaded']} {$present['upType']}")-
						  getBytes("{$past['uploaded']} {$past['upType']}");
					break;
				case "downloaded":
					$data[$i]=getBytes("{$present['downloaded']} {$present['downType']}")-
						  getBytes("{$past['downloaded']} {$past['downType']}");
					break;
				case "buffer":
					$data[$i]=getBytes("{$present['buffer']} {$present['buffType']}")-
						  getBytes("{$past['buffer']} {$past['buffType']}");
					break;
				default:
					if(strstr($i,"Type")||$i=="date"||$i=="id") continue;
					$data[$i]=floatval($present[$i])-floatval($past[$i]);
			}
		}
		return $data;
	}
	
	function currentStats($username){
		global $sqlserver, $sqluser, $sqlpw, $sqldb;
		$data=array();
		$sqlconnection=mysql_connect($sqlserver,$sqluser,$sqlpw);
		mysql_select_db($sqldb,$sqlconnection);
		$substh=mysql_query("SELECT * FROM statistics WHERE username = '$username' ORDER BY date DESC LIMIT 1", $sqlconnection);
		$present = mysql_fetch_assoc($substh);
		if(!is_array($present)) return -1;
		foreach($present as $i=>$v){
			switch($i){
				case "uploaded":
					$data[$i]=getBytes("{$present['uploaded']} {$present['upType']}");
					break;
				case "downloaded":
					$data[$i]=getBytes("{$present['downloaded']} {$present['downType']}");
					break;
				case "buffer":
					$data[$i]=getBytes("{$present['buffer']} {$present['buffType']}");
					break;
				default:
					if(strstr($i,"Type")||$i=="date"||$i=="id") continue;
					$data[$i]=floatval($present[$i]);
			}
		}
		return $data;
	}
	
	function topTen($information,$duration){
			global $sqlserver, $sqluser, $sqlpw, $sqldb;
			$topten=array();
			$data=array();
			$sqltable="statistics";
			$information=str_split($information);
			$sqlstr="SELECT ".$dbmap[$information[0]];
			switch($duration){
				case "week":
					$startDate = date("Y-m-d H:i:s",time()-60*60*24*7);
					$checkDate =  $startDate = date("Y-m-d H:i:s",time()-60*60*24*14);
					break;
				case "month":
					$startDate = date("Y-m-d H:i:s",time()-60*60*24*30);
					$checkDate =  $startDate = date("Y-m-d H:i:s",time()-60*60*24*37);
					break;
				case "year":
					$startDate = date("Y-m-d H:i:s",time()-60*60*24*365);
					$checkDate = date("Y-m-d H:i:s",time()-60*60*24*370);
					break;
				case "day":
				default:
					$startDate = date("Y-m-d H:i:s",time()-60*60*24);
					$checkDate = date("Y-m-d H:i:s",time()-60*60*24*2);
					break;
			}
			$sqlconnection=mysql_connect($sqlserver,$sqluser,$sqlpw);
			mysql_select_db($sqldb,$sqlconnection);
			$userSth=mysql_query("SELECT username FROM usernames",$sqlconnection);
			while($username=mysql_fetch_row($userSth)){
				$username=$username[0];
				$sth=mysql_query("SELECT buffer, buffType FROM statistics WHERE username = '$username' && date < '$startDate' ORDER BY date DESC LIMIT 1", $sqlconnection);
				if(mysql_num_rows($sth) > 0){
					if($substh=mysql_query("SELECT buffer, buffType FROM statistics WHERE username = '$username' ORDER BY date DESC LIMIT 1", $sqlconnection)){
						$past = mysql_fetch_row($sth);
						$present = mysql_fetch_row($substh);
						list ($change, $changeType) = calculateBuffer($present[0],$present[1],$past[0],$past[1]);
						if($change == 0) { continue; }
						$unsorted = array($username, getBytes("$change $changeType"));
						array_push($topten, $unsorted);
					}}
			}
			uasort($topten,'top10sort');
			//$topten=array_chunk($topten,10);
			return $topten;//[0];
		}
		function top10sort($a, $b) {
		    if ($a[1] == $b[1]) {
		        return 0;
		    }
	    	return ($a[1] < $b[1]) ? 1 : -1;
		}

	function calculateBuffer($uploaded, $uploadedType, $downloaded, $downloadedType) {
		if($uploaded && $downloaded) {
			if	($downloadedType == "b")  { $dBuffer = $downloaded / 8388608;	    }
			elseif	($downloadedType == 'B')  { $dBuffer = $downloaded / 1048576; 	    }
			elseif	($downloadedType == 'KB') { $dBuffer = $downloaded / 1024;   	    }
			elseif	($downloadedType == 'MB') { $dBuffer = $downloaded;		    }
			elseif	($downloadedType == 'GB') { $dBuffer = $downloaded * 1024;   	    }
			elseif	($downloadedType == 'TB') { $dBuffer = $downloaded * 1048576;	    }
			elseif	($downloadedType == 'PB') { $dBuffer = $downloaded * 1073741824;    }
			elseif	($downloadedType == 'EB') { $dBuffer = $downloaded * 1099511627776; }
			
			if	($uploadedType == 'b')  { $buffer = $uploaded / 8388608;       }
			elseif	($uploadedType == 'B')	{ $buffer = $uploaded / 1048576;       }
			elseif	($uploadedType == 'KB')	{ $buffer = $uploaded / 1024; 	       }
			elseif	($uploadedType == 'MB') { $buffer = $uploaded;		       }
			elseif	($uploadedType == 'GB') { $buffer = $uploaded * 1024; 	       }
			elseif	($uploadedType == 'TB') { $buffer = $uploaded * 1048576;       }
			elseif	($uploadedType == 'PB') { $buffer = $uploaded * 1073741824;    }
			elseif	($uploadedType == 'EB') { $buffer = $uploaded * 1099511627776; }
			
			#Convert buffer to the appropriate power
			if($dBuffer==0) $dBuffer=1;
			$ratio = $buffer / $dBuffer;
			$buffer -= $dBuffer;
			$bufferType = 'MB';
			$temp = abs($buffer);
			if	($temp > 1099511627776)		{ $buffer /= 1099511627776; $buffer = sprintf("%.2f", $buffer); $bufferType = 'EB'; }
			elseif	($temp > 1073741824) 		{ $buffer /= 1073741824;    $buffer = sprintf("%.2f", $buffer); $bufferType = 'PB'; }
			elseif	($temp > 1048576)		{ $buffer /= 1048576;       $buffer = sprintf("%.2f", $buffer); $bufferType = 'TB'; }
			elseif	($temp >= 1024) 		{ $buffer /= 1024; 	    $buffer = sprintf("%.2f", $buffer); $bufferType = 'GB'; }
			elseif	($temp < 1024 && $temp >= 1)	{ 			    $buffer = sprintf("%.2f", $buffer);			    }
			elseif	($temp > 0.0009765625)		{ $buffer *= 1024;	    $buffer = sprintf("%.2f", $buffer); $bufferType = 'KB'; }
			elseif	($temp > 0.000000953674)	{ $buffer *= 1048576;	    $buffer = sprintf("%.2f", $buffer); $bufferType = 'B';  }
			elseif	($temp > 0.00000011920929)	{ $buffer *= 8388608; 	    $buffer = sprintf("%.2f", $buffer); $bufferType = 'b';  }
		}
		elseif(!$uploaded) { return array(-$downloaded, $downloadedType, 0); }
		elseif(!$downloaded) { return array($uploaded, $uploadedType, 0); }
		return array($buffer, $bufferType, $ratio);
	}
?>
