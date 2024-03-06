<?php

header('Content-Type: application/json'); // Indicate that you're returning JSON data.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = strip_tags(trim($_POST["fname"]));
    $lname = strip_tags(trim($_POST["lname"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = strip_tags(trim($_POST["message"]));

    // Check that data was sent to the mailer.
    if (empty($fname) OR empty($lname) OR empty($message) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Set a 400 (bad request) response code and exit.
        http_response_code(400);
        echo json_encode(["message" => "Oops! There was a problem with your submission. Please complete the form and try again."]);
        exit;
    }

    // Recipient email (Replace this with your email)
    $recipient = "altamiad@kean.edu";

    // Set the email subject.
    $subject = "New contact from $fname + $lname";

    // Build the email content.
    $email_content = "First Name: $fname\n";
    $email_content = "Last Name: $lname\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Message:\n$message\n";

    // Build the email headers.
    $email_headers = "From: $fname + $lname<$email>";

    // Send the email.
    if (mail($recipient, $subject, $email_content, $email_headers)) {
        // Set a 200 (okay) response code.
        http_response_code(200);
        echo json_encode(["message" => "Thank You! Your message has been sent."]);
    } else {
        // Set a 500 (internal server error) response code.
        http_response_code(500);
        echo json_encode(["message" => "Oops! Something went wrong and we couldn't send your message."]);
    }

} else {
    // Not a POST request, set a 403 (forbidden) response code.
    http_response_code(403);
    echo json_encode(["message" => "There was a problem with your submission, please try again."]);
}
?>
