<?php
$edit = $this->request->params['action'] === 'edit' ? true: false;
echo $this->element(
    '/genericElements/SideMenu/side_menu',
    [
        'menuList' => 'privatesavesearch',
        'menuItem' => $edit ? 'edit' : 'add'
    ]
);

echo $this->element('genericElements/Form/genericForm', [
    'data' => [
        'title' => $edit ? __('Edit Search Query') : __('Add Search Query'),
        'fields' => [
            [
                'field' => 'title',
                'label' => __('Title'),
                'type' => 'text',
                'error' => ['escape' => false],
                'div' => 'input clear',
                'class' => 'input-xxlarge',
            ],
            [
                'field' => 'value',
                'label' => __('Value'),
                'type' => 'text',
                'error' => ['escape' => false],
                'div' => 'input clear',
                'class' => 'input-xxlarge'
            ],
        ],
        'submit' => [
            'action' => $this->request->params['action'],
            'ajaxSubmit' => 'submitGenericFormInPlace();'
        ]
    ]
]);
