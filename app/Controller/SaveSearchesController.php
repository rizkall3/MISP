<?php
App::uses('AppController', 'Controller');

class SaveSearchesController extends AppController
{
  // idk
  public function index()
  {
    $filterData = array(
            'request' => $this->request,
            'paramArray' => array('setting', 'user_id', 'sort', 'direction', 'page', 'limit'),
            'named_params' => $this->params['named']
        );
        $exception = false;
        $filters = $this->_harvestParameters($filterData, $exception);
        $conditions = array();
        if (!empty($filters['setting'])) {
            $conditions['AND'][] = array(
                'setting' => $filters['setting']
            );
        }
        if (!empty($filters['user_id'])) {
            if ($filters['user_id'] === 'all') {
                $context = 'all';
            } else if ($filters['user_id'] === 'me') {
                $conditions['AND'][] = array(
                    'user_id' => $this->Auth->user('id')
                );
                $context = 'me';
            } else if ($filters['user_id'] === 'org') {
                $conditions['AND'][] = array(
                    'user_id' => $this->UserSetting->User->find(
                        'list', array(
                            'conditions' => array(
                                'User.org_id' => $this->Auth->user('org_id')
                            ),
                            'fields' => array(
                                'User.id', 'User.id'
                            )
                        )
                    )
                );
                $context = 'org';
            } else {
                $conditions['AND'][] = array(
                    'user_id' => $filters['user_id']
                );
            }
        }
        if (!$this->_isSiteAdmin()) {
            if ($this->_isAdmin()) {
                $conditions['AND'][] = array(
                    'UserSetting.user_id' => $this->UserSetting->User->find(
                        'list', array(
                            'conditions' => array(
                                'User.org_id' => $this->Auth->user('org_id')
                            ),
                            'fields' => array(
                                'User.id', 'User.id'
                            )
                        )
                    )
                );
            } else {
                $conditions['AND'][] = array(
                    'UserSetting.user_id' => $this->Auth->user('id')
                );
            }
        }
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
            $userSettings = $this->UserSetting->find('all', $params);
            return $this->RestResponse->viewData($userSettings, $this->response->type());
        } else {
            $this->paginate['conditions'] = $conditions;
            $data = $this->paginate();
            foreach ($data as $k => $v) {
                if (!empty($this->UserSetting->validSettings[$v['UserSetting']['setting']])) {
                    $data[$k]['UserSetting']['restricted'] = empty($this->UserSetting->validSettings[$v['UserSetting']['setting']]['restricted']) ? '' : $this->UserSetting->validSettings[$v['UserSetting']['setting']]['restricted'];
                } else {
                    $data[$k]['UserSetting']['restricted'] = array();
                }
            }
            $this->set('data', $data);
            $this->set('context', empty($context) ? 'null' : $context);
      }
  }
}
