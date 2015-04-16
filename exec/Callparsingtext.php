<?php
exec("/var/www/ScTeam11/parsing data.txt",$json);
$json = implode($json);
//$body = '[{"srcacc":123,"destacc":345,"amount":1,"tan":"a123sA"}, {"srcacc":198,"destacc":234,"amount":3,"tan":"aoefj1"}]';
//print_r($body);
//print_r($json);

$json=json_decode($json);
//echo $json[0]->srcacc;
//print_r ($json);
for ($i=0; $i<sizeof($json) - 1; $i++) {
	$destacc[$i] = $json[$i]->destacc;
	$amount[$i] = $json[$i]->amount;
}
$sum = $json[sizeof($json)-1];
print_r ($sum);

?>
