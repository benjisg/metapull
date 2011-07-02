#!/bin/bash
#
# Script to pull and generate review datasets from metacritic for use
# Author: Benji Schwartz-Gilbert
# 03/13/2011
# 
# Input:
#		Number of pages starting from highest score to parse
#		The name of the file to write parsed content to

NUMBER_OF_PAGES=$1;
OUTPUT_FILE_NAME=$2;

# Optional parameters; defaults to pulling xbox360 reviews
MEDIA_TYPE=${3:-games};
if [ "$MEDIA_TYPE" = "games" ]; then
	SUBMEDIA_TYPE="all/"${4:-xbox360};
elif [ "$MEDIA_TYPE" = "music" ]; then
	MEDIA_TYPE="albums";
	SUBMEDIA_TYPE="all";
else
	SUBMEDIA_TYPE="all";
fi

DATA_DIRECTORY="data";
TEMP_FOLDER="temp";

if [ "$OUTPUT_FILE_NAME" != "" ] && [ $NUMBER_OF_PAGES -gt 0 ]; then
	if [ ! -d $DATA_DIRECTORY ]; then
		mkdir $DATA_DIRECTORY
	else
		if [ -e $DATA_DIRECTORY/$OUTPUT_FILE_NAME ]; then
			echo "Old output file found in data directory, removing it"
			rm $DATA_DIRECTORY/$OUTPUT_FILE_NAME
		fi
	fi
	
	if [ ! -d $TEMP_FOLDER ]; then
		echo "Temp folder not found, creating it"
		mkdir $TEMP_FOLDER
	fi
	
	for (( i = 0; i < $NUMBER_OF_PAGES; i++ ))
	do
		echo "Processing page "${i}
		curl -s "http://www.metacritic.com/browse/"$MEDIA_TYPE"/score/metascore/"$SUBMEDIA_TYPE"?view=detailed&page="${i} -o $TEMP_FOLDER/page${i}.txt
		php lib/clean.php $TEMP_FOLDER/page${i}.txt $DATA_DIRECTORY/$OUTPUT_FILE_NAME $MEDIA_TYPE
	done
	echo "Cleaning temp folder"
	rm $TEMP_FOLDER/*
	echo "Finished"
else
	echo
	echo "Usage: ./pull.sh [Number of Pages] [Output File Name] {Major Media Type} {Minor Media Type}"
	echo
	echo "Required:"
	echo "  [Number of Pages] -- The number of pages to pull starting from page 1 (highest ranked titles)"
	echo "  [Output File Name] -- The name of the output file to write results to"
	echo "     1) Will be placed in the data directory"
	echo "     2) Removes the file if it exists"
	echo
	echo "Optional:"
	echo "  {Major Media Type} -- The type of titles to pull"
	echo "    Valid types: games (default), movies, music, tv"
	echo "  {Minor Media Type} -- The minor media type to pull down (only applies to games); "
	echo "    Valid types: xbox360 (default), ps3, wii, psp, ds, 3ds, pc, ps2, ios, all (all platforms)" 
	echo 
fi
