<?php

class Nodo
{
	var $input=0;
	var $inputs=array();
	var $outputs=array();
	var $state=0;
	
	function update()
	{
		if ($this->state != 0) return;
		$this->state=1;
		foreach ($this->inputs as $i)
		{
			global $nodes;
			$temp=explode("-",$i);
			//echo "Actualizando ".$temp[0]."\n";
			$nodes[$temp[0]]->update();
			$this->input+=min($temp[1],$nodes[$temp[0]]->input);
			//echo "Recibido ".min($temp[1],$nodes[$temp[0]]->input)." de ".$temp[0]."\n";
		}
	
	}
}
$ciudades=readline();

for (;$ciudades>0;$ciudades--)
{
	$nombre=readline();
	//echo "Nombre: ".$nombre."\n";
	$vels=explode(" ",readline());
	$items=explode(" ",readline());
	
	$nodes=array();
	for (;$items[0]>0;$items[0]--)
	{
		$node = new Nodo(); 
		$nodes[$items[0]-1]=$node;
		//echo "Nodo: ".($items[0]-1)."\n";
	
	}
		
	$node=new Nodo();
	$nodes["AwesomeVille"]=$node;
	//echo "Nodo: AwesomeVille\n";
		
	for (;$items[1]>0;$items[1]--)
	{
		$linea=readline();
		if ($linea == "") break;
		$road=explode(" ",$linea);
		//print_r($road);
		if ($road[0]==$nombre)
		{
			$nodes[$road[1]]->input=calc_flow($road);
			//echo "Nodo: ".$road[1]." input=".calc_flow($road)."\n";
		}
		elseif ($road[0]=="AwesomeVille" || $road[1]==$nombre)
			continue;
		else
		{
			$nodes[$road[1]]->inputs[]=$road[0]."-".calc_flow($road);
			$nodes[$road[0]]->outputs[]=$road[1]."-".calc_flow($road);
			//echo "Nodo: ".$road[1]." inputs+=".$road[0]."\n";
			//echo "Nodo: ".$road[0]." outputs+=".$road[1]."\n";
		}
	}
	//print_r($nodes);
	
	$nodes["AwesomeVille"]->update();
	
	echo $nombre." ".$nodes["AwesomeVille"]->input."\n";
}

function calc_flow($road)
{
	global $vels;
	return $road[2] == "normal" ? $vels[0]*$road[3]*200 : $vels[1]*$road[3]*200;
}

?>