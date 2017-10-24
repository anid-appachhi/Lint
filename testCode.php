<?php 
		$str = "[apple]";
		$regExpPattern = "/\[|\]/";
		$str = trim( preg_replace( $regExpPattern, "", $str ) );
		echo "$str \n";
?>