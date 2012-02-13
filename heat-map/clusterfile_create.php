<?php
/*************** Heat Map Generator v1.1*********************
(c) Fazmin 2011. All Rights Reserved
Usage: This script can be used FREE of charge for any commercial or personal projects. Enjoy!
Limitations:
- This script cannot be sold.
- This script should have copyright notice intact. Dont remove it please...
- This script may not be provided for download except from its original site.

For further usage, please contact me.

***********************************************************/

$filename=$_POST['filename'];
$data=$_POST['data'];
$line=$_POST['line'];

if(isset($data)){
	//echo $line;
	//$line=preg_replace('/"/','',$line);
	$file=fopen('uploads/clustered/c-'.$filename,'w+');

	//fwrite($file,','.$line."\n");
	fwrite($file,$data);
	fclose($file);
echo 'uploads/clustered/c-'.$filename;
//echo 
} else { echo "WRONG !!!"; }




?>