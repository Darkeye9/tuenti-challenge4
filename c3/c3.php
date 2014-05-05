<?php
$cuantos=readline();
while($cuantos > 0)
{
	$linea=readline();
	$nums=explode(" ",$linea);
	$number=sqrt(pow($nums[0],2)+pow($nums[1],2));
	echo intval($number) == $number ? $number."\n" : number_format((float)$number, 2, '.', '')."\n";
	$cuantos--;
}
?>