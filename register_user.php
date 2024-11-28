<?php
// データベース接続情報
$host = 'localhost';
$dbname = 'mybook_db'; // 任意のデータベース名
$user = 'root'; // デフォルトユーザー
$password = ''; // デフォルトパスワード（必要に応じて変更）

// ユーザーからの入力を取得
$username = $_POST['username'];
$password = $_POST['password'];
$password_confirm = $_POST['password_confirm'];

// 入力データの確認
if ($password !== $password_confirm) {
    die("エラー: パスワードが一致しません。<a href='new_user.html'>戻る</a>");
}

// パスワードをハッシュ化（安全対策）
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

try {
    // データベース接続
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ユーザーIDの重複確認
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetchColumn() > 0) {
        die("エラー: このユーザーIDは既に使用されています。<a href='new_user.html'>戻る</a>");
    }

    // データベースに新規ユーザーを登録
    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->execute([$username, $hashed_password]);

    echo "登録が完了しました！<a href='login.html'>ログイン画面へ</a>";
} catch (PDOException $e) {
    echo "エラー: " . $e->getMessage();
}
?>
