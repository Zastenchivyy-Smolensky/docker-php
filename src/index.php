<?php
  require('db.php');
  require('functions.php');
  
  $messages = get_messages($pdo);
  session_start();
  create_token();

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validate_token();
    $action = filter_input(INPUT_GET, 'action');
    if ($action === 'post') {
      post_message($pdo);
    } else if ($action === 'delete') {
      delete_message($pdo);
    } else if ($action === 'change_importance') {
      change_importance($pdo);
    }
  }

  if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = filter_input(INPUT_GET, 'action');
    if ($action === 'get_current_message') {
      get_current_message($pdo);
    }
  }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ToDoApp</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container" data-token="<?= h($_SESSION['token']); ?>">
    <h1>ToDoApp</h1>

    <div class="popup">
      <div class="message"></div>
      <div class="close-btn"><i class="bi bi-x-lg"></i></div>
    </div>
    
    <form>
      <div class="label">
        <label for="name">Title</label>
        <span id="alert-name">※20⽂字以内で⼊⼒してください。</span>
      </div>
      <input type="text" name="name" id="name">
      <div class="label">
        <label for="message">Content</label>
        <span id="alert-message">※140字以内で⼊⼒してください。</span>
      </div>
      <textarea name="message" id="message" cols="35" rows="25"></textarea>
      <input type="submit" name="post" value="Add" class="btn">
    </form>

    <ul>
      <?php foreach($messages as $message): ?>
        <?php if ($message->delete_flag == 0) : ?>
          <li data-id="<?= h($message->id); ?>">
            <div class="header">
              <div class="name"><?= h($message->post_name) ?></div>
              <div class="time"><?= h(modify_datetime($message->created_at)) ?></div>
            </div>
            <div class="message"><?= h($message->message) ?></div>
            <span class="delete"><i class="bi bi-x"></i></span>
            <?php if ($message->importance == 0) : ?>
              <span class="importance low"><i class="bi bi-bookmark-fill"></i></span>
            <?php elseif ($message->importance == 1) : ?>
              <span class="importance middle"><i class="bi bi-bookmark-fill"></i></span>
            <?php elseif ($message->importance == 2) : ?>
              <span class="importance high"><i class="bi bi-bookmark-fill"></i></span>
            <?php endif; ?>
          </li>
        <?php endif; ?>
      <?php endforeach; ?>
    </ul>
  </div>
  <script src="main.js"></script>
</body>
</html>
