<?php
	/*
		clean.php
		Authored by Benji Schwartz-Gilbert
		
		PHP-CLI code to strip and clean metacritic pages pulled down by curl
		[Should really only be used by the shell script but can be passed identical pages that were pulled by another means]
		
		Usage: clean.php [Input File] [Output File] [Media Type]
		
		Arguments In:
			Input File -> The HTML reviews page pulled down from curl
			Output File -> The file to write results to
				Results will be formatted in alternation CSV or fields and URL to the 98 pixel image for the title
			Media Type -> The type of media page being parsed; this is pretty important as it allows us to use the right set of parsing expressions (assumes games by default)
		
	*/
	
	$fileToClean = (isset($argv[1])) ? $argv[1] : null;
	$outputFile = (isset($argv[2])) ? $argv[2] : null;
	$inputType = (isset($argv[3])) ? $argv[3] : "games";
	
	if(!empty($fileToClean)) {
		if(!empty($outputFile)) {
			try {
				if(file_exists($fileToClean)) {
					$fileContents = file_get_contents($fileToClean);
					if($fileContents != false) {
						
						/* Make sure our file contents are UTF-8 encoded */
						$fileContents = iconv("ISO-8859-1", "UTF-8//TRANSLIT", $fileContents);
						
						if($fileContents != false) {
							/* Give the image links some special consideration */
							$pre_format = preg_replace("/\<img class\=\"product_image\ssmall_image\"\ssrc\=\"([^\"]+)[^\>]+\>/", "<span>*$1*</span>", $fileContents);
							
							/* We need to be able to discern between titles later */
							$pre_format = preg_replace("/(\<h3 class\=\"product\_title\"\>)/", "||$1", $pre_format);
							
							/* Special artist parser for music */
							if($inputType == "albums") {
								$pre_format = preg_replace("/(\<span class\=\"product\_artist\"\>)/", "##$1", $pre_format);
							}
							
							$stripped_text = strip_tags($pre_format);
							
							$stripped_text = preg_replace(
								Array(
									"/\s\s+/", 
									"/^.*\<div\sclass\=\"body\_wrap\"\>/",
									"/^(.*)\<\/div\>\<\/div\>\<\/div\>\<div\sclass\=\"page_nav\"\>.*/",
									"/\|\|/",
									"/\#\#\s\-\s/"
								),
								Array(
									" ", 
									"",
									"$1 ",
									"^Title: ",
									"^Artist: "
								),
								$stripped_text);
							
							/* Do conditional breaking */
							/* Use a ^ character to marker fields breaks as this is a character less likely to be in a title */
							/* Allows for missing tags, especially User Score (happens a lot when something is rated by critics before it's released to public) */
							switch($inputType) {
								case "games":
									$stripped_text = preg_replace(
										Array(
											"/Metascore\:/",
											"/Release\sDate\:/",
											"/Rating\:/",
											"/Publishers{0,1}\:/",
											"/User\sScore\:/",
											"/\^Title\:\s*([^\^]+)\s+\^Metascore\:\s+([^\^]+)\s+(\^Release\sDate\:\s+([^\^]*)\s+){0,1}(\^Rating\:\s+([^\^]*)\s+){0,1}(\^Publisher\:\s+([^\^]+)\s+){0,1}(\^User\sScore\:\s+([^\*]+)\s+){0,1}/",
											"/([^\^]+)\^([^\,\^]+)(\,\s([^\^]+)){0,1}\^/",
											"/([^\[]+)\[([^\]]+)\]\*([^\-]+)\-(53|game)\.jpg\*/",
											"/53w\-98.jpg/"
										),
										Array(
											"^Metascore:",
											"^Release Date:",
											"^Rating:",
											"^Publisher:",
											"^User Score:",
											"\"$1\",\"$2\",\"$10\",\"$4\",\"$6\",^$8^[$1]",
											"$1\"$2\",\"$4\"",
											"$1\n[$2]$3-98.jpg\n",
											"98w-game.jpg"
										),
										$stripped_text);
									break;
								case "movies":
									$stripped_text = preg_replace(
										Array(
											"/Metascore/",
											"/Release\sDate\:/",
											"/Rated\:/",
											"/Starring\:/",
											"/Genre\(s\)\:/",
											"/User\sScore\:/",
											"/Runtime\:/"
										),
										Array(
											"\nMetascore",
											"\nRelease Date:",
											"\nRated:",
											"\nStarring:",
											"\nGenre(s):",
											"\nUser Score:",
											"\nRuntime:"
										),
										$stripped_text);
									break;
								case "tv":
									$stripped_text = preg_replace(
										Array(
											"/Metascore/",
											"/Start\sdate\:/",
											"/Starring\:/",
											"/Genre\(s\)\:/",
											"/User\sScore\:/"
										),
										Array(
											"\nMetascore",
											"\nRelease Date:",
											"\nStarring:",
											"\nGenre(s):",
											"\nUser Score:"
										),
										$stripped_text);
									break;
								case "albums":
									$stripped_text = preg_replace(
										Array(
											"/Metascore/",
											"/Release\sDate\:/",
											"/Rating\:/",
											"/Genre\(s\)\:/",
											"/User\sScore\:/"
										),
										Array(
											"\nMetascore",
											"\nRelease Date:",
											"\nRating:",
											"\nGenre(s):",
											"\nUser Score:"
										),
										$stripped_text);
									break;
							}
							
							file_put_contents($outputFile, $stripped_text, FILE_APPEND);
						} else {
							echo "\n";
							trigger_error("Error converting character set of input text; exiting");
							echo "\n\n";
						}
					} else {
						echo "\n";
						trigger_error("Problem encountered opening the input file; exiting");
						echo "\n\n";
					}
				}
			} catch(Exception $e) {
				echo "\n";
				echo $e->getMessage();
				echo "\n\n";
			}
		} else {
			echo "\n";
			echo "No output file name given; exiting";
			echo "\n\n";
		}
	} else {
		echo "\n";
		echo "No input file given for cleaning; exiting";
		echo "\n\n";
	}

?>