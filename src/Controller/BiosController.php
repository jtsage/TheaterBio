<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Bios Controller
 *
 * @property \App\Model\Table\BiosTable $Bios
 *
 * @method \App\Model\Entity\Bio[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class BiosController extends AppController
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

        if ( $this->Auth->user('is_admin')) {
            $bios = $this->paginate($this->Bios->find('all'));
        } else {
            $bios = $this->paginate($this->Bios->find('all')
                ->where(['user_id' => $this->Auth->user('id')]));
        }

        $this->set('isAdmin', $this->Auth->user('is_admin'));
        $this->set('bios', $bios);

    }

    /**
     * View method
     *
     * @param string|null $id Bio id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $bio = $this->Bios->get($id, [
            'contain' => ['Users', 'Purposes']
        ]);

        if ( $this->Auth->user('is_admin') || $bio->user_id == $this->Auth->user('id') ) {
            $this->set('bio', $bio);
        } else {
            $this->Flash->error(__('You may only view your own bios.'));
            return $this->redirect("/bios");
        }
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $bio = $this->Bios->newEntity();
        if ($this->request->is('post')) {
            $bio = $this->Bios->patchEntity($bio, $this->request->getData());
            if ($this->Bios->save($bio)) {
                $this->Flash->success(__('The bio has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The bio could not be saved. Please, try again.'));
        }
        $users = $this->Bios->Users->find('list', ['limit' => 200]);
        $purposes = $this->Bios->Purposes->find('list', ['limit' => 200]);
        $this->set(compact('bio', 'users', 'purposes'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Bio id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $bio = $this->Bios->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $bio = $this->Bios->patchEntity($bio, $this->request->getData());
            if ($this->Bios->save($bio)) {
                $this->Flash->success(__('The bio has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The bio could not be saved. Please, try again.'));
        }
        $users = $this->Bios->Users->find('list', ['limit' => 200]);
        $purposes = $this->Bios->Purposes->find('list', ['limit' => 200]);
        $this->set(compact('bio', 'users', 'purposes'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Bio id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $bio = $this->Bios->get($id);
        if ($this->Bios->delete($bio)) {
            $this->Flash->success(__('The bio has been deleted.'));
        } else {
            $this->Flash->error(__('The bio could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
