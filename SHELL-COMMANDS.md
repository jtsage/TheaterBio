# TDTracX Shell Tool Commands

For convience, a fair few operations can be achieved from the shell. This is a list of those commands:

    $ ./bin cake tdtrac 

**Usage: cake tdtrac [subcommand] [-h] [-q] [-v]**

	Subcommands:

	adduser    Add a user
	ban        Make a user inactive
	cron       Run scheduled tasks
	demoreset  Reset the database to demo defaults
	install    Run the install routine
	resetpass  Reset a user password
	unban      Make a user active

_To see help on a subcommand use `cake tdtrac [subcommand] --help`_

	Options:

	--help, -h     Display this help.
	--quiet, -q    Enable quiet output.
	--verbose, -v  Enable verbose output.

	
### adduser

Add a user to the database.  All arguments are required.

    cake tdtrac adduser [-a] [-n] <UserName> <NewPassword> <FirstName> <LastName>


 Options:

     --isAdmin, -a     This user is an admin
     --isNotified, -n  This user is notified

Arguments:

 * __UserName__     The e-mail address of the user
 * __NewPassword__  The new password for the user
 * __FirstName__    The first name of the user
 * __LastName__     The last name of the user

### ban / unban

Make a user inactive, or reactivate the user

    cake tdtrac ban [-h] [-q] [-v] <UserName>
    cake tdtrac unban [-h] [-q] [-v] <UserName>

Arguments:

 * __UserName__     The e-mail address of the user

### resetpass

Reset the password for a user.  This does not force the user to change it on login. 

    cake tdtrac resetpass [-h] [-q] [-v] <UserName> <NewPassword>

Arguments:

 * __UserName__     The e-mail address of the user
 * __NewPassword__  The new password for the user

### install

Run the install routine - specifically, set up a couple of database trigger routines, and optionally 
create a default admin user.  This is a non-destructive process, but should only have to be run once.

### demoreset

Don't use this.  Seriously.  It nukes your entire database.  And it only asks once.  But, I guess if 
you want some test data...

    cake tdtrac demoreset [-h] [-q] [--really] [-v] <AreYouSure>

Arguments:

 * __AreYouSure__  Enter YES in all caps to proceed with this operation

The actually command to run:

    $ ./bin/cake tdtrac demoreset --really YES

But seriously.  Don't use this.
