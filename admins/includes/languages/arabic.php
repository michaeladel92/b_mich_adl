<?php

function lang($phrase){
	//static because it dose not change
	static $lang = array(
			'MESSAGE' => 'مرحبا',
			'Admin'   => 'أدمن'
		);
	return $lang[$phrase];
}