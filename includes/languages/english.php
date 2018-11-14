<?php

function lang($phrase){
	//static because it dose not change
	static $lang = array(
				
				//Dashboard page
						'HOME_ADMIN' 	=> 'Home',
						'CATEGORIES'	=> 'Categories',
						'ITEMS' 		=> 'items',
						'MEMBERS' 		=> 'Members',
						'STATISTICS' 	=> 'Statistics',
						'LOGS' 			=> 'Logs',
						'EDIT' 			=> 'Edit Profile',
						'SETTING' 		=> 'Settings',
						'LOGOUT' 		=> 'Logout',
						'COMMENTS' 		=> 'Comments',
						'' 		=> '',




		);
	return $lang[$phrase];
}