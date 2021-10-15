<?php
  echo '<div class="index">';
  echo '<h2>';
  echo __('Saved Searches');
  echo '</h2>';
  echo $this->element('/genericElements/IndexTable/index_table', array(
    'data' => array(
      'data' => $data,
      'fields' => array(
        array(
          'name' => __('Id'),
          'sort' => 'id',
          'class' => 'short',
          'data_path' => 'SaveSearches.id'
        ),
        array(
          'name' => __('User'),
          'sort' => 'User.email',
          'class' => 'short',
          'data_path' => 'User.email'
        )
      )
      'description' => __('Manage the bookmarked searches.'),
      'actions' => array(
        array(
          'url' => '/SaveSearches/setSearch',
          'url_params_data_paths' => array(
              'SaveSearches.user_id',
              'SaveSearches.link'
          ),
          'icon' => 'edit'
        ),
        array(
          'url' => '/SaveSearches/delete',
          'url_params_data_paths' => array(
              'SaveSearches.id'
          ),
          'icon' => 'trash',
          'postLink' => true,
          'postLinkConfirm' => __('Are you sure you wish to delete this entry?')
        )
      )
    )
  ));

  echo '</div>';
 ?>
