<video id="player" controls autoplay></video>
<button id="capture">Capture</button>
<button id="done">Done</button>
<button id="MLstart">Machine Learning Start</button>
<canvas id="snapshot" width=240 height=240></canvas>
<script>
  var player = document.getElementById('player');
  var snapshotCanvas = document.getElementById('snapshot');
  var captureButton = document.getElementById('capture');
  var saveButton = document.getElementById('save');
  var isPressed = false;
  var old_v = "null";
  var count = 0;
  var snap_count = 0;

  //유저로부터 일정시간마다 스냅샷을 받아오기 위한 var
  var doneButton = document.getElementById('done');
  var doneSuccess = function(stream){
    player.srcObject = stream;
  }
  //ML 학습을 시작하기 위한 var
  var startButton = document.getElementById('MLstart');
  function startML(action){
    var memo = "start";
    $.ajax({
      method: 'POST',
      url: 'ml_start.php',
      data: memo
    });
  }
  startButton.addEventListener('click',function(event){
    startML();
  })

  function autoSnapshot(action){
    var auto_context = snapshot.getContext('2d');
    auto_context.drawImage(player, 0, 0, snapshotCanvas.width,snapshotCanvas.height);
    var auto_photo = snapshot.toDataURL('image/jpeg');
    var auto = "auto";
    var auto_parameter = {photo: auto_photo , class: auto, count_value: snap_count}
    var result;
    snap_count++;
    $.ajax({
      method: 'POST',
      url: 'saveSnapshot.php',
      async: false,
      data: auto_parameter,
    }).done(function(json) {
      jsonObj = JSON.parse(json);
      result = jsonObj['result'];
    });
    setTimeout(function() {
    autoSnapshot(action);
  }, 1000);
  $('#result').val(result);
  }

  doneButton.addEventListener('click',function(event){
    navigator.mediaDevices.getUserMedia({video: true})
        .then(doneSuccess);
    autoSnapshot();
  })

  var handleSuccess = function(stream) {
    player.srcObject = stream;
  };
  captureButton.addEventListener('mouseup', function(event){
    isPressed = false;
  });
  captureButton.addEventListener('mousedown', function(event){
    isPressed = true;
    doInterval();
  });
  function doInterval(action){
    if(isPressed)
    {
      var context = snapshot.getContext('2d');
      context.drawImage(player, 0, 0, snapshotCanvas.width,
          snapshotCanvas.height);
      var v = $('#class').val()
      if(v != old_v)
        count = 0;
      old_v = v;
      count += 1;

      var photo = snapshot.toDataURL('image/jpeg');
      var parameter = {photo: photo , class: v, count_value: count}
      $.ajax({
        method: 'POST',
        url: 'saveCapture.php',
        data: parameter
      });
      setTimeout(function() {
      doInterval(action);
    }, 200);
    }
  }
  navigator.mediaDevices.getUserMedia({video: true})
      .then(handleSuccess);
</script>

<html>
<head>
  <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
</head>
  <body>
    <a href="tensorboard.php">텐서보드 서버 실행</a>
    <a href="javascript:void(window.open('http://localhost:6006', '_blank'))">TensorBoard</a>
    <form method="POST" action="inputtxt.php">
      <input type="text" name="kind" value="Conv2D" />
      filters:<input type="text" name="var1" />
      kernel_size:<input type="text" name="var2" />
      <input type="submit" value="생성" />
      class: <input type="text" name="class" id="class"/>
      result: <input type="text" name="result" id="result"/>
    </form>
    <form method="POST" action="inputtxt.php">
      <input type="text" name="kind" value="MaxPooling2D"/>
      pool_size:<input type="text" name="var1" />
      <input type="submit" value="생성" />
    </form>
    <form method="POST" action="deltxt.php">
      <input type="submit" value="삭제" />
    </form>
    <div>
      <?php
          $file=fopen("param.txt", "r");
          (int)$size = 240;
          $option = 0;
          while(!feof($file)){
            $str = fgets($file, 999);
            $str = trim($str);
            $arr = explode(" ", $str);
            if(!strcmp($arr[0], "Conv2D")){
              $channel = $arr[1];
              $size = $size - $arr[2] + 1;
              $option = 1;
            }
            else if(!strcmp($arr[0], "MaxPooling2D")){
              $size = floor($size / $arr[1]);
              $option = 2;
            }
            if($arr[1] != 0){
              echo "<img src="."\"createBox.php?size=".$size."&channel=".$channel."&option=".$option."\" />";
            }
          }
          fclose($file);
      ?>
    </div>
  </body>
</html>
