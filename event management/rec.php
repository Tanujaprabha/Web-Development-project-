<?php
$host = 'localhost';
$user = 'root';  // Your DB username
$pass = '';      // Your DB password
$dbname = 'mobile'; // Your DB name
$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Validate name and phone from URL
if (!isset($_GET['name']) || !isset($_GET['phone'])) {
    echo "Invalid receipt details.";
    exit();
}

$name = $_GET['name'];
$phone = $_GET['phone'];

// Fetch latest payment and booking by phone
$query = "
  SELECT 
    p.name, p.phone, p.event, p.total_amount, p.payment_method, p.transaction_date
  FROM payments p
  JOIN bookings b ON p.phone = b.phone
  WHERE p.name = ? AND p.phone = ?
  ORDER BY p.transaction_date DESC
  LIMIT 1
";

$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $name, $phone);
$stmt->execute();
$stmt->bind_result($fetchedName, $fetchedPhone, $event, $total, $method, $date);
$found = $stmt->fetch();
$stmt->close();

if (!$found) {
    echo "No receipt found for the given name and phone.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Event Booking Receipt</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #e8f5e9;
      margin: 0;
      padding: 0;
    }
    .receipt {
      background: #ffffff;
      border-radius: 10px;
      padding: 30px;
      max-width: 600px;
      margin: 50px auto;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      color: #2e7d32;
    }
    .success-message {
      text-align: center;
      font-size: 20px;
      color: #388e3c;
      margin-top: 10px;
    }
    .details {
      margin-top: 30px;
    }
    .details p {
      font-size: 16px;
      margin: 10px 0;
    }
    .footer {
      margin-top: 40px;
      text-align: center;
      font-size: 14px;
      color: #777;
    }
    .highlight {
      color: #1b5e20;
      font-weight: bold;
    }
    .offline-message {
      margin-top: 20px;
      text-align: center;
      font-size: 16px;
      color: #1976d2;
    }
  </style>
</head>
<body>
  <div class="receipt">
    <h2>🎉 Booking Confirmed!</h2>
    <p class="success-message">Your event has been successfully booked.</p>

    <div class="details">
      <p><span class="highlight">Name:</span> <?php echo htmlspecialchars($fetchedName); ?></p>
      <p><span class="highlight">Event:</span> <?php echo htmlspecialchars($event); ?></p>
      <p><span class="highlight">Total Amount:</span> ₹<?php echo number_format($total); ?></p>
      <p><span class="highlight">Payment Method:</span> <?php echo htmlspecialchars($method); ?></p>
      <p><span class="highlight">Transaction Date:</span> <?php echo date("d M Y, h:i A", strtotime($date)); ?></p>
    </div>

    <div class="offline-message">
      <p>Take a screenshot to proceed offline.</p>
    </div>

    <div class="footer">
      Thank you for booking with us! We'll make your event memorable! 🌿
    </div>
  </div>
</body>
</html>
