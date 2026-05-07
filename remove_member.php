<?php
session_start();
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $member_id = $_POST['member_id'];

    $stmt = $pdo->prepare("
        DELETE FROM club_members
        WHERE id = ?
    ");

    $stmt->execute([$member_id]);

    header("Location: Admin_ClubManagement.php");
    exit();
}
?>