#include <stdio.h>
#include <stdlib.h>

int main ()
{
	unsigned long start, lines;
	scanf("%lu,%lu",&start, &lines);
	
	char cmd[200];
	sprintf(cmd, "sed -n %lu,%lup c16/points | tr  '\\011' ',' | tr -d ' ' > subset", start, start+lines);
	system(cmd);
	
	FILE * fp;
	char * line = NULL;
	size_t len = 0;
	ssize_t read;

	fp = fopen("subset", "r");
	
	typedef struct Object{
		unsigned long x;
		unsigned long y;
		unsigned long r;
	} Object;
	
	Object objects [lines+1];

	unsigned long count=0;
	while ((read = getline(&line, &len, fp)) != -1) {
		sscanf("%lu,%lu,%lu",line, &(objects[count].x),&(objects[count].y),&(objects[count].r));
		count++;
		
	}
	if (line)
	free(line);
	unsigned long i;
	for (i=0;i<count;i++)
		printf("X:%lu Y:%lu R:%lu\n",objects[i].x,objects[i].y,objects[i].r);

	
	exit(EXIT_SUCCESS);
	
}
/*
$input=explode(",",readline());

//No pude resistirme, pero BASH es lento para el resto del proceso
shell_exec("sed -n ".$input[0].",".($input[0]+$input[1])."p c16/points | tr  '\011' ',' | tr -d ' ' > subset");

$objects=array();
$handle =fopen("subset",'r');

while (1)
{
	$line=fgets($handle);
	if ($line=="") break;
	$cosas=explode(",",$line);
	$objects[]=$cosas;
}
//echo "Parseo ok\n";

$count=0;
$c=0;
foreach ($objects as $c1)
{
	//echo $c."\n";
	foreach($objects as $c2)
	{
		if ( pow($c1[0]-$c2[0],2)+pow($c1[1]-$c2[1],2) < pow($c1[2]-$c2[2],2) 
			&& pow($c1[0]-$c2[0],2)+pow($c1[1]-$c2[1],2) != 0 )
			$count++;
	
	}
	$c++;

}
echo $count."\n";
?>*/