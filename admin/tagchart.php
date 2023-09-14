<?php
require('db.php');
$sql = "SELECT tags FROM listings";
$result = mysqli_query($connect, $sql);

// Count the occurrences of each tag
$tagCounts = array();
while ($row = mysqli_fetch_assoc($result)) {
    $tags = explode(',', $row['tags']);
    foreach ($tags as $tag) {
        $tag = trim($tag);
        if (!isset($tagCounts[$tag])) {
            $tagCounts[$tag] = 1;
        } else {
            $tagCounts[$tag]++;
        }
    }
}

// Generate data points for the chart
$dataPoints = array();
foreach ($tagCounts as $tag => $count) {
    $dataPoints[] = array("label" => $tag, "y" => $count);
}
?>
<!DOCTYPE HTML>
<html>
<head>
<script>
window.onload = function() {
 
var chart = new CanvasJS.Chart("chartContainer", {
	theme: "light2",
	animationEnabled: true,
	title: {
		text: "Tags Statistics"
	},
	data: [{
		type: "doughnut",
		indexLabel: "{symbol} - {y}",
		yValueFormatString: "#,##0.0\"%\"",
		showInLegend: true,
		legendText: "{label} : {y}",
		dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
	}]
});
chart.render();
 
}
</script>
</head>
<body>
<div id="chartContainer" style="height: 370px; width: 100%;"></div>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>
</html>              