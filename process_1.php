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
            $result_meesage = "{0";
          } else {
            $sql = "update agvs_info set product = ".$Product." where VehicleNumber = ".$VehicleNumber;
            $result = mysqli_query($mysqli, $sql);
            if($result === false){
                $result_meesage = "{0";
            } else {
                $result_meesage="{1";
            }
          
          }


    //          $result_meesage = $result_meesage."1}";


    //readed RFID가 특정 위치일 때 set_collision 호출 (출발지에서 함수를 호출함 )

        $start_point = array(101,406,306,2307,307,2101);
        $set_collision_flag = 0;

        for($i = 0; $i<6; $i++){
            if($PointNumber = $start_point[$i]){
                $set_collision_flag = 1;
            }
        }
        if($set_collision_flag){
            $result_meesage = set_collision($Destination, $PointNumber);
        }

          

    } 
    else{ 

      
    } 
    //---------충돌 방지 코드 쓰기
    // AGV stop code
    //  $result_meesage = $result_meesage."0";
echo json_encode($result_meesage);
mysqli_close($mysqli); 

//목적지 한쌍이 정상적으로 입력되었을 때 충돌이 예상되는 location point를 점령함 
function set_collision($called_destination, $readed_RFOD){
    $dic_destination_positiont = array('101'=>'406', '306'=>'2307', '307'=>'2101',
    '406'=>'101', '2307'=>'306','2101'=>'307');

    //출발지 별로 점령해야 하는 point number
    $dic_collision_positionts = array('101'=>array(307,306,305,304,303,302,301,205,204,203,202,201), 
    '306'=>array(306,305,304,303,302,301,205,204,203,202,201), 
    '307'=>array(307,306,305,304,303,302,301),
    '406'=>array(307,306,305,304,303,302,301,205,204,203,202,201), 
    '2307'=>array(306,305,304,303,302,301,205,204,203,202,201),
    '2101'=>array(307,306,305,304,303,302,301));

    echo $dic_collision_positionts[$readed_RFOD][1]."<br><br>";

    if($readed_RFOD == $dic_destination_positiont[$called_destination]){

        echo "matched";
        return true;
    }
    else{
        echo "unmatched";
        return false;}
}


?>

