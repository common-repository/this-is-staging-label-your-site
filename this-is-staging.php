<?
/**
* Plugin Name: This is Staging (Label your WordPress site)
* Description: This plugin gives you to add label to your website so you don't get confused if you are on staging or on the production side.
* Version: 		0.3
* Author: 		Alexander Vasilev
* Author URI:   http://profiles.wordpress.org/alordiel
* License:		GPL-2.0+
* License URI:	http://www.gnu.org/licenses/gpl-2.0.txt
* Text Domain:  tis
*/

/*  Copyright 2016 Alexander Vasilev  (email : alexander.vasilev@protonmail.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
	
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit; 

/**
 * Register style sheet and scripts for the admin.
 */
add_action( 'admin_enqueue_scripts', 'tis_admin_styles_script' );
function tis_admin_styles_script() {
 	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'tis-script', plugins_url( '/assets/custom.js' , __FILE__), array('jquery','wp-color-picker'), '1.0.0', true );
	wp_enqueue_style( 'tis-style', plugins_url( '/assets/style.css', __FILE__ ) );
	
	$translation_array = array( 
		'success' 		=> __('Well done. Settings are updated!','tis'),
		'wrong_user_id' => __('Oh, Noooo! You are inserting something that should not be in the user\'s id list. Numbers and commas only, please!','tis'),
		'no_ids'		=> __('Hm, you didn\'t include any ids. Try to fix this. If it is too hard - choose another option.','tis')
	);
	wp_localize_script( 'tis-script', 'trans_str', $translation_array );
	wp_localize_script( 'tis-script', 'tis_ajax', array( 'ajax_url' => admin_url( 'admin-ajax.php' )));

}

/**
 * Register textdomain.
 */
add_action('plugins_loaded', 'tis_add_textdomain_and_options');
function tis_add_textdomain_and_options() {
	
	load_plugin_textdomain( 'tis', false, basename( dirname( __FILE__ ) ) . '/languages' );

	$options = [
		'tis-admin-enable'	=>'1',
		'tis-front-enable'	=>'1',
		'tis-text-color'	=>'#fff',
		'tis-box-color'		=>'#ff0000',
		'tis-text'			=>'This is Staging!',
		'tis-position'		=>'bot-left',
		'tis-spartan'		=>'1',
		'tis-visibility'	=>'1',
		'tis-userslist'		=>''
	];
	add_option('tis-options', $options);
}


/**
 * Register submenu in Settings.
 */
add_action('admin_menu','tis_register_submenu');
function tis_register_submenu(){
	
	add_submenu_page( 'options-general.php', 'Label your site', 'This is staging', 'manage_options', 'tis', 'tis_page_admin' );

}

add_action( 'admin_bar_menu', 'tis_toolbar_link_to_admin_bar', 999 );
function tis_toolbar_link_to_admin_bar( $wp_admin_bar ) {
	
	$data = get_option('tis-options');
	$should_see = false;
	$is_admin_bar_enabled = $data['tis-admin-enable'];
	$user_regulation = $data['tis-visibility'];
	switch ($user_regulation) {
		case '1':
			if (get_current_user_id() == $data['tis-userslist']) {
				$should_see = true;
			}
			break;
		case '2':
			if (current_user_can('manage_options')) {
				$should_see = true;
			}
			break;
		case '3':
			$ids = explode(',', $data['tis-userslist']);
			if (in_array(get_current_user_id(), $ids)) {
				$should_see = true;
			}
			break;

	}

	if ( $is_admin_bar_enabled==1 && $should_see ) {
		
		$title = $data['tis-text'];
		$args  = array(
			'id'    => 'my_page',
			'title' => $title,
			'href'  => admin_url("/options-general.php")."?page=tis",
			'meta'  => array( 'class' => 'tis-paint-me-red' )
		);
		$wp_admin_bar->add_node( $args );

	}
	
}

/**
 * Building the admin part.
 */ 
function tis_page_admin(){

	$out = include('admin-page.php');

	return $out;
}

/*Ajax  - update settings*/
add_action("wp_ajax_tis_update_options", "tis_update_options");
function tis_update_options() {

	$data = $_POST['data'];
	$err  = '';
	$text = 'This is staging!';

	//sanitize the text input
	if($data['label_name']){
		$text = sanitize_text_field($data['label_name']);
	}


	//check if data different of 0 or 1
	if (!in_array($data['admin_enable'], array('1','0')) || !in_array($data['front_enable'], array('1','0')) || !in_array($data['the_spartan'], array('1','0')) || !in_array($data['visibility'], array('1','2','3'))) {
		$err .= __('Errrorrrr! I expected 1 or 0. What are you giving me?','tis');
	}

	//check if correct positions
	if (!in_array($data['position'] , array('top-left','top-right','bot-left','bot-right'))) {
		$err .= __('Ops, It seems that the position is wrong. What have you done?','tis');
	}

	//hex validation
	if(!preg_match ('/#([a-f0-9]{3}){1,2}\b/i', $data['box_color'] ) || !preg_match ('/#([a-f0-9]{3}){1,2}\b/i', $data['text_color'])){
		$err .= __('Hombre, the hex color code is wrong. Keep it in the standard format.','tis');
	}
	if(!preg_match ('/[0-9,]/i', $data['userslist'] ) && $data['visibility'] ==3 ){
		$err .= __('Dear user, in the list of users I expect only numbers and comma, nothing else. Thank you.','tis');
	}

	if ($data['visibility'] ==1) {
		$data['userslist'] = get_current_user_id();
	}

	if ($data['visibility'] ==3 && $data['userslist'] =='') {
		$err .= __('Hm, you didn\'t include any ids. Try to fix this. If it is too hard - choose another option.','tis');
	}

	//check if errors
	if ($err == '') {

		$options = [
			'tis-admin-enable'	=> $data['admin_enable'],
			'tis-front-enable'	=> $data['front_enable'],
			'tis-text-color'	=> $data['text_color'],
			'tis-box-color'		=> $data['box_color'],
			'tis-text'			=> $text,
			'tis-position'		=> $data['position'],
			'tis-spartan'		=> $data['the_spartan'],
			'tis-visibility'	=> $data['visibility'],
			'tis-userslist'		=> $data['userslist'],
		];
		update_option('tis-options', $options);

		echo 1;

	} else {

		echo $err;

	}

	die();
}

add_action( 'get_footer', 'this_front_end_label_handler' );
function this_front_end_label_handler() {
	$data = get_option('tis-options');
	$is_frontend_enabled = $data['tis-front-enable'];

	$should_see = false;
	$is_admin_bar_enabled = $data['tis-admin-enable'];
	$user_regulation = $data['tis-visibility'];
	switch ($user_regulation) {
		case '1':
			if (get_current_user_id() == $data['tis-userslist']) {
				$should_see = true;
			}
			break;
		case '2':
			if (current_user_can('manage_options')) {
				$should_see = true;
			}
			break;
		case '3':
			$ids = explode(',', $data['tis-userslist']);
			if (in_array(get_current_user_id(), $ids)) {
				$should_see = true;
			}
			break;
	}

	if ($is_frontend_enabled == 1 && $should_see == true) {
		$class = "this-is-sparta";
		$box_color =  $data['tis-box-color'];
		$text_color = $data['tis-text-color'];
		$position = "top:5%; left: 5%;";
		switch ($data['tis-position']) {
			case 'top-right':
				$position = "top:5%; right: 5%;";
				break;
			case 'bot-left':
				$position = "bottom:5%; left: 5%;";
				break;
			case 'bot-right':
				$position = "bottom:5%; right: 5%;";
				break;
		}
		$style = "style=\"position:fixed; $position  background-color: $box_color; color: $text_color; font-weight:bold; z-index:9999999;padding:10px\"";

		$img = '';
		if ($data['tis-spartan'] == 1) {
			$img = '<img style="position: absolute;top: -12px;left: -24px;" src= "'.plugins_url('assets/spartan.png', __FILE__).'"/>';
		}
		
	    echo "<script>";
	    echo "jQuery(document).ready(function(){jQuery('body').append('<div ". $style ." class=\"sad\">".$img ." " . $data['tis-text']."</div>')})";
	    echo "</script>";

    }

	if ( $data['tis-admin-enable']==1) {
		echo "<style>#wpadminbar .tis-paint-me-red {background-color: red;} </style>";
	}
}