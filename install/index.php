<?php
/**
 * TestLink Open Source Project - http://testlink.sourceforge.net/
 * This script is distributed under the GNU General Public License 2 or later.
 *
 * Navigation for installation scripts
 *
 * @package     TestLink
 * @copyright   2007,2014 TestLink community
 * @filesource  index.php
 *
 * @internal revisions
 */
error_reporting(E_ALL);

session_start();

$const_inc_dir = dirname(__FILE__) ."./../const.inc.php";
require_once($const_inc_dir);

$_SESSION['app_name'] = APP_NAME;
$_SESSION['app_version'] = APP_VERSION;

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <title><?php echo $_SESSION['app_name'] .' ' .$_SESSION['app_version'] ?> Installation procedure</title>
  <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
  <link href="./img/favicon.ico" rel="icon" type="image/gif"/>
  <style type="text/css">@import url('./css/style.css');</style>
</head>

<body>
<div class="tlPager">
	<h1><img src="./img/dot.gif" alt="Dot" style="margin: 0px 10px;" />
	    <?php echo $_SESSION['app_name'] .' ' .$_SESSION['app_version'] ?> Installation</h1>
	<div class="tlLiner">&nbsp;</div>
	<div class="tlStory">
	    <p>You are installing <?php echo $_SESSION['app_name'] .' ' .$_SESSION['app_version'] ?> </p>
	    
	    <p>Open <a target="_blank" href="../docs/testlink_installation_manual.pdf">Installation manual</a>
	    for more information or troubleshooting. You could also look at
	    <a href="../README.txt">README</a> or <a href="../CHANGELOG.txt">Changes Log</a>.
	    You are welcome to visit our <a target="_blank" href="http://forum.testlink.org">
	    forum</a> to browse or discuss.
	    </p>
	
	    <p><ul><li><a href="installIntro.php?type=new">New installation</a></li></ul></p>
	
	    <br>
	    <i>
	    <?php echo $_SESSION['app_name'] ?> is a complicated piece of software, and has always been released under 
	    an Open Source license, and this will continue into the far future. 
	    <br>It has cost thousands of hours to develop, test and support <?php echo $_SESSION['app_name'] ?>. 
	    <br>If you find <?php echo $_SESSION['app_name'] ?> valuable, we would appreciate if you would consider 
	    buying a support agreement or requesting custom development.    
	    </i>
	</div>
	<div class="tlLiner">&nbsp;</div>
</div>
</body>
</html>