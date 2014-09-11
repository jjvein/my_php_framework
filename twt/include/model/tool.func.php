<?PHP

function unCleanValue($val)
{
    if ( $val == "" )
    {
    	return "";
    }
	$val = str_replace(  "&#032;"," ", $val );
    $val = str_replace( "&amp;","&",$val );
    $val = str_replace( "&#60;&#33;--","<!--",$val );
    $val = str_replace( "--&#62;","-->",$val );
    $val = str_replace( "&#60;script","/<script/i", $val);
    $val = str_replace( "&gt;", ">",$val );
    $val = str_replace( "&lt;","<",$val );
    $val = str_replace( "&quot;","\"",$val );   
    $val = str_replace( "&#036;","/\\\$/", $val );
    $val = str_replace( "","", $val ); // Remove literal carriage returns	
    $val = str_replace( "&#33;","!", $val );	
    $val = str_replace( "&#39;","'",$val ); // IMPORTANT: It helps to increase sql query safety.
 
	// Ensure unicode chars are OK
    	
	$val = str_replace( "&#\\1;","/&amp;#([0-9]+);/s", $val );
    	
    // Swop user inputted backslashes
		
    	
    $val = str_replace(  "&#092;","/\\\(?!&amp;#|\?#)/", $val ); 
 	
    return $val;
} 
?>