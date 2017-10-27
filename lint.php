<?php 
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
		$MASTER_LIST  = parse_ini_file( "LintErrors.ini");

		readLintOutputToCSV( $lintErrorLocationSave, $MASTER_LIST );
	}

	function readLintOutputToCSV( $lintOutputLocation, &$errorList ) {

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
			// assign priority
			$id = trim( getBugId( $desc[ 0 ] ) );
			$priority = getPriorityFromList( $errorList, $id );
			if( $priority == "" ) {
				$priority = "NO PRIORITY";
			}
			array_push( $desc,  $priority );
			fputcsv( $csvFilePointer,  $desc );
			//print_r( $desc );
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
		if( $found === 1 ) {
			$bugId = trim( $matches[ 0 ] );
			// remove '[' and ']'
			$regExpPattern = "/\[|\]/";
			$str = trim( preg_replace( $regExpPattern, "", $bugId ) );
			$bugId = $str;
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

	function getPriorityFromList( & $errorList, $bugId ) {

		$sz = sizeof( $errorList );
		for( $index = 0; $index < $sz; $index++ ) {
			if( $errorList[ $index ][ 'bugId' ] == $bugId ) {
				return trim( $errorList[ $index ][ 'priority'] );
			}
		}
		return "";
	}

?>