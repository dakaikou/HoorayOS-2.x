<?php
/**
 * TestLink Open Source Project - http://testlink.sourceforge.net/ 
 * This script is distributed under the GNU General Public License 2 or later. 
 *
 * Check configuration and system 
 * Using: Installer, sysinfo.php and Login
 * 
 * @filesource  configCheck.php
 * @package     TestLink
 * @author      Martin Havlat
 * @copyright   2007-2014, TestLink community 
 * @link        http://www.testlink.org/
 * @see         sysinfo.php
 *
 * @internal revisions
 * @since 1.9.9
 **/

/**
 * get home URL
 * 
 * @author adapted from Mantis Bugtracking system
 * @return string URL 
 *
 * @internal revision
 * @since 1.9.9
 * 
 * TICKET 0006015 - Webserver: Nginx - https is forced incorrectly
 * Applying user suggestion after checking how mantisbt act.
 *
 * From MantisBT
 * Make test for HTTPS protocol compliant with PHP documentation
 * Prior to this, the protocol was considered to be HTTPS when
 * isset($_SERVER['HTTPS']) is true, while PHP doc[1] states that HTTPS is
 * "Set to a non-empty value if the script was queried through the HTTPS
 * protocol" so the test should be !empty($_SERVER['HTTPS']) instead.
 *
 * This was causing issues with nginx 1.x with php5fastcgi as
 * $_SERVER['HTTPS'] is set but empty, thus MantisBT redirects all http
 * requests to https.
 *
 */

/** 
 * checking register global = OFF (doesn't cause error')
 * @param integer &$errCounter reference to error counter
 * @return string html table row
 */
function check_php_settings(&$errCounter)
{
  $max_execution_time_recommended = 120;
  $max_execution_time = ini_get('max_execution_time');
  $memory_limit_recommended = 64;
  $memory_limit = intval(str_ireplace('M','',ini_get('memory_limit')));

  $final_msg = '<tr><td>Checking max. execution time (Parameter max_execution_time)</td>';
  if($max_execution_time < $max_execution_time_recommended)
  {
    $final_msg .=  "<td><span class='tab-warning'>{$max_execution_time} seconds - " .
                   "We suggest {$max_execution_time_recommended} " .
                   "seconds in order to manage hundred of test cases (edit php.ini)</span></td>";
  }
  else
  {
    $final_msg .= '<td><span class="tab-success">OK ('.$max_execution_time.' seconds)</span></td></tr>';
  }
  
  $final_msg .=  "<tr><td>Checking maximal allowed memory (Parameter memory_limit)</td>";
  if($memory_limit < $memory_limit_recommended)
  {
    $final_msg .= "<td><span class='tab-warning'>$memory_limit MegaBytes - " .
                  "We suggest {$memory_limit_recommended} MB" .
                  " in order to manage hundred of test cases</span></td></tr>";
  }
  else
  {
    $final_msg .= '<td><span class="tab-success">OK ('.$memory_limit.' MegaBytes)</span></td></tr>';
  }
  
  $final_msg .= "<tr><td>Checking if Register Globals is disabled</td>";
  if(ini_get('register_globals')) 
  {
    $final_msg .=  "<td><span class='tab-warning'>Failed! is enabled - " .
                   "Please change the setting in your php.ini file</span></td></tr>";
  }
  else
  { 
    $final_msg .= "<td><span class='tab-success'>OK</span></td></tr>\n";
  }
  
  return ($final_msg);
}


/** 
 * Check availability of PHP extensions
 * 
 * @param integer &$errCounter pointer to error counter
 * @return string html table rows
 * @author Martin Havlat
 * @todo martin: Do we require "Checking DOM XML support"? It seems that we use internal library.
 *      if (function_exists('domxml_open_file'))
 */
function checkPhpExtensions(&$errCounter)
{
 
  $cannot_use='cannot be used';
  $td_ok = "<td><span class='tab-success'>OK</span></td></tr>\n";
  $td_failed = '<td><span class="tab-warning">Failed! %s %s.</span></td></tr>';
  
  $msg_support='<tr><td>Checking %s </td>';

  $checks=array();

  // Database extensions  
  $checks[]=array('extension' => 'mysql',
                  'msg' => array('feedback' => 'php_mysql.dll', 'ok' => $td_ok, 'ko' => 'cannot be used') );

  $checks[]=array('extension' => 'pdo_mysql',
		  		'msg' => array('feedback' => 'php_pdo_mysql.dll', 'ok' => $td_ok,
  				'ko' => "cannot be used. <br>It's recommended to install it.") );
  
  $checks[]=array('extension' => 'mysqli',
                  'msg' => array('feedback' => 'php_mysqli.dll', 'ok' => $td_ok, 'ko' => 'cannot be used') );
  
  $checks[]=array('extension' => 'pgsql',
                  'msg' => array('feedback' => 'php_pgsql.dll', 'ok' => $td_ok, 'ko' => 'cannot be used') );
  
  $checks[]=array('extension' => 'gd',
                  'msg' => array('feedback' => 'php_gd2.dll', 'ok' => $td_ok, 
                                 'ko' => " not enabled.<br>Graph rendering requires it. This feature will be disabled." .
                                         " It's recommended to install it.") );
  
  $checks[]=array('extension' => 'ldap',
                  'msg' => array('feedback' => 'php_ldap.dll', 'ok' => $td_ok, 
                                 'ko' => " not enabled. LDAP authentication cannot be used. " .
                                         "(default internal authentication will works)"));
  
  $checks[]=array('extension' => 'json',
                  'msg' => array('feedback' => 'JSON library', 'ok' => $td_ok, 
                                 'ko' => " not enabled. You MUST install it to use EXT-JS tree component. "));
  
  $out='';
  foreach($checks as $test)
  {
    $out .= sprintf($msg_support,$test['msg']['feedback']);
    if( extension_loaded($test['extension']) )
    {
      $msg=$test['msg']['ok'];
    }
    else
    {
      $msg=sprintf($td_failed,$test['msg']['feedback'],$test['msg']['ko']);  
    }
    $out .= $msg;
  }

  return ($out);
}  


/**
 * check PHP defined timeout
 * 
 * @param integer &$errCounter reference to error counter
 * @return string html row with result 
 */
function check_timeout(&$errCounter)
{
    $out = '<tr><td>Maximum Session Idle Time before Timeout</td>';

  $timeout = ini_get("session.gc_maxlifetime");
  $gc_maxlifetime_min = floor($timeout/60);
  $gc_maxlifetime_sec = $timeout % 60;
  
    if ($gc_maxlifetime_min > 30) {
      $color = 'success';
      $res = 'OK';
  } else if ($gc_maxlifetime_min > 10){
      $color = 'warning';
      $res = 'Short. Consider to extend.';
  } else {
      $color = 'error';
      $res = 'Too short. It must be extended!';
        $errCounter++;
    }
    $out .= "<td><span class='tab-$color'>".$gc_maxlifetime_min .
        " minutes and $gc_maxlifetime_sec seconds - ($res)</span></td></tr>\n";
    
  return $out;
}


/**
 * Display Operating System
 * 
 * @return string html table row
 */
function checkServerOs()
{
  $final_msg = '<tr><td>Server Operating System (no constrains)</td>';
  $final_msg .= '<td>'.PHP_OS.'</td></tr>';
  
  return $final_msg;
}  


/**
 * check minimal required PHP version
 * 
 * @param integer &$errCounter pointer to error counter
 * @return string html row with result 
 */
function checkPhpVersion(&$errCounter)
{
  // 5.2 is required because json is used in ext-js component
  // 20131001 - 5.4 to avoid the issue with issuetracker interface
/*  
  $min_version = '5.4.0'; 
  $my_version = phpversion();

  // version_compare:
  // -1 if left is less, 0 if equal, +1 if left is higher
  $php_ver_comp = version_compare($my_version, $min_version);

  $final_msg = '<tr><td>PHP version</td>';

  if($php_ver_comp < 0) 
  {
    $final_msg .= "<td><span class='tab-error'>Failed!</span> - You are running on PHP " . $my_version .
                  ", and TestLink requires PHP " . $min_version . ' or greater. ' .
                  'This is fatal problem. You must upgrade it.</td>';
    $errCounter += 1;
  } 
  else 
  {
    $final_msg .= "<td><span class='tab-success'>OK ( {$min_version} [minimum version] ";
    $final_msg .= ($php_ver_comp == 0 ? " = " : " <= ");
    $final_msg .=  $my_version . " [your version] " ;
    $final_msg .= " ) </span></td></tr>";
  }
*/
  $final_msg = '<tr><td>PHP version</td>';
  $final_msg .= "<td><span class='tab-sucess'>" .phpversion() ."</span></td></tr>";
  
  return ($final_msg);
}  


/**
 * verify that files are writable/readable
 * OK result is for state:
 *     a) installation - writable
 *     b) installed - readable
 * 
 * @param integer &$errCounter pointer to error counter
 * @return string html row with result 
 * @author Martin Havlat
 */
function check_file_permissions(&$errCounter, $inst_type, $checked_filename, $isCritical=FALSE)
{
  $checked_path = realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..');
  $checked_file = $checked_path.DIRECTORY_SEPARATOR.$checked_filename;
  $out = '<tr><td>Access to file ('.$checked_file.')</td>';

  if ($inst_type == 'new')
  {
    if(file_exists($checked_file)) 
    {
      if (is_writable($checked_file))
      {
        $out .= "<td><span class='tab-success'>OK (writable)</span></td></tr>\n"; 
      }
      else
      {
        if ($isCritical)
        {
          $out .= "<td><span class='tab-error'>Failed! Please fix the file " .
          $checked_file . " permissions and reload the page.</span></td></tr>"; 
          $errCounter += 1;
        }
        else
        {
           $out .= "<td><span class='tab-warning'>Not writable! Please fix the file " .
           $checked_file . " permissions.</span></td></tr>"; 
        }      
      }
    } 
    else 
    {
      if (is_writable($checked_path))
      {
        $out .= "<td><span class='tab-success'>OK</span></td></tr>\n"; 
      }
      else
      {
        if ($isCritical)
        {
          $out .= "<td><span class='tab-error'>Directory is not writable! Please fix " .
          $checked_path . " permissions and reload the page.</span></td></tr>"; 
          $errCounter += 1;
        }
        else
        {
          $out .= "<td><span class='tab-warning'>Directory is not writable! Please fix " .
          $checked_path . " permissions.</span></td></tr>"; 
        }      
      }
    }
  }
  else
  {
    if(file_exists($checked_file)) 
    {
      if (!is_writable($checked_file))
      {
        $out .= "<td><span class='tab-success'>OK (read only)</span></td></tr>\n"; 
      }
      else
      {
        $out .= "<td><span class='tab-warning'>It's recommended to have read only permission for security reason.</span></td></tr>"; 
      }
    } 
    else 
    {
      if ($isCritical)
      {
        $out .= "<td><span class='tab-error'>Failed! The file is not on place.</span></td></tr>"; 
        $errCounter += 1;
      }
      else
      {
        $out .= "<td><span class='tab-warning'>The file is not on place.</span></td></tr>"; 
      }  
    }
  }

  return($out);
}


/**
 * Check read/write permissions for directories
 * based on check_with_feedback($dirs_to_check);
 * 
 * @param integer &$errCounter pointer to error counter
 * @return string html row with result 
 * @author Martin Havlat
 */
function check_dir_permissions(&$errCounter)
{
  $dirs_to_check = array('cache' . DIRECTORY_SEPARATOR . 'templates_c' => null, 
                         'logs' => 'log_path','upload_area' => 'repositoryPath');

  $final_msg = '';
  $msg_ko = "<td><span class='tab-error'>Failed!</span></td></tr>";
  $msg_ok = "<td><span class='tab-success'>OK</span></td></tr>";
//  $checked_path_base = realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..');
  $checked_path_base = realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);
  
  $final_msg .= "<tr><td>For security reasons we suggest that directories tagged with [S]" .
                " on following messages, will be made UNREACHEABLE from browser.<br>" .
                "<span class='tab-success'>Give a look to README file, section 'Installation & SECURITY' " . 
                " to understand how to change the defaults.</span>" .
                "</td>";

  foreach ($dirs_to_check as $the_d => $how) 
  {
    if( is_null($how) )
    {
      // Correct relative path for installer.
      $needsLock = '';
//      $the_d = $checked_path_base . DIRECTORY_SEPARATOR . $the_d;
    }
    else
    {
      $needsLock = '[S] ';
//      $the_d = config_get($how);  
    }
    $the_d = $checked_path_base . DIRECTORY_SEPARATOR . $the_d;
    
    $final_msg .= "<tr><td>Checking if <span class='mono'>{$the_d}</span> directory exists <b>{$needsLock}</b<</td>";
  
    if(!file_exists($the_d)) 
    {
        $errCounter += 1;
        $final_msg .= $msg_ko; 
      } 
    else 
    {
        $final_msg .= $msg_ok;
        $final_msg .= "<tr><td>Checking if <span class='mono'>{$the_d}</span> directory is writable (by user used to run webserver process) </td>";
        if(!is_writable($the_d)) 
        {
        $errCounter += 1;
              $final_msg .= $msg_ko;  
        }
        else
        {
            $final_msg .= $msg_ok;  
      }
     }
  }

  return($final_msg);
}


/** 
 * print table with system checking results
 *  
 * @param integer &$errCounter reference to error counter
 * @author Martin Havlat
 **/
function reportCheckingSystem(&$errCounter)
{
  echo '<h2>System requirements</h2><table class="common" style="width: 100%;">';
  echo checkServerOs();
  echo checkPhpVersion($errCounter);
  echo '</table>';
}


/** 
 * print table with system checking results 
 * 
 * @param integer &$errCounter reference to error counter
 * @author Martin Havlat
 **/
function reportCheckingWeb(&$errCounter)
{
  echo '<h2>Web and PHP configuration</h2><table class="common" style="width: 100%;">';
  echo check_timeout($errCounter);
  echo check_php_settings($errCounter);
  echo checkPhpExtensions($errCounter);
  echo '</table>';

}


/** 
 * print table with system checking results
 *  
 * @param integer &$errCounter pointer to error counter
 * @param string installationType: useful when this function is used on installer
 * 
 * @author Martin Havlat
 **/
function reportCheckingPermissions(&$errCounter,$installationType='none')
{
  echo '<h2>Read/write permissions</h2><table class="common" style="width: 100%;">';
  echo check_dir_permissions($errCounter);
  
  // for $installationType='upgrade' existence of config_db.inc.php is not needed
  $blockingCheck=$installationType=='upgrade' ? FALSE : TRUE;
  if($installationType=='new')
  {
    echo check_file_permissions($errCounter,$installationType,'config_db.inc.php', $blockingCheck);
  }
  echo '</table>';
}