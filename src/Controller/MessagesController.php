<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Messages Controller
 *
 * @property \App\Model\Table\MessagesTable $Messages
 */
class MessagesController extends AppController
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
            'contain' => ['Users']
        ];
        $this->set('messages', $this->paginate($this->Messages));
        $this->set('_serialize', ['messages']);
    }

    /**
     * View method
     *
     * @param string|null $id Message id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->Flash->error(__('This action is not allowed'));
        return $this->redirect('/users/');
        $message = $this->Messages->get($id, [
            'contain' => ['Users']
        ]);
        $this->set('message', $message);
        $this->set('_serialize', ['message']);
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
        $message = $this->Messages->newEntity();
        if ($this->request->is('post')) {
            $message = $this->Messages->patchEntity($message, $this->request->data);
            if ($this->Messages->save($message)) {
                $this->Flash->success(__('The message has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The message could not be saved. Please, try again.'));
            }
        }
        $users = $this->Messages->Users->find('list', ['limit' => 200]);
        $this->set(compact('message', 'users'));
        $this->set('_serialize', ['message']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Message id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->Flash->error(__('This action is not allowed'));
        return $this->redirect('/users/');
        $message = $this->Messages->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $message = $this->Messages->patchEntity($message, $this->request->data);
            if ($this->Messages->save($message)) {
                $this->Flash->success(__('The message has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The message could not be saved. Please, try again.'));
            }
        }
        $users = $this->Messages->Users->find('list', ['limit' => 200]);
        $this->set(compact('message', 'users'));
        $this->set('_serialize', ['message']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Message id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {

        $this->request->allowMethod(['post', 'delete']);
        $message = $this->Messages->get($id);

        if ( $message->user_id == $this->Auth->user('id') ) {
            if ($this->Messages->delete($message)) {
                $this->Flash->success(__('The message has been deleted.'));
            } else {
                $this->Flash->error(__('The message could not be deleted. Please, try again.'));
            }
        } else {
            $this->Flash->error(__("You cannont delete messages not addressed to you."));
        }
        
        return $this->redirect("/");
    }

    public function clear($id = null)
    {

        $this->request->allowMethod(['post', 'delete']);

        if ( $id <> $this->Auth->user('id') ) {
            $this->Flash->error(__('You can only clear your own messages!'));
        } elseif ( $this->Messages->deleteAll(['user_id' => $this->Auth->user('id')]) ) {
            $this->Flash->success(__('All messages have been cleared.'));
        } else {
            $this->Flash->error(__('Something went wrong.  Odd.'));
        }
        
        return $this->redirect("/");
    }
}
