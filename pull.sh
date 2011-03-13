#!/bin/bash
#
# Script to pull and generate datasets from metacritic for use
# Author: Benji Schwartz-Gilbert
#
# 

NUMBER_OF_PAGES=$1;
OUTPUT_FILE_NAME=$2;

# Optional parameters; defaults to pulling xbox360 reviews
MEDIA_TYPE=${3:-games};
if [ $MEDIA_TYPE = "games" ]; then
	SUBMEDIA_TYPE="all/"${4:-xbox360};
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
		curl -s "http://www.metacritic.com/browse/"$MEDIA_TYPE"/score/metascore/all/"$SUBMEDIA_TYPE"?view=detailed&page="${i} -o $TEMP_FOLDER/page${i}.txt
		php lib/clean.php $TEMP_FOLDER/page${i}.txt $DATA_DIRECTORY/$OUTPUT_FILE_NAME
	done
	echo "Cleaning temp folder"
	rm $TEMP_FOLDER/*
	echo "Finished"
else
	echo
	echo "Invalid parameters passed in, number of pages and output file should both be specified"
	echo 
fi