<?php

$connection = mysqli_connect('localhost','admini27_root','@ERPideas2019','admini27_erp');

$tables = array();
$result = mysqli_query($connection,"SHOW TABLES");
while($row = mysqli_fetch_row($result)){
  $tables[] = $row[0];
}

$return = '';
foreach($tables as $table){
  $result = mysqli_query($connection,"SELECT * FROM ".$table);
  $num_fields = mysqli_num_fields($result);
  
  $return .= 'DROP TABLE '.$table.';';
  $row2 = mysqli_fetch_row(mysqli_query($connection,"SHOW CREATE TABLE ".$table));
  $return .= "\n\n".$row2[1].";\n\n";
  
  for($i=0;$i<$num_fields;$i++){
    while($row = mysqli_fetch_row($result)){
      $return .= "INSERT INTO ".$table." VALUES(";
      for($j=0;$j<$num_fields;$j++){
        $row[$j] = addslashes($row[$j]);
        if(isset($row[$j])){ $return .= '"'.$row[$j].'"';}
        else{ $return .= '""';}
        if($j<$num_fields-1){ $return .= ',';}
      }
      $return .= ");\n";
    }
  }
  $return .= "\n\n\n";
}

//save file
date_default_timezone_set('America/Mexico_City');
$handle = fopen("backup ".date("d-m-Y h:i:s").".sql","w+");
fwrite($handle,$return);
fclose($handle);
echo "Successfully backed up";
$path = "/var/www/repos/$_POST[project]"; 
$a='';
chdir($path);
exec("git add .");  
exec("git commit -m'message'");
?>

