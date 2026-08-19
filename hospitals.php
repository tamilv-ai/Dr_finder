<?php
include 'db_connect.php';

$search = $_GET['search'] ?? '';
$district = $_GET['district'] ?? '';
$is_near = $_GET['near'] ?? false;
$lat = $_GET['lat'] ?? null;
$lon = $_GET['lon'] ?? null;

$hospitals_list = [];

if ($db_connected) {
    $sql = "SELECT * FROM hospitals WHERE 1=1";
    $params = [];
    $types = "";

    if (!empty($search)) {
        $sql .= " AND (hospital_name LIKE ? OR specialty LIKE ?)";
        $searchTerm = "%" . $search . "%";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $types .= "ss";
    }

    if (!empty($district)) {
        $sql .= " AND district = ?";
        $params[] = $district;
        $types .= "s";
    }

    $stmt = $conn->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $hospitals_list[] = $row;
    }
}

// Fallback seed data if DB connection is unavailable or initial empty result
if (empty($hospitals_list) && !$db_connected) {
    $fallback_data = [
        ['hospital_name' => 'Govt Headquarters Hospital Ariyalur', 'district' => 'Ariyalur', 'specialty' => 'General Medicine, Emergency Trauma', 'address' => 'Jayankondam Road, Ariyalur - 621704', 'contact' => '+91 4329 220 231', 'lat' => 11.1401, 'lon' => 79.0782],
        ['hospital_name' => 'Chengalpattu Govt Medical College Hospital', 'district' => 'Chengalpattu', 'specialty' => 'Multi-Specialty, Intensive Care, Trauma', 'address' => 'GST Road, Chengalpattu - 603001', 'contact' => '+91 44 2742 6566', 'lat' => 12.6841, 'lon' => 79.9836],
        ['hospital_name' => 'Rajiv Gandhi Government General Hospital (RGGGH)', 'district' => 'Chennai', 'specialty' => 'Cardiology, Trauma & Intensive Care', 'address' => 'EVR Periyar Salai, Park Town, Chennai - 600003', 'contact' => '+91 44 2530 5000', 'lat' => 13.0827, 'lon' => 80.2707],
        ['hospital_name' => 'Govt Stanley Medical College Hospital', 'district' => 'Chennai', 'specialty' => 'Plastic Surgery, Emergency Care', 'address' => 'Old Jail Road, Royapuram, Chennai - 600013', 'contact' => '+91 44 2528 1351', 'lat' => 13.1075, 'lon' => 80.2872],
        ['hospital_name' => 'Coimbatore Medical College Hospital', 'district' => 'Coimbatore', 'specialty' => 'Cardiology, Orthopedics, Intensive Care', 'address' => 'Trichy Road, Coimbatore - 641018', 'contact' => '+91 422 230 1393', 'lat' => 11.0168, 'lon' => 76.9558],
        ['hospital_name' => 'Cuddalore Govt Headquarters Hospital', 'district' => 'Cuddalore', 'specialty' => 'General Surgery, Pediatrics, Emergency Unit', 'address' => 'Manjakuppam, Cuddalore - 607001', 'contact' => '+91 4142 230 331', 'lat' => 11.7480, 'lon' => 79.7714],
        ['hospital_name' => 'Dharmapuri Medical College Hospital', 'district' => 'Dharmapuri', 'specialty' => 'Trauma Care, General Medicine, Orthopedics', 'address' => 'Netaji Bypass Road, Dharmapuri - 636701', 'contact' => '+91 4342 233 255', 'lat' => 12.1357, 'lon' => 78.1560],
        ['hospital_name' => 'Dindigul Govt Headquarters Hospital', 'district' => 'Dindigul', 'specialty' => 'Obstetrics, Pediatrics, Emergency Center', 'address' => 'Sub Collector Office Road, Dindigul - 624001', 'contact' => '+91 451 242 3200', 'lat' => 10.3673, 'lon' => 77.9803],
        ['hospital_name' => 'Govt Medical College Hospital Perundurai', 'district' => 'Erode', 'specialty' => 'Respiratory Medicine, General Medicine', 'address' => 'Perundurai, Erode - 638053', 'contact' => '+91 424 253 3302', 'lat' => 11.3410, 'lon' => 77.7172],
        ['hospital_name' => 'Kallakurichi Govt Headquarters Hospital', 'district' => 'Kallakurichi', 'specialty' => 'Emergency Care, General Surgery', 'address' => 'Kachirapalayam Road, Kallakurichi - 606202', 'contact' => '+91 4151 222 340', 'lat' => 11.7384, 'lon' => 78.9639],
        ['hospital_name' => 'Kanchipuram District Headquarters Hospital', 'district' => 'Kancheepuram', 'specialty' => 'Emergency Care, Obstetrics & Gynecology', 'address' => 'Railway Station Road, Kanchipuram - 631501', 'contact' => '+91 44 2722 2434', 'lat' => 12.8342, 'lon' => 79.7036],
        ['hospital_name' => 'Govt Headquarters Hospital Kanyakumari', 'district' => 'Kanyakumari', 'specialty' => 'Emergency Care, General Surgery', 'address' => 'Asaripallam, Nagercoil - 629001', 'contact' => '+91 4652 230 461', 'lat' => 8.1833, 'lon' => 77.4119],
        ['hospital_name' => 'Govt Medical College Hospital Karur', 'district' => 'Karur', 'specialty' => 'General Medicine, Trauma Unit', 'address' => 'Gandhigramam, Karur - 639004', 'contact' => '+91 4324 225 300', 'lat' => 10.9601, 'lon' => 78.0766],
        ['hospital_name' => 'Krishnagiri Govt Headquarters Hospital', 'district' => 'Krishnagiri', 'specialty' => 'Emergency Care, Orthopedics', 'address' => 'Royakottai Road, Krishnagiri - 635001', 'contact' => '+91 4343 232 400', 'lat' => 12.5186, 'lon' => 78.2137],
        ['hospital_name' => 'Madurai Govt Rajaji Hospital', 'district' => 'Madurai', 'specialty' => 'Multi-Specialty, Pediatrics, Cardiology', 'address' => 'Panagal Road, Shenoy Nagar, Madurai - 625020', 'contact' => '+91 452 253 2535', 'lat' => 9.9252, 'lon' => 78.1198],
        ['hospital_name' => 'Mayiladuthurai Govt District Hospital', 'district' => 'Mayiladuthurai', 'specialty' => 'General Medicine, Emergency Surgery', 'address' => 'Hospital Road, Mayiladuthurai - 609001', 'contact' => '+91 4364 222 320', 'lat' => 11.1018, 'lon' => 79.6525],
        ['hospital_name' => 'Nagapattinam Govt Medical College Hospital', 'district' => 'Nagapattinam', 'specialty' => 'Multi-Specialty, Emergency Unit', 'address' => 'Public Office Road, Nagapattinam - 611001', 'contact' => '+91 4365 222 300', 'lat' => 10.7672, 'lon' => 79.8449],
        ['hospital_name' => 'Govt Medical College Hospital Namakkal', 'district' => 'Namakkal', 'specialty' => 'General Surgery, Cardiology', 'address' => 'Collectorate Complex, Namakkal - 637003', 'contact' => '+91 4286 221 400', 'lat' => 11.2189, 'lon' => 78.1674],
        ['hospital_name' => 'Ooty Govt District Headquarters Hospital', 'district' => 'Nilgiris', 'specialty' => 'Hill Emergency Unit, Respiratory Care', 'address' => 'Hospital Road, Ooty - 643001', 'contact' => '+91 423 244 2212', 'lat' => 11.4102, 'lon' => 76.6950],
        ['hospital_name' => 'Perambalur Govt Headquarters Hospital', 'district' => 'Perambalur', 'specialty' => 'Emergency Care, General Medicine', 'address' => 'Trichy Main Road, Perambalur - 621212', 'contact' => '+91 4328 277 300', 'lat' => 11.2342, 'lon' => 78.8820],
        ['hospital_name' => 'Govt Pudukkottai Medical College Hospital', 'district' => 'Pudukkottai', 'specialty' => 'Multi-Specialty, ICU, Pediatrics', 'address' => 'Mulamangalam, Pudukkottai - 622004', 'contact' => '+91 4322 221 500', 'lat' => 10.3833, 'lon' => 78.8001],
        ['hospital_name' => 'Ramanathapuram Govt Medical College Hospital', 'district' => 'Ramanathapuram', 'specialty' => 'Emergency Surgery, Cardiology', 'address' => 'Rameswaram Road, Ramanathapuram - 623501', 'contact' => '+91 4567 230 400', 'lat' => 9.3639, 'lon' => 78.8395],
        ['hospital_name' => 'Ranipet Govt District Hospital', 'district' => 'Ranipet', 'specialty' => 'General Medicine, Emergency Trauma', 'address' => 'MBT Road, Ranipet - 632401', 'contact' => '+91 4172 272 500', 'lat' => 12.9296, 'lon' => 79.3331],
        ['hospital_name' => 'Govt Mohan Kumaramangalam Medical College Hospital', 'district' => 'Salem', 'specialty' => 'Neurology, Oncology, Emergency Ward', 'address' => 'Steel Plant Road, Salem - 636030', 'contact' => '+91 427 226 0204', 'lat' => 11.6643, 'lon' => 78.1460],
        ['hospital_name' => 'Sivaganga Govt Medical College Hospital', 'district' => 'Sivaganga', 'specialty' => 'Multi-Specialty, Pediatrics, ICU', 'address' => 'Melavaniyangudi, Sivaganga - 630562', 'contact' => '+91 4575 240 600', 'lat' => 9.8433, 'lon' => 78.4809],
        ['hospital_name' => 'Tenkasi Govt District Headquarters Hospital', 'district' => 'Tenkasi', 'specialty' => 'Emergency Care, General Surgery', 'address' => 'Railway Feeder Road, Tenkasi - 627811', 'contact' => '+91 4633 222 400', 'lat' => 8.9593, 'lon' => 77.3150],
        ['hospital_name' => 'Thanjavur Medical College Hospital', 'district' => 'Thanjavur', 'specialty' => 'Cardiology, Neurosurgery, Critical Care', 'address' => 'Medical College Road, Thanjavur - 613004', 'contact' => '+91 4362 240 011', 'lat' => 10.7870, 'lon' => 79.1378],
        ['hospital_name' => 'Govt Theni Medical College Hospital', 'district' => 'Theni', 'specialty' => 'Multi-Specialty, Emergency Unit', 'address' => 'K.Vellaimalaimedu, Theni - 625512', 'contact' => '+91 4546 263 700', 'lat' => 10.0104, 'lon' => 77.4768],
        ['hospital_name' => 'Thoothukudi Govt Medical College Hospital', 'district' => 'Thoothukudi', 'specialty' => 'Cardiology, Pediatrics, Intensive Care', 'address' => '3rd Mile, Thoothukudi - 628008', 'contact' => '+91 461 239 2200', 'lat' => 8.7642, 'lon' => 78.1348],
        ['hospital_name' => 'Govt KAP Viswanatham Medical College Hospital', 'district' => 'Tiruchirappalli', 'specialty' => 'Gastroenterology, Orthopedics', 'address' => 'Periyamilaguparai, Tiruchirappalli - 620001', 'contact' => '+91 431 240 1011', 'lat' => 10.7905, 'lon' => 78.7047],
        ['hospital_name' => 'Tirunelveli Medical College Hospital', 'district' => 'Tirunelveli', 'specialty' => 'Nephrology, Pediatrics, Cardiology', 'address' => 'High Ground, Tirunelveli - 627011', 'contact' => '+91 462 257 2733', 'lat' => 8.7139, 'lon' => 77.7567],
        ['hospital_name' => 'Tirupathur Govt Headquarters Hospital', 'district' => 'Tirupathur', 'specialty' => 'General Surgery, Emergency Care', 'address' => 'GH Road, Tirupathur - 635601', 'contact' => '+91 4179 220 300', 'lat' => 12.4929, 'lon' => 78.5686],
        ['hospital_name' => 'Govt Medical College Hospital Tiruppur', 'district' => 'Tiruppur', 'specialty' => 'Multi-Specialty, Intensive Care', 'address' => 'Palani Road, Tiruppur - 641604', 'contact' => '+91 421 224 2000', 'lat' => 11.1085, 'lon' => 77.3411],
        ['hospital_name' => 'Govt Medical College Hospital Tiruvallur', 'district' => 'Tiruvallur', 'specialty' => 'General Medicine, Emergency Trauma', 'address' => 'CVD Road, Tiruvallur - 602001', 'contact' => '+91 44 2766 0300', 'lat' => 13.1432, 'lon' => 79.9070],
        ['hospital_name' => 'Tiruvannamalai Govt Medical College Hospital', 'district' => 'Tiruvannamalai', 'specialty' => 'Nephrology, Pediatrics, Trauma Center', 'address' => 'Outer Ring Road, Tiruvannamalai - 606604', 'contact' => '+91 4175 222 500', 'lat' => 12.2253, 'lon' => 79.0747],
        ['hospital_name' => 'Govt Tiruvarur Medical College Hospital', 'district' => 'Tiruvarur', 'specialty' => 'Multi-Specialty, Emergency Surgery', 'address' => 'Thandalai, Tiruvarur - 610004', 'contact' => '+91 4366 228 000', 'lat' => 10.7726, 'lon' => 79.6365],
        ['hospital_name' => 'Vellore Government Medical College Hospital', 'district' => 'Vellore', 'specialty' => 'Pulmonology, General Surgery, ICU', 'address' => 'Adukkamparai, Vellore - 632011', 'contact' => '+91 416 226 0900', 'lat' => 12.9165, 'lon' => 79.1325],
        ['hospital_name' => 'Govt Villupuram Medical College Hospital', 'district' => 'Viluppuram', 'specialty' => 'Multi-Specialty, Emergency Unit', 'address' => 'Mundiyampakkam, Villupuram - 605602', 'contact' => '+91 4146 232 500', 'lat' => 11.9401, 'lon' => 79.4861],
        ['hospital_name' => 'Govt Medical College Hospital Virudhunagar', 'district' => 'Virudhunagar', 'specialty' => 'General Medicine, Intensive Care', 'address' => 'Desigapuram, Virudhunagar - 626001', 'contact' => '+91 4562 243 500', 'lat' => 9.5680, 'lon' => 77.9624]
    ];

    $hospitals_list = array_filter($fallback_data, function($item) use ($search, $district) {
        $matchesSearch = empty($search) || stripos($item['hospital_name'], $search) !== false || stripos($item['specialty'], $search) !== false;
        $matchesDist = empty($district) || strcasecmp($item['district'], $district) === 0;
        return $matchesSearch && $matchesDist;
    });
}
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Hospitals Directory – MedPulse TN</title>
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
      <span class="text-xl font-extrabold text-main tracking-tight">MedPulse<span class="text-emerald-400">.Hospitals</span></span>
      <span class="text-xs text-muted font-mono tracking-widest uppercase animate-pulse">Filtering Live Infrastructure...</span>
    </div>
  </div>

  <!-- TOP HEADER NAVBAR -->
  <nav class="sticky top-0 z-40 border-b border-theme bg-card px-4 md:px-8 py-3.5 shadow-sm">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
      
      <a href="index.php" class="flex items-center gap-2.5">
        <div class="w-9 h-9 rounded-xl bg-emerald-500/15 border border-emerald-500/30 flex items-center justify-center font-extrabold text-emerald-400 text-lg">🏥</div>
        <div>
          <span class="text-lg font-extrabold tracking-tight text-main">MedPulse<span class="text-emerald-400">.Hospitals</span></span>
          <span class="text-[10px] bg-emerald-500/10 text-emerald-400 px-2 py-0.5 rounded-full font-bold ml-1.5 border border-emerald-500/20">38 DISTRICTS</span>
        </div>
      </a>

      <div class="flex items-center gap-2.5">
        <a href="index.php" class="text-xs font-semibold text-muted hover:text-emerald-400 px-3 py-1.5">Home</a>
        <a href="blood_bank.php" class="text-xs font-semibold text-muted hover:text-emerald-400 px-3 py-1.5">Blood Bank</a>
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
        <div>
          <p class="text-[11px] font-extrabold uppercase text-emerald-400 tracking-wider mb-2">🌐 Navigation</p>
          <div class="space-y-1 text-sm font-semibold">
            <a href="index.php" class="drawer-item">Home Dashboard</a>
            <a href="hospitals.php" class="drawer-item">Hospital Directory</a>
            <a href="blood_bank.php" class="drawer-item">Blood Bank Stocks</a>
            <a href="map.php" class="drawer-item">Live Interactive Map</a>
          </div>
        </div>
      </div>
    </div>
  </aside>

  <!-- CONTENT SECTION -->
  <section class="px-4 md:px-8 pt-8 pb-12 max-w-7xl mx-auto w-full flex-grow">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
      <div>
        <h1 class="text-2xl font-extrabold text-main tracking-tight">Hospital Directory</h1>
        <p class="text-xs text-muted mt-1">Verified emergency units and clinical facilities across Tamil Nadu districts.</p>
      </div>

      <a href="index.php" class="inline-flex items-center gap-2 text-xs font-bold bg-input hover:bg-card text-main px-4 py-2 rounded-xl border border-theme self-start md:self-auto">
        <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
      </a>
    </div>

    <?php if ($is_near): ?>
      <div class="mb-6 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-semibold flex items-center gap-2">
        <i class="fa-solid fa-location-dot"></i>
        <span>Showing hospital centers nearest to your detected GPS location coordinates.</span>
      </div>
    <?php endif; ?>

    <!-- HOSPITAL CARDS GRID -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php if (!empty($hospitals_list)): ?>
        <?php foreach ($hospitals_list as $row): ?>
          <div class="bg-card border border-theme rounded-2xl p-5 hover:border-emerald-500/40 transition-all shadow-xl flex flex-col justify-between anti-gravity-card">
            <div>
              <div class="flex items-center justify-between gap-2 mb-3">
                <span class="text-[10px] bg-emerald-500/10 text-emerald-400 px-2.5 py-0.5 rounded-full font-bold border border-emerald-500/20">
                  📍 <?= htmlspecialchars($row['district'] ?? 'Tamil Nadu') ?>
                </span>
                <span class="text-[10px] bg-blue-500/10 text-blue-400 px-2 py-0.5 rounded font-mono border border-blue-500/20">
                  VERIFIED
                </span>
              </div>

              <h3 class="font-extrabold text-main text-base mb-2 leading-snug">
                <?= htmlspecialchars($row['hospital_name'] ?? 'Medical Center') ?>
              </h3>

              <p class="text-xs text-muted mb-3 flex items-start gap-1.5">
                <i class="fa-solid fa-location-dot text-red-400 mt-0.5"></i>
                <span><?= htmlspecialchars($row['address'] ?? 'Address unavailable') ?></span>
              </p>

              <div class="space-y-1 text-xs border-t border-theme pt-3 text-muted">
                <p><strong class="text-main">Specialty:</strong> <?= htmlspecialchars($row['specialty'] ?? 'General Emergency') ?></p>
                <p><strong class="text-main">Contact:</strong> <?= htmlspecialchars($row['contact'] ?? 'N/A') ?></p>
              </div>
            </div>

            <div class="mt-4 pt-3 border-t border-theme">
              <a href="map.php?dest=<?= urlencode($row['hospital_name']) ?>&lat=<?= $row['lat'] ?? '' ?>&lon=<?= $row['lon'] ?? '' ?>" class="w-full bg-input hover:bg-emerald-500 hover:text-slate-950 text-main font-bold py-2.5 px-4 rounded-xl flex items-center justify-center gap-2 text-xs transition-all border border-theme">
                <i class="fa-solid fa-diamond-turn-right"></i> Get Directions & Live Map
              </a>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="col-span-full text-center py-16 bg-card border border-theme rounded-2xl">
          <i class="fa-solid fa-hospital-user text-4xl text-muted mb-3"></i>
          <p class="text-muted font-bold text-sm">No hospitals found matching your search criteria.</p>
          <a href="hospitals.php" class="inline-block bg-emerald-500 text-slate-950 font-bold px-5 py-2.5 rounded-xl text-xs mt-4">Reset Filters</a>
        </div>
      <?php endif; ?>
    </div>

  </section>

  <!-- FOOTER -->
  <footer class="border-t border-theme py-6 px-4 md:px-8 bg-card text-center text-xs text-muted">
    <p>MedPulse TN — Hospital & Emergency Infrastructure Directory</p>
  </footer>

  <script src="./app.js"></script>
</body>
</html>
