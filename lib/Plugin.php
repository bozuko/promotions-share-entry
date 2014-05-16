<?php

/**
 * Share entry functionality
 */

class PromotionsShareEntry_Plugin extends Promotions_Plugin_Base
{
  
  protected $tab_key  = 'share';
  protected $tab_text = 'Share';
  
  public function init()
  {
    add_rewrite_endpoint('share', EP_PERMALINK | EP_ROOT );
    
    $this->register_field_groups(
      'share-entry'
    );
  }
  
  /**
   * @wp.filter       promotions/features
   */
  public function add_feature( $features )
  {
    $features['share_entry'] = 'Share Entries';
    return $features;
  }
  
  /**
   * @wp.filter     promotions/tabs/promotion/display
   * @wp.priority   10
   */
  public function display_tabs( $tabs, $post )
  {
    if( Snap::inst('Promotions_Functions')->is_enabled('share_entry', $post->ID) )
      return $tabs;
    unset( $tabs[$this->tab_key] );
    return $tabs;
  }
  
  /**
   * @wp.filter       promotions/tabs/promotion/register
   * @wp.priority     40
   */
  public function register_tab( $tabs )
  {
    $tabs[$this->tab_key] = $this->tab_text;
    return $tabs;
  }
  
  /**
   * @wp.filter         promotions/registration_form/content
   */
  public function add_hidden_field( $content )
  {
    if( !Snap::inst('Promotions_Functions')->is_enabled('share_entry', $post->ID) ){
      return $content;
    }
    $registration_id = get_query_var('share');
    if( !$registration_id ) return $content;
    $content .= Snap_Util_Html::tag('input', array(
      'type'        => 'hidden',
      'name'        => '_share',
      'value'       => $registration_id
    ));
    return $content;
  }
  
  /**
   * @wp.filter         promotions/api/result?method=register
   */
  public function on_register( $result, $params )
  {
    if( !Snap::inst('Promotions_Functions')->is_enabled('share_entry', $post->ID) ){
      return $result;
    }
    if( is_array($result) && isset($result['success']) && $result['success'] ){
      if( isset( $params['_share'] ) ){
        $id = $params['_share'];
        $registration = get_post( $id );
        if( $registration && $registration->post_type == 'registration' ){
          
          // check the limits...
          $limit = get_field( 'share_entry_limit' );
          
          global $wpdb;
          
          $sql = "
            SELECT COUNT(*) FROM `{$wpdb->posts}` `p`
            WHERE `p`.`post_type` = 'entry'
              AND `p`.`post_title` = 'share'
              AND `p`.`post_parent` = %d
          ";
          
          $stmt = $wpdb->prepare( $sql, $registration->ID );
          if( $wpdb->get_var($stmt) < $limit ){
            // we can add a new one.
            $entry_id = wp_insert_post(array(
              'post_type'     => 'entry',
              'post_parent'   => $registration->ID,
              'post_title'    => 'share',
              'post_status'   => 'publish',
              'post_name'     => 'share-from-'.$registration->ID
            ));
            update_post_meta( $entry_id, 'shared_to', $result['registration_id'] );
            update_post_meta( $entry_id, 'source', @$params['_share_source'] );
          }
        }
      }
    }
    return $this->_add_share_url( $result );
  }
  
  /**
   * @wp.filter         promotions/api/result?method=enter
   */
  public function on_enter( $result )
  {
    if( !Snap::inst('Promotions_Functions')->is_enabled('share_entry', $post->ID) ){
      return $result;
    }
    return $this->_add_share_url( $result );
  }
  
  protected function _add_share_url( $result )
  {
    if( is_array($result) && isset( $result['success'] ) && $result['success'] ){
      $parent = get_post( $result['entry_id'] )->post_parent;
      $result['share_url'] = preg_replace('#/$#','',get_permalink()).'/share/'.$parent;
    }
    return $result;
  }
}
