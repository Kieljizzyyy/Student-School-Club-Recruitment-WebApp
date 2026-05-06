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
    <title>User Management | ClubRecruit</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "primary": "#002045", // Deep Midnight Blue[cite: 1]
                        "secondary": "#1960a3",
                        "accent": "#ECC94B",   // Golden Yellow for consistent Actions[cite: 3]
                        "background": "#f8f9ff",
                        "on-background": "#0d1c2e",
                        "surface": "#ffffff",
                        "error": "#ba1a1a",
                        "primary-fixed": "#d6e3ff"
                    }
                },
            },
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        
        .campus-bg {
            background-image: linear-gradient(rgba(248, 249, 255, 0.95), rgba(248, 249, 255, 0.95)), 
                              url('https://images.unsplash.com/photo-1541339907198-e08756ebafe3?auto=format&fit=crop&q=80&w=2000');
            background-attachment: fixed;
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="text-on-background campus-bg">

<!-- SideNavBar: Updated to Deep Blue Theme -->
<aside class="flex flex-col h-full w-64 fixed left-0 top-0 z-40 bg-primary text-white shadow-2xl transition-transform">
    <div class="px-6 py-8 flex flex-col gap-1 border-b border-white/10">
        <h1 class="text-2xl font-black text-white leading-tight tracking-tight">ClubRecruit</h1>
        <p class="text-xs font-bold text-blue-300 tracking-widest uppercase">Admin Portal</p>
    </div>
    
    <nav class="flex-1 px-4 space-y-2 mt-6">
        <a class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all text-blue-100/70 hover:bg-white/5 hover:text-white group" href="Admin_Dashboard.php">
            <span class="material-symbols-outlined">dashboard</span>
            <span class="text-sm">Dashboard</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all bg-white/10 text-white font-semibold shadow-inner" href="Admin_UserManagement.php">
            <span class="material-symbols-outlined">person</span>
            <span class="text-sm">Users</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all text-blue-100/70 hover:bg-white/5 hover:text-white group" href="Admin_ClubManagement.php">
            <span class="material-symbols-outlined">groups</span>
            <span class="text-sm">Clubs</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all text-blue-100/70 hover:bg-white/5 hover:text-white group" href="Admin_ApplicationManagement.php">
            <span class="material-symbols-outlined">description</span>
            <span class="text-sm">Applications</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all text-blue-100/70 hover:bg-white/5 hover:text-white group" href="Admin_Analytics.php">
            <span class="material-symbols-outlined">analytics</span>
            <span class="text-sm">Analytics</span>
        </a>
    </nav>

    <div class="p-4 mt-auto border-t border-white/10">
        <a href="../logOut.php" class="flex items-center gap-3 px-4 py-3 w-full rounded-lg text-red-300 hover:bg-red-500/10 hover:text-red-200 transition-all">
            <span class="material-symbols-outlined">logout</span>
            <span class="text-sm font-bold">Logout</span>
        </a>
    </div>
</aside>

<main class="ml-64 min-h-screen flex flex-col">
    <!-- Top Bar: Glassmorphism[cite: 1] -->
    <header class="flex justify-between items-center w-full px-8 py-4 bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-30 shadow-sm">
        <div class="flex items-center gap-4 flex-1">
            <div class="relative w-full max-w-md">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                <input class="w-full bg-slate-100 border-none rounded-full py-2 pl-10 pr-4 text-sm focus:ring-2 focus:ring-primary/20" placeholder="Search system assets..." type="text"/>
            </div>
        </div>
        <div class="flex items-center gap-6">
            <div class="text-right">
                <p class="text-sm font-bold text-primary"><?= htmlspecialchars($fullName) ?></p>
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-tighter">System Admin</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center font-black text-lg border-2 border-white shadow-md">
                <?= strtoupper(substr($firstName, 0, 1)) ?>
            </div>
        </div>
    </header>

    <div class="p-8 max-w-7xl mx-auto w-full space-y-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h2 class="text-3xl font-black text-primary tracking-tight">User Management</h2>
                <p class="text-slate-500 font-medium mt-1">Configure access levels and monitor recruitment participation.</p>
            </div>
            <!-- UNIFORM ACTION BUTTON: Matches Club Management Gold[cite: 3] -->
            <button class="flex items-center gap-2 bg-accent text-primary font-black px-6 py-3 rounded-xl shadow-lg hover:brightness-110 transition-all hover:scale-105 active:scale-95">
                <span class="material-symbols-outlined font-black">person_add</span>
                Add New User
            </button>
        </div>

        <!-- Bento Grid Stats[cite: 4] -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 border-l-4 border-primary">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Users</p>
                <p class="text-3xl font-black text-primary">2,842</p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 border-l-4 border-emerald-500">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Active Students</p>
                <p class="text-3xl font-black text-primary">2,105</p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 border-l-4 border-secondary">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">New Registrations</p>
                <p class="text-3xl font-black text-primary">48</p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 border-l-4 border-accent">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Staff Access</p>
                <p class="text-3xl font-black text-primary">12</p>
            </div>
        </div>

        <!-- Data Table Container[cite: 4] -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-4 bg-slate-50/50 border-b border-slate-100 flex justify-between items-center">
                <div class="relative w-64">
                    <span class="material-symbols-outlined absolute left-2 top-1/2 -translate-y-1/2 text-slate-400 text-sm">filter_list</span>
                    <input class="w-full pl-8 pr-4 py-1.5 bg-white border border-slate-200 rounded-lg text-xs" placeholder="Quick filter..." type="text"/>
                </div>
                <button class="flex items-center gap-2 px-4 py-1.5 border border-slate-200 rounded-lg text-xs font-bold text-slate-600 hover:bg-white transition-all">
                    <span class="material-symbols-outlined text-sm">download</span> Export
                </button>
            </div>
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest">User Details</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest">Role</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr class="hover:bg-blue-50/30 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-primary-fixed flex items-center justify-center text-primary font-black text-xs">SM</div>
                                <div>
                                    <p class="text-sm font-bold text-primary">Sarah Miller</p>
                                    <p class="text-[10px] text-slate-400 font-medium">sarah.m@nbsc.edu.ph</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase bg-blue-100 text-primary border border-blue-200">Student</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="flex items-center gap-1.5 text-emerald-600 text-[11px] font-bold">
                                <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div> Active
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-1 opacity-40 group-hover:opacity-100 transition-opacity">
                                <button class="p-2 text-slate-500 hover:text-primary hover:bg-blue-50 rounded-lg transition-all"><span class="material-symbols-outlined text-lg">edit</span></button>
                                <button class="p-2 text-slate-500 hover:text-error hover:bg-red-50 rounded-lg transition-all"><span class="material-symbols-outlined text-lg">block</span></button>
                            </div>  
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <footer class="mt-auto py-6 text-center">
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">© 2026 ClubRecruit Institutional Management System</p>
    </footer>
</main>
</body>
</html>