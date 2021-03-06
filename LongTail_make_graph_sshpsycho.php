<?php // content="text/plain; charset=utf-8"
// Example for use of JpGraph,
//Lightly modified from http://jpgraph.net/download/manuals/chunkhtml/ch08s04.html

require_once ('/usr/local/php/jpgraph-3.5.0b1/src/jpgraph.php');
require_once ('/usr/local/php/jpgraph-3.5.0b1/src/jpgraph_bar.php');

$date=`date +"%Y-%m-%d %H:%M"`;
$URL_LINE="http://longtail.it.marist.edu";


// 	php /usr/local/etc/LongTail_make_graph_sshpsycho.php $HTML_DIR/last-30-days-attack-count.data $HTML_DIR/last-30-days-sshpsycho-attack-count.data $HTML_DIR/last-30-days-friends-of-sshpsycho-attack-count.data  $HTML_DIR/last-30-days-associates-of-sshpsycho-attack-count.data "Last 30 Days Attack Count (Red=sshPsycho, Yellow=Friends of sshPsycho, Green=Associates of sshPsycho, Blue=others)" "" "" "wide" > $GRAPHIC_FILE

// Must pass full filename to read and "Quote delimited text header"
// And redirect the output to a file

$file1 = $argv[1];
$file2 = $argv[2];
$file3 = $argv[3];
$file4 = $argv[4];
$header = $argv[5];
$x_axis_title =  $argv[6];
$y_axis_title =  $argv[7];
$size=$argv[8];
 
// We need some data
$counter=0;
# Initialize it just in case there's no data
$datay[$counter]=0;
$datay2[$counter]=0;
$datay3[$counter]=0;
$datay4[$counter]=0;
$datax[$counter]="NO DATA";
 
$myfile = fopen($file1, "r") 
	or die("Unable to open file!");
if ($myfile) {
	while (($buffer = fgets($myfile, 4096)) !== false) {
		list($count,$account) = explode(" ",$buffer);
		$account = chop($account);
		if ($count <0){
			$count=0;
		}
		$datay[$counter]=$count;
		$datax[$counter]=$account;
// print "counter is $counter, account is $account, count is $count\n";
		$counter++;
	}
    if (!feof($myfile)) {
        echo "Error: unexpected fgets() fail\n";
    }
    fclose($myfile);
}
 
$counter=0;
$myfile = fopen($file2, "r") 
	or die("Unable to open file!");
if ($myfile) {
	while (($buffer = fgets($myfile, 4096)) !== false) {
		list($count,$account) = explode(" ",$buffer);
		$account = chop($account);
		if ($count <0){
			$count=0;
		}
		$datay2[$counter]=$count;
		$datay[$counter]=$datay[$counter]-$count;
		$datax[$counter]=$account;
// print "counter is $counter, account is $account, count is $count\n";
		$counter++;
	}
    if (!feof($myfile)) {
        echo "Error: unexpected fgets() fail\n";
    }
    fclose($myfile);
}
 
$counter=0;
$myfile = fopen($file3, "r") 
	or die("Unable to open file!");
if ($myfile) {
	while (($buffer = fgets($myfile, 4096)) !== false) {
		list($count,$account) = explode(" ",$buffer);
		$account = chop($account);
		if ($count <0){
			$count=0;
		}
		$datay3[$counter]=$count;
		$datay[$counter]=$datay[$counter]-$count;
		$datax[$counter]=$account;
		$counter++;
	}
    if (!feof($myfile)) {
        echo "Error: unexpected fgets() fail\n";
    }
    fclose($myfile);
}
 
$counter=0;
$myfile = fopen($file4, "r") 
	or die("Unable to open file!");
if ($myfile) {
	while (($buffer = fgets($myfile, 4096)) !== false) {
		list($count,$account) = explode(" ",$buffer);
		$account = chop($account);
		if ($count <0){
			$count=0;
		}
		$datay4[$counter]=$count;
		$datay[$counter]=$datay[$counter]-$count;
		if ( $datay[$counter] < 0 ){
			$datay[$counter] =0;
		}
		$datax[$counter]=$account;
		$counter++;
	}
    if (!feof($myfile)) {
        echo "Error: unexpected fgets() fail\n";
    }
    fclose($myfile);
}

// Setup the graph.
if ($size == "wide"){
	$graph = new Graph(810,240);
}
else {
	$graph = new Graph(400,240);
}
$graph->img->SetMargin(60,20,35,75);
$graph->SetScale("textlin");
$graph->SetMarginColor("lightblue:1.1");
$graph->SetShadow();
 
// Set up the title for the graph
$graph->title->Set("$header");
$graph->subtitle->Set("$URL_LINE $date");

//$graph->title->Set("$header\n$URL_LINE $date");
$graph->xaxis->title->Set("$x_axis_title","left");
$graph->yaxis->title->Set("$y_axis_title","middle");

// Setup font for axis
$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,10);
$graph->yaxis->SetFont(FF_VERDANA,FS_NORMAL,10);

// Show 0 label on Y-axis (default is not to show)
$graph->yscale->ticks->SupressZeroLabel(false);

// Setup X-axis labels
$graph->xaxis->SetTickLabels($datax);
$graph->xaxis->SetLabelAngle(45);

// Create the bar pot
$bplot = new BarPlot($datay);
$bplot2 = new BarPlot($datay2);
$bplot3 = new BarPlot($datay3);
$bplot4 = new BarPlot($datay4);

$bplot->SetWidth(0.7);

// Setup color for gradient fill style
$bplot->SetFillGradient("blue:0.9","blue:1.85",GRAD_LEFT_REFLECTION);
$bplot2->SetFillGradient("red:0.9","red:1.85",GRAD_LEFT_REFLECTION);
$bplot3->SetFillGradient("yellow:0.9","yellow:1.85",GRAD_LEFT_REFLECTION);
$bplot4->SetFillGradient("green:0.9","yellow:1.85",GRAD_LEFT_REFLECTION);

// Set color for the frame of each bar
$bplot->SetColor("white");

// Create the grouped bar plot
$gbplot = new AccBarPlot(array($bplot,$bplot2,$bplot3,$bplot4));

$graph->Add($gbplot);

// Finally send the graph to the browser
$graph->Stroke();
?>
