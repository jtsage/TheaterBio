<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Files Controller
 *
 * @property \App\Model\Table\FilesTable $Files
 *
 * @method \App\Model\Entity\File[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FilesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $files = $this->paginate($this->Files);
        $this->set('crumby', [
            ["/", __("Dashboard")],
            [null, __("Stored Files")]
        ]);

        if ( $this->Auth->user('is_admin') ) {
            $this->set('opsok', true);
        } else {
            $this->set('opsok', false);
        }

        $this->set(compact('files'));
    }

    /**
     * View method
     *
     * @param string|null $id File id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $file = $this->Files->get($id, [
            'contain' => []
        ]);

        $content = base64_decode(fread($file->fle, $file->fle_size*2));

        $this->set('file', $file);
        $response = $this->response;
        $response = $response
            ->withHeader('Content-Type', $file->fle_type)
            ->withHeader('Content-length', $file->fle_size)
            ->withStringBody($content);

        return $response;

 
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        if ( ! $this->Auth->user('is_admin')) {
            $this->Flash->error(__('Only Administrative users may upload, alter, or delete files.')); 
            return $this->redirect('/');
        }
        $file = $this->Files->newEntity();
        if ($this->request->is('post')) {

            $file = $this->Files->patchEntity($file, $this->request->getData());
            $file->fle_size = $_FILES['uppy']['size'];
            $file->fle_type = $_FILES['uppy']['type'];

            $tmpName = $_FILES['uppy']['tmp_name'];

            $fp      = fopen($tmpName, 'r');
            $content = fread($fp, filesize($tmpName));
            $content = base64_encode($content);
            fclose($fp);

            $file->fle = $content;

            if ($this->Files->save($file)) {
                $this->Flash->success(__('The file has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The file could not be saved. Please, try again.'));
        }
        $this->set('crumby', [
            ["/", __("Dashboard")],
            ["/files/", __("Stored Files")],
            [null, __("Add Stored File")]
        ]);
        $this->set(compact('file'));
    }


    /**
     * Delete method
     *
     * @param string|null $id File id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        if ( ! $this->Auth->user('is_admin')) {
            $this->Flash->error(__('Only Administrative users may upload, alter, or delete files.')); 
            return $this->redirect('/');
        }
        $this->request->allowMethod(['post', 'delete']);
        $file = $this->Files->get($id);
        if ($this->Files->delete($file)) {
            $this->Flash->success(__('The file has been deleted.'));
        } else {
            $this->Flash->error(__('The file could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
