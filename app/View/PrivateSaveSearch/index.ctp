<?php

$this->set('menuData', ['menuList' => 'privatesavesearch', 'menuItem' => 'index']);

echo $this->element('genericElements/IndexTable/scaffold', [
        'scaffold_data' => [
            'data' => [
                'data' => $privateSavedSearches,
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
                    'data_path' => 'PrivateSaveSearch.id'
                ],
                [
                    'name' => __('User'),
                    'sort' => 'email',
                    'data_path' => 'User.email'
                ],
                [
                    'name' => __('Title'),
                    'sort' => 'title',
                    'data_path' => 'PrivateSaveSearch.title'
                ],
                [
                    'name' => __('Value'),
                    'sort' => 'value',
                    'data_path' => 'PrivateSaveSearch.value'
                ],
                [
                    'name' => __('Created at'),
                    'sort' => 'date_created',
                    'data_path' => 'PrivateSaveSearch.date_created',
                    'element' => 'datetime'
                ],
            ],
            'title' => empty($ajax) ? __('Your Saved Searches') : false,
            'pull' => 'right',
            'actions' => [
                [
                    'url' => $baseurl . '/PrivateSaveSearch/edit',
                    'url_params_data_paths' => [
                        'PrivateSaveSearch.id'
                    ],
                    'icon' => 'edit',
                    'title' => 'Edit Query',
                ],
                [
                    'onclick' => sprintf(
                        'openGenericModal(\'%s/PrivateSaveSearch/delete/[onclick_params_data_path]\');',
                        $baseurl
                    ),
                    'onclick_params_data_path' => 'PrivateSaveSearch.id',
                    'icon' => 'trash',
                    'title' => __('Delete query'),
                ]
            ]
        ]
    ]
]);
