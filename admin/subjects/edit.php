<?php
require_once '../../functions.php';
require_once '../partials/header.php';


// Initialize error messages and subject data
$errors = [];
$subject_data = ['subject_code' => '', 'subject_name' => ''];

// Check if subject ID is provided via GET request for editing
if (isset($_GET['id'])) {
    $subject_id = intval($_GET['id']);
    $conn = connectToDatabase();

    try {
        $stmt = $conn->prepare("SELECT * FROM subjects WHERE id = :id");
        $stmt->bindParam(':id', $subject_id, PDO::PARAM_INT);
        $stmt->execute();
        $subject_data = $stmt->fetch(PDO::FETCH_ASSOC);

        // If no subject found
        if (!$subject_data) {
            $errors[] = "Subject not found.";
        }
    } catch (PDOException $e) {
        $errors[] = "Error retrieving subject details: " . $e->getMessage();
    }
}

// Handle the form submission for updating the subject
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate input data
    $subject_id = intval($_POST['subject_id']);
    $subject_name = trim($_POST['subject_name']);

    if (empty($subject_name)) {
        $errors[] = "Subject name is required.";
    } elseif (strlen($subject_name) < 3) {
        $errors[] = "Subject name must be at least 3 characters long.";
    }

    // Proceed with updating the subject if there are no validation errors
    if (empty($errors)) {
        $conn = connectToDatabase();

        try {
            // Update the subject name in the database without checking for duplicates
            $stmt = $conn->prepare("UPDATE subjects SET subject_name = :subject_name WHERE id = :id");
            $stmt->bindParam(':subject_name', $subject_name, PDO::PARAM_STR);
            $stmt->bindParam(':id', $subject_id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                // Redirect after successful update
                header("Location: add.php");
                exit();
            } else {
                $errors[] = "Error updating subject. Please try again.";
            }
        } catch (PDOException $e) {
            $errors[] = "Error updating subject: " . $e->getMessage();
        }
    }
}
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <?php require_once '../partials/side-bar.php'; ?>
        <!-- Edit Subject Content -->
        <div class="col-md-10">
            <div class="pt-5">
                <h3 class="card-title">Edit Subject</h3><br>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="add.php">Add Subject</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Subject</li>
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

            <!-- Edit Subject Form -->
            <div class="card mb-4">
                <div class="card-body">
                    <form action="" method="POST">
                        <input type="hidden" name="subject_id" value="<?php echo htmlspecialchars($subject_data['id']); ?>">
                        <div class="mb-3">
                            <label for="subject_code" class="form-label">Subject Code</label>
                            <input type="text" class="form-control" id="subject_code" name="subject_code" value="<?php echo htmlspecialchars($subject_data['subject_code']); ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="subject_name" class="form-label">Subject Name</label>
                            <input type="text" class="form-control" id="subject_name" name="subject_name" value="<?php echo htmlspecialchars($subject_data['subject_name']); ?>" placeholder="Enter Subject Name">
                        </div>
                        <button type="submit" class="btn btn-primary">Update Subject</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../partials/footer.php'; ?>
