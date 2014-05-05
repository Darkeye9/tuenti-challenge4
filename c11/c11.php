<?php

while (1)
{
	$events=array();
	$caso=readline();
	if ($caso=="") break;
	$datos=explode('; ',$caso);
	
	$cuantos=count($datos)-1;
	//echo $cuantos." Amigos\n";
	while ($cuantos>0)
	{
		//echo "Amigo ".$cuantos."\n";
		$friend=explode(',',$datos[$cuantos]);
		//echo "ID: ".$friend[0]." Clave: ".$friend[1]."\n";
		parse_file($friend[0], $friend[1]);
		$cuantos--;
	}
	
	arsort($events);

	$output='';
	foreach ($events as $key => $val) 
	{
		if ($datos[0]>0) $datos[0]--; else break;
		$output.=$key.' ';
	}
	echo substr($output,0,strlen($output)-1)."\n";
}

function parse_file($uid, $key)
{
	global $events;

	$c = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"; // charset
	$l = 3; // string length
	$enc= file_get_contents('c11/encrypted/'.substr($uid,strlen($uid)-2).'/'.$uid.'.feed');

	for($t='',$cl=strlen($c),$s=array_fill(0,$l,0),$i=pow($cl,$l);$a=0,$i--;) {
	    for($t;$a<$l;$t.=$c[$s[$a++]]);
	    
	    $res=dec_data($enc,$key.$t,$uid);
	    //echo $t."\n";
	    if ($res !== false)
	    {
		preg_match_all('(\d{10}\s\d{7})', $res, $matches, PREG_SET_ORDER);
		foreach ($matches as $m)
		{
			$ev=explode(' ',$m[0]);
			$events[$ev[1]]=$ev[0];
			//echo "EvID: ".$ev[1]." T: ".$ev[0]."\n";
		}
		return;
	    }
	    
	    $t='';
	    for(;$a--&&++$s[$a]==$cl;$s[$a]=0);
	}
	//echo "No descifrado :(\n";
}

function dec_data($enc, $key, $uid)
{
	$dec=openssl_decrypt($enc, 'AES-256-ECB', $key,  OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING);
	//echo $dec;
	if (strpos($dec,$uid.' ')!== false)
	{
		return $dec;
	}else
	{
		return false;
	}
}

?>