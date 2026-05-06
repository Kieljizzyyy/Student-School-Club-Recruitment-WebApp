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
        accent: "#ECC94B"
      }
    }
  }
}
</script>

<style>
body { font-family: Inter, sans-serif; }

.material-symbols-outlined{
  font-variation-settings:'FILL' 0,'wght' 400,'GRAD' 0,'opsz' 24;
}

/* NAV STYLE */
.nav-item{
  color: rgba(255,255,255,0.7);
  transition: 0.2s;
}

.nav-item:hover{
  background: rgba(255,255,255,0.06);
  color: white;
}

.nav-active{
  background: rgba(255,255,255,0.10);
  color: white;
  font-weight: 700;
  border-left: 4px solid #ECC94B;
}

/* BUTTON FIX (GLOBAL CONSISTENCY) */
.btn-primary{
  background: #002045;
  color: white;
  font-weight: 700;
}

.btn-primary:hover{
  background: #001633;
}

.btn-accent{
  background: #ECC94B;
  color: #002045;
  font-weight: 800;
}

.btn-accent:hover{
  filter: brightness(1.05);
}
</style>
</head>

<body class="bg-[#f8f9ff]">

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

       
        <a href="Admin_ClubManagement.php"
           class="flex items-center gap-3 px-4 py-3 rounded-lg text-blue-100/70 hover:bg-white/5 hover:text-white">
            <span class="material-symbols-outlined">groups</span>
            <span class="text-sm">Clubs</span>
        </a>

        <!-- Applications -->
        <a href="Admin_ApplicationManagement.php"
           class="flex items-center gap-3 px-4 py-3 rounded-lg bg-white/10 text-white font-semibold shadow-inner">
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

<!-- MAIN CONTENT (UNCHANGED) -->
<main class="ml-64 p-8">

<!-- HEADER -->
<div class="flex justify-between items-end mb-6">
  <div>
    <h2 class="text-3xl font-black text-primary">Applications Management</h2>
    <p class="text-gray-500">Manage student recruitment pipelines across all organizations.</p>
  </div>

  <button class="btn-accent flex items-center gap-2 px-6 py-3 rounded-xl shadow-md">
    <span class="material-symbols-outlined">add</span>
    New Application
  </button>
</div>
</div>
<div class="bento-grid">
<!-- Total Card -->
<div class="col-span-1 bg-white p-6 rounded-xl shadow-[0_2px_4px_rgba(26,54,93,0.1)] border-t-4 border-[#2B6CB0]">
<div class="flex items-center justify-between mb-4">
<span class="material-symbols-outlined text-[#2B6CB0] p-2 bg-blue-50 rounded-lg" data-icon="description">description</span>
<span class="text-xs font-label-caps text-green-600 bg-green-50 px-2 py-1 rounded-full">+12%</span>
</div>
<p class="text-body-sm text-outline uppercase tracking-wider font-bold">Total Applications</p>
<p class="text-h2 text-primary mt-1">2,482</p>
</div>
<!-- Pending Card -->
<div class="col-span-1 bg-white p-6 rounded-xl shadow-[0_2px_4px_rgba(26,54,93,0.1)] border-t-4 border-[#ECC94B]">
<div class="flex items-center justify-between mb-4">
<span class="material-symbols-outlined text-[#ECC94B] p-2 bg-yellow-50 rounded-lg" data-icon="pending_actions">pending_actions</span>
<span class="text-xs font-label-caps text-yellow-600">Active Review</span>
</div>
<p class="text-body-sm text-outline uppercase tracking-wider font-bold">Pending Approval</p>
<p class="text-h2 text-primary mt-1">843</p>
</div>
<!-- Success Card -->
<div class="col-span-1 bg-white p-6 rounded-xl shadow-[0_2px_4px_rgba(26,54,93,0.1)] border-t-4 border-green-500">
<div class="flex items-center justify-between mb-4">
<span class="material-symbols-outlined text-green-500 p-2 bg-green-50 rounded-lg" data-icon="check_circle">check_circle</span>
<span class="text-xs font-label-caps text-green-600">34.2% Rate</span>
</div>
<p class="text-body-sm text-outline uppercase tracking-wider font-bold">Accepted</p>
<p class="text-h2 text-primary mt-1">1,120</p>
</div>
<!-- Rejected Card -->
<div class="col-span-1 bg-white p-6 rounded-xl shadow-[0_2px_4px_rgba(26,54,93,0.1)] border-t-4 border-error">
<div class="flex items-center justify-between mb-4">
<span class="material-symbols-outlined text-error p-2 bg-red-50 rounded-lg" data-icon="cancel">cancel</span>
<span class="text-xs font-label-caps text-error">Requires Override</span>
</div>
<p class="text-body-sm text-outline uppercase tracking-wider font-bold">Rejected</p>
<p class="text-h2 text-primary mt-1">519</p>
</div>
</div>
</div>
<!-- Filters Section -->
<div class="bg-white p-gutter rounded-xl shadow-[0_2px_4px_rgba(26,54,93,0.1)] mb-gutter flex flex-wrap items-center gap-6">
<div class="flex-1 min-w-[200px]">
<label class="block text-xs font-label-caps text-outline mb-2 uppercase">Status Filter</label>
<div class="flex gap-2">
<button class="bg-[#2B6CB0] text-white px-4 py-1.5 rounded-full text-sm font-medium">All</button>
<button class="bg-slate-100 text-slate-600 px-4 py-1.5 rounded-full text-sm font-medium hover:bg-slate-200">Pending</button>
<button class="bg-slate-100 text-slate-600 px-4 py-1.5 rounded-full text-sm font-medium hover:bg-slate-200">Accepted</button>
<button class="bg-slate-100 text-slate-600 px-4 py-1.5 rounded-full text-sm font-medium hover:bg-slate-200">Rejected</button>
</div>
</div>
<div class="w-64">
<label class="block text-xs font-label-caps text-outline mb-2 uppercase">Club/Organization</label>
<select class="w-full border-[#E2E8F0] rounded-lg text-body-sm focus:ring-[#2B6CB0] focus:border-[#2B6CB0]">
<option>All Organizations</option>
<option>Debate Society</option>
<option>IEEE Student Branch</option>
<option>Varsity Soccer</option>
<option>Business Analytics Club</option>
</select>
</div>
<div class="w-48">
<label class="block text-xs font-label-caps text-outline mb-2 uppercase">Date Range</label>
<div class="relative">
<input class="w-full border-[#E2E8F0] rounded-lg text-body-sm pl-10" placeholder="Last 30 Days" type="text"/>
<span class="material-symbols-outlined absolute left-3 top-2.5 text-slate-400 text-sm" data-icon="calendar_today">calendar_today</span>
</div>
</div>
</div>
<!-- Detailed Application Table -->
<div class="bg-white rounded-xl shadow-[0_2px_4px_rgba(26,54,93,0.1)] overflow-hidden">
<div class="px-gutter py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
<h3 class="font-h3 text-primary text-lg">Active Application Logs</h3>
<span class="text-body-sm text-outline">Showing 1-10 of 2,482 applications</span>
</div>
<table class="w-full text-left">
<thead>
<tr class="bg-white border-b border-slate-100">
<th class="px-gutter py-4 font-label-caps text-outline uppercase">Applicant</th>
<th class="px-gutter py-4 font-label-caps text-outline uppercase">Target Organization</th>
<th class="px-gutter py-4 font-label-caps text-outline uppercase">Submitted</th>
<th class="px-gutter py-4 font-label-caps text-outline uppercase">Status</th>
<th class="px-gutter py-4 font-label-caps text-outline uppercase text-right">Actions</th>
</tr>
</thead>
<tbody class="divide-y divide-slate-50">
<!-- Row 1 -->
<tr class="hover:bg-slate-50/50 transition-colors">
<td class="px-gutter py-4">
<div class="flex items-center gap-3">
<img alt="Student Profile" class="h-10 w-10 rounded-full border border-slate-200" data-alt="close-up portrait of a young male student smiling in a campus courtyard, natural soft daylight" src="https://lh3.googleusercontent.com/aida-public/AB6AXuA-RsQdzofTcCh0XRvY_aMdhttBCqxGiyRwFQV0mAo5iD8KekXPJb1EEudMOPuiJ7MPnTxO8X9aBu7655dU62raISTnfuAIAzkAmUDPl9bzHrmGah4_qmb62qriVQdyk9hNSajOnth5Cka7EYPig_RbINTirQy2f9UiL3D_4onT6q0XpGAOvGy-Gi2cDxMOKn5WSbj5RGJGtLrlEqL_LotZE7iTXH3SdvIoqXI89tzaNuNDP9fEL1cRL6IFZZXk1W4-lGJi8jlEbdgS"/>
<div>
<p class="font-semibold text-primary">Alex Chen</p>
<p class="text-xs text-outline">alex.chen@university.edu</p>
</div>
</div>
</td>
<td class="px-gutter py-4">
<div class="flex items-center gap-2">
<div class="w-2 h-8 bg-[#2B6CB0] rounded-full"></div>
<span class="font-medium">Debate Society</span>
</div>
</td>
<td class="px-gutter py-4 text-body-sm text-outline">Oct 12, 2023</td>
<td class="px-gutter py-4">
<span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-bold flex items-center w-fit gap-1">
<span class="material-symbols-outlined text-xs" data-icon="history">history</span>
                                Pending Review
                            </span>
</td>
<td class="px-gutter py-4 text-right">
<button class="text-[#2B6CB0] hover:underline font-button text-sm mr-4">View Detail</button>
<button class="bg-primary-container text-white px-3 py-1 rounded-lg text-xs font-bold hover:bg-primary transition-colors">Override</button>
</td>
</tr>
<!-- Row 2 -->
<tr class="hover:bg-slate-50/50 transition-colors">
<td class="px-gutter py-4">
<div class="flex items-center gap-3">
<img alt="Student Profile" class="h-10 w-10 rounded-full border border-slate-200" data-alt="portrait of a confident young woman student with long hair in a library setting, warm indoor lighting" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDndPPICFBIcfBy3Fi7BCuILkb1yyQp7p0hqN88QOooqCWOLrc4DWgPAx9neu7FqZNZoKbXqQq4hrlqcqN4Nl9787NNuYfHEyH9ALafKerl3uDDlaTG3yo-KdgW-7rP_SqGPARC51_-Im37RRX7rq46q_AvguB8iOgOxmPnGfFV-i4XPK-ZHHPIWEgEcOnI7sWeMCNDwPNVDSkqKv6O7hUDSHqxfL2qd49XQMeeCrJc22GWUt_jctdE0lewnjWLcGTSBCUBTT4yV3Eu"/>
<div>
<p class="font-semibold text-primary">Sarah Miller</p>
<p class="text-xs text-outline">s.miller@university.edu</p>
</div>
</div>
</td>
<td class="px-gutter py-4">
<div class="flex items-center gap-2">
<div class="w-2 h-8 bg-[#ECC94B] rounded-full"></div>
<span class="font-medium">Business Analytics</span>
</div>
</td>
<td class="px-gutter py-4 text-body-sm text-outline">Oct 11, 2023</td>
<td class="px-gutter py-4">
<span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold flex items-center w-fit gap-1">
<span class="material-symbols-outlined text-xs" data-icon="check_circle" data-weight="fill" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                                Accepted
                            </span>
</td>
<td class="px-gutter py-4 text-right">
<button class="text-[#2B6CB0] hover:underline font-button text-sm mr-4">View Detail</button>
<button class="bg-primary-container text-white px-3 py-1 rounded-lg text-xs font-bold hover:bg-primary transition-colors">Override</button>
</td>
</tr>
<!-- Row 3 -->
<tr class="hover:bg-slate-50/50 transition-colors">
<td class="px-gutter py-4">
<div class="flex items-center gap-3">
<img alt="Student Profile" class="h-10 w-10 rounded-full border border-slate-200" data-alt="headshot of a smiling young man wearing glasses, scholarly look in an academic environment" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCiXaFwhptPjJdGZ7W030V1ms7YufbV-U1fyTMiujgmhfenlVZ4keoFaWfPeT9lc8eCoGVP6lnLqP9qkFbqe2n3gxBhS4OJWpJmXH7eIYFO7kZMwCa9dJLZVmAH_bQ2WAVYq72D_7poo-L0qXRXIvu_Ob5K5qxcao5EnpoxDELTNohRksuw--0mr3-DYssNhLXa291HgFhz63-ZIoZ5WnvXcS4TqxAzuUwXrmPFjwMqL2eGT9yGp6sAp54sfVvyimMNvDP6ENrreQuP"/>
<div>
<p class="font-semibold text-primary">Jason Wang</p>
<p class="text-xs text-outline">jasonw@university.edu</p>
</div>
</div>
</td>
<td class="px-gutter py-4">
<div class="flex items-center gap-2">
<div class="w-2 h-8 bg-slate-300 rounded-full"></div>
<span class="font-medium">IEEE Student Branch</span>
</div>
</td>
<td class="px-gutter py-4 text-body-sm text-outline">Oct 10, 2023</td>
<td class="px-gutter py-4">
<span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold flex items-center w-fit gap-1">
<span class="material-symbols-outlined text-xs" data-icon="cancel" data-weight="fill" style="font-variation-settings: 'FILL' 1;">cancel</span>
                                Rejected
                            </span>
</td>
<td class="px-gutter py-4 text-right">
<button class="text-[#2B6CB0] hover:underline font-button text-sm mr-4">View Detail</button>
<button class="bg-primary-container text-white px-3 py-1 rounded-lg text-xs font-bold hover:bg-primary transition-colors">Override</button>
</td>
</tr>
</tbody>
</table>
<!-- Pagination -->
<div class="px-gutter py-6 bg-slate-50 flex items-center justify-between">
<button class="text-body-sm text-outline hover:text-primary flex items-center gap-1 transition-colors">
<span class="material-symbols-outlined text-sm" data-icon="chevron_left">chevron_left</span>
                    Previous
                </button>
<div class="flex gap-2">
<button class="w-8 h-8 rounded-lg bg-[#2B6CB0] text-white font-bold text-xs">1</button>
<button class="w-8 h-8 rounded-lg bg-white text-primary border border-slate-200 font-bold text-xs hover:bg-slate-100">2</button>
<button class="w-8 h-8 rounded-lg bg-white text-primary border border-slate-200 font-bold text-xs hover:bg-slate-100">3</button>
<span class="text-outline pt-2">...</span>
<button class="w-8 h-8 rounded-lg bg-white text-primary border border-slate-200 font-bold text-xs hover:bg-slate-100">24</button>
</div>
<button class="text-body-sm text-outline hover:text-primary flex items-center gap-1 transition-colors">
                    Next
                    <span class="material-symbols-outlined text-sm" data-icon="chevron_right">chevron_right</span>
</button>
</div>
</div>
</main>
<!-- FAB Action (Suppressed on Admin Detail screens, but present on Overview) -->
<div class="fixed bottom-8 right-8 z-50">
<button class="w-14 h-14 bg-[#ECC94B] rounded-full shadow-xl flex items-center justify-center text-[#002045] hover:scale-110 active:scale-95 transition-all">
<span class="material-symbols-outlined" data-icon="edit">edit</span>
</button>
</div>
</body></html>