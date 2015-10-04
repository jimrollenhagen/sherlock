<?php
	include_once "config.inc.php";
	include_once "logout.inc.php";
	include_once "prefs.inc.php";
	$prefs=getPrefs();
	//$data=getData("udbrh",26);
	$data=getData("udbrh","day");
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
</head>
<body id="user_page">
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
        <h1>What.cd statistics for <span><?php echo $_SESSION['username']; ?></span></h1>
      </div>
      
      <div id="statistics_summary">
        <div class="section_head clearfix">
          <div class="primary">
            <h3>Summary <span id="summary_type">Buffer</span></h3>
          </div>
          <div class="secondary">
            <ul class="section_head_links">
              <li class="active"><a href="#buffer_summary">Buffer</a></li>
              <li><a href="#ratio_summary">Ratio</a></li>
              <li><a href="#uploaded_summary">Uploaded</a></li>
              <li><a href="#downloaded_summary">Downloaded</a></li>
            </ul>
          </div>
        </div>
        <div class="summary_content_container">
          <?php
            // Buffer timespans
            $day = timeSpan($_SESSION['username'],"day");
            $week = timeSpan($_SESSION['username'],"week");
            $month = timeSpan($_SESSION['username'],"month");
            $alltime = currentStats($_SESSION['username']);
            
            // Buffer variables
            $daybuffer = $day['buffer'];
            $weekbuffer = $week['buffer'];
            $monthbuffer = $month['buffer'];
            $alltimebuffer = $alltime['buffer'];
            
            // Ratio variables
            $dayratio = $day['ratio'];
            $weekratio = $week['ratio'];
            $monthratio = $month['ratio'];
            $alltimeratio = $alltime['ratio'];
            
            // Check class for past 24 hour buffer
            $daybufferclass = "";
            if($daybuffer>0) {
              $daybufferclass = abs($daybuffer) > 3221225472 ? "positive" : "minimal";
            } elseif($daybuffer<0) {
              $daybufferclass = "negative";
            }

            // get threshold used for display rows
            $threshold = ($day['uploaded'] / 24) * 1.3;
            
            // Check class for past week buffer
            $weekbufferclass = "";
            if($weekbuffer>0) {
              $weekbufferclass = abs($weekbuffer) > 3221225472 ? "positive" : "minimal";
            } elseif($weekbuffer<0) {
              $weekbufferclass = "negative";
            }
            
            // Check class for past month buffer
            $monthbufferclass = "";
            if($monthbuffer>0) {
              $monthbufferclass = abs($monthbuffer) > 3221225472 ? "positive" : "minimal";
            } elseif($monthbuffer<0) {
              $monthbufferclass = "negative";
            }
            
            // Check class for past 24 hour ratio
            $dayratioclass = "";
            if($dayratio>0) {
              $dayratioclass = abs($dayratio) > 0.05 ? "positive" : "minimal";
            } elseif($dayratio<0) {
              $dayratioclass = "negative";
            }
            
            // Check class for past week ratio
            $weekratioclass = "";
            if($weekratio>0) {
              $weekratioclass = abs($weekratio) > 0.05 ? "positive" : "minimal";
            } elseif($weekratio<0) {
              $weekratioclass = "negative";
            }
            
            // Check class for past month ratio
            $monthratioclass = "";
            if($monthratio>0) {
              $monthratioclass = abs($monthratio) > 0.05 ? "positive" : "minimal";
            } elseif($monthratio<0) {
              $monthratioclass = "negative";
            }
          ?>
          <ul class="summary_content clearfix" id="buffer_summary">
            <li class="past_24 <?php echo $daybufferclass; ?>"><span title="<?php echo "$daybuffer B";?>"><?php echo getByteStr($daybuffer); ?></span> past 24 hours</li>
            <li class="past_week <?php echo $weekbufferclass; ?>"><span title="<?php echo "$weekbuffer B";?>"><?php echo getByteStr($weekbuffer); ?></span> past week</li>
            <li class="past_month <?php echo $monthbufferclass; ?>"><span title="<?php echo "$monthbuffer B";?>"><?php echo getByteStr($monthbuffer); ?></span> past month</li>
            <li class="all_time"><span title="<?php echo "$alltimebuffer B";?>"><?php echo getByteStr($alltimebuffer); ?></span> all-time</li>
          </ul>
          <ul class="summary_content clearfix" id="ratio_summary">
            <li class="past_24 <?php echo $dayratioclass; ?>"><span><?php echo number_format($dayratio,4); ?></span> past 24 hours</li>
            <li class="past_week <?php echo $weekratioclass; ?>"><span><?php echo number_format($weekratio,4); ?></span> past week</li>
            <li class="past_month <?php echo $monthratioclass; ?>"><span><?php echo number_format($monthratio,4); ?></span> past month</li>
            <li class="all_time"><span><?php echo number_format($alltimeratio,4); ?></span> all-time</li>
          </ul>
          <ul class="summary_content clearfix" id="uploaded_summary">
            <li class="past_24"><span title="<?php echo $day['uploaded']." B";?>"><?php echo getByteStr($day['uploaded']); ?></span> past 24 hours</li>
            <li class="past_week"><span title="<?php echo $week['uploaded']." B";?>"><?php echo getByteStr($week['uploaded']); ?></span> past week</li>
            <li class="past_month"><span title="<?php echo $month['uploaded']." B";?>"><?php echo getByteStr($month['uploaded']); ?></span> past month</li>
            <li class="all_time"><span title="<?php echo $alltime['uploaded']." B";?>"><?php echo getByteStr($alltime['uploaded']); ?></span> all-time</li>
          </ul>
          <ul class="summary_content clearfix" id="downloaded_summary">
            <li class="past_24"><span title="<?php echo $day['downloaded']." B";?>"><?php echo getByteStr($day['downloaded']); ?></span> past 24 hours</li>
            <li class="past_week"><span title="<?php echo $week['downloaded']." B";?>"><?php echo getByteStr($week['downloaded']); ?></span> past week</li>
            <li class="past_month"><span title="<?php echo $month['downloaded']." B";?>"><?php echo getByteStr($month['downloaded']); ?></span> past month</li>
            <li class="all_time"><span title="<?php echo $alltime['downloaded']." B";?>"><?php echo getByteStr($alltime['downloaded']); ?></span> all-time</li>
          </ul>
        </div>
      </div>
      
      <div id="hourly_statistics" class="page_section">
        <div class="section_head clearfix">
          <div class="primary"><h3>Hourly Statistics</h3></div>
          <div class="secondary"><!-- dropdown to change timezone here? --></div>
        </div>
        <div class="section_body">
          <table class="hourly_statistics_table">
            <thead>
              <tr>
                <th>Time</th>
                <th>Upload</th>
                <th>Download</th>
                <th>Buffer</th>
                <th>Ratio</th>
              </tr>
            </thead>
            <tbody>
              <?php
              	$lastup=0;
              	$lastdown=0;
              	$lastratio=0;
              	$lastbuff=0;
              	$skip=1;
              	foreach($data as $d){
#			var_dump($d);
              		if(!count($d)) break;
              		$time=date("M d H:i",$d["date"]);
              		$up=$d["uploaded"];
              		$diffup=$up-$lastup;
              		$lastup=$up;
              		$fup="<span title=\"".$diffup." B\">".getByteStr($diffup)."</span> <span class=\"all-time\" title=\"".$up." B\">(".getByteStr($up).")</span>";
              		$down=$d["downloaded"];
              		$diffdown=$down-$lastdown;
              		$lastdown=$down;
              		$fdown="<span title=\"".$diffdown." B\">".getByteStr($diffdown)."</span> <span class=\"all-time\" title=\"".$down." B\">(".getByteStr($down).")</span>";
              		$buffer=$d["buffer"];
              		$diffbuff=$buffer-$lastbuff;
              		$lastbuff=$buffer;
              		
              		$bufferclass = "";
              		$overrange = false;
              		if(abs($diffbuff) > $threshold){ $overrange = true; }
              		if($diffbuff==0) {
              		  $bufferclass = "none";
              		} elseif($diffbuff>0) {
              		  $bufferclass = $overrange ? "positive" : "minimal";
              		} else {
              		  $bufferclass = "negative";
              		}
                  
              		$fbuff="<span title=\"".$diffbuff." B\">".getByteStr($diffbuff)."</span> <span class=\"all-time\" title=\"".$buffer." B\">(".getByteStr($buffer).")</span>";
              		$ratio=number_format($d["ratio"],4);
              		$diffratio=number_format($ratio-$lastratio,4);
              		$lastratio=$ratio;
              		$hourlyratio=$d["hourlyRatio"];
#              		$fratio="$hourlyratio <span class=\"all-time\">($ratio)</span>";
			$fratio="$diffratio <span class=\"all-time\">($ratio)</span>";
              		if(!$skip){
              			echo "<tr><td>$time</td><td>$fup</td><td>$fdown</td><td class=\"$bufferclass\">$fbuff</td><td>$fratio</td></tr>\n";
              		}else{
              			$skip--;
              		}
              	}
              ?>
            </tbody>
          </table>
        </div>
      </div>
      
      <div id="graphs" class="page_section">
        <div class="section_head clearfix"><div class="primary"><h3>Graphs</h3></div></div>
        <div class="section_body">
          <div class="graph">
            <div class="graph_head clearfix">
              <div class="primary"><h4>Upload and download (per hour)</h4></div>
              <div class="secondary">
                <ul class="graph_key">
                  <li style="background: #ca8080;"><span>Upload</span></li>
                  <li style="background: #6C8ABD;"><span>Download</span></li>
                </ul>
              </div>
            </div>
            <div class="graph_body" id="upload_download_per_hour" style="height: 180px; width: 940px;"></div>
          </div>
          
          <div class="graph">
            <div class="graph_head clearfix">
              <div class="primary"><h4>Upload and download (total)</h4></div>
              <div class="secondary">
                <ul class="graph_key">
                  <li style="background: #ca8080;"><span>Upload</span></li>
                  <li style="background: #6C8ABD;"><span>Download</span></li>
                </ul>
              </div>
            </div>
            <div class="graph_body" id="upload_download_total" style="height: 180px; width: 940px;"></div>
          </div>
          
          <div class="graph">
            <div class="graph_head clearfix">
              <div class="primary"><h4>Buffer</h4></div>
              <div class="secondary">
                <ul class="graph_key">
                  <li style="background: #88CA97;"><span>Buffer</span></li>
                </ul>
              </div>
            </div>
            <div class="graph_body" id="buffer" style="height: 180px; width: 940px;"></div>
          </div>
          
          <div class="graph">
            <div class="graph_head clearfix">
              <div class="primary"><h4>Ratio</h4></div>
              <div class="secondary">
                <ul class="graph_key">
                  <li style="background: #CA9E6B;"><span>Ratio</span></li>
                </ul>
              </div>
            </div>
            <div class="graph_body" id="ratio" style="height: 180px; width: 940px;"></div>
          </div>
        </div>        
      </div>
      
      <?php if(count($prefs['cGraphs'])){ ?>
      <div id="custom_graphs" class="page_section">
        <div class="section_head clearfix"><div class="primary"><h3>Custom Graphs</h3></div></div>
        <div class="section_body">
          <?php
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
          rtrim($graph_colors,",");
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
              <div class="primary"><h4>Custom Graph #<?php echo $i2; ?></h4></div>
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
          <?php } ?>
        </div>        
      </div>
      <?php } ?>      
    </div>
  </div>
  <script type="text/javascript" charset="utf-8">
  $(document).ready(function(){
    // Upload and download per hour graph
    upload_download_per_hour = new Dygraph(
      document.getElementById("upload_download_per_hour"),
      "<?php
        $data=getData("ud",getGS());
        $strdata="date,uploaded,downloaded\\n";
        $lastup=0;
        $lastdown=0;
        $skipped=0;
        foreach($data as $d){
          $up=$d["uploaded"];
          $down=$d["downloaded"];
          $newup=$up-$lastup;
          $newdown=$down-$lastdown;
          $lastup=$up;
          $lastdown=$down;
          if($skipped){
            $strdata.=date("Y/m/d H:i:00",$d["date"]).", ".number_format($newup,2,".","").", ".number_format($newdown,2,".","")." \\n";
          }else{
            $skipped=1;
          }
        }
        echo substr(chop($strdata),0,-2);
      ?>",
      {
        labelsKMG2:true,
        colors:['#ca8080', '#6C8ABD'],
        labelsSeparateLines:true,
        labelsDivStyles:{'background':'transparent'},
        fillGraph: true,
        drawPoints: true,
        gridLineColor: '#ccc',
      }
    );
    
    // Upload and download total
    upload_download_total = new Dygraph(
      document.getElementById("upload_download_total"),
      "<?php
        $data=getData("ud",getGS());
        $strdata="date,uploaded,downloaded\\n";
        $lastup=0;
        $lastdown=0;
        $skipped=0;
        foreach($data as $d){
          $up=$d["uploaded"];
          $down=$d["downloaded"];
          $lastup=$up;
          $lastdown=$down;
          if($skipped){
            $strdata.=date("Y/m/d H:i:00",$d["date"]).", ".number_format($up,2,".","").", ".number_format($down,2,".","")." \\n";
          }else{
            $skipped=1;
          }
        }
        echo substr(chop($strdata),0,-2);
      ?>",
      {
        labelsKMG2:true,
        colors:['#ca8080', '#6C8ABD'],
        labelsSeparateLines:true,
        labelsDivStyles:{'background':'transparent'},
        fillGraph: true,
        drawPoints: true
      }
    );
    
    // Buffer
    buffer = new Dygraph(
      document.getElementById("buffer"),
      "<?php
        $data=getData("b",getGS());
        $strdata="date,buffer\\n";
        $skipped=0;
        foreach($data as $d){
          $b=$d["buffer"];
          if($skipped){
            $strdata.=date("Y/m/d H:i:00",$d["date"]).", ".number_format($b,2,".","")." \\n";
          }else{
            $skipped=1;
          }
        }
        echo substr(chop($strdata),0,-2);
      ?>",
      {
        colors:['#88CA97'],
        labelsKMG2:true,
        labelsSeparateLines:true,
        labelsDivStyles:{'background':'transparent'},
        fillGraph: true,
        drawPoints: true
      }
    );
    
    // Ratio
    ratio = new Dygraph(
      document.getElementById("ratio"),
      "<?php
        $data=getData("r",getGS());
        $strdata="date,ratio \\n";
        $skipped=0;
        foreach($data as $d){
          $r=$d["ratio"];
          $h=$d["hourlyRatio"];
          if($skipped){
            $strdata.=date("Y/m/d H:i:00",$d["date"]).", ".number_format($r,6,".","")." \\n";
          }else{
            $skipped=1;
          }
        }
        echo substr(chop($strdata),0,-2);
      ?>",
      {
        colors:['#CA9E6B'],
        labelsSeparateLines:true,
        labelsDivStyles:{'background':'transparent'},
        fillGraph: true,
        drawPoints: true
      }
    );
  })
	</script>
</body>
</html>
