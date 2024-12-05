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
    <!-- ログイン状態の表示 -->
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
            <li><a href="#">本棚</a></li>
            <li><a href="login.html">ログイン</a></li>
            <li><a href="logout.php">ログアウト</a></li>
            <li><a href="new_user.html">新規登録</a></li>
        </ul>
    </nav>
    <h1>Tanalog-たなログ-</h1>

    <!-- タブボタン -->
    <div class="tab-buttons">
        <button onclick="showTab('search')">検索から登録する</button>
        <button onclick="showTab('manual')">手動で登録する</button>
    </div>

    <!-- タブ内容: 検索から登録 -->
    <div id="search" class="tab-content">
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
                <tr>
                    <th>ISBNコード</th>
                    <td>
                        <input type="tel" id="ISBN" minlength="13" maxlength="13" />
                        <button onclick="startScanner()">カメラでISBNをスキャン</button>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="center">
            <button id="kensaku">検索</button>
        </div>
    </div>

    <!-- タブ内容: 手動登録 -->
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

<!-- カメラ表示エリア -->
<div id="reader" style="width: 300px; margin: 20px auto">
    <button id="stopScanner" style="display: none" onclick="stopScanner()">停止</button>
</div>
<script src="https://cdn.jsdelivr.net/npm/html5-qrcode/minified/html5-qrcode.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="index.js"></script>
</body>
</html>
