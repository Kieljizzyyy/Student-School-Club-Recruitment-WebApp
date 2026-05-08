<?php
session_start();

// 1. SECURITY CHECK
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../logIn.php");
    exit();
}

// 2. DATABASE CONFIGURATION
$host = 'localhost';
$dbname = 'it26b_StudentClubRecruitment';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // --- FETCH SUMMARY STATS ---
    $totalStudents = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'student'")->fetchColumn();
    $totalClubs    = $pdo->query("SELECT COUNT(*) FROM clubs")->fetchColumn();
    $totalApps     = $pdo->query("SELECT COUNT(*) FROM club_applications")->fetchColumn();
    $pendingApps   = $pdo->query("SELECT COUNT(*) FROM club_applications WHERE status = 'Pending'")->fetchColumn();

    // --- FETCH GRAPH DATA ---
    $graphQuery = "SELECT MONTH(applied_at) as month, COUNT(*) as count 
                   FROM club_applications 
                   WHERE YEAR(applied_at) = YEAR(CURDATE()) 
                   GROUP BY MONTH(applied_at)";
    $graphResults = $pdo->query($graphQuery)->fetchAll(PDO::FETCH_KEY_PAIR);

    // --- FETCH RECENT ACTIVITY ---
    $activityQuery = "SELECT u.name as student_name, c.club_name, a.status, a.applied_at 
                      FROM club_applications a
                      JOIN users u ON a.user_id = u.id
                      JOIN clubs c ON a.club_id = c.id
                      ORDER BY a.applied_at DESC LIMIT 5";

    $recentActivities = $pdo->query($activityQuery)->fetchAll();

} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}

// USER INFO
$fullName = $_SESSION['name'] ?? 'Admin';
$firstName = explode(' ', trim($fullName))[0];
?>

<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>

    <title>Admin Dashboard | ClubRecruit</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>

    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#002045",
                        secondary: "#1960a3",
                        background: "#f8f9ff",
                        "on-background": "#0d1c2e",
                        "primary-fixed": "#d6e3ff",
                        "secondary-fixed": "#d3e4ff"
                    },

                    fontFamily: {
                        sans: ["Inter", "sans-serif"]
                    }
                }
            }
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
                linear-gradient(rgba(0, 18, 45, 0.72), rgba(0, 18, 45, 0.72)),
                url('https://images.unsplash.com/photo-1541339907198-e08756ebafe3?auto=format&fit=crop&q=80&w=2000');

            background-attachment: fixed;
            background-size: cover;
            background-position: center;
        }
    </style>
</head>

<body class="bg-background text-on-background campus-bg">

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

        <a class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all bg-white/10 text-white font-semibold shadow-inner"
           href="Admin_Dashboard.php">

            <span class="material-symbols-outlined">dashboard</span>
            <span class="text-sm">Dashboard</span>
        </a>

        <a class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all text-blue-100/70 hover:bg-white/5 hover:text-white"
           href="Admin_UserManagement.php">

            <span class="material-symbols-outlined">person</span>
            <span class="text-sm">Users</span>
        </a>

        <a class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all text-blue-100/70 hover:bg-white/5 hover:text-white"
           href="Admin_ClubManagement.php">

            <span class="material-symbols-outlined">groups</span>
            <span class="text-sm">Clubs</span>
        </a>

        <a class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all text-blue-100/70 hover:bg-white/5 hover:text-white"
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
<main class="ml-64 min-h-screen">

    <!-- TOP BAR -->
    <header class="flex justify-between items-center w-full px-8 py-4 bg-white/80 backdrop-blur-md border-b border-primary/10 sticky top-0 z-30 shadow-sm">

        <div class="flex items-center gap-4 flex-1">

            <div class="relative w-full max-w-md">

                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                    search
                </span>

                <input
                    class="w-full bg-slate-100 border-none rounded-full py-2 pl-10 pr-4 text-sm focus:ring-2 focus:ring-primary/20"
                    placeholder="Search system..."
                    type="text"/>
            </div>
        </div>

        <div class="flex items-center gap-6">

            <div class="flex items-center gap-3 border-l border-primary/10 pl-6">

                <div class="text-right">

                    <p class="text-sm font-bold text-primary">
                        <?= htmlspecialchars($fullName) ?>
                    </p>

                    <p class="text-[10px] font-bold text-slate-500 uppercase">
                        Administrator
                    </p>
                </div>

                <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center font-black">
                    <?= strtoupper(substr($firstName, 0, 1)) ?>
                </div>
            </div>
        </div>
    </header>

    <!-- CONTENT -->
    <div class="p-8 max-w-7xl mx-auto space-y-8">

        <!-- HEADER -->
        <div class="flex justify-between items-end">

            <div>
                <h2 class="text-3xl font-extrabold text-white tracking-tight">
                    Admin Dashboard
                </h2>

                <p class="text-slate-200 font-medium mt-1">
                    Real-time recruitment pipeline overview.
                </p>
            </div>

            <button class="flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-xl font-bold shadow-lg hover:bg-blue-900 transition-all">

                <span class="material-symbols-outlined text-xl">
                    download
                </span>

                Export Data
            </button>
        </div>

        <!-- SUMMARY CARDS -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            <div class="bg-primary-fixed/95 backdrop-blur-sm p-6 rounded-2xl shadow-sm border border-primary/10">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">
                    Total Students
                </p>

                <h3 class="text-3xl font-black text-primary mt-1">
                    <?= number_format($totalStudents) ?>
                </h3>
            </div>

            <div class="bg-primary-fixed/95 backdrop-blur-sm p-6 rounded-2xl shadow-sm border border-primary/10">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">
                    Total Clubs
                </p>

                <h3 class="text-3xl font-black text-primary mt-1">
                    <?= number_format($totalClubs) ?>
                </h3>
            </div>

            <div class="bg-primary-fixed/95 backdrop-blur-sm p-6 rounded-2xl shadow-sm border border-primary/10">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">
                    Applications
                </p>

                <h3 class="text-3xl font-black text-primary mt-1">
                    <?= number_format($totalApps) ?>
                </h3>
            </div>

            <div class="bg-amber-50/95 backdrop-blur-sm p-6 rounded-2xl shadow-sm border border-amber-200">
                <p class="text-xs font-bold text-amber-700 uppercase tracking-widest">
                    Pending Review
                </p>

                <h3 class="text-3xl font-black text-amber-800 mt-1">
                    <?= number_format($pendingApps) ?>
                </h3>
            </div>
        </div>

        <!-- MAIN GRID -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- GRAPH -->
            <section class="lg:col-span-2">

                <div class="bg-primary-fixed/95 backdrop-blur-sm p-6 rounded-2xl shadow-sm border border-primary/10 h-full">

                    <h3 class="text-xl font-black text-primary mb-8">
                        Recruitment Performance (<?= date('Y') ?>)
                    </h3>

                    <div class="relative h-64 w-full flex items-end justify-between gap-2 px-2">

                        <?php
                        $months = ['JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC'];

                        $maxVal = (count($graphResults) > 0)
                            ? max($graphResults)
                            : 1;

                        foreach($months as $index => $monthName):

                            $monthNum = $index + 1;
                            $count = $graphResults[$monthNum] ?? 0;
                            $height = ($count / $maxVal) * 100;
                        ?>

                        <div class="flex-1 flex flex-col items-center group">

                            <div class="w-full bg-gradient-to-t from-primary to-secondary rounded-t-lg shadow-lg transition-all hover:opacity-90"
                                 style="height: <?= max($height, 5) ?>%"
                                 title="<?= $count ?> Apps">
                            </div>

                            <span class="text-[10px] font-black text-slate-500 mt-3">
                                <?= $monthName ?>
                            </span>
                        </div>

                        <?php endforeach; ?>

                    </div>
                </div>
            </section>

            <!-- RECENT ACTIVITY -->
            <section class="lg:col-span-1">

                <div class="bg-primary-fixed/95 backdrop-blur-sm rounded-2xl shadow-sm border border-primary/10 h-full flex flex-col">

                    <div class="p-6 border-b border-primary/10">

                        <h3 class="text-xl font-black text-primary">
                            Recent Applications
                        </h3>
                    </div>

                    <div class="flex-1 p-4 space-y-4">

                        <?php if(empty($recentActivities)): ?>

                            <p class="text-center text-slate-400 py-10">
                                No recent activity.
                            </p>

                        <?php else: ?>

                            <?php foreach($recentActivities as $activity): ?>

                            <div class="p-3 rounded-xl hover:bg-blue-50/40 transition-all flex gap-4 border-l-4 border-secondary">

                                <div class="w-10 h-10 rounded-full bg-blue-50 text-secondary flex items-center justify-center flex-shrink-0">

                                    <span class="material-symbols-outlined text-sm">
                                        person_add
                                    </span>
                                </div>

                                <div>

                                    <p class="text-sm font-bold text-primary">
                                        <?= htmlspecialchars($activity['student_name']) ?>
                                    </p>

                                    <p class="text-[10px] text-slate-500">
                                        Applied to <?= htmlspecialchars($activity['club_name']) ?>
                                    </p>

                                    <p class="text-[10px] font-black text-slate-400 mt-1 uppercase">
                                        <?= date('M d, H:i', strtotime($activity['applied_at'])) ?>
                                    </p>
                                </div>
                            </div>

                            <?php endforeach; ?>

                        <?php endif; ?>

                    </div>
                </div>
            </section>
        </div>
    </div>
</main>

</body>
</html>