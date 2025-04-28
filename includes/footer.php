<!-- footer.php -->
<footer class="footer">
  <div class="footer-container">
    <div class="footer-left">
      <h3>ShopEase</h3>
      <p>&copy; <?php echo date("Y"); ?> ShopEase. All rights reserved.</p>
    </div>
    <div class="footer-right">
      <a href="#">Privacy Policy</a>
      <a href="#">Terms of Service</a>
      <a href="#">Contact</a>
    </div>
  </div>
</footer>

<style>
  .footer {
    background-color: #1a1a1a;
    color: #f0f0f0;
    padding: 20px 40px;
    position: fixed;
    bottom: 0;
    width: 100%;
    font-family: 'Segoe UI', sans-serif;
  }

  .footer-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
  }

  .footer-left h3 {
    margin: 0;
    font-size: 24px;
    color: #ffca28;
  }

  .footer-left p {
    margin: 5px 0 0;
    font-size: 14px;
    color: #ccc;
  }

  .footer-right a {
    margin-left: 20px;
    color: #f0f0f0;
    text-decoration: none;
    font-size: 14px;
  }

  .footer-right a:hover {
    color: #ffca28;
  }

  /* Sticky footer support */
  html, body {
    height: 100%;
    margin: 0;
  }

  body {
    display: flex;
    flex-direction: column;
  }

  main {
    flex: 1;
  }
</style>
