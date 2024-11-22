<?php
require_once '../../functions.php';
require_once '../partials/header.php';


$student_data = ['student_id' => '', 'first_name' => '', 'last_name' => ''];


// Check if student ID is provided via GET request for editing
if (isset($_GET['id'])) {
    $student_id = intval($_GET['id']);
    $student_data = getStudentData($student_id);
}

// Handle the form submission for updating the student
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input data
    $student_id = intval($_POST['student_id']);
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);

    // Proceed with updating the student if no validation errors
    if (empty($errors)) {
        if (updateStudentData($student_id, $first_name, $last_name)) {
            // Redirect after successful update
            header("Location: add.php"); // Assuming 'add.php' is the page listing students
            exit();
        }
    }
}
?>

<div class="container-fluid">
    <div class="row">
        <?php require_once '../partials/side-bar.php'; ?>

        <!-- Edit Student Content -->
        <div class="col-md-9">
            <div class="pt-5">
                <h3 class="card-title">Edit Student</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="register.php">Add Student</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Student</li>
                    </ol><br>
                </nav>
            </div>

            <!-- Display Errors -->
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>System Errors</strong>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- Edit Student Form -->
            <div class="card mb-4">
                <div class="card-body">
                    <form action="" method="POST">
                        <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($student_data['id']); ?>">
                        <div class="mb-3">
                            <label for="student_id" class="form-label">Student ID</label>
                            <input type="text" class="form-control" id="student_id" name="student_id" value="<?php echo htmlspecialchars($student_data['student_id']); ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($student_data['first_name']); ?>" placeholder="Enter First Name">
                        </div>
                        <div class="mb-3">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($student_data['last_name']); ?>" placeholder="Enter Last Name">
                        </div>
                        <button type="submit" class="btn btn-primary">Update Student</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../partials/footer.php'; ?>
