<?php

require_once 'inc/functions.php';
include_once 'inc/labels.php';

$file = 'bonnie.csv';
if ($_GET['run']) {
	$run = preg_replace('/[^\d]/', '', $_GET['run']);
	$run .= '.csv';
	if (file_exists($run)) $file = $run;
}
$data = parse_bonnie_csv($file);

echo <<<EOT
<!DOCTYPE html>
<html>
  <head>
	<title>bonnie2gchart</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
EOT;
    foreach ($types as $key => $type) {
      echo "google.setOnLoadCallback(drawChart_". $key .");\n";
      echo "function drawChart_" . $key . "() {\n";
      echo <<<EOT
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Tests');

EOT;

	foreach ($data['name'] as $val) {
		printf("        data.addColumn('number', '%s')\n", $val);
	}

foreach($types[$key]['types'] as $label)
	echo addRow($data[$label], $labels[$label]);

echo <<<EOT
        var options = {
          title: '{$types[$key]['title']}',
          vAxis: {title: '{$types[$key]['name']}',  titleTextStyle: {color: 'red'}}
        };

EOT;

        echo "var chart = new google.visualization.BarChart(document.getElementById('chart_div_". $key ."'));\n";
	echo <<<EOT
        chart.draw(data, options);
      }
EOT;
}
ECHO <<<EOT
    </script>
  </head>
  <body>
    <h1>Bonnie++ graph</h1>
EOT;
    echo "<p>" . $data['comment']  . "</p>\n";

    foreach ($types as $key => $type) {
    	echo '<p id="' . $key . '"><a href="#' . $key . '">' . $types[$key]['name'] . '</a></p>';
        echo '<div id="chart_div_'. $key .'" style="width: 900px; height: 500px;"></div>';
    }
echo <<<EOT
  <footer>
  <p>Forked from <a href="https://github.com/pommi/bonnie2gchart">GitHub</a>, modifications made by Annttu</p>
  </body>
</html>

EOT;
