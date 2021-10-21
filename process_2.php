<?php 
header("Content-Type: text/html;charset=UTF-8"); 
$result_meesage;
$host = 'localhost'; 
$user = 'root'; 
$pw = '1234'; 
$dbName = 'agv_monitor'; 
$dic_collision_positionts = array('101'=>array(307,306,305,304,303,302,301,205,204,203,202,201), 
'306'=>array(306,305,304,303,302,301,205,204,203,202,201), 
'307'=>array(307,306,305,304,303,302,301),
'406'=>array(307,306,305,304,303,302,301,205,204,203,202,201), 
'2307'=>array(306,305,304,303,302,301,205,204,203,202,201),
'2101'=>array(307,306,305,304,303,302,301));
//agv 별로 경로를 관리하는 다차원 배열
$agv_route = ['1'=>array(), '2'=>array(),'3'=>array(),'4'=>array(),
'5'=>array(),'6'=>array(),'7'=>array(),'8'=>array(),'9'=>array(),'10'=>array(),
'11'=>array()];
/* 이런 식으로 가져온 경로를 $agv_route배열에 붙여넣어서(enpueue) 사용. 주행하면서 앞에 부분부터 빼냄(dequeue)
array_push($agv_route[1],$dic_collision_positionts[306]);
echo $agv_route[1][0][0];
*/
$mysqli = new mysqli($host, $user, $pw, $dbName); 

    if($mysqli){ 

        $VehicleNumber = $_GET['VehicleNumber'];  //$VehicleNumber
        $PointNumber = $_GET['PointNumber'];  //$PointNumber
        $Destination = $_GET['Destination'];  //$Destination
        $Product = $_GET['Product'];  //$Product

    

    //readed RFID가 특정 위치일 때 set_collision 호출 (출발지에서 함수를 호출함 )
    $start_point = array(101,406,306,2307,307,2101);
    $set_collision_flag = 0;
    $combine_PN_DN = "";

    for($i = 0; $i<6; $i++){
        if($PointNumber == $start_point[$i]){
            $set_collision_flag = 1;
        }
    }

    //정상적으로 출발지에서 하는 경우
    if($set_collision_flag){
        //--------------------------------------현재위치+목적지로 루트 설정---------------------------------------------------------------
        $combine_PN_DN = $combine_PN_DN.$PointNumber.$Destination;

        //result_message 변수가 있는 경우 목적지 설정을 실시함.
        if(get_route($combine_PN_DN)){
            $query = "update agvs_route set route = '".get_route($combine_PN_DN)."',satrt_time = now() where VehicleNumber = ";
            $query = $query.$VehicleNumber;
            echo $query."<br>";


        }else{ // 오류가 있는 경우 VN PN DN 전송 
            $error_message = "VN: ".$VehicleNumber." PN: ".$PointNumber." DN: ".$Destination;
            $query ="insert into error_log(code, message, Time)
            VALUES(1001,'
            $error_message',
            now()
            )
            ";
            echo $query;
    
            $result = mysqli_query($mysqli,$query); 
            if($result){
                //에러 입력 후 작동될 코드 작성
              } else {
              }
        }

    }else{ //비정상적으로 출발지 외의 장소에서 출발하는 경우
        $error_message = "VN: ".$VehicleNumber." PN: ".$PointNumber." DN: ".$Destination;
        $query ="insert into error_log(code, message, Time)
        VALUES(1002,'
        $error_message',
        now()
        )
        ";
        echo $query;

        $result = mysqli_query($mysqli,$query); 
        if($result){
            //에러 입력 후 작동될 코드 작성
          } else {
          }
    }



        $query ="insert into agvlocation".$VehicleNumber."(Direction, PointNumber, Destination, Time)
        VALUES(1,
        $PointNumber,
        $Destination,
        now()
        )
        ";



          

    } 
    else{ 
        echo "mysql dodule connection error";
    } 
    //---------충돌 방지 코드 쓰기

//enqueue 여기서 하기 (함수 리턴값ㄷ으로)
// 입력 형식 echo $agv_route[$VehicleNumber][0][0] => 출럭 "306";

mysqli_close($mysqli); 
//=====================================================
//                      함수
//=====================================================


function set_collision($called_destination, $readed_RFOD){   //목적지 한쌍이 정상적으로 입력되었을 때 충돌이 예상되는 location point를 점령함 
    $dic_destination_positiont = array('101'=>'406', '306'=>'2307', '307'=>'2101',
    '406'=>'101', '2307'=>'306','2101'=>'307');



    if($readed_RFOD == $dic_destination_positiont[$called_destination]){

        echo "matched";
        return true;
    }
    else{
        echo "unmatched";
        return false;}
}

function get_route($combine_PN_DN){

    switch ($combine_PN_DN) {
        case 101406:
            return "route101406";
            break;
        case 3062307:
            return "route3062307";
            break;
        case 3072101:
            return "route3072101";
            break;
        case 406101:
            return "route406101";
            break;
        case 2307306:
            return "route2307306";
            break;
        case 2101307:
            return "route2101307";
            break;
        default:
        return "error";
            return 0;
    }

    echo "<br>";



}

?>

