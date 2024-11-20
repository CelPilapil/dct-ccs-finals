<?php
require_once '../../functions.php';  
require_once '../partials/header.php';  
require_once '../partials/side-bar.php';  



?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5">

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
                    <li><strong>Subject Code:</strong></li>
                    <li><strong>Subject Name:</strong></li>
                </ul>
                <form method="post">
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='add.php'">Cancel</button>
                    <button type="submit" name="delete_subject" class="btn btn-primary">Delete Subject Record</button>
                </form>
            </div>
        </div>

</main>

<?php
// Include the footer file
require_once '../partials/footer.php';
?>
