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

    public function beforeFilter() {
        parent::beforeFilter();

        // Sets thisUser to the user by id var from db
        $thisUser = $this->Auth->user('id');
        $this->set('thisUser', $thisUser);
    }

    // Creates the individuality of privateSavedSearch page (unique to each user)
    public function index()
    {
        /* Didn't work
        if (!$this->_isSiteAdmin() && !$this->_isAdmin()) {
          $this->paginate['contain'] = array('User' => array('fields' => array('User.id', 'User.email')));
        }
        */
        $context = 'private'; // private page
        $filterData = array(
            'request' => $this->request,
            'paramArray' => array('user_id', 'sort', 'direction', 'page', 'limit'),
            'named_params' => $this->params['named']
        );
        $exception = false;
        $filters = $this->_harvestParameters($filterData, $exception);
        $conditions = array();
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
            return $this->RestResponse->viewData($privateSavedSearches, $this->response->type());
        } else {
            $this->paginate['conditions'] = $conditions;
            $privateSavedSearches = $this->paginate();
        }
        //$privateSavedSearches = $this->paginate();

        // Set privateSavedSearches var to data from privateSavedSearches
        $this->set('privateSavedSearches', $privateSavedSearches);
        $this->set('context', empty($context) ? 'null' : $context);
        // Loads user model
        $this->loadModel('User');


        if (!empty($this->passedArgs['value'])) {
            $search = $this->__search($this->passedArgs['value']);
            $searchFixed = array();
            foreach ($privateSavedSearches as $key => $value) {
                if (in_array($privateSavedSearches[$key]['PrivateSaveSearch']['id'], $search, true)) {
                    array_push($searchFixed, $privateSavedSearches[$key]);
                }
            }
            $this->set('privateSavedSearches', $searchFixed);
        }
        else {
            $this->set('privateSavedSearches', $privateSavedSearches);
        }

        $this->set('context', empty($context) ? 'null' : $context);
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
                //$this->redirect($this->referer());
            } else {
                $this->Flash->error(__('The search query could not be added.'));
                //$this->redirect($this->referer());
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
                //$this->redirect($this->referer());
            } else {
                $this->Flash->error(__('Could not update search query.'));
                //$this->redirect($this->referer());
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

    private function __search($value)
    {
        $value = mb_strtolower(trim($value));
        $searchTerm = "%$value%";
        $searchId = $this->PrivateSaveSearch->find('column', [
            'fields' => ['PrivateSaveSearch.id'],
            'conditions' => ['OR' => [
                'LOWER(title) LIKE' => $searchTerm,
            ]],
        ]);
        return $searchId;
    }
}
