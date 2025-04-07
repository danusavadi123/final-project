<?php
session_start();
require_once('./config/database.php');
require_once('./includes/header.php');
require_once('./includes/navbar.php');

// Handle contact form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    if (!empty($name) && !empty($email) && !empty($message)) {
        // Store message in the database
        $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("sss", $name, $email, $message);
        if ($stmt->execute()) {
            $success = "Message sent successfully!";
        } else {
            $error = "Failed to send message. Please try again.";
        }
    } else {
        $error = "All fields are required.";
    }
}
?>

<section class="py-5 bg-light text-center">
    <div class="container">
        <h1 class="display-5">Contact Us</h1>
        <p class="lead">Have a question or need help? We're here to assist you!</p>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <?php if (isset($success)) { echo "<div class='alert alert-success'>$success</div>"; } ?>
        <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>

        <div class="row">
            <div class="col-md-6 mb-4">
                <img src="assets/images/contact.jpg" class="img-fluid rounded shadow" alt="Contact Image">
            </div>
            <div class="col-md-6">
                <form method="POST" action="">
                    <div class="form-group mb-3">
                        <label for="name">Your Name</label>
                        <input type="text" name="name" id="name" class="form-control" required />
                    </div>
                    <div class="form-group mb-3">
                        <label for="email">Your Email</label>
                        <input type="email" name="email" id="email" class="form-control" required />
                    </div>
                    <div class="form-group mb-3">
                        <label for="message">Your Message</label>
                        <textarea name="message" id="message" rows="5" class="form-control" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php
require_once('./includes/footer.php');
?>