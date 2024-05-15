<?php
  // Database connection
require_once "db_connection.php";

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
  $sql = "INSERT INTO Q_acc_tbl (symbol_s, training_date_start, training_date_end, evalu_date_start, evalu_date_end, interval_i, aq_threshold, market_variation, status_c) 
          VALUES ('$symbol', '$trainingStartDate', '$trainingEndDate', '$evalStartDate', '$evalEndDate', '$interval', '$aqThreshold', '$marketVariation', 'pending')";

  if ($conn->query($sql) === TRUE) {
    echo "Data inserted successfully!";
  } else {
    echo "Error inserting data: " . $conn->error;
  }

  $conn->close();
?>