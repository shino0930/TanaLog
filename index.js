document.addEventListener("DOMContentLoaded", () => {
    if (typeof Html5Qrcode === "undefined") {
        console.error("Html5Qrcodeライブラリが読み込まれていません。ライブラリURLを確認してください。");
        return;
    }

    let html5QrCode;

    // QRコード/バーコード読み取りを開始する関数
    window.startScanner = function () {
        const readerElement = document.getElementById("reader");
        const stopButton = document.getElementById("stopScanner");

        // カメラ表示エリアが正しく取得できない場合
        if (!readerElement) {
            console.error("カメラ表示エリアが見つかりません。");
            return;
        }

        // Html5Qrcodeオブジェクトの初期化
        html5QrCode = new Html5Qrcode("reader");

        // カメラを開始してQRコード/バーコードをスキャン
        html5QrCode.start(
            { facingMode: "environment" }, // 背面カメラを使用
            {
                fps: 10, // フレームレート
                qrbox: { width: 250, height: 250 }, // QRコード読み取り領域
                willReadFrequently: true, // 頻繁に読み取り
                formatsToSupport: ["QR_CODE", "EAN_13", "UPC_A"] // QRコードとバーコード（EAN-13, UPC-A）のサポート
            },
            (decodedText) => {
                // スキャン結果をISBN入力フィールドにセット
                document.getElementById("ISBN").value = decodedText;
                stopScanner();  // スキャン成功後にカメラ停止
            },
            (errorMessage) => {
                console.error("読み取りエラー:", errorMessage);
            }
        ).then(() => {
            // スキャン開始後に停止ボタンを表示
            if (stopButton) stopButton.style.display = "inline-block";
        }).catch((err) => {
            console.error("カメラの起動に失敗しました:", err);
        });

        // カメラ表示エリアを表示
        readerElement.style.display = "block";
    };

    // QRコード/バーコード読み取りを停止する関数
    window.stopScanner = function () {
        if (html5QrCode) {
            html5QrCode.stop().then(() => {
                // カメラ停止後にエリアとボタンを非表示
                const readerElement = document.getElementById("reader");
                const stopButton = document.getElementById("stopScanner");

                if (readerElement) readerElement.style.display = "none";
                if (stopButton) stopButton.style.display = "none";
            }).catch((err) => {
                console.error("カメラ停止エラー:", err);
            });
        }
    };
});

function showTab(tabId) {
    // すべてのタブコンテンツを非表示
    document.querySelectorAll('.tab-content').forEach((tab) => {
        tab.style.display = 'none';
    });
    // クリックされたタブを表示
    document.getElementById(tabId).style.display = 'block';

    // すべてのボタンから「active」クラスを外す
    document.querySelectorAll('.tab-buttons button').forEach((btn) => {
        btn.classList.remove('active');
    });
    // クリックされたボタンに「active」クラスを追加
    document.querySelector(`.tab-buttons button[onclick="showTab('${tabId}')"]`).classList.add('active');
}

// 初期表示で「検索から登録する」を表示
document.addEventListener("DOMContentLoaded", function () {
    showTab('search');
});
