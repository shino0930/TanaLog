<?php
// データベース接続設定
$host = 'localhost';
$dbname = 'mybook_db';
$username = 'root';
$password = '';  // パスワードを設定

try {
  $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo 'Connection failed: ' . $e->getMessage();
}

// booksテーブルから書籍情報を取得
$stmt = $pdo->prepare("SELECT title, author, isbn, publisher FROM books");
$stmt->execute();
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, user-scalable=no">
  <title>本棚</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <div id="wrapper">
    <p class="btn-gnavi">
      <span></span>
      <span></span>
      <span></span>
    </p>
    <nav id="global-navi">
      <ul class="menu">
        <li><a href="index.php">登録</a></li>
        <li><a href="bookshelf.php">本棚</a></li>
        <li><a href="login.html">ログイン</a></li>
        <li><a href="logout.html">ログアウト</a></li>
        <li><a href="new_user.html">新規登録</a></li>
      </ul>
    </nav>

    <h1>本棚</h1>
    <div class="book-container">
      <?php foreach ($books as $book): ?>
        <div class="book-box">
          <h3><?php echo htmlspecialchars($book['title']); ?></h3>
          <p><strong>著者:</strong> <?php echo htmlspecialchars($book['author']); ?></p>
          <p><strong>ISBN:</strong> <?php echo htmlspecialchars($book['isbn']); ?></p>
          <p><strong>出版社:</strong> <?php echo htmlspecialchars($book['publisher']); ?></p>
        </div>
      <?php endforeach; ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="index.js"></script>
</body>

</html>