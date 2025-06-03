<?php
$host = "localhost";
$db = "mobile";
$user = "root";
$pass = "";
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$selectedServices = [];
$total = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = isset($_POST['name']) ? $conn->real_escape_string($_POST['name']) : '';
  $phone = isset($_POST['phone']) ? $conn->real_escape_string($_POST['phone']) : '';
  $event_name = isset($_POST['event_name']) ? $conn->real_escape_string($_POST['event_name']) : '';
  $total = isset($_POST['total']) ? $_POST['total'] : 0;

  if (isset($_POST['services'])) {
    if (is_array($_POST['services'])) {
        $selectedServices = $_POST['services'];
    } else {
        $selectedServices = explode(", ", $_POST['services']);
    }
    $services = implode(", ", $selectedServices);
  } else {
    $services = '';
  }

  if (!empty($name) && !empty($phone) && !empty($event_name)) {
    $sql = "INSERT INTO bookings (name, phone, event_name, services, total)
            VALUES ('$name', '$phone', '$event_name', '$services', '$total')";

    if ($conn->query($sql) === TRUE) {
      // Redirect to pay.php with booking details
      header("Location: pay.php?name=" . urlencode($name) .
                            "&phone=" . urlencode($phone) .
                            "&event=" . urlencode($event_name) .
                            "&total=" . urlencode($total));
      exit();
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
  <title>Book Event Package</title>
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
    input[type="tel"] {
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
  </style>
</head>
<body>

  <div class="container">
    <h2>Book Your Event 🎉</h2>

    <form method="post">
      <label for="name">Full Name</label>
      <input type="text" name="name" required>

      <label for="phone">Phone</label>
      <input type="tel" name="phone" required>

      <label for="event_name">Event Name</label>
      <input type="text" name="event_name" required>

      <!-- Hidden fields for services and total -->
      <input type="hidden" name="services" value="<?php echo htmlspecialchars(implode(", ", $selectedServices)); ?>">
      <input type="hidden" name="total" value="<?php echo isset($total) ? htmlspecialchars($total) : 0; ?>">

      <label>Total Cost:</label>
      <p>₹<?php echo isset($total) ? htmlspecialchars($total) : 0; ?></p>

      <button type="submit">Confirm Booking</button>
    </form>
  </div>

</body>
</html>
