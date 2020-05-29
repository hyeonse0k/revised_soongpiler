<?php
$route = "C:\Bitnami\wampstack-7.3.18-0\apache2\htdocs\Controller/result/";
$data = $_POST['photo'];
list($type, $data) = explode(';', $data);
list(, $data)      = explode(',', $data);
$data = base64_decode($data);
$class = $_POST['class'];
$count = $_POST['count_value'];
if(!$class){ #class에 아무런 입력도 없이 캡쳐 시 exception return 관리 해줘야 한다
  throw new exception('no class value');
}

$save_route = $route.$class."/";
if(!is_dir($save_route)){
  mkdir($save_route);
}
file_put_contents($save_route.$class."_".time()."_".$count.'.png', $data);
if(!is_file("./result/snapshot.txt")){
  $file=fopen("./result/snapshot.txt", "w");
}
else{
  $file=fopen("./result/snapshot.txt", "a+");
}
$text = $class."_".time()."_".$count.".png\n";
fwrite($file,$text);
fclose($file);
#$res_file = fopen("./result/result.txt","r");
#while(!feof($file)){
#  $str = fgets($res_file);
#}
$result = array("result" => "hello");
echo json_encode($result);
#fclose($res_file);
die;
?>
