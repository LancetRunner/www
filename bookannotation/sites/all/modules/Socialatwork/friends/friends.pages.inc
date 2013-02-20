<?php 
function friends_friends_page($rtid = NULL){
	global $user;
	
	if( isset($rtid) ) {
		$rtype = user_relationships_type_load($rtid);
		if(empty($rtype))
		drupal_goto('friends');
	}
	
	if( !isset($rtid) )
	$rtid = variable_get('friends_default_group_id', 1);
	
	drupal_add_library('system', 'drupal.ajax');	
	drupal_add_css(drupal_get_path('module', 'friends').'/css/friends.page.css');
	
	$relations = friends_get_groups($user->uid);
	$relation_links = array();
	$members_in_group = array();
	foreach($relations as $relation){
		$count = user_relationships_load(array('requester_id'=>$user->uid, 'approved'=>1, 'rtid'=>$relation->rtid), array('count'=>true));

		$relation_links[$relation->rtid]['data'] = l(t($relation->name).'&nbsp;<em>('.$count.')</em>', "friends/group/{$relation->rtid}", array('html'=>TRUE));

		if($relation->rtid == $rtid) {
			$relation_links[$relation->rtid]['class'] = array('active');
			$relation_name = $relation->name;
			$members_in_group = user_relationships_load(array('requester_id'=>$user->uid, 'approved'=>1, 'rtid'=>$rtid), array('paging'=>variable_get('user_relationships_relationships_per_page', 16)));
		}
	}

	$action_links = _friends_group_action_links($rtid);

	$member_links = array();

	foreach($members_in_group as $member){
		$account = user_load($member->requestee_id);

		$relationships = user_relationships_load(array('requester_id'=>$user->uid, 'requestee_id'=>$account->uid));
		
		$relation_names = array();
		foreach($relationships as $relationship)
		$relation_names[] = $relationship->name;

		$member_links[] = '<div class="friend" id="friend-request-' . $member->rid . '">' .
			theme('user_picture', array('account' => $account)) .
			'<div class="right">' .
			'<div class="username">' . theme('username', array('account'=>$account)) . '</div>' .
			'<div class="actions">' . _friends_action_link('remove', $member->rid) . '</div>' . 
			'<div class="groups">' . implode(', ', $relation_names) . '</div>' .
			'</div>'. 
			'</div>';
	}
	
	$output[] = '<div class="groups-wrapper">' . theme('item_list', array('items'=>$relation_links)) . '</div>';
	$output[] = '<div class="group">';
	$output[] = '	<div class="toolbar clearfix">';
	$output[] = '		<div class="actions">' . theme('item_list', array('items'=>$action_links)) . '</div>';
	$output[] = '		<div class="pager">' . theme('pager') . '</div>';
	$output[] = '	</div>';
	$output[] = '	<div class="friends-wrapper clearfix">' . implode("\n", $member_links) . '</div>';
	$output[] = '</div>';
	  
	return implode("\n", $output);
}

function friends_action_callback($action, $rid = NULL){
	$request = user_relationships_load($rid);
	if( $request && $request->approved == 0 ) {
		switch($action){
			case 'approve':
				return drupal_get_form('friends_approve_form', $request);
			case 'decline':	
				return drupal_get_form('friends_decline_form', $request);
			case 'add':
			case 'cancel':
			case 'remove':
		}
	}	
//	drupal_goto('friends/requests');
	return MENU_NOT_FOUND;	
}

function friends_approve_form($form, &$form_state, $request ){
	$form = array();
	
	$form['#request'] = $request;	
	$requester = user_load($request->requester_id);
		
	return confirm_form(
		$form,
		t('Are you sure to accept the friend request from !name?', array('!name' => $requester->name)),
		'friends/requests',
		''
	);
}

function friends_approve_form_submit($form, &$form_state){
	user_relationships_save_relationship($form['#request'], 'approve');
	$form_state['redirect'] = 'friends/requests';
}

function friends_decline_form($form, &$form_state, $request){
	$form = array();
	
	$form['#request'] = $request;	
	$requester = user_load($request->requester_id);
	
	return confirm_form(
		$form,
		t('Do you want to decline the friend request from !name?', array('!name' => $requester->name)),		
		'friends/requests',
		''
	);
}

function friends_decline_form_submit($form, &$form_state){
	global $user;
	user_relationships_delete_relationship($form['#request'], $user);
	$form_state['redirect'] = 'friends/requests';
}
 
function friends_action_ajax_callback($action, $rid = NULL ){
	global $user;

	$commands = array();
	switch($action){
		case 'add':
			if( $requestee = user_load($rid) ) {
				$rtid = variable_get('friends_default_group_id', 1);
				user_relationships_request_relationship($user, $requestee, $rtid);
				$commands[] = ajax_command_replace('#friend-request-'.$requestee->uid, '<span>' . t('Request sent, waiting for approval.') . '</span>');
			}
			break;
		case 'approve':
			$relationships = user_relationships_load(array('rid' =>$rid));
			if( !empty($relationships) )
			user_relationships_save_relationship(array_shift($relationships), 'approve');
			$commands[] = ajax_command_invoke('#friend-request-'.$rid, 'slideUp', array());
			break;
		case 'decline':
		case 'cancel':
		case 'remove':
			$relationships = user_relationships_load(array('rid' => $rid));
			if( !empty($relationships) )
			user_relationships_delete_relationship(array_shift($relationships), $user);
			$commands[] = ajax_command_invoke('#friend-request-'.$rid, 'slideUp', array());
			break;
	}
	
	return array(
		'#type' => 'ajax',
		'#commands' => $commands,
	);
}
