<?php
include_once('jpgraph/jpgraph.php');
include_once('jpgraph/jpgraph_bar.php');

function barplot($data1, $data2, $data3){
    // Create the graph. These two calls are always required
    $graph = new Graph(500,300, 'auto');    
    $graph->SetScale("textlin");
    
    $graph->SetShadow();
    $graph->img->SetMargin(40,100,20,40); // Increase right margin for legend
    $graph->xaxis->SetTickLabels(array("Worst", "Equal", "Better"));

    // Create the bar plots
    $b1plot = new BarPlot($data1);
    $b1plot->SetFillColor("orange");
    $b1plot->SetLegend("Placebo");
    $b2plot = new BarPlot($data2);
    $b2plot->SetFillColor("blue");
    $b2plot->SetLegend("Reference");
    $b3plot = new BarPlot($data3);
    $b3plot->SetFillColor("red");
    $b3plot->SetLegend("Test");

    // Show values on top of bars
    $bar_array = array($b1plot, $b2plot, $b3plot);
    foreach ($bar_array as $plot) {
        $plot->value->Show();
        $plot->value->SetFormat('%d'); // Format the value
        $plot->value->SetColor("black");
        $plot->value->SetFont(FF_FONT1, FS_BOLD);
    }

    // Create the grouped bar plot
    $gbplot = new GroupBarPlot($bar_array);
    // ...and add it to the graph
    $graph->Add($gbplot);
    
    $graph->title->Set("First test bar plot");
    $graph->xaxis->title->Set("X-title");
    $graph->yaxis->title->Set("Y-title");
    
    $graph->title->SetFont(FF_FONT1, FS_BOLD);
    $graph->yaxis->title->SetFont(FF_FONT1, FS_BOLD);
    $graph->xaxis->title->SetFont(FF_FONT1, FS_BOLD);

    // Position the legend to the right of the graph
    $graph->legend->SetPos(0.005, 0.5, 'right', 'center');
    $graph->legend->SetColumns(1); // Arrange legend items in a single column

    // Display the graph
    $graph->Stroke();
}

$data1y = array(12, 8, 19);
$data2y = array(8, 2, 11);
$data3y = array(5, 4, 14);

// barplot($data1y, $data2y, $data3y);

// Example of a stock chart
require_once ('jpgraph/jpgraph.php');
require_once ('jpgraph/jpgraph_stock.php');
 
// Data must be in the format : open,close,min,max,median
$datay = array(
    34,42,27,45,36,
    55,25,14,59,40,
    15,40,12,47,23,
    62,38,25,65,57,
    38,49,32,64,45);
 
// Setup a simple graph
$graph = new Graph(300,200);
$graph->SetScale('textlin');
$graph->SetMarginColor('lightblue');
$graph->title->Set('Box Stock chart example');
 
// Create a new stock plot
$p1 = new BoxPlot($datay);
 
// Width of the bars (in pixels)
$p1->SetWidth(9);
 
// Uncomment the following line to hide the horizontal end lines
//$p1->HideEndLines();
 
// Add the plot to the graph and send it back to the browser
$graph->Add($p1);
$graph->Stroke();
 

?>