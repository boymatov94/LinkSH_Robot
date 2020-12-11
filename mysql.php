<?php
function connect()
  
{
  $host = 'localhost';
    $db   = 'root'; // Имя БД
    $user = 'test';  // Имя пользователя БД
    $pass = 'pass'; // Пароль БД
    $charset = 'utf8';
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $opt = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
  try {
    $pdo = new PDO($dsn, $user, $pass, $opt);
  
    return $pdo;
} catch (PDOException $e) {
    echo 'Подключение не удалось: ' . $e->getMessage();
    return false;
   // die('Подключение не удалось: ' . $e->getMessage());
}
}



function addUser($uid,$username,$first_name)
{
	  
		$pdo = connect();
		if ($pdo)
		{
			
			//users
			$stmt = $pdo->prepare('SELECT id FROM users WHERE id = ?');
			$stmt->execute([$uid]);

			if ($stmt->rowCount() == 0) // юзера нет - пишем
			{
		
          $data = array("id" => $uid,"username" => $username,"first_name" => $first_name, "date_begin" => gmdate("d.m.Y"));
					$st = $pdo->prepare("INSERT INTO  users (id, username, first_name, status, date_begin) VALUES (:id, :username, :first_name, 0, :date_begin)");
					$st->execute($data);
          account_init($uid);// Инициализация баланса
			}
		} 
}

function MessageSave($data)
{
		$pdo = connect();
		if ($pdo)
		{

			$st = $pdo->prepare("INSERT INTO  messages (user_id, username, first_name, message_id, text, date, ndate)
				VALUES (:user_id, :username, :first_name, :message_id, :text, :date, :ndate)");
			$st->execute($data);				 

			return true;
			
		}
	else{ return false;}
}

function userget($uid)
{
	$pdo = connect();
		if ($pdo)
		{
			$stmt = $pdo->prepare('SELECT username, first_name, status FROM users WHERE id = ?');
			$stmt->execute([$uid]);
			$stat = [];
			foreach ($stmt as $row)
				{
						$stat['username'] = $row['username'];
						$stat['first_name'] = $row['first_name'];
						$stat['status'] = $row['status'];
				}
			return $stat;
		}
	return false;
}

function getUsers($table)
{
  $i = 0;
  $pdo = connect();
    if ($pdo)
    {
    $stmt = $pdo->prepare("SELECT distinct(id) FROM {$table}");
    $stmt->execute();
    foreach ($stmt as $row) {
          $arr[$i] = $row['id'];
          $i ++; 
    }  
    return $arr;
    }
}

function getmess($tables)
{
  $i = 0;
  $pdo = connect();
    if ($pdo)
    {
    $stmt = $pdo->prepare("SELECT distinct(id) FROM {$tables}");
    $stmt->execute();
    foreach ($stmt as $row) {
          $arr[$i] = $row['id'];
          $i ++; 
    } 
    return $arr;
    }
}

?>
