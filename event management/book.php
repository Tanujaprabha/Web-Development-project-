<?php
$host = "localhost";
$db = "mobile";
$user = "root";
$pass = "";
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$success = false;
$selectedServices = [];
$total = 0;
$paymentMethod = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = isset($_POST['name']) ? $conn->real_escape_string($_POST['name']) : '';
  $phone = isset($_POST['phone']) ? $conn->real_escape_string($_POST['phone']) : '';
  $total = isset($_POST['total']) ? $_POST['total'] : 0;
  $paymentMethod = isset($_POST['payment_method']) ? $conn->real_escape_string($_POST['payment_method']) : '';

  if (isset($_POST['services'])) {
    if (is_array($_POST['services'])) {
        $selectedServices = $_POST['services'];
    } else {
        $selectedServices = explode(", ", $_POST['services']);
    }
    $services = implode(", ", $selectedServices);
  } else {
    $selectedServices = [];
    $services = '';
  }

  if (!empty($name) && !empty($phone)) {
    $sql = "INSERT INTO bookings (name, phone, services, total, payment_method)
            VALUES ('$name', '$phone', '$services', '$total', '$paymentMethod')";

    if ($conn->query($sql) === TRUE) {
      $success = true;
    } else {
      echo "Error: " . $conn->error;
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Book Birthday Package</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #d7ecff;
      margin: 0;
      padding: 20px;
    }

    .container {
      max-width: 500px;
      margin: auto;
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    h2 {
      text-align: center;
      color: #333;
    }

    form label {
      margin-top: 15px;
      display: block;
      font-weight: bold;
    }

    input[type="text"],
    input[type="tel"],
    select {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border-radius: 4px;
      border: 1px solid #ccc;
    }

    button {
      margin-top: 20px;
      width: 100%;
      background-color: #3498db;
      color: white;
      padding: 12px;
      font-size: 16px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    button:hover {
      background-color: #2980b9;
    }

    .success {
      background-color: #d4edda;
      padding: 15px;
      border-radius: 5px;
      color: #155724;
      margin-bottom: 20px;
      text-align: center;
    }

    .details {
      margin-top: 15px;
      background-color: #f4f4f4;
      padding: 10px;
      border-radius: 5px;
    }
  </style>
</head>
<body>

  <div class="container">
    <h2>Book Your Birthday Bash 🎉</h2>

    <?php if ($success): ?>
      <div class="success">✅ Booking & Payment Successful!</div>
      <div class="details">
        <p><strong>Name:</strong> <?php echo htmlspecialchars($name); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($phone); ?></p>
        <p><strong>Services:</strong> <?php echo htmlspecialchars($services); ?></p>
        <p><strong>Total:</strong> ₹<?php echo htmlspecialchars($total); ?></p>
        <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($paymentMethod); ?></p>
      </div>
    <?php endif; ?>

    <form method="post">
      <label for="name">Full Name</label>
      <input type="text" name="name" required>

      <label for="phone">Phone</label>
      <input type="tel" name="phone" required>

      <!-- Services and Total will be auto-filled from the form logic -->
      <input type="hidden" name="services" value="<?php echo htmlspecialchars(implode(", ", $selectedServices)); ?>">
      <input type="hidden" name="total" value="<?php echo isset($total) ? htmlspecialchars($total) : 0; ?>">

      <label for="payment_method">Select Payment Method</label>
      <select name="payment_method" required>
        <option value="">-- Choose Payment Method --</option>
        <option value="VISA">VISA</option>
        <option value="Credit Card">Credit Card</option>
        <option value="GPay">GPay</option>
      </select>

      <label>Total Cost:</label>
      <p>₹<?php echo isset($total) ? htmlspecialchars($total) : 0; ?></p>

      <button type="submit">Proceed to Payment</button>
    </form>
  </div>

</body>
</html>
