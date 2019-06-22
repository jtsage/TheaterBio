# TheaterBio Install Instructions

## Download

Option 1 (Likely the better option) - Track Git:

    $ git clone https://github.com/jtsage/TheaterBio.git

Option 2 - Static Download - Visit here:

[https://github.com/jtsage/TheaterBio/releases](https://github.com/jtsage/TheaterBio/releases)

Version numbers follow SemVer - so, release 0.0.10 is **more recent** than 0.0.9

## Requirements

* Server shell access.  Power users might be able to get arround this, but there isn't now, and is 
not likely to be a web based installer.
* MySQL 5.7+ - Note, this should be database agnostic, except for the files module.  But it's 
never been tested.
* PHP 7.x - Note that 7.3 isn't supported yet.
* ~150Mb of disk space

## Configuration

Edit the _{TDTracRoot}_/config/tdtracx.php file (defaults in tdtracx.php-dist).  Below is the most 
important bits explained. 

    return [
        'Company' => [
            'longname' => 'Long version of the company name',
            'shortname' => 'Company initials',
            'adminmail' => 'Your E-mail address',
            'adminname' => Your Name,
            'servername' => 'Web address of your install',
            'welcomemail' => "Welcome E-Mail text - you probably don't need to change this"
        ],
        ...snip...
        'Datasources' => [
            'default' => [
                ...snip...
                'host' => 'SQL Server address',
                'username' => 'SQL Server username',
                'password' => 'SQL Server password',
                'database' => 'SQL Server database',
                ...snip...
            ],
        ],
        'Security' => [
            'salt' => 'make a new one of these!!!',
        ],
    ];

If you don't know how to make a salt hash, go here: 
[https://cakephp.thomasv.nl/](https://cakephp.thomasv.nl/), and Thomas V. has done it for you.

## Database Creation

If you haven't already, make sure the database from the above step exists, with the user and 
password you already set.

Then, from the install folder, you'll need to run:

    $./bin cake migrations migrate

This will create the empty database.  Next, run:

    $ ./bin cake tdtrac install

This will set up the couple routine triggers and an initial user.  You can choose **not** to use 
the initial user if you prefer, you'll need to run the following in order to add your first user:

    $ ./bin cake tdtrac adduser [-a] [-n] <UserName> <NewPassword> <FirstName> <LastName>

## Fix Permissions

Check to see if there is a ./logs and ./tmp folder.  Then, make them writable by your webserver.  From the install folder:

    $ mkdir logs; chown -R a+w logs
    $ mkdir tmp; chown -R a+w tmp

## Ready to go?

Go to the instance in a web browser **AND CHANGE THE DEFAULT PASSWORD**, as it is "password". Also, 
the username is "admin@tdtrac.com", the same as for the demo. This is probably a security risk.

## Scheduled Tasts

Using scheduled tasks?  Make sure you add it to cron. It needs to run as the webserver user.  
Something like in your crontab (assuming you have passwordless sudo access).

    $ crontab -e

Add something like:

    0 * * * * /usr/bin/sudo -u www-data /path/to/install/bin/cake tdtrac cron

Or, if your webserver user is www-data, you can add it to that user's crontab

    $ sudo crontab -u www-data -e

Add something like:

    0 * * * * /path/to/install/bin/cake tdtrac cron

These examples are running the cron processes once an hour.  That's probably plenty - TDTracX 
does not require any cron process to work, only if you want to send scheduled e-mails.

## Issue Tracker

Any issues, please head to github: 
[https://github.com/jtsage/TDTracX](https://github.com/jtsage/TDTracX)

## Author

This is the work of J.T.Sage (jtsage+datebox@gmail.com) - TDTracX is covered under the MIT License, 
inherited from the CakePHP project it relies on.  Expedited support for this software is available from the project website, for a fee.