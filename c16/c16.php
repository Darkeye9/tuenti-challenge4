<?php
//ini_set('memory_limit', '512M');
$input=explode(",",readline());

//No pude resistirme, pero BASH es lento para el resto del proceso

shell_exec("sed -n ".$input[0].",".($input[0]+$input[1]-1)."p c16/points | tr  '\011' ',' | tr -d ' ' > subset");

$objects=array();
$handle =fopen("subset",'r');

while (1)
{
	$line=fgets($handle);
	if ($line=="") break;
	$cosas=explode(",",$line);
	$objects[]=array($cosas[0], $cosas[1], $cosas[2], false);
}
echo "Parseo ok\n";

$count=0;
$c=0;
for ($i=0;$i<$input[1];$i++)
{
	//echo $c."\n";
	for ($j=0;$j<$input[1];$j++)
	{
		
		if ($objects[$j][3] == true)
			continue;
		//echo "------------------------\n";
		//echo "(".$objects[$i][0]."-".$objects[$j][0].")^2=".pow($objects[$i][0]-$objects[$j][0],2)."\n";
		//echo "(".$objects[$i][1]."-".$objects[$j][1].")^2=".pow($objects[$i][1]-$objects[$j][1],2)."\n";
		//echo "Total: ".(pow($objects[$i][0]-$objects[$j][0],2)+pow($objects[$i][1]-$objects[$j][1],2))."\n";
		//echo "(".$objects[$i][2]."-".$objects[$j][2].")^2=".pow($objects[$i][2]-$objects[$j][2],2)."\n";
		
		//echo "------------------------\n";
		
		if ($objects[$i][0] == $objects[$j][0] && $objects[$i][1] == $objects[$j][1])
			continue;
		
		if ( pow($objects[$i][0]-$objects[$j][0],2)+pow($objects[$i][1]-$objects[$j][1],2) <= pow($objects[$i][2]+$objects[$j][2],2))
				$count++;
	
	}
	$objects[$i][3]=true;
	$c++;

}
echo $count."\n";
?>