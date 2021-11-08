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


//---------------------------------MAIN()-----------------------------------
if($PointNumber == $Destination){
    driving_end($VehicleNumber); //(0)
}else{
    if(is_setted_route($VehicleNumber, $PointNumber)){ //(1)
        tracking_route($VehicleNumber, $PointNumber); //(2)-2
    }else{ //기존에 입력된 경로가 없는 경우 목적지 설정 후 주행. 출발지, 목적지 오류는 무시
        check_start_point($PointNumber); //(2)-1
        set_collision($VehicleNumber, $PointNumber, $Destination); //(3)
    }
}


mysqli_close($mysqli); 

//=====================================================
//                      함수
//=====================================================

function driving_end($VN){//(0) .주행 완료 판단 후 경로 0 설정
    //point부분 0으로 설정하는 거 추가 할 것 -------------------------------------------------------------------------
    $mysqli = new mysqli('localhost', 'root', '1234', 'agv_monitor'); 
    $query = "update agvs_info set route='0' where VehicleNumber = ".$VN;
    $result = mysqli_query($mysqli,$query);

}


function is_setted_route($VN, $PN){ //(1)기존에 설정된 경로가 있는지 check
$mysqli = new mysqli('localhost', 'root', '1234', 'agv_monitor'); 
$query = "select route from agvs_info WHERE VehicleNumber = ".$VN;
$result = mysqli_query($mysqli,$query);

    if($result){
        $row = mysqli_fetch_array($result);
        if($row[0]==NULL){ //NULL인 경우 여기 출력 VN조회 오류 => error_log tale 기록
            $result_2 = mysqli_query($mysqli,"insert into error_log(code,message,time) values('1101','VN_no_data',now())");
            if($result_2){
            return false;
            }else{
                echo "SQL ERROR";
            }
        }elseif($row[0]){ //=> 경로 따라가는 함수 실행
            return true;
        }else{ //0으로 설정된 경우 여기 출력 => 경로 새로 설정
            return false;
        }
    }
}


function check_start_point($PN){//(2)-1 정상적으로 출발지에서 출발하는지
    $mysqli = new mysqli('localhost', 'root', '1234', 'agv_monitor'); 
    echo "새로운 경로를 설정합니다".$PN;
    $start_point = array(101,406,306,2307,307,2101);
    $flag = 0;2
    for($i = 0; $i<6; $i++){
        if($start_point[$i]==$PN){
            $flag = 1;
        }
    }
    if(!$flag){
        $result_2 = mysqli_query($mysqli,"insert into error_log(code,message,time) values('1102','start_point_error',now())");
        return false;
    }
    return flag;
}


function tracking_route($VN, $PN){//(2)-2 경로 따라가는 함수 작성
    echo "기존 경로를 따라갑니다.";
}

function set_collision($VN, $PN, $DN){   //목적지 한쌍이 정상적으로 입력되었을 때 충돌이 예상되는 location point를 점령함 
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

