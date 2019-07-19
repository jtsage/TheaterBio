<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Filesystem\File;

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

        if ( $this->Auth->user('is_admin')) {
            $headshots = $this->paginate($this->Headshots->find('all')->order(['purpose_id' => 'ASC', 'Users.last' => 'ASC']));
        } else {
            $headshots = $this->paginate($this->Headshots->find('all')
                ->where(['user_id' => $this->Auth->user('id')]));
        }

        $this->set(compact('headshots'));
    }

    /**
     * View method
     *
     * @param string|null $id Headshot id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function download($id = null)
    {
        $headshot = $this->Headshots->get($id, [
            'contain' => ['Users', 'Purposes']
        ]);

        $file_ext = ltrim(strstr($headshot['file'], '.'), '.');

        $file_name = $headshot->user->print_name . "-" . $headshot->purpose->name;
        $file_name = preg_replace("/[^a-zA-Z0-9_\s-]/", "", $file_name);
        $file_name = preg_replace("/[\s]/", "_", $file_name);
        $file_name .= "." . $file_ext;

        $file_full = ROOT . DS . $headshot['dir'] . DS . $headshot['file'];
        $response = $this->response->withFile($file_full,
            ['download' => true, 'name' => $file_name]
        );
    // Return the response to prevent controller from trying to render
    // a view.
        return $response;
    }

    public function view($id = null, $file = null)
    {
        $headshot = $this->Headshots->get($id, [
            'contain' => ['Users', 'Purposes']
        ]);


        $file_ext = ltrim(strstr($headshot['file'], '.'), '.');

        $file_name = $headshot->user->print_name . "-" . $headshot->purpose->name;
        $file_name = preg_replace("/[^a-zA-Z0-9_\s-]/", "", $file_name);
        $file_name = preg_replace("/[\s]/", "_", $file_name);
        $file_name .= "." . $file_ext;

        if ( is_null($file) ) {
            // Yikes.  But it works.
            return $this->redirect(['action' => 'view', $id, $file_name]);
        }

        $file_full = ROOT . DS . $headshot['dir'] . DS . $headshot['file'];
        $response = $this->response->withFile($file_full,
            ['download' => false, 'name' => $file_name]
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
        $headshot = $this->Headshots->newEntity();
        if ($this->request->is('post')) {
            $headshot = $this->Headshots->patchEntity($headshot, $this->request->getData());
                if ( $this->Auth->user('is_admin') || $headshot->user_id == $this->Auth->user('id') ) {
                if ($this->Headshots->save($headshot)) {
                    $this->Flash->success(__('The headshot has been saved.'));

                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('The headshot could not be saved. Please, try again.'));
            } else {
                $this->Flash->error(__('You may only add your own headshots.'));
                return $this->redirect("/headshots");
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
            ["/headshots/", __("Headshots")],
            [null, __("Add Headshot")]
        ]);

        $users = $this->Headshots->Users->find('list')
            ->where($userWhere)
            ->order(['Users.last' => 'ASC']);
        $purposes = $this->Headshots->Purposes->find('list', ['limit' => 200])->where(['is_active' => 1 ]);
        
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
        $this->Flash->error(__('Editing headshots is not possible.  Remove and re-upload'));
        return $this->redirect(['action' => 'index']);
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
        $path = ROOT . DS . $headshot->dir . DS . $headshot->file;
        $file = new File($path);

        if ($this->Headshots->delete($headshot)) {
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
