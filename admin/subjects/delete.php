<?php

// Include the functions.php file
require_once '../../functions.php';  
require_once '../partials/header.php';  
require_once '../partials/side-bar.php';  


$subject_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$subject_id) {
    header("Location: add.php"); // Redirect if no valid ID is provided
    exit();
}

// Create a database connection
$conn = connectToDatabase();

// Fetch the subject details using the function from functions.php
$subject = getSubjectDetails($subject_id, $conn);

// If no subject is found, set an error message
if (!$subject) {
    $error_message = "Subject not found.";
}

// Handle the POST request for deleting the subject
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_subject'])) {
    try {
        // Attempt to delete the subject from the database
        if (deleteSubject($subject_id, $conn)) {
            // Set success message and redirect to the "add.php" page
            $_SESSION['delete_success'] = "Subject deleted successfully!";
            header("Location: add.php");
            exit(); // Stop further code execution
        } else {
            $error_message = "Failed to delete the subject.";
        }
    } catch (PDOException $e) {
        // If an error occurs during the deletion, catch the exception and set the error message
        $error_message = "Error deleting subject: " . $e->getMessage();
    }
}
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5">

    <!-- Display the error message if there is any -->
    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($error_message); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Display Subject Details if the subject exists -->
    <?php if ($subject): ?>
        <div class="card-body">
            <h3 class="card-title">Delete Subject</h3><br>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="add.php">Add Subject</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Delete Subject</li>
                </ol>
            </nav>
        </div>

        <div class="card mt-4">
            <div class="card-body">
                <p>Are you sure you want to delete the following subject record?</p>
                <ul>
                    <li><strong>Subject Code:</strong> <?php echo htmlspecialchars($subject['subject_code']); ?></li>
                    <li><strong>Subject Name:</strong> <?php echo htmlspecialchars($subject['subject_name']); ?></li>
                </ul>
                <form method="post">
                    <!-- Cancel button that redirects back to the add page -->
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='add.php'">Cancel</button>
                    <!-- Submit button to confirm deletion -->
                    <button type="submit" name="delete_subject" class="btn btn-primary">Delete Subject Record</button>
                </form>
            </div>
        </div>
    <?php endif; ?>

</main>

<?php require_once '../partials/footer.php';
?>
