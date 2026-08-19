<?php
include 'db_connect.php';

$district_filter = $_GET['district'] ?? '';
$blood_group = $_GET['blood_group'] ?? '';
$search_filter = $_GET['search'] ?? '';

$blood_list = [];

if ($db_connected) {
    $sql = "SELECT * FROM blood_bank WHERE 1=1";
    $params = [];
    $types = "";

    if (!empty($district_filter)) {
        $sql .= " AND district = ?";
        $params[] = $district_filter;
        $types .= "s";
    }

    if (!empty($blood_group)) {
        $sql .= " AND blood_group = ?";
        $params[] = $blood_group;
        $types .= "s";
    }

    if (!empty($search_filter)) {
        $sql .= " AND (blood_bank_name LIKE ? OR district LIKE ? OR address LIKE ?)";
        $like_search = "%$search_filter%";
        $params[] = $like_search;
        $params[] = $like_search;
        $params[] = $like_search;
        $types .= "sss";
    }

    $stmt = $conn->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $blood_list[] = $row;
    }
}

// Fallback seed data if DB connection is unavailable
if (empty($blood_list) && !$db_connected) {
    $fallback_data = [
        ['blood_bank_name' => 'Rajiv Gandhi Govt General Hospital (RGGGH)', 'district' => 'Chennai', 'address' => 'Park Town, Chennai', 'blood_group' => 'A+', 'units' => 28, 'contact' => '044-25305000'],
        ['blood_bank_name' => 'Stanley Govt Hospital', 'district' => 'Chennai', 'address' => 'Royapuram, Chennai', 'blood_group' => 'O-', 'units' => 8, 'contact' => '044-25281351'],
        ['blood_bank_name' => 'Kilpauk Medical College (KMC)', 'district' => 'Chennai', 'address' => 'Kilpauk, Chennai', 'blood_group' => 'B+', 'units' => 25, 'contact' => '044-28364951'],
        ['blood_bank_name' => 'Govt Kasturba Gandhi Hospital', 'district' => 'Chennai', 'address' => 'Triplicane, Chennai', 'blood_group' => 'O+', 'units' => 34, 'contact' => '044-28545222'],
        ['blood_bank_name' => 'Govt Peripheral Hospital', 'district' => 'Chennai', 'address' => 'Tondiarpet, Chennai', 'blood_group' => 'A+', 'units' => 12, 'contact' => '044-25912525'],
        ['blood_bank_name' => 'Govt Peripheral Hospital', 'district' => 'Chennai', 'address' => 'Anna Nagar, Chennai', 'blood_group' => 'B+', 'units' => 22, 'contact' => '044-26211234'],
        ['blood_bank_name' => 'Chengalpattu Govt Medical College', 'district' => 'Chengalpattu', 'address' => 'Chengalpattu', 'blood_group' => 'O+', 'units' => 35, 'contact' => '044-27426566'],
        ['blood_bank_name' => 'Govt Kanchipuram HQ Hospital', 'district' => 'Kanchipuram', 'address' => 'Kanchipuram', 'blood_group' => 'A+', 'units' => 14, 'contact' => '044-27222255'],
        ['blood_bank_name' => 'Govt Thiruvallur HQ Hospital', 'district' => 'Tiruvallur', 'address' => 'Thiruvallur', 'blood_group' => 'O+', 'units' => 22, 'contact' => '044-27660380'],
        ['blood_bank_name' => 'Vellore Govt Medical College', 'district' => 'Vellore', 'address' => 'Adukkamparai, Vellore', 'blood_group' => 'B+', 'units' => 30, 'contact' => '0416-2260900'],
        ['blood_bank_name' => 'Coimbatore Govt Medical College', 'district' => 'Coimbatore', 'address' => 'Trichy Road, Coimbatore', 'blood_group' => 'O+', 'units' => 50, 'contact' => '0422-2301393'],
        ['blood_bank_name' => 'Govt Erode HQ Hospital', 'district' => 'Erode', 'address' => 'Erode', 'blood_group' => 'A+', 'units' => 17, 'contact' => '0424-2258352'],
        ['blood_bank_name' => 'Govt Mohan Kumaramangalam Medical College', 'district' => 'Salem', 'address' => 'Salem', 'blood_group' => 'B+', 'units' => 29, 'contact' => '0427-2383313'],
        ['blood_bank_name' => 'Govt Tiruppur Medical College', 'district' => 'Tiruppur', 'address' => 'Tiruppur', 'blood_group' => 'O+', 'units' => 33, 'contact' => '0421-2242151'],
        ['blood_bank_name' => 'Govt Namakkal HQ Hospital', 'district' => 'Namakkal', 'address' => 'Namakkal', 'blood_group' => 'A+', 'units' => 13, 'contact' => '04286-220800'],
        ['blood_bank_name' => 'Govt Dharmapuri Medical College', 'district' => 'Dharmapuri', 'address' => 'Dharmapuri', 'blood_group' => 'O+', 'units' => 27, 'contact' => '04342-230890'],
        ['blood_bank_name' => 'Govt Krishnagiri Medical College', 'district' => 'Krishnagiri', 'address' => 'Krishnagiri', 'blood_group' => 'B+', 'units' => 22, 'contact' => '04343-232200'],
        ['blood_bank_name' => 'Govt Nilgiris HQ Hospital', 'district' => 'Nilgiris', 'address' => 'Ooty, Nilgiris', 'blood_group' => 'O+', 'units' => 18, 'contact' => '044-2442212'],
        ['blood_bank_name' => 'Tiruchirappalli MGM Govt Hospital', 'district' => 'Trichy', 'address' => 'Puthur, Trichy', 'blood_group' => 'A+', 'units' => 27, 'contact' => '0431-2410111'],
        ['blood_bank_name' => 'Govt Thanjavur Medical College', 'district' => 'Thanjavur', 'address' => 'Thanjavur', 'blood_group' => 'O+', 'units' => 37, 'contact' => '04362-240011'],
        ['blood_bank_name' => 'Govt Karur Medical College', 'district' => 'Karur', 'address' => 'Karur', 'blood_group' => 'B+', 'units' => 17, 'contact' => '04324-220100'],
        ['blood_bank_name' => 'Govt Cuddalore HQ Hospital', 'district' => 'Cuddalore', 'address' => 'Cuddalore', 'blood_group' => 'A+', 'units' => 16, 'contact' => '04142-230350'],
        ['blood_bank_name' => 'Govt Villupuram Medical College', 'district' => 'Viluppuram', 'address' => 'Mundiyampakkam, Villupuram', 'blood_group' => 'O+', 'units' => 36, 'contact' => '04146-232500'],
        ['blood_bank_name' => 'Govt Nagapattinam Medical College', 'district' => 'Nagapattinam', 'address' => 'Nagapattinam', 'blood_group' => 'B+', 'units' => 15, 'contact' => '04365-222300'],
        ['blood_bank_name' => 'Govt Pudukkottai Medical College', 'district' => 'Pudukkottai', 'address' => 'Pudukkottai', 'blood_group' => 'O+', 'units' => 26, 'contact' => '04322-221500'],
        ['blood_bank_name' => 'Govt Rajaji Hospital (GRH)', 'district' => 'Madurai', 'address' => 'Panagal Road, Madurai', 'blood_group' => 'O+', 'units' => 48, 'contact' => '0452-2532536'],
        ['blood_bank_name' => 'Govt Tirunelveli Medical College', 'district' => 'Tirunelveli', 'address' => 'High Ground, Tirunelveli', 'blood_group' => 'A+', 'units' => 25, 'contact' => '0462-2572733'],
        ['blood_bank_name' => 'Kanyakumari Govt Medical College', 'district' => 'Kanyakumari', 'address' => 'Asaripallam, Nagercoil', 'blood_group' => 'B+', 'units' => 23, 'contact' => '04652-223201'],
        ['blood_bank_name' => 'Govt Dindigul HQ Hospital', 'district' => 'Dindigul', 'address' => 'Dindigul', 'blood_group' => 'O+', 'units' => 27, 'contact' => '0451-2423200'],
        ['blood_bank_name' => 'Govt Virudhunagar Medical College', 'district' => 'Virudhunagar', 'address' => 'Virudhunagar', 'blood_group' => 'A+', 'units' => 17, 'contact' => '04562-243500'],
        ['blood_bank_name' => 'Govt Theni Medical College', 'district' => 'Theni', 'address' => 'Kanavu Vilakku, Theni', 'blood_group' => 'B+', 'units' => 22, 'contact' => '04546-244500'],
        ['blood_bank_name' => 'Govt Ramanathapuram Medical College', 'district' => 'Ramanathapuram', 'address' => 'Ramanathapuram', 'blood_group' => 'O+', 'units' => 23, 'contact' => '04567-220300'],
        ['blood_bank_name' => 'Govt Sivagangai Medical College', 'district' => 'Sivaganga', 'address' => 'Sivagangai', 'blood_group' => 'A+', 'units' => 14, 'contact' => '04575-240200'],
        ['blood_bank_name' => 'Govt Thoothukudi Medical College', 'district' => 'Thoothukudi', 'address' => 'Thoothukudi', 'blood_group' => 'O+', 'units' => 38, 'contact' => '0461-2321000'],
        ['blood_bank_name' => 'Govt Tenkasi HQ Hospital', 'district' => 'Tenkasi', 'address' => 'Tenkasi', 'blood_group' => 'B+', 'units' => 14, 'contact' => '04633-222100']
    ];

    $blood_list = array_filter($fallback_data, function($item) use ($district_filter, $blood_group, $search_filter) {
        $matchesDist = empty($district_filter) || strcasecmp($item['district'], $district_filter) === 0;
        $matchesBG = empty($blood_group) || strcasecmp($item['blood_group'], $blood_group) === 0;
        $matchesSearch = empty($search_filter) || (
            stripos($item['blood_bank_name'], $search_filter) !== false ||
            stripos($item['district'], $search_filter) !== false ||
            stripos($item['address'], $search_filter) !== false
        );
        return $matchesDist && $matchesBG && $matchesSearch;
    });
}
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Blood Bank Directory – MedPulse TN</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="./styles.css" />
</head>
<body class="min-h-screen flex flex-col justify-between">

  <!-- ANTI-GRAVITY GLASSMORPHIC PRELOADER -->
  <div id="app-preloader">
    <div class="floating-logo flex flex-col items-center gap-3 text-center">
      <div class="w-16 h-16 rounded-2xl bg-red-500/20 border-2 border-red-500/40 flex items-center justify-center font-extrabold text-red-500 text-2xl shadow-2xl">
        🩸
      </div>
      <span class="text-xl font-extrabold text-main tracking-tight">MedPulse<span class="text-red-500">.Blood</span></span>
      <span class="text-xs text-muted font-mono tracking-widest uppercase animate-pulse">Scanning Blood Reserves...</span>
    </div>
  </div>

  <!-- TOP HEADER NAVBAR -->
  <nav class="sticky top-0 z-40 border-b border-theme bg-card px-4 md:px-8 py-3.5 shadow-sm">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
      
      <a href="index.php" class="flex items-center gap-2.5">
        <div class="w-9 h-9 rounded-xl bg-red-500/15 border border-red-500/30 flex items-center justify-center font-extrabold text-red-500 text-lg">🩸</div>
        <div>
          <span class="text-lg font-extrabold tracking-tight text-main">MedPulse<span class="text-red-500">.Blood</span></span>
          <span class="text-[10px] bg-red-500/10 text-red-400 px-2 py-0.5 rounded-full font-bold ml-1.5 border border-red-500/20">TN BLOOD DIRECTORY</span>
        </div>
      </a>

      <div class="flex items-center gap-2.5">
        <a href="index.php" class="text-xs font-semibold text-muted hover:text-red-400 px-3 py-1.5">Home</a>
        <a href="hospitals.php" class="text-xs font-semibold text-muted hover:text-red-400 px-3 py-1.5">Hospitals</a>
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
          <p class="text-[11px] font-extrabold uppercase text-red-400 tracking-wider mb-2">🌐 Navigation</p>
          <div class="space-y-1 text-sm font-semibold">
            <a href="index.php" class="drawer-item">Home Dashboard</a>
            <a href="queue.php" class="drawer-item">Live OPD Queue Status</a>
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

  <!-- CONTENT SECTION -->
  <section class="px-4 md:px-8 pt-8 pb-12 max-w-7xl mx-auto w-full flex-grow">
    
    <div class="text-center max-w-3xl mx-auto mb-8">
      <h1 class="text-3xl font-extrabold text-main mb-2">35 Regional Blood Banks</h1>
      <p class="text-muted text-xs md:text-sm">Filter real-time blood group availability across Tamil Nadu district blood centers.</p>
    </div>

    <!-- FILTER FORM -->
    <form method="GET" action="blood_bank.php" class="max-w-3xl mx-auto mb-8 space-y-4">
      
      <!-- SEARCH & MAP NAVIGATION BAR -->
      <div class="bg-card border border-theme p-3 md:p-4 rounded-2xl shadow-xl flex flex-col sm:flex-row gap-3 items-stretch sm:items-center max-w-3xl mx-auto mb-6 anti-gravity-card">
        
        <!-- Search Input -->
        <input 
          type="text" 
          name="search"
          placeholder="Search blood bank name, city, or location..." 
          class="w-full bg-input border border-theme text-main px-4 py-3 rounded-xl text-sm focus:outline-none focus:border-red-500 sm:flex-1"
          value="<?= htmlspecialchars($search_filter) ?>"
          onchange="this.form.submit()"
        />

        <!-- District Selector -->
        <select name="district" onchange="this.form.submit()" class="bg-input border border-theme text-main text-xs px-3 py-3 rounded-xl focus:outline-none focus:border-red-500">
          <option value="">All 38 Districts</option>
          <option value="ariyalur" <?= ($district_filter == 'ariyalur') ? 'selected' : '' ?>>Ariyalur</option>
          <option value="chengalpattu" <?= ($district_filter == 'chengalpattu') ? 'selected' : '' ?>>Chengalpattu</option>
          <option value="chennai" <?= ($district_filter == 'chennai') ? 'selected' : '' ?>>Chennai</option>
          <option value="coimbatore" <?= ($district_filter == 'coimbatore') ? 'selected' : '' ?>>Coimbatore</option>
          <option value="cuddalore" <?= ($district_filter == 'cuddalore') ? 'selected' : '' ?>>Cuddalore</option>
          <option value="dharmapuri" <?= ($district_filter == 'dharmapuri') ? 'selected' : '' ?>>Dharmapuri</option>
          <option value="dindigul" <?= ($district_filter == 'dindigul') ? 'selected' : '' ?>>Dindigul</option>
          <option value="erode" <?= ($district_filter == 'erode') ? 'selected' : '' ?>>Erode</option>
          <option value="kallakurichi" <?= ($district_filter == 'kallakurichi') ? 'selected' : '' ?>>Kallakurichi</option>
          <option value="kanchipuram" <?= ($district_filter == 'kanchipuram') ? 'selected' : '' ?>>Kancheepuram</option>
          <option value="kanyakumari" <?= ($district_filter == 'kanyakumari') ? 'selected' : '' ?>>Kanyakumari</option>
          <option value="karur" <?= ($district_filter == 'karur') ? 'selected' : '' ?>>Karur</option>
          <option value="krishnagiri" <?= ($district_filter == 'krishnagiri') ? 'selected' : '' ?>>Krishnagiri</option>
          <option value="madurai" <?= ($district_filter == 'madurai') ? 'selected' : '' ?>>Madurai</option>
          <option value="mayiladuthurai" <?= ($district_filter == 'mayiladuthurai') ? 'selected' : '' ?>>Mayiladuthurai</option>
          <option value="nagapattinam" <?= ($district_filter == 'nagapattinam') ? 'selected' : '' ?>>Nagapattinam</option>
          <option value="namakkal" <?= ($district_filter == 'namakkal') ? 'selected' : '' ?>>Namakkal</option>
          <option value="nilgiris" <?= ($district_filter == 'nilgiris') ? 'selected' : '' ?>>Nilgiris</option>
          <option value="perambalur" <?= ($district_filter == 'perambalur') ? 'selected' : '' ?>>Perambalur</option>
          <option value="pudukkottai" <?= ($district_filter == 'pudukkottai') ? 'selected' : '' ?>>Pudukkottai</option>
          <option value="ramanathapuram" <?= ($district_filter == 'ramanathapuram') ? 'selected' : '' ?>>Ramanathapuram</option>
          <option value="ranipet" <?= ($district_filter == 'ranipet') ? 'selected' : '' ?>>Ranipet</option>
          <option value="salem" <?= ($district_filter == 'salem') ? 'selected' : '' ?>>Salem</option>
          <option value="sivaganga" <?= ($district_filter == 'sivaganga') ? 'selected' : '' ?>>Sivaganga</option>
          <option value="tenkasi" <?= ($district_filter == 'tenkasi') ? 'selected' : '' ?>>Tenkasi</option>
          <option value="thanjavur" <?= ($district_filter == 'thanjavur') ? 'selected' : '' ?>>Thanjavur</option>
          <option value="theni" <?= ($district_filter == 'theni') ? 'selected' : '' ?>>Theni</option>
          <option value="thoothukudi" <?= ($district_filter == 'thoothukudi') ? 'selected' : '' ?>>Thoothukudi</option>
          <option value="trichy" <?= ($district_filter == 'trichy') ? 'selected' : '' ?>>Tiruchirappalli</option>
          <option value="tirunelveli" <?= ($district_filter == 'tirunelveli') ? 'selected' : '' ?>>Tirunelveli</option>
          <option value="tirupathur" <?= ($district_filter == 'tirupathur') ? 'selected' : '' ?>>Tirupathur</option>
          <option value="tiruppur" <?= ($district_filter == 'tiruppur') ? 'selected' : '' ?>>Tiruppur</option>
          <option value="tiruvallur" <?= ($district_filter == 'tiruvallur') ? 'selected' : '' ?>>Tiruvallur</option>
          <option value="tiruvannamalai" <?= ($district_filter == 'tiruvannamalai') ? 'selected' : '' ?>>Tiruvannamalai</option>
          <option value="tiruvarur" <?= ($district_filter == 'tiruvarur') ? 'selected' : '' ?>>Tiruvarur</option>
          <option value="vellore" <?= ($district_filter == 'vellore') ? 'selected' : '' ?>>Vellore</option>
          <option value="viluppuram" <?= ($district_filter == 'viluppuram') ? 'selected' : '' ?>>Viluppuram</option>
          <option value="virudhunagar" <?= ($district_filter == 'virudhunagar') ? 'selected' : '' ?>>Virudhunagar</option>
        </select>

        <!-- View on Live Map Button -->
        <a href="map.php" id="map-btn" onclick="redirectToMap(event, 'map.php')" class="map-btn bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs px-4 py-3 rounded-xl shadow-md transition-all flex items-center justify-center gap-1.5 whitespace-nowrap shrink-0">
          🗺️ View on Live Map
        </a>

      </div>

      <!-- BLOOD GROUP PILLS -->
      <div class="bg-card border border-theme p-4 rounded-2xl shadow-xl space-y-4 anti-gravity-card">
        <div>
          <label class="block text-xs font-semibold text-muted uppercase mb-2">Filter by Blood Group</label>
          <input type="hidden" name="blood_group" id="blood_group_input" value="<?= htmlspecialchars($blood_group) ?>">
          <div class="flex flex-wrap gap-2">
            <button type="button" onclick="selectBloodGroupPHP('')" class="blood-pill <?= empty($blood_group) ? 'active px-3.5 py-1.5 rounded-xl text-xs font-bold border border-red-500 bg-red-600 text-white' : 'border border-theme bg-input text-main hover:border-red-500' ?> transition-all">ALL</button>
            <?php foreach (['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg): ?>
              <button type="button" onclick="selectBloodGroupPHP('<?= $bg ?>')" class="blood-pill <?= ($blood_group == $bg) ? 'active px-3.5 py-1.5 rounded-xl text-xs font-bold border border-red-500 bg-red-600 text-white' : 'border border-theme bg-input text-main hover:border-red-500' ?> transition-all"><?= $bg ?></button>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

    </form>

    <!-- BLOOD BANK GRID -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php if (!empty($blood_list)): ?>
        <?php foreach ($blood_list as $row): ?>
          <div class="bg-card border border-theme rounded-2xl p-5 hover:border-red-500/40 transition-all shadow-xl flex flex-col justify-between anti-gravity-card">
            <div>
              <div class="flex items-center justify-between gap-2 mb-3">
                <span class="text-[10px] bg-red-500/10 text-red-400 px-2.5 py-0.5 rounded-full font-bold border border-red-500/20">
                  📍 <?= htmlspecialchars($row['district'] ?? 'Tamil Nadu') ?>
                </span>
                <span class="text-[10px] bg-emerald-500/10 text-emerald-400 px-2 py-0.5 rounded-full font-semibold border border-emerald-500/20">
                  LIVE STOCK
                </span>
              </div>

              <h3 class="font-extrabold text-main text-base mb-1">
                <?= htmlspecialchars($row['blood_bank_name'] ?? 'Blood Bank') ?>
              </h3>
              
              <p class="text-xs text-muted mb-4"><i class="fa-solid fa-location-dot text-red-400 me-1"></i> <?= htmlspecialchars($row['address'] ?? 'Address unavailable') ?></p>

              <div class="bg-input border border-theme rounded-2xl p-4 text-center my-2">
                <span class="text-xs text-muted uppercase font-bold tracking-wider">Group</span>
                <div class="text-3xl font-extrabold text-red-500 my-1 flex items-center justify-center gap-1.5">
                  <i class="fa-solid fa-droplet"></i> <?= htmlspecialchars($row['blood_group'] ?? 'N/A') ?>
                </div>
                <div class="text-xs font-bold text-main mt-1">
                  <?= htmlspecialchars($row['units'] ?? '0') ?> <span class="text-muted font-normal">Units Available</span>
                </div>
              </div>
            </div>

            <div class="pt-3 border-t border-theme flex items-center justify-between text-xs mt-4">
              <span class="text-muted"><i class="fa-solid fa-phone me-1"></i> <?= htmlspecialchars($row['contact'] ?? 'N/A') ?></span>
              <a href="tel:<?= htmlspecialchars($row['contact'] ?? '') ?>" class="text-red-500 font-bold hover:underline">Call Center</a>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="col-span-full text-center py-16 bg-card border border-theme rounded-2xl">
          <i class="fa-solid fa-droplet-slash text-4xl text-muted mb-3"></i>
          <p class="text-muted font-bold text-sm">No matching blood availability units found.</p>
          <a href="blood_bank.php" class="inline-block bg-red-600 text-white font-bold px-5 py-2.5 rounded-xl text-xs mt-4">Reset Filters</a>
        </div>
      <?php endif; ?>
    </div>

  </section>

  <!-- FOOTER -->
  <footer class="border-t border-theme py-6 px-4 md:px-8 bg-card text-center text-xs text-muted">
    <p>MedPulse TN — Blood Bank Reserve Directory</p>
  </footer>

  <script src="./app.js"></script>
  <script>
    function selectBloodGroupPHP(group) {
      document.getElementById('blood_group_input').value = group;
      document.getElementById('blood_group_input').form.submit();
    }
  </script>
</body>
</html>
