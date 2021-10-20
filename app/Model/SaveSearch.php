<?php
App::uses('AppModel', 'Model');

class SaveSearch extends AppModel
{
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
