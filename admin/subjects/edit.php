<?php
require_once '../../functions.php';
require_once '../partials/header.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$errors = []; // Error messages array
$subject_data = ['subject_code' => '', 'subject_name' => ''];

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $subject_id = intval($_POST['subject_id']);
    $subject_name = trim($_POST['subject_name']);

    // Validate input
    if (empty($subject_name)) {
        $errors[] = "Subject name is required.";
    } elseif (strlen($subject_name) < 3) {
        $errors[] = "Subject name must be at least 3 characters long.";
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
                    <div class="alert alert-danger alert-dismissible" role="alert">
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
                            <input type="hidden" name="subject_id" value="<?php echo htmlspecialchars($subject_data['subject_code']); ?>">
                            <div class="mb-3">
                                <label for="subject_code" class="form-label">Subject Code</label>
                                <input type="text" class="form-control" id="subject_code" name="subject_code" value="<?php echo htmlspecialchars($subject_data['subject_code']); ?>" placeholder="Enter Subject Name" readonly>
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
</div>

<?php require_once '../partials/footer.php'; ?>
