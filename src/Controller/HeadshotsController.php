<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Headshots Controller
 *
 * @property \App\Model\Table\HeadshotsTable $Headshots
 *
 * @method \App\Model\Entity\Headshot[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class HeadshotsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users', 'Purposes']
        ];
        $headshots = $this->paginate($this->Headshots);

        $this->set(compact('headshots'));
    }

    /**
     * View method
     *
     * @param string|null $id Headshot id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $headshot = $this->Headshots->get($id, [
            'contain' => ['Users', 'Purposes']
        ]);

        $this->set('headshot', $headshot);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $headshot = $this->Headshots->newEntity();
        if ($this->request->is('post')) {
            $headshot = $this->Headshots->patchEntity($headshot, $this->request->getData());
            if ($this->Headshots->save($headshot)) {
                $this->Flash->success(__('The headshot has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The headshot could not be saved. Please, try again.'));
        }
        $users = $this->Headshots->Users->find('list', ['limit' => 200]);
        $purposes = $this->Headshots->Purposes->find('list', ['limit' => 200]);
        $this->set(compact('headshot', 'users', 'purposes'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Headshot id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $headshot = $this->Headshots->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $headshot = $this->Headshots->patchEntity($headshot, $this->request->getData());
            if ($this->Headshots->save($headshot)) {
                $this->Flash->success(__('The headshot has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The headshot could not be saved. Please, try again.'));
        }
        $users = $this->Headshots->Users->find('list', ['limit' => 200]);
        $purposes = $this->Headshots->Purposes->find('list', ['limit' => 200]);
        $this->set(compact('headshot', 'users', 'purposes'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Headshot id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $headshot = $this->Headshots->get($id);
        if ($this->Headshots->delete($headshot)) {
            $this->Flash->success(__('The headshot has been deleted.'));
        } else {
            $this->Flash->error(__('The headshot could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
