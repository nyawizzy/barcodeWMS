<?php
session_start();

// To ensure the user is logged in and is a Staff member
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Staff') {
    header("Location: loginpage.php");
    exit();
}
require 'db_connect.php';

// Fetch products
function fetchProducts($conn) {
    $sql = "SELECT * FROM products";
    $result = $conn->query($sql);
    $products = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }
    return $products;
}

// Handle adding a new product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $barcode = $_POST['barcode'];
    $quantity = $_POST['quantity'];
    $location = $_POST['location'];
    $price = $_POST['price'];

    if (!empty($barcode)) {
        $sql = "INSERT INTO products (product_name, description, barcode, quantity, location, price) VALUES ('$product_name', '$description', '$barcode', '$quantity', '$location', '$price')";
        if ($conn->query($sql) === TRUE) {
            header("Location: inventory_management.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Barcode cannot be empty.";
    }
}

// Handle barcode scanning and assigning a name
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'scan') {
    $barcode = $_POST['barcode'];
    if (!empty($barcode)) {
        $name = "Product-" . strtoupper(uniqid()); // Generate a unique name

        // Check if the barcode already exists
        $sql_check = "SELECT * FROM products WHERE barcode='$barcode'";
        $result_check = $conn->query($sql_check);

        if ($result_check->num_rows > 0) {
            // Update existing product
            $sql_update = "UPDATE products SET product_name='$name' WHERE barcode='$barcode'";
            if ($conn->query($sql_update) === TRUE) {
                $response = [
                    'status' => 'success',
                    'message' => 'Barcode processed and product updated',
                    'barcode' => $barcode,
                    'name' => $name
                ];
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Error updating product: ' . $conn->error
                ];
            }
        } else {
            // Insert new product
            $sql_insert = "INSERT INTO products (product_name, barcode) VALUES ('$name', '$barcode')";
            if ($conn->query($sql_insert) === TRUE) {
                $response = [
                    'status' => 'success',
                    'message' => 'Barcode processed and product added',
                    'barcode' => $barcode,
                    'name' => $name
                ];
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Error adding product: ' . $conn->error
                ];
            }
        }

        echo json_encode($response);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Barcode cannot be empty.']);
    }
}

$products = fetchProducts($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: #800000;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            color: #fff;
        }

        .table th, .table td {
            color: #ffc300;
        }

        .btn {
            background-color: #ffc300;
            color: #800000;
            border: none;
        }

        .btn:hover {
            background-color: #e0b000;
        }

        .form-control {
            background-color: #f2f2f2;
            border: none;
            color: #800000;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #ffc300;
        }

        .form-group label {
            color: #ffc300;
        }

        .form-group button {
            background-color: #ffc300;
            color: #800000;
            border: none;
            border-radius: 5px;
        }

        .form-group button:hover {
            background-color: #e0b000;
        }

        #interactive.viewport {
            position: relative;
            width: 100%;
            height: 400px;
            overflow: hidden;
        }

        #interactive.viewport video {
            width: 100%;
            height: 100%;
        }

        #interactive.viewport canvas {
            position: absolute;
            top: 0;
            left: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Inventory Management</h1>
        <p>Manage your inventory items here.</p>

        <!-- Add New Inventory Item Form -->
        <h2>Add New Item</h2>
        <form id="add-product-form" method="POST" action="inventory_management.php">
            <div class="mb-3">
                <label for="product_name" class="form-label">Product Name:</label>
                <input type="text" id="product_name" name="product_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description:</label>
                <input type="text" id="description" name="description" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="barcode" class="form-label">Barcode:</label>
                <input type="text" id="barcode" name="barcode" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity:</label>
                <input type="number" id="quantity" name="quantity" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="location" class="form-label">Location:</label>
                <input type="text" id="location" name="location" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price:</label>
                <input type="number" step="0.01" id="price" name="price" class="form-control" required>
            </div>
            <input type="hidden" name="action" value="add">
            <input type="submit" class="btn" value="Add Product">
        </form>

        <div class="mb-3 mt-4">
            <label for="scan-barcode" class="form-label">Scan Barcode</label>
            <button class="btn mt-2" onclick="openCamera()">Scan with Camera</button>
            <form method="POST" action="inventory_management.php">
                <input type="text" id="scan-barcode" name="barcode" class="form-control">
                <input type="hidden" name="action" value="scan">
                <button type="submit" class="btn mt-2">Scan</button>
            </form>
        </div>

        <div id="interactive" class="viewport" style="display: none;"></div>

        <h2 class="mt-5">Inventory Items</h2>
        <table id="products-table" class="table table-bordered">
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Description</th>
                    <th>Barcode</th>
                    <th>Quantity</th>
                    <th>Location</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?= htmlspecialchars($product['product_id']) ?></td>
                        <td><?= htmlspecialchars($product['product_name']) ?></td>
                        <td><?= htmlspecialchars($product['description']) ?></td>
                        <td><?= htmlspecialchars($product['barcode']) ?></td>
                        <td><?= htmlspecialchars($product['quantity']) ?></td>
                        <td><?= htmlspecialchars($product['location']) ?></td>
                        <td><?= htmlspecialchars($product['price']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
    <script>
        function openCamera() {
            document.getElementById('interactive').style.display = 'block';
            Quagga.init({
                inputStream: {
                    name: "Live",
                    type: "LiveStream",
                    target: document.querySelector('#interactive')
                },
                decoder: {
                    readers: ["code_128_reader", "ean_reader"]
                },
                locate: true,
                locator: {
                    halfSample: true,
                    patchSize: "medium",
                    debug: {
                        showCanvas: false,
                        showPatches: false,
                        showFoundPatches: false,
                        showSkeleton: false,
                        showLabels: false,
                        showPatchLabels: false,
                        showRemainingPatchLabels: false,
                        boxFromPatches: {
                            showTransformed: false,
                            showTransformedBox: false,
                            showBB: false
                        }
                    }
                }
            }, function(err) {
                if (err) {
                    console.error("Initialization error: ", err);
                    return;
                }
                console.log("Initialization finished. Ready to start");
                Quagga.start();
            });

            Quagga.onProcessed(function(result) {
                var drawingCtx = Quagga.canvas.ctx.overlay,
                    drawingCanvas = Quagga.canvas.dom.overlay;

                if (result) {
                    drawingCtx.clearRect(0, 0, drawingCanvas.width, drawingCanvas.height);
                    if (result.boxes) {
                        result.boxes.forEach(function (box) {
                            Quagga.ImageDebug.drawPath(box, {x: 0, y: 1}, drawingCtx, {color: "green", lineWidth: 2});
                        });
                    }

                    if (result.box) {
                        Quagga.ImageDebug.drawPath(result.box, {x: 0, y: 1}, drawingCtx, {color: "#00F", lineWidth: 2});
                    }

                    if (result.codeResult && result.codeResult.code) {
                        Quagga.ImageDebug.drawPath(result.line, {x: 'x', y: 'y'}, drawingCtx, {color: 'red', lineWidth: 3});
                    }
                }
            });

            Quagga.onDetected(function(data) {
                var barcode = data.codeResult.code;
                console.log("Barcode detected and processed: [" + barcode + "]", data);
                alert("Barcode detected: " + barcode);
                Quagga.stop();
                document.getElementById('interactive').style.display = 'none';
                processBarcode(barcode);
            });
        }

        function processBarcode(barcode) {
            fetch('process_scanned_barcode.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ barcode: barcode })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // TO Populate the form fields with the returned data
                    document.getElementById('product_name').value = data.name;
                    document.getElementById('description').value = data.description;
                    document.getElementById('quantity').value = data.quantity;
                    document.getElementById('location').value = data.location;
                    document.getElementById('price').value = data.price;
                    alert(`Barcode processed successfully: ${data.barcode}\nName: ${data.name}`);
                } else {
                    alert('Error processing barcode: ' + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>
