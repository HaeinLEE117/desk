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

    //--------------------------------------현재위치+목적지로 경로 업데이트(순서있음)---------------------------------------------------------------
        $combine_PN_DN = $combine_PN_DN.$PointNumber.$Destination;
        $combine_PN_DN = (int)$combine_PN_DN;


        //함수로 따로 정의하기 AGV 넘버 + conbine_pn_DN해서 AGV넘버 정보로 AGV_route배열 업데이트 해줄 것



        $query ="insert into agvlocation".$VehicleNumber."(Direction, PointNumber, Destination, Time)
        VALUES(1,
        $PointNumber,
        $Destination,
        now()
        )
        ";





        for($i = 0; $i<6; $i++){
            if($PointNumber = $start_point[$i]){
                $set_collision_flag = 1;
            }
        }
        if($set_collision_flag){
            $result_meesage = "<br>일치";
        }

          

    } 
    else{ 
        echo "mysql dodule connection error";
    } 
    //---------충돌 방지 코드 쓰기

echo ($result_meesage);
mysqli_close($mysqli); 
//enqueue 여기서 하기 (함수 리턴값ㄷ으로)
 array_push($agv_route[$VehicleNumber], get_route($combine_PN_DN));
// 입력 형식 echo $agv_route[$VehicleNumber][0][0] => 출럭 "306";

//=====================================================
//                      함수
//=====================================================


function set_collision($called_destination, $readed_RFOD){   //목적지 한쌍이 정상적으로 입력되었을 때 충돌이 예상되는 location point를 점령함 
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

function get_route($combined_No){

    switch ($combined_No) {
        case 101406:
            return "route 1";
            break;
        case 3062307:
            echo "worked <br>";
            return array(306,305,304,303,302,301,205,204,203,202,201);
            break;
        case 3072101:
            return "route 3";
            break;
        case 406101:
            return "route 4";
            break;
        case 2307306:
            return "route 5";
            break;
        case 2101307:
            return "route 6";
            break;
        default:
            echo "wrong route";
    }

    echo "<br>";


    /*
    array_push($agv_route[$AGV_No],$dic_collision_positionts[306]);
    
    echo $agv_route[$AGV_No][0];
    */
}

?>

