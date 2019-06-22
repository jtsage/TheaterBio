<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\I18n\Time;
use Cake\Mailer\Email;

/**
 * Tasks Controller
 *
 * @property \App\Model\Table\TasksTable $Tasks
 */
class TasksController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('UserPerm');
        $this->loadComponent('TaskUtil');
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {

        $this->loadModel('Shows');

        $permListA = $this->UserPerm->getAllPerm($this->Auth->user('id'), 'is_task_admin');
        $permListU = $this->UserPerm->getAllPerm($this->Auth->user('id'), 'is_task_user');

        $showsA = $this->Shows->find('all')
            ->where(['Shows.is_active' => 1])
            ->where(['id' => $permListA], ['id' => 'integer[]'])
            ->order(['end_date' => 'ASC']);

        $showsU = $this->Shows->find('all')
            ->where(['Shows.is_active' => 1])
            ->where(['id' => $permListU], ['id' => 'integer[]'])
            ->order(['end_date' => 'ASC']);

        $this->set('showtask', $this->TaskUtil->getAllCounts($this->Auth->user('id')));

        if ( $this->Auth->user('is_admin') ) {
            $inactshows = $this->Shows->find('all')
                ->where(['Shows.is_active' => 0])
                ->where(['id' => $permListA], ['id' => 'integer[]'])
                ->order(['end_date' => 'ASC']);
            $this->set('inactshows', $inactshows);
        } else {
            $this->set('inactshows', []);
        }

        $this->set('crumby', [
            ["/", __("Dashboard")],
            [null, __("Task Lists")]
        ]);

        $this->set('showsA', $showsA);
        $this->set('showsU', $showsU);
        $this->set('tasks', []);
        $this->set('shows', $this->paginate($showsA));
        $this->set('_serialize', ['showsA']);
    }

    /**
     * View method
     *
     * @param string|null $id Task id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null, $srtorder = 'due')
    {
        $this->loadModel('Shows');

        $show = $this->Shows->findById($id)->first();

        if ( ! $show ) {
            $this->Flash->error(__('Task list not found!'));
            return $this->redirect(['action' => 'index']); 
        }

        if ( ! $this->UserPerm->checkShow($this->Auth->user('id'), $id, 'is_task_user') && ! $this->UserPerm->checkShow($this->Auth->user('id'), $id, 'is_task_admin') ) {
            $this->Flash->error(__('You do not have access to this task list'));
            return $this->redirect(['action' => 'index']);
        }

        if ( ! $this->UserPerm->checkShow($this->Auth->user('id'), $id, 'is_task_admin') ) {
            $extraWhere = ['created_by' => $this->Auth->user('id')];
        } else {
            $extraWhere = [];
        }

        if ( $show->is_active < 1 ) {
            $this->Flash->error(__('Sorry, this show is now closed.'));
            if ( $this->Auth->user('is_admin') ) {
                $this->set('opsok', false);
            } else {
                return $this->redirect(['action' => 'index']);
            }
        } else {
            $this->set('opsok', $this->UserPerm->checkShow($this->Auth->user('id'), $id, 'is_task_admin'));
        }

        switch ( $srtorder ) {
            case "new":
                $sorter = ['task_accepted' => 'ASC', 'Tasks.created_at' => 'DESC'];
                break;
            case "created":
                $sorter = ['Tasks.created_at' => 'DESC'];
                break;
            case "updated":
                $sorter = ['Tasks.updated_at' => 'DESC'];
                break;
            case "priority":
                $sorter = ['Tasks.task_done' => 'ASC', 'priority' => 'DESC'];
                break;
            case "due":
            default:
                $sorter = ['task_done' => 'ASC', 'due' => 'ASC'];
                break;
        }

        $tasks = $this->Tasks->find('all')
            ->where(['show_id' => $id])
            //->where($extraWhere)
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
            ->order($sorter);

        
         $this->set('crumby', [
            ["/", __("Dashboard")],
            ["/tasks/", __("Task Lists")],
            [null, __("{0} Tasks", $show->name)],
        ]);

        $this->set('sort', $srtorder);
        $this->set('show', $show);
        $this->set('tasks', $tasks);
        $this->set('opid', $this->Auth->user('id'));
        $this->set('_serialize', ['tasks']);

    }


    public function detail($id = null)
    {
        $this->loadModel('Shows');

        $task = $this->Tasks->find('all')
            ->contain(['Shows'])
            ->where(['Tasks.id' => $id])
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
                'show_id', 'title', 'due', 'priority', 'category', 'note', 'id', 'created_at', 'updated_at', 'task_accepted', 'task_done', 'created_by', 'assigned_to',
                'show_name' => 'Shows.name', 'show_active' => 'Shows.is_active',
                'assigned_name' => 'concat(assigned.first, " ", assigned.last)',
                'created_name' => 'concat(created.first, " ", created.last)',
                'is_overdue' => 'IF(Tasks.due < "' . date('Y-m-d') . '", 1, 0)'
            ])->first();


        if ( ! $task ) {
            $this->Flash->error(__('Task not found!'));
            return $this->redirect(['action' => 'index']); 
        }

        if ( ! $this->UserPerm->checkShow($this->Auth->user('id'), $task->show_id, 'is_task_user') && ! $this->UserPerm->checkShow($this->Auth->user('id'), $task->show_id, 'is_task_admin') ) {
            $this->Flash->error(__('You do not have access to this task'));
            return $this->redirect(['action' => 'index']);
        }

        if ( $task->show_active < 1 ) {
            $this->Flash->error(__('Sorry, this show is now closed.'));
            if ( $this->Auth->user('is_admin') ) {
                $this->set('opsok', false);
            } else {
                return $this->redirect(['action' => 'index']);
            }
        } else {
            $this->set('opsok', $this->UserPerm->checkShow($this->Auth->user('id'), $task->show_id, 'is_task_admin'));
        }

        
         $this->set('crumby', [
            ["/", __("Dashboard")],
            ["/tasks/", __("Task Lists")],
            ["/tasks/" . $task->show_id, __("{0} Tasks", $task->show_name)],
            [null, $task->title]
        ]);

        $this->set('task', $task);
        $this->set('opid', $this->Auth->user('id'));
        $this->set('_serialize', ['task']);

    }
    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add($id = null)
    {
        $this->loadModel('Shows');
        $this->loadModel('ShowUserPerms');
        $this->loadModel('Users');
        $this->loadComponent('MailMsg');

        $show = $this->Shows->findById($id)->first();

        if ( ! $show ) {
            $this->Flash->error(__('Show not found!'));
            return $this->redirect(['action' => 'index']); 
        }

        if ( ! $this->UserPerm->checkShow($this->Auth->user('id'), $id, 'is_task_user') && ! $this->UserPerm->checkShow($this->Auth->user('id'), $id, 'is_task_admin') ) {
            $this->Flash->error(__('You do not have access to this show'));
            return $this->redirect(['action' => 'index']);
        }        

        if ( $show->is_active < 1 ) {
            $this->Flash->error(__('Sorry, this show is now closed.'));
            return $this->redirect(['action' => 'index']);   
        }


        $task = $this->Tasks->newEntity();
        if ($this->request->is('post')) {

            $time = Time::createFromFormat('Y-m-d', $this->request->getData('due'), 'UTC');

            $task = $this->Tasks->patchEntity($task, $this->request->getData());
            $task->due = $time;
            $task->created_by = $this->Auth->user('id');

            if ($this->Tasks->save($task)) {
                $this->MailMsg->sendIntNotify($this->Auth->user('id'), $task->assigned_to, "New Task Created: " . $task->title);
                $userTo = $this->Users->findById($task->assigned_to)->first();
                $this->MailMsg->sendExtNotify($this->Auth->user('id'), $task->assigned_to, "newtask", "New Task Created", [
                    'title' => 'New Task Created',
                    'creator' => $this->Auth->user('first') . " " . $this->Auth->user('last'),
                    'assign' => $userTo->first . " " . $userTo->last,
                    'title' => $task->title,
                    'link' => "http://" . $_SERVER['HTTP_HOST'] . "/tasks/view/" . $show->id,
                    'due' => $time->i18nFormat([\IntlDateFormatter::FULL, \IntlDateFormatter::NONE], 'UTC'),
                    'descrip' => $task->note
                ]);
                $this->Flash->success(__('The task has been saved.'));
                return $this->redirect(['action' => 'view', $show->id]);
            } else {
                $this->Flash->error(__('The task could not be saved. Please, try again.'));
            }
        }

        $assignee = $this->ShowUserPerms->find('list', ['valueField' => 'fullname', 'keyField' => 'user_id'])
            ->contain(['Users'])
            ->select(['fullname' => 'concat(Users.first, " ", Users.last)', 'ShowUserPerms.user_id'])
            ->where(['Users.is_active' => 1])
            ->where(['is_task_admin' => 1])
            ->where(['show_id' => $id])
            ->group(['user_id'])
            ->order(['Users.last' => 'ASC', 'Users.first' => 'ASC']);

        $catq = $this->Tasks->find()
            ->select(['category'])
            ->distinct(['category'])
            ->order(['category' => 'ASC']);
        $cat = json_encode($catq->extract('category'));

        $this->set('crumby', [
            ["/", __("Dashboard")],
            ["/tasks/", __("Task Lists")],
            ["/tasks/view/" . $show->id, __("{0} Tasks", $show->name)],
            [null, __("Add Task")]
        ]);

        $shows = [$show->id => $show->name];
        $this->set(compact('task', 'shows', 'assignee', 'cat'));
        $this->set('_serialize', ['task']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Task id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $task = $this->Tasks->get($id, [
            'contain' => ['Users']
        ]);

        $this->set('adminTask', $this->UserPerm->checkShow($this->Auth->user('id'), $task->show_id, 'is_task_admin'));

        $this->loadModel('Shows');
        $this->loadModel('ShowUserPerms');

        $show = $this->Shows->findById($task->show_id)->first();

        if ( ! $show ) {
            $this->Flash->error(__('Show not found!'));
            return $this->redirect(['action' => 'index']); 
        }

        if ( ! $this->UserPerm->checkShow($this->Auth->user('id'), $task->show_id, 'is_task_user') && ! $this->UserPerm->checkShow($this->Auth->user('id'), $task->show_id, 'is_task_admin') ) {
            $this->Flash->error(__('You do not have access to this show'));
            return $this->redirect(['action' => 'index']);
        }

        if ( ! $this->UserPerm->checkShow($this->Auth->user('id'), $task->show_id, 'is_task_admin') && $task->created_by <> $this->Auth->user('id') ) {
            $this->Flash->error(__('You may only edit your own tasks in this show'));
            return $this->redirect(['action' => 'index']);
        }

        if ( $show->is_active < 1 ) {
            $this->Flash->error(__('Sorry, this show is now closed.'));
            return $this->redirect(['action' => 'index']);   
        }


        if ($this->request->is(['patch', 'post', 'put'])) {
            $time = Time::createFromFormat( 'Y-m-d', $this->request->getData('due'), 'UTC' );

            $task = $this->Tasks->patchEntity($task, $this->request->getData());
            $task->due = $time;

            if ($this->Tasks->save($task)) {
                $this->Flash->success(__('The task has been saved.'));
                return $this->redirect(['action' => 'view', $show->id]);
            } else {
                $this->Flash->error(__('The task could not be saved. Please, try again.'));
            }
        }

        $assignee = [$task->user->id => $task->user->first . " " . $task->user->last];

        $catq = $this->Tasks->find()
            ->select(['category'])
            ->distinct(['category'])
            ->order(['category' => 'ASC']);
        $cat = json_encode($catq->extract('category'));

        $this->set('crumby', [
            ["/", __("Dashboard")],
            ["/tasks/", __("Task Lists")],
            ["/tasks/view/" . $show->id, __("{0} Tasks", $show->name)],
            [null, __("Edit Task")]
        ]);

        $shows = [$show->id => $show->name];
        $this->set(compact('task', 'shows', 'assignee', 'cat'));
        $this->set('_serialize', ['task']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Task id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        
        $this->loadModel('Shows');

        $task = $this->Tasks->get($id);
        $show = $this->Shows->findById($task->show_id)->first();

        if ( ! $show ) {
            $this->Flash->error(__('Show not found!'));
            return $this->redirect(['action' => 'index']); 
        }

        if ( ! $this->UserPerm->checkShow($this->Auth->user('id'), $task->show_id, 'is_task_admin') ) {
            $this->Flash->error(__('You do not have access to delete this task'));
            return $this->redirect(['action' => 'index']);
        }

        if ( $show->is_active < 1 ) {
            $this->Flash->error(__('Sorry, this show is now closed.'));
            return $this->redirect(['action' => 'index']);   
        }


        if ($this->Tasks->delete($task)) {
            $this->Flash->success(__('The task has been deleted.'));
        } else {
            $this->Flash->error(__('The task could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'view', $show->id]);
    }
}
