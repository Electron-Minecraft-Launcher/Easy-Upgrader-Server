<?php

error_reporting(0);

// +--------------------------------------------------------------+
// |     EASY UPGRADER :: UPGRADER FILE FROM SERVER TO CLIENT     |
// +--------------------------------------------------------------+

/**
 * This file is a major part of the Easy Upgrader.
 * It will be downloaded by the easy-upgrader-client to upgrade the web application.
 */

/**
 * Here is a diagram of the operation of the 'upgrader.php' file:
 * 
 * 
 *       (1)           Check if the variable GET 'version' is set. 
 *                                          |                      
 *                          if THE VARIABLE GET VERSION IS SET --- else -- Return (with echo) error and exit.
 *                                          |
 *       (2)           Check if the variable GET version is correct
 *                    and if the version is in the list of versions.
 *                                          |
 *                                        if YES --- else -- Return (with echo) error and exit.
 *                                          |
 *                     Get the previous version (what should be the
 *                      current versions of the web application). 
 *       (3)          Then, if necessary, create a condition about the
 *                     file to include (with include()). This file 
 *                      must contains the functions needed for the
 *                                 'upgrader.php' file.
 *                      In all the cases, a file must be included.
 *                                          |
 *                                          |
 *                                          |
 *                   Compare the presumed current version with the
 *       (4)           real current version via a function in the 
 *                                    included file.
 *                                          |
 *                             if THE VERSIONS ARE THE SAME --- else -- Return (with echo) error and exit.
 *                                          |
 *                    If necessary, check the perms of the user who
 *       (5)            is installing the new version of the web 
 *                                      application.
 *                                          |
 *                                   if PERMS ARE OK --- else -- Return (with echo) error and exit.
 *                                          |
 *                    • Delete the files and folders that have to
 *       (6)            be deleted.
 *                    • Install the new files and folders.
 *                                          |
 *                                if INSTALLATION IS OK --- else -- Return (with echo) error and exit.
 *                                          |
 *       (7)                    Return (with echo) success
 *                                                           
 */


// (1) Check if the variable GET 'version' is set. The variable GET 'version' is the version of the web application that the user wants to install.

if (!isset($_GET['version']) || empty($_GET['version'])) {
  echo 'Error: The variable GET "version" is not set.';
  http_response_code(500);
  exit;
}

// (2) Check if the variable GET version is correct

$i = 0;
$correct_version = false;
foreach (get_versions() as $value) {
  if ($_GET['version'] == $value) {
    $correct_version = true;
    break;
  }
  $i++;
}

if (!$correct_version) {
  echo 'Error: The variable GET "version" is not correct.';
  http_response_code(500);
  exit;
}


// (3) Get the previous version (what should be the current versions of the web application).
// In this example, we do not change the include path in function of the version.
/**
 * The include path is a path from the root of your web application website to a file that contains the function
 *  - 'get_current_version()'. This function returns the current version of the web application, like '1.0.0', '0.0.9-beta', etc.
 */

$presumed_current_version = get_versions()[0][$i]->version;

// ------ You can make your condition here ------
// eg.:
// if (in_array($presumed_current_version, ['1.0.2', '1.0.1', '1.0.0', '1.0.0-beta', '1.0.0-alpha', ...])) { 
if (!include('assets/includes/main.php')) {
  echo 'Error: Unable to include the file.';
  http_response_code(500);
  exit;
}
// } else { // you can use 'else if ()' too
//   if (!include('assets/includes/main_include.php')) {
//     echo 'Error: Unable to include the file.';
//     http_response_code(500);
//     exit;
//   }
// }
// ----------------------------------------------

$current_version = get_current_version();


// (4) Compare the presumed current version with the real current version via a function in the included file.

if ($presumed_current_version != $current_version) {
  echo 'Error: You are trying to install a version that may be not compatible with the current version of your web application. Please, indicate a correct version to install.';
  http_response_code(500);
  exit;
}


// (5) Check the perms of the user who is installing the new version of the web application.
// In this example, we do not check the permissions of the user who is installing the new version of the web application.
/**
 * You can add here your own code lines to check if the user is allowed to upgrade, or other stuff (if you want).
 * Don't forget to exit and add the response code if you want to stop the upgrade.
 */



// (6) Delete the files and folders that have to be deleted. Install the new files and folders.

$new_version_info = get_new_version($_GET['version']);

foreach ($new_version_info[0]->removedFilesAndDirectories as $key => $value) {
  rmrf($value);
}

foreach ($new_version_info[0]->addedAndModifiedFilesAndDirectories as $key => $value) {
  $file_content = file_get_contents($value->url);
  if (!file_exists(dirname($value->path))) mkdir(dirname($value->path), 0775, true);
  file_put_contents($value->path, $file_content);
}


// (7) Return (with echo) success.

echo 'Success: The upgrade has been completed.';
http_response_code(200);
exit;





/**
 * Get the list of the versions of the EML AdminTool from the electron-minecraft-launcher.ml website in an array.
 * @return array|object List of versions
 */
function get_versions()
{
  try {
    $ch = curl_init("http://server.easy-upgrader.off/versions/"); // The URL to get the list of versions: DON'T FORGET TO CHANGE IT TO THE RIGHT URL.
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $data = json_decode(curl_exec($ch));

    if (!curl_errno($ch)) {
      switch ($http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
        case 200:
          curl_close($ch);
          return [$data, true];
          break;
        default:
          curl_close($ch);
          return ['Unable to check the versions of the EML AdminTool', false];
      }
    } else {
      curl_close($ch);
      return ['Unable to check the versions of the EML AdminTool', false];
    }
  } catch (Exception $e) {
    return ['Unable to check the versions of the EML AdminTool', false];
  }
}

/**
 * This function get the informations of the new version.
 * @param string $version The new version (from the GET version).
 */
function get_new_version($version)
{
  try {
    $ch = curl_init("http://server.easy-upgrader.off/versions/" . $version . "/"); // The URL to get the list of versions: DON'T FORGET TO CHANGE IT TO THE RIGHT URL.
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $data = json_decode(curl_exec($ch));

    if (!curl_errno($ch)) {
      switch ($http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
        case 200:
          curl_close($ch);
          return [$data, true];
          break;
        default:
          curl_close($ch);
          return ['Unable to check the new version of the EML AdminTool', false];
      }
    } else {
      curl_close($ch);
      return ['Unable to check the new version of the EML AdminTool', false];
    }
  } catch (Exception $e) {
    return ['Unable to check the new version of the EML AdminTool', false];
  }
}

/**
 * This function delete a file or a folder and its content.
 * @param $dir
 */
function rmrf($dir)
{

  if (is_dir($dir)) {

    $files = array_diff(scandir($dir), ['.', '..']);

    foreach ($files as $file) {
      rmrf($dir . "/" . $file);
    }

    rmdir($dir);
  } else {

    unlink($dir);
  }
}

function get_current_version()
{
  return '1.0.3-beta';
}
