<?php
// セッションを開始
session_start();

// データベース接続情報
$host = 'localhost';
$dbname = 'mybook_db';
$user = 'root';
$password = '';

try {
    // データベース接続
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // フォームからのデータを取得
    $username = $_POST['username'];
    $password = $_POST['password'];

    // ユーザー情報を検索
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // パスワードが一致した場合、セッションに情報を保存
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        // ログイン成功
        header('Location: index.html');
        exit;
    } else {
        // ログイン失敗:エラーメッセージを表示してリダイレクト
        $_SESSION['error'] = 'ユーザー名またはパスワードが正しくありません';
        header('Location: login.html');
        exit;
    }
} catch (PDOException $e) {
    echo "エラー: " . $e->getMessage();
    exit;
}
