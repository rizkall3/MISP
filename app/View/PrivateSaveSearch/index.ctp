<?php

$this->set('menuData', ['menuList' => 'privatesavesearch', 'menuItem' => 'index']);
echo $this->element('genericElements/IndexTable/scaffold', [
        'scaffold_data' => [
            'data' => [
                'top_bar' => [
                    'children' => [
                        [
                            'children' => [
                                [
                                    'active' => $context === 'public',
                                    'url' => $baseurl . '/SaveSearch',
                                    'text' => __('Public'),
                                ],
                                [
                                    'active' => $context === 'private',
                                    'url' => $baseurl . '/PrivateSaveSearch',
                                    'text' => __('Private'),
                                ],
                                [
                                    'onClick' => 'saveSearchQuery()',
                                    'title' => __('Add Query'),
                                    'text' => __('Add Search Query'),
                                    'pull' => 'right'
                                ]
                            ]
                        ]
                    ]
                ],
                'data' => $privateSavedSearches,
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
                        'data_path' => 'PrivateSaveSearch.value',
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
                        'onclick' => sprintf('openGenericModal(\'%s/PrivateSaveSearch/edit/[onclick_params_data_path]\');', $baseurl),
                        'onclick_params_data_path' => 'PrivateSaveSearch.id',
                        'icon' => 'edit',
                        'title' => __('Edit Query'),
                        'complex_requirement' => [
                            'function' => function($object) use($isSiteAdmin, $thisUser) {
                                return $thisUser == $object['PrivateSaveSearch']['user_id'];
                            },
                        ]
                    ],
                    [
                        'onclick' => sprintf('openGenericModal(\'%s/PrivateSaveSearch/delete/[onclick_params_data_path]\');', $baseurl),
                        'onclick_params_data_path' => 'PrivateSaveSearch.id',
                        'icon' => 'trash',
                        'title' => __('Delete query'),
                        'complex_requirement' => [
                            'function' => function($object) use($isSiteAdmin, $thisUser) {
                                return $thisUser == $object['PrivateSaveSearch']['user_id'];
                            },
                        ]
                    ],
                    [
                        'onclick' => sprintf('openMailTo(\'[onclick_params_data_path]\');'),
                        'onclick_params_data_path' => 'PrivateSaveSearch.value',
                        'icon' => 'share',
                        'title' => __('Share Query')
                    ]
                ]
            ]
        ]
    ]);
