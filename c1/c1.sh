#!/bin/bash
IFS=$'\n'
read basura
COUNT=1
while read cases; do
	#echo "$cases"
	MATCHES=`grep -E -ir "$cases" c1/students.txt`

	RES=""
	#echo "Matches->$MATCHES"
	match=""
	for match in $MATCHES; do
		TEMP=`echo $match | awk '{match($0,"(.*),[F|M],",a)}END{print a[1]}'`

		RES+=$TEMP
		RES+=$'\n'
		#echo "RES->$RES"
	done

	if [ "$match" == "" ]
	then
		echo "Case #$COUNT: NONE"

	else
		TEMP=`echo  "$RES" | sort | tr "\n" , | sed 's .$  ' | sed 's .  '`
		echo "Case #$COUNT: $TEMP"
	fi
	COUNT=$[$COUNT+1]
done
unset IFS

