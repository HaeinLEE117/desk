    <?php
    date_default_timezone_set('Asia/Seoul');
    $conn = mysqli_connect(
    'localhost', // 주소
    'root',
    '1234',
    'agv_monitor');

    $filtered = array(
    'VehicleNumber'=>mysqli_real_escape_string($conn, $_GET['VehicleNumber']),
    'PointNumber'=>mysqli_real_escape_string($conn, $_GET['PointNumber']),
    'Destination'=>mysqli_real_escape_string($conn, $_GET['Destination']),
    'Product'=>mysqli_real_escape_string($conn, $_GET['Product'])
    );

    $sql = "insert into agvlocation".$filtered['VehicleNumber']."(Direction, PointNumber, Destination, Time)
        VALUES(1,
        {$filtered['PointNumber']},
        {$filtered['Destination']},
        now()
        )
    ";


$result = mysqli_query($conn, $sql);
if($result === false){
  echo '위치 정보를 저장하는 과정에서 문제가 생겼습니다. 데이터 입력에 오류가 없는지 확인해주세요 <br/> ';
  error_log(mysqli_error($conn));
} else {
  echo 'agv_location 정보 업데이트 성공했습니다. <br/> ';
}

$sql = "update agvs_info set product = ".$filtered['Product'] ." where VehicleNumber = ".$filtered['VehicleNumber'];

$result = mysqli_query($conn, $sql);
if($result === false){
  echo 'product를 저장하는 과정에서 문제가 생겼습니다. 데이터 입력에 오류가 없는지 확인해주세요 <br/> <a href="QR_input_page.html">돌아가기</a>';
  error_log(mysqli_error($conn));
} else {
  echo 'product 정보 업데이트 성공했습니다. <br/> <a href="QR_input_page.html">돌아가기</a>';
  echo set_collision(1,3);
}

function set_collision($called_destination, $readed_RFOD){

  return "true";
}
?>

