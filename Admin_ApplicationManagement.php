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
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    /* =========================
       STATS (MATCH DB LOWERCASE)
    ========================== */

    $totalApplications = $pdo->query("SELECT COUNT(*) FROM club_applications")->fetchColumn();

    $pendingApplications = $pdo->query("SELECT COUNT(*) FROM club_applications WHERE status='pending'")->fetchColumn();

    $approvedApplications = $pdo->query("SELECT COUNT(*) FROM club_applications WHERE status='approved'")->fetchColumn();

    $rejectedApplications = $pdo->query("SELECT COUNT(*) FROM club_applications WHERE status='rejected'")->fetchColumn();

    /* CLUB LIST */
    $clubs = $pdo->query("
        SELECT id, club_name 
        FROM clubs 
        ORDER BY club_name ASC
    ")->fetchAll(PDO::FETCH_ASSOC);

    /* FILTERS */
    $selectedStatus = $_GET['status'] ?? 'all';
    $selectedClub = $_GET['club'] ?? 'all';

    /* MAIN QUERY */
    $query = "
        SELECT 
            ca.id,
            ca.status,
            ca.applied_at,
            u.name AS student_name,
            u.email,
            c.club_name
        FROM club_applications ca
        JOIN users u ON ca.user_id = u.id
        JOIN clubs c ON ca.club_id = c.id
        WHERE 1=1";

    $params = [];

    if ($selectedStatus !== 'all') {
        $query .= " AND ca.status = :status";
        $params[':status'] = $selectedStatus;
    }

    if ($selectedClub !== 'all') {
        $query .= " AND ca.club_id = :club_id";
        $params[':club_id'] = $selectedClub;
    }

    $query .= " ORDER BY ca.applied_at DESC";

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    die("Database Error: " . $e->getMessage());
}

$fullName = $_SESSION['name'] ?? 'Admin';
$firstName = explode(' ', trim($fullName))[0];
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>

<title>Application Management | ClubRecruit</title>

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
body{
    font-family: 'Inter', sans-serif;
}

.material-symbols-outlined{
    font-variation-settings:
    'FILL' 0,
    'wght' 400,
    'GRAD' 0,
    'opsz' 24;
}

.campus-bg{
    background-image:
    linear-gradient(rgba(248,249,255,.94), rgba(248,249,255,.94)),
    url('https://images.unsplash.com/photo-1541339907198-e08756ebafe3?auto=format&fit=crop&q=80&w=2000');

    background-size: cover;
    background-position: center;
    background-attachment: fixed;
}

.glass-card{
    background: rgba(255,255,255,0.72);
    backdrop-filter: blur(16px);
}
</style>

</head>

<body class="campus-bg text-slate-800">

<!-- SIDEBAR -->
<aside class="flex flex-col h-full w-64 fixed left-0 top-0 z-40 bg-primary text-white shadow-2xl">

    <div class="px-6 py-8 border-b border-white/10">
        <h1 class="text-2xl font-black">ClubRecruit</h1>
        <p class="text-xs text-blue-300 font-bold uppercase tracking-widest">
            Admin Portal
        </p>
    </div>

    <nav class="flex-1 px-4 space-y-2 mt-6">

        <a href="Admin_Dashboard.php"
        class="flex items-center gap-3 px-4 py-3 rounded-lg text-blue-100/70 hover:bg-white/5 hover:text-white">
            <span class="material-symbols-outlined">dashboard</span>
            <span class="text-sm">Dashboard</span>
        </a>

        <a href="Admin_UserManagement.php"
        class="flex items-center gap-3 px-4 py-3 rounded-lg text-blue-100/70 hover:bg-white/5 hover:text-white">
            <span class="material-symbols-outlined">person</span>
            <span class="text-sm">Users</span>
        </a>

        <a href="Admin_ClubManagement.php"
        class="flex items-center gap-3 px-4 py-3 rounded-lg text-blue-100/70 hover:bg-white/5 hover:text-white">
            <span class="material-symbols-outlined">groups</span>
            <span class="text-sm">Clubs</span>
        </a>

        <a href="Admin_ApplicationManagement.php"
        class="flex items-center gap-3 px-4 py-3 rounded-lg bg-white/10 text-white font-semibold shadow-inner border-l-4 border-accent">
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
        class="flex items-center gap-3 px-4 py-3 text-red-300 hover:bg-red-500/10 hover:text-red-200 rounded-lg">
            <span class="material-symbols-outlined">logout</span>
            <span class="text-sm font-bold">Logout</span>
        </a>
    </div>

</aside>

<!-- MAIN -->
<main class="ml-64 min-h-screen">

    <!-- TOPBAR -->
    <header class="flex justify-between items-center px-8 py-4 bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-30">

        <div>
            <h1 class="text-2xl font-black text-primary">
                Applications Management
            </h1>

            <p class="text-sm text-slate-500">
                Manage recruitment pipelines and application approvals.
            </p>
        </div>

        <div class="flex items-center gap-4">

            <div class="text-right">
                <p class="text-sm font-bold text-primary">
                    <?= htmlspecialchars($fullName) ?>
                </p>

                <p class="text-[10px] uppercase font-bold text-slate-400">
                    Administrator
                </p>
            </div>

            <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center font-black">
                <?= strtoupper(substr($firstName,0,1)) ?>
            </div>

        </div>

    </header>

    <!-- CONTENT -->
    <div class="p-8 max-w-7xl mx-auto space-y-8">

        <!-- HEADER -->
        <div class="flex justify-between items-end">

            <div>
                <h2 class="text-3xl font-black text-primary">
                    Applications Overview
                </h2>

                <p class="text-slate-500 mt-1">
                    Monitor student applications across all clubs.
                </p>
            </div>

            <button class="flex items-center gap-2 bg-accent text-primary font-black px-6 py-3 rounded-xl shadow-lg hover:scale-105 transition-all">
                <span class="material-symbols-outlined">add</span>
                New Application
            </button>

        </div>

        <!-- STATS -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

            <div class="glass-card rounded-3xl shadow-xl border border-white/40 p-6 border-l-4 border-secondary">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-xs font-black uppercase tracking-widest text-slate-400">
                            Total Applications
                        </p>

                        <h3 class="text-4xl font-black text-primary mt-2">
                            <?= number_format($totalApplications) ?>
                        </h3>
                    </div>

                    <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center">
                        <span class="material-symbols-outlined text-secondary text-3xl">
                            description
                        </span>
                    </div>
                </div>
            </div>

            <div class="glass-card rounded-3xl shadow-xl border border-white/40 p-6 border-l-4 border-amber-400">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-xs font-black uppercase tracking-widest text-slate-400">
                            Pending
                        </p>

                        <h3 class="text-4xl font-black text-amber-600 mt-2">
                            <?= number_format($pendingApplications) ?>
                        </h3>
                    </div>

                    <div class="w-14 h-14 rounded-2xl bg-amber-50 flex items-center justify-center">
                        <span class="material-symbols-outlined text-amber-500 text-3xl">
                            pending_actions
                        </span>
                    </div>
                </div>
            </div>

            <div class="glass-card rounded-3xl shadow-xl border border-white/40 p-6 border-l-4 border-green-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-xs font-black uppercase tracking-widest text-slate-400">
                          Approved
                        </p>

                        <h3 class="text-4xl font-black text-green-600 mt-2">
                            <?= number_format($approvedApplications) ?>
                        </h3>
                    </div>

                    <div class="w-14 h-14 rounded-2xl bg-green-50 flex items-center justify-center">
                        <span class="material-symbols-outlined text-green-500 text-3xl">
                            check_circle
                        </span>
                    </div>
                </div>
            </div>

            <div class="glass-card rounded-3xl shadow-xl border border-white/40 p-6 border-l-4 border-red-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-xs font-black uppercase tracking-widest text-slate-400">
                            Rejected
                        </p>

                        <h3 class="text-4xl font-black text-red-600 mt-2">
                            <?= number_format($rejectedApplications) ?>
                        </h3>
                    </div>

                    <div class="w-14 h-14 rounded-2xl bg-red-50 flex items-center justify-center">
                        <span class="material-symbols-outlined text-red-500 text-3xl">
                            cancel
                        </span>
                    </div>
                </div>
            </div>

        </div>

        <!-- FILTER -->
        <form method="GET"
        class="glass-card rounded-3xl shadow-xl border border-white/40 p-6 flex flex-wrap gap-6 items-end">

            <!-- STATUS -->
            <div>
                <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">
                    Status
                </label>

                <select name="status"
                class="rounded-xl border-slate-200 px-4 py-3 w-52 focus:ring-primary focus:border-primary">

                    <option value="all">All Status</option>

                    <option value="pending"
                    <?= $selectedStatus == 'pending' ? 'selected' : '' ?>>
                        Pending
                    </option>

                    <option value="approved"
                    <?= $selectedStatus == 'approved' ? 'selected' : '' ?>>
                        approved
                    </option>

                    <option value="rejected"
                    <?= $selectedStatus == 'rejected' ? 'selected' : '' ?>>
                        Rejected
                    </option>

                </select>
            </div>

            <!-- CLUB -->
            <div>
                <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">
                    Club
                </label>

                <select name="club"
                class="rounded-xl border-slate-200 px-4 py-3 w-64 focus:ring-primary focus:border-primary">

                    <option value="all">All Clubs</option>

                    <?php foreach($clubs as $club): ?>

                        <option value="<?= $club['id'] ?>"
                        <?= $selectedClub == $club['id'] ? 'selected' : '' ?>>

                            <?= htmlspecialchars($club['club_name']) ?>

                        </option>

                    <?php endforeach; ?>

                </select>
            </div>

            <!-- BUTTON -->
            <button type="submit"
            class="bg-primary text-white font-bold px-6 py-3 rounded-xl hover:bg-blue-950 transition-all">
                Apply Filters
            </button>

        </form>

        <!-- TABLE -->
        <div class="glass-card rounded-3xl shadow-xl border border-white/40 overflow-hidden">

            <div class="px-8 py-6 border-b border-slate-100 bg-white/50">
                <h3 class="text-xl font-black text-primary">
                    Application Records
                </h3>
            </div>

            <div class="overflow-x-auto">

                <table class="w-full text-left">

                    <thead class="bg-slate-50/80">

                        <tr>

                            <th class="px-8 py-4 text-xs uppercase tracking-widest text-slate-500 font-black">
                                Student
                            </th>

                            <th class="px-8 py-4 text-xs uppercase tracking-widest text-slate-500 font-black">
                                Club
                            </th>

                            <th class="px-8 py-4 text-xs uppercase tracking-widest text-slate-500 font-black">
                                Applied Date
                            </th>

                            <th class="px-8 py-4 text-xs uppercase tracking-widest text-slate-500 font-black">
                                Status
                            </th>

                            <th class="px-8 py-4 text-xs uppercase tracking-widest text-slate-500 font-black text-right">
                                Actions
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        <?php if(empty($applications)): ?>

                            <tr>
                                <td colspan="5" class="text-center py-16 text-slate-400 font-semibold">
                                    No applications found.
                                </td>
                            </tr>

                        <?php else: ?>

                            <?php foreach($applications as $application): ?>

                                <?php
                                $status = strtolower(trim($application['status']));

                                if($status == 'approved'){
                                    $statusClass = 'bg-green-100 text-green-700';
                                    $icon = 'check_circle';
                                } elseif($status == 'rejected'){
                                    $statusClass = 'bg-red-100 text-red-700';
                                    $icon = 'cancel';
                                } else {
                                    $statusClass = 'bg-amber-100 text-amber-700';
                                    $icon = 'pending_actions';
                                }
                                ?>

                                <tr class="hover:bg-blue-50/30 transition-all">

                                    <td class="px-8 py-5">

                                        <div class="flex items-center gap-4">

                                            <div class="w-11 h-11 rounded-full bg-primary-fixed text-primary flex items-center justify-center font-black">
                                                <?= strtoupper(substr($application['student_name'],0,2)) ?>
                                            </div>

                                            <div>
                                                <p class="font-bold text-primary">
                                                    <?= htmlspecialchars($application['student_name']) ?>
                                                </p>

                                                <p class="text-xs text-slate-400">
                                                    <?= htmlspecialchars($application['email']) ?>
                                                </p>
                                            </div>

                                        </div>

                                    </td>

                                    <td class="px-8 py-5 font-semibold text-slate-700">
                                        <?= htmlspecialchars($application['club_name']) ?>
                                    </td>

                                    <td class="px-8 py-5 text-slate-500 text-sm">
                                        <?= date("M d, Y h:i A", strtotime($application['applied_at'])) ?>
                                    </td>

                                    <td class="px-8 py-5">

                                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-black uppercase <?= $statusClass ?>">

                                            <span class="material-symbols-outlined text-sm">
                                                <?= $icon ?>
                                            </span>

                                            <?= htmlspecialchars($application['status']) ?>

                                        </span>

                                    </td>

                                    <td class="px-8 py-5 text-right">

                                        <div class="flex justify-end gap-2">

                                            <button class="px-4 py-2 rounded-lg bg-blue-50 text-primary font-bold hover:bg-blue-100 transition-all">
                                                View
                                            </button>

                                            <form method="POST" action="update_application.php" class="flex items-center justify-end gap-2">

                                            <input type="hidden" name="application_id" value="<?= $application['id'] ?>">

                                            <!-- STATUS SELECT (styled like badge system) -->
                                            <select name="status"
                                                class="
                                                text-xs font-bold uppercase tracking-wide
                                                px-3 py-2 rounded-xl border
                                                focus:ring-2 focus:ring-primary outline-none
                                                <?=
                                                    $application['status'] == 'approved' ? 'bg-green-50 text-green-700 border-green-200' : (
                                                    ($application['status'] == 'rejected') ? 'bg-red-50 text-red-700 border-red-200' :
                                                    'bg-amber-50 text-amber-700 border-amber-200'
                                                )
                                                ?>
                                            ">

                                                <option value="pending" class="text-amber-700">Pending</option>
                                                <option value="approved" class="text-green-700">Approved</option>
                                                <option value="rejected" class="text-red-700">Rejected</option>

                                            </select>

                                            <!-- SAVE BUTTON -->
                                            <button type="submit"
                                                class="px-4 py-2 rounded-xl bg-primary text-white font-bold text-xs hover:bg-blue-950 transition-all shadow-sm">

                                                Save

                                            </button>

                                        </form>
                                        </div>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</main>

</body>
</html>