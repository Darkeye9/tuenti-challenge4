#!/bin/bash

read track
offset=`echo $track | awk '{match($0,"([^-]-*)$",a)}END{print length(a[1])}'`

x=$((offset + 1))
y=2

dx=1
dy=0


for i in $(seq 0 $((${#track} - 1))); do
	case "${track:$i:1}" in
	
	/) 	echo -ne "\e[$y;$((x))H${track:$i:1}"
		temp=$(($dx * -1))
		dx=$(($dy * -1))
		dy="$temp"
		
		x=$(($x + $dx))
		y=$(($y + $dy))		
		;;
	
	\\) 	echo -ne "\e[$y;$((x))H${track:$i:1}"
		temp="$dx"
		dx="$dy"
		dy="$temp"
		
		x=$(($x + $dx))
		y=$(($y + $dy))
		;;
	
	*) 	echo -ne "\e[$y;$((x))H${track:$i:1}"
		x=$(($x + $dx))
		y=$(($y + $dy))
		;;
	esac

done
echo -ne "\e[$((y + 40));0H"

