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
    ),
  );

  public function index()
  {

  }
}
