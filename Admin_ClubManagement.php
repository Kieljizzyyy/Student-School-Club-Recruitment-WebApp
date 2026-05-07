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

    /* CREATE CLUB */
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_club'])) {

        $clubName = trim($_POST['club_name']);
        $category = trim($_POST['category']);
        $description = trim($_POST['description']);

        if (!empty($clubName) && !empty($category)) {

            $stmt = $pdo->prepare("
                INSERT INTO clubs (club_name, category, description)
                VALUES (?, ?, ?)
            ");

            $stmt->execute([$clubName, $category, $description]);

            header("Location: Admin_ClubManagement.php");
            exit();
        }
    }

    /* REMOVE / KICK MEMBER */
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_member'])) {

        $memberId = $_POST['member_id'];

        $stmt = $pdo->prepare("
            DELETE FROM club_members
            WHERE id = ?
        ");

        $stmt->execute([$memberId]);

        header("Location: Admin_ClubManagement.php");
        exit();
    }

   /* FETCH CLUBS WITH SEARCH FUNCTIONALITY */
    // 1. Check if there is a search term in the URL
    $searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

    if (!empty($searchTerm)) {
        // 2. If searching, use a WHERE clause with LIKE
        $clubStmt = $pdo->prepare("
            SELECT *
            FROM clubs
            WHERE club_name LIKE :search OR category LIKE :search
            ORDER BY club_name ASC
        ");
        $clubStmt->execute([':search' => '%' . $searchTerm . '%']);
    } else {
        // 3. Otherwise, fetch everything normally
        $clubStmt = $pdo->query("
            SELECT *
            FROM clubs
            ORDER BY club_name ASC
        ");
    }
    
    $clubs = $clubStmt->fetchAll(PDO::FETCH_ASSOC);

    /* TOTAL CLUBS */
    $totalClubs = $pdo->query("SELECT COUNT(*) FROM clubs")->fetchColumn();

    /* FETCH MEMBERS (JOINED DATA) */
    $membersStmt = $pdo->prepare("
        SELECT 
            cm.id AS membership_id,
            cm.student_id,
            cm.club_id,
            cm.joined_at,
            u.name AS student_name,
            u.email
        FROM club_members cm
        JOIN users u ON cm.student_id = u.id
        ORDER BY cm.joined_at DESC
    ");

    $membersStmt->execute();
    $members = $membersStmt->fetchAll(PDO::FETCH_ASSOC);

    /* GROUP MEMBERS BY CLUB */
    $clubMembers = [];

    foreach ($members as $m) {
        $clubMembers[$m['club_id']][] = $m;
    }

} catch(PDOException $e) {
    die("Database Error: " . $e->getMessage());
}

/* USER INFO */
$fullName = $_SESSION['name'] ?? 'Admin';
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
                        background: "#f1f5ff",
                        error: "#ba1a1a",
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
        <p class="text-xs text-blue-300 font-bold uppercase tracking-widest">
            Admin Portal
        </p>
    </div>

    <nav class="flex-1 px-4 space-y-2 mt-6">

        <a href="Admin_Dashboard.php"
           class="flex items-center gap-3 px-4 py-3 rounded-lg text-blue-100/70 hover:bg-white/5 hover:text-white transition-all">
            <span class="material-symbols-outlined">dashboard</span>
            <span class="text-sm">Dashboard</span>
        </a>

        <a href="Admin_UserManagement.php"
           class="flex items-center gap-3 px-4 py-3 rounded-lg text-blue-100/70 hover:bg-white/5 hover:text-white transition-all">
            <span class="material-symbols-outlined">person</span>
            <span class="text-sm">Users</span>
        </a>

        <a href="Admin_ClubManagement.php"
           class="flex items-center gap-3 px-4 py-3 rounded-lg bg-white/10 text-white font-semibold shadow-inner">
            <span class="material-symbols-outlined">groups</span>
            <span class="text-sm">Clubs</span>
        </a>

        <a href="Admin_ApplicationManagement.php"
           class="flex items-center gap-3 px-4 py-3 rounded-lg text-blue-100/70 hover:bg-white/5 hover:text-white transition-all">
            <span class="material-symbols-outlined">description</span>
            <span class="text-sm">Applications</span>
        </a>

        <a href="Admin_Analytics.php"
           class="flex items-center gap-3 px-4 py-3 rounded-lg text-blue-100/70 hover:bg-white/5 hover:text-white transition-all">
            <span class="material-symbols-outlined">analytics</span>
            <span class="text-sm">Analytics</span>
        </a>

    </nav>

    <div class="p-4 mt-auto border-t border-white/10">
        <a href="../logOut.php"
           class="flex items-center gap-3 px-4 py-3 text-red-300 hover:bg-red-500/10 hover:text-red-200 rounded-lg transition-all">
            <span class="material-symbols-outlined">logout</span>
            <span class="text-sm font-bold">Logout</span>
        </a>
    </div>
</aside>

<!-- MAIN -->
<main class="ml-64 min-h-screen flex flex-col">

    <!-- TOP BAR -->
    <header class="flex justify-between items-center px-8 py-4 bg-white/70 backdrop-blur-xl border-b border-white/30 shadow-sm sticky top-0 z-30">

        <form action="Admin_ClubManagement.php" method="GET" class="relative">
    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
        search
    </span>

    <input
        type="text"
        name="search"
        value="<?= htmlspecialchars($searchTerm) ?>"
        class="w-96 bg-slate-100/80 rounded-full pl-10 pr-4 py-2 text-sm focus:ring-2 focus:ring-primary/20 outline-none"
        placeholder="Search by club name or category..." />
</form>


        <div class="flex items-center gap-4">

            <div class="text-right">
                <p class="font-bold text-primary">
                    <?= htmlspecialchars($fullName) ?>
                </p>

                <p class="text-xs text-slate-500 uppercase font-semibold">
                    Administrator
                </p>
            </div>

            <div class="w-10 h-10 bg-primary text-white rounded-full flex items-center justify-center font-black shadow-lg">
                <?= strtoupper(substr($firstName, 0, 1)) ?>
            </div>

        </div>

    </header>

    <!-- CONTENT -->
    <div class="p-8 space-y-8">

        <!-- HEADER -->
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">

            <div>
                <h2 class="text-4xl font-black text-primary tracking-tight">
                    Club Management
                </h2>

                <p class="text-slate-600 mt-2 font-medium">
                    Manage campus organizations and monitor registered clubs.
                </p>
            </div>

            <!-- TOTAL CLUBS -->
            <div class="bg-primary text-white px-6 py-5 rounded-2xl shadow-xl min-w-[220px]">
                <p class="text-xs uppercase tracking-widest text-blue-200 font-bold">
                    Total Clubs
                </p>

                <h3 class="text-4xl font-black mt-2">
                    <?= number_format($totalClubs) ?>
                </h3>
            </div>

        </div>

        <!-- CREATE CLUB -->
        <div class="glass-card rounded-3xl shadow-xl border border-white/40 p-8">

            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 rounded-2xl bg-primary text-white flex items-center justify-center shadow-lg">
                    <span class="material-symbols-outlined">groups</span>
                </div>

                <div>
                    <h3 class="text-2xl font-black text-primary">
                        Create New Club
                    </h3>

                    <p class="text-sm text-slate-500">
                        Add a new organization into the recruitment system.
                    </p>
                </div>
            </div>

            <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <label class="text-sm font-bold text-primary block mb-2">
                        Club Name
                    </label>

                    <input
                        type="text"
                        name="club_name"
                        required
                        class="w-full bg-slate-100 rounded-xl px-4 py-3 border border-slate-200 focus:ring-2 focus:ring-primary/20 outline-none"
                        placeholder="Enter club name">
                </div>

                <div>
                    <label class="text-sm font-bold text-primary block mb-2">
                        Category
                    </label>

                    <input
                        type="text"
                        name="category"
                        required
                        class="w-full bg-slate-100 rounded-xl px-4 py-3 border border-slate-200 focus:ring-2 focus:ring-primary/20 outline-none"
                        placeholder="e.g Programming, Sports">
                </div>

                <div class="md:col-span-2">
                    <label class="text-sm font-bold text-primary block mb-2">
                        Description
                    </label>

                    <textarea
                        name="description"
                        rows="4"
                        class="w-full bg-slate-100 rounded-xl px-4 py-3 border border-slate-200 focus:ring-2 focus:ring-primary/20 outline-none resize-none"
                        placeholder="Enter club description"></textarea>
                </div>

                <div class="md:col-span-2">
                    <button
                        type="submit"
                        name="create_club"
                        class="flex items-center gap-2 bg-primary text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:bg-blue-900 transition-all hover:scale-105 active:scale-95">

                        <span class="material-symbols-outlined">
                            add_circle
                        </span>

                        Create Club
                    </button>
                </div>

            </form>
        </div>

        <!-- CLUB LIST -->
<div>

    <div class="flex items-center justify-between mb-6">

        <div>
            <h3 class="text-2xl font-black text-primary">
                Registered Clubs
            </h3>

            <p class="text-slate-500 text-sm">
                Dynamically showing all clubs from database.
            </p>
        </div>

    </div>

    <?php if(empty($clubs)): ?>

        <div class="glass-card rounded-2xl p-10 text-center border border-white/40">
            <span class="material-symbols-outlined text-6xl text-slate-300">
                groups
            </span>

            <p class="mt-4 text-slate-500 font-medium">
                No clubs available yet.
            </p>
        </div>

    <?php else: ?>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

            <?php foreach($clubs as $club): ?>

            <!-- CLUB CARD -->
            <div class="bg-gradient-to-br from-primary to-secondary text-white rounded-3xl p-6 shadow-2xl hover:scale-[1.02] transition-all duration-300">

                <div class="flex items-start justify-between">

                    <div class="w-14 h-14 rounded-2xl bg-white/10 backdrop-blur flex items-center justify-center">
                        <span class="material-symbols-outlined text-3xl">groups</span>
                    </div>

                    <span class="text-[10px] uppercase tracking-widest bg-white/10 px-3 py-1 rounded-full font-bold">
                        <?= htmlspecialchars($club['category']) ?>
                    </span>

                </div>

                <div class="mt-6">

                    <h3 class="text-2xl font-black leading-tight">
                        <?= htmlspecialchars($club['club_name']) ?>
                    </h3>

                    <p class="text-blue-100 text-sm mt-3">
                        <?= !empty($club['description']) 
                            ? htmlspecialchars($club['description']) 
                            : 'No description available.' ?>
                    </p>

                </div>

                <div class="mt-6 pt-4 border-t border-white/10 flex justify-between items-center">

                    <p class="text-xs text-blue-200 uppercase tracking-widest font-bold">
                        Club Organization
                    </p>

                    <!-- VIEW BUTTON -->
                    <button 
                        onclick="openModal(<?= $club['id'] ?>)"
                        class="bg-white/10 hover:bg-white/20 transition-all px-4 py-2 rounded-xl text-sm font-bold">
                        View
                    </button>

                </div>

            </div>

            <!-- MODAL -->
            <div id="modal-<?= $club['id'] ?>" 
                 class="hidden fixed inset-0 bg-black/60 flex items-center justify-center z-50">

                <div class="bg-white w-[420px] max-h-[80vh] overflow-y-auto rounded-2xl p-6 shadow-2xl">

                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-black text-primary">
                            <?= htmlspecialchars($club['club_name']) ?> Members
                        </h2>

                        <button onclick="closeModal(<?= $club['id'] ?>)"
                                class="text-slate-500 text-xl font-bold">
                            ×
                        </button>
                    </div>

                    <?php if (!empty($clubMembers[$club['id']])): ?>

                        <?php foreach ($clubMembers[$club['id']] as $member): ?>

                            <div class="flex justify-between items-center border-b py-2">

                                <div>
                                    <p class="font-bold text-sm">
                                        <?= htmlspecialchars($member['student_name']) ?>
                                    </p>

                                    <p class="text-xs text-slate-500">
                                        <?= htmlspecialchars($member['email']) ?>
                                    </p>
                                </div>

                                <form method="POST">
                                    <input type="hidden" name="member_id"
                                           value="<?= $member['membership_id'] ?>">

                                    <button type="submit"
                                            name="remove_member"
                                            class="text-xs bg-red-500 text-white px-3 py-1 rounded-lg">
                                        Kick
                                    </button>
                                </form>

                            </div>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <p class="text-sm text-slate-500">
                            No members yet.
                        </p>

                    <?php endif; ?>

                    <button onclick="closeModal(<?= $club['id'] ?>)"
                            class="mt-4 w-full bg-slate-200 py-2 rounded-lg font-bold">
                        Close
                    </button>

                </div>

            </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</div>

    </div>

</main>

        <script>
function openModal(id){
    document.getElementById("modal-" + id).classList.remove("hidden");
}

function closeModal(id){
    document.getElementById("modal-" + id).classList.add("hidden");
}
</script>

</body>
</html>