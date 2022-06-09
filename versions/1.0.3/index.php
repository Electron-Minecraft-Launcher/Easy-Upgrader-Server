<?php

header('Content-Type: application/json');

// +---------------------------------------------------------------------+
// |     PLEASE READ THE INSTRUCTIONS BELOW BEFORE USING THIS SCRIPT     |
// +---------------------------------------------------------------------+

/**
 * Description of each keys in the $update array:
 *  - 'version': the new version number (eg. 1.0.3-beta)
 *  - 'removedDirectories': an array of directories that will be deleted (these directories can contain files or subdirectories)
 *  - 'removedFiles': an array of files that will be deleted
 *  - 'addedAndModifiedFilesAndDirectories': an array of files that will be added or modified ; you can use the 'dir_to_array()' function to get the list of files and directories
 * 
 * Exemple of different values for the 'version' key in the $update array: (cf. ../index.php)
 * 
 * Exemple of different values for the 'removedDirectories' key in the $update array:
 *  - ['/assets/css/', '/assets/js/', '/home/']: these directories and their subdirectories will be deleted
 *  
 * Exemple of different values for the 'removedFiles' key in the $update array:
 *  - ['index.php', '/assets/css/style.css']: these files will be deleted
 */

$files = [];
get_all_files('1.0.3');

$update = array(
	'version' => '1.0.3',
	"removedDirectories" => [
		"/assets/css copy/"
	],
	'removedFiles' => [
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
 */







/**
 * This function will get all the files and directories of your new version in the 'files' folder.
 * @param string $version The new version you want to 'scan'.
 */
function get_all_files($version)
{
	global $files;

	$dir = '../files/' . $version;

	$cdir = scandir($dir);
	foreach ($cdir as $key => $value) {

		if (!in_array($value, array(".", ".."))) {
			if (is_dir($dir . "/" . $value)) {
				get_all_files($version . "/" . $value);
			} else {
				$hash = hash_file('sha1', $dir . "/" . $value);
				$size = filesize($dir . "/" . $value);
				$path = str_replace("files/", "", $dir . "/" . $value);
				$url = "http://" . $_SERVER['HTTP_HOST'] . "/versions/files/" . $version . '/' . $value;

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
