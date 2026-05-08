<?php
session_start();
include '../db.php';

$app_id = $_POST['application_id'];
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("DELETE FROM club_applications WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $app_id, $user_id);
$stmt->execute();

header("Location: Student_Application.php");
exit();
?>