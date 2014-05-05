#!/bin/bash

read terr2
read terr1

suspects=()
suspects+=("$terr2")
searchnew=0;
searchold=0;
while true; do
	searchold="$searchnew"
	search=`echo "${suspects[@]}" | tr ' ' '|'`
	search+='|'

	search=`echo $( awk 'BEGIN{RS=ORS="|"}!a[$0]++' <<<$search ) | sed 's |$  ' | sed 's |\s$  '`
	#echo "$search"
	searchnew="${#search}"
	#echo "$searchnew"
	
	#if [[ "${#search}" -gt 30000 ]]; then
	#	echo "Not Connected"
	#	exit
	#fi
	
	if [[ "$searchnew" == "$searchold" ]]; then
		echo "Not connected"
		exit
	fi
	
	MATCHES=`fgrep -f <(echo -ne "$search" | tr "|" '\n') c7/phone_call.log`
	
	result=`echo "${MATCHES[0]}" | grep "$terr1" | grep -n -s -I -f - c7/phone_call.log`
	if [ "$?" == 0 ]; then
		echo "$result" | awk -F':' '{print "Connected at " $1}'
		exit
	fi
	for match in $MATCHES; do
		array=($match)
		
		if [ "${array[0]}" != "$terr2" ]; then
		suspects+=(${array[0]})
		fi
		
		if [ "${array[1]}" != "$terr2" ]; then
		suspects+=(${array[1]})
		fi
	done
done
