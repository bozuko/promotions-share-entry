<?php
if(function_exists("register_field_group"))
{
	register_field_group(array (
    'id' => 'acf_share-entry',
    'title' => 'Share Entry',
    'fields' => array (
      array (
        'key' => 'field_536d2ceaf17a3',
        'label' => 'Share Entry Limit',
        'name' => 'share_entry_limit',
        'type' => 'number',
        'default_value' => 5,
        'placeholder' => '',
        'prepend' => '',
        'append' => '',
        'min' => '',
        'max' => '',
        'step' => '',
      ),
    ),
    'location' => array (
      array (
        array (
          'param' => 'promotion_tab',
          'operator' => '==',
          'value' => 'share',
          'order_no' => 0,
          'group_no' => 0,
        ),
      ),
    ),
    'options' => array (
      'position' => 'normal',
      'layout' => 'default',
      'hide_on_screen' => array (
      ),
    ),
    'menu_order' => 0,
  ));
}
    