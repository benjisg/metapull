<?php
	/*
		pulldown.php
		Authored by Benji Schwartz-Gilbert
		
		Breaks up a clean.php results file into a csv file of product fields and then downloads the associated images for each title. Images
		are saved to the given directory with an MD5 hash of the title in the product fields.
		
		Usage: php pulldown.php [Input File] [Output File] [Image Directory]
		
		Arguments In:
			Input File -> The resulting file from a successful run of clean.php
			Output File -> The file to write the CSV descriptor files to
				Results will be formatted in alternation CSV or fields and URL to the 98 pixel image for the title
			Image Directory -> The directory to save downloaded images to (make sure you have enough space)
		
	*/
	
	$inputFile = (isset($argv[1])) ? $argv[1] : null;
	$outputFile = (isset($argv[2])) ? $argv[2] : null;
	$imageDirectory = (isset($argv[3])) ? $argv[3] : null;
	
	if(!empty($inputFile)) {
		if(!empty($outputFile)) {
			if(file_exists($outputFile)) {
				echo "Found and deleting old output file\n";
				unlink($outputFile);
			}
			if(!empty($imageDirectory)) {
				try {
					if(file_exists($inputFile)) {
						$fileContents = file($inputFile, FILE_SKIP_EMPTY_LINES);
						if(!empty($fileContents)) {
							$downloaded = 0;
							$ch = curl_init();
							$fc_value = "";
							for($i =0; $i < count($fileContents); $i++) {
								// Check if we are assuming we're processing a CSV line
								if(($i % 2) == 0) {
									$fc_value = $fileContents[$i];
								} else {
									$parts = explode("]", $fileContents[$i]);
									$title = substr($parts[0], 1);
									$filename = md5($title);
									/* Only download images we don't have */
									if(!file_exists($imageDirectory . "/" . $filename . ".jpg")) {
										$fh = fopen($imageDirectory . "/" . $filename . ".jpg", "w");
										if($fh != false) {
											curl_setopt($ch, CURLOPT_URL, $parts[1]);
											curl_setopt($ch, CURLOPT_BINARYTRANSFER, TRUE);
											curl_setopt($ch, CURLOPT_FAILONERROR, TRUE);
											curl_setopt($ch, CURLOPT_NOPROGRESS, FALSE);
											curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
											curl_setopt($ch, CURLOPT_TIMEOUT, 30);
											curl_setopt($ch, CURLOPT_FILE, $fh);
											curl_exec($ch);
										} else {
											echo "Image file could not be created successfully, check directory permissions; exiting";
											echo "\n\n";
										}
										@fclose($fh);
										$downloaded++;
									}
									file_put_contents($outputFile, trim($fc_value) . ",\"" . $filename . "\"\n", FILE_APPEND);
									if(filesize($outputFile) == 0) {
										echo "Failed to pulldown image for " . $title;
									}
								}
							}
							curl_close($ch);
							echo "Finished, downloaded " . $downloaded . " images\n";
						} else {
							echo "\n";
							trigger_error("Problem encountered opening the input file; exiting");
							echo "\n\n";
						}
					} else {
						
					}
				} catch(Exception $e) {
					echo "\n";
					echo $e->getMessage();
					echo "\n\n";
				}
			} else {
				echo "No image directory specified; exiting";
				echo "\n\n";
			}
		} else {
			echo "\n";
			echo "No output file name given; exiting";
			echo "\n\n";
		}
	} else {
		echo "\n";
		echo "No input file given for processing; exiting";
		echo "\n\n";
	}

?>