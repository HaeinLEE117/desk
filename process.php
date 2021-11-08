<?php 
header("Content-Type: text/html;charset=UTF-8"); 
$result_meesage;
$host = 'localhost'; 
$user = 'root'; 
$pw = '1234'; 
$dbName = 'agv_monitor'; 

$mysqli = new mysqli($host, $user, $pw, $dbName); 

    if($mysqli){ 

        $VehicleNumber = $_GET['VehicleNumber'];  //$VehicleNumber
        $PointNumber = $_GET['PointNumber'];  //$PointNumber
        $Destination = $_GET['Destination'];  //$Destination
        $Product = $_GET['Product'];  //$Product

        $query ="insert into agvlocation".$VehicleNumber."(Direction, PointNumber, Destination, Time)
        VALUES(1,
        $PointNumber,
        $Destination,
        now()
        )
    ";

        $result = mysqli_query($mysqli,$query); 
        if($result === false){
            $result_meesage = "0";
          } else {
            $sql = "update agvs_info set product = ".$Product." where VehicleNumber = ".$VehicleNumber;
            $result = mysqli_query($mysqli, $sql);
  if($result === false){
      $result_meesage = "0";
  } else {
      $result_meesage="1";
  }
          
          }


$result_meesage = $result_meesage."1";
} 
    else{ 

      
    } 
    //---------충돌 방지 코드 쓰기
    // AGV stop code
    //  $result_meesage = $result_meesage."0";
echo json_encode($result_meesage);
mysqli_close($mysqli); 

?>

