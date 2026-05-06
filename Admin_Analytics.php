<!DOCTYPE html>
<html class="light" lang="en">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>ClubRecruit Admin - Analytics Dashboard</title>

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
        background: "#f8f9ff"
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
  background-image: linear-gradient(rgba(248, 249, 255, 0.92), rgba(248, 249, 255, 0.92)),
  url('https://images.unsplash.com/photo-1541339907198-e08756ebafe3?auto=format&fit=crop&q=80&w=2000');
  background-attachment: fixed;
  background-size: cover;
  background-position: center;
}
</style>
</head>

<body class="campus-bg bg-background">

<!-- SIDEBAR (MATCHED DESIGN) -->
<aside class="flex flex-col h-full w-64 fixed left-0 top-0 z-40 bg-primary text-white shadow-2xl">

<div class="px-6 py-8 border-b border-white/10">
  <h1 class="text-2xl font-black">ClubRecruit</h1>
  <p class="text-xs text-blue-300 uppercase font-bold tracking-widest">Admin Portal</p>
</div>

<nav class="flex-1 px-4 space-y-2 mt-6">

<a href="Admin_Dashboard.php"
class="flex items-center gap-3 px-4 py-3 rounded-lg text-blue-100/70 hover:bg-white/5 hover:text-white">
<span class="material-symbols-outlined">dashboard</span>
Dashboard
</a>

<a href="Admin_UserManagement.php"
class="flex items-center gap-3 px-4 py-3 rounded-lg text-blue-100/70 hover:bg-white/5 hover:text-white">
<span class="material-symbols-outlined">person</span>
Users
</a>

<a href="Admin_ClubManagement.php"
class="flex items-center gap-3 px-4 py-3 rounded-lg text-blue-100/70 hover:bg-white/5 hover:text-white">
<span class="material-symbols-outlined">groups</span>
Clubs
</a>

<a href="Admin_ApplicationManagement.php"
class="flex items-center gap-3 px-4 py-3 rounded-lg text-blue-100/70 hover:bg-white/5 hover:text-white">
<span class="material-symbols-outlined">description</span>
Applications
</a>

<!-- ACTIVE -->
<a href="Admin_Analytics.php"
class="flex items-center gap-3 px-4 py-3 rounded-lg bg-white/10 text-white font-semibold shadow-inner">
<span class="material-symbols-outlined">analytics</span>
Analytics
</a>

</nav>

<div class="p-4 mt-auto border-t border-white/10">
<a href="../logOut.php"
class="flex items-center gap-3 px-4 py-3 text-red-300 hover:bg-red-500/10 rounded-lg">
<span class="material-symbols-outlined">logout</span>
Logout
</a>
</div>

</aside>

<!-- MAIN -->
<main class="ml-64 min-h-screen flex flex-col">

<!-- TOP BAR -->
<header class="flex justify-between items-center px-8 py-4 bg-white/80 backdrop-blur-md border-b">

<input class="w-96 bg-slate-100 rounded-full px-4 py-2 text-sm"
placeholder="Search analytics, clubs, reports..." />

<div class="flex items-center gap-4">

<button class="p-2 rounded-full hover:bg-slate-100 text-primary">
<span class="material-symbols-outlined">notifications</span>
</button>

<div class="text-right">
<p class="font-bold text-primary">Admin User</p>
<p class="text-xs text-slate-500">Administrator</p>
</div>

<div class="w-10 h-10 bg-primary text-white rounded-full flex items-center justify-center font-bold">
A
</div>

</div>
</header>

<!-- CONTENT (UNCHANGED - YOUR ORIGINAL DESIGN) -->
<div class="p-8 max-w-[1440px] mx-auto w-full">

<div class="flex justify-between items-center mb-8">
<div>
<h1 class="text-3xl font-black text-primary">Analytics Dashboard</h1>
<p class="text-slate-500">Recruitment insights for Fall Semester 2024</p>
</div>

<div class="flex gap-3">
<button class="px-4 py-2 bg-white border rounded-lg text-sm font-bold hover:bg-slate-50">
Last 30 Days
</button>

<button class="px-6 py-2 bg-primary text-white rounded-lg font-bold hover:bg-blue-900">
Export Reports
</button>
</div>
</div>

<!-- SUMMARY STATS (UNCHANGED CONTENT) -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

<div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-600">
<p class="text-slate-500 text-sm">Total Applications</p>
<p class="text-2xl font-black text-primary">3,482</p>
</div>

<div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-yellow-400">
<p class="text-slate-500 text-sm">Success Rate</p>
<p class="text-2xl font-black text-primary">68.2%</p>
</div>

<div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-slate-400">
<p class="text-slate-500 text-sm">New Students</p>
<p class="text-2xl font-black text-primary">1,105</p>
</div>

<div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-600">
<p class="text-slate-500 text-sm">Active Clubs</p>
<p class="text-2xl font-black text-primary">248</p>
</div>

</div>
<!-- Main Charts Section (Bento Grid) -->
<div class="grid grid-cols-1 lg:grid-cols-3 grid-rows-2 gap-gutter h-auto lg:h-[800px]">
<!-- Applications per Club (Bar Chart View) -->
<div class="lg:col-span-2 bg-white p-8 rounded-xl level-1-shadow flex flex-col">
<div class="flex justify-between items-center mb-8">
<div>
<h3 class="font-h3 text-brand-navy">Applications Volume per Club</h3>
<p class="text-body-sm text-slate-500">Distribution across major categories</p>
</div>
<div class="flex gap-2">
<span class="flex items-center gap-1 text-[10px] font-bold text-slate-400 uppercase"><span class="w-2 h-2 rounded-full bg-brand-sapphire"></span> 2024</span>
<span class="flex items-center gap-1 text-[10px] font-bold text-slate-400 uppercase"><span class="w-2 h-2 rounded-full bg-brand-gold"></span> 2023</span>
</div>
</div>
<div class="flex-1 flex items-end gap-6 pb-6 border-b border-slate-100">
<!-- Simulated Bar Chart -->
<div class="flex-1 flex flex-col items-center group">
<div class="w-full max-w-[40px] bg-brand-sapphire rounded-t-lg transition-all h-[85%] group-hover:brightness-110"></div>
<div class="w-full max-w-[40px] bg-brand-gold/30 rounded-t-sm h-[60%] -mt-10"></div>
<p class="text-[10px] font-bold text-slate-400 mt-4 rotate-45 lg:rotate-0">STEM</p>
</div>
<div class="flex-1 flex flex-col items-center group">
<div class="w-full max-w-[40px] bg-brand-sapphire rounded-t-lg transition-all h-[65%] group-hover:brightness-110"></div>
<div class="w-full max-w-[40px] bg-brand-gold/30 rounded-t-sm h-[70%] -mt-10"></div>
<p class="text-[10px] font-bold text-slate-400 mt-4">Arts</p>
</div>
<div class="flex-1 flex flex-col items-center group">
<div class="w-full max-w-[40px] bg-brand-sapphire rounded-t-lg transition-all h-[95%] group-hover:brightness-110"></div>
<div class="w-full max-w-[40px] bg-brand-gold/30 rounded-t-sm h-[40%] -mt-10"></div>
<p class="text-[10px] font-bold text-slate-400 mt-4">Sports</p>
</div>
<div class="flex-1 flex flex-col items-center group">
<div class="w-full max-w-[40px] bg-brand-sapphire rounded-t-lg transition-all h-[45%] group-hover:brightness-110"></div>
<div class="w-full max-w-[40px] bg-brand-gold/30 rounded-t-sm h-[50%] -mt-10"></div>
<p class="text-[10px] font-bold text-slate-400 mt-4">Debate</p>
</div>
<div class="flex-1 flex flex-col items-center group">
<div class="w-full max-w-[40px] bg-brand-sapphire rounded-t-lg transition-all h-[75%] group-hover:brightness-110"></div>
<div class="w-full max-w-[40px] bg-brand-gold/30 rounded-t-sm h-[65%] -mt-10"></div>
<p class="text-[10px] font-bold text-slate-400 mt-4">Music</p>
</div>
<div class="flex-1 flex flex-col items-center group">
<div class="w-full max-w-[40px] bg-brand-sapphire rounded-t-lg transition-all h-[55%] group-hover:brightness-110"></div>
<div class="w-full max-w-[40px] bg-brand-gold/30 rounded-t-sm h-[45%] -mt-10"></div>
<p class="text-[10px] font-bold text-slate-400 mt-4">Service</p>
</div>
</div>
</div>
<!-- Most Active Clubs -->
<div class="bg-white p-8 rounded-xl level-1-shadow flex flex-col">
<h3 class="font-h3 text-brand-navy mb-6">Most Active Clubs</h3>
<div class="space-y-4 flex-1 overflow-y-auto custom-scrollbar pr-2">
<div class="flex items-center justify-between p-3 rounded-lg bg-slate-50 hover:bg-blue-50 transition-colors border border-transparent hover:border-brand-sapphire/20">
<div class="flex items-center gap-3">
<div class="w-10 h-10 rounded-lg bg-brand-navy text-white flex items-center justify-center font-bold">RC</div>
<div>
<p class="font-bold text-sm text-brand-navy">Robotics Club</p>
<p class="text-[10px] text-slate-500">421 Apps • 12 Events</p>
</div>
</div>
<span class="material-symbols-outlined text-slate-300">chevron_right</span>
</div>
<div class="flex items-center justify-between p-3 rounded-lg bg-slate-50 hover:bg-blue-50 transition-colors">
<div class="flex items-center gap-3">
<div class="w-10 h-10 rounded-lg bg-brand-gold text-brand-navy flex items-center justify-center font-bold">BS</div>
<div>
<p class="font-bold text-sm text-brand-navy">Business Society</p>
<p class="text-[10px] text-slate-500">388 Apps • 8 Events</p>
</div>
</div>
<span class="material-symbols-outlined text-slate-300">chevron_right</span>
</div>
<div class="flex items-center justify-between p-3 rounded-lg bg-slate-50 hover:bg-blue-50 transition-colors">
<div class="flex items-center gap-3">
<div class="w-10 h-10 rounded-lg bg-brand-sapphire text-white flex items-center justify-center font-bold">DS</div>
<div>
<p class="font-bold text-sm text-brand-navy">Drama Society</p>
<p class="text-[10px] text-slate-500">312 Apps • 15 Events</p>
</div>
</div>
<span class="material-symbols-outlined text-slate-300">chevron_right</span>
</div>
<div class="flex items-center justify-between p-3 rounded-lg bg-slate-50 hover:bg-blue-50 transition-colors">
<div class="flex items-center gap-3">
<div class="w-10 h-10 rounded-lg bg-slate-200 text-slate-600 flex items-center justify-center font-bold">VC</div>
<div>
<p class="font-bold text-sm text-brand-navy">Venture Club</p>
<p class="text-[10px] text-slate-500">245 Apps • 5 Events</p>
</div>
</div>
<span class="material-symbols-outlined text-slate-300">chevron_right</span>
</div>
</div>
<button class="mt-6 text-sm font-bold text-brand-sapphire hover:underline text-center">View Full Directory</button>
</div>
<!-- Recruitment Success Rates -->
<div class="bg-brand-navy p-8 rounded-xl level-1-shadow text-white relative overflow-hidden">
<div class="relative z-10">
<h3 class="font-h3 mb-2">Recruitment Pipeline</h3>
<p class="text-sm text-blue-200/80 mb-8">Overall conversion funnel performance</p>
<div class="space-y-6">
<div>
<div class="flex justify-between text-sm mb-2">
<span class="font-medium text-blue-100">Application Review</span>
<span class="font-bold">85%</span>
</div>
<div class="h-2 w-full bg-white/10 rounded-full overflow-hidden">
<div class="h-full bg-brand-gold rounded-full" style="width: 85%"></div>
</div>
</div>
<div>
<div class="flex justify-between text-sm mb-2">
<span class="font-medium text-blue-100">Interview Stage</span>
<span class="font-bold">42%</span>
</div>
<div class="h-2 w-full bg-white/10 rounded-full overflow-hidden">
<div class="h-full bg-brand-gold rounded-full" style="width: 42%"></div>
</div>
</div>
<div>
<div class="flex justify-between text-sm mb-2">
<span class="font-medium text-blue-100">Final Acceptance</span>
<span class="font-bold">28%</span>
</div>
<div class="h-2 w-full bg-white/10 rounded-full overflow-hidden">
<div class="h-full bg-brand-gold rounded-full" style="width: 28%"></div>
</div>
</div>
</div>
<div class="mt-12 p-4 bg-white/5 rounded-lg border border-white/10">
<p class="text-xs text-blue-200 mb-1">Top Referral Source</p>
<p class="text-lg font-bold">University Orientation Portal</p>
<p class="text-[10px] uppercase tracking-tighter text-brand-gold font-black mt-2">62% OF TOTAL TRAFFIC</p>
</div>
</div>
<!-- Decorative Background Graphic -->
<div class="absolute -right-16 -bottom-16 w-64 h-64 bg-brand-sapphire/20 rounded-full blur-3xl"></div>
</div>
<!-- User Activity Trends (Line Chart View) -->
<div class="lg:col-span-2 bg-white p-8 rounded-xl level-1-shadow flex flex-col">
<div class="flex justify-between items-center mb-8">
<div>
<h3 class="font-h3 text-brand-navy">User Engagement Trends</h3>
<p class="text-body-sm text-slate-500">Daily active students vs platform sessions</p>
</div>
<select class="bg-slate-50 border-slate-200 text-xs font-bold rounded-lg focus:ring-brand-sapphire focus:border-brand-sapphire">
<option>Real-time View</option>
<option>Daily Average</option>
<option>Weekly Peak</option>
</select>
</div>
<div class="flex-1 relative pb-8">
<!-- Simulated Line Chart with SVG -->
<svg class="w-full h-full" preserveaspectratio="none" viewbox="0 0 1000 300">
<!-- Background Grid -->
<line stroke="#f1f5f9" stroke-width="1" x1="0" x2="1000" y1="50" y2="50"></line>
<line stroke="#f1f5f9" stroke-width="1" x1="0" x2="1000" y1="125" y2="125"></line>
<line stroke="#f1f5f9" stroke-width="1" x1="0" x2="1000" y1="200" y2="200"></line>
<line stroke="#f1f5f9" stroke-width="1" x1="0" x2="1000" y1="275" y2="275"></line>
<!-- Area Path -->
<path d="M0,300 L0,220 C150,180 250,260 400,140 C550,20 750,200 1000,80 L1000,300 Z" fill="url(#blueGradient)" opacity="0.1"></path>
<!-- Main Line -->
<path d="M0,220 C150,180 250,260 400,140 C550,20 750,200 1000,80" fill="none" stroke="#2B6CB0" stroke-linecap="round" stroke-width="4"></path>
<!-- Trend Dots -->
<circle cx="400" cy="140" fill="#2B6CB0" r="6" stroke="white" stroke-width="2"></circle>
<circle cx="1000" cy="80" fill="#2B6CB0" r="6" stroke="white" stroke-width="2"></circle>
<defs>
<lineargradient id="blueGradient" x1="0%" x2="0%" y1="0%" y2="100%">
<stop offset="0%" style="stop-color:#2B6CB0;stop-opacity:1"></stop>
<stop offset="100%" style="stop-color:#2B6CB0;stop-opacity:0"></stop>
</lineargradient>
</defs>
</svg>
<!-- X-Axis Labels -->
<div class="flex justify-between mt-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest px-2">
<span>Week 1</span>
<span>Week 2</span>
<span>Week 3</span>
<span>Week 4</span>
<span>Week 5</span>
</div>
<!-- Tooltip Overlay (Static) -->
<div class="absolute left-[38%] top-[10%] bg-white p-3 rounded-lg level-2-shadow border border-slate-100 hidden lg:block">
<p class="text-[10px] font-bold text-slate-400 mb-1">OCT 14, 2024</p>
<div class="flex items-center gap-2">
<span class="w-2 h-2 rounded-full bg-brand-sapphire"></span>
<p class="text-sm font-black text-brand-navy">1,482 Active Users</p>
</div>
</div>
</div>
</div>
</div>
<!-- Detailed Data Table Section -->
<div class="mt-gutter bg-white rounded-xl level-1-shadow overflow-hidden">
<div class="p-6 border-b border-slate-100 flex justify-between items-center">
<h3 class="font-h3 text-brand-navy">Club Performance Registry</h3>
<div class="flex gap-2">
<button class="p-2 border border-slate-200 rounded hover:bg-slate-50"><span class="material-symbols-outlined text-lg">filter_list</span></button>
<button class="p-2 border border-slate-200 rounded hover:bg-slate-50"><span class="material-symbols-outlined text-lg">sort</span></button>
</div>
</div>
<div class="overflow-x-auto">
<table class="w-full text-left">
<thead>
<tr class="bg-slate-50 text-[10px] font-bold text-slate-500 uppercase tracking-widest">
<th class="px-6 py-4">Club Name</th>
<th class="px-6 py-4">Category</th>
<th class="px-6 py-4 text-center">Applicants</th>
<th class="px-6 py-4 text-center">Growth</th>
<th class="px-6 py-4">Status</th>
<th class="px-6 py-4 text-right">Actions</th>
</tr>
</thead>
<tbody class="divide-y divide-slate-50">
<tr class="hover:bg-slate-50 transition-colors group">
<td class="px-6 py-4">
<div class="flex items-center gap-3">
<div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center font-bold text-brand-navy text-xs">AI</div>
<span class="font-semibold text-brand-navy">AI Research Lab</span>
</div>
</td>
<td class="px-6 py-4"><span class="px-2 py-1 bg-blue-50 text-brand-sapphire text-[10px] font-bold rounded">TECH</span></td>
<td class="px-6 py-4 text-center font-medium">184</td>
<td class="px-6 py-4 text-center text-emerald-500 font-bold">+22%</td>
<td class="px-6 py-4"><span class="flex items-center gap-1 text-xs text-emerald-600 font-bold"><span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span> Active Recruitment</span></td>
<td class="px-6 py-4 text-right">
<button class="text-slate-400 hover:text-brand-navy"><span class="material-symbols-outlined">more_horiz</span></button>
</td>
</tr>
<tr class="hover:bg-slate-50 transition-colors group">
<td class="px-6 py-4">
<div class="flex items-center gap-3">
<div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center font-bold text-brand-navy text-xs">CO</div>
<span class="font-semibold text-brand-navy">Chamber Orchestra</span>
</div>
</td>
<td class="px-6 py-4"><span class="px-2 py-1 bg-purple-50 text-purple-700 text-[10px] font-bold rounded">ARTS</span></td>
<td class="px-6 py-4 text-center font-medium">92</td>
<td class="px-6 py-4 text-center text-slate-400 font-bold">0%</td>
<td class="px-6 py-4"><span class="flex items-center gap-1 text-xs text-slate-500 font-bold"><span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span> Closed</span></td>
<td class="px-6 py-4 text-right">
<button class="text-slate-400 hover:text-brand-navy"><span class="material-symbols-outlined">more_horiz</span></button>
</td>
</tr>
<tr class="hover:bg-slate-50 transition-colors group">
<td class="px-6 py-4">
<div class="flex items-center gap-3">
<div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center font-bold text-brand-navy text-xs">SB</div>
<span class="font-semibold text-brand-navy">Sustainability Board</span>
</div>
</td>
<td class="px-6 py-4"><span class="px-2 py-1 bg-green-50 text-green-700 text-[10px] font-bold rounded">SOCIAL</span></td>
<td class="px-6 py-4 text-center font-medium">315</td>
<td class="px-6 py-4 text-center text-emerald-500 font-bold">+45%</td>
<td class="px-6 py-4"><span class="flex items-center gap-1 text-xs text-emerald-600 font-bold"><span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span> Active Recruitment</span></td>
<td class="px-6 py-4 text-right">
<button class="text-slate-400 hover:text-brand-navy"><span class="material-symbols-outlined">more_horiz</span></button>
</td>
</tr>
</tbody>
</table>
</div>
<div class="p-6 bg-slate-50 flex items-center justify-between">
<p class="text-xs text-slate-500">Showing 3 of 248 clubs</p>
<div class="flex gap-2">
<button class="px-4 py-2 bg-white border border-slate-200 text-xs font-bold rounded hover:bg-slate-100 transition-colors disabled:opacity-50" disabled="">Previous</button>
<button class="px-4 py-2 bg-white border border-slate-200 text-xs font-bold rounded hover:bg-slate-100 transition-colors">Next</button>
</div>
</div>
</div>
</div>
</main>
</body></html>