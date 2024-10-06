<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "feedback_db";
$port = "4306";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $contactNumber = $_POST["contactNumber"];
    $likedSection = $_POST["likedSection"];
    $improvementSection = $_POST["improvementSection"];
    $likedSectionComment = $_POST["likedSectionComment"];
    $improvementSectionComment = $_POST["improvementSectionComment"];
    $rating = $_POST["rating"];
    $recaptcha = $_POST['g-recaptcha-response'];
    $secret_key = '6Ldrg1gqAAAAAAUTe2imQSGfy0SnyP2xgdoJpx5F';

    // Verify the reCAPTCHA response
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret_key}&response={$recaptcha}");
    $response_keys = json_decode($response, true);

    if (intval($response_keys["success"]) !== 1) {
        echo "Please complete the CAPTCHA.";
    } else {
        // Prepare and bind the SQL statement
        $stmt = $conn->prepare("INSERT INTO feedback_table (name, email, contact_number, liked_section, improvement_section, liked_section_comment, improvement_section_comment, rating)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $name, $email, $contactNumber, $likedSection, $improvementSection, $likedSectionComment, $improvementSectionComment, $rating);

        if ($stmt->execute() === TRUE) {
            echo "Thank you for your feedback!";
            echo "<br>Name: $name";
            echo "<br>Email: $email";
            echo "<br>Contact Number: $contactNumber";
            echo "<br>Liked Section: $likedSection";
            echo "<br>Improvement Section: $improvementSection";
            echo "<br>Liked Section Comment: $likedSectionComment";
            echo "<br>Improvement Section Comment: $improvementSectionComment";
            echo "<br>Rating: $rating";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>