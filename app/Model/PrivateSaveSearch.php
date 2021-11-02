<?php
App::uses('AppModel', 'Model');

class PrivateSaveSearch extends AppModel
{
    public $useTable = 'private_save_searches';
    public $actsAs = array('AuditLog', 'Containable');

    public $validate = array(
        'value' => array(
            'valueNotEmpty' => array(
                'rule' => array('valueNotEmpty'),
            ),
        ),
        'title' => array(
                'valueNotEmpty' => array(
                        'rule' => array('valueNotEmpty'),
                ),
        )
    );

    public $belongsTo = 'User';

}
