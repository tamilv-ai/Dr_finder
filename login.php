<?php
include 'db_connect.php';

// List of all 38 Districts in Tamil Nadu with RTO Code PINs
$district_pins = [
    "Ariyalur" => "TN61", "Chengalpattu" => "TN19", "Chennai" => "TN01", 
    "Coimbatore" => "TN37", "Cuddalore" => "TN31", "Dharmapuri" => "TN29", 
    "Dindigul" => "TN57", "Erode" => "TN33", "Kallakurichi" => "TN15", 
    "Kancheepuram" => "TN21", "Kanyakumari" => "TN74", "Karur" => "TN47", 
    "Krishnagiri" => "TN24", "Madurai" => "TN58", "Mayiladuthurai" => "TN82", 
    "Nagapattinam" => "TN51", "Namakkal" => "TN28", "Nilgiris" => "TN43", 
    "Perambalur" => "TN46", "Pudukkottai" => "TN55", "Ramanathapuram" => "TN65", 
    "Ranipet" => "TN73", "Salem" => "TN27", "Sivaganga" => "TN63", 
    "Tenkasi" => "TN79", "Thanjavur" => "TN49", "Theni" => "TN60", 
    "Thoothukudi" => "TN69", "Tiruchirappalli" => "TN45", "Tirunelveli" => "TN72", 
    "Tirupathur" => "TN83", "Tiruppur" => "TN39", "Tiruvallur" => "TN20", 
    "Tiruvannamalai" => "TN25", "Tiruvarur" => "TN50", "Vellore" => "TN23", 
    "Viluppuram" => "TN32", "Virudhunagar" => "TN67"
];
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>District Staff Authentication Gateway – MedPulse TN</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="styles.css" />
</head>
<body class="min-h-screen flex flex-col justify-between">

  <!-- ANTI-GRAVITY GLASSMORPHIC PRELOADER -->
  <div id="app-preloader">
    <div class="floating-logo flex flex-col items-center gap-3 text-center">
      <div class="w-16 h-16 rounded-2xl bg-emerald-500/20 border-2 border-emerald-500/40 flex items-center justify-center text-emerald-400 shadow-2xl text-2xl font-bold">
        <i class="fa-solid fa-user-shield"></i>
      </div>
      <span class="text-xl font-extrabold text-main tracking-tight">MedPulse<span class="text-emerald-400">.Auth</span></span>
      <span class="text-xs text-muted font-mono tracking-widest uppercase animate-pulse">Initializing Staff Authentication...</span>
    </div>
  </div>

  <!-- TOP HEADER NAVBAR -->
  <nav class="sticky top-0 z-40 border-b border-theme bg-card px-4 md:px-8 py-3.5 shadow-sm">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
      
      <a href="index.php" class="flex items-center gap-2.5">
        <div class="w-9 h-9 rounded-xl bg-emerald-500/15 border border-emerald-500/30 flex items-center justify-center font-extrabold text-emerald-400 text-lg">
          <i class="fa-solid fa-key"></i>
        </div>
        <div>
          <span class="text-lg font-extrabold tracking-tight text-main">MedPulse<span class="text-emerald-400">.Auth</span></span>
          <span class="text-[10px] bg-emerald-500/10 text-emerald-400 px-2 py-0.5 rounded-full font-bold ml-1.5 border border-emerald-500/20">STAFF DESK GATEWAY</span>
        </div>
      </a>

      <div class="flex items-center gap-2.5">
        <a href="index.php" title="Return to Index" class="hidden sm:flex items-center gap-1.5 text-xs text-muted hover:text-emerald-400 font-semibold px-3 py-2 rounded-xl border border-theme bg-input transition-all">
          ← Back to Public Portal
        </a>

        <button onclick="toggleTheme()" title="Toggle Light/Dark Theme" class="w-9 h-9 icon-btn-reactive text-sm flex items-center justify-center">
          <span class="theme-icon-symbol"><i class="fa-solid fa-moon"></i></span>
        </button>

        <button onclick="openSidebar()" title="Open Menu Drawer" class="w-9 h-9 icon-btn-reactive text-sm flex items-center justify-center">
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
            <a href="queue.php" class="drawer-item">Live OPD Queue Status</a>
            <a href="telemedicine.php" class="drawer-item">Telemedicine Portal</a>
            <a href="hpr.php" class="drawer-item">About HPR Verification</a>
            <a href="doctor_register.php" class="drawer-item">👨‍⚕️ Doctor Onboarding Portal</a>
            <a href="login.php" class="drawer-item drawer-item-active">Admin & Staff Portal</a>
            <a href="hospitals.php" class="drawer-item">Hospital Directory</a>
            <a href="blood_bank.php" class="drawer-item">Blood Bank Stocks</a>
            <a href="map.php" class="drawer-item">Live Interactive Map</a>
          </div>
        </div>
      </div>
    </div>
  </aside>

  <!-- LOGIN MAIN CONTAINER -->
  <main class="flex-grow flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
      
      <!-- AUTHENTICATION CARD FORM -->
      <div class="bg-card border border-theme rounded-3xl p-8 shadow-2xl relative overflow-hidden anti-gravity-card">
        
        <div class="text-center mb-8">
          <div class="w-14 h-14 rounded-2xl bg-emerald-500/15 border border-emerald-500/30 flex items-center justify-center text-emerald-400 text-2xl mx-auto mb-3 shadow-lg">
            <i class="fa-solid fa-user-gear"></i>
          </div>
          <h1 class="text-2xl font-extrabold text-main">District Staff Authentication</h1>
          <p class="text-xs text-muted mt-1.5">Select your assigned district & enter staff PIN to access your regional control desk</p>
        </div>

        <!-- TOAST ALERTS CONTAINER -->
        <div id="auth-toast" class="hidden mb-5 p-3.5 rounded-2xl text-xs font-bold text-center border transition-all"></div>

        <form onsubmit="handleAdminLogin(event)" class="space-y-5">
          <!-- DISTRICT SELECT DROPDOWN (ALL 38 DISTRICTS) -->
          <div>
            <label class="block text-xs font-extrabold text-muted uppercase tracking-wider mb-2">Assigned Tamil Nadu District *</label>
            <select 
              id="admin-login-district" 
              required 
              class="w-full bg-input border border-theme rounded-xl px-4 py-3 text-sm text-main focus:outline-none focus:border-emerald-500 transition-all font-semibold"
            >
              <option value="">-- Select Your 38 District --</option>
              <?php foreach ($district_pins as $d => $pin): ?>
                <option value="<?= htmlspecialchars($d) ?>"><?= htmlspecialchars($d) ?> (<?= htmlspecialchars($pin) ?>)</option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- DISTRICT STAFF PIN INPUT -->
          <div>
            <div class="flex items-center justify-between mb-2">
              <label class="text-xs font-extrabold text-muted uppercase tracking-wider">District Staff PIN *</label>
              <span class="text-[10px] text-emerald-400 font-mono">e.g., TN23 or ADMIN123</span>
            </div>
            <input 
              type="password" 
              id="admin-login-pin" 
              required 
              placeholder="Enter District PIN (e.g. TN23)"
              class="w-full bg-input border border-theme rounded-xl px-4 py-3 text-sm text-main font-mono focus:outline-none focus:border-emerald-500 transition-all tracking-wider"
            />
          </div>

          <!-- HINT CARD -->
          <div class="bg-input/60 border border-theme rounded-xl p-3 text-[11px] text-muted space-y-1">
            <div class="flex items-center justify-between font-bold text-main">
              <span>💡 Staff Authentication Hint:</span>
              <span class="text-emerald-400">ABDM Sync</span>
            </div>
            <p>Each district uses its RTO code PIN (e.g. <b>Vellore</b> = <code>TN23</code>, <b>Chennai</b> = <code>TN01</code>, <b>Coimbatore</b> = <code>TN37</code>, <b>Salem</b> = <code>TN27</code>). Master Admin PIN: <code>ADMIN123</code>.</p>
          </div>

          <button 
            type="submit" 
            class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-extrabold py-3.5 rounded-xl shadow-lg transition-all text-sm tracking-wide mt-2 flex items-center justify-center gap-2">
            <i class="fa-solid fa-lock-open"></i>
            <span>Authenticate & Launch Desk</span>
          </button>
        </form>

        <!-- RETURN LINK TO INDEX -->
        <div class="mt-6 pt-5 border-t border-theme text-center">
          <p class="text-xs text-muted">
            Looking for public OPD status? 
            <a href="index.php" class="text-emerald-400 font-bold hover:underline ml-1">Return to Public Portal Index →</a>
          </p>
        </div>

      </div>

      <!-- FOOTER NOTE -->
      <p class="text-[11px] text-muted text-center mt-6">
        Tamil Nadu e-Health Development Cell · DPDPA 2023 Compliant
      </p>

    </div>
  </main>

  <!-- FOOTER -->
  <footer class="border-t border-theme py-6 px-4 bg-card text-center text-xs text-muted">
    <p>MedPulse TN — District Staff Authentication Gateway</p>
  </footer>

  <script src="app.js"></script>
</body>
</html>
