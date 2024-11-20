<?php
require_once '../../functions.php';
require_once '../partials/header.php';
 require_once '../partials/side-bar.php'; 



$errors = []; // Error messages array
$success_message = ''; // Success message

// Handle Add Subject Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $subject_code = trim($_POST['subject_code']);
    $subject_name = trim($_POST['subject_name']);

    // Validate input
    if (validateSubjectInput($subject_code, $subject_name, $errors)) {
        if (insertSubject($subject_code, $subject_name, $errors)) {
            $success_message = "Subject added successfully.";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }
}

function validateSubjectInput($subject_code, $subject_name, &$errors) {
    if (empty($subject_code)) {
        $errors[] = "Subject code is required.";
    } elseif (strlen($subject_code) != 4) {
        $errors[] = "Subject code must be exactly 4 characters.";
    }

    if (empty($subject_name)) {
        $errors[] = "Subject name is required.";
    }

    return empty($errors);
}

function insertSubject($subject_code, $subject_name, &$errors) {
    $conn = connectToDatabase();

    // Check for duplicate subject code
    $stmt = $conn->prepare("SELECT * FROM subjects WHERE subject_code = :subject_code");
    $stmt->bindParam(':subject_code', $subject_code);
    $stmt->execute();
    if ($stmt->fetch(PDO::FETCH_ASSOC)) {
        $errors[] = "Subject code already exists.";
        return false;
    }

    // Check for duplicate subject name
    $stmt = $conn->prepare("SELECT * FROM subjects WHERE subject_name = :subject_name");
    $stmt->bindParam(':subject_name', $subject_name);
    $stmt->execute();
    if ($stmt->fetch(PDO::FETCH_ASSOC)) {
        $errors[] = "Subject name already exists.";
        return false;
    }

    // Insert new subject
    $stmt = $conn->prepare("INSERT INTO subjects (subject_code, subject_name) VALUES (:subject_code, :subject_name)");
    $stmt->bindParam(':subject_code', $subject_code);
    $stmt->bindParam(':subject_name', $subject_name);
    if ($stmt->execute()) {
        return true;
    } else {
        $errors[] = "Error adding subject. Please try again.";
        return false;
    }
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-9 col-lg-10">
            <div class="pt-5">
                <h3 class="card-title">Add a New Subject</h3><br>
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Subject</li>
                    </ol>
                </nav>
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
