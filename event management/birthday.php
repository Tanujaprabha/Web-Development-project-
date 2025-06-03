<?php
$services = [
  "Decoration" => 3000,
  "Cake" => 1500,
  "Games & Activities" => 2000,
  "Photography" => 2500,
  "Catering" => 4000,
  "DJ Music" => 1800,
  "Return Gifts" => 1000
];

$total = 0;
$selectedServices = [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['services'])) {
  $selectedServices = $_POST['services'];
  foreach ($selectedServices as $service) {
    $total += $services[$service];
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Birthday Party Package</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #d7ecff;
      margin: 0;
      padding: 0;
    }

    .sticky-header {
      position: fixed;
      top: 0;
      width: 100%;
      background-color: #fff;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 40px;
      z-index: 1000;
    }

    .logo {
      font-size: 24px;
      font-weight: bold;
      color: #2c3e50;
    }

    nav ul {
      list-style: none;
      display: flex;
      gap: 20px;
      margin: 0;
      padding: 0;
    }

    nav a {
      text-decoration: none;
      color: #2c3e50;
      font-weight: 500;
      transition: color 0.3s;
    }

    nav a:hover {
      color: #007bff;
    }

    .container {
      max-width: 700px;
      margin: 140px auto 30px; /* adjust top margin due to sticky header */
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    h1 {
      text-align: center;
      color: #2c3e50;
      margin-bottom: 20px;
    }

    p.catchy {
      font-size: 18px;
      color: #555;
      text-align: center;
      margin-bottom: 30px;
    }

    .service {
      display: flex;
      justify-content: space-between;
      margin: 10px 0;
      font-size: 16px;
    }

    input[type="submit"] {
      background-color: #3498db;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
      display: block;
      margin: 20px auto 0;
      transition: background-color 0.3s;
    }

    input[type="submit"]:hover {
      background-color: #2980b9;
    }

    .result {
      margin-top: 30px;
      padding: 20px;
      background-color: #eaf6ff;
      border: 1px solid #b3d9f5;
      border-radius: 8px;
    }

    .result h3 {
      margin-bottom: 10px;
      color: #2c3e50;
    }

    ul.selected {
      list-style: disc;
      padding-left: 20px;
    }

    .book-btn {
      background-color: #2ecc71;
      color: white;
      padding: 10px 25px;
      font-size: 16px;
      border: none;
      border-radius: 5px;
      margin: 20px auto 0;
      cursor: pointer;
      display: block;
      transition: background-color 0.3s ease;
    }

    .book-btn:hover {
      background-color: #27ae60;
    }

    @media screen and (max-width: 768px) {
      .container {
        margin: 15px;
        padding: 20px;
      }

      .service {
        flex-direction: column;
        align-items: flex-start;
      }

      .service span {
        margin-top: 5px;
      }

      nav ul {
        flex-direction: column;
        gap: 10px;
      }

      .sticky-header {
          position: fixed;
          top: 0;
          width: 100%;
          background-color: #fff;
          box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
          display: flex;
          justify-content: space-between;
          align-items: center;
          padding: 20px 40px; /* increased vertical padding */
          z-index: 1000;
      }

    }
  </style>
</head>
<body>
  <header class="sticky-header">
    <div class="logo">EventEase</div>
    <nav>
      <ul>
        <li><a href="./dashboard.html">Home</a></li>
        <li><a href="event.html">Event</a></li>
        <li><a href="#">About</a></li>
        <li><a href="#">Contact</a></li>
      </ul>
    </nav>
  </header>

  <div class="container">
    <h1>Birthday Bash Planner 🎉</h1>
    <p class="catchy">Make your child’s birthday magical with our hand-picked services — fun, delicious, and memorable!</p>

    <form method="post">
      <?php foreach ($services as $service => $price): ?>
        <div class="service">
          <label>
            <input type="checkbox" name="services[]" value="<?php echo $service; ?>"
              <?php echo in_array($service, $selectedServices) ? 'checked' : ''; ?>>
            <?php echo $service; ?>
          </label>
          <span>₹<?php echo $price; ?></span>
        </div>
      <?php endforeach; ?>
      
      <input type="submit" value="Calculate Total">
    </form>

    <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
      <div class="result">
        <h3>You selected:</h3>
        <?php if (!empty($selectedServices)): ?>
          <ul class="selected">
            <?php foreach ($selectedServices as $service): ?>
              <li><?php echo $service; ?> - ₹<?php echo $services[$service]; ?></li>
            <?php endforeach; ?>
          </ul>
          <p><strong>Total Cost: ₹<?php echo $total; ?></strong></p>
          
          <!-- Book Now Button -->
          <form method="post" action="booknow.php">
            <?php foreach ($selectedServices as $service): ?>
              <input type="hidden" name="services[]" value="<?php echo $service; ?>">
            <?php endforeach; ?>
            <input type="hidden" name="total" value="<?php echo $total; ?>">
            <button type="submit" class="book-btn">Book Now</button>
          </form>

        <?php else: ?>
          <p>No services selected.</p>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>
