metapull
======
Metacritic scraper to generate review datasets for games, movies, music, and tv

####Dependencies:####
*	PHP CLI
*	Bash
*  Curl

This library is broken into two parts:
<ol>
    <li>A script to pull down and parse the Highest Rated pages from Metacritic and then generate an output file with the data</li>
    <li>A script to parse the results file and generate a pure CSV file of the fields then pull down and save the image associated with each title</li>
</ol>

## Result file sample: ##
*xbox 360 reviews*

<blockquote>
<p>"Grand Theft Auto IV","98","7.9","Apr 29, 2008","M","Rockstar Games",""</p>
<p>[Grand Theft Auto IV]http://img1.gamespotcdn.com/metacritic/public/www/images/products/games/3/78b2451531891db4396d873d82accbeb-98.jpg</p>
<p>"BioShock","96","8.7","Aug 21, 2007","M","2K Games",""</p>
<p>[BioShock]http://img2.gamespotcdn.com/metacritic/public/www/images/products/games/8/beb94c19496b1e7b633f7bf7285f42ea-98.jpg</p>
<p>"The Orange Box","96","9.0","Oct 10, 2007","M","EA Games",""</p>
<p>[The Orange Box]http://img2.gamespotcdn.com/metacritic/public/www/images/products/games/5/a1e86e73c40d4ce70191b86ccbf9295f-98.jpg</p>
<p>"Mass Effect 2","96","9.0","Jan 26, 2010","M","Electronic Arts",""</p>
<p>[Mass Effect 2]http://img2.gamespotcdn.net/metacritic/public/www/images/products/games/2/7178937e2ea07ddd2ae3e46bdf746dc7-98.jpg</p>
<p>"Red Dead Redemption","95","8.9","May 18, 2010","M","Rockstar Games","Rockstar San Diego"</p>
<p>[Red Dead Redemption]http://img2.gamespotcdn.com/metacritic/public/www/images/products/games/1/1aba85e322bb75caf4fcc3f15528963e-98.jpg</p>
<p>"Gears of War","94","8.5","Nov 7, 2006","M","Microsoft Game Studios",""</p>
<p>[Gears of War]http://img1.gamespotcdn.net/metacritic/public/www/images/products/games/5/4e6253bf2efe800e05d4c2270a52a674-98.jpg</p>
<p>"The Elder Scrolls IV: Oblivion","94","8.8","Mar 20, 2006","M","2K Games",""</p>
<p>[The Elder Scrolls IV: Oblivion]http://img1.gamespotcdn.com/metacritic/public/www/images/products/games/2/180b7a5751235c7f2322eb652070c833-98.jpg</p>
</blockquote>

### Simple Result pull Examples: ###
*	Pull first 10 pages of Xbox 360 reviews

		./pull.sh 10 xbox360_reviews.txt
		
*	Pull first page of PC reviews

		./pull.sh 1 pc_reviews.txt games pc

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
	<code>valid options: movies, games, music, tv</code>
</pre>

<p>
	4.    Sub-Media type (only applies when 'games' are specified) 
</p>

<pre>
	<code>valid options: ps3, xbox360, wii, pc, psp, ds, ps2, 3ds, ios</code>
</pre>