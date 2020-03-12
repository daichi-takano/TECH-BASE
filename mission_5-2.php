<html>
<head>
<meta charaset="UTF-8">
</head>
<body>

<?php
	//データベースへの接続
	$dsn = 'データベース名';
	$user = 'ユーザー名';
	$password = 'パスワード';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

	//テーブルの作成
	$sql = "CREATE TABLE IF NOT EXISTS mission5"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "pass INT(32),"
	. "time TEXT"
	.");";
	$stmt = $pdo->query($sql);

	//変数への代入
	$name ="";
	$comment ="";
	$pass ="";
	

	//日付データを取得して変数に代入
	$time = date("Y年m月d日 H:i:s");

	//投稿機能
	if (!empty($_POST['name']) && !empty($_POST['comment']) && empty($_POST['editNO'])) {//空っぽじゃなかったら

		$name = $_POST['name'];
		$comment = $_POST['comment'];
		$pass = $_POST['pass'];

		$sql = $pdo -> prepare("INSERT INTO mission5 (name, comment, time, pass) VALUES (:name, :comment, :time, :pass)");
		$sql -> bindParam(':name', $name, PDO::PARAM_STR);
		$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
		$sql -> bindParam(':time', $time, PDO::PARAM_STR);
		$sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
		$sql -> execute();
	}

	//削除機能
	if(!empty($_POST['dnum'])){//空っぽじゃなかったら

		$dnum = $_POST['dnum'];
		$delpass = ($_POST['delpass']);

		$sql = 'SELECT * FROM mission5';
		$stmt = $pdo->query($sql);
		$results = $stmt->fetchAll();

		foreach ($results as $row){

			if($row['pass'] == $delpass){
				$id = $dnum;
				$sql = 'delete from mission5 where id=:id';
				$stmt = $pdo->prepare($sql);
				$stmt->bindParam(':id', $id, PDO::PARAM_INT);
				$stmt->execute();
			}
		}
	}

	//編集機能
	if(!empty($_POST['name']) && !empty($_POST['comment']) && !empty($_POST['editNO'])){ //もし、編集フォームに入力されたら
		

		$name = $_POST['name'];
		$comment = $_POST['comment'];
		$pass = $_POST['pass'];

		$editNO = $_POST['editNO'];
		$editpass = $_POST['pass'];

		$sql = 'SELECT * FROM mission5';
		$stmt = $pdo->query($sql);
		$ret_array = $stmt->fetchAll();

		foreach ($ret_array as $newdata) {//配列の数だけループさせる
			
			if($newdata['pass'] == $editpass){
		
				$id = $editNO; //変更する投稿番号
				$sql = 'update mission5 set name=:name,comment=:comment where id=:id';
				$stmt = $pdo->prepare($sql);
				$stmt->bindParam(':name', $name, PDO::PARAM_STR);
				$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
				$stmt->bindParam(':id', $id, PDO::PARAM_INT);
				$stmt->execute();
			}
		}
	}

	//編集番号取得
	if (!empty($_POST['edit'])) {

		$edit = $_POST['edit'];
		$editpass = $_POST['editpass'];

		$sql = 'SELECT * FROM mission5';
		$stmt = $pdo->query($sql);
		$editCon = $stmt->fetchAll();

		foreach ($editCon as $line) {

			if ($edit == $line['id'] && $editpass == $line['pass']) {

				$editnumber = $line['id'];
				$editname = $line['name'];
				$editcomment = $line['comment'];
			}
		}
	}

?>

<投稿フォーム>
<form action="mission_5-2.php" method="post">
<input type="text" name="name" placeholder="名前"value="<?php if(!empty($editname)) echo $editname;?>"><br>
<input type="text" name="comment" placeholder="コメント"value="<?php if(!empty($editcomment)) echo $editcomment;?>"><br>
<input type="hidden" name="editNO" value="<?php if(!empty($editnumber)) echo $editnumber;?>">

<input type="text" name="pass" placeholder="パスワード" >
<input type="submit" name="submit" value="送信">
</form>

<削除フォーム>
<form action="mission_5-2.php" method="post">
<input type="text" name="dnum" placeholder="削除対象番号" ></br>
<input type="text" name="delpass"  placeholder="パスワード" >
<input type="submit" name="delete" value="削除">
</form>

<編集フォーム>
<form action="mission_5-2.php" method="post">
<input type="text" name="edit" placeholder="編集対象番号"></br>
<input type="text" name="editpass" placeholder="パスワード">
<input type="submit"name="send_edit" value="編集">
</form>


<?php
	//データの表示
	$sql = 'SELECT * FROM mission5';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $koko){
		//$rowの中にはテーブルのカラム名が入る
		echo $koko['id'].',';
		echo $koko['name'].',';
		echo $koko['comment'].',';
		echo $koko['time'].'<br>';
	echo "<hr>";
	}
?>
</body>
</html>
