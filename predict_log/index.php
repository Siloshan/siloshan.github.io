<?php
// Include the database connection file
require_once "db_connection.php";

function insertData($symbol, $trainingStartDate, $trainingEndDate, $evalStartDate, $evalEndDate, $interval, $aqThreshold, $marketVariation)
{
    global $conn;

    // Insert data into Q_acc_tbl
    $sql = "INSERT INTO Q_acc_tbl (symbol_s, training_date_start, training_date_end, evalu_date_start, evalu_date_end, interval_i, aq_threshold, market_variation, status_c) 
            VALUES ('$symbol', '$trainingStartDate', '$trainingEndDate', '$evalStartDate', '$evalEndDate', '$interval', '$aqThreshold', '$marketVariation', 'pending')";

    if ($conn->query($sql) === TRUE) {
        echo "Data inserted successfully!";
    } else {
        echo "Error inserting data: " . $conn->error;
    }
}

function insertCSVData($csvFilePath)
{
    global $conn;

    // Read the CSV file
    $file = fopen($csvFilePath, "r");
    if ($file !== false) {
        // Skip the header row
        fgetcsv($file);

        // Process each row
        while (($data = fgetcsv($file)) !== false) {
            $symbol = $data[0];
            $trainingStartDate = $data[1];
            $trainingEndDate = $data[2];
            $evalStartDate = $data[3];
            $evalEndDate = $data[4];
            $interval = $data[5];
            $aqThreshold = $data[6];
            $marketVariation = $data[7];

            // Insert data into Q_acc_tbl
            insertData($symbol, $trainingStartDate, $trainingEndDate, $evalStartDate, $evalEndDate, $interval, $aqThreshold, $marketVariation);
        }

        fclose($file);

        echo "CSV data inserted successfully!";
    } else {
        echo "Error reading CSV file!";
    }
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if the form is for CSV upload
    if (isset($_FILES["csvFile"])) {
        $csvFilePath = $_FILES["csvFile"]["tmp_name"];

        // Insert data from the CSV file
        insertCSVData($csvFilePath);
    } else {
        // Retrieve form data
        $symbol = $_POST["symbol"];
        $trainingStartDate = $_POST["trainingStartDate"];
        $trainingEndDate = $_POST["trainingEndDate"];
        $evalStartDate = $_POST["evalStartDate"];
        $evalEndDate = $_POST["evalEndDate"];
        $interval = $_POST["interval"];
        $aqThreshold = $_POST["aqThreshold"];
        $marketVariation = $_POST["marketVariation"];

        // Insert data into Q_acc_tbl
        insertData($symbol, $trainingStartDate, $trainingEndDate, $evalStartDate, $evalEndDate, $interval, $aqThreshold, $marketVariation);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .tab-content {
            display: none;
        }
    </style>
    <script>
        function switchTab(tabName) {
            var tabContent = document.getElementsByClassName("tab-content");
            for (var i = 0; i < tabContent.length; i++) {
                tabContent[i].style.display = "none";
            }
            document.getElementById(tabName).style.display = "block";
        }
    </script>
</head>
<body>
    <h2>Data Table</h2>
    <button onclick="switchTab('tableTab')">Show Table</button>
    <button onclick="switchTab('insertTab')">Insert Data</button>
    <div id="tableTab" class="tab-content">
        <table>
            <tr>
                <th>ID</th>
                <th>Symbol</th>
                <th>Training Date Start</th>
                <th>Training Date End</th>
                <th>Evaluation Date Start</th>
                <th>Evaluation Date End</th>
                <th>Interval</th>
                <th>AQ Threshold</th>
                <th>Market Variation</th>
                <th>Accuracy</th>
                <th>Status</th>
            </tr>
            <?php
            // Fetch data from Q_acc_tbl
            $sql = "SELECT * FROM Q_acc_tbl";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>".$row["id"]."</td>";
                    echo "<td>".$row["symbol_s"]."</td>";
                    echo "<td>".$row["training_date_start"]."</td>";
                    echo "<td>".$row["training_date_end"]."</td>";
                    echo "<td>".$row["evalu_date_start"]."</td>";
                    echo "<td>".$row["evalu_date_end"]."</td>";
                    echo "<td>".$row["interval_i"]."</td>";
                    echo "<td>".$row["aq_threshold"]."</td>";
                    echo "<td>".$row["market_variation"]."</td>";
                    echo "<td>".$row["Accurancy"]."</td>";
                    echo "<td>".$row["status_c"]."</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='11'>No data available</td></tr>";
            }
            ?>
        </table>
    </div>
    <div id="insertTab" class="tab-content" style="display: none;">
        <h3>Insert Data</h3>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <label for="symbol">Symbol:</label>
            <input type="text" id="symbol" name="symbol"><br><br>
            <label for="trainingStartDate">Training Date Start:</label>
            <input type="text" id="trainingStartDate" name="trainingStartDate"><br><br>
            <label for="trainingEndDate">Training Date End:</label>
            <input type="text" id="trainingEndDate" name="trainingEndDate"><br><br>
            <label for="evalStartDate">Evaluation Date Start:</label>
            <input type="text" id="evalStartDate" name="evalStartDate"><br><br>
            <label for="evalEndDate">Evaluation Date End:</label>
            <input type="text" id="evalEndDate" name="evalEndDate"><br><br>
            <label for="interval">Interval:</label>
            <input type="text" id="interval" name="interval"><br><br>
            <label for="aqThreshold">AQ Threshold:</label>
            <input type="text" id="aqThreshold" name="aqThreshold"><br><br>
            <label for="marketVariation">Market Variation:</label>
            <input type="text" id="marketVariation" name="marketVariation"><br><br>
            <input type="submit" value="Submit">
        </form>
        <br>
        <h3>Insert Data from CSV</h3>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <label for="csvFile">CSV File:</label>
            <input type="file" id="csvFile" name="csvFile"><br><br>
            <input type="submit" value="Upload CSV">
        </form>
    </div>
</body>
</html>
