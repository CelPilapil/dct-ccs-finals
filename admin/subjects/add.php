<?php
require_once '../../functions.php'; // Including functions.php
require_once '../partials/header.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start session if not already started
}

$errors = []; // Initialize error messages array
$success_message = ''; // Success message variable

// Handle Add Subject Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $subject_code = trim($_POST['subject_code']);
    $subject_name = trim($_POST['subject_name']);

    // Validate input
    validateSubjectInput($subject_code, $subject_name, $errors);

    // If no errors in validation, proceed with subject insertion
    if (empty($errors)) {
        if (insertSubject($subject_code, $subject_name, $errors)) {
            $success_message = "Subject added successfully.";
            // Redirect after successful submission to prevent resubmission on refresh
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }
}
?>

<div class="container-fluid">
    <div class="row">
        <?php require_once '../partials/side-bar.php'; ?>
        <div class="col-md-9 col-lg-10">
            <div class="pt-3">
                <h3 class="card-title">Add a New Subject</h3><br>
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Subject</li>
                    </ol>
                </nav>

                <!-- Display success message -->
                <?php if (!empty($success_message)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($success_message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Display errors -->
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

                <!-- Subject Form -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form action="" method="POST">
                            <input type="hidden" name="action" value="add">
                            <div class="mb-3">
                                <label for="subject_code" class="form-label">Subject Code</label>
                                <input type="text" class="form-control" id="subject_code" name="subject_code" maxlength="4" value="<?php echo isset($_POST['subject_code']) ? htmlspecialchars($_POST['subject_code']) : ''; ?>" placeholder="Enter Subject Code">
                            </div>
                            <div class="mb-3">
                                <label for="subject_name" class="form-label">Subject Name</label>
                                <input type="text" class="form-control" id="subject_name" name="subject_name" value="<?php echo isset($_POST['subject_name']) ? htmlspecialchars($_POST['subject_name']) : ''; ?>" placeholder="Enter Subject Name">
                            </div>
                            <button type="submit" class="btn btn-primary">Add Subject</button>
                        </form>
                    </div>
                </div>

                <!-- Subject List -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Subject List</h5>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Subject Code</th>
                                        <th>Subject Name</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $conn = connectToDatabase();
                                    $result = $conn->query("SELECT * FROM subjects");

                                    if ($result->rowCount() > 0):
                                        while ($subject = $result->fetch(PDO::FETCH_ASSOC)):
                                    ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($subject['subject_code']); ?></td>
                                            <td><?php echo htmlspecialchars($subject['subject_name']); ?></td>
                                            <td>
                                                <a href="edit.php?id=<?php echo htmlspecialchars($subject['id']); ?>" class="btn btn-sm btn-info">Edit</a>
                                                <a href="delete.php?id=<?php echo htmlspecialchars($subject['id']); ?>" class="btn btn-sm btn-danger">Delete</a>
                                            </td>
                                        </tr>
                                    <?php
                                        endwhile;
                                    else:
                                    ?>
                                        <tr>
                                            <td colspan="3" class="text-center">No subjects available.</td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php $conn = null; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../partials/footer.php'; ?>
