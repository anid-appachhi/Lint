<?php
	

	$fileContent = file( "errorDetails.txt" );
	$sz = count( $fileContent );
	$idWithDescription = array();
	$MASTER_LIST = array();
	for( $index = 0; $index < $sz; $index++ ) {
		//echo $fileContent[ $index ]."\n";
		$content = trim( $fileContent[ $index ] );
		if( $content[ 0 ] == "\"" ) {
			$arr = preg_split( "/:/", $content );
			$arr[ 0 ] = trim( preg_replace("/\"/", " ",  $arr[ 0 ] ) );
			$arr[ 1 ] = trim( $arr[ 1 ] );
			$MASTER_LIST[ "bugId" ] = $arr[ 0 ];
			$MASTER_LIST[ "bugSummary" ] = $arr[ 1 ];
			$MASTER_LIST[ "priority" ] = "MEDIUM";
			array_push( $idWithDescription, $MASTER_LIST );
		}
	}
	//print_r( $idWithDescription );
	echo '$MASTER_LIST = array('."\n";
	$sz = count( $idWithDescription );
	for( $index = 0; $index < $sz; $index++ ) {
		//echo '"'.$idWithDescription[ $index ][ 0 ].'" => "'.$idWithDescription[ $index ][ 1 ].'", '."\n";
		echo $index.' => array( 
			"bugId" => "'.$idWithDescription[ $index ]['bugId'].'",
			"bugSummary" => "'.$idWithDescription[ $index ]['bugSummary'].'",
			"priority" => "'.$idWithDescription[ $index ]['priority'].'"
		), '."\n";
	}

	echo ');';
?>