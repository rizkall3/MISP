<?php
App::uses('AppController', 'Controller', 'CRUD');

class SaveSearchController extends AppController
{
    public $components = array('Session', 'RequestHandler');

    public $paginate = array(
        'limit' => 5,
        'maxLimit' => 9999,
        'order' => array(
            'SaveSearch.id' => 'DESC'
        ),
    );

    public function beforeFilter() {
        parent::beforeFilter();

        $thisUser = $this->Auth->user('id');
        $this->set('thisUser', $thisUser);
    }

    public function index()
    {
        $context = 'public'; // public page
        $this->paginate['contain'] = array('User' => array('fields' => array('User.email')));
        $savedSearches = $this->paginate();

        if (!empty($this->passedArgs['value'])) {
            $search = $this->__search($this->passedArgs['value']);
            $searchFixed = array();
            foreach ($savedSearches as $key => $value) {
                if (in_array($savedSearches[$key]['SaveSearch']['id'], $search, true)) {
                    array_push($searchFixed, $savedSearches[$key]);
                }
            }
            $this->set('savedSearches', $searchFixed);
        }
        else {
            $this->set('savedSearches', $savedSearches);
        }

        $this->set('context', empty($context) ? 'null' : $context);
        $this->loadModel('User');
    }

    public function add()
    {
        if ($this->request->is('post')) {
            $this->SaveSearch->create();
            $this->request->data['SaveSearch']['date_created'] = time();
            $this->request->data['SaveSearch']['user_id'] = $this->Auth->user('id');
            if ($this->SaveSearch->save($this->request->data)) {
                $this->Flash->success(__('Search Query added.'));
                $this->redirect(array('action' => 'index'));
                //$this->redirect($this->referer());
            } else {
                $this->Flash->error(__('The search query could not be added.'));
                //$this->redirect($this->referer());
            }
        }
    }

    public function edit($id)
    {
        $this->SaveSearch->id = $id;
        if(!$this->SaveSearch->exists()) {
            throw new NotFoundException('Invalid search query.');
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['SaveSearch']['id'] = $id;
            if ($this->SaveSearch->save($this->request->data)) {
                $this->Flash->success(__('Search Query updated.'));
                $this->redirect(array('action' => 'index'));
                //$this->redirect($this->referer());
            } else {
                $this->Flash->error(__('Could not update search query.'));
                //$this->redirect($this->referer());
            }
        } else {
            $this->request->data = $this->SaveSearch->read(null, $id);
            $this->set('savedSearches', $this->request->data);
        }
        $this->render('add');
    }

    public function delete($id)
    {
        $this->defaultModel = 'SaveSearch';
        $this->CRUD->delete($id);
        if ($this->IndexFilter->isRest()) {
            return $this->restResponsePayload;
        }
    }

    private function __search($value)
    {
        $value = mb_strtolower(trim($value));
        $searchTerm = "%$value%";
        $searchId = $this->SaveSearch->find('column', [
            'fields' => ['SaveSearch.id'],
            'conditions' => ['OR' => [
                'LOWER(title) LIKE' => $searchTerm,
            ]],
        ]);
        return $searchId;
    }

}
