<?php
namespace App\Security;

class Session {
	
	static $instance = null;
	
	public function __construct()
	{
		session_start();
		$_SESSION['flash'] = [];
	}
	
	public static function getInstance()
    {
        if (!isset(static::$instance)) {
            static::$instance = new static;
        }
        return static::$instance;
    }
	
	public static function addErrorMessage($message)
	{
		self::addFlashMessage('error', $message);
	}
	
	public static function addSuccessMessage($message)
	{
		self::addFlashMessage('success', $message);
	}
	
	public static function addFlashMessage($name, $message)
	{
		$_SESSION['flash'][$name][] = $message;
	}
	
	public static function getFlashMessages()
	{
		return $_SESSION['flash'];
	}
	
	
}

