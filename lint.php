<?php
	include
	lintAnalyzeAPK();
	function lintAnalyzeAPK() {

		$apkPath = "/home/anid/Downloads/com.adda247.app-4.0.2-33.apk";
		//$apkPath = "/home/anid/Downloads/deviceInfo.apk";
		$apkDecompiledSaveLocation = "/home/anid/Documents/deviceInfo";
		// decomile apk
		shell_exec("apktool d -f -s $apkPath -o $apkDecompiledSaveLocation");

		$lintProjectLocation = "/home/anid/Documents/deviceInfo";
		$lintErrorLocationSave = "/home/anid/Documents/lint_error.txt";
		// run lint
		shell_exec( "lint $lintProjectLocation > $lintErrorLocationSave" );

		readLintOutputToCSV( $lintErrorLocationSave );
	}

	/*function readLintOutputToCSV( $lintOutputLocation ) {

		$fileContentArray = file( $lintOutputLocation );
		$csvFilePointer = fopen( "/home/anid/Documents/lint1.csv" , "w" );
		$sz = sizeof( $fileContentArray );
		$desc = array();
		for( $index = 3; $index < $sz; $index++ ) {
			$line = trim( $fileContentArray[ $index ] );
			if( isLineBreak( $line ) ) {

				fputcsv( $csvFilePointer, $desc );
				$desc = array();
			} else {
				array_push( $desc, $line);
			}
		}
		fclose( $csvFilePointer );
	}*/

	function readLintOutputToCSV( $lintOutputLocation ) {

		$fileContentArray = file( $lintOutputLocation );
		$csvFilePointer = fopen( "/home/anid/Documents/lint.csv" , "w" );
		$sz = sizeof( $fileContentArray );
		$desc = array();
		for( $index = 3; $index < $sz - 1; $index++ ) {
			$line = trim( $fileContentArray[ $index ] );
			if( isLineBreak( $line ) ) {
				$desc = array();
				continue;
			}
			array_push( $desc, $line );
			$nextPos = $index + 1;
			$nextLine = trim( getArrayNextElement( $fileContentArray, $nextPos ) );
			$empty = "";
			if( $nextLine != $empty ) {
				$bugId = getBugId( $nextLine );
				// write to csv if next line contains bugId
				if( $bugId == $empty ) {
					$desc[ 0 ] = $desc[ 0 ]."    ".$nextLine;
					$index++;
				}
			}
			fputcsv( $csvFilePointer,  $desc );
			$desc = array();
		}
		fclose( $csvFilePointer );
	}

	function isLineBreak( $str ) {

		$str = trim( $str );
		if( $str === "" ) {
			return true;
		}
		$char = substr( $str, 0, 1);
		if( $str === "^" || $char === "~") {
			return true;
		}
		return false;
	}

	function getBugId( $line ) {

		$bugId = "";
		$regExp = "/\[[A-z]+\]/";
		$matches = array();
		$found = preg_match( $regExp, $line, $matches );
		if( $found == 1 ) {
			$bugId = trim( $matches[ 0 ] );
			// remove '[' and ']'
			$regExpPattern = "/\[|\]/";
		$str = trim( preg_replace( $regExpPattern, "", $bugId ) );
		}
		return $bugId;
	}


	function getArrayNextElement( &$arr, $nextPos ) {

		$sz = sizeof( $arr );
		if( $nextPos < $sz ) {
			return trim( $arr[ $nextPos ] );
		}
		return "";
	}

?>