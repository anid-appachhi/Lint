<?php
	lintAnalyzeAPK();
	function lintAnalyzeAPK() {

		$apkPath = "/home/anid/Downloads/deviceInfo.apk";
		$apkDecompiledSaveLocation = "/home/anid/Documents/deviceInfo";
		// decomile apk
		shell_exec("apktool d -f -s $apkPath -o $apkDecompiledSaveLocation");

		$lintProjectLocation = "/home/anid/Documents/deviceInfo";
		$lintErrorLocationSave = "/home/anid/Documents/lint_error.txt";
		// run lint
		shell_exec( "lint lintProjectLocation > lintErrorLocationSave" );
	}
?>