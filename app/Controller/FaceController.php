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
	public function login() {
		$path = func_get_args();

		$count = count($path);
		if (!$count) {
			return $this->redirect('/');
		}
		$page = $subpage = $title_for_layout = null;

		if (!empty($path[0])) {
			$page = $path[0];
		}
		if (!empty($path[1])) {
			$subpage = $path[1];
		}
		if (!empty($path[$count - 1])) {
			$title_for_layout = Inflector::humanize($path[$count - 1]);
		}
		$this->token = FB::getAccessToken();
		$loginURL = FB::getLoginUrl(array('scope'=>'publish_actions, email, manage_pages, publish_pages'));
		$this->set(compact('page', 'subpage', 'title_for_layout', 'loginURL'));
		try {
			$this->render(implode('/', $path));
		} catch (MissingViewException $e) {
			if (Configure::read('debug')) {
				throw $e;
			}
			throw new NotFoundException();
		}
	}
	public function post() {
		FB::setAccessToken($this->token);
		$resp = FB::api('/me/feed', 'POST', array(
		'link' => 'www.example.com',
		'message' => 'User provided message'
		));
		$this->redirect('/');
	}
	public function page() {
		FB::setAccessToken($this->token);
		$response = FB::api('/me/accounts', 'GET');
		if ($response['data']) {
			foreach ($response['data'] as $page) {
				FB::setAccessToken($page['access_token']);
				FB::api('/'.$page['id'].'/feed', 'POST', array(
					'link' => 'www.example.com',
					'message' => 'User provided message'
				));
			}
		}
		$this->redirect('/');
	}
}
