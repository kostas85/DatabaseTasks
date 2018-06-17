<?php
namespace App\Controllers;

use App\Security\Session;
use App\Entities\User;

class Search extends Base {
	public function render () {
		$users = [];
		$queryString = '';
		if (empty($_SESSION['login'])){
			return [
				'view' => 'login.html',
				'data' => [
					'title' => 'Login',
					'message' => 'You should login'
				]
			];
		}
		if ($_SERVER['REQUEST_METHOD'] === 'POST'
)
		{
			if (!empty($_POST['q'])){
				$users = $this->doSearch($_POST['q']);
				$queryString = 'Query String: ' . $_POST['q'];
			}
		}
		return [
		'view' => 'search.html',
		'data' => [
			'title' => 'Search',
			'users' => $users,
		    'query_string' => $queryString
		]
	];
	}
	
	private function doSearch($query)
	{
		$users = User::searchUsers($query);
		return $users;
	}
}
