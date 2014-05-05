<?php
class Nodo
{
	public $wagons=array();//Destino-Valor
	public $outputs=array();//Nodo-Distancia-Linea
	public $name;
	public $pos;
	public $lines=array();
}
class Train
{
	public $wagon=array();//Destino-Valor
	public $fuel;
	public $station;
	public $line;
}

$sces=readline();

while($sces>0)
{
	$nodes=array();
	$trains=array();
	
	$info=explode(',',readline());
	while($info[0]>0)
	{
		$station=explode(' ',readline());
		$node = new Nodo();
		$node->name=$station[0];
		$node->pos=$station[1];
		$node->wagons[]=array($station[2], $station[3]);
		$nodes[$station[0]]=$node;
		$info[0]--;
	}
	while($info[1]>0)
	{
		$route=explode(' ',readline());
		foreach ($route as $r)
		{
			if (strlen($r)==1)
			{
				$tren = new Train();
				$tren->fuel=$info[2];
				$tren->line=$info[1];
				$tren->station=&$nodes[$r];
				
				//Borrar
				//echo "->".$tren->line."\n";
				//print_r($nodes[$nodes[$r]->wagons[0][0]]);
				/*if (in_array($tren->line,$nodes[$nodes[$r]->wagons[0][0]]->lines))
				{
					echo "si\n";
					$tren->wagon=$nodes[$r]->wagons[0];
					unset($nodes[$r]->wagons[0]);
				}*/
				
				$trains[]=$tren;
			}else
			{
				$r2=explode('-',$r);
				$nodes[$r2[0]]->outputs[]=array($r2[1], calc_dist($r2), $info[1]);
				$nodes[$r2[1]]->outputs[]=array($r2[0], calc_dist($r2), $info[1]);
				
				if (!in_array($info[1],$nodes[$r2[0]]->lines))
					$nodes[$r2[0]]->lines[]=$info[1];
					
				if (!in_array($info[1],$nodes[$r2[1]]->lines))
					$nodes[$r2[1]]->lines[]=$info[1];
			}
		
		}
		
		$info[1]--;
	}
	
	
	//print_r($nodes);
	//print_r($trains);
	$score=0;

	foreach ($trains as $train)
	{
		//echo "Tren: ".$train->line."\n";
		while($train->fuel > 0)
		{
			$scores=calc_scores($train);
			if (count($scores)==0) break;
			usort($scores,"cmp_relscores");
			print_r($scores);
			
			$count=0;
			foreach ($scores as $s)
			{
				if ($train->fuel>=$s[1])
				{
					perform_route($s, $train);
					$count++;
				}
					break;
			}
			if ($count==0) break;
			
		}
	}

	echo $score."\n";

	$sces--;
}

function perform_route($route, &$train)
{
	global $score;
	global $nodes;
	
	//print_r($route);
	
	$score+=$route[0];
	echo $route[0]. " puntos ganados\n";
	
	if ($route[2] != '0')
	{
		//echo "Vagon de la estacion ".$route[2]." borrado\n";
		unset($nodes[$route[2]]->wagons[0]);
	}	
	
	if (isset($train->wagon))
	{
		if ($route[2] != 0)
		{
			$nodes[$route[3]]->wagons[]=$train->wagon;
			//echo "Vagon aparcado en la estacion ".$route[3]. "\n";
		}
			
		unset($train->wagon);
	}
	
	$train->fuel-=$route[1];
	$train->station=&$nodes[$route[3]];
}

function calc_scores($train)
{
	global $nodes;
	
	$res=array(); //Puntos-Coste-Origen-Destino
	
	if (isset($train->wagon[0]))
	$res[]=array(
		$train->wagon[1], 
		calc_route_length($train->station->name, $train->wagon[0],$train->line),
		0,
		$train->wagon[0]
		); //Vagon Actual
	
	//Todos los vagones de la linea
	foreach ($nodes as $n)
		if (in_array($train->line,$n->lines))
			foreach($n->wagons as $w)
				if (in_array($train->line,$nodes[$w[0]]->lines))
					$res[]=array(
						$w[1], 
						calc_route_length($train->station->name, $n->name, $train->line)+
						calc_route_length($n->name, $w[0], $train->line),
						$n->name,
						$w[0]
						);
	return $res;
}

function cmp_relscores($c1,$c2)
{
	if ($c1[0]/$c1[1] < $c2[0]/$c2[1]) return 1;
	if ($c1[0]/$c1[1] > $c2[0]/$c2[1]) return -1;
	if ($c1[0]/$c1[1] == $c2[0]/$c2[1]) return 0;
}


function calc_dist($sts)
{
	global $nodes;

	$c1=explode(',',$nodes[$sts[0]]->pos);
	$c2=explode(',',$nodes[$sts[1]]->pos);
	return abs($c1[0]-$c2[0])+abs($c1[1]-$c2[1]);
}

function calc_route_length($orig, $dest, $line)
{
	global $nodes;
	
	//echo "Ruta entre ".$orig." y ".$dest."\n";
	if ($orig==$dest)
		return 0;

	$test= new Train();
	$test->station=&$nodes[$orig];
	$test->line=$line;

	$dist=0;
	$prevst=-1;
	$prevroute=-1;
	
	for($i=0;$i<count($test->station->outputs);$i++)
	{
		$out=$test->station->outputs[$i];
		//echo "Estoy en ".$test->station->name."\n";
		
		//echo "Tanteando ".$out[0]." i=".$i." prevst=".$prevst."\n";
		if ($out[2] == $test->line && ($prevst==-1 || $out[0] != $prevst))
		{
			//echo "Elegida ruta hacia ".$out[0]."\n";
			if ($test->station->name == $orig) //Primera vez
				$prevroute=$out[0];
		
			$prevst=$test->station->name;
			$test->station=&$nodes[$out[0]];
			$i=-1;
			$dist+=$out[1];

			if ($test->station->name == $dest)
			{
				return $dist;
			}
		}
		
		if ($i==count($test->station->outputs)-1)
		{
			//echo "En el otro sentido\n";
			$prevst=$test->station->name;
			//echo "Aplicando prevest=".$prevroute."\n";
			$dist=-$dist;
			$i=-1;
		}
	}
}
?>