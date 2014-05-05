<?php

$actual=readline();
$destino=readline();
$poibles=array();
$count=0;
while(1)
{
	$linea=readline();
	//echo "Linea: ".$linea."\n";
	if ($linea=="") break;
	$posibles[$count]=$linea;
	$count++;
}

while($actual!=$destino)
{
	for ($i=0;$i<$count;$i++)
		if (calc_distance($actual,$posibles[$i]) == 1)
		{
			echo $actual."->";
			$actual=$posibles[$i];
			$posibles[$i]=str_repeat("0",strlen($actual));
			break;
		}
}
echo $actual."\n";

function calc_distance($dna1, &$dna2)
{
	$count=0;
	for ($i=0;$i<strlen($dna1);$i++)
		if ($dna1[$i] != $dna2[$i]) $count++;
		
	if ($count ==0) $dna2=str_repeat("0",strlen($dna1));
		
	//echo "Distance ".$dna1."::".$dna2." ".$count."\n";	
	return $count;
	
}

?>