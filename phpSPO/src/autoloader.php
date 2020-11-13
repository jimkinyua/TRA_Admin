<?php
if (defined("PHP_SPO_BASE_PATH") === false) {
	define("PHP_SPO_BASE_PATH", __DIR__ . DIRECTORY_SEPARATOR);
	spl_autoload_register(function($className) {
		if (class_exists($className) === false) {
			$cPath	= array_values(array_filter(explode("\\", $className)));
			if (
				$cPath[0] == "Office365" 
				&& $cPath[1] == "PHP"
				&& $cPath[2] == "Client"
			) {
				$cPath		= array_slice($cPath, 3);
				$fileName	= array_pop($cPath);
				$filePath	= PHP_SPO_BASE_PATH;
				foreach($cPath as $c) {
					$filePath	.= $c . DIRECTORY_SEPARATOR;
				}
				$filePath	.= $fileName . ".php";
				if (is_readable($filePath) === true) {
					require_once $filePath;
				}
			}
		}
	});
}