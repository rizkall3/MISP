<?php

$this->set('menuData', ['menuList' => 'savesearch', 'menuItem' => 'index']);

echo $this->element('genericElements/IndexTable/scaffold', [
        'scaffold_data' => [
            'data' => [
                'data' => $savedSearches,
                'top_bar' => [
                    'children' => [
                        [
                          'type' => 'simple',
                          'children' => [
                              [
                                  'active' => $context === 'public',
                                  'url' => $baseurl . '/SaveSearch/index',
                                  'text' => __('Public'),
                              ],
                              [
                                  'active' => $context === 'private',
                                  'url' => $baseurl . '/PrivateSaveSearch/index',
                                  'text' => __('Private'),
                              ]

                          ]
                        ]
                    ]
                ],
                'fields' => [
                [
                    'name' => __('Id'),
                    'sort' => 'id',
                    'data_path' => 'SaveSearch.id'
                ],
                [
                    'name' => __('User'),
                    'sort' => 'email',
                    'data_path' => 'User.email'
                ],
                [
                    'name' => __('Title'),
                    'sort' => 'title',
                    'data_path' => 'SaveSearch.title'
                ],
                [
                    'name' => __('Value'),
                    'sort' => 'value',
                    'data_path' => 'SaveSearch.value'
                ],
                [
                    'name' => __('Created at'),
                    'sort' => 'date_created',
                    'data_path' => 'SaveSearch.date_created',
                    'element' => 'datetime'
                ],
            ],
            'title' => empty($ajax) ? __('Saved Searches') : false,
            'pull' => 'right',
            'actions' => [
                [
                    'url' => $baseurl . '/SaveSearch/edit',
                    'url_params_data_paths' => [
                        'SaveSearch.id'
                    ],
                    'icon' => 'edit',
                    'title' => 'Edit Query',
                ],
                [
                    'onclick' => sprintf(
                        'openGenericModal(\'%s/SaveSearch/delete/[onclick_params_data_path]\');',
                        $baseurl
                    ),
                    'onclick_params_data_path' => 'SaveSearch.id',
                    'icon' => 'trash',
                    'title' => __('Delete query'),
                ]
            ]
        ]
    ]
]);
