<?php
session_start();

// 1. SECURITY: Ensure only Admins access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../logIn.php");
    exit();
}

// 2. DATABASE CONNECTION
$host = 'localhost';
$dbname = 'it26b_StudentClubRecruitment'; 
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // --- FETCH BENTO STATS ---
    $totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $activeStudents = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'student' AND status = 'active'")->fetchColumn();
    $newReg = $pdo->query("SELECT COUNT(*) FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)")->fetchColumn();
    $staffAccess = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'admin'")->fetchColumn();

    // --- FETCH ADMINS ---
    $adminStmt = $pdo->query("
        SELECT id, name, email, role, status, created_at 
        FROM users 
        WHERE role = 'admin'
        ORDER BY name ASC
    ");
    $admins = $adminStmt->fetchAll(PDO::FETCH_ASSOC);

    // --- FETCH STUDENTS ---
    $studentStmt = $pdo->query("
        SELECT id, name, email, role, status, created_at 
        FROM users 
        WHERE role = 'student'
        ORDER BY name ASC
    ");
    $students = $studentStmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}

$fullName = $_SESSION['name'] ?? 'Admin';
$firstName = explode(' ', trim($fullName))[0];
?>

<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>User Management | ClubRecruit</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#002045",
                        secondary: "#1960a3",
                        accent: "#ECC94B",
                        background: "#f8f9ff",
                        "on-background": "#0d1c2e",
                        surface: "#ffffff",
                        error: "#ba1a1a",
                        "primary-fixed": "#d6e3ff"
                    }
                },
            },
        }
    </script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .material-symbols-outlined {
            font-variation-settings:
                'FILL' 0,
                'wght' 400,
                'GRAD' 0,
                'opsz' 24;
        }

        .campus-bg {
            background-image:
                linear-gradient(rgba(0, 0, 0, 0.45), rgba(0, 0, 0, 0.45)),
                url('https://images.unsplash.com/photo-1541339907198-e08756ebafe3?auto=format&fit=crop&q=80&w=2000');

            background-attachment: fixed;
            background-size: cover;
            background-position: center;
        }
    </style>
</head>

<body class="text-on-background campus-bg">

<!-- SIDEBAR -->
<aside class="flex flex-col h-full w-64 fixed left-0 top-0 z-40 bg-primary text-white shadow-2xl">

    <div class="px-6 py-8 flex flex-col gap-1 border-b border-white/10">
        <h1 class="text-2xl font-black text-white leading-tight tracking-tight">
            ClubRecruit
        </h1>

        <p class="text-xs font-bold text-blue-300 tracking-widest uppercase">
            Admin Portal
        </p>
    </div>

    <nav class="flex-1 px-4 space-y-2 mt-6">

        <a class="flex items-center gap-3 px-4 py-3 rounded-lg text-blue-100/70 hover:bg-white/5 hover:text-white"
           href="Admin_Dashboard.php">

            <span class="material-symbols-outlined">dashboard</span>
            <span class="text-sm">Dashboard</span>
        </a>

        <a class="flex items-center gap-3 px-4 py-3 rounded-lg bg-white/10 text-white font-semibold shadow-inner"
           href="Admin_UserManagement.php">

            <span class="material-symbols-outlined">person</span>
            <span class="text-sm">Users</span>
        </a>

        <a class="flex items-center gap-3 px-4 py-3 rounded-lg text-blue-100/70 hover:bg-white/5 hover:text-white"
           href="Admin_ClubManagement.php">

            <span class="material-symbols-outlined">groups</span>
            <span class="text-sm">Clubs</span>
        </a>

        <a class="flex items-center gap-3 px-4 py-3 rounded-lg text-blue-100/70 hover:bg-white/5 hover:text-white"
           href="Admin_ApplicationManagement.php">

            <span class="material-symbols-outlined">description</span>
            <span class="text-sm">Applications</span>
        </a>
        <a href="Admin_Analytics.php"
           class="flex items-center gap-3 px-4 py-3 rounded-lg text-blue-100/70 hover:bg-white/5 hover:text-white">

            <span class="material-symbols-outlined">analytics</span>
            <span class="text-sm">Analytics</span>
        </a>
    </nav>

    <div class="p-4 mt-auto border-t border-white/10">
        <a href="../logOut.php"
           class="flex items-center gap-3 px-4 py-3 w-full rounded-lg text-red-300 hover:bg-red-500/10 hover:text-red-200 transition-all">

            <span class="material-symbols-outlined">logout</span>
            <span class="text-sm font-bold">Logout</span>
        </a>
    </div>
</aside>

<!-- MAIN -->
<main class="ml-64 min-h-screen flex flex-col">

    <!-- TOP BAR -->
    <header class="flex justify-between items-center w-full px-8 py-4 bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-30 shadow-sm">

        <div class="flex items-center gap-4 flex-1">
            <div class="relative w-full max-w-md">

                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                    search
                </span>

                <input
                    class="w-full bg-slate-100 border-none rounded-full py-2 pl-10 pr-4 text-sm focus:ring-2 focus:ring-primary/20"
                    placeholder="Search system assets..."
                    type="text"/>
            </div>
        </div>

        <div class="flex items-center gap-6">

            <div class="text-right">
                <p class="text-sm font-bold text-primary">
                    <?= htmlspecialchars($fullName) ?>
                </p>

                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-tighter">
                    System Admin
                </p>
            </div>

            <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center font-black text-lg border-2 border-white shadow-md">
                <?= strtoupper(substr($firstName, 0, 1)) ?>
            </div>
        </div>
    </header>

    <!-- CONTENT -->
    <div class="p-8 max-w-7xl mx-auto w-full space-y-8">

        <!-- HEADER -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">

            <div>
                <h2 class="text-3xl font-black text-white tracking-tight">
                    User Management
                </h2>

                <p class="text-slate-200 font-medium mt-1">
                    Manage system hierarchy and verify campus accounts.
                </p>
            </div>

            <button class="flex items-center gap-2 bg-accent text-primary font-black px-6 py-3 rounded-xl shadow-lg hover:brightness-110 transition-all">

                <span class="material-symbols-outlined">
                    person_add
                </span>

                Add New User
            </button>
        </div>

        <!-- STATS -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

            <div class="bg-primary-fixed p-6 rounded-2xl shadow-sm border border-primary/10 border-l-4 border-primary">
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">
                    Total Users
                </p>

                <p class="text-3xl font-black text-primary">
                    <?= number_format($totalUsers) ?>
                </p>
            </div>

            <div class="bg-primary-fixed p-6 rounded-2xl shadow-sm border border-primary/10 border-l-4 border-emerald-500">
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">
                    Active Students
                </p>

                <p class="text-3xl font-black text-primary">
                    <?= number_format($activeStudents) ?>
                </p>
            </div>

            <div class="bg-primary-fixed p-6 rounded-2xl shadow-sm border border-primary/10 border-l-4 border-secondary">
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">
                    New Registrations
                </p>

                <p class="text-3xl font-black text-primary">
                    <?= number_format($newReg) ?>
                </p>
            </div>

            <div class="bg-primary-fixed p-6 rounded-2xl shadow-sm border border-primary/10 border-l-4 border-accent">
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">
                    Staff Access
                </p>

                <p class="text-3xl font-black text-primary">
                    <?= number_format($staffAccess) ?>
                </p>
            </div>
        </div>

        <!-- ADMINS -->
        <div class="bg-primary-fixed rounded-2xl shadow-sm border border-primary/10 overflow-hidden">

            <div class="px-6 py-4 bg-primary text-white">
                <h3 class="text-lg font-black">
                    Administrators
                </h3>
            </div>

            <table class="w-full text-left border-collapse">

                <thead>
                    <tr class="bg-blue-50/70 border-b border-primary/10">

                        <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase">
                            User Details
                        </th>

                        <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase">
                            Role
                        </th>

                        <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase">
                            Status
                        </th>

                        <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase text-right">
                            Actions
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-primary/10">

                    <?php foreach ($admins as $user): ?>

                    <tr class="hover:bg-blue-50/40 transition-colors">

                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">

                                <div class="w-9 h-9 rounded-full bg-primary flex items-center justify-center text-white font-black text-xs">
                                    <?= strtoupper(substr($user['name'], 0, 2)) ?>
                                </div>

                                <div>
                                    <p class="text-sm font-bold text-primary">
                                        <?= htmlspecialchars($user['name']) ?>
                                    </p>

                                    <p class="text-[10px] text-slate-500">
                                        <?= htmlspecialchars($user['email']) ?>
                                    </p>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <span class="px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase border bg-primary text-accent border-accent/30">
                                ADMIN
                            </span>
                        </td>

                        <td class="px-6 py-4">
                            <span class="text-sm font-semibold text-emerald-600">
                                <?= ucfirst($user['status']) ?>
                            </span>
                        </td>

                        <td class="px-6 py-4 text-right">
                            <button class="p-2 text-slate-500 hover:text-primary hover:bg-blue-50 rounded-lg">
                                <span class="material-symbols-outlined text-lg">edit</span>
                            </button>
                        </td>

                    </tr>

                    <?php endforeach; ?>

                </tbody>
            </table>
        </div>

        <!-- STUDENTS -->
        <div class="bg-primary-fixed rounded-2xl shadow-sm border border-primary/10 overflow-hidden">

            <div class="px-6 py-4 bg-secondary text-white">
                <h3 class="text-lg font-black">
                    Students
                </h3>
            </div>

            <table class="w-full text-left border-collapse">

                <thead>
                    <tr class="bg-blue-50/70 border-b border-primary/10">

                        <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase">
                            User Details
                        </th>

                        <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase">
                            Role
                        </th>

                        <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase">
                            Status
                        </th>

                        <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase text-right">
                            Actions
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-primary/10">

                    <?php foreach ($students as $user): ?>

                    <tr class="hover:bg-blue-50/40 transition-colors">

                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">

                                <div class="w-9 h-9 rounded-full bg-primary-fixed flex items-center justify-center text-primary font-black text-xs">
                                    <?= strtoupper(substr($user['name'], 0, 2)) ?>
                                </div>

                                <div>
                                    <p class="text-sm font-bold text-primary">
                                        <?= htmlspecialchars($user['name']) ?>
                                    </p>

                                    <p class="text-[10px] text-slate-500">
                                        <?= htmlspecialchars($user['email']) ?>
                                    </p>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <span class="px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase border bg-blue-50 text-primary border-blue-100">
                                STUDENT
                            </span>
                        </td>

                        <td class="px-6 py-4">
                            <span class="text-sm font-semibold text-emerald-600">
                                <?= ucfirst($user['status']) ?>
                            </span>
                        </td>

                        <td class="px-6 py-4 text-right">
                            <button class="p-2 text-slate-500 hover:text-primary hover:bg-blue-50 rounded-lg">
                                <span class="material-symbols-outlined text-lg">edit</span>
                            </button>
                        </td>

                    </tr>

                    <?php endforeach; ?>

                </tbody>
            </table>
        </div>

    </div>

    <!-- FOOTER -->
    <footer class="mt-auto py-6 text-center">
        <p class="text-[10px] font-bold text-slate-300 uppercase tracking-widest">
            © 2026 ClubRecruit Institutional Management System
        </p>
    </footer>

</main>
</body>
</html>