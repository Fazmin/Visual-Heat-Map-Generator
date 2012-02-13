<?php
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