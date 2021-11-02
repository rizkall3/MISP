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
        'contain' => array(
          'User.id',
          'User.email'
        )
    );

    public function index()
    {
        /* Didn't work
        if (!$this->_isSiteAdmin() && !$this->_isAdmin()) {
          $this->paginate['contain'] = array('User' => array('fields' => array('User.id', 'User.email')));
        }
        */
        $filterData = array(
            'request' => $this->request,
            'paramArray' => array('user_id', 'sort', 'direction', 'page', 'limit'),
            'named_params' => $this->params['named']
        );
        $exception = false;
        $filters = $this->_harvestParameters($filterData, $exception);
        $conditons = array();
        if(!empty($filters['user_id'])) {
            $conditions['AND'][] = array(
                'user_id' => $this->Auth->user('id')
            );
        }

        $this->paginate['contain'] = array('User' => array('fields' => array('User.id', 'User.email')));

        $conditions['AND'][] = array(
            'User.id' => $this->Auth->user('id')
        );

        if ($this->_isRest()) {
            $params = array(
                'conditions' => $conditions
            );
            if (!empty($filters['page'])) {
                $params['page'] = $filters['page'];
                $params['limit'] = $this->paginate['limit'];
            }
            if (!empty($filters['limit'])) {
                $params['limit'] = $filters['limit'];
            }
            //$privateSavedSearch = $this
            return $this->RestResponse->viewData($privateSaveSearches, $this->response->type());
        } else {
          $this->paginate['conditions'] = $conditions;
          $privateSavedSearches = $this->paginate();
        }

        //$privateSavedSearches = $this->paginate();

        $this->set('privateSavedSearches', $privateSavedSearches);

        $this->loadModel('User');
    }
    /* not used (possibly later)
    public function view($id)
    {
        // check if the ID is valid and whether a user setting with the given ID exists
        if (empty($id) || !is_numeric($id)) {
            throw new InvalidArgumentException(__('Invalid ID passed.'));
        }
        $privateSaveSearch = $this->privateSavedSearches->find('first', array(
            'recursive' => -1,
            'conditions' => array(
                'PrivateSaveSearch.id' => $id
            ),
            'contain' => array('User.id', 'User.org_id')
        ));
        if (empty($privateSaveSearch)) {
            throw new NotFoundException(__('Invalid bookmarks page.'));
        }
        $checkAccess = $this->UserSetting->checkAccess($this->Auth->user(), $privateSaveSearch);
        if (!$checkAccess) {
            throw new NotFoundException(__('Invalid bookmarks page.'));
        }
        if ($this->_isRest()) {
            unset($privateSaveSearch['User']);
            return $this->RestResponse->viewData($privateSaveSearch, $this->response->type());
        } else {
            $this->set($data, $privateSaveSearch);
        }
    }
    */

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
