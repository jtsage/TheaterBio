<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\I18n\Time;

/**
 * Budgets Controller
 *
 * @property \App\Model\Table\BudgetsTable $Budgets
 */
class BudgetsController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('UserPerm');
    }
    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {

        $this->loadModel('Shows');

        $permList = $this->UserPerm->getAllPerm($this->Auth->user('id'), 'is_budget');

        $shows = $this->Shows->find('all')
            ->where(['Shows.is_active' => 1])
            ->where(['id' => $permList], ['id' => 'integer[]'])
            ->order(['end_date' => 'ASC']);

        $budget = $this->Budgets->find('all')
            ->where(['show_id' => $permList], ['show_id' => 'integer[]'])
            ->select([
                'category' => 'Budgets.category',
                'total' => 'sum(Budgets.price)',
                'show_id' => 'Budgets.show_id'
            ])
            ->group('show_id')
            ->group('category')
            ->order(['category' => 'ASC']);

        if ( $this->Auth->user('is_admin') ) {
            $inactshows = $this->Shows->find('all')
                ->where(['Shows.is_active' => 0])
                ->where(['id' => $permList], ['id' => 'integer[]'])
                ->order(['end_date' => 'ASC']);
            $this->set('inactshows', $inactshows);
        } else {
            $this->set('inactshows', []);
        }

        $this->set('crumby', [
            ["/", __("Dashboard")],
            [null, __("Budget Lists")]
        ]);

        $this->set('shows', $shows);
        $this->set('budget', $budget);
        $this->set('_serialize', ['budgets']);
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
        $this->loadModel('Shows');

        $show = $this->Shows->findById($id)->first();

        if ( ! $show ) {
            $this->Flash->error(__('Show not found!'));
            return $this->redirect(['action' => 'index']); 
        }

        if ( ! $this->UserPerm->checkShow($this->Auth->user('id'), $id, 'is_budget') ) {
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

        $budgets = $this->Budgets->find('all')
            ->where(['show_id' => $id])
            ->order(['category' => 'ASC', 'date' => 'ASC']);

        $this->set('crumby', [
            ["/", __("Dashboard")],
            ["/budgets/", __("Budget Lists")],
            [null, __("{0} Budget", $show->name)]
        ]);

        $this->set('show', $show);
        $this->set('budgets', $budgets);
        $this->set('_serialize', ['budget']);
    }

    /**
     * View method - CSV Download
     *
     * @param string|null $id Show id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function viewcsv($id = null)
    {
        $this->loadModel('Shows');

        $show = $this->Shows->findById($id)->first();

        if ( ! $show ) {
            $this->Flash->error(__('Show not found!'));
            return $this->redirect(['action' => 'index']); 
        }

        if ( ! $this->UserPerm->checkShow($this->Auth->user('id'), $id, 'is_budget') ) {
            $this->Flash->error(__('You do not have access to this show'));
            return $this->redirect(['action' => 'index']);
        }

        $budgets = $this->Budgets->find('all')
            ->where(['show_id' => $id])
            ->order(['category' => 'ASC', 'date' => 'ASC']);

        $csvdata = [];
        foreach ( $budgets as $item ) {
            $csvdata[] = [
                $item->date->i18nFormat('EEE, MMM dd, yyyy', 'UTC'),
                $show->name,
                $item->category,
                $item->vendor,
                $item->description,
                $item->price
            ];
        }
        $headers = [];

        $_serialize = 'csvdata';
        $_header = ['Date', 'Show', 'Category', 'Vendor', 'Description', 'Price'];

        $filename = "budget-" . preg_replace("/ /", "_", $show->name) . "-" . date('Ymd') . ".csv";
        $this->setResponse($this->getResponse()->withDownload($filename));
        $this->viewClass = 'CsvView.Csv';
        $this->set(compact('csvdata', '_serialize', '_header'));
    }

    /**
     * Add method
     *
     * @param string $id Show id.
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add($id = null)
    {
        $this->loadModel('Shows');

        $show = $this->Shows->findById($id)->first();

        if ( ! $show ) {
            $this->Flash->error(__('Show not found!'));
            return $this->redirect(['action' => 'index']); 
        }

        if ( ! $this->UserPerm->checkShow($this->Auth->user('id'), $id, 'is_budget') ) {
            $this->Flash->error(__('You do not have access to this show'));
            return $this->redirect(['action' => 'index']);
        }

        if ( $show->is_active < 1 ) {
            $this->Flash->error(__('Sorry, this show is now closed.'));
            return $this->redirect(['action' => 'index']);   
        }

        $budget = $this->Budgets->newEntity();
        if ($this->request->is('post')) {
            $budget = $this->Budgets->patchEntity($budget, $this->request->getData());
            $budget->date = Time::createFromFormat(
                 'Y-m-d',
                 $this->request->getData('date'),
                 'UTC'
            );
            if ($this->Budgets->save($budget)) {
                $this->Flash->success(__('The budget has been saved.'));
                return $this->redirect(['action' => 'view', $id]);
            } else {
                $this->Flash->error(__('The budget could not be saved. Please, try again.'));
            }
        }

        $vendq = $this->Budgets->find()
            ->select(['vendor'])
            ->distinct(['vendor'])
            ->order(['vendor' => 'ASC']);
        $vend = json_encode($vendq->extract('vendor'));

        $catq = $this->Budgets->find()
            ->select(['category'])
            ->distinct(['category'])
            ->order(['category' => 'ASC']);
        $cat = json_encode($catq->extract('category'));

        $this->set('crumby', [
            ["/", __("Dashboard")],
            ["/budgets/", __("Budget Lists")],
            ["/budgets/view/" . $show->id, __("{0} Budget", $show->name)],
            [null, __("Add Expense")]
        ]);

        $shows = [$show->id => $show->name];
        $this->set(compact('budget', 'shows', 'vend', 'cat'));
        $this->set('_serialize', ['budget']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Budget id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $budget = $this->Budgets->get($id, [
            'contain' => []
        ]);

        $this->loadModel('Shows');

        $show = $this->Shows->findById($budget->show_id)->first();

        if ( ! $show ) {
            $this->Flash->error(__('Show not found!'));
            return $this->redirect(['action' => 'index']); 
        }

        if ( ! $this->UserPerm->checkShow($this->Auth->user('id'), $budget->show_id, 'is_budget') ) {
            $this->Flash->error(__('You do not have access to this show'));
            return $this->redirect(['action' => 'index']);
        }

        if ( $show->is_active < 1 ) {
            $this->Flash->error(__('Sorry, this show is now closed.'));
            return $this->redirect(['action' => 'index']);   
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $budget = $this->Budgets->patchEntity($budget, $this->request->getData(), [
                'fields' => ['vendor', 'category', 'description', 'price']
            ]);
            $budget->date = Time::createFromFormat(
                 'Y-m-d',
                 $this->request->getData('date'),
                 'UTC'
            );
            if ($this->Budgets->save($budget)) {
                $this->Flash->success(__('The budget has been saved.'));
                return $this->redirect(['action' => 'view', $show->id]);
            } else {
                $this->Flash->error(__('The budget could not be saved. Please, try again.'));
            }
        }

        $vendq = $this->Budgets->find()
            ->select(['vendor'])
            ->distinct(['vendor'])
            ->order(['vendor' => 'ASC']);
        $vend = json_encode($vendq->extract('vendor'));

        $catq = $this->Budgets->find()
            ->select(['category'])
            ->distinct(['category'])
            ->order(['category' => 'ASC']);
        $cat = json_encode($catq->extract('category'));

        $this->set('crumby', [
            ["/", __("Dashboard")],
            ["/budgets/", __("Budget Lists")],
            ["/budgets/view/" . $show->id, __("{0} Budget", $show->name)],
            [null, "Edit Expense"]
        ]);

        $shows = [$show->id => $show->name];
        $this->set(compact('budget', 'shows', 'cat', 'vend'));
        $this->set('_serialize', ['budget']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Budget id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $budget = $this->Budgets->get($id);

        $this->loadModel('Shows');

        $show = $this->Shows->findById($budget->show_id)->first();

        if ( ! $show ) {
            $this->Flash->error(__('Show not found!'));
            return $this->redirect(['action' => 'index']); 
        }

        if ( ! $this->UserPerm->checkShow($this->Auth->user('id'), $budget->show_id, 'is_budget') ) {
            $this->Flash->error(__('You do not have access to this show'));
            return $this->redirect(['action' => 'index']);
        }

        if ( $show->is_active < 1 ) {
            $this->Flash->error(__('Sorry, this show is now closed.'));
            return $this->redirect(['action' => 'index']);   
        }

        if ($this->Budgets->delete($budget)) {
            $this->Flash->success(__('The budget has been deleted.'));
        } else {
            $this->Flash->error(__('The budget could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'view', $show->id]);
    }
}
