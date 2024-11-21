$(function () {
    $(".btn-gnavi").on("click", function () {
        // ハンバーガーメニューの位置を設定するための変数
        var rightVal = 0;
        if ($(this).hasClass("open")) {
            // 「open」クラスを持つ要素はメニューを開いた状態に設定
            rightVal = -300;
            // メニューを開いたら次回クリック時は閉じた状態になるよう設定
            $(this).removeClass("open");
        } else {
            // 「open」クラスを持たない要素はメニューを閉じた状態に設定 (rightVal は0の状態 )
            // メニューを開いたら次回クリック時は閉じた状態になるよう設定
            $(this).addClass("open");
        }

        $("#global-navi").stop().animate({
            right: rightVal
        }, 200);
    });
});

document.addEventListener("DOMContentLoaded", () => {
    let html5QrCode;

    // QRコード/バーコードスキャン開始
    window.startScanner = function () {
        const reader = document.getElementById("reader");
        const stopButton = document.getElementById("stopScanner");

        html5QrCode = new Html5Qrcode("reader");

        html5QrCode.start(
            { facingMode: "environment" },
            { fps: 10, qrbox: { width: 250, height: 250 } },
            (decodedText) => {
                document.getElementById("ISBN").value = decodedText;
                stopScanner();
            }
        ).then(() => {
            stopButton.style.display = "inline-block";
        }).catch(console.error);
    };

    // スキャン停止
    window.stopScanner = function () {
        if (html5QrCode) {
            html5QrCode.stop().then(() => {
                document.getElementById("reader").style.display = "none";
                document.getElementById("stopScanner").style.display = "none";
            }).catch(console.error);
        }
    };

    // タブ切り替え
    window.showTab = function (tabId) {
        document.querySelectorAll('.tab-content').forEach(tab => tab.style.display = 'none');
        document.getElementById(tabId).style.display = 'block';

        document.querySelectorAll('.tab-buttons button').forEach(btn => btn.classList.remove('active'));
        document.querySelector(`.tab-buttons button[onclick="showTab('${tabId}')"]`).classList.add('active');
    };

    // 初期表示
    showTab('search');
});
