<?php
	include "logout.inc.php";
	include "config.inc.php";
	include "prefs.inc.php";
	$prefs=getPrefs();
	
	$duration="day";
	if(isset($_GET['d']))
		$duration=$_GET['d'];
	
	$data=topTen("b",$duration);
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
</head>
<body id="topten_page">
  <div id="wrapper">
    <div id="header">
      <a id="top"></a>
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
        <input type="text" name="su" value="Username" id="search_input" />
        <input type="submit" value="View User Statistics" id="search_submit" />      
      </form>
    </div>
    
    <div id="content">
      <div id="page_header">
        <h1>Top Ten</h1>
      </div>
      
      <div id="topten_list" class="page_section">
        <div class="section_head clearfix">
          <div class="primary"><h3><?php echo ucfirst($duration);?></h3></div>
          <div class="secondary">
            <ul class="section_head_links" id="<?php echo $duration;?>-active">
              <li class="day"><a href="topten.php?d=day">Day</a></li>
              <li class="week"><a href="topten.php?d=week">Week</a></li>
              <li class="month"><a href="topten.php?d=month">Month</a></li>
              <li class="year"><a href="topten.php?d=year">Year</a></li>
            </ul>
          </div>
        </div>
        <div class="section_body">
          <table class="hourly_statistics_table">
            <thead>
              <tr>
                <th style="width: 8%">Rank</th>
                <th>User</th>
                <th style="width: 10%">Buffer</th>
              </tr>
            </thead>
            <!-- back to top link here -->
            <tbody>
              <?php
        				$i=0;
        				foreach($data as $v){
        					$i++;
        					$b=getByteStr($v[1]);
        					echo "<tr><td>$i</td><td><a href=\"index.php?su={$v[0]}\">{$v[0]}</a></td><td>$b</td></tr>";
        			  }
        			?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
