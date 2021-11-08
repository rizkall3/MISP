<?php

$this->set('menuData', ['menuList' => 'savesearch', 'menuItem' => 'index']);
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
            'data' => $savedSearches,
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
                    'data_path' => 'SaveSearch.value',
                    'element' => 'links'
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
                    'onclick' => sprintf('openGenericModal(\'%s/SaveSearch/edit/[onclick_params_data_path]\');', $baseurl),
                    'onclick_params_data_path' => 'SaveSearch.id',
                    'icon' => 'edit',
                    'title' => __('Edit Query'),
                    'complex_requirement' => [
                        'function' => function($object) use($isSiteAdmin, $thisUser) {
                            return $isSiteAdmin || ($thisUser == $object['SaveSearch']['user_id']);
                        },
                    ]
                ],
                [
                    'onclick' => sprintf('openGenericModal(\'%s/SaveSearch/delete/[onclick_params_data_path]\');', $baseurl),
                    'onclick_params_data_path' => 'SaveSearch.id',
                    'icon' => 'trash',
                    'title' => __('Delete Query'),
                    'complex_requirement' => [
                        'function' => function($object) use($isSiteAdmin, $thisUser) {
                            return $isSiteAdmin || ($thisUser == $object['SaveSearch']['user_id']);
                        },
                    ]
                ],
                [
                    'onclick' => sprintf('openMailTo(\'[onclick_params_data_path]\');'),
                    'onclick_params_data_path' => 'SaveSearch.value',
                    'icon' => 'share',
                    'title' => __('Share Query')
                ]
            ]
        ]
    ]
]
);
