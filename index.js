document.addEventListener("DOMContentLoaded", () => {
    if (typeof Html5Qrcode === "undefined") {
      console.error("Html5Qrcodeライブラリが読み込まれていません。ライブラリURLを確認してください。");
      return;
    }
  
    window.startScanner = function () {
      const html5QrCode = new Html5Qrcode("reader");
  
      html5QrCode.start(
        { facingMode: "environment" },
        {
          fps: 10,
          qrbox: { width: 250, height: 250 }
        },
        (decodedText) => {
          document.getElementById("ISBN").value = decodedText;
          html5QrCode.stop();
          document.getElementById("reader").style.display = "none";
        },
        (errorMessage) => {
          // エラー発生時の処理
        }
      ).catch((err) => {
        console.error("カメラの起動に失敗しました: ", err);
      });
  
      document.getElementById("reader").style.display = "block";
    };
  });
  