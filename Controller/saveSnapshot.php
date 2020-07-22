<?php
  $python_route = "C:\Users\hyunseok\AppData\Local\Programs\Python\Python38\python.exe ";
  $obj_route = "C:\Bitnami\wampstack-7.3.18-0\apache2\htdocs\\validation.py";
  $route = "C:\Bitnami\wampstack-7.3.18-0\apache2\htdocs\Controller/result/";
  $data = $_POST['photo'];
  list($type, $data) = explode(';', $data);
  list(, $data)      = explode(',', $data);
  $data = base64_decode($data);
  $class = $_POST['class'];
  $count = $_POST['count_value'];
  $save_route = $route.$class."/";
  if(!is_dir($save_route)){
    mkdir($save_route);
  }
  $file_name = $class."_".time()."_".$count.'.png';
  file_put_contents($save_route.$file_name, $data);
  if(!is_file("./result/snapshot.txt")){
    $file=fopen("./result/snapshot.txt", "w");
  }
  else{
    $file=fopen("./result/snapshot.txt", "a+");
  }
  $text = $file_name."\n";
  fwrite($file,$text);
  fclose($file);
  if($count == 1){
    sleep(2);
  }
  #txt 파일 마지막 찾는 코드
  $file=fopen("./result/result.txt", "r");
  $line_count = 0;
  while($count > $line_count){
    $str = fgets($file, 999);
    $line_count += 1;
  }
  #$result = array('result' => $str);
  fclose($file);
  $res_line = (int)$str;
  $label_file = fopen("./photos/class_label.txt","r");
  $line_count = 0;
  while($line_count <= $res_line){
    $ans = fgets($label_file,999);
    $line_count += 1;
  }
  $result = array('result' => $ans);
  echo json_encode($result);
  die;
?>
