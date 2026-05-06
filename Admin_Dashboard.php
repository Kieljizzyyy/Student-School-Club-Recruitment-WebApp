<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../logIn.php");
    exit();
}

// Fetch the user's name from the session (fallback to 'Admin' if not found)
$fullName = isset($_SESSION['name']) ? $_SESSION['name'] : 'Admin';

// Extract just the first name for the initial
$nameParts = explode(' ', trim($fullName));
$firstName = $nameParts[0];
?>

<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Admin Dashboard | ClubRecruit</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&amp;display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            "colors": {
                    "primary": "#002045",
                    "on-primary": "#ffffff",
                    "secondary": "#1960a3",
                    "on-secondary": "#ffffff",
                    "background": "#f8f9ff",
                    "on-background": "#0d1c2e",
                    "surface": "#ffffff",
                    "on-surface": "#0d1c2e",
                    "on-surface-variant": "#43474e",
                    "outline": "#74777f",
                    "primary-fixed": "#d6e3ff",
                    "secondary-fixed": "#d3e4ff"
            },
            "fontFamily": {
                    "sans": ["Inter", "sans-serif"]
            }
          },
        },
      }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        /* Campus Background Overlay */
        .campus-bg {
            background-image: linear-gradient(rgba(248, 249, 255, 0.92), rgba(248, 249, 255, 0.92)), 
                              url('https://images.unsplash.com/photo-1541339907198-e08756ebafe3?auto=format&fit=crop&q=80&w=2000');
            background-attachment: fixed;
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="bg-background text-on-background campus-bg">

<!-- SideNavBar: Updated to Deep Blue Theme -->
<aside class="flex flex-col h-full w-64 fixed left-0 top-0 z-40 bg-primary text-white shadow-2xl transition-transform">
    <div class="px-6 py-8 flex flex-col gap-1 border-b border-white/10">
        <h1 class="text-2xl font-black text-white leading-tight tracking-tight">ClubRecruit</h1>
        <p class="text-xs font-bold text-blue-300 tracking-widest uppercase">Admin Portal</p>
    </div>
    
    <nav class="flex-1 px-4 space-y-2 mt-6">
     <a class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all bg-white/10 text-white font-semibold shadow-inner" href="Admin_Dashboard.php">
    <span class="material-symbols-outlined">dashboard</span>
    <span class="text-sm">Dashboard</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all text-blue-100/70 hover:bg-white/5 hover:text-white" href="Admin_UserManagement.php">
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

<!-- Main Canvas -->
<main class="ml-64 min-h-screen">
    <!-- TopAppBar -->
    <header class="flex justify-between items-center w-full px-8 py-4 bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-30 shadow-sm">
        <div class="flex items-center gap-4 flex-1">
            <div class="relative w-full max-w-md">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                <input class="w-full bg-slate-100 border-none rounded-full py-2 pl-10 pr-4 text-sm focus:ring-2 focus:ring-primary/20" placeholder="Search system logs, clubs, or users..." type="text"/>
            </div>
        </div>
        <div class="flex items-center gap-6">
            <div class="flex items-center gap-2">
                <button class="p-2 rounded-full hover:bg-slate-100 text-primary">
                    <span class="material-symbols-outlined">notifications</span>
                </button>
            </div>

            <!-- Profile Section -->
            <div class="flex items-center gap-3 border-l border-slate-200 pl-6 cursor-pointer hover:opacity-80 transition-opacity">
                <div class="text-right">
                    <p class="text-sm font-bold text-primary"><?= htmlspecialchars($fullName) ?></p>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-tighter">Administrator</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-primary-fixed border-2 border-white shadow-md flex items-center justify-center text-primary font-black text-lg">
                    <?= strtoupper(substr($firstName, 0, 1)) ?>
                </div>
            </div>
        </div>
    </header>

    <!-- Content Area -->
    <div class="p-8 max-w-7xl mx-auto space-y-8">
        <!-- Breadcrumbs / Header -->
        <div class="flex justify-between items-end">
            <div>
                <h2 class="text-3xl font-extrabold text-primary tracking-tight">Admin Dashboard</h2>
                <p class="text-on-surface-variant font-medium mt-1">Real-time recruitment pipeline and system health.</p>
            </div>
            <button class="flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-xl font-bold shadow-lg hover:bg-blue-900 transition-all hover:scale-105 active:scale-95">
                <span class="material-symbols-outlined text-xl">download</span>
                Export Data
            </button>
        </div>

        <!-- Summary Cards Bento Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Students -->
            <div class="bg-primary-fixed p-6 rounded-2xl shadow-sm border border-slate-100">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-primary/5 rounded-xl text-primary">
                        <span class="material-symbols-outlined">school</span>
                    </div>
                    <span class="text-xs font-black text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">+12%</span>
                </div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Students</p>
                <h3 class="text-3xl font-black text-primary mt-1">2,105</h3>
            </div>

            <!-- Total Clubs -->
            <div class="bg-primary-fixed p-6 rounded-2xl shadow-sm border border-slate-100">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-primary/5 rounded-xl text-primary">
                        <span class="material-symbols-outlined">hub</span>
                    </div>
                    <span class="text-xs font-black text-slate-400 bg-slate-50 px-2 py-1 rounded-full">Stable</span>
                </div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Clubs</p>
                <h3 class="text-3xl font-black text-primary mt-1">184</h3>
            </div>

            <!-- Total Applications -->
           <div class="bg-primary-fixed p-6 rounded-2xl shadow-sm border border-slate-100">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-primary/5 rounded-xl text-primary">
                        <span class="material-symbols-outlined">assignment</span>
                    </div>
                    <span class="text-xs font-black text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">+8.4%</span>
                </div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Applications</p>
                <h3 class="text-3xl font-black text-primary mt-1">3,482</h3>
            </div>

            <!-- Pending -->
            <div class="bg-primary-fixed p-6 rounded-2xl shadow-sm border border-slate-100">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-amber-50 rounded-xl text-amber-600">
                        <span class="material-symbols-outlined">pending_actions</span>
                    </div>
                    <span class="text-xs font-black text-amber-600 bg-amber-50 px-2 py-1 rounded-full">Action Required</span>
                </div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Pending Review</p>
                <h3 class="text-3xl font-black text-primary mt-1">512</h3>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Performance Section -->
            <section class="lg:col-span-2 space-y-6">
                <div class="bg-primary-fixed p-6 rounded-2xl shadow-sm border border-slate-100">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-xl font-black text-primary">Recruitment Performance</h3>
                        <div class="flex gap-4">
                            <span class="flex items-center gap-2 text-xs font-bold text-primary">
                                <span class="w-3 h-3 bg-primary rounded-full"></span> 2026
                            </span>
                            <span class="flex items-center gap-2 text-xs font-bold text-slate-300">
                                <span class="w-3 h-3 bg-slate-200 rounded-full"></span> 2025
                            </span>
                        </div>
                    </div>
                    <!-- Simplified Visual Graph -->
                    <div class="relative h-64 w-full flex items-end justify-between gap-2 px-2">
                        <?php for($i=0; $i<6; $i++): 
                            $h = rand(40, 100); ?>
                            <div class="flex-1 flex flex-col items-center group">
                                <div class="w-full bg-primary/10 rounded-t-lg h-[<?= $h-10 ?>%] group-hover:bg-primary/20 transition-all"></div>
                                <div class="w-full bg-primary rounded-t-lg h-[<?= $h ?>%] -mt-6 relative z-10 shadow-lg"></div>
                                <span class="text-[10px] font-black text-slate-400 mt-3"><?= ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN'][$i] ?></span>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
            </section>

            <!-- Activity Section -->
            <section class="lg:col-span-1">
                <div class="bg-primary-fixed p-6 rounded-2xl shadow-sm border border-slate-100">
                    <div class="p-6 border-b border-slate-50">
                        <h3 class="text-xl font-black text-primary">System Activity</h3>
                    </div>
                    <div class="flex-1 p-4 space-y-4">
                        <div class="p-3 rounded-xl hover:bg-slate-50 transition-all flex gap-4 border-l-4 border-emerald-500">
                            <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined">add_circle</span>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-primary">New Club: Robotics Society</p>
                                <p class="text-xs text-slate-500">Submitted by Faculty Dept.</p>
                                <p class="text-[10px] font-black text-slate-400 mt-1 uppercase">Just Now</p>
                            </div>
                        </div>
                        <!-- ... More items ... -->
                    </div>
                    <div class="p-6">
                        <button class="w-full py-3 rounded-xl border-2 border-primary/10 text-sm font-bold text-primary hover:bg-primary hover:text-white transition-all">
                            View Full Audit Log
                        </button>
                    </div>
                </div>
            </section>
        </div>
    </div>
</main>
</body>
</html>