<?php

header('Content-Type: application/json');

// +---------------------------------------------------------------------+
// |     PLEASE READ THE INSTRUCTIONS BELOW BEFORE USING THIS SCRIPT     |
// +---------------------------------------------------------------------+

/**
 * Description of each keys in the $versions array:
 *  - 'version': the new version number (eg. 1.0.3-beta) | YOU MUST KEEP THIS KEY
 *  - 'date': the publishing date of the new version (eg. 2022-09-18)
 *  - 'type': the type of the version (eg. release, beta, alpha, ...)
 *  - 'category': the category of the version (eg. major, minor, patch, ...)
 *  - 'needsToReinstall': it could be an array of modules that need to be reinstalled
 *  - ...
 * 
 * 
 * Exemple of different values for the 'version' key in the $versions array:
 *  - '1.0.3': it could be a stable release
 *  - '1.0.3-beta': it could be a beta version
 *  - '1.0.3-alpha': it could be an alpha version
 *  - ...
 * 
 * Exemple of different values for the 'type' key in the $versions array:
 *  - 'release': a stable version
 *  - 'beta': a beta version
 *  - 'alpha': an alpha version
 *  - ...
 * 
 * Exemple of different values for the 'category' key in the $versions array:
 *  - 'major': a major update
 *  - 'minor': a minor update
 *  - 'patch': a patch update
 *  - ...
 * 
 * You can choose which keys you want to keep. You can add your own keys, and remove the default ones. But you CANNOT remove the 'version' key.
 * The 'date', 'type', 'category' and 'needsToReinstall' keys are here as examples, you can choose to keep them or not, and you can add your own keys for your own needs.
 * 
 * You have to add new versions at the top/beginning of the array; you MUST NOT add new versions at the bottom/end of the array.
 */

$versions = array( // Here is an example of what this array should look like. You can delete (and you should) all the demo versions.
	// You can add a new version here.

	[
		'version' => '1.0.3',
		'date' => '2022-06-07',
		'type' => 'release',
		'category' => 'major',
		'needsToReinstall' => ['configAndDB', 'api']
	],
	[
		'version' => '1.0.3-beta',
		'date' => '2022-06-06',
		'type' => 'beta',
		'category' => 'major',
		'needsToReinstall' => ['configAndDB']
	],
	[
		'version' => '1.0.3-alpha',
		'date' => '2022-06-05',
		'type' => 'alpha',
		'category' => 'patch',
		'needsToReinstall' => []
	],

	// Do not add version at the end of the array.

);

echo json_encode($versions);

/**
 * “Then, what have I to do?”
 * 
 * You have to create a new folder that have the same name as the version number you want to add.
 * Eg., if you want to add the version 1.0.3, you have to create a folder named '1.0.3'. You must add this folder in the current folder ('versions').
 * In this folder, you have to create a file named 'index.php'. You have a model file in the '1.0.3' folder.
 * After that, you will have to create in the folder named 'files' an new folder that have the same name as the version number you want to add (again).
 * Eg., if you want to add the version 1.0.3, you have to create, in the folder 'files', a folder named '1.0.3'.
 * 
 * The tree structure of the easy-upgrader-server software should look like this:
 * 
 * 						/ (root of the website)
 * 						├─── HOME OF YOUR WEBSITE (if you want)
 * 						│
 * 						└─── versions/
 * 						     ├─── index.php (this file)
 * 						     │
 * 						     ├─── 1.0.3/ (your first new folder)
 * 						  	 │		└─── index.php (your new index.php file)
 * 						  	 │
 * 						  	 ├─── 1.0.3-beta/ 
 * 						  	 │		└─── index.php
 * 						  	 │
 * 						  	 ├─── 1.0.3-alpha/
 * 						  	 │		└─── index.php
 * 						  	 │
 * 						    ... (other versions)
 * 								 │
 * 						  	 └─── files/
 * 						  	  		├─── .htaccess (your new .htaccess file)
 * 						  	  		│
 * 								    	├─── 1.0.3/ (your second new folder)
 * 								    	│	 └─── ... (your files)
 * 											│
 * 											├─── 1.0.3-beta/
 * 											│    └─── ...
 * 											│
 * 											├─── 1.0.3-alpha/
 * 											│    └─── ...
 * 											│	
 * 										 ... (other versions)
 * 
 * You can follow the next steps in the 'index.php' file of the '1.0.3' folder (for the demo: after that, you could delete it and delete all others versions folders).
 * 
 * Here is the tree structure of the easy-upgrader-server software without any version (neither demo, nor real version):
 * 
 * 						/ (root of the website)
 * 						│
 * 						└─── versions/
 * 						     ├─── index.php
 * 						     │
 * 						     └─── files/
 * 						  	  		└─── .htaccess
 */
