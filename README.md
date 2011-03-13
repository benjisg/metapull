metapull
======
Metacritic scraper to generate review datasets for games, movies, music, and tv

####Dependencies:####
*	PHP CLI
*	Bash

## Sample data set: ##
*xbox 360 reviews*

> Grand Theft Auto IV
> Metascore: 98 
> Release Date: Apr 29, 2008 
> Rating: M 
> Publisher: Rockstar Games 
> User Score: 8.0

> BioShock 
> Metascore: 96 
> Release Date: Aug 21, 2007 
> Rating: M 
> Publisher: 2K Games 
> User Score: 8.7

> The Orange Box 
> Metascore: 96 
> Release Date: Oct 10, 2007 
> Rating: M 
> Publisher: EA Games 
> User Score: 9.0

> Mass Effect 2 
> Metascore: 96 
> Release Date: Jan 26, 2010 
> Rating: M 
> Publisher: Electronic Arts 
> User Score: 9.0

### Simple Run Examples: ###
*	Pull first 10 pages of Xbox 360 reviews

		./pull.sh 10 xbox360_reviews.txt

*	Pull first 5 pages of Movie reviews

		./pull.sh 5 movies.txt movies

### Parameters ###
*Required*
1.	Number of pages deep to parse off of the score browser (page 0 is the highest scores)
2.	Name of the output file

*Optional [case sensitive]*
3.	Media type

	valid options: movies, games, music, tv
4.	Sub-Media type (only applies when 'games' are specified) 

        valid options: ps3, xbox360, wii, pc, psp, ds, ps2
