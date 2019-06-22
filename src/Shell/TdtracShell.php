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
            ->addSubcommand('cron', [
                'help' => 'Run scheduled tasks'
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
            ])
            ->addSubcommand('demoreset', [
                'help' => 'Reset the database to demo defaults',
                'parser' => [
                    'description' => 'DESTRUCTIVLY reset the database to demo defaults',
                    'arguments' => [
                        'AreYouSure' => [ 'help' => 'Enter YES in all caps to proceed with this operation', 'required' => true ]
                    ],
                    'options' => [
                        'really' => [ 'boolean' => true, 'help' => 'Really run this command', 'default' => false ]
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

    public function demoreset($really)
    {
        if ( $really <> "YES" ) {
            throw new InternalErrorException("Wrong Parameter Supplied, Please Try Again.");
        }

        if ( ! $this->params['really']) {
            throw new InternalErrorException("Wrong Parameter Supplied, Please Try Again.");
        }

        $this->out("<warning>You have really read what this does, and are totally ok with the fact that it is going to nuke all of your data!?!</warning>");
        $selection = $this->in('Nuke it All, Start Over?', ['Y', 'N'], 'N');

        if ( $selection == "Y" ) {
            $conn = ConnectionManager::get('default');

            $this->loadModel('Users');
            $this->loadModel('Shows');
            $this->loadModel('Budgets');
            $this->loadModel('Payrolls');
            $this->loadModel('Messages');
            $this->loadModel('Calendars');
            //$this->loadModel('Tasks');
            $this->Tasks = TableRegistry::get('Tasks');
            $this->loadModel('ShowUserPerms');
            $this->loadModel('Files');

            $this->out('Removing all records.');

            if ( $this->Payrolls->deleteAll([1 => 1]) ) {
                $this->out(' Delete: Payrolls');
            }
            if ( $this->Budgets->deleteAll([1 => 1]) ) {
                $this->out(' Delete: Budgets');
            }
            if ( $this->Tasks->deleteAll([1 => 1]) ) {
                $this->out(' Delete: Tasks');
            }
            if ( $this->Calendars->deleteAll([1 => 1]) ) {
                $this->out(' Delete: Calendars');
            }
            if ( $this->ShowUserPerms->deleteAll([1 => 1]) ) {
                $this->out(' Delete: ShowUserPerms');
            }
            if ( $this->Shows->deleteAll([1 => 1]) ) {
                $this->out(' Delete: Shows');
            }
            if ( $this->Messages->deleteAll([1 => 1]) ) {
                $this->out(' Delete: Messages');
            }
            if ( $this->Users->deleteAll([1 => 1]) ) {
                $this->out(' Delete: Users');
            }
            if ( $this->Files->deleteAll([1 => 1]) ) {
                $this->out(' Delete: Files');
            }


            $this->out($this->nl(1) . 'Resetting AUTO_INCREMENT');

            $conn->execute('ALTER TABLE `users` AUTO_INCREMENT = 1');
            $conn->execute('ALTER TABLE `payrolls` AUTO_INCREMENT = 1');
            $conn->execute('ALTER TABLE `budgets` AUTO_INCREMENT = 1');
            $conn->execute('ALTER TABLE `tasks` AUTO_INCREMENT = 1');
            $conn->execute('ALTER TABLE `calendars` AUTO_INCREMENT = 1');
            $conn->execute('ALTER TABLE `shows` AUTO_INCREMENT = 1');
            $conn->execute('ALTER TABLE `messages` AUTO_INCREMENT = 1');
            $conn->execute('ALTER TABLE `files` AUTO_INCREMENT = 1');
            $conn->execute('ALTER TABLE `show_user_perms` AUTO_INCREMENT = 1');

            $this->out($this->nl(1) . 'Creating Data:');

            $adminUser = $this->Users->newEntity([
                'username' => 'admin@tdtrac.com',
                'password' => 'password',
                'phone' => 1234567890,
                'first' => 'Administrative',
                'last' => 'User',
                'is_notified' => 0,
                'is_admin' => 1,
                'time_zone' => 'America/Detroit'
            ]);

            $managerUser = $this->Users->newEntity([
                'username' => 'manager@tdtrac.com',
                'password' => 'password',
                'phone' => 1234567890,
                'first' => 'Manager',
                'last' => 'User',
                'is_notified' => 0,
                'time_zone' => 'America/Detroit'
            ]);

            $regularUser = $this->Users->newEntity([
                'username' => 'regular@tdtrac.com',
                'password' => 'password',
                'phone' => 1234567890,
                'first' => 'Regular',
                'last' => 'User',
                'is_notified' => 0,
                'time_zone' => 'America/Detroit'
            ]);

            if ( $this->Users->save($adminUser) ) {
                $this->out(' Create: Admin User #' . $adminUser->id );
            }
            if ( $this->Users->save($managerUser) ) {
                $this->out(' Create: Manager User #' . $managerUser->id );
            }
            if ( $this->Users->save($regularUser) ) {
                $this->out(' Create: Regular User #' . $regularUser->id );
            }

            $show1 = $this->Shows->newEntity([
                'name' => 'Example Show #1',
                'location' => 'Somewhere',
                'end_date' => Time::createFromFormat('Y-m-d', '2020-02-14', 'UTC'),
            ]);

            $show2 = $this->Shows->newEntity([
                'name' => 'Example Show #2',
                'location' => 'Somewhere',
                'end_date' => Time::createFromFormat('Y-m-d', '2010-02-14', 'UTC'),
                'is_active' => 0
            ]);

            if ( $this->Shows->save($show1) ) {
                $this->out(' Create: Open Show - #' . $show1->id );
            }
            if ( $this->Shows->save($show2) ) {
                $this->out(' Create: Closed Show - #' . $show2->id );
            }

            $this->out(' Creating Permissions');

            $insertCol = [ "user_id", "show_id", "is_pay_admin", "is_paid", "is_budget", "is_task_admin", "is_task_user", "is_cal" ];
            
            $insertRow = [
                [ 
                    'user_id' => $adminUser->id,
                    'show_id' => $show1->id,
                    'is_pay_admin' => 1,
                    'is_paid' => 1,
                    'is_budget' => 1,
                    'is_task_admin' => 1,
                    'is_task_user' => 1,
                    'is_cal' => 1
                ],
                [ 
                    'user_id' => $adminUser->id,
                    'show_id' => $show2->id,
                    'is_pay_admin' => 1,
                    'is_paid' => 1,
                    'is_budget' => 1,
                    'is_task_admin' => 1,
                    'is_task_user' => 1,
                    'is_cal' => 1
                ],
                [ 
                    'user_id' => $managerUser->id,
                    'show_id' => $show1->id,
                    'is_pay_admin' => 1,
                    'is_paid' => 1,
                    'is_budget' => 1,
                    'is_task_admin' => 0,
                    'is_task_user' => 1,
                    'is_cal' => 1
                ],
                [ 
                    'user_id' => $managerUser->id,
                    'show_id' => $show2->id,
                    'is_pay_admin' => 1,
                    'is_paid' => 1,
                    'is_budget' => 1,
                    'is_task_admin' => 0,
                    'is_task_user' => 1,
                    'is_cal' => 1
                ],
                [ 
                    'user_id' => $regularUser->id,
                    'show_id' => $show1->id,
                    'is_pay_admin' => 0,
                    'is_paid' => 1,
                    'is_budget' => 0,
                    'is_task_admin' => 0,
                    'is_task_user' => 1,
                    'is_cal' => 1
                ],
                [ 
                    'user_id' => $regularUser->id,
                    'show_id' => $show2->id,
                    'is_pay_admin' => 0,
                    'is_paid' => 1,
                    'is_budget' => 0,
                    'is_task_admin' => 0,
                    'is_task_user' => 1,
                    'is_cal' => 1
                ],
            ];

            $insertQuery = $this->ShowUserPerms->query();

            $insertQuery->insert($insertCol);
            $insertQuery->clause('values')->setValues($insertRow);
            $insertQuery->execute();


            $this->out(' Creating Budget Items');

            $insertCol = [ "show_id", "category", "vendor", "price", "description", "date" ];
            
            $insertRow = [
                [ 
                    'show_id' => $show1->id,
                    'category' => 'Category #1',
                    'vendor' => 'Random Vendor #' . rand(12,365),
                    'price' => (rand(2234, 355433) / 100),
                    'description' => 'Random Description #' . rand(12,365),
                    'date' => Time::createFromFormat('Y-m-d', '2010-02-'.rand(10,28), 'UTC')
                ],
                [ 
                    'show_id' => $show1->id,
                    'category' => 'Category #1',
                    'vendor' => 'Random Vendor #' . rand(12,365),
                    'price' => (rand(2234, 355433) / 100),
                    'description' => 'Random Description #' . rand(12,365),
                    'date' => Time::createFromFormat('Y-m-d', '2010-02-'.rand(10,28), 'UTC')
                ],
                [ 
                    'show_id' => $show1->id,
                    'category' => 'Category #1',
                    'vendor' => 'Random Vendor #' . rand(12,365),
                    'price' => (rand(2234, 355433) / 100),
                    'description' => 'Random Description #' . rand(12,365),
                    'date' => Time::createFromFormat('Y-m-d', '2010-02-'.rand(10,28), 'UTC')
                ],
                [ 
                    'show_id' => $show1->id,
                    'category' => 'Category #1',
                    'vendor' => 'Random Vendor #' . rand(12,365),
                    'price' => (rand(2234, 355433) / 100),
                    'description' => 'Random Description #' . rand(12,365),
                    'date' => Time::createFromFormat('Y-m-d', '2010-02-'.rand(10,28), 'UTC')
                ],
                [ 
                    'show_id' => $show1->id,
                    'category' => 'Category #2',
                    'vendor' => 'Random Vendor #' . rand(12,365),
                    'price' => (rand(2234, 355433) / 100),
                    'description' => 'Random Description #' . rand(12,365),
                    'date' => Time::createFromFormat('Y-m-d', '2010-02-'.rand(10,28), 'UTC')
                ],
                [ 
                    'show_id' => $show1->id,
                    'category' => 'Category #2',
                    'vendor' => 'Random Vendor #' . rand(12,365),
                    'price' => (rand(2234, 355433) / 100),
                    'description' => 'Random Description #' . rand(12,365),
                    'date' => Time::createFromFormat('Y-m-d', '2010-02-'.rand(10,28), 'UTC')
                ],
                [ 
                    'show_id' => $show1->id,
                    'category' => 'Category #2',
                    'vendor' => 'Random Vendor #' . rand(12,365),
                    'price' => (rand(2234, 355433) / 100),
                    'description' => 'Random Description #' . rand(12,365),
                    'date' => Time::createFromFormat('Y-m-d', '2010-02-'.rand(10,28), 'UTC')
                ],
            ];

            $insertQuery = $this->Budgets->query();

            $insertQuery->insert($insertCol);
            $insertQuery->clause('values')->setValues($insertRow);
            $insertQuery->execute();

            $this->out(' Creating Payroll Items');

            $insertCol = [ "show_id", "user_id", "is_paid", "notes", "start_time", "end_time", "date_worked" ];
            
            $mins = ['00', '15', '30', '45'];
            $insertRow = [
                [ 
                    'show_id' => $show1->id,
                    'user_id' => $regularUser->id,
                    'is_paid' => 0,
                    'notes' => 'Random Note #' . rand(12,365),
                    'start_time' => Time::createFromFormat('H:i',rand(8,11) . ":" . $mins[rand(0,3)],'UTC'),
                    'end_time' => Time::createFromFormat('H:i',rand(13,17) . ":" . $mins[rand(0,3)],'UTC'),
                    'date_worked' => Time::createFromFormat('Y-m-d', '2010-02-'.rand(10,28), 'UTC')
                ],
                [ 
                    'show_id' => $show1->id,
                    'user_id' => $regularUser->id,
                    'is_paid' => 0,
                    'notes' => 'Random Note #' . rand(12,365),
                    'start_time' => Time::createFromFormat('H:i',rand(8,11) . ":" . $mins[rand(0,3)],'UTC'),
                    'end_time' => Time::createFromFormat('H:i',rand(13,17) . ":" . $mins[rand(0,3)],'UTC'),
                    'date_worked' => Time::createFromFormat('Y-m-d', '2010-02-'.rand(10,28), 'UTC')
                ],
                [ 
                    'show_id' => $show1->id,
                    'user_id' => $regularUser->id,
                    'is_paid' => 0,
                    'notes' => 'Random Note #' . rand(12,365),
                    'start_time' => Time::createFromFormat('H:i',rand(8,11) . ":" . $mins[rand(0,3)],'UTC'),
                    'end_time' => Time::createFromFormat('H:i',rand(13,17) . ":" . $mins[rand(0,3)],'UTC'),
                    'date_worked' => Time::createFromFormat('Y-m-d', '2010-02-'.rand(10,28), 'UTC')
                ],
                [ 
                    'show_id' => $show1->id,
                    'user_id' => $regularUser->id,
                    'is_paid' => 0,
                    'notes' => 'Random Note #' . rand(12,365),
                    'start_time' => Time::createFromFormat('H:i',rand(8,11) . ":" . $mins[rand(0,3)],'UTC'),
                    'end_time' => Time::createFromFormat('H:i',rand(13,17) . ":" . $mins[rand(0,3)],'UTC'),
                    'date_worked' => Time::createFromFormat('Y-m-d', '2010-02-'.rand(10,28), 'UTC')
                ],
                [ 
                    'show_id' => $show1->id,
                    'user_id' => $managerUser->id,
                    'is_paid' => 0,
                    'notes' => 'Random Note #' . rand(12,365),
                    'start_time' => Time::createFromFormat('H:i',rand(8,11) . ":" . $mins[rand(0,3)],'UTC'),
                    'end_time' => Time::createFromFormat('H:i',rand(13,17) . ":" . $mins[rand(0,3)],'UTC'),
                    'date_worked' => Time::createFromFormat('Y-m-d', '2010-02-'.rand(10,28), 'UTC')
                ],
                [ 
                    'show_id' => $show1->id,
                    'user_id' => $managerUser->id,
                    'is_paid' => 0,
                    'notes' => 'Random Note #' . rand(12,365),
                    'start_time' => Time::createFromFormat('H:i',rand(8,11) . ":" . $mins[rand(0,3)],'UTC'),
                    'end_time' => Time::createFromFormat('H:i',rand(13,17) . ":" . $mins[rand(0,3)],'UTC'),
                    'date_worked' => Time::createFromFormat('Y-m-d', '2010-02-'.rand(10,28), 'UTC')
                ],
                [ 
                    'show_id' => $show1->id,
                    'user_id' => $managerUser->id,
                    'is_paid' => 0,
                    'notes' => 'Random Note #' . rand(12,365),
                    'start_time' => Time::createFromFormat('H:i',rand(8,11) . ":" . $mins[rand(0,3)],'UTC'),
                    'end_time' => Time::createFromFormat('H:i',rand(13,17) . ":" . $mins[rand(0,3)],'UTC'),
                    'date_worked' => Time::createFromFormat('Y-m-d', '2010-02-'.rand(10,28), 'UTC')
                ],
                [ 
                    'show_id' => $show1->id,
                    'user_id' => $adminUser->id,
                    'is_paid' => 0,
                    'notes' => 'Random Note #' . rand(12,365),
                    'start_time' => Time::createFromFormat('H:i',rand(8,11) . ":" . $mins[rand(0,3)],'UTC'),
                    'end_time' => Time::createFromFormat('H:i',rand(13,17) . ":" . $mins[rand(0,3)],'UTC'),
                    'date_worked' => Time::createFromFormat('Y-m-d', '2010-02-'.rand(10,28), 'UTC')
                ],
                [ 
                    'show_id' => $show1->id,
                    'user_id' => $adminUser->id,
                    'is_paid' => 0,
                    'notes' => 'Random Note #' . rand(12,365),
                    'start_time' => Time::createFromFormat('H:i',rand(8,11) . ":" . $mins[rand(0,3)],'UTC'),
                    'end_time' => Time::createFromFormat('H:i',rand(13,17) . ":" . $mins[rand(0,3)],'UTC'),
                    'date_worked' => Time::createFromFormat('Y-m-d', '2010-02-'.rand(10,28), 'UTC')
                ],
                
            ];

            $insertQuery = $this->Payrolls->query();

            $insertQuery->insert($insertCol);
            $insertQuery->clause('values')->setValues($insertRow);
            $insertQuery->execute();


            $this->out(' Creating Task Items');

            $insertCol = [ "created_by", "assigned_to", "show_id", "due", "title", "category", "note", "task_accepted", "task_done" ];
            
            $insertRow = [
                [ 
                    'assigned_to' => $adminUser->id,
                    'created_by' => $regularUser->id,
                    'show_id' => $show1->id,
                    'due' => Time::createFromFormat('Y-m-d', '2020-02-'.rand(10,28), 'UTC'),
                    'title' => 'Random Title #' . rand(12,365),
                    'category' => 'Category #1',
                    'note' => 'Random (not accepted, not done) Description #' . rand(12,365),
                    'task_accepted' => 0,
                    'task_done' => 0
                ],
                [ 
                    'assigned_to' => $adminUser->id,
                    'created_by' => $regularUser->id,
                    'show_id' => $show1->id,
                    'due' => Time::createFromFormat('Y-m-d', '2010-02-'.rand(10,28), 'UTC'),
                    'title' => 'Random Title #' . rand(12,365),
                    'category' => 'Category #1',
                    'note' => 'Random (overdue) Description #' . rand(12,365),
                    'task_accepted' => 0,
                    'task_done' => 0
                ],
                [ 
                    'assigned_to' => $adminUser->id,
                    'created_by' => $regularUser->id,
                    'show_id' => $show1->id,
                    'due' => Time::createFromFormat('Y-m-d', '2020-02-'.rand(10,28), 'UTC'),
                    'title' => 'Random Title #' . rand(12,365),
                    'category' => 'Category #1',
                    'note' => 'Random (accepted, not done) Description #' . rand(12,365),
                    'task_accepted' => 1,
                    'task_done' => 0
                ],
                [ 
                    'assigned_to' => $adminUser->id,
                    'created_by' => $regularUser->id,
                    'show_id' => $show1->id,
                    'due' => Time::createFromFormat('Y-m-d', '2020-02-'.rand(10,28), 'UTC'),
                    'title' => 'Random Title #' . rand(12,365),
                    'category' => 'Category #1',
                    'note' => 'Random (done) Description #' . rand(12,365),
                    'task_accepted' => 1,
                    'task_done' => 1
                ],
            ];

            $insertQuery = $this->Tasks->query();

            $insertQuery->insert($insertCol);
            $insertQuery->clause('values')->setValues($insertRow);
            $insertQuery->execute();

            $this->out(' Creating Calendar Items');

            $insertCol = [ "date", "start_time", "end_time", "all_day", "show_id", "title", "category", "note" ];

            $thisYear = date('Y');
            $thisMonth = date('m');
            $insertRow = [];

            foreach ( range(1,8) as $counter ) {
                foreach ( range(1,12) as $use_month ) { 
                    $use_year = ( $thisMonth <= $use_month ) ? $thisYear : $thisYear+1;
                    $insertRow[] = 
                        [
                            'date' => Time::createFromFormat('Y-m-d', $use_year . "-" . str_pad($use_month, 2, "0", STR_PAD_LEFT) . "-" . str_pad(rand(1,28), 2, "0", STR_PAD_LEFT), 'UTC'),
                            'start_time' => Time::createFromFormat('H:i', rand(6,11) . ":" . str_pad(rand(0,5)*10, 2, "0", STR_PAD_LEFT), 'UTC'),
                            'end_time' => Time::createFromFormat('H:i', rand(13,22) . ":" . str_pad(rand(0,5)*10, 2, "0", STR_PAD_LEFT), 'UTC'),
                            'all_day' => ((rand(1,6) % 3) < 1) ? 1 : 0,
                            'show_id' => $show1->id,
                            'title' => 'Random Title #' . rand(12,365),
                            'category' => ['default','active','success','info','warning','danger'][rand(0,5)],
                            'note' => 'Random Description #' . rand(12,365)
                        ];
                }
            }

            $insertQuery = $this->Calendars->query();

            $insertQuery->insert($insertCol);
            $insertQuery->clause('values')->setValues($insertRow);
            $insertQuery->execute();


            $this->out($this->nl(1) . 'Resetting Sessions');

            $conn->execute('DELETE FROM `sessions` WHERE 1');

            $this->out($this->nl(1) . 'Finished!');
        } else {
            $this->err('Execution Stopped');
        }
    }

    public function cron() {
        $this->loadModel('Schedules');
        $all = $this->Schedules->find('all');
        date_default_timezone_set(Configure::read('ServerTimeZoneFix'));
        $real_offset = date('Z');
        date_default_timezone_set('UTC');
        $now = Chronos::now();

        $this->verbose("Runtime: " . $now);

        foreach( $all as $task ) {
            $this->verbose("Task #" . $task->id . " Period: " . $task->period . " Original: " . $task->start_time . " Last: " . $task->last_run);
            $do_this_task = false;
            if ( $task->last_run->lt($task->start_time) ) { 
                $this->verbose(' Has never run');
                if ( $task->start_time->lte($now) ) {
                    $do_this_task = true;
                    $this->verbose(' Should run now, overdue');
                } else { $this->verbose(' SKIPPING'); }
            } else {
                $this->verbose(' Has run');
                $counter = $task->start_time->toMutable();
                $breaker = true;
                $ohshit = 0;
                while ( $breaker ) {
                    $counter = $counter->modify("+".$task->period."days");
                    if ( $counter->gt($task->last_run)) { $breaker = false; $this->verbose(' Found next run: ' . $counter);}
                    $ohshit++;
                    if ( $ohshit > 10000 ) { $this->verbose(' LoopError!  Something went way wrong'); $breaker = false; }
                }
                if ( $counter->lte($now) ) {
                    $this->verbose(' Should run now!'); $do_this_task = true;
                } else { $this->verbose(' SKIPPING'); }
            }

            if ( $do_this_task ) {
                $this->verbose(' Running task type: ' . $task->jobtype);
                switch ( $task->jobtype ) {
                    case "unpaid":
                        $this->sendunpaid($task->sendto, $task->show_id); break;
                    case "remind":
                        $this->sendremind($task->show_id); break;
                    case "tasks":
                        $this->sendtask($task->sendto, $task->show_id); break;
                    case "budget":
                        $this->sendbudget($task->sendto, $task->show_id); break;
                    case "today":
                        $this->sendcal($task->sendto, $task->show_id); break;
                }
                $task->last_run = $now;
                $this->Schedules->save($task);
                $this->verbose(' Updated last run');
            }
        }
    }
    public function sendcal($sendto, $showid) {
        $this->loadModel('Calendars');
        $this->loadModel('Budgets');

        $shownamie = $this->Shows->find('all')
            ->where(['Shows.id' => $showid])->first();

        $cals = $this->Calendars->find('all')
            ->where(['show_id' => $showid])
            ->where(['date' => date('Y-m-d')])
            ->order(['all_day' => 'DESC', 'start_time' => 'ASC']);

        $datatable = [];
        
        foreach ( $cals as $item ) {
            $datatable[] = [
                ($item->all_day) ? "ALL" : $item->start_time->i18nFormat([\IntlDateFormatter::NONE, \IntlDateFormatter::SHORT], 'UTC'),
                $item->title,
                $item->note,
                ($item->all_day) ? "DAY" : $item->end_time->i18nFormat([\IntlDateFormatter::NONE, \IntlDateFormatter::SHORT], 'UTC'),
            ];
        }

        $headers = ['Start Time', 'Title', 'Description', 'End Time'];

        $email = new Email();
        $email->setTransport('default');
        $email->viewBuilder()->setHelpers(['Html', 'Gourmet/Email.Email']);
        $email->setEmailFormat('both');
        $email->setTo($sendto);
        $email->setSubject('Today\'s Events - ' . date('Y-m-d'));
        $email->setFrom('tdtracx@tdtrac.com');
        $email->viewBuilder()->setTemplate('calendar');
        $email->setViewVars(['showname' => $shownamie->name, 'headers' => $headers, 'tabledata' => $datatable]);
        $email->send();

        $this->verbose('  E-Mail Sent.');
    }

    public function sendbudget($sendto, $showid) {
        $this->loadModel('Shows');
        $this->loadModel('Budgets');

        $shownamie = $this->Shows->find('all')
            ->where(['Shows.id' => $showid])->first();

        $budgets = $this->Budgets->find('all')
            ->where(['show_id' => $showid])
            ->order(['category' => 'ASC', 'date' => 'ASC']);

        $datatable = [];
        
        $lastcat = false;
        $subtotal = 0;
        $total = 0;
        foreach ( $budgets as $item ) {
            if ( $lastcat <> $item->category ) {
                if ( $lastcat <> false ) {
                    $datatable[] = [ "", $lastcat . " Subtotal", "", "", "$" . number_format($subtotal, 2) ];
                    $subtotal = 0;
                }
                $lastcat = $item->category;
            }
            $datatable[] = [
                $item->date->i18nFormat('EEE, MMM dd, yyyy', 'UTC'),
                $item->category,
                $item->vendor,
                $item->description,
                "$" . number_format($item->price, 2)
            ];
            $subtotal += $item->price;
            $total += $item->price;
        }
        $datatable[] = [ "", $lastcat . " Subtotal", "", "", "$" . number_format($subtotal, 2) ];
        $datatable[] = [ "", "Grand Total", "", "", "$" . number_format($total, 2) ];

        $headers = ['Date', 'Category', 'Vendor', 'Description', 'Price'];

        $email = new Email();
        $email->setTransport('default');
        $email->viewBuilder()->setHelpers(['Html', 'Gourmet/Email.Email']);
        $email->setEmailFormat('both');
        $email->setTo($sendto);
        $email->setSubject('Budget List - ' . date('Y-m-d'));
        $email->setFrom('tdtracx@tdtrac.com');
        $email->viewBuilder()->setTemplate('budget');
        $email->setViewVars(['showname' => $shownamie->name, 'headers' => $headers, 'tabledata' => $datatable]);
        $email->send();

        $this->verbose('  E-Mail Sent.');
    }
    
    public function sendtask($sendto, $showid) {
        $this->Tasks = TableRegistry::get('Tasks');
        $this->loadModel('Shows');

        $shownamie = $this->Shows->find('all')
            ->where(['Shows.id' => $showid])->first();

        $taskies = $this->Tasks->find('all')
            ->where(['show_id' => $showid])
            ->join([
                'assigned' => [
                    'table' => 'users',
                    'type' => 'INNER',
                    'conditions' => 'assigned.id = Tasks.assigned_to',
                ],
                'created' => [
                    'table' => 'users',
                    'type' => 'INNER',
                    'conditions' => 'created.id = Tasks.created_by',
                ]
            ])
            ->select([
                'title', 'due', 'priority', 'category', 'note', 'id', 'created_at', 'updated_at', 'task_accepted', 'task_done', 'created_by', 'assigned_to',
                'assigned_name' => 'concat(assigned.first, " ", assigned.last)',
                'created_name' => 'concat(created.first, " ", created.last)',
                'is_overdue' => 'IF(Tasks.due < "' . date('Y-m-d') . '", 1, 0)'
            ])
            ->order(['task_done' => 'ASC', 'due' => 'ASC']);

        $headers = ['Created By', 'Assigned To', 'Category', 'Title', 'Description', 'Accepted/Complete', 'Due', 'Priority'];

        $datatable = [];

        foreach ( $taskies as $task ) {
            $datatable[] = [
                $task->created_name,
                $task->assigned_name,
                $task->category,
                $task->title,
                $task->note,
                ( $task->task_accepted ? "YES" : "NO" ) . " / " . ( $task->task_done ? "YES" : "NO" ),
                $task->due->i18nFormat([\IntlDateFormatter::MEDIUM, \IntlDateFormatter::NONE], 'UTC'),
                ["Missable","Normal","High","Critical"][$task->priority]
            ];
        }

        $email = new Email();
        $email->setTransport('default');
        $email->viewBuilder()->setHelpers(['Html', 'Gourmet/Email.Email']);
        $email->setEmailFormat('both');
        $email->setTo($sendto);
        $email->setSubject('Task List - ' . date('Y-m-d'));
        $email->setFrom('tdtracx@tdtrac.com');
        $email->viewBuilder()->setTemplate('tasks');
        $email->setViewVars(['showname' => $shownamie->name, 'headers' => $headers, 'tabledata' => $datatable]);
        $email->send();

        $this->verbose('  E-Mail Sent.');
    }

    public function sendunpaid($sendto, $showid)
    {
        $this->loadModel('Payrolls');
        $unpaid = $this->Payrolls->find()
            ->contain(['Shows', 'Users'])
            ->select([
                'id', 'date_worked', 'start_time', 'end_time', 'worked', 'is_paid', 'notes', 
                'showname' => 'Shows.name',
                'fullname' => 'concat(Users.first, " ", Users.last)',
                'activeshow' => 'Shows.is_active',
                'Shows.end_date'
            ])
            ->where(['is_paid' => 0])
            ->where(['Shows.is_active' => 1])
            ->where(['Shows.id' => $showid])
            ->order(['Users.last' => 'ASC', 'Users.first' => 'ASC', 'Shows.end_date' => 'DESC', 'date_worked' => 'DESC', 'start_time' => 'DESC']);

        $datatable = [];
        $lastuser = "";
        $subtotal = 0;

        foreach ( $unpaid as $item ) {
            if ( $item->fullname <> $lastuser ) {
                if ( $subtotal > 0 ) {
                    $datatable[] = [ $lastuser, '', 'Subtotal', '', '', '', number_format($subtotal,2) ];
                    $subtotal = 0;
                }
                $lastuser = $item->fullname;
                
            }
            $subtotal = $subtotal + $item->worked;
            $datatable[] = [
                $item->fullname,
                $item->showname,
                $item->notes,
                $item->date_worked->i18nFormat('YYYY-MM-dd', 'UTC'),
                $item->start_time->i18nFormat('H:mm', 'UTC'),
                $item->end_time->i18nFormat('H:mm', 'UTC'),
                number_format($item->worked, 2),
            ];
        }
        if ( $subtotal > 0 ) {
            $datatable[] = [ $lastuser, '', 'Subtotal', '', '', '', number_format($subtotal,2) ];
        }

        $headers = ['User Name', 'Show Name', 'Notes', 'Date Worked', 'Start Time', 'End Time', 'Hours Worked'];

        $email = new Email();
        $email->setTransport('default');
        $email->viewBuilder()->setHelpers(['Html', 'Gourmet/Email.Email']);
        $email->setEmailFormat('both');
        $email->setTo($sendto);
        $email->setSubject('Unpaid Hours - ' . date('Y-m-d'));
        $email->setFrom('tdtracx@tdtrac.com');
        $email->viewBuilder()->setTemplate('unpaid');
        $email->setViewVars(['headers' => $headers, 'tabledata' => $datatable]);
        $email->send();
        
        $this->verbose('  E-Mail Sent.');
    }

    public function sendremind($showtoremind)
    {
        $this->loadModel('Shows');
        $this->loadModel('Users');
        $this->loadModel('ShowUserPerms');

        $usersToRemind = $this->ShowUserPerms->find('all')
            ->contain(['Users'])
            ->where(['show_id' => $showtoremind ])
            ->where(['is_paid' => 1 ]);

        foreach ( $usersToRemind as $thisUser ) {
            $this->out('Sending to: ' . $thisUser->user->first);
            $email = new Email();
            $email->setTransport('default');
            $email->setEmailFormat('both');
            $email->setTo($thisUser->user->username);
            $email->setSubject('Hours are Due!');
            $email->setFrom('tdtracx@tdtrac.com');
            $email->viewBuilder()->setTemplate('hourremind');
            $email->setViewVars(['name' => $thisUser->user->first . " " . $thisUser->user->last]);
            $email->send();
        }   
        
        $this->verbose('  E-Mail(s) Sent.');
    }
}
