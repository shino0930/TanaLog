<?php
session_start();

// データベース接続
$host = 'localhost';
$dbname = 'mybook_db';
$username = 'root';
$password = '';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

// ログインしているユーザーIDを取得
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// 書籍登録処理（ISBNで登録）
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['isbn']) && !isset($_POST['submit'])) {
    $isbn = $_POST['isbn'];

    // OpenBD APIで書籍情報を取得
    $url = "https://api.openbd.jp/v1/get?isbn=" . urlencode($isbn);
    $bookInfo = file_get_contents($url);
    $bookData = json_decode($bookInfo, true);

    if ($bookData && isset($bookData[0]['summary'])) {
        $book = $bookData[0]['summary'];

        if ($user_id !== null) {
            // 書籍情報をデータベースに登録
            $stmt = $pdo->prepare(
                "INSERT INTO books (title, author, isbn, publisher, user_id) 
                VALUES (:title, :author, :isbn, :publisher, :user_id)"
            );
            $stmt->execute([
                ':title' => $book['title'],
                ':author' => $book['author'],
                ':isbn' => $isbn,
                ':publisher' => $book['publisher'],
                ':user_id' => $user_id,
            ]);
            echo "書籍を登録しました！";
        } else {
            echo "ユーザーがログインしていません。";
        }
    } else {
        echo "書籍情報が取得できませんでした。";
    }
}

// 書籍登録処理（手動登録）
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $publisher = trim($_POST['publisher']);
    $isbn = trim($_POST['isbn']);

    if (empty($title)) {
        echo "タイトルは必須です。";
    } elseif ($user_id === null) {
        echo "ユーザーがログインしていません。";
    } else {
        // 手動で入力された書籍情報をデータベースに登録
        $stmt = $pdo->prepare(
            "INSERT INTO books (title, author, isbn, publisher, user_id) 
            VALUES (:title, :author, :isbn, :publisher, :user_id)"
        );
        $stmt->execute([
            ':title' => $title,
            ':author' => $author,
            ':isbn' => $isbn,
            ':publisher' => $publisher,
            ':user_id' => $user_id,
        ]);
        echo "手動登録が完了しました！";
    }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no">
    <link rel="stylesheet" href="index.css">
    <title>Tanalog</title>
</head>

<body>
    <?php include('header.php'); ?>
    <div id="wrapper">
        <div id="login-status">
            <?php if (isset($_SESSION['username'])): ?>
                <p><?php echo htmlspecialchars($_SESSION['username']); ?> でログイン中</p>
            <?php else: ?>
                <p><a href="login.html">ログイン</a></p>
            <?php endif; ?>
        </div>

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
                <li><a href="logout.php">ログアウト</a></li>
                <li><a href="new_user.html">新規登録</a></li>
            </ul>
        </nav>
        <h1>Tanalog-たなログ-</h1>

        <div class="tab-buttons">
            <button onclick="showTab('search')">ISBNで登録する</button>
            <button onclick="showTab('manual')">手動で登録する</button>
        </div>

        <!-- ISBNで登録するタブ -->
        <div id="search" class="tab-content">
            <form method="POST" action="index.php">
                <table id="editor">
                    <thead>
                        <tr>
                            <th colspan="2">ISBNで登録する</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>ISBNコード</th>
                            <td>
                                <input type="tel" id="ISBN" name="isbn" minlength="13" maxlength="20" required />
                                <button type="button" onclick="startScanner()">カメラでISBNをスキャン</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="center">
                    <button type="submit" id="kensaku">登録</button>
                </div>
            </form>
        </div>

        <!-- 手動で登録するタブ -->
        <div id="manual" class="tab-content" style="display: none;">
            <form method="POST" action="index.php">
                <table id="manualEditor">
                    <thead>
                        <tr>
                            <th colspan="2">手動で登録する</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>タイトル (必須)</th>
                            <td><input type="text" id="title" name="title" required /></td>
                        </tr>
                        <tr>
                            <th>著者</th>
                            <td><input type="text" id="author" name="author" /></td>
                        </tr>
                        <tr>
                            <th>出版社</th>
                            <td><input type="text" id="publisher" name="publisher" /></td>
                        </tr>
                        <tr>
                            <th>ISBN</th>
                            <td><input type="text" id="isbn" name="isbn" /></td>
                        </tr>
                    </tbody>
                </table>
                <div class="center">
                    <button type="submit" name="submit">登録</button>
                </div>
            </form>
        </div>
    </div>

    <div id="reader" style="width: 300px; margin: 20px auto;">
        <button id="stopScanner" style="display: none;" onclick="stopScanner()">停止</button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/html5-qrcode/minified/html5-qrcode.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="index.js"></script>
</body>

</html>
