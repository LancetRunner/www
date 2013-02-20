<?php

function annotation_book_settings_form($form, &$form_state){
	$form['video'] = array(
		'#type' => 'fieldset',
		'#title' => t('Share Video Settings'),
	);
	
	$form['video']['annotation_book_video_width'] = array(
		'#type' => 'textfield',
		'#size' => 10,
		'#title' => t('The width of video frame (shared with annotation)'),
		'#default_value' => variable_get('annotation_book_video_width', 400),
		'#element_validate' => array('element_validate_integer_positive'),		
	);
	
	$form['video']['annotation_book_video_height'] = array(
		'#type' => 'textfield',
		'#size' => 10,
		'#title' => t('The height of video frame (shared with annotation)'),
		'#default_value' => variable_get('annotation_book_video_height', 300),
		'#element_validate' => array('element_validate_integer_positive'),		
	);	
	
	return system_settings_form($form);
}