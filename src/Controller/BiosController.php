<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\I18n\Time;

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
            'contain' => ['Users', 'Purposes'],
            'sortWhitelist' => [
                'Bios.id',
                'Users.last',
                'Bios.purpose_id'
            ],
            'order' => ['Bios.is_prod' => 'ASC', 'Users.last' => 'ASC', 'Users.first' => 'ASC']
        ];

        if ( $this->Auth->user('is_admin')) {
            $bios = $this->paginate($this->Bios->find('all'));
        } else {
            $bios = $this->paginate($this->Bios->find('all')
                ->where(['user_id' => $this->Auth->user('id')]));
        }

        $this->set('crumby', [
            ["/", __("Dashboard")],
            [null, __("Bios")]
        ]);

        $this->set('isAdmin', $this->Auth->user('is_admin'));
        $this->set('bios', $bios);

    }

    public function download($id = null)
    {
        $bio = $this->Bios->get($id, [
            'contain' => ['Users', 'Purposes']
        ]);

        if ( ! ( $this->Auth->user('is_admin') || $bio->user_id == $this->Auth->user('id') ) ) {
            $this->Flash->error(__('You may only download your own bios.'));
            return $this->redirect("/bios");
        }

        exec( 'which pandoc', $output, $returnVar );
        if ( $returnVar === 0 ) {
            $pandoc_exec = $output[0];
        } else {
            $this->Flash->error(__('This feature is broken.  Sorry.'));
            return $this->redirect("/bios");
        }

        $string_bio = "<p><strike>" . $bio->user->print_name . "</strike> <code>(" . $bio->role . ")</code></p>";
        $string_bio .= $bio->text;

        $temp_file = tempnam(TMP, "BioConvert");

        $file = fopen($temp_file, "w");
        fwrite($file, $string_bio);
        fclose($file);

        $pandoc_cmd = $pandoc_exec . " --standalone --from=html --to=icml " . $temp_file;
        
        unset($output);

        exec(escapeshellcmd($pandoc_cmd), $output);

        unlink($temp_file);

        $file_name = $bio->user->print_name . "-" . $bio->purpose->name;
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

        $this->set('crumby', [
            ["/", __("Dashboard")],
            ["/bios/", __("Bios")],
            [null, $bio->user->print_name . " - " . $bio->purpose->name ]
        ]);

        $this->set('tz', Configure::read('ServerTimeZoneFix'));

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
            if ( $this->Auth->user('is_admin') || $bio->user_id == $this->Auth->user('id') ) {
                if ($this->Bios->save($bio)) {
                    $this->Flash->success(__('The bio has been saved.'));

                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('The bio could not be saved. Please, try again.'));
            } else {
                $this->Flash->error(__('You may only add your own bios.'));
                return $this->redirect("/bios");
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
            ["/bios/", __("Bios")],
            [null, __("Add Bio")]
        ]);

        $users = $this->Bios->Users->find('list')
            ->where($userWhere)
            ->order(['Users.last' => 'ASC']);
        $purposes = $this->Bios->Purposes->find('list', ['limit' => 200])->where(['is_active' => 1 ]);
        $this->set(compact('bio', 'users', 'purposes'));
    }

    public function copy($id = null)
    {
        $bio = $this->Bios->get($id, [
            'contain' => []
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $bio = $this->Bios->newEntity();
            $bio = $this->Bios->patchEntity($bio, $this->request->getData());
            if ( $this->Auth->user('is_admin') || $bio->user_id == $this->Auth->user('id') ) {
                if ($this->Bios->save($bio)) {
                    $this->Flash->success(__('The bio has been saved.'));

                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('The bio could not be saved. Please, try again.'));
            } else {
                $this->Flash->error(__('You may only add your own bios.'));
                return $this->redirect("/bios");
            }
        }

        $this->set('crumby', [
            ["/", __("Dashboard")],
            ["/bios/", __("Bios")],
            [null, __("Copy Bio")]
        ]);

        $users = $this->Bios->Users->find('list', ['limit' => 200])->where(['id' => $bio->user_id]);
        $purposes = $this->Bios->Purposes->find('list', ['limit' => 200])->where(['is_active' => 1 ]);
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
            if ( $this->Auth->user('is_admin') || $bio->user_id == $this->Auth->user('id') ) {
                if ($this->Bios->save($bio)) {
                    $this->Flash->success(__('The bio has been saved.'));

                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('The bio could not be saved. Please, try again.'));
            } else {
                $this->Flash->error(__('You may only edit your own bios.'));
                return $this->redirect("/bios");
            }
        }

        $this->set('crumby', [
            ["/", __("Dashboard")],
            ["/bios/", __("Bios")],
            [null, __("Edit Bio")]
        ]);

        $users = $this->Bios->Users->find('list', ['limit' => 200])->where(['id' => $bio->user_id]);
        $purposes = $this->Bios->Purposes->find('list', ['limit' => 200])->where(['is_active' => 1 ]);
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
        if ( $this->Auth->user('is_admin') || $bio->user_id == $this->Auth->user('id') ) {

            if ($this->Bios->delete($bio)) {
                $this->Flash->success(__('The bio has been deleted.'));
            } else {
                $this->Flash->error(__('The bio could not be deleted. Please, try again.'));
            }
        } else {
            $this->Flash->error(__('You may only delete your own bios.'));
            return $this->redirect("/bios");
        }

        return $this->redirect(['action' => 'index']);
    }
}
