<?php
	include "config.inc.php";
	include "prefs.inc.php";
	
	$prefs=getPrefs();
	
	if(isset($_GET['preferences_changed'])){
    savePref("tz",array($_GET['tz'],$_GET['dst']));
    savePref("gs",array($_GET['gs']));
    // savePref("graphPrefs",$_GET['graphPrefs']);
    
    // Save settings to session to use in javascript
    $_SESSION['tz'] = $_GET['tz'];
    $_SESSION['dst'] = $_GET['dst'];
    $_SESSION['gs'] = $_GET['gs'];
    $_SESSION['graphPrefs[0]'] = $_GET['graphPrefs[0]'];
    $_SESSION['graphPrefs[1]'] = $_GET['graphPrefs[1]'];
    
    if(count($prefs['tz'])){
      $tz=timezone_name_from_abbr("", intval($prefs['tz'][0])*60*60, $prefs['tz'][1]);
    } else {
      $tz="default";
    }
    if(count($prefs['gs'])){
      $gs=$prefs['gs'][0];
    } else {
      $gs="week";
    }
    
    header("Location: index.php");
    exit();
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Sherlock - The IRC Search Bot &amp; Ratio Monitoring Service</title>
	<link rel="stylesheet" href="css/application.css" type="text/css" media="screen" />
	<script src="js/analytics.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/jquery-1.4.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/sherlock.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/dygraph-combined.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript">
	  $(document).ready(function(){
	    // Ugly code - Don't worry though! The whole sherlock system will be getting a rewrite and cleanup :D
	    $("select#tz").val("<?php echo $_SESSION['tz']; ?>");
	    $("select#dst").val("<?php echo $_SESSION['dst']; ?>");
	    $("select#gs").val("<?php echo $_SESSION['gs']; ?>");
    });
	</script>
</head>
<body id="preferences_page">
  <div id="wrapper">
    <div id="header">
      <h2 id="logo"><a href="index.php">Sherlock - The IRC Search Bot &amp; Ratio Monitoring Service</a></h2>
      <div id="logged_in"><p><a href="index.php?logout" class="logout_link">Logout</a></p></div>
    </div>
  
    <div id="navigation_bar">
      <ul id="navigation">
        <li id="user_navitem"><a href="index.php">What.cd statistics for <?php echo $_SESSION['username']; ?></a></li>
        <li id="topten_navitem"><a href="topten.php">Top 10</a></li>
        <li id="preferences_navitem"><a href="prefs.php">Preferences</a></li>
        <li id="graphmanager_navitem"><a href="graphManager.php">Graph Manager</a></li>
      </ul>
      <form id="search" action="index.php" method="get">
        <input type="text" name="su" value="Username" id="su" />
        <input type="submit" value="View User Statistics" id="search_submit" />      
      </form>
    </div>
    
    <div id="content">
      <div id="page_header">
        <h1>Preferences</h1>
      </div>
      <form action="prefs.php" method="get" class="preferences_form">
        <input type="hidden" id="preferences_changed" name="preferences_changed" value="yes" />
        <fieldset>
          <div class="description">
            <h3>Timezone</h3>
            <p>Any preferences related to your timezone. These will affect your hourly statistics and your graphs.</p>
          </div>
          <ol>
            <li>
              <label for="timezone">Timezone</label>
              <select name="tz" id="tz">
                <option value=""></option>
                <option value="-12">GMT -12</option>
                <option value="-11">GMT -11</option>
                <option value="-10">GMT -10</option>
                <option value="-9">GMT -9</option>
                <option value="-8">GMT -8</option>
                <option value="-7">GMT -7</option>
                <option value="-6">GMT -6</option>
                <option value="-5">GMT -5</option>
                <option value="-4">GMT -4</option>
                <option value="-3">GMT -3</option>
                <option value="-2">GMT -2</option>
                <option value="-1">GMT -1</option>
                <option value="0">GMT 0</option>
                <option value="1">GMT +1</option>
                <option value="2">GMT +2</option>
                <option value="3">GMT +3</option>
                <option value="4">GMT +4</option>
                <option value="5">GMT +5</option>
                <option value="6">GMT +6</option>
                <option value="7">GMT +7</option>
                <option value="8">GMT +8</option>
                <option value="9">GMT +9</option>
                <option value="10">GMT +10</option>
                <option value="11">GMT +11</option>
                <option value="12">GMT +12</option>
                <option value="13">GMT +13</option>
              </select>
            </li>
<!--
            <li>
              <label for="dst">Daylight Savings Time</label>
              <select name="dst" id="dst">
                <option value=""></option>
                <option value="0">no</option>
                <option value="1">yes</option>
              </select>
            </li>
-->
          </ol>
        </fieldset>
        <fieldset>
          <div class="description">
            <h3>Graph Span</h3>
            <p>Longer graph spans may take longer to load on a slow connection.</p>
          </div>
          <ol>
            <li>
              <label for="gs">Graph Span</label>
              <select name="gs" id="gs">
                <option value=""></option>
                <option value="day">Day</option>
                <option value="week">Week</option>
                <option value="month">Month</option>
                <option value="year">Year</option>
              </select>
            </li>
          </ol>
        </fieldset>
        <!--
        <fieldset>
          <div class="description">
            <h3>Graph Preferences</h3>
            <p>Any settings related to the graphs appearance.</p>
          </div>
          <ol>
            <li>
              <label for="graphPrefs[0]">Fill Graphs</label>
              <select name="graphPrefs[0]" id="graphPrefs[0]"><option value="false">Off</option><option value="true">On</option></select>
            </li>
            <li>
              <label for="graphPrefs[1]">Dotted Points</label>
              <select name="graphPrefs[1]" id="graphPrefs[1]"><option value="false">Off</option><option value="true">On</option></select>
            </li>
          </ol>
        </fieldset>
        -->
        <fieldset class="form_submit_buttons">
          <ol><li><input name="commit" type="submit" value="Save preferences" /></li></ol>
        </fieldset>
      </form>
    </div>
  </div>
</body>
</html>
