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

?>