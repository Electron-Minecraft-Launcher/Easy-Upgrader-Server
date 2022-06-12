<?php

header('Content-Type: application/json');

// +---------------------------------------------------------------------+
// |     PLEASE READ THE INSTRUCTIONS BELOW BEFORE USING THIS SCRIPT     |
// +---------------------------------------------------------------------+

/**
 * Description of the variable '$version':
 * It is the number of the new version (eg. '1.0.3-beta'). You have to put the same name as the folder you just created.
 * 
 * Description of each keys in the $update array:
 *  - 'version': the new version number (let the $version variable)
 *  - 'needsToReinstall': it could be an array of modules that need to be reinstalled (cf. ../index.php for more info)
 *  - 'removedDirectories': an array of directories that will be deleted (these directories can contain files or subdirectories)
 *  - 'removedFiles': an array of files that will be deleted
 *  - 'addedAndModifiedFilesAndDirectories': an array of files that will be added or modified ; you can use the 'dir_to_array()' function to get the list of files and directories
 * 
 * Exemple of different values for the 'version' key in the $update array: (cf. ../index.php)
 * 
 * Exemple of different values for the 'removedFilesAndDirectories' key in the $update array:
 *  - ['/assets/css/', '/assets/js/', '/home/', 'index.php', '/assets/includes/footer.html']: these directories (from the root) and their subdirectories will be deleted
 */

$version = "1.0.3";
$files = [];
get_all_files($version);

$update = [];
$update = array(
	'version' => $version,
	'needsToReinstall' => ['configAndDB', 'api'],
	"removedFilesAndDirectories" => [
		"/assets/css copy/",
		"HoneyWeb.zip"
	],
	'addedAndModifiedFilesAndDirectories' => $files

);


echo json_encode($update);

/**
 * “Then, what have I to do?”
 * 
 * You just have to put your new files and in the folder that correspond to your version in the 'files' folder.
 * Yous should not put files that have not been updated since the last version.
 * 
 * Don't forget to put and update the PHP file with the new version number and with the functions needed for the 'upgrader.php' file.
 */







/**
 * This function will get all the files and directories of your new version in the 'files' folder.
 * @param string $version The new version you want to 'scan'.
 */
function get_all_files($v)
{
	global $files;
	global $version;

	$dir = '../files/' . $v;

	$cdir = scandir($dir);
	foreach ($cdir as $key => $value) {

		if (!in_array($value, array(".", ".."))) {
			if (is_dir($dir . "/" . $value)) {
				get_all_files($v . "/" . $value);
			} else {
				$hash = hash_file('sha1', $dir . "/" . $value);
				$size = filesize($dir . "/" . $value);
				$path = str_replace("../files/" . $version, "", $dir . "/" . $value);
				$url = "http://" . $_SERVER['HTTP_HOST'] . "/versions/files/" . $v . '/' . $value;

				array_push($files, [
					'path' => $path,
					'size' => $size,
					'hash' => $hash,
					'url' => $url
				]);
			}
		}
	}
}
