<?php

$w=array(array(array()));
$count=0;
while (1)
{
	$line = str_replace(array("X","-"),array(1,0),readline());
	if ($line =="") break;
	
	for($i=0;$i<strlen($line);$i++)
	{
		$w[0][$count][$i]=$line[$i];
	}

	$count++;
}

$count=0;
while ($count != 100)
{
	$w[$count+1]=calc_new_world($w[$count]);
	
	for($i=0;$i<$count+1;$i++)
	{	
		if ($w[$i]==$w[$count+1])
		{
			echo $i." ".($count+1-$i)."\n";
			exit(0);
		}
	}
	$count++;
}

//No reinventemos la rueda
/**
* Game Of Life (PHP Implementation by Ralfe Poisson)
*
* @author Ralfe Poisson <ralfepoisson@gmail.com>
* @version 1.0
* @copyright Copyright (C) Ralfe Poisson 2012
* @license GPLv3
*/
function calc_new_world($w) {
	//echo "Nuevo\n";
	# Global Variables
	$size=8;
	$rule1=2;
	$rule2=3;
	
	# Local Variables
	$i = 0;
	$j = 0;
	$a = array();
	
	# Cycle through cells (i = row | j = column)
	for ($j=0; $j < $size; $j++) {
		for ($i = 0; $i < $size; $i++) {
			if ($i == $size-1 || $i == 0 || $j == $size-1 || $j == 0)
				{$a[$j][$i] = 0; continue;}
			# Count how many neighbours the current cell has
			$neighbours	= 	  $w[$j - 1][$i + 0]
						+ $w[$j + 1][$i + 0]
						+ $w[$j + 0][$i - 1]
						+ $w[$j + 0][$i + 1]
						+ $w[$j - 1][$i - 1]
						+ $w[$j - 1][$i + 1]
						+ $w[$j + 1][$i - 1]
						+ $w[$j + 1][$i + 1];
			
			# Rule 1: If the current cell has $rule1 number of neighbours, and is alive, it will stay alive
			# Rule 2: If the current cell has $rule2 number of neighbours, and is dead, it will become alive
			//Modificación a contribuir al repo de origen, si es que quería implementar las reglas de la wikipedia
			//que parece que si.
			if ((($neighbours == $rule1 || $neighbours == $rule2) && $w[$j][$i]==1) || ($neighbours == $rule2 && $w[$j][$i]==0)) {
				$a[$j][$i] = 1;
			}
			# Rule 3: If it has more neighbours than $rule2 or less neighbours than $rule1, it will die 
			else {
				$a[$j][$i] = 0;
			}
			//echo $j.":".$i."->".$neighbours."=>".$w[$j][$i]." ".$a[$j][$i]."\n";
		}
	}
	
	# Return the new World
	return $a;
}
?>