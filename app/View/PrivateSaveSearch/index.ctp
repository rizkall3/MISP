<?php

$this->set('menuData', ['menuList' => 'privatesavesearch', 'menuItem' => 'index']);
echo $this->element('genericElements/IndexTable/scaffold', [
        'scaffold_data' => [
            'data' => [
                'top_bar' => [
                    'children' => [
                        [
                          // Buttons for switching between public and private bookmarks
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
                                ]
                            ]
                        ],
                        [
                          // Pulls up save query page
                            'children' => [
                                [
                                    //'onClick' => 'privateSaveSearchQuery()',
                                    'title' => __('Add Query'),
                                    'id' => 'private_save_query',
                                    'text' => __('Add Search Query'),
                                    'pull' => 'right'
                                ]
                            ]
                        ],
                        [
                            'type' => 'search',
                            'button' => __('Filter'),
                            'placeholder' => __('Enter value to search'),
                            'searchKey' => 'value',
                        ]
                    ]
                ],
                // sqldb to pull from
                'data' => $privateSavedSearches,
                // Names for columns
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
                        'element' => 'links'
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
                // Actions for column buttons
                'actions' => [
                    [
                        'onclick' => sprintf('openGenericModal(\'%s/PrivateSaveSearch/edit/[onclick_params_data_path]\');', $baseurl),
                        'onclick_params_data_path' => 'PrivateSaveSearch.id',
                        'icon' => 'edit',
                        'title' => __('Edit Query'),
                        // Ensures only individual user access their own saved queries
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
                        // Ensures only individual user can delete their own saved queries
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
    ]
);
?>
<script type="text/javascript">
    // Method for save search query button
    $(function() {
        $('#private_save_query').click(function() {
            privateSaveSearchQuery();
        });
    });
</script>
