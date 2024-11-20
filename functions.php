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
    } elseif (strlen($subject_code) != 4) {
        $errors[] = "Subject code must be exactly 4 characters.";
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
