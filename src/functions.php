<?
function h($str) {
  return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}


function post_message($pdo) {
  $stmt = $pdo->prepare(
    "INSERT INTO todos 
      (post_name, message, delete_flag, created_at, updated_at)
      VALUES (?, ?, 0, ?, ?)"
    );
  date_default_timezone_set('Asia/Tokyo');
  $stmt->execute([$_POST['name'], $_POST['message'], date("Y-m-d H:i:s"), date("Y-m-d H:i:s")]);
}


function get_messages($pdo) {
  $stmt = $pdo->query('SELECT * FROM todos ORDER BY id DESC');
  $messages = $stmt->fetchAll();
  return $messages;
}
function get_current_message($pdo) {
  $stmt = $pdo->query('SELECT * FROM todos WHERE id = (SELECT MAX(id) FROM todos)');
  $message = $stmt->fetch();
  $hash = [
    'id' => h($message->id),
    'name' => h($message->post_name),
    'message' => h($message->message),
    'time' => h(modify_datetime($message->created_at)),
    'importance' => h((string) $message->importance)
  ];
  header('Content-Type: application/json; charset=utf-8');
  echo(json_encode($hash));
}

function delete_message($pdo) {
  $stmt = $pdo->prepare(
    "UPDATE todos SET delete_flag = 1, updated_at = :now WHERE id = :id"
  );
  date_default_timezone_set('Asia/Tokyo');
  $stmt->execute([
    'now' => date("Y-m-d H:i:s"),
    'id' => $_POST["id"]
  ]);
}

function change_importance($pdo) {
  $stmt = $pdo->prepare(
    "UPDATE todos SET importance = (importance + 1) % 3 WHERE id = :id"
  );
  $stmt->execute([
    'id' => $_POST['id']
  ]);
}

function create_token() {
  if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
  }
}

function validate_token() {
  if (empty($_SESSION['token']) || $_SESSION['token'] !== filter_input(INPUT_POST, 'token')) {
    exit('Invalid request');
  }
}

function modify_datetime($datetime) {
  $modified_datetime = substr($datetime, 0, 4).'/';
  $modified_datetime .= substr($datetime, 5, 2).'/';
  $modified_datetime .= substr($datetime, 8, 2).' ';
  $modified_datetime .= substr($datetime, 11, 5);
  return $modified_datetime;
}


    
?>