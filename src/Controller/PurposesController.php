<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Purposes Controller
 *
 * @property \App\Model\Table\PurposesTable $Purposes
 *
 * @method \App\Model\Entity\Purpose[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PurposesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        if ( ! $this->Auth->user('is_admin')) {
            $this->Flash->error(__('Only administrators may access the purposes module.'));
            return $this->redirect("/");
        }

        $purposes = $this->paginate($this->Purposes);

        $this->set('crumby', [
            ["/", __("Dashboard")],
            [null, __("Purposes")]
        ]);

        $this->set(compact('purposes'));
    }

    /**
     * View method
     *
     * @param string|null $id Purpose id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        if ( ! $this->Auth->user('is_admin')) {
            $this->Flash->error(__('Only administrators may access the purposes module.'));
            return $this->redirect("/");
        }

        $purpose = $this->Purposes->get($id, [
            'contain' => [
                'Users' => [
                   'Headshots'
                ]
                
            ]
        ]);

        $this->set('crumby', [
            ["/", __("Dashboard")],
            ["/purposes/", __("Purposes")],
            [null, $purpose->name]
        ]);

        $this->set('purpose', $purpose);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        if ( ! $this->Auth->user('is_admin')) {
            $this->Flash->error(__('Only administrators may access the purposes module.'));
            return $this->redirect("/");
        }
        $purpose = $this->Purposes->newEntity();
        if ($this->request->is('post')) {
            $purpose = $this->Purposes->patchEntity($purpose, $this->request->getData());
            if ($this->Purposes->save($purpose)) {
                $this->Flash->success(__('The purpose has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The purpose could not be saved. Please, try again.'));
        }

        $this->set('crumby', [
            ["/", __("Dashboard")],
            ["/purposes/", __("Purposes")],
            [null, __("Add Purpose")]
        ]);

        $this->set(compact('purpose'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Purpose id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        if ( ! $this->Auth->user('is_admin')) {
            $this->Flash->error(__('Only administrators may access the purposes module.'));
            return $this->redirect("/");
        }
        $purpose = $this->Purposes->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $purpose = $this->Purposes->patchEntity($purpose, $this->request->getData());
            if ($this->Purposes->save($purpose)) {
                $this->Flash->success(__('The purpose has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The purpose could not be saved. Please, try again.'));
        }

        $this->set('crumby', [
            ["/", __("Dashboard")],
            ["/purposes/", __("Shows")],
            ["/purposes/view/" . $purpose->id, $purpose->name],
            [null, __("Edit Purpose")]
        ]);

        $this->set(compact('purpose'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Purpose id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        if ( ! $this->Auth->user('is_admin')) {
            $this->Flash->error(__('Only administrators may access the purposes module.'));
            return $this->redirect("/");
        }
        $this->request->allowMethod(['post', 'delete']);
        $purpose = $this->Purposes->get($id);
        if ($this->Purposes->delete($purpose)) {
            $this->Flash->success(__('The purpose has been deleted.'));
        } else {
            $this->Flash->error(__('The purpose could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
