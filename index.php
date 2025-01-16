<?php
// セッションを開始
session_start();

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

// ログインしているユーザーIDを取得
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['isbn'])) {
    $isbn = $_POST['isbn'];

    // APIを使って書籍情報を取得
    $url = "https://api.openbd.jp/v1/get?isbn=" . urlencode($isbn);
    $bookInfo = file_get_contents($url);
    $bookData = json_decode($bookInfo, true);

    if ($bookData && isset($bookData[0]['summary'])) {
        $book = $bookData[0]['summary'];

        // ユーザーIDがない場合はエラーメッセージを表示
        if ($user_id === null) {
            echo "ユーザーがログインしていないため、書籍の登録はできません。";
            exit;
        }

        // 取得した書籍情報をデータベースに保存
        $stmt = $pdo->prepare("INSERT INTO books (title, author, publisher, isbn, user_id) VALUES (:title, :author, :publisher, :isbn, :user_id)");
        $stmt->execute([
            ':title' => $book['title'],
            ':author' => $book['author'],
            ':publisher' => $book['publisher'],
            ':isbn' => $isbn,
            ':user_id' => $user_id // ユーザーIDを追加
        ]);
        echo "書籍が正常に登録されました。";
    } else {
        echo "書籍情報が見つかりませんでした。";
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
            <?php if ($username): ?>
                <p><?php echo htmlspecialchars($username); ?> でログイン中</p>
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
            <button onclick="showTab('search')">検索から登録する</button>
            <button onclick="showTab('manual')">手動で登録する</button>
        </div>

        <!-- タブ内容: 検索から登録 -->
        <div id="search" class="tab-content">
            <form method="POST" action="index.php">
                <table id="editor">
                    <thead>
                        <tr>
                            <th colspan="2">検索から登録する</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>本のタイトル</th>
                            <td><input type="text" id="booktitle" /></td>
                        </tr>
                        <tr>
                            <th>著者</th>
                            <td><input type="text" id="author" /></td>
                        </tr>
                        <tr>
                            <th>出版社</th>
                            <td><input type="text" id="publisher" /></td>
                        </tr>
                        <th>ISBNコード</th>
                        <td>
                            <input type="tel" id="ISBN" name="isbn" minlength="13" maxlength="20" />
                            <button type="button" onclick="startScanner()">カメラでISBNをスキャン</button>
                        </td>
                        </tr>
                    </tbody>
                </table>
                <div class="center">
                    <button type="submit" id="kensaku">検索</button>
                </div>
            </form>
        </div>

        <div id="manual" class="tab-content" style="display: none">
            <table id="manualEditor">
                <thead>
                    <tr>
                        <th colspan="2">手動で登録する</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>本のタイトル</th>
                        <td><input type="text" id="manualBookTitle" /></td>
                    </tr>
                    <tr>
                        <th>著者</th>
                        <td><input type="text" id="manualAuthor" /></td>
                    </tr>
                    <tr>
                        <th>出版社</th>
                        <td><input type="text" id="manualPublisher" /></td>
                    </tr>
                    <tr>
                        <th>ISBNコード</th>
                        <td>
                            <input type="tel" id="manualISBN" minlength="13" maxlength="13" />
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="center">
                <button id="register">登録</button>
            </div>
        </div>
    </div>

    <div id="reader" style="width: 300px; margin: 20px auto">
        <button id="stopScanner" style="display: none" onclick="stopScanner()">停止</button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/html5-qrcode/minified/html5-qrcode.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="index.js"></script>
</body>

</html>