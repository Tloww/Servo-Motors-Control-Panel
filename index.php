<?php
$servername="localhost";
$username="root";
$password="";
$dbname="controlpositions";
$conn=new mysqli($servername,$username,$password,$dbname);
if($conn->connect_error){die("Connection failed: ".$conn->connect_error);}

if($_SERVER["REQUEST_METHOD"]=="POST"){
  if(isset($_POST['save'])){
    $s1=max(0,min(180,(int)$_POST['servo1']));
    $s2=max(0,min(180,(int)$_POST['servo2']));
    $s3=max(0,min(180,(int)$_POST['servo3']));
    $s4=max(0,min(180,(int)$_POST['servo4']));
    $stmt=$conn->prepare("INSERT INTO positions (servo1,servo2,servo3,servo4) VALUES (?,?,?,?)");
    $stmt->bind_param("iiii",$s1,$s2,$s3,$s4);
    $stmt->execute();
    $stmt->close();
  }
  if(isset($_POST['remove'])){
    $id=(int)$_POST['id'];
    $conn->query("DELETE FROM positions WHERE ID=$id");
    header("Location: index.php");
    exit;
  }
  if(isset($_POST['load'])){
    $id=(int)$_POST['id'];
    $res=$conn->query("SELECT * FROM positions WHERE ID=$id");
    if($res && $res->num_rows){ $loaded=$res->fetch_assoc(); }
  }
  if(isset($_POST['submit'])){
    $s1=(int)$_POST['servo1'];
    $s2=(int)$_POST['servo2'];
    $s3=(int)$_POST['servo3'];
    $s4=(int)$_POST['servo4'];
    $url="http://192.168.4.1/control?s1=$s1&s2=$s2&s3=$s3&s4=$s4";
    @file_get_contents($url);
    file_put_contents("lastAngles.txt","$s1,$s2,$s3,$s4");
    $success="Angles submitted successfully!";
  }
}

$result=$conn->query("SELECT * FROM positions ORDER BY ID DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Servo Motors Control Panel</title>
<meta name="viewport" content="width=device-width, initial-scale=1" />
<style>
  :root{
    --bg1:#1a001f; --bg2:#0d0d0d;
    --box:#2b0a4a; --box2:#3a0f63;
    --line:#4e2b77; --white:#fff; --black:#000;
    --success:#28a745;
  }
  *{box-sizing:border-box}
  body{margin:0; font-family:Segoe UI,Arial,sans-serif; color:var(--white);
    background:linear-gradient(135deg,var(--bg1),var(--bg2));}
  .container{width:100%; max-width:1300px; margin:0 auto; padding:28px;
    display:grid; grid-template-columns:380px 1fr; gap:28px; align-items:stretch;
    height:calc(100vh - 56px);}
  .panel,.positions{background:var(--box); border:1px solid var(--line);
    border-radius:18px; box-shadow:0 14px 32px rgba(0,0,0,.35); padding:20px; height:100%}
  .panel{display:flex; flex-direction:column; gap:16px}
  .panel h2,.positions h2{margin:0 0 8px}
  .stack{display:flex; flex-direction:column; gap:14px; flex:1}
  .section{background:var(--box2); border:1px solid var(--line); border-radius:14px; padding:14px}
  .section label{display:flex; justify-content:space-between; margin-bottom:10px; font-weight:700}
  input[type=range]{width:100%}
  .buttons{margin-top:auto; display:flex; flex-direction:column; gap:10px}
  .btn{width:100%; background:var(--black); color:var(--white); border:none; border-radius:12px; padding:14px; font-weight:800; cursor:pointer; transition:.2s}
  .btn:hover{filter:brightness(1.12)}
  .positions-inner{background:var(--box2); border:1px solid var(--line); border-radius:14px; padding:14px; height:calc(100% - 46px); display:flex; flex-direction:column}
  table{width:100%; border-collapse:collapse; overflow:hidden; border-radius:12px; border:1px solid var(--line); background:rgba(0,0,0,.12)}
  th,td{padding:12px; text-align:center; border-bottom:1px solid var(--line)}
  th{background:rgba(0,0,0,.25)}
  tr:hover td{background:rgba(0,0,0,.15)}
  .act{display:flex; justify-content:center; gap:8px}
  .chip{background:var(--black); color:var(--white); border:none; border-radius:10px; padding:8px 14px; font-weight:700; cursor:pointer}
  .chip:hover{filter:brightness(1.12)}
  .success-box{background:var(--success); padding:10px; border-radius:10px; text-align:center; font-weight:700}
  @media (max-width:1000px){.container{grid-template-columns:1fr; height:auto} .positions{min-height:520px}}
</style>
</head>
<body>
  <div class="container">
    <div class="panel">
      <h2>Servo Motors Control Panel</h2>
      <div class="stack">
        <div class="section">
          <label>Servo 1 Angle: <span id="v1"><?= isset($loaded)?(int)$loaded['servo1']:90 ?>°</span></label>
          <input type="range" id="s1" min="0" max="180" value="<?= isset($loaded)?(int)$loaded['servo1']:90 ?>" oninput="v1.textContent=this.value+'°'">
        </div>
        <div class="section">
          <label>Servo 2 Angle: <span id="v2"><?= isset($loaded)?(int)$loaded['servo2']:90 ?>°</span></label>
          <input type="range" id="s2" min="0" max="180" value="<?= isset($loaded)?(int)$loaded['servo2']:90 ?>" oninput="v2.textContent=this.value+'°'">
        </div>
        <div class="section">
          <label>Servo 3 Angle: <span id="v3"><?= isset($loaded)?(int)$loaded['servo3']:90 ?>°</span></label>
          <input type="range" id="s3" min="0" max="180" value="<?= isset($loaded)?(int)$loaded['servo3']:90 ?>" oninput="v3.textContent=this.value+'°'">
        </div>
        <div class="section">
          <label>Servo 4 Angle: <span id="v4"><?= isset($loaded)?(int)$loaded['servo4']:90 ?>°</span></label>
          <input type="range" id="s4" min="0" max="180" value="<?= isset($loaded)?(int)$loaded['servo4']:90 ?>" oninput="v4.textContent=this.value+'°'">
        </div>
      </div>
      <?php if(isset($success)): ?>
        <div class="success-box"><?= $success ?></div>
      <?php endif; ?>
      <form method="post" onsubmit="syncHidden()">
        <input type="hidden" name="servo1" id="h1">
        <input type="hidden" name="servo2" id="h2">
        <input type="hidden" name="servo3" id="h3">
        <input type="hidden" name="servo4" id="h4">
        <div class="buttons">
          <button type="button" class="btn" onclick="resetAll()">Reset to 90°</button>
          <button type="submit" name="save" class="btn">Save Position</button>
          <button type="submit" name="submit" class="btn">Submit to ESP</button>
        </div>
      </form>
    </div>
    <div class="positions">
      <h2>Saved Positions</h2>
      <div class="positions-inner">
        <table>
          <tr><th>ID</th><th>Servo 1</th><th>Servo 2</th><th>Servo 3</th><th>Servo 4</th><th>Action</th></tr>
          <?php if($result && $result->num_rows): ?>
            <?php while($row=$result->fetch_assoc()): ?>
              <tr>
                <td><?= $row['ID'] ?></td>
                <td><?= (int)$row['servo1'] ?></td>
                <td><?= (int)$row['servo2'] ?></td>
                <td><?= (int)$row['servo3'] ?></td>
                <td><?= (int)$row['servo4'] ?></td>
                <td>
                  <div class="act">
                    <form method="post">
                      <input type="hidden" name="id" value="<?= $row['ID'] ?>">
                      <button class="chip" name="load">Load</button>
                    </form>
                    <form method="post" onsubmit="return confirm('Delete this position?')">
                      <input type="hidden" name="id" value="<?= $row['ID'] ?>">
                      <button class="chip" name="remove">Remove</button>
                    </form>
                  </div>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="6">No saved positions.</td></tr>
          <?php endif; ?>
        </table>
      </div>
    </div>
  </div>
<script>
function resetAll(){[s1,s2,s3,s4].forEach(e=>e.value=90);[v1,v2,v3,v4].forEach(l=>l.textContent='90°')}
function syncHidden(){h1.value=s1.value;h2.value=s2.value;h3.value=s3.value;h4.value=s4.value}
</script>
</body>
</html>
