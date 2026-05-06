<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../logIn.php");
    exit();
}

$fullName = isset($_SESSION['name']) ? $_SESSION['name'] : 'Admin';
$nameParts = explode(' ', trim($fullName));
$firstName = $nameParts[0];
?>

<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Club Management | ClubRecruit</title>

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
                        error: "#ba1a1a",
                        "primary-fixed": "#d6e3ff"
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Inter', sans-serif; }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .campus-bg {
            background-image: linear-gradient(rgba(248, 249, 255, 0.95), rgba(248, 249, 255, 0.95)),
            url('https://images.unsplash.com/photo-1541339907198-e08756ebafe3?auto=format&fit=crop&q=80&w=2000');
            background-attachment: fixed;
            background-size: cover;
            background-position: center;
        }
    </style>
</head>

<body class="campus-bg">

<!-- SIDEBAR -->
<aside class="flex flex-col h-full w-64 fixed left-0 top-0 z-40 bg-primary text-white shadow-2xl">

    <div class="px-6 py-8 border-b border-white/10">
        <h1 class="text-2xl font-black">ClubRecruit</h1>
        <p class="text-xs text-blue-300 font-bold uppercase tracking-widest">Admin Portal</p>
    </div>

    <nav class="flex-1 px-4 space-y-2 mt-6">

        <!-- Dashboard -->
        <a href="Admin_Dashboard.php"
           class="flex items-center gap-3 px-4 py-3 rounded-lg text-blue-100/70 hover:bg-white/5 hover:text-white">
            <span class="material-symbols-outlined">dashboard</span>
            <span class="text-sm">Dashboard</span>
        </a>

        <!-- Users -->
        <a href="Admin_UserManagement.php"
           class="flex items-center gap-3 px-4 py-3 rounded-lg text-blue-100/70 hover:bg-white/5 hover:text-white">
            <span class="material-symbols-outlined">person</span>
            <span class="text-sm">Users</span>
        </a>

        <!-- CLUBS (ACTIVE) -->
        <a href="Admin_ClubManagement.php"
           class="flex items-center gap-3 px-4 py-3 rounded-lg bg-white/10 text-white font-semibold shadow-inner">
            <span class="material-symbols-outlined">groups</span>
            <span class="text-sm">Clubs</span>
        </a>

        <!-- Applications -->
        <a href="Admin_ApplicationManagement.php"
           class="flex items-center gap-3 px-4 py-3 rounded-lg text-blue-100/70 hover:bg-white/5 hover:text-white">
            <span class="material-symbols-outlined">description</span>
            <span class="text-sm">Applications</span>
        </a>

        <!-- Analytics -->
        <a href="Admin_Analytics.php"
           class="flex items-center gap-3 px-4 py-3 rounded-lg text-blue-100/70 hover:bg-white/5 hover:text-white">
            <span class="material-symbols-outlined">analytics</span>
            <span class="text-sm">Analytics</span>
        </a>

    </nav>

    <div class="p-4 mt-auto border-t border-white/10">
        <a href="../logOut.php"
           class="flex items-center gap-3 px-4 py-3 text-red-300 hover:bg-red-500/10 hover:text-red-200 rounded-lg">
            <span class="material-symbols-outlined">logout</span>
            <span class="text-sm font-bold">Logout</span>
        </a>
    </div>
</aside>

<!-- MAIN -->
<main class="ml-64 min-h-screen flex flex-col">

    <!-- TOP BAR -->
    <header class="flex justify-between items-center px-8 py-4 bg-white/80 backdrop-blur-md border-b">

        <input class="w-96 bg-slate-100 rounded-full px-4 py-2 text-sm"
               placeholder="Search clubs..." />

        <div class="flex items-center gap-4">
            <div class="text-right">
                <p class="font-bold text-primary"><?= htmlspecialchars($fullName) ?></p>
                <p class="text-xs text-slate-500">Admin</p>
            </div>

            <div class="w-10 h-10 bg-primary text-white rounded-full flex items-center justify-center font-bold">
                <?= strtoupper(substr($firstName, 0, 1)) ?>
            </div>
        </div>

    </header>

    <!-- CONTENT -->
    <div class="p-8">

        <h2 class="text-3xl font-black text-primary">Club Management</h2>
        <p class="text-slate-500 mt-1">Manage organizations and approvals</p>

        <button class="mt-6 bg-accent text-primary font-bold px-6 py-3 rounded-xl shadow">
            <span class="material-symbols-outlined">add</span>
            Create New Club
        </button>

        <!-- SAMPLE CARD -->
        <div class="mt-10 bg-white p-6 rounded-xl shadow">
            <h3 class="font-bold text-primary">Algorithm Knights</h3>
            <p class="text-sm text-slate-500">Programming club</p>
        </div>

    </div>

</main>

</body>
</html>