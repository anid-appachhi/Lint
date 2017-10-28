<?php 
		/*$str = "[apple]";
		$regExpPattern = "/\[|\]/";
		$str = trim( preg_replace( $regExpPattern, "", $str ) );
		echo "$str \n";*/
/*
		$str = 'res/drawable  Error: <vector> requires API level 21 (current min is 1) or building with Android Gradle plugin 1.4 or higher [NewApi]
		<vector android:name="abc_btn_checkbox_checked" android:height="32.0dip" android:width="32.0dip" android:viewportWidth="48.0" android:viewportHeight="48.0"';*/
		$str = 'res/drawable <vector> requires API level 21 (current min is 1) or building with Android Gradle plugin 1.4 or higher [NewApi]
		<vector android:name="abc_btn_checkbox_checked" android:height="32.0dip" android:width="32.0dip" android:viewportWidth="48.0" android:viewportHeight="48.0"';

		$pattern = "/(Error:)|(Warning:)/";
		$matches = array();
		preg_match( $pattern,  $str, $matches, PREG_OFFSET_CAPTURE );
		echo $matches[ 0 ][ 1 ]."\n";
?>