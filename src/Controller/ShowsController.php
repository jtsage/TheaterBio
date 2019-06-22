<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\I18n\Time;

/**
 * Shows Controller
 *
 * @property \App\Model\Table\ShowsTable $Shows
 */
class ShowsController extends AppController
{

    public $paginate = [
        'limit' => 50,
        'order' => [
            'Shows.is_active' => 'DESC',
            'Shows.end_date' => 'ASC'
        ]
    ];
    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        if ( $this->Auth->user('is_admin') ) {
            $query = $this->Shows->find('all');
        } else {
            $this->loadModel('ShowUserPerms');

            $perms = $this->ShowUserPerms->find('list', [
                'keyField' => 'id',
                'valueField' => 'show_id',
                'conditions' => ['ShowUserPerms.user_id' => $this->Auth->user('id')]
            ]);

            $plist = $perms->toArray();
            
            $query = $this->Shows->find('all')
                ->where(['Shows.is_active' => 1])
                ->where(['id' => $plist], ['id' => 'integer[]']);
        }

        $this->set('crumby', [
            ["/", __("Dashboard")],
            [null, __("Show List")]
        ]);

        $this->set('isAdmin', $this->Auth->user('is_admin'));
        $this->set('shows', $this->paginate($query));
        $this->set('_serialize', ['shows']);
    }

    /**
     * View method
     *
     * @param string|null $id Show id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $show = $this->Shows->get($id, [
            'contain' => ['ShowUserPerms' => ['Users']]
        ]);

        if ( ! $this->Auth->user('is_admin') ) {
            $this->loadModel('ShowUserPerms');

            $perms = $this->ShowUserPerms->find()
                ->where(['ShowUserPerms.user_id' => $this->Auth->user('id')])
                ->where(['ShowUserPerms.show_id' => $id])
                ->select([
                    'user_id' => 'ShowUserPerms.user_id',
                    'show_id' => 'ShowUserPerms.show_id',
                    'access' => 'ShowUserPerms.is_pay_admin + ShowUserPerms.is_paid + ShowUserPerms.is_budget'
                    ])
                ->first();

            if ( $perms->access < 1 ) {
                $this->Flash->error(__('You do not have access to this show'));
                return $this->redirect(['action' => 'index']);
            }
            if ( $show->is_active < 1 ) {
                $this->Flash->error(__('Sorry, this show is now closed.'));
                return $this->redirect(['action' => 'index']);   
            }
        }

        $this->set('crumby', [
            ["/", __("Dashboard")],
            ["/shows/", __("Shows")],
            [null, $show->name]
        ]);

        $this->set('isAdmin', $this->Auth->user('is_admin'));
        $this->set('show', $show);
        $this->set('_serialize', ['show']);
        $this->set('tz', $this->Auth->user('time_zone'));
    }

    public function editperm($id = null)
    {
        if ( ! $this->Auth->user('is_admin')) {
            $this->Flash->error(__('You may not edit show permissions'));
            return $this->redirect(['action' => 'index']);
        }
        $this->loadModel('Users');
        $this->loadModel('ShowUserPerms');

        $users = $this->Users->find('all', [
            'conditions' => ['Users.is_active' => 1 ],
            'fields' => ['Users.id', 'Users.last', 'Users.first'],
            'order' => ['Users.last' => 'ASC', 'Users.first' => 'ASC']
        ]);
        $perms = $this->ShowUserPerms->find('all', [
            'conditions' => ['ShowUserPerms.show_id' => $id]
        ]);
        $show = $this->Shows->get($id);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $insertCol = [ "user_id", "show_id", "is_pay_admin", "is_paid", "is_budget", "is_task_admin", "is_task_user", "is_cal" ];
            $removeSql = $this->ShowUserPerms->query();
            $removeSql->delete()
                ->where(['show_id' => $id])
                ->execute();

            $insertRow = array();
            foreach ( $this->request->getData('users') as $user ) {
                $insertRow[] = [
                    'user_id' => $user,
                    'show_id' => $id,
                    'is_pay_admin'  => ( $this->request->getData('padmin.'.$user)     ? 1 : 0 ),
                    'is_paid'       => ( $this->request->getData('paid.'.$user)       ? 1 : 0 ),
                    'is_budget'     => ( $this->request->getData('budget.'.$user)     ? 1 : 0 ),
                    'is_task_admin' => ( $this->request->getData('task_admin.'.$user) ? 1 : 0 ),
                    'is_task_user'  => ( $this->request->getData('task_user.'.$user)  ? 1 : 0 ),
                    'is_cal'        => ( $this->request->getData('cal.'.$user)        ? 1 : 0 ),
                ];
            }

            $insertQuery = $this->ShowUserPerms->query();

            $insertQuery->insert($insertCol);
            $insertQuery->clause('values')->setValues($insertRow);
            $insertQuery->execute();
            
            $this->Flash->success(__('The show permissions have been saved.'));
            return $this->redirect(['action' => 'view', $id]);
            
        }
        $this->set(compact('show'));

        foreach ( $users as $user ) {
            $foundit = false;
            foreach ( $perms as $perm ) {
                if ( $user->id == $perm->user_id ) {
                    $foundit = true;
                    $user['perms'] = array(
                        'is_pay_admin' => $perm->is_pay_admin,
                        'is_budget' => $perm->is_budget,
                        'is_paid' => $perm->is_paid,
                        'is_task_admin' => $perm->is_task_admin,
                        'is_task_user' => $perm->is_task_user,
                        'is_cal' => $perm->is_cal
                    );
                }
            }
            if ( $foundit == false ) {
                $user['perms'] = array(
                    'is_pay_admin' => false,
                    'is_budget' => false,
                    'is_paid' => false,
                    'is_task_admin' => false,
                    'is_task_user' => false,
                    'is_cal' => false,
                );
            }
        }

        $this->set('crumby', [
            ["/", __("Dashboard")],
            ["/shows/", __("Shows")],
            ["/shows/view/" . $show->id, $show->name],
            [null, "Edit Permissions"]
        ]);

        $this->set('users', $users);

        $this->set('_serialize', ['show']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        if ( ! $this->Auth->user('is_admin')) {
            $this->Flash->error(__('You may not add shows'));
            return $this->redirect(['action' => 'index']);
        }
        $show = $this->Shows->newEntity();
        if ($this->request->is('post')) {
            $time = Time::createFromFormat(
                 'Y-m-d',
                 $this->request->getData('end_date'),
                 'UTC'
            );
	    $show = $this->Shows->patchEntity($show, $this->request->getData());
	    $show->end_date = $time;
            if ($result = $this->Shows->save($show)) {
                $this->Flash->success(__('The show has been saved, please adjust permissions'));
                return $this->redirect(['action' => 'editperm', $result->id]);
            } else {
                $this->Flash->error(__('The show could not be saved. Please, try again.'));
            }
        }
        $this->set('crumby', [
            ["/", __("Dashboard")],
            ["/shows/", __("Shows")],
            [null, __("Add Show")]
        ]);

        $this->set(compact('show'));
        $this->set('_serialize', ['show']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Show id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        if ( ! $this->Auth->user('is_admin')) {
            $this->Flash->error(__('You may not edit shows'));
            return $this->redirect(['action' => 'index']);
        }
        $show = $this->Shows->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $time = Time::createFromFormat(
                 'Y-m-d',
                 $this->request->getData('end_date'),
                 'UTC'
            );
            $show = $this->Shows->patchEntity($show, $this->request->getData());
            $show->end_date = $time;
            if ($this->Shows->save($show)) {
                $this->Flash->success(__('The show has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The show could not be saved. Please, try again.'));
            }
        }
        
        $this->set('crumby', [
            ["/", __("Dashboard")],
            ["/shows/", __("Shows")],
            ["/shows/view/" . $show->id, $show->name],
            [null, __("Edit Show")]
        ]);

        $this->set(compact('show'));
        $this->set('_serialize', ['show']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Show id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        if ( ! $this->Auth->user('is_admin')) {
            $this->Flash->error(__('You may not delete shows'));
            return $this->redirect(['action' => 'index']);
        }
        $this->request->allowMethod(['post', 'delete']);
        $show = $this->Shows->get($id);
        if ($this->Shows->delete($show)) {
            $this->Flash->success(__('The show has been deleted.'));
        } else {
            $this->Flash->error(__('The show could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
