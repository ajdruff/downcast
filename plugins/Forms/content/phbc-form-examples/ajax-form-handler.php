<?php

//----------AFTER THE FORM HAS BEEN SUBMITTED----------
$result_array[ 'status' ] = 'OK';

$result_json = json_encode( $result_array );
header( "Content-type: application/json" );
echo $result_json;
exit();
?>