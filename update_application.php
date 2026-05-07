<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../logIn.php");
    exit();
}

$pdo = new PDO("mysql:host=localhost;dbname=it26b_StudentClubRecruitment;charset=utf8", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id = $_POST['application_id'] ?? null;
    // Fix: Get the status from POST first, then lowercase it
    $status = isset($_POST['status']) ? strtolower(trim($_POST['status'])) : null;

    if ($id && $status) {
        // 1. Update application status
        $stmt = $pdo->prepare("UPDATE club_applications SET status = :status WHERE id = :id");
        $stmt->execute([':status' => $status, ':id' => $id]);

        // 2. Handle Membership
        if ($status === "approved") {
            $get = $pdo->prepare("SELECT user_id, club_id FROM club_applications WHERE id = ?");
            $get->execute([$id]);
            $data = $get->fetch(PDO::FETCH_ASSOC);

            if ($data) {
                $check = $pdo->prepare("SELECT COUNT(*) FROM club_members WHERE student_id = ? AND club_id = ?");
                $check->execute([$data['user_id'], $data['club_id']]);

                if ($check->fetchColumn() == 0) {
                    $insert = $pdo->prepare("INSERT INTO club_members (student_id, club_id) VALUES (?, ?)");
                    $insert->execute([$data['user_id'], $data['club_id']]);
                }
            }
        } elseif ($status === "rejected" || $status === "pending") {
            // Remove from members if status is changed back from approved
            $get = $pdo->prepare("SELECT user_id, club_id FROM club_applications WHERE id = ?");
            $get->execute([$id]);
            $data = $get->fetch(PDO::FETCH_ASSOC);
            
            if($data) {
                $delete = $pdo->prepare("DELETE FROM club_members WHERE student_id = ? AND club_id = ?");
                $delete->execute([$data['user_id'], $data['club_id']]);
            }
        }
    }

    header("Location: Admin_ApplicationManagement.php");
    exit();
}
?>