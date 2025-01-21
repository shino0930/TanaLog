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
    exit;
}

// POSTデータからIDを取得して削除処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id']) && is_numeric($_POST['id'])) {
        $id = intval($_POST['id']);

        // 削除処理
        $stmt = $pdo->prepare("DELETE FROM books WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // 本棚ページにリダイレクト
        header("Location: bookshelf.php");
        exit;
    } else {
        echo "無効なリクエストです。";
    }
} else {
    echo "直接アクセスは許可されていません。";
}
?>