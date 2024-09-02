<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: loginpage.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barcode Scanning</title>
    <script src="https://unpkg.com/@zxing/library@latest"></script>
    <style>
        #scanner-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        video {
            width: 400px;
            height: 300px;
        }
    </style>
</head>
<body>
    <div id="scanner-container">
        <h1>Barcode Scanning</h1>
        <video id="video" playsinline></video>
        <p id="result"></p>
    </div>
    <script>
        window.addEventListener('load', function() {
            let selectedDeviceId;
            const codeReader = new ZXing.BrowserBarcodeReader();
            console.log('ZXing code reader initialized');
            codeReader.getVideoInputDevices()
                .then((videoInputDevices) => {
                    selectedDeviceId = videoInputDevices[0].deviceId;
                    return codeReader.decodeFromVideoDevice(selectedDeviceId, 'video', (result, err) => {
                        if (result) {
                            console.log(result);
                            document.getElementById('result').textContent = result.text;
                            // Send the scanned barcode to the server for processing
                            fetch('process_scanned_barcode.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify({ barcode: result.text }),
                            })
                            .then(response => response.json())
                            .then(data => {
                                console.log('Success:', data);
                                alert('Barcode detected: ' + data.product.barcode + '\nProduct Name: ' + data.product.product_name + '\nDescription: ' + data.product.description + '\nQuantity: ' + data.product.quantity + '\nLocation: ' + data.product.location + '\nPrice: ' + data.product.price);
                            })
                            .catch((error) => {
                                console.error('Error:', error);
                            });
                        }
                        if (err && !(err instanceof ZXing.NotFoundException)) {
                            console.error(err);
                            document.getElementById('result').textContent = err;
                        }
                    });
                })
                .catch((err) => {
                    console.error(err);
                });
        });
    </script>
</body>
</html>

