<?php
			$dsn = 'mysql:dbname=tb210403db;host=localhost';
			$user = 'tb-210403';
			$password = 'xShUdhZpyS';
			$pdo = new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
			//��O�����𓊂���i�X���[�j�悤�ɂ���




	//�e�[�u���̍쐬





		$sql = "CREATE TABLE IF NOT EXISTS member" //�e�[�u�����ɗ\���A�n�C�t�����g�p�ł��Ȃ�
		." ("
		. "id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,"
		. "account VARCHAR(50) NOT NULL,"
		. "mail VARCHAR(50) NOT NULL,"
		. "password VARCHAR(128) NOT NULL,"
		. "flag TINYINT(1) NOT NULL DEFAULT 1"
		.")ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;";
		$stmt = $pdo->query($sql);




//date�̃J�����ǉ�
	//$sql = "alter table tbtest add date datetime";
	//$stmt = $pdo->query($sql);

//�e�[�u���ꗗ��\������R�}���h���g���č쐬���o�������m�F����B
	$sql ='SHOW TABLES';
	$result = $pdo -> query($sql);
	foreach ($result as $row){
		echo $row[0];
		echo '<br>';
	}
	echo "<hr>";


//�e�[�u���̒��g���m�F����R�}���h���g���āA�Ӑ}�������e�̃e�[�u�����쐬����Ă��邩�m�F����

	$sql ='SHOW CREATE TABLE pre_member';
	$result = $pdo -> query($sql);
	foreach ($result as $row){
		echo $row[1];
	}
	echo "<hr>";




	$sql ='SHOW CREATE TABLE member';
	$result = $pdo -> query($sql);
	foreach ($result as $row){
		echo $row[1];
	}
	echo "<hr>";



$sql ='SHOW CREATE TABLE preserve';
	$result = $pdo -> query($sql);
	foreach ($result as $row){
		echo $row[1];
	}
	echo "<hr>";



//�e�[�u���m�F
try {
	$sql = 'SELECT * FROM member';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$row�̒��ɂ̓e�[�u���̃J������������
		echo $row['id'].',';
		echo $row['account'].',';
		echo $row['mail'].',';
		echo $row['password'].',';
		echo $row['flag'].'<br>';
		echo "<hr>";
	}
} catch (\Exception $e) {
	print $e->getMessage();
}


//���o�^�e�[�u�^�\��
try {
	$sql = 'SELECT * FROM pre_member';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$row�̒��ɂ̓e�[�u���̃J������������
		echo $row['id'].',';
		echo $row['urltoken'].',';
		echo $row['mail'].',';
		echo $row['date'].',';
		echo $row['flag'].'<br>';
		echo "<hr>";
	}
} catch (\Exception $e) {
	print $e->getMessage();
}
?>
