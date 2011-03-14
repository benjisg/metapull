#!/bin/bash
#
# Script to pull and generate datasets from metacritic for use
# Author: Benji Schwartz-Gilbert
# 03/13/2011
# 
# Input:
#		# of pages starting from highest score to parse backwards
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

if [ $OUTPUT_FILE_NAME != "" ] && [ $NUMBER_OF_PAGES -gt 0 ]; then
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
	echo "Invalid parameters passed in, number of pages and output file should both be specified"
	echo 
fi