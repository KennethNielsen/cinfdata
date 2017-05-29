<?php
// Read dateplot is a rather simple status page used only for graphs with dates on the x-axis.
include("../common_functions_v2.php");
include("graphsettings.php");
$type = "";
$settings = plot_settings($type, $params="", $ignore_invalid_type=True);
$dbi = std_dbi($settings["sql_username"]);
?>

<?php echo html_header()?>
  
<?php
if (!empty($_GET["time"])){
    $timestamp = $_GET["time"];
    $comment = $_GET["comment"];
    $query = "select id from " . $settings["measurements_table"] . " where time = \"" . $timestamp . "\"";
    $result  = mysqli_query($dbi, $query);  
    $i = 0;
    while ($row = mysqli_fetch_array($result)){
        $query = "update ". $settings["measurements_table"] . " set comment = \"" . $comment . "\", time = time where id = " . $row[0];
        mysqli_query($dbi, $query);  # This looks like a mistake, since the result isn't saved
        $valid = $i++;
    }
    if ($i==0){
        echo("<b>Not a valid timestamp</b>");
    }
    else{
        echo("<b>Updated " . $i . " measurement rows</b>");
    }
}
?>


<form action="modify_comment.php" method="get">
Select timestamp to modify<br>
<input name="time" type="text" size="13"><br>
New comment:<br>
<input name="comment" type="text" size="100"><br>
<input type="submit" value="Engage"><br>

<?php
$query = "select distinct time, comment from " . $settings["measurements_table"] . " order by time desc";
$result  = mysqli_query($dbi, $query);  
while ($row = mysqli_fetch_array($result)){
    print($row[0] . " - " . $row[1] .  "<br>");
}
?>



<?php echo html_footer()?>
