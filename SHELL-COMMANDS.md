# TheaterBio Shell Tool Commands

For convience, a fair few operations can be achieved from the shell. This is a list of those commands:

    $ ./bin cake theater_bio 

**Usage: cake theater_bio [subcommand] [-h] [-q] [-v]**

	Subcommands:

	adduser    Add a user
	ban        Make a user inactive
	resetpass  Reset a user password
	unban      Make a user active

_To see help on a subcommand use `cake tdtrac [subcommand] --help`_

	Options:

	--help, -h     Display this help.
	--quiet, -q    Enable quiet output.
	--verbose, -v  Enable verbose output.

	
### adduser

Add a user to the database.  All arguments are required.

    cake theater_bio adduser [-a] <UserName> <NewPassword> <FirstName> <LastName>


 Options:

     --isAdmin, -a     This user is an admin

Arguments:

 * __UserName__     The e-mail address of the user
 * __NewPassword__  The new password for the user
 * __FirstName__    The first name of the user
 * __LastName__     The last name of the user

### ban / unban

Make a user inactive, or reactivate the user

    cake theater_bio ban [-h] [-q] [-v] <UserName>
    cake theater_bio unban [-h] [-q] [-v] <UserName>

Arguments:

 * __UserName__     The e-mail address of the user

### resetpass

Reset the password for a user.  This does not force the user to change it on login. 

    cake theater_bio resetpass [-h] [-q] [-v] <UserName> <NewPassword>

Arguments:

 * __UserName__     The e-mail address of the user
 * __NewPassword__  The new password for the user

