metapull
======
Metacritic scraper to generate review datasets for games, movies, music, and tv

####Dependencies:####
*	PHP CLI
*	Bash

## Sample data set: ##
*xbox 360 reviews*

<blockquote>
<p>
Grand Theft Auto IV
<br />
Metascore: 98 
<br />
Release Date: Apr 29, 2008 
<br />
Rating: M 
<br />
Publisher: Rockstar Games 
<br />
User Score: 8.0
</p>

<p>
BioShock 
<br />
Metascore: 96 
<br />
Release Date: Aug 21, 2007 
<br />
Rating: M 
<br />
Publisher: 2K Games 
<br />
User Score: 8.7
</p>
 
<p>
The Orange Box 
<br />
Metascore: 96 
<br />
Release Date: Oct 10, 2007 
<br />
Rating: M 
<br />
Publisher: EA Games 
<br />
User Score: 9.0
</p>
 
<p>
Mass Effect 2 
<br />
Metascore: 96 
<br />
Release Date: Jan 26, 2010 
<br />
Rating: M 
<br />
Publisher: Electronic Arts 
<br />
User Score: 9.0
</p>
</blockquote>

### Simple Run Examples: ###
*	Pull first 10 pages of Xbox 360 reviews

		./pull.sh 10 xbox360_reviews.txt

*	Pull first 5 pages of Movie reviews

		./pull.sh 5 movies.txt movies

### Parameters ###
<p>
	<em>Required</em>
	<br />
	1.    Number of pages deep to parse off of the score browser (page 0 is the highest scores)
	<br />
	2.    Name of the output file
</p>

<p>
	<em>Optional [case sensitive]</em>
	<br />
	3.    Media type
</p>

<pre>
	<code>
		valid options: movies, games, music, tv
	</code>
</pre>

<p>
	4.    Sub-Media type (only applies when 'games' are specified) 
</p>

<pre>
	<code>
		valid options: ps3, xbox360, wii, pc, psp, ds, ps2
	</code>
</pre>