<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Filesystem\File;

/**
 * Photos Controller
 *
 * @property \App\Model\Table\PhotosTable $Photos
 *
 * @method \App\Model\Entity\Photo[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PhotosController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users']
        ];

        if ( $this->Auth->user('is_admin')) {
            $photos = $this->paginate($this->Photos->find('all')->order(['Users.last' => 'ASC', 'Users.first' => 'ASC']));
        } else {
            $photos = $this->paginate($this->Photos->find('all')
                ->where(['user_id' => $this->Auth->user('id')]));
        }

        $this->set(compact('photos'));
    }

    /**
     * View method
     *
     * @param string|null $id Photo id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null, $file = null)
    {
        $photo = $this->Photos->get($id, [
            'contain' => ['Users']
        ]);


        $file_ext = ltrim(strstr($photo['file'], '.'), '.');

        $file_name = $photo->user->print_name;
        $file_name = preg_replace("/[^a-zA-Z0-9_\s-]/", "", $file_name);
        $file_name = preg_replace("/[\s]/", "_", $file_name);
        $file_name .= "." . $file_ext;

        if ( is_null($file) ) {
            // Yikes.  But it works.
            return $this->redirect(['action' => 'view', $id, $file_name]);
        }

        $file_full = ROOT . DS . $photo['dir'] . DS . $photo['file'];
        $response = $this->response->withFile($file_full,
            ['download' => false, 'name' => $file_name]
        );
    // Return the response to prevent controller from trying to render
    // a view.
        return $response;
    }
    public function download($id = null)
    {
        $photo = $this->Photos->get($id, [
            'contain' => ['Users']
        ]);


        $file_ext = ltrim(strstr($photo['file'], '.'), '.');

        $file_name = $photo->user->print_name;
        $file_name = preg_replace("/[^a-zA-Z0-9_\s-]/", "", $file_name);
        $file_name = preg_replace("/[\s]/", "_", $file_name);
        $file_name .= "." . $file_ext;

        $file_full = ROOT . DS . $photo['dir'] . DS . $photo['file'];
        $response = $this->response->withFile($file_full,
            ['download' => true, 'name' => $file_name]
        );
    // Return the response to prevent controller from trying to render
    // a view.
        return $response;
    }


    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $photo = $this->Photos->newEntity();
        if ($this->request->is('post')) {
            $photo = $this->Photos->patchEntity($photo, $this->request->getData());
            if ( $this->Auth->user('is_admin') || $photo->user_id == $this->Auth->user('id') ) {
                if ($this->Photos->save($photo)) {
                    $this->Flash->success(__('The headshot has been saved.'));

                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('The headshot could not be saved. Please, try again.'));
            } else {
                $this->Flash->error(__('You may only add your own headshots.'));
                return $this->redirect("/photos");
            }
        }

        $userWhere = [
            'is_active' => 1,
            'is_verified' => 1,
        ];

        if ( ! $this->Auth->user('is_admin') ) {
            $userWhere['id'] = $this->Auth->user('id');
        }

        $this->set('crumby', [
            ["/", __("Dashboard")],
            ["/photos/", __("Headshots")],
            [null, __("Add Headshot")]
        ]);

        $users = $this->Photos->Users->find('list')
            ->where($userWhere)
            ->order(['Users.last' => 'ASC']);

        $this->set(compact('photo', 'users'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Photo id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->Flash->error(__('Editing headshots is not possible.  Remove and re-upload'));
        return $this->redirect(['action' => 'index']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Photo id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $photo = $this->Photos->get($id);
        $path = ROOT . DS . $photo->dir . DS . $photo->file;
        $file = new File($path);

        if ($this->Photos->delete($photo)) {
            if ( $file->exists() ) {
                $file->delete();
            }
            $file->close();
            $this->Flash->success(__('The headshot has been deleted.'));
        } else {
            $this->Flash->error(__('The headshot could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
