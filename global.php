<?php
	chdir(dirname(__FILE__));
	error_reporting(E_ALL);
	
	if (!isset($_SESSION))
	{
	    session_start();
	}
	
?>