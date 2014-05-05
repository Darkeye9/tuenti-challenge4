<?php

$track = readline();
preg_match('/([^-]-*)$/', $track, $matches);

$x=strlen($matches[1]);
$y=0;
$dx=1;
$dy=0;

$max=0;
$birdeye=array(array());
for ($i=0; $i<strlen($track); $i++) {
    
    switch ($track[$i])
    {
	case "/":
		$birdeye[$y][$x]="/";
		//echo $y.":".$x." ".$track[$i]."\n";
		
		$temp=$dx*-1;
		$dx=$dy*-1;
		$dy=$temp;
		break;
		
	case "\\":
		$birdeye[$y][$x]="\\";
		//echo $y.":".$x." ".$track[$i]."\n";
		
		$temp=$dx;
		$dx=$dy;
		$dy=$temp;
		break;		
	
	default:
		$birdeye[$y][$x]= $dy != 0 ? "|" : $track[$i];
		//echo $y.":".$x." ".$track[$i]."\n";
		break;	
    }
    $x+=$dx;
    $y+=$dy;
    
    $max = $x > $max ? $x : $max;
}
foreach ($birdeye as $i => $a)
{
	for($j=0;$j<$max+1;$j++)
	{
		echo isset($birdeye[$i][$j]) ? $birdeye[$i][$j] : ' ';
	}
	echo "\n";
}
?>