<?php
	include "config.inc.php";
	include "prefs.inc.php";
	$prefs=getPrefs();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Sherlock - The IRC Search Bot &amp; Ratio Monitoring Service</title>
	<link rel="stylesheet" href="css/application.css" type="text/css" media="screen" />
	<script src="js/jquery-1.4.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/sherlock.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/dygraph-combined.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript">
		var s=new Array();
		var n=new Array();
		var v=new Array();
		v[0]="u";  s[0]=0;  n[0]="uploaded";
		v[1]="d";  s[1]=0;  n[1]="downloaded";
		v[2]="r";  s[2]=0;  n[2]="ratio";
		v[3]="b";  s[3]=0;  n[3]="buffer";
		v[4]="p";  s[4]=0;  n[4]="uploads";
		v[5]="s";  s[5]=0;  n[5]="snatched";
		v[6]="l";  s[6]=0;  n[6]="leeching";
		v[7]="e";  s[7]=0;  n[7]="seeding";
		v[8]="f";  s[8]=0;  n[8]="forumPosts";
		v[9]="c";  s[9]=0;  n[9]="torrentComments";
		v[10]="h"; s[10]=0; n[10]="hourlyRatio";
		function gen(){
 			var str="?d=";
			var countgraphs=0;
 			for(var i=0;i<v.length;i++){
 				var thisn=v[i];
 				if(s[thisn]>0){
 					str+=v[i];
					countgraphs+=1;
 				}
 			}
 			str+="&c=";
 			for(var i=0;i<v.length;i++){
 				var thisn=v[i];
 				if(s[thisn]==2){
 					str+=v[i];
 				}
 			}
 			str+="&t=7";
			if(countgraphs){
	 			document.location="./graphManager.php?+"+str;
			}
			else{
				alert("You can not add a graph with no content!");
			}
		}
		function toggle(i){
			switch(s[i]){
				case 1:
					s[i]=2;
					document.getElementById("toggle"+i).innerHTML="Change";
					break;
				case 2:
					s[i]=0;
					document.getElementById("toggle"+i).innerHTML="Off";
					break;
				default:
					s[i]=1;
					document.getElementById("toggle"+i).innerHTML="Total";
					break;
			}
		}
	</script>
</head>
<body id="graphmanager_page">
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
        <h1>Add new graph</h1>
      </div>
      <div class="page_section" id="graph_manager">
        <div class="section_head clearfix">
          <div class="primary">
            <h3><?php echo count($prefs['cGraphs']); ?> graphs</h3>
          </div>
          <div class="secondary">
          </div>
        </div>
        <div class="section_body">
          <table class="custom_graph_table">
            <thead>
              <tr>
                <th>Statistic</th>
                <th style="width: 8%;">Display</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <td colspan="2"><a href="#" onclick="gen()">Add graph to main page</a></td>
              </tr>
            </tfoot>
            <tbody>
              <script type='text/javascript'>
          			for(var i=0;i<v.length;i++){
          				document.write("<tr><td>"+n[i]+"</td><td><a href='#' id='toggle"+v[i]+"' onclick='toggle(\""+v[i]+"\")'>Off</a></td></tr>");
          			}
          		</script>
            </tbody>      		
      		</table>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
