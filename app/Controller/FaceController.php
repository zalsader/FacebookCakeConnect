<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('AppController', 'Controller');
App::uses('FB', 'Facebook.Lib');


/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class FaceController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array();
	public $helpers = array('Facebook.Facebook');
	public $components = array('Facebook.Connect', 'session');
	public $token;

/**
 * Displays a view
 *
 * @param mixed What page to display
 * @return void
 * @throws NotFoundException When the view file could not be found
 *	or MissingViewException in debug mode.
 */
	public function home() {
		$loginURL = FB::getLoginUrl(array('scope'=>'publish_actions, email, manage_pages, publish_pages, user_photos',
		 'redirect_uri'=>'http://localhost/FacebookCakeConnect/face/login'));
		$this->set(compact('loginURL'));
	}

	public function login()
	{
		$this->token = FB::getAccessToken();
		FB::setExtendedAccessToken();
		$this->token = FB::getAccessToken();
		$this->redirect('/face/home');
	}
	public function post() {
		$photo = $this->data['image'];
		$caption = $this->data['caption'];
		FB::setFileUploadSupport(true);
		FB::setAccessToken($this->token);
		$albums = FB::api('/me/albums', 'GET');
		$albumId = 'me';
		foreach ($albums['data'] as $album) {
			if ($album['type']=='wall') {
				$albumId = $album['id'];
			}
		}
		$resp = FB::api("/$albumId/photos", 'POST', array(
			'image' => '@'.realpath($photo['tmp_name']),
			'caption' => $caption,
		));
		$this->redirect('/face/home');
	}
	public function page() {
		$photo = $this->data['image'];
		$caption = $this->data['caption'];
		FB::setFileUploadSupport(true);
		FB::setAccessToken($this->token);
		$response = FB::api('/me/accounts', 'GET');
		if ($response['data']) {
			foreach ($response['data'] as $page) {
				FB::setAccessToken($page['access_token']); // changes the token to the one provided by the page.
				$albums = FB::api('/'.$page['id'].'/albums', 'GET');
				$albumId = $page['access_token'];
				foreach ($albums['data'] as $album) {
					if ($album['type']=='wall') {
						$albumId = $album['id'];
					}
				}
				$resp = FB::api("/$albumId/photos", 'POST', array(
					'image' => '@'.realpath($photo['tmp_name']),
					'caption' => $caption,
				));
			}
		}
		$this->redirect('/face/home');
	}
}
