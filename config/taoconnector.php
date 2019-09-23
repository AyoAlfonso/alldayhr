<?php
/*
* @author: Ayo Alfonso
*
*/
	return [

	   /*
		* URL Prefix is the I.P address of our remote server set by env varibles
		*/
		'urlprefix' => env('TAO_URL_PREFIX','http://tao.alldayhr.com'),

		/*
		* URL where we connect to our tao v2.1 api layaer
		*/
		'TTuri' => env('TTuri', 'http_2_tao_0_alldayhr_0_com_1_alldayhrtao_0_rdf_3_'),
		'taoPass' => env('taoPass', 'password123'),
		'taoLogin' => env('taoLogin', 'admin'),
		'toaSubdomain' => env('toaSubdomain', 'alldayhrtao'),
	];
