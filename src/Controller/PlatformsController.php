<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Platforms Controller
 *
 * @property \App\Model\Table\PlatformsTable $Platforms
 */
class PlatformsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Platforms->find();
        $platforms = $this->paginate($query);

        $this->set(compact('platforms'));
    }

    /**
     * View method
     *
     * @param string|null $id Platform id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $platform = $this->Platforms->get($id, contain: ['Logs', 'Services']);
        $this->set(compact('platform'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $platform = $this->Platforms->newEmptyEntity();
        if ($this->request->is('post')) {
            $platform = $this->Platforms->patchEntity($platform, $this->request->getData());
            if ($this->Platforms->save($platform)) {
                $this->Flash->success(__('The platform has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The platform could not be saved. Please, try again.'));
        }
        $this->set(compact('platform'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Platform id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $platform = $this->Platforms->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $platform = $this->Platforms->patchEntity($platform, $this->request->getData());
            if ($this->Platforms->save($platform)) {
                $this->Flash->success(__('The platform has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The platform could not be saved. Please, try again.'));
        }
        $this->set(compact('platform'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Platform id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $platform = $this->Platforms->get($id);
        if ($this->Platforms->delete($platform)) {
            $this->Flash->success(__('The platform has been deleted.'));
        } else {
            $this->Flash->error(__('The platform could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
