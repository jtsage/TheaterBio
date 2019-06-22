# TheaterBio
### A Theater Oriented Biograph and Headshot system
TheaterBio is a simple to use online bio and headshot submission system, with easy organization into shows, events, or jobs.


## Requirements
 * MySQL 5.7+
 * PHP 7.x+
 * Apache or similar webserver
 * Approx 100 MB of space for web files + caches (Headshots are extra!  Depending on the number of users, you may need quite a lot more!)
 * A shared or unique MySQL database

## Using as a user manager

So, this uses the basic auth from CakePHP - and adds very little functionality beyond user management.  Why might this be better than the really, really nice CakeDC user plugin?  Because it is written in user space, and a bit easier to understand.  YMMV.  But, often when I start a "simple" application like this I wish somebody had a basic pre-built user framework available I could just rip apart. 