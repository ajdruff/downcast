<?php

/*
 * header is required or wont parse correctly
 */
header( "Content-type: application/json" );

$result['source_url']=$_POST['source_url'];
/*
 * find file from url
 */
$file_info = $this->getFileFromUrl($_POST[ 'source_url' ]);

/*
 * return error if not found
 */
if ( !$file_info['file_exists']) {
    $result['error']='File does not exist';
    $result['source_url']=$_POST['source_url'];
    $result['validated_file_path']=$file_info['file_path'];
    die(json_encode($result));
}



$result[ 'file_path' ] = $this->file_getRelativePath($file_info [ 'file_path' ]);
$result[ 'html' ] = '';
$result[ 'html_with_line_numbers' ] = '';

/*
 * parse so we can show source formatting
 */
$file_contents=htmlspecialchars(file_get_contents( $this->file_getAbsolutePath($result[ 'file_path' ])));
$result[ 'html' ] = (
        (

               '<pre><code class="markdown">'.$file_contents.'</code></pre>' 
        )
);



//$result[ 'html' ]= file_get_contents( $this->file_getAbsolutePath($result[ 'file_path' ]));
/*
 * apply <br> tags
 */

$lines = preg_split( '/\r\n|\r|\n/', ($file_contents) ); //split the string on new lines
//ref http://stackoverflow.com/a/14087932
foreach ( $lines as $lineNumber => $line ){
    if ( $lineNumber === 0 ) {
      //  $lineNumber = '';
         $line_break='';
} //else if ( $lineNumber ===1 ){
  //      $lineNumber .= ". ";
  //      $line_break="\r\n";
//}
else {
  //   $lineNumber .= ". ";
   // $line_break="\r\n";
}
     $lineNumber .= ". ";
    $line_break="\r\n";
    $result[ 'html_with_line_numbers' ].= $line_break . '<span class="noselect">'.$lineNumber.'</span>' . $line;
}

//$result[ 'html' ] = trim(nl2br( $result[ 'html' ] ));
//$result[ 'html_with_line_numbers' ] = trim(( $result[ 'html_with_line_numbers' ] ));
$result[ 'html_with_line_numbers' ] = (
        (

               '<pre><code>'.trim($result[ 'html_with_line_numbers' ]).'</code></pre>' 
        )
);

$result['id']=$this->getPlugin('ViewSource')->convertToDomID($result[ 'file_path' ] );



echo json_encode( $result );




?>
