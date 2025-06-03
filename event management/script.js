function bookEvent(eventName, price) {
  const confirmation = confirm(
    `You selected the "${eventName}" package for ₹${price}.\nDo you want to proceed to booking?`
  );
  if (confirmation) {
    // You can redirect to a booking form page or show a booking section
    window.location.href = `booking.html?event=${encodeURIComponent(eventName)}&price=${price}`;
  }
}
