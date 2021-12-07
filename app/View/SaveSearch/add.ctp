<?php

// Add page for saving search queries to table
$edit = $this->request->params['action'] === 'edit' ? true: false;
if (!$edit) {
    echo $this->element('genericElements/Form/genericForm', [
        'data' => [
            'title' => __('Add Search Query'),
            'fields' => [
                [
                    'field' => 'title',
                    'label' => __('Title'),
                    'type' => 'text',
                    'error' => ['escape' => false],
                    'div' => 'input clear',
                    'class' => 'input-xlarge',
                ],
                [
                    'field' => 'value',
                    'label' => __('Value'),
                    'type' => 'text',
                    'error' => ['escape' => false],
                    'div' => 'input clear',
                    'class' => 'input-xlarge'
                ],
            ],
            'submit' => [
                [
                    'action' => $this->request->params['action'],
                    'ajaxSubmit' => 'submitGenericFormInPlace();'
                ]

            ]
        ]
    ]
    );
} else {
    if ($isSiteAdmin || ($thisUser == $this->request->data['SaveSearch']['user_id'])) {
        echo $this->element('genericElements/Form/genericForm', [
                'data' => [
                    'title' => __('Edit Search Query'),
                    'fields' => [
                        [
                            'field' => 'title',
                            'label' => __('Title'),
                            'type' => 'text',
                            'error' => ['escape' => false],
                            'div' => 'input clear',
                            'class' => 'input-xlarge',
                        ],
                        [
                            'field' => 'value',
                            'label' => __('Value'),
                            'type' => 'text',
                            'error' => ['escape' => false],
                            'div' => 'input clear',
                            'class' => 'input-xlarge'
                        ],
                    ],
                    'submit' => [
                        [
                            'action' => $this->request->params['action'],
                            'ajaxSubmit' => 'submitGenericFormInPlace();'
                        ]

                    ]
                ]
            ]
        );
    } else {
        echo "<h1 style='text-align:center;'> Error 403 - Forbidden </h1>";
        echo "<h4 style='text-align:center;'> You do not have permission to edit this query. </h4>";
    }
}
