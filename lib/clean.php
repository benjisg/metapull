<?php

	$fileToClean = $argv[1];
	$outputFile = $argv[2];
	
	if(!empty($fileToClean)) {
		if(!empty($outputFile)) {
			try {
				if(file_exists($fileToClean)) {
					$fileContents = file_get_contents($fileToClean);
					if($fileContents != false) {
						$stripped_text = strip_html_tags($fileContents);
						$stripped_text = preg_replace(
							Array(
								"/\s\s+/", 
								"/^.*\sCondensed\sList\sView\s/",
								"/^(.*)\sprev\snext\s.*/",
								"/Metascore/",
								"/Release\sDate/",
								"/Rating/",
								"/Publisher/",
								"/User\sScore\:\s([\d\.]+|tbd)\s+/"
							),
							Array(
								" ", 
								"",
								"\\1",
								"\nMetascore",
								"\nRelease Date",
								"\nRating",
								"\nPublisher",
								"\nUser Score: \\1\n\n"
							),
							$stripped_text);
						file_put_contents($outputFile, $stripped_text, FILE_APPEND);
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
	
/**
* strip_html_tags
* Function courtesy of David Robert Nadeau, Ph.D.
* http://nadeausoftware.com/
*
* Remove HTML tags, including invisible text such as style and
* script code, and embedded objects.  Add line breaks around
* block-level tags to prevent word joining after tag removal.
*/
function strip_html_tags( $text ) {
    $text = preg_replace(
        array(
          // Remove invisible content
            '@<head[^>]*?>.*?</head>@siu',
            '@<style[^>]*?>.*?</style>@siu',
            '@<script[^>]*?.*?</script>@siu',
            '@<object[^>]*?.*?</object>@siu',
            '@<embed[^>]*?.*?</embed>@siu',
            '@<applet[^>]*?.*?</applet>@siu',
            '@<noframes[^>]*?.*?</noframes>@siu',
            '@<noscript[^>]*?.*?</noscript>@siu',
            '@<noembed[^>]*?.*?</noembed>@siu',
		// Add line breaks before and after blocks
            '@</?((address)|(blockquote)|(center)|(del))@iu',
            '@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
            '@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
            '@</?((table)|(th)|(td)|(caption))@iu',
            '@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
            '@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
            '@</?((frameset)|(frame)|(iframe))@iu',
        ),
        array(
            ' ', 
			' ', 
			' ', 
			' ', 
			' ', 
			' ', 
			' ', 
			' ', 
			' ',
            "\n\$0", 
			"\n\$0",
			"\n\$0",
			"\n\$0", 
			"\n\$0",
			"\n\$0",
            "\n\$0", 
			"\n\$0",
        ),
        $text );
    return strip_tags( $text );
}

?>