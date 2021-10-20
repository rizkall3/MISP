<?php
App::uses('AppController', 'Controller');

class SaveSearchesController extends AppController
{
  public $components = array('Session', 'RequestHandler');

  public $paginate = array(
    'limit' => 20,
    'recursive' => 0,
    'order' => array(
      'SaveSearches.id' => 'asc'
    )
  );

  public function index()
  {
      $paginationParams = array('limit', 'page', 'sort', 'direction', 'order');
      $overrideAbleParams = array('searchid', 'email', 'link');
      $passedArgs = $this->passedArgs;
      if (isset($this->request->data)) {
        if (isset($this->request->data['request'])) {
            $this->request->data = $this->request->data['request'];
        }
        foreach ($this->request->data as $k => $v) {
            if (substr($k, 0, 6) === 'search' && in_array(strtolower(substr($k, 6)), $overrideAbleParams)) {
                unset($this->request->data[$k]);
                $this->request->data[strtolower(substr($k, 6))] = $v;
            } else if (in_array(strtolower($k), $overrideAbleParams)) {
                unset($this->request->data[$k]);
                $this->request->data[strtolower($k)] = $v;
            }
        }
        foreach ($overrideAbleParams as $oap) {
            if (isset($this->request->data[$oap])) {
                $passedArgs['search' . $oap] = $this->request->data[$oap];
            }
        }
        foreach ($paginationParams as $paginationParam) {
            if (isset($this->request->data[$paginationParam])) {
                $passedArgs[$paginationParam] = $this->request->data[$paginationParam];
            }
        }
      }
  }
}
