<?php
$con = mysqli_connect("host","user","password","database");

// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit();
}


$datoer = array(); //bliver hentet fra sql
//indsætter data i fstoer array
$sql = "SELECT dato FROM fitness WHERE ovelse = 'Dumbell chest press' GROUP BY dato";
$result = $con->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        array_push($datoer, $row['dato']);
    }
}


$datoerpull = array(); //bliver hentet fra sql
//indsætter data i fstoer array
$sql = "SELECT dato FROM fitness WHERE ovelse = 'Rygovelse' GROUP BY dato";
$result = $con->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        array_push($datoerpull, $row['dato']);
    }
}


//laver lifting day count med tracking siden vi startede. //det er push dag
$liftingdaycount = array();
$startdato = new DateTime($datoer[0]);
foreach($datoer as $value2) {
  $temp = new DateTime($value2);
  $dif = date_diff($startdato, $temp);
  array_push($liftingdaycount, $dif);
}


//laver lifting day count med tracking siden vi startede. //det er push dag
$liftingdaycountpull = array();
$startdatopull = new DateTime($datoerpull[0]);
foreach($datoerpull as $value2) {
  $temp = new DateTime($value2);
  $dif = date_diff($startdatopull, $temp);
  array_push($liftingdaycountpull, $dif);
}




$dumbellpress = array(); //laver array til al dumbell press data
foreach($datoer as $value){
  array_push($dumbellpress, array("Thomas"=>"0", "Morten"=>"0", "Andreas"=>"0", "Niels"=>"0"));
}

$armbojninger = array(); //laver array til al dumbell press data
foreach($datoer as $value){
  array_push($armbojninger, array("Thomas"=>"0", "Morten"=>"0", "Andreas"=>"0", "Niels"=>"0"));
}

$shoulderpress = array(); //laver array til al dumbell press data
foreach($datoer as $value){
  array_push($shoulderpress, array("Thomas"=>"0", "Morten"=>"0", "Andreas"=>"0", "Niels"=>"0"));
}



$rygovelse = array(); //laver array til al dumbell press data
foreach($datoerpull as $value){
  array_push($rygovelse, array("Thomas"=>"0", "Morten"=>"0", "Andreas"=>"0", "Niels"=>"0"));
}

$bicepcurl = array(); //laver array til al dumbell press data
foreach($datoerpull as $value){
  array_push($bicepcurl, array("Thomas"=>"0", "Morten"=>"0", "Andreas"=>"0", "Niels"=>"0"));
}

$bicepcurlsitting = array(); //laver array til al dumbell press data
foreach($datoerpull as $value){
  array_push($bicepcurlsitting, array("Thomas"=>"0", "Morten"=>"0", "Andreas"=>"0", "Niels"=>"0"));
}






//indsætter data
$sql2 = "SELECT navn, ovelse, kg, rep, dato FROM fitness"; 
$result2 = $con->query($sql2);
if ($result2->num_rows > 0) {
    // output data of each row
    while($row2 = $result2->fetch_assoc()) {
     
      $tempdato = new DateTime($row2["dato"]);
      $tempdif = date_diff($startdato, $tempdato)->format('%a');
      $tempdifpull = date_diff($startdatopull, $tempdato)->format('%a');
      $tempnavn = $row2["navn"];
      $volume = $row2["kg"] * $row2["rep"];
        switch ($row2["ovelse"]) { //kig på swutch statement

          case "Dumbell chest press":
            $dumbellpress[$tempdif][$tempnavn] += $volume;
            break;

          case "armbojninger":
            $armbojninger[$tempdif][$tempnavn] += $row2["rep"]; //fordi kg er ligemøj
            
            break;

          case "Shoulder press":
            $shoulderpress[$tempdif][$tempnavn] += $volume;
            break;

          case "Rygovelse":
            $rygovelse[$tempdifpull][$tempnavn] += $volume;
            break;

          case "Standing bicep curls":
            $bicepcurl[$tempdifpull][$tempnavn] += $volume;
            break;

          case "Sitting bicep curls":
            $bicepcurlsitting[$tempdifpull][$tempnavn] += $volume;
            break;


        } 


    }
}



//echo til testing
/*for ($i = 0; $i < count($liftingdaycountpull); $i++) {
    echo "[" . $liftingdaycountpull[$i]->format('%a') . ", " . $rygovelse[$i]['Thomas'] . ", " . $rygovelse[$i]['Morten'] . ", " . $rygovelse[$i]['Andreas'] . ", " . $rygovelse[$i]['Niels'] . "], ";
} */


//echo til testing
$dumbellgraph = "";
$j = 0;
for ($i = 0; $i < count($dumbellpress); $i++) {
  
  if ($liftingdaycount[$j]) {
    $dayssincestart = $liftingdaycount[$j]->format('%a');
    $dumbellgraph = $dumbellgraph . " [" . $dayssincestart . ", " . $dumbellpress[$dayssincestart]['Thomas'] . ", " . $dumbellpress[$dayssincestart]['Morten'] . ", " . $dumbellpress[$dayssincestart]['Andreas'] . ", " . $dumbellpress[$dayssincestart]['Niels'] . "], ";
    $j++;
  }

  
}



$armbojningergraph = "";
$j = 0;
for ($i = 0; $i < count($armbojninger); $i++) {
  
  if ($liftingdaycount[$j]) {
    $dayssincestart = $liftingdaycount[$j]->format('%a');
    $armbojningergraph = $armbojningergraph . " [" . $dayssincestart . ", " . $armbojninger[$dayssincestart]['Thomas'] . ", " . $armbojninger[$dayssincestart]['Morten'] . ", " . $armbojninger[$dayssincestart]['Andreas'] . ", " . $armbojninger[$dayssincestart]['Niels'] . "], ";
    $j++;
  }

  
}



$shouldergraph = "";
$j = 0;
for ($i = 0; $i < count($shoulderpress); $i++) {
  
  if ($liftingdaycount[$j]) {
    $dayssincestart = $liftingdaycount[$j]->format('%a');
    $shouldergraph = $shouldergraph . " [" . $dayssincestart . ", " . $shoulderpress[$dayssincestart]['Thomas'] . ", " . $shoulderpress[$dayssincestart]['Morten'] . ", " . $shoulderpress[$dayssincestart]['Andreas'] . ", " . $shoulderpress[$dayssincestart]['Niels'] . "], ";
    $j++;
  }

  
}




$rygovelsegraph = "";
$j = 0;
for ($i = 0; $i < count($rygovelse); $i++) {
  
  if ($liftingdaycountpull[$j]) {
    $dayssincestart = $liftingdaycountpull[$j]->format('%a');
    $rygovelsegraph = $rygovelsegraph . " [" . $dayssincestart . ", " . $rygovelse[$dayssincestart]['Thomas'] . ", " . $rygovelse[$dayssincestart]['Morten'] . ", " . $rygovelse[$dayssincestart]['Andreas'] . ", " . $rygovelse[$dayssincestart]['Niels'] . "], ";
    $j++;
  }

  
}







$bicepgraph = "";
$j = 0;
for ($i = 0; $i < count($bicepcurl); $i++) {
  
  if ($liftingdaycountpull[$j]) {
    $dayssincestart = $liftingdaycountpull[$j]->format('%a');
    $bicepgraph = $bicepgraph . " [" . $dayssincestart . ", " . $bicepcurl[$dayssincestart]['Thomas'] . ", " . $bicepcurl[$dayssincestart]['Morten'] . ", " . $bicepcurl[$dayssincestart]['Andreas'] . ", " . $bicepcurl[$dayssincestart]['Niels'] . "], ";
    $j++;
  }

  
}






$bicepsittinggraph = "";
$j = 0;
for ($i = 0; $i < count($bicepcurlsitting); $i++) {
  
  if ($liftingdaycountpull[$j]) {
    $dayssincestart = $liftingdaycountpull[$j]->format('%a');
    $bicepsittinggraph = $bicepsittinggraph . " [" . $dayssincestart . ", " . $bicepcurlsitting[$dayssincestart]['Thomas'] . ", " . $bicepcurlsitting[$dayssincestart]['Morten'] . ", " . $bicepcurlsitting[$dayssincestart]['Andreas'] . ", " . $bicepcurlsitting[$dayssincestart]['Niels'] . "], ";
    $j++;
  }

  
}

?>




<!DOCTYPE html>
<html>
<head>
  <title>Fitness trackerrr</title>
  <style type="text/css">
    div {
   
    display: inline-block;
    float: left;
    }


  </style>
</head>
<body>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <div id="chart_div"></div>
  <div id="rygovelse"></div>
  <div id="armbojninger"></div>
  <div id="bicepcurl"></div>
  <div id="shoulderpress"></div>
  <div id="bicepcurlsitting"></div>



</body>

<?php
$navne = array("Thomas", "Morten", "Andreas", "Niels");
$p1gains = array(0, 0, 0, 0);

$count = 0;
foreach ($navne as $value) {
  $temp1 = end($dumbellpress)[$value];
  $temp2 = prev($dumbellpress)[$value];

  if($temp1 != 0) {
    $p1gains[$count] = round($temp1 / $temp2 * 100 - 100);
  $count++;
  }
}





$p2gains = array(0, 0, 0, 0);

$count = 0;
foreach ($navne as $value) {
  $temp1 = end($armbojninger)[$value];
  $temp2 = prev($armbojninger)[$value];

  if($temp1 != 0) {
    $p2gains[$count] = round($temp1 / $temp2 * 100 - 100);
  $count++;
  }
}




$p3gains = array(0, 0, 0, 0);

$count = 0;
foreach ($navne as $value) {
  $temp1 = end($shoulderpress)[$value];
  $temp2 = prev($shoulderpress)[$value];

  if($temp1 != 0) {
    $p3gains[$count] = round($temp1 / $temp2 * 100 - 100);
  $count++;
  }
}


$p4gains = array(0, 0, 0, 0);

$count = 0;
foreach ($navne as $value) {
  $temp1 = end($rygovelse)[$value];
  $temp2 = prev($rygovelse)[$value];

  if($temp1 != 0) {
    $p4gains[$count] = round($temp1 / $temp2 * 100 - 100);
  $count++;
  }
}



$p5gains = array(0, 0, 0, 0);

$count = 0;
foreach ($navne as $value) {
  $temp1 = end($bicepcurl)[$value];
  $temp2 = prev($bicepcurl)[$value];

  if($temp1 != 0) {
    $p5gains[$count] = round($temp1 / $temp2 * 100 - 100);
  $count++;
  }
}


$p6gains = array(0, 0, 0, 0);

$count = 0;
foreach ($navne as $value) {
  $temp1 = end($bicepcurlsitting)[$value];
  $temp2 = prev($bicepcurlsitting)[$value];

  if($temp1 != 0) {
    $p6gains[$count] = round($temp1 / $temp2 * 100 - 100);
  $count++;
  }
}



?>

<script type="text/javascript">google.charts.load('current', {packages: ['corechart', 'line']});
google.charts.setOnLoadCallback(drawCurveTypes);
google.charts.setOnLoadCallback(drawCurveTypes2);
google.charts.setOnLoadCallback(drawCurveTypes3);
google.charts.setOnLoadCallback(drawCurveTypes4);
google.charts.setOnLoadCallback(drawCurveTypes5);
google.charts.setOnLoadCallback(drawCurveTypes6);

function drawCurveTypes() {
      var data = new google.visualization.DataTable();
      data.addColumn('number', 'X');
      data.addColumn('number', 'Thomas + <?php echo $p1gains[0] ?>% ');
      data.addColumn('number', 'Morten + <?php echo $p1gains[1] ?>% ');
      data.addColumn('number', 'Andreas + <?php echo $p1gains[2] ?>% ');
      data.addColumn('number', 'Niels + <?php echo $p1gains[3] ?>% ');

      data.addRows([


        <?php
echo "$dumbellgraph";

          ?>


      ]);

      var options = {
        hAxis: {
          title: 'Dage siden start',
        },
        vAxis: {
          title: 'Volume'
        },
        series: {
          1: {curveType: 'function'}
        },
        width: 800,
        height: 440,
        title: 'Dumbell press gains'
      };

      var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
      chart.draw(data, options);
    }







    function drawCurveTypes2() {
      var data = new google.visualization.DataTable();
      data.addColumn('number', 'X');
      data.addColumn('number', 'Thomas + <?php echo $p2gains[0] ?>% ');
      data.addColumn('number', 'Morten + <?php echo $p2gains[1] ?>% ');
      data.addColumn('number', 'Andreas + <?php echo $p2gains[2] ?>% ');
      data.addColumn('number', 'Niels + <?php echo $p2gains[3] ?>% ');

      data.addRows([


        <?php
echo "$armbojningergraph";

          ?>


      ]);

      var options = {
        hAxis: {
          title: 'Dage siden start',
        },
        vAxis: {
          title: 'Total antal'
        },
        series: {
          1: {curveType: 'function'}
        },
        width: 800,
        height: 440,
        title: 'Armbojninger'
      };

      var chart = new google.visualization.LineChart(document.getElementById('armbojninger'));
      chart.draw(data, options);
    }




     function drawCurveTypes3() {
      var data = new google.visualization.DataTable();
      data.addColumn('number', 'X');
      data.addColumn('number', 'Thomas + <?php echo $p3gains[0] ?>% ');
      data.addColumn('number', 'Morten + <?php echo $p3gains[1] ?>% ');
      data.addColumn('number', 'Andreas + <?php echo $p3gains[2] ?>% ');
      data.addColumn('number', 'Niels + <?php echo $p3gains[3] ?>% ');

      data.addRows([


        <?php
echo "$shouldergraph";

          ?>


      ]);

      var options = {
        hAxis: {
          title: 'Dage siden start',
        },
        vAxis: {
          title: 'Volume'
        },
        series: {
          1: {curveType: 'function'}
        },
        width: 800,
        height: 440,
        title: 'Shoulder press'
      };

      var chart = new google.visualization.LineChart(document.getElementById('shoulderpress'));
      chart.draw(data, options);
    }









    function drawCurveTypes4() {
      var data = new google.visualization.DataTable();
      data.addColumn('number', 'X');
      data.addColumn('number', 'Thomas + <?php echo $p4gains[0] ?>% ');
      data.addColumn('number', 'Morten + <?php echo $p4gains[1] ?>% ');
      data.addColumn('number', 'Andreas + <?php echo $p4gains[2] ?>% ');
      data.addColumn('number', 'Niels + <?php echo $p4gains[3] ?>% ');

      data.addRows([


        <?php
echo "$rygovelsegraph";

          ?>


      ]);

      var options = {
        hAxis: {
          title: 'Dage siden start',
        },
        vAxis: {
          title: 'Volume'
        },
        series: {
          1: {curveType: 'function'}
        },
        width: 800,
        height: 440,
        title: 'Bent-Over Underhand Barbell Row'
      };

      var chart = new google.visualization.LineChart(document.getElementById('rygovelse'));
      chart.draw(data, options);
    }



    function drawCurveTypes5() {
      var data = new google.visualization.DataTable();
      data.addColumn('number', 'X');
      data.addColumn('number', 'Thomas + <?php echo $p5gains[0] ?>% ');
      data.addColumn('number', 'Morten + <?php echo $p5gains[1] ?>% ');
      data.addColumn('number', 'Andreas + <?php echo $p5gains[2] ?>% ');
      data.addColumn('number', 'Niels + <?php echo $p5gains[3] ?>% ');

      data.addRows([


        <?php
echo "$bicepgraph";

          ?>


      ]);

      var options = {
        hAxis: {
          title: 'Dage siden start',
        },
        vAxis: {
          title: 'Volume'
        },
        series: {
          1: {curveType: 'function'}
        },
        width: 800,
        height: 440,
        title: 'Standing bicep curl'
      };

      var chart = new google.visualization.LineChart(document.getElementById('bicepcurl'));
      chart.draw(data, options);
    }



    function drawCurveTypes6() {
      var data = new google.visualization.DataTable();
      data.addColumn('number', 'X');
      data.addColumn('number', 'Thomas + <?php echo $p6gains[0] ?>% ');
      data.addColumn('number', 'Morten + <?php echo $p6gains[1] ?>% ');
      data.addColumn('number', 'Andreas + <?php echo $p6gains[2] ?>% ');
      data.addColumn('number', 'Niels + <?php echo $p6gains[3] ?>% ');

      data.addRows([


        <?php
echo "$bicepsittinggraph";

          ?>


      ]);

      var options = {
        hAxis: {
          title: 'Dage siden start',
        },
        vAxis: {
          title: 'Volume'
        },
        series: {
          1: {curveType: 'function'}
        },
        width: 800,
        height: 440,
        title: 'Sitting bicep curls'
      };

      var chart = new google.visualization.LineChart(document.getElementById('bicepcurlsitting'));
      chart.draw(data, options);
    }
  </script>

</html>

<?php  
mysqli_close($con);
?>