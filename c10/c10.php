<?php 
error_reporting(E_ERROR | E_PARSE);
$input=readline();

$diff=0;
calc_diff($diff);

$result="wrong!";
$pid=1336;
while ($result == "wrong!")
{
	if ($pid % 100 == 0) calc_diff($diff);
	
	$h=date('H', time()-$diff);
	$m=date('i', time()-$diff);

	srand(mktime($h, $m, 0) * $pid);
	//echo "Date: ".gmdate("D, d M Y H:i:s", time()-$diff)." GMT\n";
	$pass=rand();

	$handle=fopen("http://random.contest.tuenti.net/index.php?input=".$input."&password=".$pass, "r");
	$result=fgets($handle);
	//echo "PID:".$pid." ".$result."\n";
	fclose($handle);
	$pid++;
	//echo rand()==$_GET['password']?json_decode(file_get_contents('../keys.json'), true)[$_GET['input']]:"wrong!";
}
echo $result."\n";

function calc_diff(&$diff)
{
	$rdate=strtotime(substr(get_headers("http://random.contest.tuenti.net")[5],23,8));
	$diff=time()-$rdate;
	//echo "Diferencia: ", $diff, " segundos\n";
}
?>