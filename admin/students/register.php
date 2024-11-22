<?php
require_once '../../functions.php'; 
require_once '../partials/header.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start(); 
}

$errors = []; 
$success_message = ''; 

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $student_id = trim($_POST['student_id']);
    $student_first_name = trim($_POST['student_first_name']);
    $student_last_name = trim($_POST['student_last_name']);

    // Validate student data
    $errors = validateStudentData($student_id, $student_first_name, $student_last_name);

    // If no errors, try to insert the student
    if (empty($errors)) {
        if (insertStudent($student_id, $student_first_name, $student_last_name)) {
            $success_message = "Student added successfully.";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $errors[] = "Failed to add student. Please try again.";
        }
    }
}
?>

<div class="container-fluid">
    <div class="row">
        <?php require_once '../partials/side-bar.php'; ?>
        <div class="col-md-9 col-lg-10">
            <div class="pt-3">
                <h3 class="card-title">Add a New Student</h3><br>
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Student</li>
                    </ol>
                </nav>

                <!-- Display success message -->
                <?php if (!empty($success_message)): ?>
                    <?php displayMessage($success_message, 'success'); ?>
                <?php endif; ?>

                <!-- Display errors -->
                <?php if (!empty($errors)): ?>
                    <?php displayMessage(implode('<br>', $errors), 'danger'); ?>
                <?php endif; ?>

                <!-- Student Form -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form action="" method="POST">
                            <input type="hidden" name="action" value="add">
                            <div class="mb-3">
                                <label for="student_id" class="form-label">Student ID</label>
                                <input type="text" class="form-control" id="student_id" name="student_id" maxlength="10" value="<?php echo isset($_POST['student_id']) ? htmlspecialchars($_POST['student_id']) : ''; ?>" placeholder="Enter Student ID">
                            </div>
                            <div class="mb-3">
                                <label for="student_first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="student_first_name" name="student_first_name" value="<?php echo isset($_POST['student_first_name']) ? htmlspecialchars($_POST['student_first_name']) : ''; ?>" placeholder="Enter First Name">
                            </div>
                            <div class="mb-3">
                                <label for="student_last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="student_last_name" name="student_last_name" value="<?php echo isset($_POST['student_last_name']) ? htmlspecialchars($_POST['student_last_name']) : ''; ?>" placeholder="Enter Last Name">
                            </div>
                            <button type="submit" class="btn btn-primary">Add Student</button>
                        </form>
                    </div>
                </div>

               <!-- Student List -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title ms-3">Student List</h5> 
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Student ID</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $students = getAllStudents();

                                    if (count($students) > 0):
                                        foreach ($students as $student):
                                    ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($student['student_id']); ?></td>
                                            <td><?php echo htmlspecialchars($student['first_name']); ?></td>
                                            <td><?php echo htmlspecialchars($student['last_name']); ?></td>
                                            <td class>
                                                <a href="edit.php?id=<?php echo htmlspecialchars($student['id']); ?>" class="btn btn-sm btn-info">Edit</a>
                                                <a href="delete.php?id=<?php echo htmlspecialchars($student['id']); ?>" class="btn btn-sm btn-danger">Delete</a>
                                            </td> 
                                        </tr>
                                    <?php
                                        endforeach;
                                    else:
                                    ?>
                                        <tr>
                                            <td colspan="4" class="text-center">No students available.</td>
                                        </tr>
                                    <?php endif; ?>
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
