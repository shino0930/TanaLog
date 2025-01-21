document.addEventListener("DOMContentLoaded", () => {
    let html5QrCode;

    // スキャナーを開始する関数
    window.startScanner = function () {
        const reader = document.getElementById("reader");
        const stopButton = document.getElementById("stopScanner");

        html5QrCode = new Html5Qrcode("reader");

        html5QrCode.start(
            { facingMode: "environment" },
            { fps: 10, qrbox: { width: 250, height: 250 } },
            (decodedText) => {
                document.getElementById("ISBN").value = decodedText; // ISBNに結果を表示
            },
            (errorMessage) => {
                console.log("スキャンエラー:", errorMessage);
            }
        ).catch((err) => {
            console.error("カメラの起動に失敗しました:", err);
        });

        // 停止ボタンのイベントリスナーを設定
        stopButton.addEventListener("click", () => {
            if (html5QrCode) {
                html5QrCode.stop().then(() => {
                    console.log("スキャナーを停止しました。");
                    reader.innerHTML = ""; // カメラビューをリセット
                }).catch((err) => {
                    console.error("スキャナーの停止に失敗しました:", err);
                });
            }
        });
    };
});

$(function () {
    $(".btn-gnavi").on("click", function () {
        var rightVal = 0;
        if ($(this).hasClass("open")) {
            rightVal = -300;
            $(this).removeClass("open");
        } else {
            $(this).addClass("open");
        }

        $("#global-navi").stop().animate({
            right: rightVal
        }, 200);
    });
});

// タブ切り替え
window.showTab = function (tabId) {
    document.querySelectorAll('.tab-content').forEach(tab => tab.style.display = 'none');
    document.getElementById(tabId).style.display = 'block';

    document.querySelectorAll('.tab-buttons button').forEach(btn => btn.classList.remove('active'));
    document.querySelector(`.tab-buttons button[onclick="showTab('${tabId}')"]`).classList.add('active');
};

// 初期表示
showTab('search');
