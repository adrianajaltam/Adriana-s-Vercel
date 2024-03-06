<?php

header('Content-Type: application/json'); // Indicate that you're returning JSON data.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = strip_tags(trim($_POST["fname"]));
    $lname = strip_tags(trim($_POST["lname"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $linkedinUrl = filter_var(trim($_POST["linkedinUrl"]), FILTER_SANITIZE_URL);
    $projectName = strip_tags(trim($_POST["projectName"]));
    $yourRole = strip_tags(trim($_POST["yourRole"]));
    $desiredContribution = strip_tags(trim($_POST["desiredContribution"]));
    $projectDescription = strip_tags(trim($_POST["projectDescription"]));
    $personalDescription = strip_tags(trim($_POST["personalDescription"])); // Capture personal description


    // Check that data was sent to the mailer.
    if (empty($fname) OR empty($lname) OR !filter_var($email, FILTER_VALIDATE_EMAIL) OR empty($linkedinUrl) OR empty($projectName) OR empty($yourRole) OR empty($desiredContribution) OR empty($projectDescription) OR empty($personalDescription)) {
        // Set a 400 (bad request) response code and exit.
        http_response_code(400);
        echo json_encode(["message" => "Oops! There was a problem with your submission. Please complete all fields and try again."]);
        exit;
    }

    // Recipient email (Replace this with your email)
    $recipient = "altamiad@kean.edu";

    // Set the email subject.
    $subject = "Collaboration Proposal from $fname + $lname";

    // Build the email content.
    $email_content = "First Name: $fname\n";
    $email_content = "Last Name: $lname\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "LinkedIn Profile: $linkedinUrl\n";
    $email_content .= "Project Name: $projectName\n";
    $email_content .= "Your Role: $yourRole\n";
    $email_content .= "Desired Contribution from Me: $desiredContribution\n";
    $email_content .= "Project Description: $projectDescription\n";
    $email_content .= "Personal Description: $personalDescription\n"; // Include personal description in email content


    // Build the email headers.
    $email_headers = "From: $fname + $lname <$email>";

    // Send the email.
    if (mail($recipient, $subject, $email_content, $email_headers)) {
        // Set a 200 (okay) response code.
        http_response_code(200);
        echo json_encode(["message" => "Thank You! Your collaboration proposal has been sent. I look forward to reviewing it!"]);
    } else {
        // Set a 500 (internal server error) response code.
        http_response_code(500);
        echo json_encode(["message" => "Oops! Something went wrong and we couldn't send your proposal."]);
    }

} else {
    // Not a POST request, set a 403 (forbidden) response code.
    http_response_code(403);
    echo json_encode(["message" => "There was a problem with your submission, please try again."]);
}
?>
