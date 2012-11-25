<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Sherlock - The IRC Search Bot &amp; Ratio Monitoring Service</title>
	<link rel="stylesheet" href="css/application.css" type="text/css" media="screen" />
	<script src="js/jquery-1.4.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/sherlock.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/dygraph-combined.js" type="text/javascript" charset="utf-8"></script>
</head>
<body>
  <div id="wrapper">
    <div id="header">
      <h2 id="logo"><a href="index.php">Sherlock - The IRC Search Bot &amp; Ratio Monitoring Service</a></h2>
      <div id="logged_in"><p><a href="index.php?logout" class="logout_link">Logout</a></p></div>
    </div>
  
    <div id="navigation_bar">
      <ul id="navigation">
        <li><a href="topten.php">Top 10</a></li>
        <li><a href="prefs.php">Preferences</a></li>
        <li><a href="graphManager.php">Graph Manager</a></li>
      </ul>
      <form id="search" action="index.php" method="get">
        <input type="text" name="su" value="Username" id="search_input" />
        <input type="submit" value="View User Statistics" id="search_submit" />      
      </form>
    </div>