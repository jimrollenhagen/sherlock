<?php
	error_reporting(0);
	require_once "sekrit.php";

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

	if ($_GET["username"] && $_GET["userid"]) {
		$success = addUser($_GET["username"], $_GET["userid"]);
		if ($success) {
			echo 'Added!';
		} else {
			echo 'You\'re already added, dummy';
		}
	} else {
?>
	<form action="" method="get">
		What.CD username: <input type="text" name="username" /> <br />
		What.CD userid: <input type="text" name="userid" /> <br />
		<input type="submit" value="Add me!" />
	</form>

<?php
	}
?>


