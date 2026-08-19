<?php
include 'db_connect.php';

// List of all 38 Districts in Tamil Nadu
$tamilnadu_districts = [
    "Ariyalur", "Chengalpattu", "Chennai", "Coimbatore", "Cuddalore", 
    "Dharmapuri", "Dindigul", "Erode", "Kallakurichi", "Kancheepuram", 
    "Kanyakumari", "Karur", "Krishnagiri", "Madurai", "Mayiladuthurai", 
    "Nagapattinam", "Namakkal", "Nilgiris", "Perambalur", "Pudukkottai", 
    "Ramanathapuram", "Ranipet", "Salem", "Sivaganga", "Tenkasi", 
    "Thanjavur", "Theni", "Thoothukudi", "Tiruchirappalli", "Tirunelveli", 
    "Tirupathur", "Tiruppur", "Tiruvallur", "Tiruvannamalai", "Tiruvarur", 
    "Vellore", "Viluppuram", "Virudhunagar"
];
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Live OPD Queue Status & Token Tracker – MedPulse TN</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="./styles.css" />
</head>
<body class="min-h-screen flex flex-col justify-between">

  <!-- ANTI-GRAVITY GLASSMORPHIC PRELOADER -->
  <div id="app-preloader">
    <div class="floating-logo flex flex-col items-center gap-3">
      <div class="w-16 h-16 rounded-2xl bg-emerald-500/20 border-2 border-emerald-500/40 flex items-center justify-center font-extrabold text-emerald-400 text-2xl shadow-2xl">
        M
      </div>
      <span class="text-xl font-extrabold text-main tracking-tight">MedPulse<span class="text-emerald-400">.TN</span></span>
      <span class="text-xs text-muted font-mono tracking-widest uppercase animate-pulse">Initializing OPD Queue Tracker...</span>
    </div>
  </div>

  <!-- TOP HEADER NAVBAR -->
  <nav class="sticky top-0 z-40 border-b border-theme bg-card px-4 md:px-8 py-3.5 shadow-sm">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
      
      <a href="index.php" class="flex items-center gap-2.5">
        <div class="w-9 h-9 rounded-xl bg-emerald-500/15 border border-emerald-500/30 flex items-center justify-center font-extrabold text-emerald-400 text-lg">M</div>
        <div>
          <span class="text-lg font-extrabold tracking-tight text-main">MedPulse<span class="text-emerald-400">.TN</span></span>
          <span class="text-[10px] bg-emerald-500/10 text-emerald-400 px-2 py-0.5 rounded-full font-bold ml-1.5 border border-emerald-500/20">LIVE OPD QUEUE</span>
        </div>
      </a>

      <div class="flex items-center gap-2.5">
        <a href="map.php" title="Emergency Map" class="w-9 h-9 icon-btn-reactive text-sm flex items-center justify-center">
          <i class="fa-solid fa-map-location-dot"></i>
        </a>

        <button onclick="toggleTheme()" title="Toggle Light/Dark Theme" class="w-9 h-9 icon-btn-reactive text-sm flex items-center justify-center">
          <span class="theme-icon-symbol"><i class="fa-solid fa-moon"></i></span>
        </button>

        <button onclick="openSidebar()" title="Open Menu Drawer" class="w-9 h-9 icon-btn-reactive text-sm">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
        </button>
      </div>
    </div>
  </nav>

  <!-- RIGHT SLIDING SIDEBAR DRAWER (☰) -->
  <div id="sidebar-overlay" onclick="closeSidebar()" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[9990] hidden transition-opacity duration-300"></div>

  <aside id="sidebar" class="fixed top-0 right-0 h-full w-80 max-w-[85vw] bg-card border-l border-theme z-[9999] transform translate-x-full transition-transform duration-300 p-6 flex flex-col justify-between shadow-2xl overflow-y-auto">
    <div>
      <div class="flex items-center justify-between border-b border-theme pb-4 mb-6">
        <h3 class="font-extrabold text-base text-main tracking-tight">MedPulse TN Menu</h3>
        <button onclick="closeSidebar()" class="p-1 rounded-lg text-muted hover:text-main">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>

      <div class="space-y-6">
        <div>
          <p class="text-[11px] font-extrabold uppercase text-emerald-400 tracking-wider mb-2">🌐 Navigation</p>
          <div class="space-y-1 text-sm font-semibold">
            <a href="index.php" class="drawer-item">Home Dashboard</a>
            <a href="queue.php" class="drawer-item drawer-item-active">Live OPD Queue Status</a>
            <a href="telemedicine.php" class="drawer-item">Telemedicine Portal</a>
            <a href="hpr.php" class="drawer-item">About HPR Verification</a>
            <a href="doctor_register.php" class="drawer-item">👨‍⚕️ Doctor Onboarding Portal</a>
            <a href="login.php" class="drawer-item">Admin & Staff Portal</a>
            <a href="hospitals.php" class="drawer-item">Hospital Directory</a>
            <a href="blood_bank.php" class="drawer-item">Blood Bank Stocks</a>
            <a href="map.php" class="drawer-item">Live Interactive Map</a>
          </div>
        </div>
      </div>
    </div>
  </aside>

  <!-- MAIN CONTENT CONTAINER -->
  <main class="max-w-7xl mx-auto px-4 md:px-8 py-8 w-full flex-grow">

    <!-- HERO BANNER -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
      <div>
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold mb-3">
          <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
          Statewide OPD Token & Cabin Monitoring System
        </div>
        <h1 class="text-2xl md:text-3xl font-extrabold text-main tracking-tight">Live OPD Queue Status & Token Tracker</h1>
        <p class="text-xs md:text-sm text-muted mt-1.5 max-w-2xl">
          Real-time outpatient cabin token telemetry covering all 38 Tamil Nadu districts. Track live serving tokens, monitor doctor cabin presence, and issue digital OP consultation tickets.
        </p>
      </div>

      <button onclick="openTokenModal()" class="bg-emerald-500 hover:bg-emerald-600 text-white font-extrabold text-xs md:text-sm px-5 py-3 rounded-2xl shadow-xl shadow-emerald-500/20 transition-all flex items-center gap-2.5 whitespace-nowrap self-start md:self-auto">
        <i class="fa-solid fa-ticket text-base"></i>
        <span>Issue Digital OP Token</span>
      </button>
    </div>

    <!-- METRICS DASHBOARD GRID -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
      <!-- CARD 1: NOW SERVING TOKEN -->
      <div class="bg-card border border-theme rounded-2xl p-5 shadow-lg anti-gravity-card flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-emerald-500/15 border border-emerald-500/30 flex items-center justify-center text-emerald-400 text-xl font-bold">
          <i class="fa-solid fa-user-check"></i>
        </div>
        <div>
          <span class="block text-[10px] font-extrabold uppercase text-muted tracking-wider">Now Serving</span>
          <div class="text-2xl font-extrabold text-main mt-0.5 flex items-center gap-2">
            <span>Token #18</span>
            <span class="text-[10px] bg-emerald-500/20 text-emerald-400 px-2 py-0.5 rounded-full font-bold border border-emerald-500/30 animate-pulse">LIVE</span>
          </div>
        </div>
      </div>

      <!-- CARD 2: TOTAL ISSUED TOKENS -->
      <div class="bg-card border border-theme rounded-2xl p-5 shadow-lg anti-gravity-card flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-blue-500/15 border border-blue-500/30 flex items-center justify-center text-blue-400 text-xl font-bold">
          <i class="fa-solid fa-receipt"></i>
        </div>
        <div>
          <span class="block text-[10px] font-extrabold uppercase text-muted tracking-wider">Total Issued Tokens</span>
          <div class="text-2xl font-extrabold text-main mt-0.5">
            <span>#42</span>
            <span class="text-xs text-muted font-normal ml-1.5">Today</span>
          </div>
        </div>
      </div>

      <!-- CARD 3: ESTIMATED WAIT TIME -->
      <div class="bg-card border border-theme rounded-2xl p-5 shadow-lg anti-gravity-card flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-amber-500/15 border border-amber-500/30 flex items-center justify-center text-amber-400 text-xl font-bold">
          <i class="fa-solid fa-hourglass-half"></i>
        </div>
        <div>
          <span class="block text-[10px] font-extrabold uppercase text-muted tracking-wider">Est. Wait Time</span>
          <div class="text-2xl font-extrabold text-main mt-0.5">
            <span>10–12 mins</span>
            <span class="text-xs text-muted font-normal ml-1">/ patient</span>
          </div>
        </div>
      </div>
    </div>

    <!-- FILTER BAR -->
    <div class="bg-card border border-theme rounded-2xl p-4 mb-8 shadow-md flex flex-col sm:flex-row items-center gap-4 justify-between">
      <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
        <!-- DISTRICT FILTER -->
        <div class="w-full sm:w-60">
          <label class="block text-[10px] font-extrabold uppercase text-muted tracking-wider mb-1">Select District</label>
          <select id="queue-district-filter" onchange="filterQueueGrid()" class="w-full bg-input border border-theme rounded-xl px-3.5 py-2.5 text-xs text-main focus:outline-none focus:border-emerald-500">
            <option value="">All 38 Districts</option>
            <?php foreach ($tamilnadu_districts as $d): ?>
              <option value="<?= htmlspecialchars($d) ?>"><?= htmlspecialchars($d) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- DEPARTMENT FILTER -->
        <div class="w-full sm:w-60">
          <label class="block text-[10px] font-extrabold uppercase text-muted tracking-wider mb-1">Department Wing</label>
          <select id="queue-dept-filter" onchange="filterQueueGrid()" class="w-full bg-input border border-theme rounded-xl px-3.5 py-2.5 text-xs text-main focus:outline-none focus:border-emerald-500">
            <option value="">All Specialization Wings</option>
            <option value="General Medicine">General Medicine</option>
            <option value="Cardiology">Cardiology</option>
            <option value="Orthopedics">Orthopedics</option>
            <option value="Neurology">Neurology</option>
            <option value="Pediatrics">Pediatrics</option>
            <option value="Trauma & Emergency">Trauma & Emergency</option>
          </select>
        </div>
      </div>

      <div class="text-xs text-muted font-bold text-center sm:text-right w-full sm:w-auto" id="queue-count-badge">
        Showing OPD Cabin Queue Cards
      </div>
    </div>

    <!-- ACTIVE QUEUE CARDS GRID -->
    <div id="queue-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
      <!-- Rendered dynamically by app.js -->
    </div>
  </main>

  <!-- ISSUE OP TOKEN MODAL -->
  <div id="token-modal" class="fixed inset-0 bg-black/75 backdrop-blur-sm z-[10000] hidden flex items-center justify-center p-4">
    <div class="bg-card border border-theme rounded-3xl p-6 max-w-lg w-full shadow-2xl relative max-h-[90vh] overflow-y-auto">
      <button onclick="closeTokenModal()" class="absolute top-5 right-5 text-muted hover:text-main text-xl font-bold leading-none p-1">✕</button>
      
      <div class="flex items-center gap-3 border-b border-theme pb-4 mb-5">
        <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold text-lg">
          <i class="fa-solid fa-ticket"></i>
        </div>
        <div>
          <h3 class="text-lg font-extrabold text-main">Issue Digital OP Token Ticket</h3>
          <p class="text-xs text-muted">Generate instant outpatient cabin ticket with QR Code</p>
        </div>
      </div>

      <form onsubmit="generateOPToken(event)" class="space-y-4 text-xs">
        <div>
          <label class="block font-bold text-muted uppercase mb-1">Patient Full Name *</label>
          <input type="text" id="token-patient-name" required class="w-full bg-input border border-theme rounded-xl px-3.5 py-2.5 text-main focus:outline-none focus:border-emerald-500" placeholder="e.g. R. Koushik">
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block font-bold text-muted uppercase mb-1">Mobile Number *</label>
            <input type="tel" id="token-patient-phone" required pattern="[0-9]{10}" class="w-full bg-input border border-theme rounded-xl px-3.5 py-2.5 text-main focus:outline-none focus:border-emerald-500" placeholder="10-digit mobile">
          </div>
          <div>
            <label class="block font-bold text-muted uppercase mb-1">Preferred Time Slot *</label>
            <select id="token-slot" required class="w-full bg-input border border-theme rounded-xl px-3 py-2.5 text-main focus:outline-none focus:border-emerald-500">
              <option value="Morning (09:00 AM - 11:30 AM)">Morning (09:00 AM - 11:30 AM)</option>
              <option value="Mid-Day (11:30 AM - 01:30 PM)">Mid-Day (11:30 AM - 01:30 PM)</option>
              <option value="Afternoon (02:00 PM - 04:30 PM)">Afternoon (02:00 PM - 04:30 PM)</option>
            </select>
          </div>
        </div>

        <div>
          <label class="block font-bold text-muted uppercase mb-1">Select Doctor / OPD Wing *</label>
          <select id="token-doctor-select" required class="w-full bg-input border border-theme rounded-xl px-3.5 py-2.5 text-main focus:outline-none focus:border-emerald-500">
            <!-- Populated dynamically -->
          </select>
        </div>

        <div>
          <label class="block font-bold text-muted uppercase mb-1">Brief Symptoms / Notes</label>
          <input type="text" id="token-notes" class="w-full bg-input border border-theme rounded-xl px-3.5 py-2.5 text-main focus:outline-none focus:border-emerald-500" placeholder="Routine checkup, fever, joint pain...">
        </div>

        <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-extrabold text-sm py-3 rounded-xl shadow-lg transition-all mt-2 flex items-center justify-center gap-2">
          <i class="fa-solid fa-qrcode"></i>
          <span>Generate Printable OP Ticket</span>
        </button>
      </form>

      <!-- GENERATED TICKET RESULT -->
      <div id="generated-ticket-wrapper" class="hidden mt-6 pt-5 border-t border-theme">
        <div class="bg-input/60 border border-emerald-500/40 rounded-2xl p-4 text-center relative shadow-xl">
          <div class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full bg-emerald-500/20 text-emerald-400 text-[10px] font-bold mb-2">
            ✓ DIGITAL OP TICKET CONFIRMED
          </div>
          <h4 id="ticket-doc-name" class="font-extrabold text-main text-sm">Dr. Name</h4>
          <p id="ticket-hosp-name" class="text-xs text-muted mb-3">Hospital Name</p>

          <div class="bg-card border border-theme rounded-xl p-3 my-3 flex items-center justify-between">
            <div class="text-left">
              <span class="block text-[10px] text-muted font-bold">PATIENT TOKEN</span>
              <span id="ticket-number" class="text-2xl font-black text-emerald-400">#33</span>
            </div>
            <div class="text-right">
              <span class="block text-[10px] text-muted font-bold">ESTIMATED TURN</span>
              <span id="ticket-time-estimate" class="text-xs font-bold text-main">~25 mins</span>
            </div>
          </div>

          <!-- MOCK QR CODE -->
          <div class="flex flex-col items-center justify-center gap-2 my-3">
            <div class="w-24 h-24 bg-white p-2 rounded-xl border border-gray-300 shadow-md flex items-center justify-center">
              <i class="fa-solid fa-qrcode text-6xl text-slate-800"></i>
            </div>
            <span class="text-[10px] text-muted font-mono" id="ticket-qr-code">TOKEN-TN-89412-2026</span>
          </div>

          <button onclick="window.print()" class="w-full bg-card hover:bg-input border border-theme text-main font-bold text-xs py-2 rounded-xl transition-all flex items-center justify-center gap-2">
            <i class="fa-solid fa-print"></i> Print / Save PDF
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- FOOTER -->
  <footer class="border-t border-theme py-6 px-4 md:px-8 bg-card text-center text-xs text-muted">
    <p>MedPulse TN — Statewide OPD Queue Telemetry & Token Infrastructure</p>
  </footer>

  <script src="./app.js"></script>
</body>
</html>
