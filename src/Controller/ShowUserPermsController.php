<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ShowUserPerms Controller
 *
 * @property \App\Model\Table\ShowUserPermsTable $ShowUserPerms
 */
class ShowUserPermsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->Flash->error(__('This action is not allowed'));
        return $this->redirect('/users/');
        $this->paginate = [
            'contain' => ['Users', 'Shows']
        ];
        $this->set('showUserPerms', $this->paginate($this->ShowUserPerms));
        $this->set('_serialize', ['showUserPerms']);
    }

    /**
     * View method
     *
     * @param string|null $id Show User Perm id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->Flash->error(__('This action is not allowed'));
        return $this->redirect('/users/');
        $showUserPerm = $this->ShowUserPerms->get($id, [
            'contain' => ['Users', 'Shows']
        ]);
        $this->set('showUserPerm', $showUserPerm);
        $this->set('_serialize', ['showUserPerm']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->Flash->error(__('This action is not allowed'));
        return $this->redirect('/users/');
        $showUserPerm = $this->ShowUserPerms->newEntity();
        if ($this->request->is('post')) {
            $showUserPerm = $this->ShowUserPerms->patchEntity($showUserPerm, $this->request->data);
            if ($this->ShowUserPerms->save($showUserPerm)) {
                $this->Flash->success(__('The show user perm has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The show user perm could not be saved. Please, try again.'));
            }
        }
        $users = $this->ShowUserPerms->Users->find('list', ['limit' => 200]);
        $shows = $this->ShowUserPerms->Shows->find('list', ['limit' => 200]);
        $this->set(compact('showUserPerm', 'users', 'shows'));
        $this->set('_serialize', ['showUserPerm']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Show User Perm id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->Flash->error(__('This action is not allowed'));
        return $this->redirect('/users/');
        $showUserPerm = $this->ShowUserPerms->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $showUserPerm = $this->ShowUserPerms->patchEntity($showUserPerm, $this->request->data);
            if ($this->ShowUserPerms->save($showUserPerm)) {
                $this->Flash->success(__('The show user perm has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The show user perm could not be saved. Please, try again.'));
            }
        }
        $users = $this->ShowUserPerms->Users->find('list', ['limit' => 200]);
        $shows = $this->ShowUserPerms->Shows->find('list', ['limit' => 200]);
        $this->set(compact('showUserPerm', 'users', 'shows'));
        $this->set('_serialize', ['showUserPerm']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Show User Perm id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->Flash->error(__('This action is not allowed'));
        return $this->redirect('/users/');
        $this->request->allowMethod(['post', 'delete']);
        $showUserPerm = $this->ShowUserPerms->get($id);
        if ($this->ShowUserPerms->delete($showUserPerm)) {
            $this->Flash->success(__('The show user perm has been deleted.'));
        } else {
            $this->Flash->error(__('The show user perm could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
