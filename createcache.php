<?php
include("config.php");
file_put_contents("cache.html","Cache is being created, please refresh in one minute");
$n = intval(getopt('f:')['f']);
if ($n != 1 && $n != 2 && $n !=3) {
        die();
}
$table = "words" . $n;
$output = "";
$rank = 1;
$query = "SELECT SUM(count) AS scount FROM $table ORDER BY count DESC";
$result = $conn->query($query);
$row = $result->fetch_assoc();
$tot = $row['scount'];
$query = "SELECT count FROM $table ORDER BY count DESC LIMIT 1";
$result = $conn->query($query);
$row = $result->fetch_assoc();
$highest = $row['count'];
$output = $output.'<table id="loading" style="float:left;text-align: center;" border="1" cellpadding="0" cellspacing="0">
        <tbody><tr style="text-align:center"><td>Rank</td><td>Word</td><td>Count</td><td>Last time</td><td>Percent total</td><td>Percent representation</td><td>Color</td></tr>';
$query = "SELECT word, count, created FROM $table ORDER BY count DESC LIMIT 1000";
$result = $conn->query($query);
while ($row = $result->fetch_assoc()) {
        $output = $output."<tr style='text-align: center'>";
        $word = $row["word"];
        $count = $row["count"];
        $created = $row["created"];
        $per = round($count / $tot * 100, 2);
        $rep = round($count / $highest * 100, 2);
        $c = round(255 - ( 255 * ($count / $highest))) ;
        $output = $output."<td>$rank</td>";
        $output = $output."<td>$word</td>";
        $output = $output."<td>$count</td>";
        $output = $output."<td>$created</td>";
        $output = $output."<td>$per</td>";
        $output = $output."<td>$rep</td>";
        $output = $output."<td style='background-color: rgb($c,$c,$c)'> </td>";
        $output = $output."</tr>";
        $rank++;
}
$output = $output."</tbody></table>";
file_put_contents("cache.html",$output);
