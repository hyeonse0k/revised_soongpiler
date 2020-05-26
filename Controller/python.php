<video id="player" controls autoplay></video>
<button id="capture">Capture</button>
<button id="done">Done</button>
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

  function autoSnapshot(action){
    var auto_context = snapshot.getContext('2d');
    auto_context.drawImage(player, 0, 0, snapshotCanvas.width,snapshotCanvas.height);
    var auto_photo = snapshot.toDataURL('image/jpeg');
    var auto = "auto";
    var auto_parameter = {photo: auto_photo , class: auto, count_value: snap_count}
    snap_count++;
    console.log(snap_count);
    $.ajax({
      method: 'POST',
      url: 'testSave.php',
      data: auto_parameter
    });
    setTimeout(function() {
    autoSnapshot(action);
  }, 1000);
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

      console.log(v,count);
      var photo = snapshot.toDataURL('image/jpeg');
      var parameter = {photo: photo , class: v, count_value: count}
      $.ajax({
        method: 'POST',
        url: 'testSave.php',
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
    <form method="POST" action="inputtxt.php">
      x:<input type="text" name="kind" />
      y:<input type="text" name="var1" />
      w:<input type="text" name="var2" />
      class:<input type="text" name="class" id="class"/>
      <input type="submit" value="생성" />
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
              $channel = (int)$arr[1];
              $size = (int)$size - (int)$arr[2] + 1;
              $option = 1;
            }
            else if(!strcmp($arr[0], "max")){
              (int)$size = (int)$size / (int)$arr[1];
              $option = 2;
            }
            if($arr[1] != 0)
              echo "<img src="."\"createBox.php?size=".$size."&channel=".$channel."&option=".$option."\" />";
          }
          fclose($file);
      ?>
    </div>
  </body>
</html>
