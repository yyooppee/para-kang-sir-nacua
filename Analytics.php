<?php
// Include your database connection file
include "components/db.php";

// Fetch data from the database
$sql = "SELECT brand.Brand_Name, COUNT(requisition.brand_id) AS frequency
        FROM brand
        LEFT JOIN requisition ON brand.brand_id = requisition.brand_id
        GROUP BY brand.Brand_Name
        ORDER BY frequency DESC";

$result = $conn->query($sql);

// Initialize arrays to store data for the chart
$brandNames = [];
$frequencies = [];

while ($row = $result->fetch_assoc()) {
    $brandNames[] = $row['Brand_Name'];
    $frequencies[] = $row['frequency'];
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brand Frequency Chart</title>
    <!-- Include Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <?php include "Style.php"; ?>
</head>

<body>
    <?php include "header.php"; ?>

    <div class="content">
        <div class="container">
            <h1>Brand Request Analysis Chart</h1>

            <div class="chart-container">
                <canvas id="brandChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        // Create a bar chart using Chart.js
        var ctx = document.getElementById('brandChart').getContext('2d');
        var brandChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($brandNames); ?>,
                datasets: [{
                    label: 'Total Brand Sales',
                    data: <?php echo json_encode($frequencies); ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Request Count'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Brand Names'
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>
