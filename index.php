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
  <title>MedPulse TN – Healthcare Dashboard & Outpatient Infrastructure</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="./styles.css" />
</head>
<body class="min-h-screen flex flex-col justify-between">

  <!-- ANTI-GRAVITY GLASSMORPHIC PRELOADER -->
  <div id="app-preloader">
    <div class="floating-logo flex flex-col items-center gap-3 text-center">
      <div class="w-16 h-16 rounded-2xl bg-emerald-500/20 border-2 border-emerald-500/40 flex items-center justify-center font-extrabold text-emerald-400 text-2xl shadow-2xl">
        M
      </div>
      <span class="text-xl font-extrabold text-main tracking-tight">MedPulse<span class="text-emerald-400">.TN</span></span>
      <span class="text-xs text-muted font-mono tracking-widest uppercase animate-pulse">Initializing Live Infrastructure...</span>
    </div>
  </div>

  <!-- TOP HEADER NAVBAR -->
  <nav class="sticky top-0 z-40 border-b border-theme bg-card px-4 md:px-8 py-3.5 shadow-sm">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
      
      <a href="index.php" class="flex items-center gap-2.5">
        <div class="w-9 h-9 rounded-xl bg-emerald-500/15 border border-emerald-500/30 flex items-center justify-center font-extrabold text-emerald-400 text-lg">M</div>
        <div>
          <span class="text-lg font-extrabold tracking-tight text-main">MedPulse<span class="text-emerald-400">.TN</span></span>
          <span class="text-[10px] bg-emerald-500/10 text-emerald-400 px-2 py-0.5 rounded-full font-bold ml-1.5 border border-emerald-500/20">STATEWIDE PORTAL</span>
        </div>
      </a>

      <div class="flex items-center gap-2.5">
        <a href="hospitals.php" class="hidden md:inline-block text-xs font-semibold text-muted hover:text-emerald-400 px-3 py-1.5">Hospitals</a>
        <a href="blood_bank.php" class="hidden md:inline-block text-xs font-semibold text-muted hover:text-emerald-400 px-3 py-1.5">Blood Bank</a>
        <a href="doctors.html" class="hidden md:inline-block text-xs font-semibold text-muted hover:text-emerald-400 px-3 py-1.5">Doctors</a>
        <a href="admin.php" class="hidden lg:inline-block text-xs font-bold text-emerald-400 bg-emerald-500/10 border border-emerald-500/30 px-3 py-1.5 rounded-xl hover:bg-emerald-500/20">⚙️ Admin Portal</a>
        <a href="map.php" title="Live Emergency GIS Map" class="w-9 h-9 icon-btn-reactive text-sm flex items-center justify-center">
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
        <!-- DISCOVER -->
        <div>
          <p class="text-[11px] font-extrabold uppercase text-emerald-400 tracking-wider mb-2">🌐 Discover</p>
          <div class="space-y-1 text-sm font-semibold">
            <a href="index.php" onclick="closeSidebar()" class="drawer-item">Home</a>
            <a href="hospitals.php" class="drawer-item">Hospitals Directory</a>
            <a href="doctors.html" class="drawer-item">Doctor Directory</a>
            <a href="blood_bank.php" class="drawer-item">
              <span>Blood Bank Stock</span>
              <span class="text-[10px] bg-red-500/10 text-red-400 border border-red-500/20 px-2 py-0.5 rounded-full font-bold">UNITS</span>
            </a>
            <a href="map.php" class="drawer-item">
              <span>Live Map 🟢</span>
              <span class="text-[10px] bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 px-2 py-0.5 rounded-full font-bold">24/7</span>
            </a>
          </div>
        </div>

        <!-- PATIENT SERVICES -->
        <div>
          <p class="text-[11px] font-extrabold uppercase text-emerald-400 tracking-wider mb-2">⚡ Patient Services</p>
          <div class="space-y-1 text-sm font-semibold">
            <button onclick="triggerService('appointment')" class="drawer-item">Book Appointment</button>
            <a href="queue.php" class="drawer-item">Live OPD Queue Status</a>
            <a href="telemedicine.php" class="drawer-item">Telemedicine Portal</a>
          </div>
        </div>

        <!-- PRACTITIONER HUB -->
        <div>
          <p class="text-[11px] font-extrabold uppercase text-emerald-400 tracking-wider mb-2">🩺 Practitioner Hub</p>
          <div class="space-y-1 text-sm font-semibold">
            <a href="doctor_register.php" class="drawer-item">👨‍⚕️ Doctor Onboarding Portal</a>
            <a href="login.php" class="drawer-item bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 font-bold mb-1">
              <span>⚙️ Admin & Staff Portal</span>
            </a>
            <a href="hpr.php" class="drawer-item mt-1">About HPR Verification</a>
          </div>
        </div>

        <!-- PLATFORM -->
        <div>
          <p class="text-[11px] font-extrabold uppercase text-emerald-400 tracking-wider mb-2">⚙️ Platform</p>
          <div class="space-y-1 text-sm font-semibold">
            <button onclick="triggerService('emergency')" class="drawer-item danger-item text-red-400 font-bold">Emergency Help (24/7)</button>
            <button onclick="triggerService('support')" class="drawer-item">Contact Support</button>
          </div>
        </div>
      </div>
    </div>

    <div class="border-t border-theme pt-4 mt-6 text-center text-xs text-muted">
      <p>MedPulse TN · Public Health Platform</p>
    </div>
  </aside>

  <!-- HERO & GEOLOCATION SECTION -->
  <section class="px-4 md:px-8 pt-10 pb-8 text-center max-w-5xl mx-auto">
    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold mb-4">
      <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
      Coverage Across All 38 Tamil Nadu Districts
    </div>
    
    <h1 class="text-3xl md:text-5xl font-extrabold text-main tracking-tight mb-4">
      Find Healthcare Near You <span class="text-emerald-400">Instantly</span>
    </h1>
    
    <p class="text-muted text-sm md:text-base max-w-2xl mx-auto mb-8">
      Synchronizing rural transit demand with verified hospital attendance, emergency trauma wards, and real-time blood bank inventories.
    </p>

    <!-- GEOLOCATION BANNER CARD -->
    <div class="bg-card border border-theme p-6 rounded-3xl shadow-xl mb-8 text-left anti-gravity-card">
      <div class="flex flex-col md:flex-row items-center justify-between gap-4">
        <div>
          <h3 class="text-xl font-bold text-main flex items-center gap-2">
            <i class="fa-solid fa-location-crosshairs text-emerald-400"></i>
            Live Geolocation Distance Engine
          </h3>
          <p class="text-xs text-muted mt-1">Allow browser location permissions to calculate instant driving distance to nearest medical centers.</p>
        </div>
        <button onclick="fetchUserLocation()" id="btn-detect-gps" class="bg-emerald-500 hover:brightness-110 text-slate-950 font-bold px-5 py-3 rounded-2xl text-xs transition-all flex items-center gap-2 shadow-lg whitespace-nowrap">
          <i class="fa-solid fa-crosshairs"></i> Detect My Location
        </button>
      </div>
    </div>

    <!-- UNIVERSAL SEARCH BAR FORM -->
    <form action="hospitals.php" method="GET" class="bg-card border border-theme p-3 rounded-2xl shadow-2xl text-left anti-gravity-card">
      <div class="grid grid-cols-1 md:grid-cols-12 gap-2 items-center">
        <div class="md:col-span-6 flex items-center gap-2 bg-input border border-theme px-3 py-2.5 rounded-xl">
          <i class="fa-solid fa-magnifying-glass text-emerald-400 ml-1"></i>
          <input type="text" name="search" placeholder="Search hospital name, specialty (Cardiology, Trauma)..." class="w-full bg-transparent text-main text-xs focus:outline-none placeholder:text-slate-500">
        </div>

        <div class="md:col-span-4 flex items-center gap-2 bg-input border border-theme px-3 py-2.5 rounded-xl">
          <i class="fa-solid fa-location-dot text-red-400 ml-1"></i>
          <select name="district" class="w-full bg-transparent text-main text-xs focus:outline-none">
            <option value="" class="bg-card">Select Tamil Nadu District...</option>
            <?php foreach ($tamilnadu_districts as $dist): ?>
              <option value="<?= htmlspecialchars($dist) ?>" class="bg-card"><?= htmlspecialchars($dist) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="md:col-span-2">
          <button type="submit" class="w-full bg-emerald-500 hover:brightness-110 text-slate-950 font-bold py-3 px-4 rounded-xl text-xs transition-all shadow-md">
            Search
          </button>
        </div>
      </div>
    </form>
  </section>

  <!-- QUICK LINKS GRID SECTION -->
  <section class="px-4 md:px-8 py-8 max-w-7xl mx-auto w-full">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      
      <!-- Hospitals Card -->
      <div class="bg-card border border-theme p-6 rounded-3xl shadow-xl flex flex-col justify-between anti-gravity-card">
        <div>
          <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 text-xl font-bold mb-4">
            <i class="fa-solid fa-hospital"></i>
          </div>
          <h3 class="font-extrabold text-main text-lg mb-2">Hospitals Directory</h3>
          <p class="text-xs text-muted leading-relaxed">
            Explore emergency wards, specialized ICUs, and OPD presence across all 38 districts of Tamil Nadu.
          </p>
        </div>
        <a href="hospitals.php" class="mt-6 inline-flex items-center justify-center gap-2 bg-input border border-theme hover:border-emerald-500/40 text-main font-bold py-2.5 px-4 rounded-xl text-xs transition-all">
          Browse Hospitals →
        </a>
      </div>

      <!-- Blood Bank Card -->
      <div class="bg-card border border-theme p-6 rounded-3xl shadow-xl flex flex-col justify-between anti-gravity-card">
        <div>
          <div class="w-12 h-12 rounded-2xl bg-red-500/10 border border-red-500/20 flex items-center justify-center text-red-500 text-xl font-bold mb-4">
            <i class="fa-solid fa-droplet"></i>
          </div>
          <h3 class="font-extrabold text-main text-lg mb-2">Blood Bank Stocks</h3>
          <p class="text-xs text-muted leading-relaxed">
            Check real-time blood group unit reserves (A+, O+, B+, AB-) with direct emergency hotline dialing.
          </p>
        </div>
        <a href="blood_bank.php" class="mt-6 inline-flex items-center justify-center gap-2 bg-input border border-theme hover:border-red-500/40 text-main font-bold py-2.5 px-4 rounded-xl text-xs transition-all">
          Check Blood Bank →
        </a>
      </div>

      <!-- Live Map Card -->
      <div class="bg-card border border-theme p-6 rounded-3xl shadow-xl flex flex-col justify-between anti-gravity-card">
        <div>
          <div class="w-12 h-12 rounded-2xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-400 text-xl font-bold mb-4">
            <i class="fa-solid fa-map-location-dot"></i>
          </div>
          <h3 class="font-extrabold text-main text-lg mb-2">Live Map & GPS FAB</h3>
          <p class="text-xs text-muted leading-relaxed">
            Interactive OpenStreetMap vector map featuring floating location action button and turn-by-turn routing.
          </p>
        </div>
        <a href="map.php" class="mt-6 inline-flex items-center justify-center gap-2 bg-input border border-theme hover:border-blue-500/40 text-main font-bold py-2.5 px-4 rounded-xl text-xs transition-all">
          Open Live Map →
        </a>
      </div>

    </div>
  </section>

  <!-- THEORY & SYSTEM DETAILS SECTION -->
  <section class="px-4 md:px-8 py-12 max-w-7xl mx-auto w-full space-y-12">
    
    <div class="bg-card border border-theme p-8 rounded-3xl shadow-xl anti-gravity-card">
      <h2 class="text-2xl font-extrabold text-main mb-4">1. Public Health Challenge: Asymmetric Travel Risk</h2>
      <p class="text-muted text-sm leading-relaxed mb-4">
        Approximately 70% of healthcare utilization in India operates at the primary care level, yet specialized clinical expertise remains concentrated in tertiary urban centers such as Rajiv Gandhi Government General Hospital (RGGGH) in Chennai, Salem Government Medical College, and Madurai Medical College Hospital.
      </p>
      <p class="text-muted text-sm leading-relaxed">
        Rural families residing along major highway corridors frequently travel "blind" without verified information regarding specialist availability. MedPulse TN synchronizes local demand with physical presence tracking.
      </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="bg-card border border-theme p-8 rounded-3xl shadow-xl anti-gravity-card">
        <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 font-bold mb-4">🛡️</div>
        <h3 class="text-xl font-bold text-main mb-3">Nurse-Proxy Workflow & DPDPA 2023</h3>
        <p class="text-muted text-xs leading-relaxed mb-3">
          Direct biometric scanning on clinicians created workflow friction and introduced compliance risks under India's Digital Personal Data Protection Act (DPDPA), 2023.
        </p>
        <p class="text-muted text-xs leading-relaxed">
          MedPulse TN shifts data entry entirely to an authorized Nurse-Proxy desk. Reception nurses update presence states (`IN CABIN`, `EMERGENCY / OT`, `OFF DUTY`) via secure role-based controls without processing sensitive personal biometric markers.
        </p>
      </div>

      <div class="bg-card border border-theme p-8 rounded-3xl shadow-xl anti-gravity-card">
        <div class="w-10 h-10 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-400 font-bold mb-4">🤝</div>
        <h3 class="text-xl font-bold text-main mb-3">State Ecosystem Alignment</h3>
        <p class="text-muted text-xs leading-relaxed mb-3">
          MedPulse TN integrates into Tamil Nadu's digital health infrastructure:
        </p>
        <ul class="text-muted text-xs space-y-2 list-disc pl-4">
          <li><strong>Nalam TN:</strong> Capital mobilization for hospital infrastructure funding.</li>
          <li><strong>Nalam AI:</strong> Conversational WhatsApp pre-registration and OP ticket generation.</li>
          <li><strong>MedPulse TN:</strong> Physical desk verification layer preventing unnecessary transit.</li>
        </ul>
      </div>
    </div>

  </section>

  <!-- FOOTER -->
  <footer class="border-t border-theme py-6 px-4 md:px-8 bg-card text-center text-xs text-muted">
    <p>MedPulse TN — Statewide Healthcare Portal · 38 Tamil Nadu Districts Coverage</p>
  </footer>

  <script src="./app.js"></script>
  <script>
    function fetchUserLocation() {
      const btn = document.getElementById('btn-detect-gps');
      if (btn) btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Locating GPS...';

      if (navigator.geolocation) {
        const geoOptions = {
          enableHighAccuracy: true,
          timeout: 10000,
          maximumAge: 0
        };

        navigator.geolocation.getCurrentPosition(position => {
          let lat = position.coords.latitude;
          let lon = position.coords.longitude;
          window.location.href = `hospitals.php?lat=${lat}&lon=${lon}&near=true`;
        }, error => {
          let errMsg = 'Unable to retrieve location coordinates. Please grant GPS permissions in your browser.';
          if (error.code === error.PERMISSION_DENIED) {
            errMsg = 'Location permission was denied. Please allow location access in your browser settings.';
          }
          alert(errMsg);
          if (btn) btn.innerHTML = '<i class="fa-solid fa-crosshairs"></i> Detect My Location';
        }, geoOptions);
      } else {
        alert("Geolocation is not supported by your browser.");
        if (btn) btn.innerHTML = '<i class="fa-solid fa-crosshairs"></i> Detect My Location';
      }
    }
  </script>
</body>
</html>
