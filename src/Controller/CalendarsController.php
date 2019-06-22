<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\I18n\Time;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\ForbiddenException;
use Cake\Core\Configure;

/**
 * Calendars Controller
 *
 * @property \App\Model\Table\CalendarsTable $Calendars
 */
class CalendarsController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('UserPerm');
        $this->loadComponent('CalUtil');
        $this->Auth->allow(['ics']);
    }
    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->loadModel('Shows');

        $permList = $this->UserPerm->getAllPerm($this->Auth->user('id'), 'is_cal');

        $shows = $this->Shows->find('all')
            ->where(['Shows.is_active' => 1])
            ->where(['id' => $permList], ['id' => 'integer[]'])
            ->order(['end_date' => 'ASC']);

        if ( $this->Auth->user('is_admin') ) {
            $inactshows = $this->Shows->find('all')
                ->where(['Shows.is_active' => 0])
                ->where(['id' => $permList], ['id' => 'integer[]'])
                ->order(['end_date' => 'ASC']);
            $this->set('inactshows', $inactshows);
        } else {
            $this->set('inactshows', []);
        }

        $this->set('showcal', $this->CalUtil->getAllCounts($this->Auth->user('id')));

        $this->set('crumby', [
            ["/", __("Dashboard")],
            [null, __("Calendars")]
        ]);

        $this->set('calendars', []);
        $this->set('shows', $this->paginate($shows));
        $this->set('_serialize', ['shows']);
    }

    /**
     * View method
     *
     * @param string|null $id Calendar id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null, $year = null, $month = null)
    {

        $this->loadModel('Shows');

        $show = $this->Shows->findById($id)->first();

        if ( ! $show ) {
            $this->Flash->error(__('Show not found!'));
            return $this->redirect(['action' => 'index']); 
        }

        if ( ! $this->UserPerm->checkShow($this->Auth->user('id'), $id, 'is_cal') ) {
            $this->Flash->error(__('You do not have access to this show'));
            return $this->redirect(['action' => 'index']);
        }        

        if ( $show->is_active < 1 ) {
            $this->Flash->error(__('Sorry, this show is now closed.'));
            if ( $this->Auth->user('is_admin') ) {
                $this->set('opsok', false);
            } else {
                return $this->redirect(['action' => 'index']);
            }
        } else {
            $this->set('opsok', true);
        }

        if ( substr($month, 0, 1) == "0" ) { $month = intval($month); }

        $this->set('show', $show); 

        $moy = ["", __("January"), __("February"), __("March"), __("April"), __("May"), __("June"), __("July"), __("August"), __("September"), __("October"), __("November"), __("December")];

        if ( is_null($year) ) { $year = date('Y'); }
        if ( is_null($month) ) { $month = date('m'); }

        $last_day_num = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $last_day = $year . "-" . str_pad($month, 2, "0", STR_PAD_LEFT) . "-" . $last_day_num;
        $first_day = $year . "-" . str_pad($month, 2, "0", STR_PAD_LEFT) . "-01" ;
        $first_day_of_week = date('w', strtotime($first_day));

        $this->set('first_day_of_week', $first_day_of_week);
        $this->set('last_day_num', $last_day_num);

        if ( $month == date('m') && $year == date('Y') ) {
            $today_is = date('j');
        } else {
            $today_is = 0;
        }

        $this->set('today_is', $today_is);

        $calendar = $this->Calendars->find('all')
            ->where([ 'Calendars.show_id' => $id ])
            ->where([ 'Calendars.date >=' => Time::createFromFormat('Y-m-d', $first_day, 'UTC') ])
            ->where([ 'Calendars.date <=' => Time::createFromFormat('Y-m-d', $last_day, 'UTC') ])
            ->order([
                'Calendars.date' => 'ASC',
                'Calendars.all_day' => 'DESC',
                'Calendars.start_time' => 'ASC'
            ]);

        $big_event = [];

        for ( $i=1; $i<=$last_day_num; $i++ ) {
            $big_event[$i] = [];
        }

        foreach ( $calendar as $event ) {
            $big_event[$event->date->i18nFormat("d", 'UTC')][] = $event->toArray();
        }

        $this->set('big_event', $big_event);

        $this->set('year', $year);
        $this->set('month_num', $month);
        $this->set('month', $moy[intval($month)]);

        if ( $month < 12 ) { $next = [ $year, $month+1 ]; } else { $next = [ $year+1, 1]; }
        if ( $month > 1 ) { $prev = [ $year, $month-1 ]; } else { $prev = [$year-1, 12]; }

        $this->set('crumby', [
            ["/", __("Dashboard")],
            ["/calendars/", __("Calendars")],
            [null, __("{0} Calendar", $show->name)]
        ]);

        $this->set('next', $next);
        $this->set('prev', $prev);
        $this->set('calendar', $calendar);
        $this->set('_serialize', ['calendar']);
    }

    public function ics($id = null, $sec = null)
    {
        $this->loadModel('Shows');

        $show = $this->Shows->findById($id)->first();

        if ( ! $show ) {
            throw new NotFoundException(__('Show not found'));
        }

        if ( $show->sec_string <> $sec ) {
            throw new ForbiddenException(__('You do not have access to this show.'));
        }

        $events = $this->Calendars->find('all')
            ->where([ 'Calendars.show_id' => $id ])
            ->order([
                 'Calendars.date' => 'ASC',
                 'Calendars.all_day' => 'DESC',
                 'Calendars.start_time' => 'ASC'
            ]);

        $this->set('events', $events);
        $this->set('show', $show);

        //date_default_timezone_set(Configure::read('ServerTimeZoneFix'));
        $real_offset = date('Z');
        //date_default_timezone_set('UTC');
        $this->set('real_offset', $real_offset);

        $this->response->withType('ics');
        $this->viewBuilder()->setLayout(false);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add($id = null)
    {
        $this->loadModel('Shows');

        $show = $this->Shows->findById($id)->first();

        if ( ! $show ) {
            $this->Flash->error(__('Show not found!'));
            return $this->redirect(['action' => 'index']); 
        }

        if ( ! $this->UserPerm->checkShow($this->Auth->user('id'), $id, 'is_cal') ) {
            $this->Flash->error(__('You do not have access to this show'));
            return $this->redirect(['action' => 'index']);
        }        

        if ( $show->is_active < 1 ) {
            $this->Flash->error(__('Sorry, this show is now closed.'));
            return $this->redirect(['action' => 'index']);   
        }

        $calendar = $this->Calendars->newEntity();
        if ($this->request->is('post')) {

            $calendar = $this->Calendars->patchEntity($calendar, $this->request->getData());
            
            $calendar->date = Time::createFromFormat( 'Y-m-d', $this->request->getData('date'), 'UTC' );
            $calendar->start_time =Time::createFromFormat( 'H:i', $this->request->getData('start_time'), 'UTC' );
            $calendar->end_time = Time::createFromFormat( 'H:i', $this->request->getData('end_time'), 'UTC' );

            if ($this->Calendars->save($calendar)) {
                $this->Flash->success(__('The event has been saved.'));

                return $this->redirect(['action' => 'view', $calendar->show_id]);
            } else {
                $this->Flash->error(__('The event could not be saved. Please, try again.'));
            }
        }

        $this->set('crumby', [
            ["/", __("Dashboard")],
            ["/calendars/", __("Calendars")],
            ["/calendars/view/" . $show->id . "/" . date("Y") . "/" . date("m"), __("{0} Calendar", $show->name)],
            [null, __("Add Event")]
        ]);

        $shows = [$show->id => $show->name];
        $this->set(compact('calendar', 'shows'));
        $this->set('_serialize', ['calendar']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Calendar id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $calendar = $this->Calendars->get($id);

        $this->loadModel('Shows');

        $show = $this->Shows->findById($calendar->show_id)->first();

        if ( ! $calendar ) {
            $this->Flash->error(__('Event not found!'));
            return $this->redirect(['action' => 'index']); 
        }

        if ( ! $this->UserPerm->checkShow($this->Auth->user('id'), $calendar->show_id, 'is_cal') ) {
            $this->Flash->error(__('You do not have access to this show'));
            return $this->redirect(['action' => 'index']);
        }        

        if ( $show->is_active < 1 ) {
            $this->Flash->error(__('Sorry, this show is now closed.'));
            return $this->redirect(['action' => 'index']);   
        }

        if ($this->request->is(['patch', 'post', 'put'])) {

            $calendar = $this->Calendars->patchEntity($calendar, $this->request->getData());

            $calendar->date = Time::createFromFormat( 'Y-m-d', $this->request->getData('date'), 'UTC' );
            $calendar->start_time =Time::createFromFormat( 'H:i', $this->request->getData('start_time'), 'UTC' );
            $calendar->end_time = Time::createFromFormat( 'H:i', $this->request->getData('end_time'), 'UTC' );


            if ($this->Calendars->save($calendar)) {
                $this->Flash->success(__('The event has been saved.'));

                return $this->redirect(['action' => 'view', $calendar->show_id]);
            } else {
                $this->Flash->error(__('The event could not be saved. Please, try again.'));
            }
        }

        $this->set('crumby', [
            ["/", __("Dashboard")],
            ["/calendars/", __("Calendars")],
            ["/calendars/view/" . $show->id . "/" . date("Y") . "/" . date("m"), __("{0} Calendar", $show->name)],
            [null, __("Edit Event")]
        ]);

        $shows = [$show->id => $show->name];
        $this->set(compact('calendar', 'shows'));
        $this->set('_serialize', ['calendar']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Calendar id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $calendar = $this->Calendars->get($id);

        if ( ! $calendar ) {
            $this->Flash->error(__('Event not found!'));
            return $this->redirect(['action' => 'index']); 
        }

        if ( ! $this->UserPerm->checkShow($this->Auth->user('id'), $calendar->show_id, 'is_cal') ) {
            $this->Flash->error(__('You do not have access to this show'));
            return $this->redirect(['action' => 'index']);
        }        

        if ($this->Calendars->delete($calendar)) {
            $this->Flash->success(__('The event has been deleted.'));
        } else {
            $this->Flash->error(__('The event could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'view', $calendar->show_id, date('Y'), date('m')]);
    }
}
