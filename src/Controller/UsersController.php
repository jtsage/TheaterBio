<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Mailer\Email;
use Cake\I18n\Time;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{

    public function beforeFilter(\Cake\Event\Event $event)
    {
        $this->Auth->allow(['logout', 'forgotPassword', 'resetPassword']);
    }
    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        if ( ! $this->Auth->user('is_admin')) {
            return $this->redirect(['action' => 'view', $this->Auth->user('id')]);
        }
        $this->paginate = [
            'order' => [
                'Users.last' => 'ASC',
                'Users.first' => 'ASC'
            ]
        ];

        $this->set('crumby', [
            ["/", "Dashboard"],
            [null, "User List"]
        ]);

        $this->set('users', $this->paginate($this->Users));
        $this->set('_serialize', ['users']);


        $this->set('tz', Configure::read('ServerTimeZoneFix'));
    }



    public function login()
    {
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);
                
                $goodUser = $this->Users->get($this->Auth->user('id'));
                
                $this->Users->touch($goodUser, 'Users.afterLogin');
                $goodUser->reset_hash = null;
                $goodUser->reset_hash_time = date('Y-m-d H:i:s', 1);
                $this->Users->save($goodUser);

                $this->set('UserTemp', $this->Auth->user('is_active'));

                if ( $this->Auth->user('is_password_expired')) {
                    $this->Flash->error(__("Your password has expired, please change it!"));
                    return $this->redirect(['controller' => 'Users', 'action' => 'changepass', $this->Auth->user('id')]); 
                }

                if ( ! $this->Auth->user('is_active')) {
                    $this->Flash->error(__("Your account is disabled, please contact your system adminitrator"));
                    return $this->redirect($this->Auth->logout());
                }

                if ( ! $this->Auth->user('is_verified')) {
                    $this->Flash->error(__("Your account is not yet verified, please check your e-mail for details."));
                    return $this->redirect($this->Auth->logout());
                }

                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error('Your username or password is incorrect.');
        }
    }

    public function logout()
    {
        $this->Flash->success(__('You are now logged out.'));
        return $this->redirect($this->Auth->logout());
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        if ( !$this->Auth->user('is_admin') && $id <> $this->Auth->user('id') ) {
            $this->Flash->error(__('You may only view and edit your own user record. (Loaded)'));
            return $this->redirect(['action' => 'view', $this->Auth->user('id')]);
        }
        $user = $this->Users->get($id, [
            'contain' => ['Bios', 'Headshots']
        ]);

        if ( $this->Auth->user('is_admin')) {
            $this->set('crumby', [
                ["/", __("Dashboard")],
                ["/users/", __("Users")],
                [null, __("View User")]
            ]);
        } else {
            $this->set('crumby', [
                ["/", __("Dashboard")],
                [null, __("Your Profile")]
            ]);
        }

        $this->set('user', $user);
        $this->set('_serialize', ['user']);
        $this->set('tz', Configure::read('ServerTimeZoneFix'));
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        if ( ! $this->Auth->user('is_admin')) {
            $this->Flash->error(__('You may not add users'));
            return $this->redirect(['action' => 'view', $this->Auth->user('id')]);
        }

        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());

            if ( $this->request->getData('welcomeEmailSend') ) {
                $email = new Email('default');
                $email->setTo(rtrim($user->username))
                    ->setSubject('Welcome to TDTracX');
                $email->send(preg_replace("/\n/", "<br />\n", $this->request->getData('welcomeEmail')));
            }
            if ( $this->request->getData('welcomeEmailSendCopy') ) {
                $email = new Email('default');
                $email->setTo(CINFO['adminmail'])
                    ->setSubject('Welcome to TDTracX: ' . $this->request->getData('first') . " " .  $this->request->getData('last'));
                $email->send(preg_replace("/\n/", "<br />\n", $this->request->getData('welcomeEmail')));
            }

            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }

        $this->set('crumby', [
            ["/", __("Dashboard")],
            ["/users/", __("Users")],
            [null, __("Add User")]
        ]);
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        if ( ! $this->Auth->user('is_admin') ) {
            if ( $id <> $this->Auth->user('id') ) {
                $this->Flash->error(__('You may only change your own user record. (Loaded)'));
            }
            return $this->redirect(['action' => 'safeedit', $this->Auth->user('id')]);
        }
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }
        $this->set('crumby', [
            ["/", __("Dashboard")],
            ["/users/", __("Users")],
            ["/users/view/" . $user->id, $user->first . " " . $user->last],
            [null, __("Edit User")]
        ]);
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    public function safeedit($id = null)
    {
        if ( !$this->Auth->user('is_admin') && $id <> $this->Auth->user('id') ) {
            $this->Flash->error(__('You may edit your own user record. (Loaded)'));
            return $this->redirect(['action' => 'safeedit', $this->Auth->user('id')]);
        }
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData(), [
                'field' => ['first', 'last', 'print_name']
            ]);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }
        $this->set('crumby', [
            ["/", __("Dashboard")],
            ["/users/view/" . $user->id, __("Your Profile")],
            [null, __("Edit Profile")]
        ]);
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    public function changepass($id = null)
    {
        if ( !$this->Auth->user('is_admin') && $id <> $this->Auth->user('id') ) {
            $this->Flash->error(__('You may only change your own password. (Loaded)'));
            return $this->redirect(['action' => 'changepass', $this->Auth->user('id')]);
        }
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData(), ['field' => ['password', 'is_password_expired']]);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }
        if ( $this->Auth->user('is_admin') ) {
            $this->set('crumby', [
                ["/", __("Dashboard")],
                ["/users/", __("User List")],
                ["/users/view/" . $user->id, $user->first . " " . $user->last],
                [null, __("Change Password")]
            ]);
        } else {
            $this->set('crumby', [
                ["/", __("Dashboard")],
                ["/users/view/" . $user->id, __("Your Profile")],
                [null, __("Change Your Password")]
            ]);
        }
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        if ( ! $this->Auth->user('is_admin')) {
            $this->Flash->error(__('You may not delete users'));
            return $this->redirect(['action' => 'view', $this->Auth->user('id')]);
        }
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }

    function __genPassToken($user) {
        if (empty($user)) { return null; }
        
        $token_raw = random_bytes(30);
        $token_hex = bin2hex($token_raw);


        $token_expire_timestamp = time() + (60*60*24);
        $token_expire = date('Y-m-d H:i:s', $token_expire_timestamp);
        
        $user->reset_hash = $token_hex;
        $user->reset_hash_time = $token_expire;
        
        return $user;
    }

    function __genVerifyToken($user) {
        if (empty($user)) { return null; }
        
        $token_raw = random_bytes(30);
        $token_hex = bin2hex($token_raw);

        $user->verify_hash = $token_hex;
        
        return $user;
    }

    function forgotPassword() {
        if ( ! is_null($this->Auth->user('id'))) {
            $this->Flash->error(__('You have not forgotten your password, you are logged in.'));
            return $this->redirect('/');
        }
        if ($this->request->is(['post']) && !empty( $this->request->getData('username') ) ) {

            $userReset = $this->Users->findByUsername($this->request->getData('username'))->first();

            if ( empty($userReset) ) {
                $this->Flash->error(__('Password reset instructions sent.  You have 24 hours to complete this request.'));
                return $this->redirect('/');
            } else {
                $userReset = $this->__genPassToken($userReset);
                if ( $this->Users->save($userReset) ) {
                    $email = new Email('default');
                    $email->viewBuilder()->setTemplate('reset');
                    $email
                        ->setEmailFormat('both')
                        
                        ->setTo($userReset->username)
                        ->setSubject('Password Reset Requested')
                        ->setViewVars([
                            'username' => $userReset->username,
                            'ip' => $_SERVER['REMOTE_ADDR'],
                            'hash' => $userReset->reset_hash,
                            'expire' => $userReset->reset_hash_time,
                            'fullURL' => "http://" . $_SERVER['HTTP_HOST'] . "/users/reset_password/",
                        ])
                        ->send();
                    $this->Flash->error(__('Password reset instructions sent.  You have 24 hours to complete this request.'));
                    return $this->redirect('/');
                }
            }
        }
    }

    function resetPassword($hash) {
        if ( ! is_null($this->Auth->user('id'))) {
            $this->Flash->error(__('You have not forgotten your password, you are logged in.'));
            return $this->redirect('/');
        }
        if ( empty($hash) ) {
            $this->Flash->error(__('That link is invalid, sorry!'));
            return $this->redirect('/');
        } else {
            $user = $this->Users->findByResetHash($hash)->first();
            if ( empty($user) ) {
                $this->Flash->error(__('That link is invalid, sorry!'));
                return $this->redirect('/');
            } elseif ( $user->reset_hash_time->isPast() ) {
                $this->Flash->error(__('That Link has expired, sorry!'));
                return $this->redirect('/users/forgot_password');
            } else {
                $this->set('user', $user);    
                if ($this->request->is(['patch', 'post', 'put'])) {
                    $user = $this->Users->patchEntity($user, $this->request->getData(), ['fields' => ['password', 'is_password_expired']]);
                    $user->reset_hash = null;
                    $user->reset_hash_time = date('Y-m-d H:i:s', 1);
                    if ($this->Users->save($user)) {
                        $this->Flash->success(__('Your password has been saved, please login now.'));
                        return $this->redirect('/users/login');
                    } else {
                        $this->Flash->error(__('The user could not be saved. Please, try again.'));
                    }
                }
            }   
        }
    }


}
