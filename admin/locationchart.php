<?php

require ('db.php');
$sql = 'SELECT location, COUNT(*) AS count FROM listings GROUP BY location';
$result = mysqli_query($connect, $sql);

$dataPoints = array();
while ($row = mysqli_fetch_assoc($result)){
    $location = $row['location'];
    $count = $row['count'];
    $dataPoints[] = array(
        "y" => $row["count"],
        "label" => $row["location"]
    );
}


 
?>
<!DOCTYPE HTML>
<html>
<head>
<script>
window.onload = function() {
 
var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	theme: "light2",
	title:{
		text: "Gold Reserves"
	},
	axisY: {
		title: "Gold Reserves (in tonnes)"
	},
	data: [{
		type: "column",
		yValueFormatString: "#,##0.## tonnes",
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