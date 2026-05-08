<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../logIn.php");
    exit();
}

/* DATABASE */
$host = "localhost";
$dbname = "it26b_StudentClubRecruitment";
$username = "root";
$password = "";

try {

    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8",
        $username,
        $password
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    /* HELPERS */
    function fetchSingle($pdo, $query) {
        return $pdo->query($query)->fetchColumn();
    }

    function fetchAll($pdo, $query) {
        return $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =========================
       SUMMARY STATS
    ========================== */

    // TOTAL APPLICATIONS
    $totalApplications = fetchSingle($pdo, "
        SELECT COUNT(*) 
        FROM club_applications
    ");

    // APPROVED APPLICATIONS
    $approvedApplications = fetchSingle($pdo, "
        SELECT COUNT(*) 
        FROM club_applications
        WHERE status = 'approved'
    ");

    // REJECTED APPLICATIONS
    $rejectedApplications = fetchSingle($pdo, "
        SELECT COUNT(*) 
        FROM club_applications
        WHERE status = 'rejected'
    ");

    // PENDING APPLICATIONS
    $pendingApplications = fetchSingle($pdo, "
        SELECT COUNT(*) 
        FROM club_applications
        WHERE status = 'pending'
    ");

    // TOTAL STUDENTS
    $totalStudents = fetchSingle($pdo, "
        SELECT COUNT(*) 
        FROM users
        WHERE role = 'student'
    ");

    // TOTAL CLUBS
    $activeClubs = fetchSingle($pdo, "
        SELECT COUNT(*) 
        FROM clubs
    ");

    // SUCCESS RATE
    $successRate = ($totalApplications > 0)
        ? ($approvedApplications / $totalApplications) * 100
        : 0;

    /* =========================
       TOP CLUBS
    ========================== */

    $topClubs = fetchAll($pdo, "
        SELECT
            c.id,
            c.club_name,
            c.category,

            COUNT(ca.id) AS total_applications,

            SUM(
                CASE
                    WHEN ca.status = 'approved'
                    THEN 1
                    ELSE 0
                END
            ) AS approved_count

        FROM clubs c

        LEFT JOIN club_applications ca
            ON c.id = ca.club_id

        GROUP BY c.id

        ORDER BY total_applications DESC
    ");

    /* =========================
       CLUB ANALYTICS TABLE
    ========================== */

    $clubsTable = fetchAll($pdo, "
        SELECT
            c.id,
            c.club_name,
            c.category,

            COUNT(ca.id) AS applicants,

            SUM(
                CASE
                    WHEN ca.status = 'approved'
                    THEN 1
                    ELSE 0
                END
            ) AS approved_members,

            SUM(
                CASE
                    WHEN ca.status = 'pending'
                    THEN 1
                    ELSE 0
                END
            ) AS pending_members,

            SUM(
                CASE
                    WHEN ca.status = 'rejected'
                    THEN 1
                    ELSE 0
                END
            ) AS rejected_members

        FROM clubs c

        LEFT JOIN club_applications ca
            ON c.id = ca.club_id

        GROUP BY c.id

        ORDER BY applicants DESC
    ");

    /* =========================
       MONTHLY APPLICATION DATA
    ========================== */

    $monthlyRaw = fetchAll($pdo, "
        SELECT
            MONTH(applied_at) AS month_num,
            COUNT(*) AS total

        FROM club_applications

        WHERE YEAR(applied_at) = YEAR(CURDATE())

        GROUP BY MONTH(applied_at)
    ");

    $monthlyData = array_fill(1, 12, 0);

    foreach ($monthlyRaw as $row) {

        $monthlyData[(int)$row['month_num']]
            = (int)$row['total'];
    }

} catch(PDOException $e) {

    die('Database Error: ' . $e->getMessage());
}

/* USER INFO */
$fullName = $_SESSION['name'] ?? 'Admin';
$firstName = explode(' ', trim($fullName))[0];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Analytics Dashboard | ClubRecruit</title>

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
                background: "#f1f5ff",
                "primary-fixed": "#d6e3ff"
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
        linear-gradient(rgba(235, 242, 255, 0.96), rgba(235, 242, 255, 0.96)),
        url('https://images.unsplash.com/photo-1541339907198-e08756ebafe3?auto=format&fit=crop&q=80&w=2000');

    background-size: cover;
    background-position: center;
    background-attachment: fixed;
}

.glass-card {
    background: rgba(255,255,255,0.85);
    backdrop-filter: blur(12px);
}
</style>

</head>

<body class="campus-bg text-slate-800">

<!-- SIDEBAR -->
<aside class="flex flex-col h-full w-64 fixed left-0 top-0 z-40 bg-primary text-white shadow-2xl">

    <div class="px-6 py-8 border-b border-white/10">
        <h1 class="text-2xl font-black">ClubRecruit</h1>
        <p class="text-xs text-blue-300 uppercase font-bold tracking-widest">
            Admin Portal
        </p>
    </div>

    <nav class="flex-1 px-4 space-y-2 mt-6">

        <a href="Admin_Dashboard.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-blue-100/70 hover:bg-white/5 hover:text-white">
            <span class="material-symbols-outlined">dashboard</span>
            Dashboard
        </a>

        <a href="Admin_UserManagement.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-blue-100/70 hover:bg-white/5 hover:text-white">
            <span class="material-symbols-outlined">person</span>
            Users
        </a>

        <a href="Admin_ClubManagement.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-blue-100/70 hover:bg-white/5 hover:text-white">
            <span class="material-symbols-outlined">groups</span>
            Clubs
        </a>

        <a href="Admin_ApplicationManagement.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-blue-100/70 hover:bg-white/5 hover:text-white">
            <span class="material-symbols-outlined">description</span>
            Applications
        </a>

        <a href="Admin_Analytics.php" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-white/10 text-white font-semibold shadow-inner">
            <span class="material-symbols-outlined">analytics</span>
            Analytics
        </a>

    </nav>

</aside>

<!-- MAIN -->
<main class="ml-64 min-h-screen flex flex-col">

<header class="flex justify-between items-center px-8 py-4 bg-white/80 backdrop-blur-md border-b">

    <input class="w-96 bg-slate-100 rounded-full px-4 py-2 text-sm"
           placeholder="Search analytics..."/>

    <div class="flex items-center gap-4">

        <div class="text-right">
            <p class="font-bold text-primary">
                <?= htmlspecialchars($fullName) ?>
            </p>
            <p class="text-xs text-slate-500">Administrator</p>
        </div>

        <div class="w-10 h-10 bg-primary text-white rounded-full flex items-center justify-center font-bold">
            <?= strtoupper(substr($firstName,0,1)) ?>
        </div>

    </div>

</header>

<div class="p-8 space-y-8">

    <!-- TITLE -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-black text-primary">
                Analytics Dashboard
            </h1>
            <p class="text-slate-500">
                Fully dynamic recruitment system
            </p>
        </div>
    </div>

    <!-- STATS -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

        <div class="bg-primary-fixed p-6 rounded-2xl border-l-4 border-primary">
            <p class="text-sm text-slate-500">Total Applications</p>
            <p class="text-3xl font-black text-primary"><?= $totalApplications ?></p>
        </div>

        <div class="bg-primary-fixed p-6 rounded-2xl border-l-4 border-accent">
            <p class="text-sm text-slate-500">Success Rate</p>
            <p class="text-3xl font-black text-primary"><?= number_format($successRate,1) ?>%</p>
        </div>

        <div class="bg-primary-fixed p-6 rounded-2xl border-l-4 border-secondary">
            <p class="text-sm text-slate-500">Students</p>
            <p class="text-3xl font-black text-primary"><?= $totalStudents ?></p>
        </div>

        <div class="bg-primary-fixed p-6 rounded-2xl border-l-4 border-emerald-500">
            <p class="text-sm text-slate-500">Active Clubs</p>
            <p class="text-3xl font-black text-primary"><?= $activeClubs ?></p>
        </div>

    </div>

    <!-- TOP CLUBS -->
    <div class="glass-card p-8 rounded-3xl">

        <h3 class="text-xl font-black text-primary mb-6">
            Top Clubs
        </h3>

        <div class="space-y-4">

            <?php foreach($topClubs as $c): ?>
            <div class="flex justify-between bg-white/70 p-4 rounded-xl">
                <div>
                    <p class="font-bold text-primary"><?= $c['club_name'] ?></p>
                    <p class="text-xs text-slate-500"><?= $c['category'] ?></p>
                </div>
                <p class="font-black text-primary"><?= $c['total_applications'] ?></p>
            </div>
            <?php endforeach; ?>

        </div>

    </div>

</div>

</main>

</body>
</html>