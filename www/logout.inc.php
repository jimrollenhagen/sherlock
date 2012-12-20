<?php
//	include_once "config.inc.php";
	session_start();
	if(isset($_GET['logout'])){
		unset($_SESSION['whatauth']);
		unset($_SESSION['username']);
		header("Location: .");
		exit();
	}
	if(isset($_GET['su'])){
		if(strlen($_GET['su'])){
			if(checkPresence($_GET['su'])){
				$_SESSION['username']=$_GET['su'];
				header("Location: index.php");
				exit();
			}
			else{
				header("Location: index.php?su&err=1");
				exit();
			}
		}else{
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
		</head>
		<body>
			<div class="head">Sherlock (what.cd)</div>
			<?php
			if(isset($_GET['err'])){
			$err=array();
			$err[0]="Undefined Error";
			$err[1]="No data found for provided user";
			if(!isset($err[$_GET['err']])) $_GET['err']=0;
			echo "<div class='err'>{$err[$_GET['err']]}</div>";
			}
			?>
			<form class="body" action="index.php" method="get">
				<table align="center" border="0" cellpadding="2" cellspacing="1">
					<tbody><tr valign="top">
						<td align="right">Username&nbsp;</td>
						<td align="left"><input name="su" type="text"></td>
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
		}
	}
?>
