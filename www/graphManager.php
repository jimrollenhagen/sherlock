<?php
	include "config.inc.php";
	include "prefs.inc.php";
	$prefs=getPrefs();
	$query=$_SERVER['QUERY_STRING'];
	$action=substr($query,0,1);
	$graph=substr($query,1);
	switch($action){
		case "+":
			$cGraphs=$prefs['cGraphs'];
			$cGraphs[]=$graph;
			savePref("cGraphs",$cGraphs);
			header("Location: index.php");
			exit();
			break;
		case "-":
			$cGraphs=$prefs['cGraphs'];
			foreach($cGraphs as $i=>$c){
				if($c==$graph){
					unset($cGraphs[$i]);
					break;
				}
			}
			savePref("cGraphs",$cGraphs);
			header("Location: index.php");
			exit();
			break;
		default:
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
        <h1>Graph Manager</h1>
      </div>
      <div id="custom_graphs" class="page_section">
        <div class="section_head clearfix">
          <div class="primary">
            <h3><?php echo count($prefs['cGraphs']); ?> graphs</h3>
          </div>
          <div class="secondary">
            <ul class="section_head_links">
              <li><a href="customgraph.php">Add new graph</a></li>
            </ul>
          </div>
        </div>
        <div class="section_body">
          <?php if(count($prefs['cGraphs'])){
            foreach($prefs['cGraphs'] as $i=>$cgraph){
            $i2=$i;
            $kmg=false;
            $c=array();
            parse_str(parse_url($cgraph,PHP_URL_QUERY),$c);
            $data=getData($c['d'],getGS());
            $haslast=str_split($c['c']);
            $indexconv=array("u"=>"uploaded",
            "d"=>"downloaded",
            "r"=>"ratio",
            "b"=>"buffer",
            "p"=>"uploads",
            "s"=>"snatched",
            "l"=>"leeching",
            "e"=>"seeding",
            "f"=>"forumPosts",
            "c"=>"torrentComments",
            "h"=>"hourlyRatio"
            );
            $statcolors=array("u"=>"#ca8080",
            "d"=>"#6C8ABD",
            "r"=>"#CA9E6B",
            "b"=>"#88CA97",
            "p"=>"#6db8bd",
            "s"=>"#b9bd6d",
            "l"=>"#b07eb3",
            "e"=>"#b38d7e",
            "f"=>"#b3ab7f",
            "c"=>"#7fb393",
            "h"=>"#dd9745"
            );
            foreach($haslast as $thislasti=>$thislastv){
            $haslast[$indexconv[$thislastv]]=1;
            unset($haslast[$thislasti]);
            }
            $last=array();
            $gdata=array();
            $skipped=0;
            $strdata="date";
            foreach(str_split($c['d']) as $d){
            $strdata.=", ".$indexconv[$d];
            }
            $graph_key = "";
            foreach(str_split($c['d']) as $d){
              $graph_key .= "<li style=\"background: ".$statcolors[$d].";\"><span>".$indexconv[$d]."</span></li>";
            }
            $graph_colors = "";
            foreach(str_split($c['d']) as $d){
              $graph_colors .= "'".$statcolors[$d]."',";
            }
            $graph_colors = rtrim($graph_colors,",");
            foreach($data as $d){
            $strdata.=" \\n ".date("Y/m/d H:i:00",$d["date"]);
            foreach($d as $i=>$v){
            if($i=="date") continue;
            if($i=="buffer"||$i=="uploaded"||$i=="downloaded") $kmg=true;//$v=toGb($v);
            if(!isset($haslast[$i])){
            if($skipped){ $strdata.=", $v"; }
            }else{
            $diff=$v-$last[$i];
            $last[$i]=$v;
            if($skipped){ $strdata.=", $diff"; }
            }
            $o=$v;
            $d[$i]=round($o-$last[$i],2);
            $last[$i]=$o;
            }
            if(!$skipped)$skipped=1;
            }
            $title="";
            foreach(str_split($c['d']) as $d){
            $title.=", ".$indexconv[$d];
            }
          ?> 
          <div class="graph">
            <div class="graph_head clearfix">
              <div class="primary"><h4>Custom Graph #<?php echo $i2; ?> <span>(<a href='./graphManager.php?-<?php echo $cgraph; ?>'>remove</a>)</span></h4></div>
              <div class="secondary">
                <ul class="graph_key">
                  <?php echo $graph_key; ?>
                </ul>
              </div>
            </div>
            <div class="graph_body" id="graph_custom<?php echo $i2; ?>" style="height: 180px; width: 940px;"></div>
            <script type="text/javascript" charset="utf-8">
              $(document).ready(function(){
                // Upload and download per hour graph
                graph_custom<?php echo $i2; ?> = new Dygraph(
                document.getElementById("graph_custom<?php echo $i2; ?>"),
                "<?php echo $strdata; ?>",
                {
                  <?php echo graphPrefs(); ?>,
                  <?php if($kmg) echo "labelsKMG2:true,"; ?>
                  colors:[<?php echo $graph_colors; ?>],
                  labelsSeparateLines:true,
                  labelsDivStyles:{'background':'transparent'},
                  fillGraph: true,
                  drawPoints: true,
                  gridLineColor: '#ccc'
                }
              );
            });
            </script>
          </div>
          <?php
          }
        }
        ?>
      </div>
    </div>
  </div>
</div>
</body>
</html>
<?php
	}
?>
