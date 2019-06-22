<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Users2 Controller
 *
 * @property \App\Model\Table\Users2Table $Users2
 *
 * @method \App\Model\Entity\Users2[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class Users2Controller extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $users2 = $this->paginate($this->Users2);

        $this->set(compact('users2'));
    }

    /**
     * View method
     *
     * @param string|null $id Users2 id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $users2 = $this->Users2->get($id, [
            'contain' => []
        ]);

        $this->set('users2', $users2);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $users2 = $this->Users2->newEntity();
        if ($this->request->is('post')) {
            $users2 = $this->Users2->patchEntity($users2, $this->request->getData());
            if ($this->Users2->save($users2)) {
                $this->Flash->success(__('The users2 has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The users2 could not be saved. Please, try again.'));
        }
        $this->set(compact('users2'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Users2 id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $users2 = $this->Users2->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $users2 = $this->Users2->patchEntity($users2, $this->request->getData());
            if ($this->Users2->save($users2)) {
                $this->Flash->success(__('The users2 has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The users2 could not be saved. Please, try again.'));
        }
        $this->set(compact('users2'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Users2 id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $users2 = $this->Users2->get($id);
        if ($this->Users2->delete($users2)) {
            $this->Flash->success(__('The users2 has been deleted.'));
        } else {
            $this->Flash->error(__('The users2 could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
