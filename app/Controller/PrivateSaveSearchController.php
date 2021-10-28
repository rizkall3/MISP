<?php
App::uses('AppController', 'Controller', 'CRUD');

class PrivateSaveSearchController extends AppController
{

    public $components = array('Session', 'RequestHandler');

    public $paginate = array(
        'limit' => 5,
        'maxLimit' => 9999,
        'order' => array(
            'PrivateSaveSearch.id' => 'DESC'
        ),
    );

    public function index()
    {

        $this->paginate['contain'] = array('User' => array('fields' => array('User.email')));
        $privateSavedSearches = $this->paginate();

        $this->set('privateSavedSearches', $privateSavedSearches);

        $this->loadModel('User');
    }

    public function add()
    {
        if ($this->request->is('post')) {
            $this->PrivateSaveSearch->create();
            $this->request->data['PrivateSaveSearch']['date_created'] = time();
            $this->request->data['PrivateSaveSearch']['user_id'] = $this->Auth->user('id');
            if ($this->PrivateSaveSearch->save($this->request->data)) {
                $this->Flash->success(__('Search Query added.'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Flash->error(__('The search query could not be added.'));
            }
        }
    }

    public function edit($id)
    {
        $this->PrivateSaveSearch->id = $id;
        if(!$this->PrivateSaveSearch->exists()) {
            throw new NotFoundException('Invalid search query.');
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['PrivateSaveSearch']['id'] = $id;
            if ($this->PrivateSaveSearch->save($this->request->data)) {
                $this->Flash->success(__('Search Query updated.'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Flash->error(__('Could not update search query.'));
            }
        } else {
            $this->request->data = $this->PrivateSaveSearch->read(null, $id);
            $this->set('privateSavedSearches', $this->request->data);
        }
        $this->render('add');
    }

    public function delete($id)
    {
        $this->defaultModel = 'PrivateSaveSearch';
        $this->CRUD->delete($id);
        if ($this->IndexFilter->isRest()) {
            return $this->restResponsePayload;
        }
    }
}
