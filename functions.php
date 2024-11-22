<?php
session_start();

function connectToDatabase() {
    $dsn = 'mysql:host=localhost;dbname=dct-ccs-finals';
    $username = 'root';
    $password = '';

    try {
        $connection = new PDO($dsn, $username, $password);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die('Connection failed: ' . $e->getMessage());
    }

    return $connection;
}

function validateUser($email, $password) {
    $connection = connectToDatabase();

    $hashedPassword = md5($password);
    $sql = 'SELECT * FROM users WHERE email = :email AND password = :password';
    $stmt = $connection->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->execute();

    if ($stmt->rowCount() === 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        return true;
    } else {
        return false;
    }
}

function checkUserSession() {
    return isset($_SESSION['user_id']);
}

function validateLoginInput($email, $password) {
    $errors = [];

    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($password)) {
        $errors[] = "Password is required.";
    }

    return $errors;
}

function validateSubjectInput($subject_code, $subject_name, &$errors) {
    if (empty($subject_code)) {
        $errors[] = "Subject code is required.";
    }
    if (empty($subject_name)) {
        $errors[] = "Subject name is required.";
    }

    return empty($errors);
}

function subjectExists($value, $field, &$errors) {
    $conn = connectToDatabase();
    $stmt = $conn->prepare("SELECT * FROM subjects WHERE $field = :value");
    $stmt->bindParam(':value', $value);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $errors[] = ucfirst($field) . " already exists.";
        return true;
    }
    return false;
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
function getSubjectDetails($subject_id, $conn) {
    $query = "SELECT * FROM subjects WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $subject_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Function to delete a subject from the database
function deleteSubject($subject_id, $conn) {
    $delete_query = "DELETE FROM subjects WHERE id = :id";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bindParam(':id', $subject_id, PDO::PARAM_INT);
    
    return $delete_stmt->execute();
}

function logout() {
    session_start(); 
    session_destroy(); 
    header("Location: ../index.php"); // Redirect to login page
    exit(); 
}

// Function to validate student data
function validateStudentData($student_id, $student_first_name, $student_last_name) {
    $errors = [];

    if (empty($student_id) || !ctype_digit($student_id)) {
        $errors[] = "Student ID must be a valid integer.";
    }
    if (empty($student_first_name)) {
        $errors[] = "Student first name is required.";
    }
    if (empty($student_last_name)) {
        $errors[] = "Student last name is required.";
    }

    return $errors;
}

// Function to insert new student data
function insertStudent($student_id, $student_first_name, $student_last_name) {
    try {
        $conn = connectToDatabase();
        $stmt = $conn->prepare("INSERT INTO students (student_id, first_name, last_name) VALUES (:student_id, :first_name, :last_name)");
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        $stmt->bindParam(':first_name', $student_first_name, PDO::PARAM_STR);
        $stmt->bindParam(':last_name', $student_last_name, PDO::PARAM_STR);

        return $stmt->execute();
    } catch (Exception $e) {
        return false;
    } finally {
        $conn = null;
    }
}

// Function to fetch all students from the database
function getAllStudents() {
    try {
        $conn = connectToDatabase();
        $stmt = $conn->query("SELECT * FROM students");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        return [];
    } finally {
        $conn = null;
    }
}

function displayMessage($message, $type = 'success') {
    echo '<div class="alert alert-' . $type . ' alert-dismissible fade show" role="alert">';
    echo htmlspecialchars($message);
    echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
    echo '</div>';
}