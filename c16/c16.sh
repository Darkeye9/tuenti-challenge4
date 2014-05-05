#!/bin/bash

IFS=","
read input
info=($input)
lineas=`sed -n ${info[0]},$((info[0] + info[1]))p c16/points2 | tr  '\011' ',' | tr -d ' '`
#echo "$lineas"
echo "Archivo OK"
cols=0
c=0
unset IFS
for c1s in $lineas; do
		#echo "$c"
		IFS=","
		c1=($c1s)
		
		unset IFS
		for c2s in $lineas; do
		IFS=","
		c2=($c2s)
		
		dx=$((c1[0]-c2[0]))
		dy=$((c1[1]-c2[1]))
		radii=$((c1[2]+c2[2]))
		temp1=$(((dx*dx)+(dy*dy)))
		radii2=$((radii*radii))
		
		echo "----------------"
		echo "${c1[0]}-${c2[0]}=$dx"
		echo "${c1[1]}-${c2[1]}=$dy"
		echo "$temp1"
		echo "(${c1[2]}-${c2[2]})^2=$radii2"
		echo "-----------------"
		
		#echo "$temp1 $radii2"
		if [ "$temp1" -lt "$radii2" ] && [ "$temp1" -ne 0 ]; then
		cols=$((cols+1))
		fi
		#echo "$c1s   $c2s"
		#echo "X1: ${c1[0]} X2: ${c2[0]}"
		#echo "Y1: ${c1[1]} Y2: ${c2[1]}"
		#echo "R1: ${c1[2]} R2: ${c2[2]}"
		
		

		done
		c=$((c+1))
done
echo "$cols"
