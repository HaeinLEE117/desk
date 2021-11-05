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

//DB연결 
$mysqli = new mysqli('localhost', 'root', '1234', 'agv_monitor'); 


//html 에서 넘어온 정보 파싱
$VehicleNumber = $_GET['VehicleNumber'];  //$VehicleNumber
$PointNumber = $_GET['PointNumber'];  //$PointNumber
$Destination = $_GET['Destination'];  //$Destination
$Product = $_GET['Product'];  //$Product

//(1)

is_setted_route($VehicleNumber, $PointNumber);





//-------------------------------------------------이전 프로그램--------------------------------------------------------------------------//

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




        $query ="insert into agvlocation".$VehicleNumber."(Direction, PointNumber, Destination, Time)
        VALUES(1,                  
        $PointNumber,
        $Destination,
        now()
        )
        ";



          


    //---------충돌 방지 코드 쓰기

//enqueue 여기서 하기 (함수 리턴값ㄷ으로)
// 입력 형식 echo $agv_route[$VehicleNumber][0][0] => 출럭 "306";

mysqli_close($mysqli); 



//=====================================================
//                      함수
//=====================================================


function is_setted_route($VN, $PN){ //(1)기존에 설정된 경로가 있는지 check
$mysqli = new mysqli('localhost', 'root', '1234', 'agv_monitor'); 

    $query = "select route from agvs_info WHERE VehicleNumber = ".$VN;
    echo $query;
    $result = mysqli_query($mysqli,$query);
    if($result){
        echo "resulteed</br>";
        $row = mysqli_fetch_array($result);
        if($row[0]==NULL){ //NULL인 경우 여기 출력 VN조회 오류 => error_log tale 기록
            echo "null find";
            return false;
        }elseif($row[0]){ //=> 경로 따라가는 함수 실행
            echo "경로설정됨";
            return true;
        }else{ //0으로 설정된 경우 여기 출력 => 경로 새로 설정
            echo "find nothing";
            return false;
        }
        /*
        if($row[0]){
            echo $row[0];
            return true;
        }else{
            echo "no data";
            return false;
        }
    }else{
        echo "insert into error_log(code,message,time) values('1101','VN_no_data',now())";
        return false;
    }
    */
}
}


function check_start_point($PN){//(2)-1 정상적으로 출발지에서 출발하는지
    $start_point = array(101,406,306,2307,307,2101);
    $flag = 0;
    for($i = 0; $i<6; $i++){
        if($start_point[$i]==$PN){
            $flag = 1;
        }
    }
    return flag;
}


function tracking_route($VN, $PN){//(2)-2 경로 따라가는 함수 작성

}

function set_collision($called_destination, $readed_RFID){   //목적지 한쌍이 정상적으로 입력되었을 때 충돌이 예상되는 location point를 점령함 
    $dic_destination_positiont = array('101'=>'406', '306'=>'2307', '307'=>'2101',
    '406'=>'101', '2307'=>'306','2101'=>'307');



    if($readed_RFID == $dic_destination_positiont[$called_destination]){

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

