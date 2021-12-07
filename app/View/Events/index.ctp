<?php $this->Html->addCrumb('Events', '/events/index'); ?>
<div class="events <?php if (!$ajax) echo 'index'; ?>">
    <h2><?php echo __('Events');?></h2>
    <div class="pagination">
        <ul>
        <?php
            $pagination = $this->Paginator->prev('&laquo; ' . __('previous'), array('tag' => 'li', 'escape' => false), null, array('tag' => 'li', 'class' => 'prev disabled', 'escape' => false, 'disabledTag' => 'span'));
            $pagination .= $this->Paginator->numbers(array('modulus' => 20, 'separator' => '', 'tag' => 'li', 'currentClass' => 'active', 'currentTag' => 'span'));
            $pagination .= $this->Paginator->next(__('next') . ' &raquo;', array('tag' => 'li', 'escape' => false), null, array('tag' => 'li', 'class' => 'next disabled', 'escape' => false, 'disabledTag' => 'span'));
            echo $pagination;
        ?>
        </ul>
    </div>
    <?php
        $filterParamsString = array();
        foreach ($passedArgsArray as $k => $v) {
            $filterParamsString[] = sprintf(
                '%s: %s',
                h(ucfirst($k)),
                h($v)
            );
        }

        $columnsDescription = [
            'owner_org' => __('Owner org'),
            'attribute_count' => __('Attribute count'),
            'creator_user' => __('Creator user'),
            'tags' => __('Tags'),
            'clusters' => __('Clusters'),
            'correlations' => __('Correlations'),
            'sightings' => __('Sightings'),
            'proposals' => __('Proposals'),
            'discussion' => __('Posts'),
            'report_count' => __('Report count')
        ];

        $columnsMenu = [];
        foreach ($possibleColumns as $possibleColumn) {
            $html = in_array($possibleColumn, $columns, true) ? '<i class="fa fa-check"></i> ' : '<i class="fa fa-check" style="visibility: hidden"></i> ';
            $html .= $columnsDescription[$possibleColumn];
            $columnsMenu[] = [
                'html' => $html,
                'onClick' => 'eventIndexColumnsToggle',
                'onClickParams' => [$possibleColumn],
            ];
        }

        $filterParamsString = implode(' & ', $filterParamsString);

        $data = array(
            'children' => array(
                array(
                    'children' => array(
                        array(
                            'id' => 'create-button',
                            'title' => __('Modify filters'),
                            'fa-icon' => 'search',
                            'onClick' => 'getPopup',
                            'onClickParams' => array($urlparams, 'events', 'filterEventIndex')
                        )
                    )
                ),
                array(
                    'children' => array(
                        array(
                            'id' => 'multi-delete-button',
                            'title' => __('Delete selected events'),
                            'fa-icon' => 'trash',
                            'class' => 'hidden mass-select',
                            'onClick' => 'multiSelectDeleteEvents'
                        )
                    )
                ),



                array(
                    'children' => array(
                        array(
                            'title' => __('My events only'),
                            'text' => __('Favorite Events'),
                            'data' => array(
                                'searchall' => "favorite"
                            ),
                            'class' => 'searchFilterButton',
                            'active' => isset($passedArgsArray['email']) && $passedArgsArray['email'] === $me['email']
                        ),
                    )
                ),
                array(
                    'children' => array(
                        array(
                            'requirement' => count($passedArgsArray) > 0,
                            'html' => sprintf(
                                '<span class="bold">%s</span>: %s',
                                __('Filters'),
                                $filterParamsString
                            )
                        ),
                        array(
                            'requirement' => count($passedArgsArray) > 0,
                            'url' => $baseurl . '/events/index',
                            'title' => __('Remove filters'),
                            'fa-icon' => 'times'
                        )
                    )
                ),
                array(
                    'children' => array(
                        array(
                            'title' => __('My events only'),
                            'text' => __('My Events'),
                            'data' => array(
                                'searchemail' => h($me['email'])
                            ),
                            'class' => 'searchFilterButton',
                            'active' => isset($passedArgsArray['email']) && $passedArgsArray['email'] === $me['email']
                        ),
                        array(
                            'title' => __('My organisation\'s events only'),
                            'text' => __('Org Events'),
                            'data' => array(
                                'searchorg' => h($me['org_id'])
                            ),
                            'class' => 'searchFilterButton',
                            'active' => isset($passedArgsArray['org']) && $passedArgsArray['org'] === $me['org_id']
                        )
                    )
                ),
                array(
                    'children' => array(
                        array(
                            'id' => 'simple_filter',
                            'type' => 'group',
                            'class' => 'last',
                            'title' => __('Choose columns to show'),
                            'fa-icon' => 'columns',
                            'style'=>'color:#20b160',
                            'children' => $columnsMenu,
                        ),
                    ),
                ),
                array(
                    'children' => array(
                        array(
                          // Shares query through openMailTo()
                            'title' => __('Share'),
                            'id' => 'share_query',
                            'text' => __('Share'),

                        ),
                        array(
                          // Saves query to public bookmarks
                            'title' => __('Public Save'),
                            'id' => 'save_query',
                            'text' => __('Public Save'),
                        ),
                        array(
                          // Saves query to private bookmarks
                            'title' => __('Private Save'),
                            'id' => 'private_save_query',
                            'text' => __('Private Save'),
                        )

                    )
                ),
                array(
                    'type' => 'search',
                    'button' => __('Filter'),
                    'placeholder' => __('Enter value to search'),
                    'data' => '',
                )
            )
        );
    if (!$ajax) {
            echo $this->element('/genericElements/ListTopBar/scaffold', array('data' => $data));
        }
        echo $this->element('Events/eventIndexTable');
    ?>
    <p>
    <?php
    echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
    ));
    ?>
    </p>
    <div class="pagination">
        <ul>
        <?= $pagination ?>
        </ul>
    </div>
</div>
<script type="text/javascript">
    var passedArgsArray = <?php echo $passedArgs; ?>;
    $(function() {
        $('.searchFilterButton').click(function() {
            runIndexFilter(this);
        });
        $('#quickFilterButton').click(function() {
            runIndexQuickFilter(passedArgsArray);
        });
        // Share search query through openMailTo()
        $('#share_query').click(function() {
            openMailTo();
        });
        // Saves query to public bookmarks
        $('#save_query').click(function() {
            saveSearchQuery();
        });
        // Saves query to private bookmarks
        $('#private_save_query').click(function() {
            privateSaveSearchQuery();
        });
    });
</script>
<?php
    echo $this->Html->script('vis');
    echo $this->Html->css('vis');
    echo $this->Html->css('distribution-graph');
    echo $this->Html->script('network-distribution-graph');
?>
<?php
    if (!$ajax) echo $this->element('/genericElements/SideMenu/side_menu', array('menuList' => 'event-collection', 'menuItem' => 'index'));
