<?php
namespace App\Shell;

use Cake\Console\Shell;
use Cake\Datasource\ConnectionManager;
use Cake\Http\Exception\InternalErrorException;
use Cake\I18n\Time;
use Cake\Mailer\Email;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;
use Cake\Chronos\Chronos;

/**
 * Tdtrac shell command.
 */
class TdtracShell extends Shell
{

    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser
            ->setDescription('A small set of utilities to streamline administering TDTracX')
            ->addSubcommand('install', [
                'help' => 'Run the install routine'
            ])
            ->addSubcommand('adduser', [
                'help' => 'Add a user',
                'parser' => [
                    'description' => 'Add a user to the current system',
                    'arguments' => [
                        'UserName' => [ 'help' => 'The e-mail address of the user', 'required' => true ],
                        'NewPassword' => [ 'help' => 'The new password for the user', 'required' => true ],
                        'FirstName' => [ 'help' => 'The first name of the user', 'required' => true ],
                        'LastName' => [ 'help' => 'The last name of the user', 'required' => true ]
                    ],
                    'options' => [
                        'isAdmin' => [ 'short' => 'a', 'boolean' => true, 'help' => 'This user is an admin', 'default' => false ],
                        'isNotified' => [ 'short' => 'n', 'boolean' => true, 'help' => 'This user is notified', 'default' => false ]
                    ]
                ]
            ])
            ->addSubcommand('resetpass', [
                'help' => 'Reset a user password',
                'parser' => [
                    'description' => 'Reset a user\'s password',
                    'arguments' => [
                        'UserName' => [ 'help' => 'The e-mail address of the user', 'required' => true ],
                        'NewPassword' => [ 'help' => 'The new password for the user', 'required' => true ]
                    ]
                ]
            ])
            ->addSubcommand('unban', [
                'help' => 'Make a user active',
                'parser' => [
                    'description' => 'Mark a user as active, allowing login',
                    'arguments' => [
                        'UserName' => [ 'help' => 'The e-mail address of the user', 'required' => true ],
                    ]
                ]
            ])
            ->addSubcommand('ban', [
                'help' => 'Make a user inactive',
                'parser' => [
                    'description' => 'Mark a user as inactive, preventing login',
                    'arguments' => [
                        'UserName' => [ 'help' => 'The e-mail address of the user', 'required' => true ],
                    ]
                ]
            ]);
        return $parser;
    }
    /**
     * main() method.
     *
     * @return bool|int Success or error code.
     */
    public function main() 
    {
        return $this->out($this->getOptionParser()->help());
    }

    public function resetpass($user, $pass)
    {
        $this->loadModel('Users');

        if ( $thisUser = $this->Users->findByUsername($user)->first() ) {
            $this->out('Changing password for: ' . $thisUser->first . " " . $thisUser->last);
            $thisUser->password = $pass;
            if ( $this->Users->save($thisUser) ) {
                $this->out('New password saved');
            } else {
                $this->out('Unable to update password');
            }
        } else {
            $this->err('User not found');
        }
    }

    public function unban($user)
    {
        $this->loadModel('Users');

        if ( $thisUser = $this->Users->findByUsername($user)->first() ) {
            $this->out('Setting user active: ' . $thisUser->first . " " . $thisUser->last);
            $thisUser->is_active = 1;
            if ( $this->Users->save($thisUser) ) {
                $this->out('User now active');
            } else {
                $this->out('Unable to update user');
            }
        } else {
            $this->err('User not found');
        }
    }

    public function ban($user)
    {
        $this->loadModel('Users');

        if ( $thisUser = $this->Users->findByUsername($user)->first() ) {
            $this->out('Setting user inactive: ' . $thisUser->first . " " . $thisUser->last);
            $thisUser->is_active = 0;
            if ( $this->Users->save($thisUser) ) {
                $this->out('User now inactive');
            } else {
                $this->out('Unable to update user');
            }
        } else {
            $this->err('User not found');
        }
    }

    public function adduser($user, $pass, $first, $last) {
        $this->loadModel('Users');

        $thisUser = $this->Users->newEntity([
            'username' => $user,
            'password' => $pass,
            'first' => $first,
            'last' => $last,
            'is_notified' => ($this->params['isNotified'] ? 1:0 ),
            'is_admin' => ($this->params['isAdmin'] ? 1:0 ),
            'time_zone' => 'America/Detroit'
        ]);

        if ( $this->Users->save($thisUser) ) {
            $this->out('Added user: ' . $thisUser->first . " " . $thisUser->last);
        } else {
            $this->err('Unable to add user');
        }
    }

    public function install() 
    {
        $conn = ConnectionManager::get('default');

        $selection = $this->in('Set up triggers?', ['Y', 'N'], 'Y');

        if ( $selection == 'Y' || $selection == 'y' ) {
            $this->out('Setting up triggers');

            $tiggersql1 = "CREATE TRIGGER `compute_work_ins` BEFORE INSERT ON `payrolls` FOR EACH ROW SET NEW.worked = time_to_sec(timediff(NEW.end_time, NEW.start_time))/(60*60);";
            $tiggersql2 = "CREATE TRIGGER `compute_work_upd` BEFORE UPDATE ON `payrolls` FOR EACH ROW SET NEW.worked = time_to_sec(timediff(NEW.end_time, NEW.start_time))/(60*60);";
            $conn->execute("DROP TRIGGER IF EXISTS `compute_work_upd`");
            $conn->execute("DROP TRIGGER IF EXISTS `compute_work_ins`");
            $conn->execute($tiggersql1);
            $conn->execute($tiggersql2);

            $this->out('Done.' . $this->nl(1));
        } else {
            $this->out('Skipping...' . $this->nl(1));
        }

        $selection = $this->in('Add admin@tdtrac.com::password?', ['Y', 'N'], 'Y');

        if ( $selection == 'Y' || $selection == 'y' ) {
            $this->out('Adding user');
            $this->loadModel('Users');

            $adminUser = $this->Users->newEntity([
                'username' => 'admin@tdtrac.com',
                'password' => 'password',
                'phone' => 1234567890,
                'first' => 'Administrative',
                'last' => 'User',
                'is_notified' => 1,
                'is_admin' => 1,
                'time_zone' => 'America/Detroit'
            ]);
            if ( $this->Users->save($adminUser) ) {
                $this->out('Create: Admin User #' . $adminUser->id  . $this->nl(1));
                $this->out('Please change the password as soon as possible.' . $this->nl(1));
            } else {
                $this->out('Unable to add - duplicate email probably' . $this->nl(1));
            }
            
        } else {
            $this->out('Skipping...' . $this->nl(1));
        }

        $this->out('Nothing else to do.');
    }

    
}
