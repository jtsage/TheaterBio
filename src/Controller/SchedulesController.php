<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\I18n\Time;

/**
 * Schedules Controller
 *
 * @property \App\Model\Table\SchedulesTable $Schedules
 */
class SchedulesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        if ( ! $this->Auth->user('is_admin')) {
            $this->Flash->error(__('Only administrators may access the scheduled tasks module.'));
            return $this->redirect("/");
        }
        $schedules = $this->paginate($this->Schedules);

        $this->loadModel('Shows');
        $showsq = $this->Shows->find('all')
            ->select(['id', 'name'])
            ->where(['Shows.is_active' => 1]);

        $shows = ["**Not Applicable**"];
        foreach ($showsq as $show) { $shows[$show->id] = $show->name; }
        $this->set('shows', $shows);

        $this->set('crumby', [
            ["/", __("Dashboard")],
            [null, __("Scheduled Tasks")]
        ]);

        $this->set(compact('schedules'));
        $this->set('_serialize', ['schedules']);
    }

    /**
     * View method
     *
     * @param string|null $id Schedule id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        if ( ! $this->Auth->user('is_admin')) {
            $this->Flash->error(__('Only administrators may access the scheduled tasks module.'));
            return $this->redirect("/");
        }
        $schedule = $this->Schedules->get($id, [
            'contain' => []
        ]);

        $this->loadModel('Shows');
        $showsq = $this->Shows->find('all')
            ->select(['id', 'name'])
            ->where(['Shows.is_active' => 1]);

        $shows = ["**Not Applicable**"];
        foreach ($showsq as $show) { $shows[$show->id] = $show->name; }
        $this->set('shows', $shows);

        $this->set('crumby', [
            ["/", __("Dashboard")],
            ["/schedules/", __("Scheduled Tasks")],
            [null, __("Scheduled Task #{0}", $id)]
        ]);

        $this->set('schedule', $schedule);
        $this->set('_serialize', ['schedule']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        if ( ! $this->Auth->user('is_admin')) {
            $this->Flash->error(__('Only administrators may access the scheduled tasks module.'));
            return $this->redirect("/");
        }

        $this->loadModel('Shows');
        $showsq = $this->Shows->find('all')
            ->select(['id', 'name'])
            ->where(['Shows.is_active' => 1]);

        $shows = ["** Not Applicable **"];
        foreach ($showsq as $show) { $shows[$show->id] = $show->name; }
        $this->set('shows', $shows);

        $schedule = $this->Schedules->newEntity();
        if ($this->request->is('post')) {
            $schedule = $this->Schedules->patchEntity($schedule, $this->request->getData());
            $schedule->start_time = Time::createFromFormat(
                 'Y-m-d H:i:s',
                 $this->request->getData('start_time'),
                 'UTC'
            );
            if ($this->Schedules->save($schedule)) {
                $this->Flash->success(__('The schedule has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The schedule could not be saved. Please, try again.'));
            }
        }
        $this->set('crumby', [
            ["/", __("Dashboard")],
            ["/schedules/", __("Scheduled Tasks")],
            [null, __("Add Scheduled Task")]
        ]);
        $this->set(compact('schedule'));
        $this->set('_serialize', ['schedule']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Schedule id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        if ( ! $this->Auth->user('is_admin')) {
            $this->Flash->error(__('Only administrators may access the scheduled tasks module.'));
            return $this->redirect("/");
        }
        
        $this->loadModel('Shows');
        $showsq = $this->Shows->find('all')
            ->select(['id', 'name'])
            ->where(['Shows.is_active' => 1]);

        $shows = ["** Not Applicable **"];
        foreach ($showsq as $show) { $shows[$show->id] = $show->name; }
        $this->set('shows', $shows);

        $schedule = $this->Schedules->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $schedule = $this->Schedules->patchEntity($schedule, $this->request->getData());
            $schedule->start_time = Time::createFromFormat(
                 'Y-m-d H:i:s',
                 $this->request->getData('start_time'),
                 'UTC'
            );
            if ($this->Schedules->save($schedule)) {
                $this->Flash->success(__('The schedule has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The schedule could not be saved. Please, try again.'));
            }
        }
        $this->set('crumby', [
            ["/", __("Dashboard")],
            ["/schedules/", __("Scheduled Tasks")],
            [null, __("Scheduled Task #{0}", $id)]
        ]);
        $this->set(compact('schedule'));
        $this->set('_serialize', ['schedule']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Schedule id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        if ( ! $this->Auth->user('is_admin')) {
            $this->Flash->error(__('Only administrators may access the scheduled tasks module.'));
            return $this->redirect("/");
        }
        $this->request->allowMethod(['post', 'delete']);
        $schedule = $this->Schedules->get($id);
        if ($this->Schedules->delete($schedule)) {
            $this->Flash->success(__('The schedule has been deleted.'));
        } else {
            $this->Flash->error(__('The schedule could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
