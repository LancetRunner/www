<?php
/**
 * user relationship edit form
 */
function friends_edit_form ($form, & $form_state, $rtid = NULL ) {
	global $user;
	
	if( isset($rtid) ){
		if( $rtid == variable_get('friends_default_group_id', 1) )
		drupal_goto('friends');
		
		$rtype = user_relationships_type_load($rtid);	
		if( !empty($rtype) ){
			$relation_name = $rtype->name;
		} else {
			drupal_goto('friends');
		}
	}

	$form['#attached']['css'][] = drupal_get_path('module', 'friends').'/css/friends.page.css';

	$form['#rtid'] = $rtid;	

	$form['name'] = array (
		'#type' 	=> 'textfield',
		'#title' 	=> t('Group Name'),
		'#maxlength' => 20,
		'#size' =>20,
		'#default_value'=> isset($relation_name) ? $relation_name : '',
		'#description' 	=> t("Example:co-workers, classmates"),
		'#required' => TRUE,
	  '#element_validate' => array('_friends_name_validate'),	
	);

	// select all users from group requested
	$members = array();
	if( isset($rtid) ) {
		foreach(user_relationships_load(array('requester_id'=>$user->uid, 'approved'=>1, 'rtid'=>$rtid)) as $member){
			$members[] = $member->requestee_id;
		}
	}

	// select all friends
	$friends = array();
	foreach(user_relationships_load(array('requester_id'=>$user->uid, 'approved'=>1, 'rtid'=>variable_get('friends_default_group_id', 1))) as $friend){
		$account = user_load($friend->requestee_id);
		$friends[$friend->requestee_id] = theme('user_picture',  array('account'=>$account)) . theme('username', array('account' => $account));
	}

	$form['general'] = array (
		'#type' => 'fieldset',
		'#title' => t('Choose friends'),
	);
	
	$form['general']['friends'] = array (
		'#type' => 'checkboxes',
		'#options' => $friends,
		'#default_value' => $members,
	);

	$form['action'] = array (
		'#type' => 'submit',
		'#value' => isset($rtid) ? t('Edit') : t('Create'),
	);
 	
 	return $form;
}

function _friends_name_validate($element, &$form_state) {
	global $user;
  if( $element['#value'] != $element['#default_value'] ) {
  	 if( db_query('SELECT rtid FROM {user_relationship_types} WHERE owner_uid=:uid AND name=:name', array(':name'=>$element['#value'], ':uid'=>$user->uid))->fetchField()){
  			form_error($element, t('A group with name !name already exists.', array('!name', $element['#value']))); 		
  	 }
  }
}

function friends_edit_form_submit($form, &$form_state) {
	global $user;
	
	$rtid = $form['#rtid'];
	
	if( $form['general']['name']['#default_value'] != $form_state['values']['name'] ){
		$rtype = (object) array (
		   	'name' => $form_state['values']['name'],
		   	'plural_name' => $form_state['values']['name'],
		   	'is_oneway' => 1,
		   	'is_reciprocal' => 1,
		   	'requires_approval' => 1,
		   	'expires_val' => 0,
		   	'owner_uid' => $user->uid,
		);
		drupal_write_record('user_relationship_types', $rtype, isset($rtid)?'rtid':array());
		$rtid = $rtype->rtid;
	}

	// For those selected checkebox, get its id
	$selected = array_filter($form_state['values']['friends']);
	$original_friends = $form['general']['friends']['#default_value'];	
	$add_friends = array_diff($selected, $original_friends);
	$remove_friends = array_diff($original_friends, $selected);
	
	// Update the relationship into the user_relationship_friendlist table
	// Get all the friends IDs
	foreach( $add_friends as $uid ){
		$new_relationship = new stdClass;
		$new_relationship->requester_id = $user->uid;
		$new_relationship->requestee_id = $uid;
		$new_relationship->approved = 1;
		$new_relationship->rtid = $rtid;
		user_relationships_save_relationship($new_relationship);
	}

	foreach($remove_friends as $uid){
		$relationships = user_relationships_load(array('requester_id'=>$user->uid, 'requestee_id'=>$uid, 'rtid'=>$rtid, 'approved'=>1));
		if(!empty($relationships))
		user_relationships_delete_relationship(array_shift($relationships), $user->uid);
	}

	drupal_set_message(t('Group %name has been %op.', array (
		'%name' => $form_state['values']['name'], '%op' => ($form_state['values']['op']==t('Create'))?'created':'updated',
	)));

	$form_state['redirect'] = 'friends';
}

function friends_delete_form($form, &$form_state, $rtid = NULL) {
	if( isset($rtid) ) {
		$rtype = user_relationships_type_load($rtid);
		if( !empty($rtype) )
		$relation_name = $rtype->name;
	}
	
	if(!isset($relation_name))
	drupal_goto('friends');

	$form['#rtid'] = $rtid;
	
	return confirm_form(
		$form,
		t('Delete group'),
		'friends',
		t('Are you sure to delete group !name?', array('!name' => $relation_name)),
		t('Delete'), 
		t('Cancel')
	);
}

function friends_delete_form_submit($form, &$form_state){
	user_relationships_type_delete($form['#rtid']);
	$form_state['redirect'] = 'friends';
}

function friends_request_form($form, $form_state, $action){
	global $user;
	
	drupal_set_title(t('Pending Requests'), PASS_THROUGH);
	
	$form['#attached']['css'][] = drupal_get_path('module', 'friends').'/css/requests.page.css';	

	$form['options'] = array(
		'#type' => 'fieldset',
	);
	
	$rtid = variable_get('friends_default_group_id', 1);
	$rows = array();
	
	switch($action){
	case 'received':
		$requests = user_relationships_load(array('requestee_id' => $user->uid, 'approved' => 0, 'rtid' => $rtid));
		
		if( !empty($requests) ){
			$form['options']['approve']= array(
				'#type'	=> 'submit',
				'#value' => t('Approve'),
			);
			$form['options']['decline']= array(
				'#type'	=> 'submit',
				'#value' => t('Decline'),
			);
			
			foreach($requests as $request){
				$account = user_load($request->requester_id);
				$links = array();
				$links[] = _friends_action_link('approve', $request->rid);
				$links[] = _friends_action_link('decline', $request->rid);
				$rows[$request->rid] = array(
					friends_request_user_info($account),
					implode(' ', $links),
					'#attributes' => array(
						'id' => 'friend-request-' . $request->rid,
						'class' => array('friend-request'),
					),
				);	
			}
		}
		break;
	case 'sent':
		$requests = user_relationships_load(array('requester_id' => $user->uid, 'approved' => 0, 'rtid' => $rtid));
		if( !empty($requests) ){
			$form['options']['cancel']= array(
				'#type'	=> 'submit',
				'#value' => t('Cancel'),
			);
			
			foreach($requests as $request){
				// Sent requests, display cancel link.
		  	$account = user_load($request->requestee_id);
				$links = array();		  	
		  	$links[] = _friends_action_link('cancel', $request->rid);
				$rows[$request->rid] = array(
					friends_request_user_info($account), 
					implode(' ', $links),
					'#attributes' => array(
						'id' => 'friend-request-' . $request->rid,
						'class' => array('friend-request'),
					),
				);	
		  }
		}
		break;
	default:
		return;
	}

	$header = array( t('User'), t('Operations') );
	$form['options']['requests'] = array(
		'#type' => 'tableselect',
		'#header' => $header,
		'#options' => $rows,
		'#empty' => t('You have no pending requests.'),
	);
	
	
	if(!empty($rows)){
		$form['pager'] = array('#markup' => theme('pager'));
		$form['#attached']['library'][] = array('system', 'drupal.ajax');
	}
	
	return $form;
}

function friends_request_form_submit($form, &$form_state){
	global $user;

	foreach($form_state['values']['requests'] as $rid){
		$requests = user_relationships_load(array('rid' => $rid));

		if( empty($requests) ) {
			continue;
		} else {
			$request = array_shift($requests);
		}
		
		if( $request->approved )
			continue;
		
		switch( $form_state['values']['op'] ){
			case t('Approve'):
				user_relationships_save_relationship($request, 'approve');
				break;
			case t('Decline'):
			case t('Cancel'):
				user_relationships_delete_relationship($request, $user);
				break;
		}
	}
}

function friends_request_user_info( $account ){
	$info['username']= '<div class="username">'. theme('username', array('account' => $account)).'</div>';
	$info['email']= '<div class="email">' . $account->mail . '</div>';
	
	// let other modules to modify the info shown in friend request	
	$info += module_invoke_all('friends_user_info');
	
	// user detail info on the right part;
	$output[] = theme('user_picture', array('account' => $account));
	$output[] = '<div class="user-info">';	
	$output[] = implode("\n", $info);
	$output[] = '</div>';

	return implode("\n", $output);
}
