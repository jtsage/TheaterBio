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
                   'Photos',
                   'sort' => ['Bios.is_prod' => 'ASC', 'Users.last' => 'ASC', 'Users.first' => 'ASC']
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

    public function download($id = null)
    {
        if ( ! $this->Auth->user('is_admin')) {
            $this->Flash->error(__('Only administrators may access the purposes module.'));
            return $this->redirect("/");
        }

        $purpose = $this->Purposes->get($id, [
            'contain' => [
                'Users' => [
                   'Photos',
                   'sort' => ['Bios.is_prod' => 'ASC', 'Users.last' => 'ASC', 'Users.first' => 'ASC']
                ]
            ]
        ]);

        exec( 'which pandoc', $output, $returnVar );
        if ( $returnVar === 0 ) {
            $pandoc_exec = $output[0];
        } else {
            $this->Flash->error(__('This feature is broken.  Sorry.'));
            return $this->redirect("/bios");
        }

        $string_bio = "";

        foreach ( $purpose->users as $user ) {
            $string_bio .= "<p><strike>" . $user->print_name . "</strike> <code>(" . $user->_joinData->role . ")</code></p>";
            $string_bio .= $user->_joinData->text;
        }

        $temp_file = tempnam(TMP, "BioConvert");

        $file = fopen($temp_file, "w");
        fwrite($file, $string_bio);
        fclose($file);

        $pandoc_cmd = $pandoc_exec . " --standalone --from=html --to=icml " . $temp_file;
        
        unset($output);

        exec(escapeshellcmd($pandoc_cmd), $output);

        unlink($temp_file);

        $file_name = $purpose->name;
        $file_name = preg_replace("/[^a-zA-Z0-9_\s-]/", "", $file_name);
        $file_name = preg_replace("/[\s]/", "_", $file_name);
        $file_name .= ".icml";

        $response = $this->response;
        $response = $response->withStringBody(implode("\n", $output));
        $response = $response->withType('xml');
        $response = $response->withDownload($file_name);
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
