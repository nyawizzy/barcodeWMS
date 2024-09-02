<?php
session_start();

// Ensure the user is logged in and is a System Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'System Admin') {
    header("Location: loginpage.php");
    exit();
}

require 'db_connect.php';

// Fetch analytics data
function getAnalyticsData($conn) {
    $sql = "SELECT * FROM analytics ORDER BY timestamp DESC";
    $result = $conn->query($sql);
    $analytics = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $analytics[] = $row;
        }
    }
    return $analytics;
}

$analytics = getAnalyticsData($conn);

// Prepare data for charts
$metrics = [];
$values = [];
$timestamps = [];

foreach ($analytics as $analytic) {
    $metrics[] = $analytic['metric'];
    $values[] = $analytic['value'];
    $timestamps[] = $analytic['timestamp'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        .chart-container {
            position: relative;
            height: 400px;
            width: 100%;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Analytics</h1>
        
        <!-- Chart Container -->
        <div class="chart-container">
            <canvas id="analyticsChart"></canvas>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Analytics ID</th>
                    <th>Metric</th>
                    <th>Value</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($analytics as $analytic): ?>
                    <tr>
                        <td><?= htmlspecialchars($analytic['analytics_id']) ?></td>
                        <td><?= htmlspecialchars($analytic['metric']) ?></td>
                        <td><?= htmlspecialchars($analytic['value']) ?></td>
                        <td><?= htmlspecialchars($analytic['timestamp']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        // Prepare data for Chart.js
        const metrics = <?= json_encode($metrics) ?>;
        const values = <?= json_encode($values) ?>;
        const timestamps = <?= json_encode($timestamps) ?>;

        // Create a chart
        const ctx = document.getElementById('analyticsChart').getContext('2d');
        const analyticsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: timestamps,
                datasets: [{
                    label: 'Analytics Data',
                    data: values,
                    borderColor: '#ffc300',
                    backgroundColor: 'rgba(255, 195, 0, 0.2)',
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        type: 'time',
                        time: {
                            unit: 'day'
                        },
                        title: {
                            display: true,
                            text: 'Timestamp',
                            color: '#ffc300'
                        },
                        ticks: {
                            color: '#ffc300'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Value',
                            color: '#ffc300'
                        },
                        ticks: {
                            color: '#ffc300'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
