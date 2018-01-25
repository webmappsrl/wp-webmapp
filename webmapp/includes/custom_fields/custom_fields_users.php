<?php // custom_fields_users.php

function getUserCustomFields() {
$user_fields = array (
    array (
      'key' => 'wm_user_newsletter',
      'label' => 'newsletter',
      'name' => 'newsletter',
      'type' => 'true_false',
      'instructions' => 'Check this value if you want to receive the newsletter.',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => array (
        'width' => '',
        'class' => '',
        'id' => '',
      ),
      'default_value' => 0,
      'placeholder' => '',
      'prepend' => '',
      'append' => '',
      'maxlength' => '',
    ),
    array (
        'key' => 'wm_user_country',
        'name' => 'country',
        'label' => 'Country',
        'type' => 'text',
        'instructions' => 'Insert the user country'
      )
  );

return $user_fields;
}

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array (
  'key' => 'group_wm_user',
  'title' => 'User WEBMAPP Profile',
  'fields' => getUserCustomFields(),
  'location' => array (
    array (
      array (
        'param' => 'user_form',
        'operator' => '==',
        'value' => 'edit',
      ),
    ),
  ),
  'menu_order' => 0,
  'position' => 'normal',
  'style' => 'default',
  'label_placement' => 'top',
  'instruction_placement' => 'label',
  'hide_on_screen' => '',
  'active' => 1,
  'description' => '',
));

endif;