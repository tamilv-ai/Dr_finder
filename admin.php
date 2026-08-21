<?php
include 'db_connect.php';

$hospitals_list = [];
$doctors_list = [];
$blood_list = [];

if ($db_connected) {
    $resH = $conn->query("SELECT * FROM hospitals ORDER BY district ASC, hospital_name ASC");
    if ($resH) { while ($row = $resH->fetch_assoc()) $hospitals_list[] = $row; }

    $resD = $conn->query("SELECT * FROM doctors ORDER BY district ASC, doctor_name ASC");
    if ($resD) { while ($row = $resD->fetch_assoc()) $doctors_list[] = $row; }

    $resB = $conn->query("SELECT * FROM blood_bank ORDER BY district ASC, blood_bank_name ASC");
    if ($resB) { while ($row = $resB->fetch_assoc()) $blood_list[] = $row; }
}

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
  <title>Admin & Staff Control Portal – MedPulse TN</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="./styles.css" />
  <!-- SESSION GUARD ENGINE -->
  <script>
    (function() {
      const rawSession = sessionStorage.getItem('medpulse_admin_session');
      if (!rawSession) {
        const isPhp = window.location.pathname.endsWith('.php');
        window.location.href = isPhp ? 'login.php' : 'login.html';
        return;
      }
      try {
        const session = JSON.parse(rawSession);
        if (!session || !session.loggedIn || !session.district) {
          const isPhp = window.location.pathname.endsWith('.php');
          window.location.href = isPhp ? 'login.php' : 'login.html';
        }
      } catch(e) {
        const isPhp = window.location.pathname.endsWith('.php');
        window.location.href = isPhp ? 'login.php' : 'login.html';
      }
    })();
  </script>
</head>
<body class="min-h-screen flex flex-col justify-between">

  <!-- ANTI-GRAVITY GLASSMORPHIC PRELOADER -->
  <div id="app-preloader">
    <div class="floating-logo flex flex-col items-center gap-3 text-center">
      <div class="w-16 h-16 rounded-2xl bg-emerald-500/20 border-2 border-emerald-500/40 flex items-center justify-center text-emerald-400 shadow-2xl">
        <i class="fa-solid fa-sliders text-2xl animate-spin-slow"></i>
      </div>
      <span class="text-xl font-extrabold text-main tracking-tight">MedPulse<span class="text-emerald-400">.Admin</span></span>
      <span class="text-xs text-muted font-mono tracking-widest uppercase animate-pulse">Initializing Management Console...</span>
    </div>
  </div>

  <!-- TOP HEADER NAVBAR -->
  <nav class="sticky top-0 z-40 border-b border-theme bg-card px-4 md:px-8 py-3.5 shadow-sm">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
      
      <a href="index.php" class="flex items-center gap-2.5">
        <div class="w-9 h-9 rounded-xl bg-emerald-500/15 border border-emerald-500/30 flex items-center justify-center text-emerald-400 text-base">
          <i class="fa-solid fa-sliders"></i>
        </div>
        <div>
          <span class="text-lg font-extrabold tracking-tight text-main">MedPulse<span class="text-emerald-400">.Admin</span></span>
          <span class="text-[10px] bg-emerald-500/10 text-emerald-400 px-2 py-0.5 rounded-full font-bold ml-1.5 border border-emerald-500/20">STAFF & NURSE DESK</span>
        </div>
      </a>

      <div class="flex items-center gap-2.5">
        <a href="index.php" class="hidden sm:inline-block text-xs font-semibold text-muted hover:text-emerald-400 px-3 py-1.5">Home</a>
        <a href="hospitals.php" class="hidden md:inline-block text-xs font-semibold text-muted hover:text-emerald-400 px-3 py-1.5">Hospitals</a>
        <a href="blood_bank.php" class="hidden md:inline-block text-xs font-semibold text-muted hover:text-emerald-400 px-3 py-1.5">Blood Bank</a>

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
            <a href="queue.php" class="drawer-item">Live OPD Queue Status</a>
            <a href="telemedicine.php" class="drawer-item">Telemedicine Portal</a>
            <a href="hpr.php" class="drawer-item">About HPR Verification</a>
            <a href="admin.php" class="drawer-item drawer-item-active">Admin & Staff Portal</a>
            <a href="hospitals.php" class="drawer-item">Hospital Directory</a>
            <a href="blood_bank.php" class="drawer-item">Blood Bank Stocks</a>
            <a href="map.php" class="drawer-item">Live Interactive Map</a>
          </div>
        </div>
      </div>
    </div>
  </aside>

  <!-- ADMIN MAIN CONTAINER -->
  <main class="max-w-7xl mx-auto px-4 md:px-8 py-8 w-full flex-grow">
    
    <!-- BANNER HEADER -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
      <div>
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold mb-2">
          <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
          <span id="admin-district-badge">📍 Logged in: District Staff Console</span>
        </div>
        <h1 class="text-2xl md:text-3xl font-extrabold text-main tracking-tight">Admin & Staff Control Desk</h1>
        <p class="text-xs text-muted mt-1">Manage doctor availability, update cabin presence, edit hospital infrastructure, and adjust blood bank inventories.</p>
      </div>

      <div class="flex items-center gap-3">
        <button onclick="logoutAdminSession()" class="bg-red-500/10 hover:bg-red-500/20 text-red-400 font-extrabold text-xs px-4 py-2.5 rounded-xl border border-red-500/30 transition-all flex items-center gap-2">
          <i class="fa-solid fa-right-from-bracket"></i>
          <span>Logout / Switch District</span>
        </button>
        <a href="index.php" class="bg-input hover:bg-card text-main font-bold text-xs px-4 py-2.5 rounded-xl border border-theme transition-all">
          ← Public Portal View
        </a>
      </div>
    </div>

    <!-- TAB NAVIGATION HEADER -->
    <div class="flex items-center gap-2.5 border-b border-theme pb-3 mb-8 overflow-x-auto">
      <button onclick="switchTab('presence')" id="tab-btn-presence" class="admin-tab active group px-5 py-2.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 shadow-md flex items-center gap-2">
        <i class="fa-solid fa-stethoscope text-emerald-400 text-sm"></i>
        <span>OPD Cabin & Doctor Presence</span>
      </button>
      <button onclick="switchTab('hospitals')" id="tab-btn-hospitals" class="admin-tab group px-5 py-2.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap bg-input text-muted border border-theme hover:border-emerald-500/40 hover:text-main flex items-center gap-2">
        <i class="fa-solid fa-hospital text-blue-400 text-sm"></i>
        <span>Hospitals Directory</span>
      </button>
      <button onclick="switchTab('doctors')" id="tab-btn-doctors" class="admin-tab group px-5 py-2.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap bg-input text-muted border border-theme hover:border-emerald-500/40 hover:text-main flex items-center gap-2">
        <i class="fa-solid fa-user-doctor text-emerald-400 text-sm"></i>
        <span>Doctors Management</span>
      </button>
      <button onclick="switchTab('blood')" id="tab-btn-blood" class="admin-tab group px-5 py-2.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap bg-input text-muted border border-theme hover:border-red-500/40 hover:text-main flex items-center gap-2">
        <i class="fa-solid fa-droplet text-red-400 text-sm"></i>
        <span>Blood Bank Stocks</span>
      </button>
      <button onclick="switchTab('verification')" id="tab-btn-verification" class="admin-tab group px-5 py-2.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap bg-input text-muted border border-theme hover:border-amber-500/40 hover:text-main flex items-center gap-2">
        <i class="fa-solid fa-scroll text-amber-400 text-sm"></i>
        <span>Doctor Verification Desk</span>
      </button>
    </div>

    <!-- TAB 1: DOCTOR PRESENCE CONTROL (NURSE PROXY DESK) -->
    <section id="tab-presence" class="tab-content space-y-6">
      <div class="bg-card border border-theme rounded-3xl p-6 shadow-xl anti-gravity-card max-w-3xl mx-auto">
        <div class="border-b border-theme pb-4 mb-6">
          <h2 class="text-xl font-extrabold text-main">Nurse Proxy Presence Update</h2>
          <p class="text-xs text-muted mt-1">Select a doctor to broadcast real-time cabin status (`IN CABIN`, `EMERGENCY / OT`, `OFF DUTY`) to the public portal.</p>
        </div>

        <div class="space-y-5">
          <div>
            <label class="block text-xs font-semibold text-muted uppercase mb-2">Select Doctor in your OPD Wing</label>
            <select id="presence-doc-select" class="w-full bg-input border border-theme rounded-xl px-4 py-3 text-xs md:text-sm text-main focus:outline-none focus:border-emerald-500" onchange="onPresenceDocChange()">
              <option value="">-- Choose Doctor --</option>
              <?php if (!empty($doctors_list)): ?>
                <?php foreach ($doctors_list as $doc): ?>
                  <option value="<?= htmlspecialchars($doc['doc_code']) ?>" data-name="<?= htmlspecialchars($doc['doctor_name']) ?>" data-cabin="<?= htmlspecialchars($doc['cabin']) ?>" data-district="<?= htmlspecialchars($doc['district']) ?>" data-status="<?= htmlspecialchars($doc['status']) ?>" data-note="<?= htmlspecialchars($doc['note'] ?? '') ?>">
                    <?= htmlspecialchars($doc['doctor_name']) ?> [<?= htmlspecialchars($doc['doc_code']) ?>] — <?= htmlspecialchars($doc['district']) ?> (<?= htmlspecialchars($doc['department']) ?>)
                  </option>
                <?php endforeach; ?>
              <?php endif; ?>
            </select>
          </div>

          <div id="presence-details" class="hidden space-y-5 pt-4 border-t border-theme">
            <div class="flex items-center justify-between">
              <div>
                <h3 id="presence-doc-name" class="font-extrabold text-lg text-main">Dr. Name</h3>
                <p id="presence-doc-location" class="text-xs text-muted">Location</p>
              </div>
              <span id="presence-badge" class="px-3.5 py-1 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">IN CABIN</span>
            </div>

            <div>
              <label class="block text-xs font-semibold text-muted uppercase mb-2">Toggle Presence State</label>
              <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <button type="button" onclick="setPresenceStatus('in')" id="btn-status-in" class="py-3 px-4 rounded-xl text-xs font-bold border border-emerald-500/40 bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500/20 text-center transition-all">
                  🟢 IN CABIN
                </button>
                <button type="button" onclick="setPresenceStatus('emr')" id="btn-status-emr" class="py-3 px-4 rounded-xl text-xs font-bold border border-amber-500/40 bg-amber-500/10 text-amber-400 hover:bg-amber-500/20 text-center transition-all">
                  🟡 EMERGENCY / OT
                </button>
                <button type="button" onclick="setPresenceStatus('off')" id="btn-status-off" class="py-3 px-4 rounded-xl text-xs font-bold border border-red-500/40 bg-red-500/10 text-red-400 hover:bg-red-500/20 text-center transition-all">
                  🔴 OFF DUTY
                </button>
              </div>
            </div>

            <div>
              <label class="block text-xs font-semibold text-muted uppercase mb-2">Broadcast Note / Schedule (Optional)</label>
              <input type="text" id="presence-note" placeholder="e.g. Conducting OPD consultation until 1:30 PM" class="w-full bg-input border border-theme rounded-xl px-4 py-3 text-xs text-main focus:outline-none focus:border-emerald-500">
            </div>

            <button type="button" onclick="savePresenceStatus()" class="w-full bg-emerald-500 hover:brightness-110 text-slate-950 font-bold py-3.5 rounded-xl shadow-lg text-xs md:text-sm transition-all">
              Broadcast Status Change to Patient Portal
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- TAB 2: HOSPITALS MANAGEMENT -->
    <section id="tab-hospitals" class="tab-content space-y-6 hidden">
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-extrabold text-main">Hospital Facilities Directory</h2>
        <button onclick="openHospitalModal()" class="bg-emerald-500 hover:brightness-110 text-slate-950 font-bold px-4 py-2.5 rounded-xl text-xs flex items-center gap-2 shadow-md">
          <i class="fa-solid fa-plus"></i> Add New Hospital
        </button>
      </div>

      <!-- HOSPITALS TABLE CARD -->
      <div class="bg-card border border-theme rounded-3xl shadow-xl overflow-hidden anti-gravity-card">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse text-xs">
            <thead>
              <tr class="border-b border-theme bg-input/50 text-muted font-bold uppercase text-[11px]">
                <th class="p-4">Hospital Name</th>
                <th class="p-4">District</th>
                <th class="p-4">Specialty Wards</th>
                <th class="p-4">Contact Phone</th>
                <th class="p-4">GPS Coordinates</th>
                <th class="p-4 text-right">Actions</th>
              </tr>
            </thead>
            <tbody id="hospitals-table-body" class="divide-y divide-theme text-main">
              <?php if (!empty($hospitals_list)): ?>
                <?php foreach ($hospitals_list as $h): ?>
                  <tr class="hover:bg-input/30 transition-all">
                    <td class="p-4 font-bold"><?= htmlspecialchars($h['hospital_name']) ?></td>
                    <td class="p-4"><span class="px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 font-semibold border border-emerald-500/20"><?= htmlspecialchars($h['district']) ?></span></td>
                    <td class="p-4 text-muted"><?= htmlspecialchars($h['specialty']) ?></td>
                    <td class="p-4 font-mono"><?= htmlspecialchars($h['contact']) ?></td>
                    <td class="p-4 text-muted font-mono text-[11px]"><?= $h['lat'] ? htmlspecialchars($h['lat']).', '.htmlspecialchars($h['lon']) : 'N/A' ?></td>
                    <td class="p-4 text-right space-x-1.5">
                      <button onclick="viewHospitalDoctors('<?= $h['id'] ?>', '<?= htmlspecialchars(addslashes($h['hospital_name'])) ?>')" class="px-2.5 py-1 rounded-lg bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500 hover:text-white font-bold text-[11px] border border-emerald-500/30 transition-all"><i class="fa-solid fa-hospital-user"></i> View Doctors</button>
                      <button onclick='editHospital(<?= json_encode($h) ?>)' class="px-2.5 py-1 rounded-lg bg-blue-500/10 text-blue-400 hover:bg-blue-500/20 font-bold text-[11px] border border-blue-500/30">Edit</button>
                      <button onclick="deleteHospital(<?= $h['id'] ?>, '<?= htmlspecialchars(addslashes($h['hospital_name'])) ?>')" class="px-2.5 py-1 rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500/20 font-bold text-[11px] border border-red-500/30">Delete</button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </section>

    <!-- TAB 3: DOCTORS MANAGEMENT -->
    <section id="tab-doctors" class="tab-content space-y-6 hidden">
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-extrabold text-main">Doctors Directory</h2>
        <button onclick="openDoctorModal()" class="bg-emerald-500 hover:brightness-110 text-slate-950 font-bold px-4 py-2.5 rounded-xl text-xs flex items-center gap-2 shadow-md">
          <i class="fa-solid fa-user-plus"></i> Add New Doctor
        </button>
      </div>

      <!-- DOCTORS TABLE CARD -->
      <div class="bg-card border border-theme rounded-3xl shadow-xl overflow-hidden anti-gravity-card">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse text-xs">
            <thead>
              <tr class="border-b border-theme bg-input/50 text-muted font-bold uppercase text-[11px]">
                <th class="p-4">Doctor & HPR ID</th>
                <th class="p-4">Qualification / Dept</th>
                <th class="p-4">Hospital & District</th>
                <th class="p-4">Cabin</th>
                <th class="p-4">Presence Status</th>
                <th class="p-4 text-right">Actions</th>
              </tr>
            </thead>
            <tbody id="doctors-table-body" class="divide-y divide-theme text-main">
              <?php if (!empty($doctors_list)): ?>
                <?php foreach ($doctors_list as $d): ?>
                  <tr class="hover:bg-input/30 transition-all">
                    <td class="p-4">
                      <div class="font-bold text-main"><?= htmlspecialchars($d['doctor_name']) ?></div>
                      <div class="text-[10px] text-blue-400 font-mono">Code: <?= htmlspecialchars($d['doc_code']) ?> · HPR: <?= htmlspecialchars($d['hpr_id']) ?></div>
                    </td>
                    <td class="p-4">
                      <div class="font-semibold"><?= htmlspecialchars($d['qualification']) ?></div>
                      <div class="text-muted text-[11px]"><?= htmlspecialchars($d['department']) ?></div>
                    </td>
                    <td class="p-4">
                      <div class="font-semibold"><?= htmlspecialchars($d['hospital_name']) ?></div>
                      <div class="text-emerald-400 text-[11px]"><?= htmlspecialchars($d['district']) ?> District</div>
                    </td>
                    <td class="p-4 font-mono text-[11px]"><?= htmlspecialchars($d['cabin']) ?></td>
                    <td class="p-4">
                      <?php if ($d['status'] === 'in'): ?>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/15 text-emerald-400 border border-emerald-500/30">IN CABIN</span>
                      <?php elseif ($d['status'] === 'emr'): ?>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/15 text-amber-400 border border-amber-500/30">EMERGENCY</span>
                      <?php else: ?>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-red-500/15 text-red-400 border border-red-500/30">OFF DUTY</span>
                      <?php endif; ?>
                    </td>
                    <td class="p-4 text-right space-x-2">
                      <button onclick='editDoctor(<?= json_encode($d) ?>)' class="px-2.5 py-1 rounded-lg bg-blue-500/10 text-blue-400 hover:bg-blue-500/20 font-bold text-[11px] border border-blue-500/30">Edit</button>
                      <button onclick="deleteDoctor(<?= $d['id'] ?>, '<?= htmlspecialchars(addslashes($d['doctor_name'])) ?>')" class="px-2.5 py-1 rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500/20 font-bold text-[11px] border border-red-500/30">Delete</button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </section>

    <!-- TAB 4: BLOOD BANK STOCKS MANAGEMENT -->
    <section id="tab-blood" class="tab-content space-y-6 hidden">
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-extrabold text-main">Blood Bank Reserves Directory</h2>
        <button onclick="openBloodModal()" class="bg-red-500 hover:brightness-110 text-white font-bold px-4 py-2.5 rounded-xl text-xs flex items-center gap-2 shadow-md">
          <i class="fa-solid fa-droplet"></i> Add Blood Stock Entry
        </button>
      </div>

      <!-- BLOOD BANK TABLE CARD -->
      <div class="bg-card border border-theme rounded-3xl shadow-xl overflow-hidden anti-gravity-card">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse text-xs">
            <thead>
              <tr class="border-b border-theme bg-input/50 text-muted font-bold uppercase text-[11px]">
                <th class="p-4">Blood Bank Center</th>
                <th class="p-4">District</th>
                <th class="p-4">Blood Group</th>
                <th class="p-4">Units Available</th>
                <th class="p-4">Emergency Contact</th>
                <th class="p-4 text-right">Actions</th>
              </tr>
            </thead>
            <tbody id="blood-table-body" class="divide-y divide-theme text-main">
              <?php if (!empty($blood_list)): ?>
                <?php foreach ($blood_list as $b): ?>
                  <tr class="hover:bg-input/30 transition-all">
                    <td class="p-4 font-bold"><?= htmlspecialchars($b['blood_bank_name']) ?></td>
                    <td class="p-4"><span class="px-2 py-0.5 rounded-full bg-red-500/10 text-red-400 font-semibold border border-red-500/20"><?= htmlspecialchars($b['district']) ?></span></td>
                    <td class="p-4 font-extrabold text-red-500 text-sm"><?= htmlspecialchars($b['blood_group']) ?></td>
                    <td class="p-4 font-bold">
                      <span class="px-3 py-1 rounded-xl bg-input border border-theme text-emerald-400 font-mono text-xs">
                        <?= htmlspecialchars($b['units']) ?> Units
                      </span>
                    </td>
                    <td class="p-4 font-mono"><?= htmlspecialchars($b['contact']) ?></td>
                    <td class="p-4 text-right space-x-2">
                      <button onclick='editBloodBank(<?= json_encode($b) ?>)' class="px-2.5 py-1 rounded-lg bg-blue-500/10 text-blue-400 hover:bg-blue-500/20 font-bold text-[11px] border border-blue-500/30">Edit Units</button>
                      <button onclick="deleteBloodBank(<?= $b['id'] ?>, '<?= htmlspecialchars(addslashes($b['blood_bank_name'])) ?>')" class="px-2.5 py-1 rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500/20 font-bold text-[11px] border border-red-500/30">Delete</button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </section>

    <!-- TAB 5: DOCTOR VERIFICATION & ONBOARDING DESK -->
    <section id="tab-verification" class="tab-content space-y-6 hidden">
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-xl font-extrabold text-main">Doctor Verification & Onboarding Desk</h2>
          <p class="text-xs text-muted mt-0.5">Review credentials of unemployed, locum, and specialist applicants seeking state hospital placement</p>
        </div>
        <div class="flex items-center gap-3">
          <button onclick="openAdminDoctorRegModal()" class="bg-emerald-500 hover:bg-emerald-600 text-white font-extrabold px-4 py-2.5 rounded-xl text-xs shadow-lg transition-all flex items-center gap-2">
            <i class="fa-solid fa-user-plus"></i>
            <span>+ Enter New Doctor Details</span>
          </button>
          <a href="doctor_register.php" target="_blank" class="bg-input hover:bg-card text-main font-bold px-3.5 py-2.5 rounded-xl text-xs border border-theme transition-all hidden sm:flex items-center gap-1.5">
            <span>Public Form ↗</span>
          </a>
        </div>
      </div>

      <!-- APPLICATIONS TABLE CARD -->
      <div class="bg-card border border-theme rounded-3xl shadow-xl overflow-hidden anti-gravity-card">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse text-xs">
            <thead>
              <tr class="border-b border-theme bg-input/50 text-muted font-bold uppercase text-[11px]">
                <th class="p-4">Applicant Doctor</th>
                <th class="p-4">HPR & Medical Council No</th>
                <th class="p-4">Qualification & Dept</th>
                <th class="p-4">Employment Seeking</th>
                <th class="p-4">District</th>
                <th class="p-4">Verification Status</th>
                <th class="p-4 text-right">Actions</th>
              </tr>
            </thead>
            <tbody id="applications-table-body" class="divide-y divide-theme text-main">
              <!-- Rendered dynamically by app.js -->
            </tbody>
          </table>
        </div>
      </div>
    </section>

  </main>

  <!-- MODAL: HOSPITAL FORM -->
  <div id="hospital-modal" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-[10000] hidden flex items-center justify-center p-4">
    <div class="bg-card border border-theme rounded-3xl p-6 max-w-lg w-full shadow-2xl relative">
      <button onclick="closeHospitalModal()" class="absolute top-5 right-5 text-muted hover:text-main text-lg font-bold">✕</button>
      <h3 id="hospital-modal-title" class="text-lg font-extrabold text-main mb-4">Add New Hospital</h3>
      
      <form onsubmit="saveHospitalForm(event)" class="space-y-4 text-xs">
        <input type="hidden" id="hosp-id" value="">
        <div>
          <label class="block font-semibold text-muted uppercase mb-1">Hospital Name *</label>
          <input type="text" id="hosp-name" required class="w-full bg-input border border-theme rounded-xl px-3.5 py-2.5 text-main focus:outline-none focus:border-emerald-500" placeholder="e.g. Govt Medical College Hospital">
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block font-semibold text-muted uppercase mb-1">District *</label>
            <select id="hosp-district" required class="w-full bg-input border border-theme rounded-xl px-3 py-2.5 text-main focus:outline-none">
              <?php foreach ($tamilnadu_districts as $d): ?>
                <option value="<?= htmlspecialchars($d) ?>"><?= htmlspecialchars($d) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label class="block font-semibold text-muted uppercase mb-1">Contact Phone</label>
            <input type="text" id="hosp-contact" class="w-full bg-input border border-theme rounded-xl px-3.5 py-2.5 text-main focus:outline-none" placeholder="+91 44 2530 5000">
          </div>
        </div>

        <div>
          <label class="block font-semibold text-muted uppercase mb-1">Specialties Offered</label>
          <input type="text" id="hosp-specialty" class="w-full bg-input border border-theme rounded-xl px-3.5 py-2.5 text-main focus:outline-none" placeholder="e.g. Cardiology, Emergency Trauma, ICU">
        </div>

        <div>
          <label class="block font-semibold text-muted uppercase mb-1">Full Address</label>
          <input type="text" id="hosp-address" class="w-full bg-input border border-theme rounded-xl px-3.5 py-2.5 text-main focus:outline-none" placeholder="Street Name, City, Pincode">
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block font-semibold text-muted uppercase mb-1">Latitude (Lat)</label>
            <input type="number" step="any" id="hosp-lat" class="w-full bg-input border border-theme rounded-xl px-3.5 py-2.5 text-main focus:outline-none" placeholder="13.0827">
          </div>
          <div>
            <label class="block font-semibold text-muted uppercase mb-1">Longitude (Lon)</label>
            <input type="number" step="any" id="hosp-lon" class="w-full bg-input border border-theme rounded-xl px-3.5 py-2.5 text-main focus:outline-none" placeholder="80.2707">
          </div>
        </div>

        <div class="pt-3 flex items-center justify-end gap-3 border-t border-theme">
          <button type="button" onclick="closeHospitalModal()" class="px-4 py-2.5 rounded-xl border border-theme text-muted font-bold hover:bg-input">Cancel</button>
          <button type="submit" class="bg-emerald-500 hover:brightness-110 text-slate-950 font-bold px-5 py-2.5 rounded-xl shadow-lg">Save Hospital</button>
        </div>
      </form>
    </div>
  </div>

  <!-- MODAL: DOCTOR FORM -->
  <div id="doctor-modal" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-[10000] hidden flex items-center justify-center p-4">
    <div class="bg-card border border-theme rounded-3xl p-6 max-w-xl w-full shadow-2xl relative overflow-y-auto max-h-[90vh]">
      <button onclick="closeDoctorModal()" class="absolute top-5 right-5 text-muted hover:text-main text-lg font-bold">✕</button>
      <h3 id="doctor-modal-title" class="text-lg font-extrabold text-main mb-4">Add New Doctor</h3>
      
      <form onsubmit="saveDoctorForm(event)" class="space-y-4 text-xs">
        <input type="hidden" id="doc-id" value="">
        
        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block font-semibold text-muted uppercase mb-1">Doctor Code *</label>
            <input type="text" id="doc-code" required class="w-full bg-input border border-theme rounded-xl px-3.5 py-2.5 text-main focus:outline-none" placeholder="D1025">
          </div>
          <div>
            <label class="block font-semibold text-muted uppercase mb-1">NHA HPR ID</label>
            <input type="text" id="doc-hpr" class="w-full bg-input border border-theme rounded-xl px-3.5 py-2.5 text-main focus:outline-none" placeholder="HPR_94820">
          </div>
        </div>

        <div>
          <label class="block font-semibold text-muted uppercase mb-1">Doctor Name *</label>
          <input type="text" id="doc-name" required class="w-full bg-input border border-theme rounded-xl px-3.5 py-2.5 text-main focus:outline-none" placeholder="Dr. K. Raman">
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block font-semibold text-muted uppercase mb-1">Qualification</label>
            <input type="text" id="doc-qual" class="w-full bg-input border border-theme rounded-xl px-3.5 py-2.5 text-main focus:outline-none" placeholder="MD Cardiology">
          </div>
          <div>
            <label class="block font-semibold text-muted uppercase mb-1">Department</label>
            <input type="text" id="doc-dept" class="w-full bg-input border border-theme rounded-xl px-3.5 py-2.5 text-main focus:outline-none" placeholder="Cardiology">
          </div>
        </div>

        <div>
          <label class="block font-semibold text-muted uppercase mb-1">Assigned Hospital Name</label>
          <input type="text" id="doc-hospital" class="w-full bg-input border border-theme rounded-xl px-3.5 py-2.5 text-main focus:outline-none" placeholder="Rajiv Gandhi Govt General Hospital">
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block font-semibold text-muted uppercase mb-1">District *</label>
            <select id="doc-district" required class="w-full bg-input border border-theme rounded-xl px-3 py-2.5 text-main focus:outline-none">
              <?php foreach ($tamilnadu_districts as $d): ?>
                <option value="<?= htmlspecialchars($d) ?>"><?= htmlspecialchars($d) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label class="block font-semibold text-muted uppercase mb-1">OPD Cabin Location</label>
            <input type="text" id="doc-cabin" class="w-full bg-input border border-theme rounded-xl px-3.5 py-2.5 text-main focus:outline-none" placeholder="Cabin 1, Block A">
          </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block font-semibold text-muted uppercase mb-1">OPD Start Time</label>
            <input type="time" id="doc-opd-start" class="w-full bg-input border border-theme rounded-xl px-3.5 py-2.5 text-main focus:outline-none" value="09:00">
          </div>
          <div>
            <label class="block font-semibold text-muted uppercase mb-1">OPD End Time</label>
            <input type="time" id="doc-opd-end" class="w-full bg-input border border-theme rounded-xl px-3.5 py-2.5 text-main focus:outline-none" value="13:00">
          </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block font-semibold text-muted uppercase mb-1">Initial Presence Status</label>
            <select id="doc-status" class="w-full bg-input border border-theme rounded-xl px-3 py-2.5 text-main focus:outline-none">
              <option value="in">IN CABIN</option>
              <option value="emr">IN EMERGENCY / OT</option>
              <option value="off">OFF DUTY</option>
            </select>
          </div>
          <div>
            <label class="block font-semibold text-muted uppercase mb-1">Broadcast Note</label>
            <input type="text" id="doc-note" class="w-full bg-input border border-theme rounded-xl px-3.5 py-2.5 text-main focus:outline-none" placeholder="In OPD Consultation">
          </div>
        </div>

        <div class="pt-3 flex items-center justify-end gap-3 border-t border-theme">
          <button type="button" onclick="closeDoctorModal()" class="px-4 py-2.5 rounded-xl border border-theme text-muted font-bold hover:bg-input">Cancel</button>
          <button type="submit" class="bg-emerald-500 hover:brightness-110 text-slate-950 font-bold px-5 py-2.5 rounded-xl shadow-lg">Save Doctor</button>
        </div>
      </form>
    </div>
  </div>

  <!-- MODAL: BLOOD BANK FORM -->
  <div id="blood-modal" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-[10000] hidden flex items-center justify-center p-4">
    <div class="bg-card border border-theme rounded-3xl p-6 max-w-lg w-full shadow-2xl relative">
      <button onclick="closeBloodModal()" class="absolute top-5 right-5 text-muted hover:text-main text-lg font-bold">✕</button>
      <h3 id="blood-modal-title" class="text-lg font-extrabold text-main mb-4">Add Blood Bank Stock Entry</h3>
      
      <form onsubmit="saveBloodForm(event)" class="space-y-4 text-xs">
        <input type="hidden" id="blood-id" value="">
        
        <div>
          <label class="block font-semibold text-muted uppercase mb-1">Blood Bank Center Name *</label>
          <input type="text" id="blood-name" required class="w-full bg-input border border-theme rounded-xl px-3.5 py-2.5 text-main focus:outline-none" placeholder="RGGGH Central Blood Bank">
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block font-semibold text-muted uppercase mb-1">District *</label>
            <select id="blood-district" required class="w-full bg-input border border-theme rounded-xl px-3 py-2.5 text-main focus:outline-none">
              <?php foreach ($tamilnadu_districts as $d): ?>
                <option value="<?= htmlspecialchars($d) ?>"><?= htmlspecialchars($d) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label class="block font-semibold text-muted uppercase mb-1">Emergency Phone</label>
            <input type="text" id="blood-contact" class="w-full bg-input border border-theme rounded-xl px-3.5 py-2.5 text-main focus:outline-none" placeholder="044-25305000">
          </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block font-semibold text-muted uppercase mb-1">Blood Group *</label>
            <select id="blood-group" required class="w-full bg-input border border-theme rounded-xl px-3 py-2.5 text-main focus:outline-none">
              <option value="A+">A+</option>
              <option value="A-">A-</option>
              <option value="B+">B+</option>
              <option value="B-">B-</option>
              <option value="O+">O+</option>
              <option value="O-">O-</option>
              <option value="AB+">AB+</option>
              <option value="AB-">AB-</option>
            </select>
          </div>
          <div>
            <label class="block font-semibold text-muted uppercase mb-1">Units Available *</label>
            <input type="number" id="blood-units" required min="0" class="w-full bg-input border border-theme rounded-xl px-3.5 py-2.5 text-main focus:outline-none" placeholder="45">
          </div>
        </div>

        <div>
          <label class="block font-semibold text-muted uppercase mb-1">Address / Landmark</label>
          <input type="text" id="blood-address" class="w-full bg-input border border-theme rounded-xl px-3.5 py-2.5 text-main focus:outline-none" placeholder="Park Town, Chennai">
        </div>

        <div class="pt-3 flex items-center justify-end gap-3 border-t border-theme">
          <button type="button" onclick="closeBloodModal()" class="px-4 py-2.5 rounded-xl border border-theme text-muted font-bold hover:bg-input">Cancel</button>
          <button type="submit" class="bg-red-500 hover:brightness-110 text-white font-bold px-5 py-2.5 rounded-xl shadow-lg">Save Blood Entry</button>
        </div>
      </form>
    </div>
  </div>

  <!-- FOOTER -->
  <footer class="border-t border-theme py-6 px-4 md:px-8 bg-card text-center text-xs text-muted">
    <p>MedPulse TN — Administration & Healthcare Staff Console</p>
  </footer>

  <script src="./app.js"></script>
    // Dynamic Tab Switcher Logic
    function switchTab(tabName) {
      document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
      document.querySelectorAll('.admin-tab').forEach(el => {
        el.className = 'admin-tab group px-5 py-2.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap bg-input text-muted border border-theme hover:border-emerald-500/40 hover:text-main flex items-center gap-2';
      });

      const activeTab = document.getElementById(`tab-${tabName}`);
      const activeBtn = document.getElementById(`tab-btn-${tabName}`);
      if (activeTab) activeTab.classList.remove('hidden');
      if (activeBtn) {
        let activeColor = 'bg-emerald-500/20 text-emerald-400 border-emerald-500/40 shadow-lg';
        if (tabName === 'blood') activeColor = 'bg-red-500/20 text-red-400 border-red-500/40 shadow-lg';
        else if (tabName === 'hospitals') activeColor = 'bg-blue-500/20 text-blue-400 border-blue-500/40 shadow-lg';

        activeBtn.className = `admin-tab active group px-5 py-2.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap border flex items-center gap-2 ${activeColor}`;
      }
    }

    // Doctor Presence Control Logic
    let selectedPresenceStatus = 'in';

    function onPresenceDocChange() {
      const select = document.getElementById('presence-doc-select');
      const opt = select.options[select.selectedIndex];
      const details = document.getElementById('presence-details');

      if (!select.value) {
        if (details) details.classList.add('hidden');
        return;
      }

      details.classList.remove('hidden');
      document.getElementById('presence-doc-name').textContent = opt.getAttribute('data-name');
      document.getElementById('presence-doc-location').textContent = `${opt.getAttribute('data-district')} · ${opt.getAttribute('data-cabin')}`;
      
      const noteInput = document.getElementById('presence-note');
      if (noteInput) noteInput.value = opt.getAttribute('data-note') || '';

      setPresenceStatus(opt.getAttribute('data-status') || 'in');
    }

    function setPresenceStatus(status) {
      selectedPresenceStatus = status;
      const badge = document.getElementById('presence-badge');
      if (status === 'in') {
        badge.className = 'px-3.5 py-1 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30';
        badge.textContent = 'IN CABIN';
      } else if (status === 'emr') {
        badge.className = 'px-3.5 py-1 rounded-full text-xs font-bold bg-amber-500/20 text-amber-400 border border-amber-500/30';
        badge.textContent = 'IN EMERGENCY / OT';
      } else {
        badge.className = 'px-3.5 py-1 rounded-full text-xs font-bold bg-red-500/20 text-red-400 border border-red-500/30';
        badge.textContent = 'OFF DUTY';
      }
    }

    async function savePresenceStatus() {
      const select = document.getElementById('presence-doc-select');
      const docCode = select ? select.value : '';
      const note = document.getElementById('presence-note').value;

      if (!docCode) return;

      // LocalStorage sync for instant static fallback
      const savedUpdates = JSON.parse(localStorage.getItem('medpulse_status_updates') || '{}');
      savedUpdates[docCode] = {
        status: selectedPresenceStatus,
        reason: note,
        lastPunch: 'Just now'
      };
      localStorage.setItem('medpulse_status_updates', JSON.stringify(savedUpdates));

      // DB sync
      try {
        await fetch('admin_api.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ action: 'update_doctor_status', doc_code: docCode, status: selectedPresenceStatus, note: note })
        });
      } catch (e) {}

      alert('✓ Presence status updated and live across all portals!');
    }

    // Modal Control Functions
    function openHospitalModal() {
      document.getElementById('hosp-id').value = '';
      document.getElementById('hosp-name').value = '';
      document.getElementById('hosp-contact').value = '';
      document.getElementById('hosp-specialty').value = '';
      document.getElementById('hosp-address').value = '';
      document.getElementById('hosp-lat').value = '';
      document.getElementById('hosp-lon').value = '';
      document.getElementById('hospital-modal-title').textContent = 'Add New Hospital';
      document.getElementById('hospital-modal').classList.remove('hidden');
    }

    function closeHospitalModal() {
      document.getElementById('hospital-modal').classList.add('hidden');
    }

    function editHospital(h) {
      document.getElementById('hosp-id').value = h.id || '';
      document.getElementById('hosp-name').value = h.hospital_name || '';
      document.getElementById('hosp-district').value = h.district || 'Chennai';
      document.getElementById('hosp-contact').value = h.contact || '';
      document.getElementById('hosp-specialty').value = h.specialty || '';
      document.getElementById('hosp-address').value = h.address || '';
      document.getElementById('hosp-lat').value = h.lat || '';
      document.getElementById('hosp-lon').value = h.lon || '';
      document.getElementById('hospital-modal-title').textContent = 'Edit Hospital Details';
      document.getElementById('hospital-modal').classList.remove('hidden');
    }

    async function saveHospitalForm(e) {
      e.preventDefault();
      const payload = {
        action: 'save_hospital',
        id: document.getElementById('hosp-id').value,
        hospital_name: document.getElementById('hosp-name').value,
        district: document.getElementById('hosp-district').value,
        contact: document.getElementById('hosp-contact').value,
        specialty: document.getElementById('hosp-specialty').value,
        address: document.getElementById('hosp-address').value,
        lat: document.getElementById('hosp-lat').value,
        lon: document.getElementById('hosp-lon').value
      };

      // LocalStorage sync
      const overrides = JSON.parse(localStorage.getItem('medpulse_hospitals_override') || '[]');
      if (payload.id) {
        const idx = overrides.findIndex(x => x.id == payload.id);
        if (idx >= 0) overrides[idx] = payload;
        else overrides.push(payload);
      } else {
        payload.id = 'h_' + Date.now();
        overrides.push(payload);
      }
      localStorage.setItem('medpulse_hospitals_override', JSON.stringify(overrides));

      try {
        await fetch('admin_api.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        });
      } catch (e) {}

      closeHospitalModal();
      alert('✓ Hospital details saved and published!');
      window.location.reload();
    }

    async function deleteHospital(id, name) {
      if (!confirm(`Are you sure you want to delete "${name}"?`)) return;

      const overrides = JSON.parse(localStorage.getItem('medpulse_hospitals_override') || '[]');
      const filtered = overrides.filter(x => x.id != id);
      localStorage.setItem('medpulse_hospitals_override', JSON.stringify(filtered));

      try {
        await fetch('admin_api.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ action: 'delete_hospital', id: id })
        });
      } catch (e) {}

      alert('✓ Hospital deleted!');
      window.location.reload();
    }

    function openDoctorModal() {
      document.getElementById('doc-id').value = 'D' + Math.floor(1000 + Math.random() * 9000);
      document.getElementById('doc-code').value = document.getElementById('doc-id').value;
      document.getElementById('doc-hpr').value = 'HPR_' + Math.floor(10000 + Math.random() * 90000);
      document.getElementById('doc-name').value = '';
      document.getElementById('doc-qual').value = '';
      document.getElementById('doc-dept').value = '';
      document.getElementById('doc-hospital').value = '';
      document.getElementById('doc-cabin').value = 'Cabin 1, Main Block';
      document.getElementById('doc-status').value = 'in';
      document.getElementById('doc-note').value = '';
      if (document.getElementById('doc-opd-start')) document.getElementById('doc-opd-start').value = '09:00';
      if (document.getElementById('doc-opd-end')) document.getElementById('doc-opd-end').value = '13:00';
      document.getElementById('doctor-modal-title').textContent = 'Add New Doctor';
      document.getElementById('doctor-modal').classList.remove('hidden');
    }

    function closeDoctorModal() {
      document.getElementById('doctor-modal').classList.add('hidden');
    }

    function editDoctor(d) {
      document.getElementById('doc-id').value = d.id || '';
      document.getElementById('doc-code').value = d.doc_code || d.id || '';
      document.getElementById('doc-hpr').value = d.hpr_id || d.hpr || '';
      document.getElementById('doc-name').value = d.doctor_name || d.name || '';
      document.getElementById('doc-qual').value = d.qualification || d.qual || '';
      document.getElementById('doc-dept').value = d.department || d.dept || '';
      document.getElementById('doc-hospital').value = d.hospital_name || d.hospitalName || '';
      document.getElementById('doc-district').value = d.district || d.districtName || 'Chennai';
      document.getElementById('doc-cabin').value = d.cabin || '';
      document.getElementById('doc-status').value = d.status || 'in';
      document.getElementById('doc-note').value = d.note || '';
      if (document.getElementById('doc-opd-start')) document.getElementById('doc-opd-start').value = d.opd_start_time || d.opdStartTime || '09:00';
      if (document.getElementById('doc-opd-end')) document.getElementById('doc-opd-end').value = d.opd_end_time || d.opdEndTime || '13:00';
      document.getElementById('doctor-modal-title').textContent = 'Edit Doctor Profile';
      document.getElementById('doctor-modal').classList.remove('hidden');
    }

    async function saveDoctorForm(e) {
      e.preventDefault();
      const payload = {
        action: 'save_doctor',
        id: document.getElementById('doc-id').value,
        doc_code: document.getElementById('doc-code').value,
        hpr_id: document.getElementById('doc-hpr').value,
        doctor_name: document.getElementById('doc-name').value,
        qualification: document.getElementById('doc-qual').value,
        department: document.getElementById('doc-dept').value,
        hospital_name: document.getElementById('doc-hospital').value,
        district: document.getElementById('doc-district').value,
        cabin: document.getElementById('doc-cabin').value,
        status: document.getElementById('doc-status').value,
        note: document.getElementById('doc-note').value,
        opd_start_time: document.getElementById('doc-opd-start') ? document.getElementById('doc-opd-start').value : '09:00',
        opd_end_time: document.getElementById('doc-opd-end') ? document.getElementById('doc-opd-end').value : '13:00',
        opdStartTime: document.getElementById('doc-opd-start') ? document.getElementById('doc-opd-start').value : '09:00',
        opdEndTime: document.getElementById('doc-opd-end') ? document.getElementById('doc-opd-end').value : '13:00'
      };

      const overrides = JSON.parse(localStorage.getItem('medpulse_doctors_override') || '[]');
      if (payload.id) {
        const idx = overrides.findIndex(x => x.id == payload.id);
        if (idx >= 0) overrides[idx] = payload;
        else overrides.push(payload);
      } else {
        payload.id = 'd_' + Date.now();
        overrides.push(payload);
      }
      localStorage.setItem('medpulse_doctors_override', JSON.stringify(overrides));

      try {
        await fetch('admin_api.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        });
      } catch (e) {}

      closeDoctorModal();
      alert('✓ Doctor record saved!');
      window.location.reload();
    }

    async function deleteDoctor(id, name) {
      if (!confirm(`Are you sure you want to delete "${name}"?`)) return;

      const overrides = JSON.parse(localStorage.getItem('medpulse_doctors_override') || '[]');
      const filtered = overrides.filter(x => x.id != id);
      localStorage.setItem('medpulse_doctors_override', JSON.stringify(filtered));

      try {
        await fetch('admin_api.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ action: 'delete_doctor', id: id })
        });
      } catch (e) {}

      alert('✓ Doctor record deleted!');
      window.location.reload();
    }

    // Blood Bank Modal Functions
    function openBloodModal() {
      document.getElementById('blood-id').value = '';
      document.getElementById('blood-name').value = '';
      document.getElementById('blood-contact').value = '';
      document.getElementById('blood-units').value = '25';
      document.getElementById('blood-address').value = '';
      document.getElementById('blood-modal-title').textContent = 'Add Blood Bank Stock Entry';
      document.getElementById('blood-modal').classList.remove('hidden');
    }

    function closeBloodModal() {
      document.getElementById('blood-modal').classList.add('hidden');
    }

    function editBloodBank(b) {
      document.getElementById('blood-id').value = b.id || '';
      document.getElementById('blood-name').value = b.blood_bank_name || '';
      document.getElementById('blood-district').value = b.district || 'Chennai';
      document.getElementById('blood-contact').value = b.contact || '';
      document.getElementById('blood-group').value = b.blood_group || 'A+';
      document.getElementById('blood-units').value = b.units || '0';
      document.getElementById('blood-address').value = b.address || '';
      document.getElementById('blood-modal-title').textContent = 'Edit Blood Stock Units';
      document.getElementById('blood-modal').classList.remove('hidden');
    }

    async function saveBloodForm(e) {
      e.preventDefault();
      const payload = {
        action: 'save_blood_bank',
        id: document.getElementById('blood-id').value,
        blood_bank_name: document.getElementById('blood-name').value,
        district: document.getElementById('blood-district').value,
        contact: document.getElementById('blood-contact').value,
        blood_group: document.getElementById('blood-group').value,
        units: document.getElementById('blood-units').value,
        address: document.getElementById('blood-address').value
      };

      const overrides = JSON.parse(localStorage.getItem('medpulse_blood_override') || '[]');
      if (payload.id) {
        const idx = overrides.findIndex(x => x.id == payload.id);
        if (idx >= 0) overrides[idx] = payload;
        else overrides.push(payload);
      } else {
        payload.id = 'b_' + Date.now();
        overrides.push(payload);
      }
      localStorage.setItem('medpulse_blood_override', JSON.stringify(overrides));

      try {
        await fetch('admin_api.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        });
      } catch (e) {}

      closeBloodModal();
      alert('✓ Blood stock units updated!');
      window.location.reload();
    }

    async function deleteBloodBank(id, name) {
      if (!confirm(`Are you sure you want to delete entry for "${name}"?`)) return;

      const overrides = JSON.parse(localStorage.getItem('medpulse_blood_override') || '[]');
      const filtered = overrides.filter(x => x.id != id);
      localStorage.setItem('medpulse_blood_override', JSON.stringify(filtered));

      try {
        await fetch('admin_api.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ action: 'delete_blood_bank', id: id })
        });
      } catch (e) {}

      alert('✓ Blood bank entry deleted!');
      window.location.reload();
    }
  </script>

  <!-- HOSPITAL DOCTOR MANAGEMENT MODAL -->
  <div id="hospital-doctors-modal" class="fixed inset-0 bg-black/75 backdrop-blur-sm z-[10000] hidden flex items-center justify-center p-4">
    <div class="bg-card border border-theme rounded-3xl p-6 max-w-3xl w-full shadow-2xl relative max-h-[90vh] overflow-y-auto">
      <button onclick="closeHospitalDoctorsModal()" class="absolute top-5 right-5 text-muted hover:text-main text-xl font-bold leading-none p-1">✕</button>
      
      <div class="flex items-center gap-3 border-b border-theme pb-4 mb-5">
        <div class="w-10 h-10 rounded-xl bg-blue-500/20 text-blue-400 flex items-center justify-center font-bold text-lg">
          <i class="fa-solid fa-hospital-user"></i>
        </div>
        <div>
          <h3 id="hosp-modal-title" class="text-lg font-extrabold text-main">Hospital Assigned Clinicians</h3>
          <p id="hosp-modal-subtitle" class="text-xs text-muted">Real-time OPD Cabin Presence Management</p>
        </div>
      </div>

      <!-- DOCTOR LIST FOR THIS HOSPITAL -->
      <div id="hosp-modal-doctor-list" class="space-y-4">
        <!-- Rendered dynamically by app.js -->
      </div>
    </div>
  </div>

  <!-- DOCUMENT REVIEW MODAL (TAB 5) -->
  <div id="doc-review-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[10000] hidden flex items-center justify-center p-4">
    <div class="bg-card border border-theme rounded-3xl p-6 md:p-8 max-w-4xl w-full shadow-2xl relative max-h-[90vh] overflow-y-auto anti-gravity-card">
      <button onclick="closeDocReviewModal()" class="absolute top-5 right-5 text-muted hover:text-main text-xl font-bold leading-none p-1">✕</button>
      
      <div class="flex items-center gap-3 border-b border-theme pb-4 mb-6">
        <div class="w-12 h-12 rounded-2xl bg-amber-500/20 text-amber-400 flex items-center justify-center font-bold text-xl border border-amber-500/30">
          <i class="fa-solid fa-id-card"></i>
        </div>
        <div>
          <h3 id="review-doc-name" class="text-xl font-extrabold text-main">Dr. Applicant Credentials</h3>
          <p id="review-doc-sub" class="text-xs text-muted">ABDM HPR & State Medical Council Verification Desk</p>
        </div>
      </div>

      <!-- DETAILS GRID -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-input/50 border border-theme rounded-2xl p-5 space-y-3 text-xs">
          <h4 class="font-extrabold text-emerald-400 uppercase text-[11px] tracking-wider mb-2">📋 Applicant Details</h4>
          <div class="flex justify-between"><span class="text-muted">HPR ID:</span><span id="review-hpr" class="font-mono font-bold text-main">HPR_18492</span></div>
          <div class="flex justify-between"><span class="text-muted">TN Council No:</span><span id="review-council" class="font-mono font-bold text-main">TNMC/2021/84920</span></div>
          <div class="flex justify-between"><span class="text-muted">Qualification:</span><span id="review-qual" class="font-semibold text-main">MD Gen Medicine</span></div>
          <div class="flex justify-between"><span class="text-muted">Specialization:</span><span id="review-dept" class="font-semibold text-main">Cardiology</span></div>
          <div class="flex justify-between"><span class="text-muted">District:</span><span id="review-district" class="font-semibold text-emerald-400">Vellore</span></div>
          <div class="flex justify-between"><span class="text-muted">Seeking Posting:</span><span id="review-status" class="font-semibold text-amber-400">Unemployed / Seeking Posting</span></div>
        </div>

        <div class="bg-input/50 border border-theme rounded-2xl p-5 space-y-4 text-xs">
          <h4 class="font-extrabold text-blue-400 uppercase text-[11px] tracking-wider mb-2">📄 Certificate Documents Uploaded</h4>
          
          <div class="border border-theme rounded-xl p-3 flex items-center justify-between">
            <div class="flex items-center gap-2">
              <i class="fa-solid fa-file-pdf text-red-400 text-lg"></i>
              <div>
                <p class="font-bold text-main">Scanned Degree Certificate</p>
                <span class="text-[10px] text-muted">PDF / Verified Scan</span>
              </div>
            </div>
            <button onclick="openSampleCert('Degree Certificate')" class="px-3 py-1.5 rounded-lg bg-blue-500/15 text-blue-400 font-bold border border-blue-500/30 hover:bg-blue-500 hover:text-white transition-all text-xs">Preview Document</button>
          </div>

          <div class="border border-theme rounded-xl p-3 flex items-center justify-between">
            <div class="flex items-center gap-2">
              <i class="fa-solid fa-certificate text-amber-400 text-lg"></i>
              <div>
                <p class="font-bold text-main">Medical Council Certificate</p>
                <span class="text-[10px] text-muted">TNMC Official Registration</span>
              </div>
            </div>
            <button onclick="openSampleCert('Medical Council Certificate')" class="px-3 py-1.5 rounded-lg bg-blue-500/15 text-blue-400 font-bold border border-blue-500/30 hover:bg-blue-500 hover:text-white transition-all text-xs">Preview Document</button>
          </div>

          <!-- ABDM VALIDATION BUTTON -->
          <button onclick="runABDMValidationCheck()" class="w-full py-2.5 px-4 rounded-xl bg-emerald-500/15 hover:bg-emerald-500/25 text-emerald-400 font-extrabold border border-emerald-500/30 text-xs flex items-center justify-center gap-2 transition-all">
            <i class="fa-solid fa-shield-halved"></i>
            <span>Run Live ABDM HPR Validation Check</span>
          </button>
          <div id="abdm-check-result" class="hidden text-center p-2 rounded-xl text-xs font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30"></div>
        </div>
      </div>

      <!-- ACTION BUTTONS -->
      <div class="pt-4 border-t border-theme flex flex-col sm:flex-row items-center justify-between gap-3">
        <button onclick="rejectDoctorApplicationPrompt()" class="w-full sm:w-auto bg-red-500/15 hover:bg-red-500 text-red-400 hover:text-white font-extrabold px-5 py-3 rounded-xl border border-red-500/30 text-xs transition-all flex items-center justify-center gap-2">
          <i class="fa-solid fa-xmark"></i> Reject Application
        </button>

        <button onclick="openHospitalAttachModal()" class="w-full sm:w-auto bg-emerald-500 hover:bg-emerald-600 text-white font-extrabold px-6 py-3 rounded-xl shadow-lg text-xs transition-all flex items-center justify-center gap-2">
          <i class="fa-solid fa-circle-check"></i> Approve & Attach to Hospital
        </button>
      </div>

    </div>
  </div>

  <!-- ADMIN DIRECT DOCTOR REGISTRATION MODAL -->
  <div id="admin-register-doc-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[10000] hidden flex items-center justify-center p-4">
    <div class="bg-card border border-theme rounded-3xl p-6 md:p-8 max-w-2xl w-full shadow-2xl relative max-h-[90vh] overflow-y-auto anti-gravity-card">
      <button onclick="closeAdminDoctorRegModal()" class="absolute top-5 right-5 text-muted hover:text-main text-lg font-bold">✕</button>
      
      <div class="flex items-center gap-3 border-b border-theme pb-4 mb-5">
        <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-lg font-bold">
          <i class="fa-solid fa-user-plus"></i>
        </div>
        <div>
          <h3 class="text-lg font-extrabold text-main">Register Practitioner Application</h3>
          <p class="text-xs text-muted">Enter doctor details directly for district verification</p>
        </div>
      </div>

      <form onsubmit="saveAdminDoctorRegForm(event)" class="space-y-4 text-xs">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block font-bold text-muted uppercase mb-1">Full Doctor Name *</label>
            <input type="text" id="admin-reg-name" required placeholder="e.g. Dr. K. Anbarasan" class="w-full bg-input border border-theme rounded-xl px-3.5 py-2.5 text-main font-semibold focus:outline-none focus:border-emerald-500">
          </div>
          <div>
            <label class="block font-bold text-muted uppercase mb-1">Email Address *</label>
            <input type="email" id="admin-reg-email" required placeholder="e.g. dr.anbarasan@gmail.com" class="w-full bg-input border border-theme rounded-xl px-3.5 py-2.5 text-main font-semibold focus:outline-none focus:border-emerald-500">
          </div>
          <div>
            <label class="block font-bold text-muted uppercase mb-1">Contact Phone *</label>
            <input type="tel" id="admin-reg-phone" required placeholder="e.g. +91 98402 12345" class="w-full bg-input border border-theme rounded-xl px-3.5 py-2.5 text-main font-semibold focus:outline-none focus:border-emerald-500">
          </div>
          <div>
            <label class="block font-bold text-muted uppercase mb-1">Posting District *</label>
            <select id="admin-reg-district" required class="w-full bg-input border border-theme rounded-xl px-3.5 py-2.5 text-main font-semibold focus:outline-none focus:border-emerald-500">
              <!-- Populated dynamically -->
            </select>
          </div>
          <div>
            <label class="block font-bold text-muted uppercase mb-1">ABDM HPR ID *</label>
            <input type="text" id="admin-reg-hpr" required placeholder="e.g. HPR_18492" class="w-full bg-input border border-theme rounded-xl px-3.5 py-2.5 text-main font-mono uppercase focus:outline-none focus:border-emerald-500">
          </div>
          <div>
            <label class="block font-bold text-muted uppercase mb-1">TN Medical Council Reg No *</label>
            <input type="text" id="admin-reg-council" required placeholder="e.g. TNMC/2021/84920" class="w-full bg-input border border-theme rounded-xl px-3.5 py-2.5 text-main font-mono uppercase focus:outline-none focus:border-emerald-500">
          </div>
          <div>
            <label class="block font-bold text-muted uppercase mb-1">Qualification *</label>
            <select id="admin-reg-qual" required class="w-full bg-input border border-theme rounded-xl px-3 py-2.5 text-main font-semibold focus:outline-none">
              <option value="MBBS">MBBS</option>
              <option value="MD Gen Medicine">MD General Medicine</option>
              <option value="MD Cardiology">MD Cardiology</option>
              <option value="MS Orthopedics">MS Orthopedics</option>
              <option value="MS Obstetrics">MS Obstetrics & Gynecology</option>
              <option value="DM Neurology">DM Neurology</option>
            </select>
          </div>
          <div>
            <label class="block font-bold text-muted uppercase mb-1">Specialization *</label>
            <select id="admin-reg-dept" required class="w-full bg-input border border-theme rounded-xl px-3 py-2.5 text-main font-semibold focus:outline-none">
              <option value="Cardiology">Cardiology</option>
              <option value="Neurology">Neurology</option>
              <option value="Orthopedics">Orthopedics</option>
              <option value="General Medicine">General Medicine</option>
              <option value="Pediatrics">Pediatrics</option>
              <option value="Gynecology">Gynecology</option>
            </select>
          </div>
          <div class="md:col-span-2">
            <label class="block font-bold text-muted uppercase mb-1">Employment Seeking Status *</label>
            <select id="admin-reg-status" required class="w-full bg-input border border-theme rounded-xl px-3.5 py-2.5 text-main font-semibold focus:outline-none">
              <option value="Unemployed / Seeking Posting">Unemployed / Seeking Govt Hospital Posting</option>
              <option value="Locum / Temporary">Locum / Temporary Substitute Clinician</option>
              <option value="Private Practitioner Seeking Public Panel">Private Practitioner Seeking Public OPD Panel</option>
            </select>
          </div>
        </div>

        <div class="pt-3 flex items-center justify-end gap-3 border-t border-theme">
          <button type="button" onclick="closeAdminDoctorRegModal()" class="px-4 py-2.5 rounded-xl border border-theme text-muted font-bold hover:bg-input">Cancel</button>
          <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold px-6 py-2.5 rounded-xl shadow-lg">Submit Application to Verification Table</button>
        </div>
      </form>

    </div>
  </div>

  <!-- HOSPITAL ATTACHMENT SUB-MODAL -->
  <div id="hospital-attach-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[10050] hidden flex items-center justify-center p-4">
    <div class="bg-card border border-theme rounded-3xl p-6 max-w-md w-full shadow-2xl relative anti-gravity-card">
      <button onclick="closeHospitalAttachModal()" class="absolute top-5 right-5 text-muted hover:text-main text-lg font-bold">✕</button>
      
      <div class="flex items-center gap-2.5 mb-4">
        <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-lg font-bold">
          <i class="fa-solid fa-hospital-user"></i>
        </div>
        <div>
          <h3 class="font-extrabold text-main text-base">Assign Hospital Placement</h3>
          <p class="text-xs text-muted">Select active hospital in <span id="attach-district-name" class="text-emerald-400 font-bold">District</span></p>
        </div>
      </div>

      <div class="space-y-4 text-xs">
        <div>
          <label class="block font-bold text-muted uppercase mb-1.5">Target Hospital Facility *</label>
          <select id="attach-hospital-select" class="w-full bg-input border border-theme rounded-xl px-4 py-3 text-main font-semibold focus:outline-none focus:border-emerald-500">
            <!-- Populated dynamically by app.js -->
          </select>
        </div>

        <div>
          <label class="block font-bold text-muted uppercase mb-1.5">Generated Doctor Code</label>
          <input type="text" id="attach-doc-code" readonly class="w-full bg-input/70 border border-theme rounded-xl px-4 py-2.5 text-emerald-400 font-mono font-bold" value="D1090">
        </div>

        <div class="pt-3 flex items-center justify-end gap-3 border-t border-theme">
          <button type="button" onclick="closeHospitalAttachModal()" class="px-4 py-2.5 rounded-xl border border-theme text-muted font-bold hover:bg-input">Cancel</button>
          <button type="button" onclick="confirmHospitalAttachment()" class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold px-5 py-2.5 rounded-xl shadow-lg">Confirm Placement & Activate</button>
        </div>
      </div>

    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      checkAdminSession();
      if (typeof renderDoctorApplicationsTable === 'function') renderDoctorApplicationsTable();
    });
  </script>
</body>
</html>
