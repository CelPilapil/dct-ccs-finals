<?php
ob_start(); // Start output buffering

require_once '../../functions.php';  
require_once '../partials/header.php';  
require_once '../partials/side-bar.php';  // Ensure no extra output here

// Get the student ID from the URL query parameter and validate it
$student_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$student_id) {
    header("Location: register.php"); // Redirect if no valid ID is provided
    exit();
}

// Create a database connection
$conn = connectToDatabase();
$student = getStudentDetails($student_id, $conn);

// Handle the POST request for deleting the student
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_student'])) {
    try {
        // Attempt to delete the student from the database
        if (deleteStudent($student_id, $conn)) {
            // Set success message and redirect to the "register.php" page
            $_SESSION['delete_success'] = "Student deleted successfully!";
            // Redirect after deletion
            header("Location: register.php");
            exit(); // Ensure no further code is executed after the redirect
        } else {
            $error_message = "Failed to delete the student.";
        }
    } catch (PDOException $e) {
        // If an error occurs during the deletion, catch the exception and set the error message
        $error_message = "Error deleting student: " . $e->getMessage();
    }
}
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5">
    <?php if ($student): ?>
        <div class="card-body">
            <h3 class="card-title">Delete Student</h3><br>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="register.php">Student List</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Delete Student</li>
                </ol>
            </nav>
        </div>

        <div class="card mt-4">
            <div class="card-body">
                <p><strong>Are you sure you want to delete the following student record?</strong></p>
                <ul>
                    <li><strong>Student ID:</strong> <?php echo htmlspecialchars($student['student_id']); ?></li>
                    <li><strong>First Name:</strong> <?php echo htmlspecialchars($student['first_name']); ?></li>
                    <li><strong>Last Name:</strong> <?php echo htmlspecialchars($student['last_name']); ?></li>
                </ul>
                <form method="post">
                    <!-- Cancel button that redirects back to the student list -->
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='register.php'">Cancel</button>
                    <!-- Submit button to confirm deletion -->
                    <button type="submit" name="delete_student" class="btn btn-danger">Delete Student Record</button>
                </form>
            </div>
        </div>
    <?php endif; ?>

</main>

<?php
// Include footer
require_once '../partials/footer.php';

// End output buffering and flush content
ob_end_flush();
?>
