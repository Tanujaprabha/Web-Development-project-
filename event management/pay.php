<?php
$host = 'localhost';
$user = 'root';  // Your DB username
$pass = '';      // Your DB password
$dbname = 'mobile'; // Your DB name
$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Get values from query string
$name = isset($_GET['name']) ? htmlspecialchars($_GET['name']) : '';
$phone = isset($_GET['phone']) ? htmlspecialchars($_GET['phone']) : '';
$event = isset($_GET['event']) ? htmlspecialchars($_GET['event']) : '';
$total = isset($_GET['total']) ? htmlspecialchars($_GET['total']) : '0';

$success = false;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['method'])) {
  $payment_method = $_POST['method'];
  $transaction_status = "Success";
  $transaction_date = date('Y-m-d H:i:s'); // current timestamp

  // Prepare INSERT
  $stmt = $conn->prepare("INSERT INTO payments (name, phone, event, total_amount, payment_method, transaction_status, transaction_date) VALUES (?, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("sssisss", $name, $phone, $event, $total, $payment_method, $transaction_status, $transaction_date);

  if ($stmt->execute()) {
  // Redirect to receipt page with name and phone
  header("Location: rec.php?name=" . urlencode($name) . "&phone=" . urlencode($phone));
  exit();
} else {
  echo "Error inserting payment: " . $stmt->error;
}


  $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Payment Page</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #fff5f9;
      margin: 0;
      padding: 20px;
    }

    .container {
      max-width: 650px;
      margin: auto;
      background: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    h2 {
      text-align: center;
      color: #d63384;
    }

    .summary {
      background-color: #ffe6f0;
      padding: 15px;
      border-radius: 8px;
      margin-bottom: 20px;
    }

    .tabs {
      display: flex;
      justify-content: space-around;
      margin-bottom: 20px;
    }

    .tab {
      padding: 10px 20px;
      background-color: #f8d7e3;
      cursor: pointer;
      border-radius: 5px;
      font-weight: bold;
    }

    .tab.active {
      background-color: #d63384;
      color: white;
    }

    .tab-content {
      display: none;
    }

    .tab-content.active {
      display: block;
    }

    form label {
      display: block;
      margin-top: 15px;
      font-weight: bold;
    }

    input[type="text"], input[type="number"] {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border-radius: 4px;
      border: 1px solid #ccc;
    }

    button {
      margin-top: 20px;
      width: 100%;
      background-color: #d63384;
      color: white;
      padding: 12px;
      font-size: 16px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    button:hover {
      background-color: #c21866;
    }

    .success {
      background-color: #d4edda;
      color: #155724;
      padding: 15px;
      border-radius: 5px;
      margin-top: 20px;
      text-align: center;
    }

    .qr-image {
      display: flex;
      justify-content: center;
      margin-top: 20px;
    }

    .qr-image img {
      width: 200px;
      height: 200px;
    }
  </style>
</head>
<body>

<div class="container">
  <h2>Payment Page 💳</h2>

  <div class="summary">
    <p><strong>Name:</strong> <?php echo $name; ?></p>
    <p><strong>Phone:</strong> <?php echo $phone; ?></p>
    <p><strong>Event:</strong> <?php echo $event; ?></p>
    <p><strong>Total:</strong> ₹<?php echo $total; ?></p>
  </div>

  <div class="tabs">
    <div class="tab active" onclick="showTab('credit')">Credit Card</div>
    <div class="tab" onclick="showTab('debit')">Debit Card</div>
    <div class="tab" onclick="showTab('gpay')">GPay</div>
  </div>

  <!-- Credit Card Form -->
  <div class="tab-content active" id="credit">
    <form method="post">
      <input type="hidden" name="method" value="credit">
      <label>Card Number</label>
      <input type="text" name="card_number" placeholder="1234 5678 9012 3456" required>
      <label>Expiry Date</label>
      <input type="text" name="expiry" placeholder="MM/YY" required>
      <label>CVV</label>
      <input type="number" name="cvv" placeholder="123" required>
      <button type="submit">Pay ₹<?php echo $total; ?></button>
    </form>
  </div>

  <!-- Debit Card Form -->
  <div class="tab-content" id="debit">
    <form method="post">
      <input type="hidden" name="method" value="debit">
      <label>Card Number</label>
      <input type="text" name="card_number" placeholder="1234 5678 9012 3456" required>
      <label>Expiry Date</label>
      <input type="text" name="expiry" placeholder="MM/YY" required>
      <label>CVV</label>
      <input type="number" name="cvv" placeholder="123" required>
      <button type="submit">Pay ₹<?php echo $total; ?></button>
    </form>
  </div>

  <!-- GPay Section -->
  <div class="tab-content" id="gpay">
    <form method="post">
      <input type="hidden" name="method" value="gpay">
      <p>Scan the QR code below to pay via GPay:</p>
      <div class="qr-image">
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=upi://pay?pa=your-vpa@okaxis&pn=Your%20Name&am=<?php echo $total; ?>" alt="GPay QR Code">
      </div>
      <button type="submit">I Have Paid ₹<?php echo $total; ?></button>
    </form>
  </div>

  <?php if ($success): ?>
    <div class="success">
      ✅ Payment Successful! Thank you for booking 🎉
    </div>
  <?php endif; ?>
</div>

<script>
  function showTab(tabId) {
    const tabs = document.querySelectorAll('.tab');
    const contents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => tab.classList.remove('active'));
    contents.forEach(content => content.classList.remove('active'));

    document.getElementById(tabId).classList.add('active');
    document.querySelector('.tab[onclick="showTab(\'' + tabId + '\')"]').classList.add('active');
  }
</script>

</body>
</html>