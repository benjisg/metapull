<?php

	$fileToClean = (isset($argv[1])) ? $argv[1] : null;
	$outputFile = (isset($argv[2])) ? $argv[2] : null;
	$inputType = (isset($argv[3])) ? $argv[3] : "games";
	
	if(!empty($fileToClean)) {
		if(!empty($outputFile)) {
			try {
				if(file_exists($fileToClean)) {
					$fileContents = file_get_contents($fileToClean);
					if($fileContents != false) {
						$fileContents = iconv("ISO-8859-1", "UTF-8//TRANSLIT", $fileContents);
						if($fileContents != false) {
							/* We need to be able to discern between titles later */
							$pre_format = preg_replace("/(\<h3 class\=\"product\_title\"\>)/", "||\\1", $fileContents);
							
							/* Special artist parser for music */
							if($inputType == "albums") {
								$pre_format = preg_replace("/(\<span class\=\"product\_artist\"\>)/", "##\\1", $pre_format);
							}
							
							$stripped_text = strip_tags($pre_format);
							$stripped_text = preg_replace(
								Array(
									"/\s\s+/", 
									"/^.*\sCondensed\sList\sView\s/",
									"/^(.*)\sprev\snext\s.*/",
									"/\|\|/",
									"/\#\#\s\-\s/"
								),
								Array(
									" ", 
									"",
									"\\1",
									"\n\nTitle: ",
									"\nArtist: "
								),
								$stripped_text);
							
							/* Do conditional breaking */
							/* Allows for missing tags, especially User Score (happens a lot when something is rated by critics before it's released to public) */
							switch($inputType) {
								case "games":
									$stripped_text = preg_replace(
										Array(
											"/Metascore/",
											"/Release\sDate/",
											"/Rating/",
											"/Publisher/",
											"/User\sScore/"
										),
										Array(
											"\nMetascore",
											"\nRelease Date",
											"\nRating",
											"\nPublisher",
											"\nUser Score"
										),
										$stripped_text);
									break;
								case "movies":
									$stripped_text = preg_replace(
										Array(
											"/Metascore/",
											"/Release\sDate/",
											"/Rated/",
											"/Starring/",
											"/Genre\(s\)/",
											"/User\sScore\/",
											"/Runtime/"
										),
										Array(
											"\nMetascore",
											"\nRelease Date",
											"\nRated",
											"\nStarring",
											"\nGenre(s)",
											"\nUser Score",
											"\nRuntime"
										),
										$stripped_text);
									break;
								case "tv":
									$stripped_text = preg_replace(
										Array(
											"/Metascore/",
											"/Start\sdate/",
											"/Starring/",
											"/Genre\(s\)/",
											"/User\sScore/"
										),
										Array(
											"\nMetascore",
											"\nRelease Date",
											"\nStarring",
											"\nGenre(s)",
											"\nUser Score"
										),
										$stripped_text);
									break;
								case "albums":
									$stripped_text = preg_replace(
										Array(
											"/Metascore/",
											"/Release\sDate/",
											"/Rating/",
											"/Genre\(s\)/",
											"/User\sScore/"
										),
										Array(
											"\nMetascore",
											"\nRelease Date",
											"\nRating",
											"\nGenre(s)",
											"\nUser Score"
										),
										$stripped_text);
									break;
							}
							
							file_put_contents($outputFile, $stripped_text, FILE_APPEND);
						} else {
							trigger_error("Error converting character set of input text");
							echo "";
						}
					} else {
						trigger_error("Problem encountered opening the input file");
						echo "";
					}
				}
			} catch(Exception $e) {
				echo $e->getMessage();
				echo "";
			}
		} else {
			echo "No output file name given";
			echo "";
		}
	} else {
		echo "No input file given for cleaning";
		echo "";
	}

?>