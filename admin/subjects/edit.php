<?php
require_once '../../functions.php';
require_once '../partials/header.php';

?>

<div class="container-fluid">
    <div class="row">
        <?php require_once '../partials/side-bar.php'; ?>
        <div class="col-md-10">
            <div class="card-body">
                <div class="pt-5">
                    <h3 class="card-title">Edit Subject</h3><br>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="add.php">Add Subject</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Edit Subject</li>
                            </ol><br>
               
                </div>

                <!-- Display errors if any -->
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

                            <div class="mb-3">
                                <label for="subject_code" class="form-label">Subject Code</label>
                                <input type="text" class="form-control" id="subject_code" name="subject_code" placeholder= "Enter Subject Code"readonly>
                            </div>

                            <div class="mb-3">
                                <label for="subject_name" class="form-label">Subject Name</label>
                                <input type="text" class="form-control" id="subject_name" name="subject_name"  placeholder="Enter Subject Name">
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
