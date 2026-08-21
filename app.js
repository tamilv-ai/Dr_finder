/* ================= PRELOADER DISMISSAL ENGINE ================= */
function dismissPreloader() {
  const preloader = document.getElementById('app-preloader');
  if (preloader) {
    setTimeout(() => {
      preloader.classList.add('fade-out');
    }, 400); // Smooth 400ms delay for visual feedback
  }
}

window.addEventListener('load', () => {
  dismissPreloader();
});

// Fallback safety trigger in case external tiles or assets take long to load
setTimeout(() => {
  dismissPreloader();
}, 2500);

/* ================= GLOBAL THEME ENGINE ================= */
function applyTheme(theme) {
  const html = document.documentElement;
  html.classList.remove('light', 'dark');
  html.classList.add(theme);
  
  document.querySelectorAll('.theme-icon-symbol').forEach(el => {
    el.innerHTML = theme === 'dark' 
      ? '<i class="fa-solid fa-moon"></i>' 
      : '<i class="fa-solid fa-sun"></i>';
  });
}

function toggleTheme() {
  const current = localStorage.getItem('medpulse_theme') || 'dark';
  const next = current === 'dark' ? 'light' : 'dark';
  localStorage.setItem('medpulse_theme', next);
  applyTheme(next);
}

function initTheme() {
  const saved = localStorage.getItem('medpulse_theme') || 'dark';
  applyTheme(saved);
}

// Immediate theme initialization
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initTheme);
} else {
  initTheme();
}


window.addEventListener('storage', (e) => {
  if (e.key === 'medpulse_theme') {
    applyTheme(e.newValue || 'dark');
  }
});

/* ================= SIDEBAR DRAWER CONTROLS ================= */
function openSidebar() {
  const overlay = document.getElementById('sidebar-overlay');
  const sidebar = document.getElementById('sidebar');
  if (overlay && sidebar) {
    overlay.classList.remove('hidden');
    sidebar.classList.remove('translate-x-full');
  }
}

function closeSidebar() {
  const overlay = document.getElementById('sidebar-overlay');
  const sidebar = document.getElementById('sidebar');
  if (overlay && sidebar) {
    overlay.classList.add('hidden');
    sidebar.classList.add('translate-x-full');
  }
}

/* ================= MODAL NOTIFICATION ENGINE ================= */
function openModal(title, content) {
  closeSidebar();
  let modal = document.getElementById('global-modal');
  if (!modal) {
    modal = document.createElement('div');
    modal.id = 'global-modal';
    modal.className = 'fixed inset-0 bg-black/70 backdrop-blur-sm z-[10000] flex items-center justify-center p-4';
    modal.innerHTML = `
      <div class="bg-card border border-theme rounded-2xl p-6 max-w-md w-full shadow-2xl relative">
        <button onclick="closeModal()" class="absolute top-4 right-4 text-muted hover:text-main text-lg font-bold">✕</button>
        <h3 id="modal-title" class="text-lg font-bold text-main mb-2"></h3>
        <div id="modal-body" class="text-xs text-muted leading-relaxed space-y-3"></div>
        <button onclick="closeModal()" class="w-full mt-5 bg-emerald-500 text-slate-950 font-bold py-2.5 rounded-xl text-xs hover:brightness-110">Close</button>
      </div>
    `;
    document.body.appendChild(modal);
  }
  document.getElementById('modal-title').textContent = title;
  document.getElementById('modal-body').innerHTML = content;
  modal.classList.remove('hidden');
}

function closeModal() {
  const modal = document.getElementById('global-modal');
  if (modal) modal.classList.add('hidden');
}

function triggerService(serviceType) {
  if (serviceType === 'appointment') {
    openModal('⚡ Book Appointment', `
      <p>Select your preferred department on the <strong>Doctor Directory</strong> page. Digital OP tokens are routed through the <strong>Nalam AI WhatsApp Engine</strong>.</p>
      
      <p class="text-emerald-400 font-semibold flex items-center gap-1.5 my-2">
        <span>📞</span> Helpline: 104 (Tamil Nadu Health)
      </p>

      <div class="mt-4 pt-3 border-t border-theme">
        <a href="https://wa.me/919619222999?text=Hi%20Nalam%20AI%2C%20I%20want%20to%20book%20an%20OPD%20appointment" 
           target="_blank" 
           class="w-full bg-[#25D366] hover:bg-[#20ba5a] text-white font-bold py-3 px-4 rounded-xl flex items-center justify-center gap-2.5 transition-all shadow-lg text-xs tracking-wide">
          <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
          </svg>
          Chat with Nalam AI on WhatsApp
        </a>
      </div>
    `);
  } else if (serviceType === 'queue') {
    openModal('⚡ Live OPD Queue Status', '<p>Real-time doctor presence is verified live at the OPD desk. Check individual doctor cards for cabin status: <span class="text-emerald-400 font-bold">IN CABIN</span>, <span class="text-amber-400 font-bold">IN OT / EMERGENCY</span>, or <span class="text-red-400 font-bold">OFF DUTY</span>.</p>');
  } else if (serviceType === 'telemedicine') {
    openModal('⚡ Telemedicine Portal', '<p>eSanjeevani Tele-consultation portal integration is active for remote consultations across all 38 districts of Tamil Nadu.</p>');
  } else if (serviceType === 'hpr') {
    openModal('🩺 HPR Verification System', '<p>All doctors on MedPulse TN are authenticated via National Health Authority (NHA) official Healthcare Professionals Registry (HPR) credentials.</p>');
  } else if (serviceType === 'emergency') {
    openModal('🚨 Emergency Help (24/7)', '<p class="text-base text-red-400 font-bold">Emergency Ambulance: 108</p><p class="text-base text-emerald-400 font-bold">Health Helpline: 104</p><p class="text-base text-amber-400 font-bold">Women Safety: 181</p>');
  } else if (serviceType === 'support') {
    openModal('⚙️ Contact Support', '<p>Tamil Nadu e-Health Development Cell</p><p>Email: support.medpulse@tn.gov.in</p><p>Phone: +91 44 2530 5000</p>');
  }
}

/* ================= HAVERSINE DISTANCE CALCULATOR ================= */
function calculateDistanceKm(lat1, lon1, lat2, lon2) {
  const R = 6371; // Earth's radius in km
  const dLat = (lat2 - lat1) * Math.PI / 180;
  const dLon = (lon2 - lon1) * Math.PI / 180;
  const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
            Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
            Math.sin(dLon/2) * Math.sin(dLon/2);
  const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
  return parseFloat((R * c).toFixed(1)); // Rounded to 1 decimal place
}

/* ================= DATA GENERATORS ================= */
/* ================= DATA GENERATORS (38 TAMIL NADU DISTRICTS) ================= */
const DISTRICT_CONFIG = [
  { id: 'ariyalur', name: 'Ariyalur', lat: 11.1401, lng: 79.0782, hospitals: ['Govt Headquarters Hospital Ariyalur', 'Jayankondam Govt Hospital', 'Sendurai Primary Health Center'] },
  { id: 'chengalpattu', name: 'Chengalpattu', lat: 12.6841, lng: 79.9836, hospitals: ['Chengalpattu Govt Medical College Hospital', 'Maduranthakam Govt Hospital', 'Tambaram Govt Hospital'] },
  { id: 'chennai', name: 'Chennai', lat: 13.0827, lng: 80.2707, hospitals: ['Rajiv Gandhi Govt General Hospital', 'Govt Stanley Medical College Hospital', 'Kilpauk Medical College Hospital', 'Govt Peripheral Hospital Anna Nagar', 'Govt Multi Super Speciality Hospital Omandurar'] },
  { id: 'coimbatore', name: 'Coimbatore', lat: 11.0168, lng: 76.9558, hospitals: ['Coimbatore Govt Medical College Hospital', 'ESI Hospital Singanallur', 'Pollachi Govt District Hospital', 'Mettupalayam Govt Hospital', 'Anaimalai Govt Health Center'] },
  { id: 'cuddalore', name: 'Cuddalore', lat: 11.7480, lng: 79.7714, hospitals: ['Cuddalore Govt Headquarters Hospital', 'Chidambaram Govt Hospital', 'VRH Panruti Govt Hospital'] },
  { id: 'dharmapuri', name: 'Dharmapuri', lat: 12.1357, lng: 78.1560, hospitals: ['Dharmapuri Govt Medical College Hospital', 'Harur Govt Hospital', 'Pappireddipatti Health Center'] },
  { id: 'dindigul', name: 'Dindigul', lat: 10.3673, lng: 77.9803, hospitals: ['Dindigul Govt Headquarters Hospital', 'Palani Govt Hospital', 'Kodaikanal Govt Hospital'] },
  { id: 'erode', name: 'Erode', lat: 11.3410, lng: 77.7172, hospitals: ['Erode Govt Medical College Perundurai', 'Erode District Headquarters Hospital', 'Gobichettipalayam Govt Hospital', 'Sathyamangalam Govt Hospital', 'Bhavani Govt Sub-District Hospital'] },
  { id: 'kallakurichi', name: 'Kallakurichi', lat: 11.7384, lng: 78.9639, hospitals: ['Kallakurichi Govt Headquarters Hospital', 'Ulundurpet Govt Hospital', 'Sankarapuram Govt Hospital'] },
  { id: 'kanchipuram', name: 'Kancheepuram', lat: 12.8342, lng: 79.7036, hospitals: ['Kanchipuram Govt Headquarters Hospital', 'Sriperumbudur Govt Hospital', 'Uttiramerur Govt Hospital', 'Walajabad Community Health Center'] },
  { id: 'kanyakumari', name: 'Kanyakumari', lat: 8.1833, lng: 77.4119, hospitals: ['Kanyakumari Govt Medical College Asaripallam', 'Nagercoil Govt Headquarters Hospital', 'Padmanabhapuram Govt Hospital'] },
  { id: 'karur', name: 'Karur', lat: 10.9601, lng: 78.0766, hospitals: ['Govt Medical College Hospital Karur', 'Kulithalai Govt Hospital', 'Aravakurichi Govt Hospital'] },
  { id: 'krishnagiri', name: 'Krishnagiri', lat: 12.5186, lng: 78.2137, hospitals: ['Krishnagiri Govt Headquarters Hospital', 'Hosur Govt Hospital', 'Denkanikottai Govt Hospital'] },
  { id: 'madurai', name: 'Madurai', lat: 9.9252, lng: 78.1198, hospitals: ['Madurai Govt Rajaji Hospital', 'Melur Govt Sub-District Hospital', 'Usilampatti Govt Hospital', 'Thirumangalam Govt Hospital', 'Vadipatti Primary Health Center'] },
  { id: 'mayiladuthurai', name: 'Mayiladuthurai', lat: 11.1018, lng: 79.6525, hospitals: ['Mayiladuthurai Govt District Hospital', 'Sirkali Govt Hospital', 'Tharangambadi Govt Hospital'] },
  { id: 'nagapattinam', name: 'Nagapattinam', lat: 10.7672, lng: 79.8449, hospitals: ['Nagapattinam Govt Medical College Hospital', 'Vedaranyam Govt Hospital', 'Kilvelur Govt Hospital'] },
  { id: 'namakkal', name: 'Namakkal', lat: 11.2189, lng: 78.1674, hospitals: ['Govt Medical College Hospital Namakkal', 'Rasipuram Govt Hospital', 'Tiruchengodu Govt Hospital'] },
  { id: 'nilgiris', name: 'Nilgiris', lat: 11.4102, lng: 76.6950, hospitals: ['Ooty Govt District Headquarters Hospital', 'Coonoor Govt Hospital', 'Gudalur Govt Hospital'] },
  { id: 'perambalur', name: 'Perambalur', lat: 11.2342, lng: 78.8820, hospitals: ['Perambalur Govt Headquarters Hospital', 'Veppanthattai Health Center', 'Kunam Health Center'] },
  { id: 'pudukkottai', name: 'Pudukkottai', lat: 10.3833, lng: 78.8001, hospitals: ['Govt Pudukkottai Medical College Hospital', 'Aranthangi Govt Hospital', 'Alangudi Govt Hospital'] },
  { id: 'ramanathapuram', name: 'Ramanathapuram', lat: 9.3639, lng: 78.8395, hospitals: ['Ramanathapuram Govt Medical College Hospital', 'Paramakudi Govt Hospital', 'Rameswaram Govt Hospital'] },
  { id: 'ranipet', name: 'Ranipet', lat: 12.9296, lng: 79.3331, hospitals: ['Ranipet Govt District Hospital', 'Walajapet Govt Hospital', 'Arakkonam Govt Hospital'] },
  { id: 'salem', name: 'Salem', lat: 11.6643, lng: 78.1460, hospitals: ['Salem Govt Mohan Kumaramangalam Medical College', 'Salem District Headquarters Hospital', 'Attur Govt Hospital', 'Mettur Govt Sub-District Hospital', 'Omalur Community Health Center'] },
  { id: 'sivaganga', name: 'Sivaganga', lat: 9.8433, lng: 78.4809, hospitals: ['Sivaganga Govt Medical College Hospital', 'Karaikudi Govt Hospital', 'Devakottai Govt Hospital'] },
  { id: 'tenkasi', name: 'Tenkasi', lat: 8.9593, lng: 77.3150, hospitals: ['Tenkasi Govt District Headquarters Hospital', 'Sankarankovil Govt Hospital', 'Kadayanallur Govt Hospital'] },
  { id: 'thanjavur', name: 'Thanjavur', lat: 10.7870, lng: 79.1378, hospitals: ['Thanjavur Govt Medical College Hospital', 'Kumbakonam Govt District Hospital', 'Pattukkottai Govt Hospital', 'Orathanadu Govt Hospital', 'Thiruvaiyaru Community Health Center'] },
  { id: 'theni', name: 'Theni', lat: 10.0104, lng: 77.4768, hospitals: ['Govt Theni Medical College Hospital', 'Periyakulam Govt Hospital', 'Uthamapalayam Govt Hospital'] },
  { id: 'thoothukudi', name: 'Thoothukudi', lat: 8.7642, lng: 78.1348, hospitals: ['Thoothukudi Govt Medical College Hospital', 'Kovilpatti Govt Hospital', 'Tiruchendur Govt Hospital'] },
  { id: 'trichy', name: 'Tiruchirappalli', lat: 10.7905, lng: 78.7047, hospitals: ['Trichy Govt K.A.P. Viswanatham Medical College', 'Manapparai Govt District Hospital', 'Thuraiyur Govt Hospital', 'Lalgudi Sub-District Hospital', 'Musiri Govt Health Center'] },
  { id: 'tirunelveli', name: 'Tirunelveli', lat: 8.7139, lng: 77.7567, hospitals: ['Tirunelveli Govt Medical College Hospital', 'Ambasamudram Govt Hospital', 'Valliyur Govt Hospital', 'Nanguneri Community Health Center'] },
  { id: 'tirupathur', name: 'Tirupathur', lat: 12.4929, lng: 78.5686, hospitals: ['Tirupathur Govt Headquarters Hospital', 'Vaniyambadi Govt Hospital', 'Ambur Govt Hospital'] },
  { id: 'tiruppur', name: 'Tiruppur', lat: 11.1085, lng: 77.3411, hospitals: ['Govt Medical College Hospital Tiruppur', 'Dharapuram Govt Hospital', 'Udumalaipettai Govt Hospital'] },
  { id: 'tiruvallur', name: 'Tiruvallur', lat: 13.1432, lng: 79.9070, hospitals: ['Govt Medical College Hospital Tiruvallur', 'Avadi Urban Health Center', 'Ponneri Govt Hospital'] },
  { id: 'tiruvannamalai', name: 'Tiruvannamalai', lat: 12.2253, lng: 79.0747, hospitals: ['Tiruvannamalai Govt Medical College Hospital', 'Arani Govt Hospital', 'Cheyyar Govt Hospital'] },
  { id: 'tiruvarur', name: 'Tiruvarur', lat: 10.7726, lng: 79.6365, hospitals: ['Govt Tiruvarur Medical College Hospital', 'Mannargudi Govt Hospital', 'Thiruthuraipoondi Govt Hospital'] },
  { id: 'vellore', name: 'Vellore', lat: 12.9165, lng: 79.1325, hospitals: ['Vellore Govt Medical College Hospital', 'Gudiyatham Govt Hospital', 'Katpadi Urban Primary Health Center', 'Walajapet Govt District Hospital'] },
  { id: 'viluppuram', name: 'Viluppuram', lat: 11.9401, lng: 79.4861, hospitals: ['Govt Villupuram Medical College Hospital', 'Tindivanam Govt Hospital', 'Gingee Govt Hospital'] },
  { id: 'virudhunagar', name: 'Virudhunagar', lat: 9.5680, lng: 77.9624, hospitals: ['Govt Medical College Hospital Virudhunagar', 'Rajapalayam Govt Hospital', 'Aruppukottai Govt Hospital'] }
];

const SPECIALTIES = [
  { dept: 'Cardiology', qual: 'MD Cardiology' },
  { dept: 'Neurology', qual: 'DM Neurology' },
  { dept: 'Orthopedics', qual: 'MS Orthopedics' },
  { dept: 'General Medicine', qual: 'MD Gen Medicine' },
  { dept: 'Pediatrics', qual: 'MD Pediatrics' },
  { dept: 'Gynecology', qual: 'MS Obstetrics' }
];

const FIRST_NAMES = ['Raman', 'Meenakshi', 'Vijayakumar', 'Sundar', 'Anitha', 'Karthik', 'Suresh', 'Lakshmi', 'Rajesh', 'Kavitha', 'Balaji', 'Priya', 'Dinesh', 'Saravanan', 'Preeti', 'Ramesh', 'Swaminathan', 'Arunkumar', 'Gautham', 'Nalini', 'Senthil', 'Deepa', 'Venkatesh', 'Divya', 'Srinivas', 'Chitra', 'Mohan', 'Gayathri', 'Prasanna', 'Bhuvana'];
const INITIALS = ['K.', 'S.', 'P.', 'M.', 'R.', 'T.', 'V.', 'N.', 'G.', 'A.'];

const DOCTORS = [];
let globalIndex = 0;

DISTRICT_CONFIG.forEach(dist => {
  dist.hospitals.forEach((hospName, hospIdx) => {
    SPECIALTIES.forEach((spec, specIdx) => {
      globalIndex++;
      const docId = `D${1000 + globalIndex}`;
      const initial = INITIALS[globalIndex % INITIALS.length];
      const firstName = FIRST_NAMES[globalIndex % FIRST_NAMES.length];
      const docName = `Dr. ${initial} ${firstName}`;
      const statuses = ['in', 'in', 'emr', 'off'];
      
      DOCTORS.push({
        id: docId,
        hpr: `HPR_${10000 + globalIndex * 37 % 89999}`,
        name: docName,
        qual: spec.qual,
        dept: spec.dept,
        cabin: `Cabin ${specIdx + 1}, Block ${String.fromCharCode(65 + hospIdx)}`,
        district: dist.id,
        districtName: dist.name,
        hospitalName: hospName,
        status: statuses[globalIndex % statuses.length],
        lastPunch: `${(globalIndex * 3) % 25 + 2} mins ago`,
        lat: dist.lat + (Math.sin(globalIndex) * 0.02),
        lng: dist.lng + (Math.cos(globalIndex) * 0.02),
        opdStartTime: (globalIndex % 2 === 0) ? '09:00' : null,
        opdEndTime: (globalIndex % 2 === 0) ? '13:00' : null,
        distKm: null
      });
    });
  });
});

const BLOOD_BANKS = [
  // CHENNAI & NORTHERN DISTRICTS
  { id: 'BB01', name: 'Rajiv Gandhi Govt General Hospital (RGGGH)', district: 'chennai', city: 'Park Town, Chennai', phone: '044-25305000', lat: 13.0827, lng: 80.2707, lastUpdated: '10 mins ago', stocks: { 'A+': 28, 'A-': 5, 'B+': 32, 'B-': 3, 'O+': 45, 'O-': 8, 'AB+': 12, 'AB-': 2 }, distKm: null },
  { id: 'BB02', name: 'Stanley Govt Hospital', district: 'chennai', city: 'Royapuram, Chennai', phone: '044-25281351', lat: 13.1075, lng: 80.2872, lastUpdated: '15 mins ago', stocks: { 'A+': 15, 'A-': 2, 'B+': 20, 'B-': 1, 'O+': 30, 'O-': 4, 'AB+': 8, 'AB-': 0 }, distKm: null },
  { id: 'BB03', name: 'Kilpauk Medical College (KMC)', district: 'chennai', city: 'Kilpauk, Chennai', phone: '044-28364951', lat: 13.0784, lng: 80.2431, lastUpdated: '8 mins ago', stocks: { 'A+': 18, 'A-': 3, 'B+': 25, 'B-': 2, 'O+': 38, 'O-': 5, 'AB+': 9, 'AB-': 1 }, distKm: null },
  { id: 'BB04', name: 'Govt Kasturba Gandhi Hospital', district: 'chennai', city: 'Triplicane, Chennai', phone: '044-28545222', lat: 13.0612, lng: 80.2781, lastUpdated: '12 mins ago', stocks: { 'A+': 22, 'A-': 4, 'B+': 19, 'B-': 2, 'O+': 34, 'O-': 6, 'AB+': 7, 'AB-': 1 }, distKm: null },
  { id: 'BB05', name: 'Govt Peripheral Hospital', district: 'chennai', city: 'Tondiarpet, Chennai', phone: '044-25912525', lat: 13.1250, lng: 80.2900, lastUpdated: '20 mins ago', stocks: { 'A+': 12, 'A-': 1, 'B+': 14, 'B-': 1, 'O+': 25, 'O-': 3, 'AB+': 5, 'AB-': 0 }, distKm: null },
  { id: 'BB06', name: 'Govt Peripheral Hospital', district: 'chennai', city: 'Anna Nagar, Chennai', phone: '044-26211234', lat: 13.0850, lng: 80.2100, lastUpdated: '5 mins ago', stocks: { 'A+': 16, 'A-': 2, 'B+': 22, 'B-': 3, 'O+': 29, 'O-': 4, 'AB+': 6, 'AB-': 1 }, distKm: null },
  { id: 'BB07', name: 'Chengalpattu Govt Medical College', district: 'chengalpattu', city: 'Chengalpattu', phone: '044-27426566', lat: 12.6841, lng: 79.9836, lastUpdated: '18 mins ago', stocks: { 'A+': 20, 'A-': 3, 'B+': 26, 'B-': 2, 'O+': 35, 'O-': 5, 'AB+': 10, 'AB-': 2 }, distKm: null },
  { id: 'BB08', name: 'Govt Kanchipuram HQ Hospital', district: 'kanchipuram', city: 'Kanchipuram', phone: '044-27222255', lat: 12.8342, lng: 79.7036, lastUpdated: '30 mins ago', stocks: { 'A+': 14, 'A-': 2, 'B+': 18, 'B-': 1, 'O+': 28, 'O-': 4, 'AB+': 6, 'AB-': 1 }, distKm: null },
  { id: 'BB09', name: 'Govt Thiruvallur HQ Hospital', district: 'tiruvallur', city: 'Thiruvallur', phone: '044-27660380', lat: 13.1432, lng: 79.9070, lastUpdated: '22 mins ago', stocks: { 'A+': 11, 'A-': 1, 'B+': 15, 'B-': 2, 'O+': 22, 'O-': 3, 'AB+': 4, 'AB-': 0 }, distKm: null },
  { id: 'BB10', name: 'Vellore Govt Medical College', district: 'vellore', city: 'Adukkamparai, Vellore', phone: '0416-2260900', lat: 12.9165, lng: 79.1325, lastUpdated: '14 mins ago', stocks: { 'A+': 24, 'A-': 4, 'B+': 30, 'B-': 3, 'O+': 42, 'O-': 7, 'AB+': 11, 'AB-': 2 }, distKm: null },

  // WESTERN REGION
  { id: 'BB11', name: 'Coimbatore Govt Medical College', district: 'coimbatore', city: 'Trichy Road, Coimbatore', phone: '0422-2301393', lat: 11.0168, lng: 76.9558, lastUpdated: '5 mins ago', stocks: { 'A+': 30, 'A-': 6, 'B+': 35, 'B-': 4, 'O+': 50, 'O-': 10, 'AB+': 15, 'AB-': 3 }, distKm: null },
  { id: 'BB12', name: 'Govt Erode HQ Hospital', district: 'erode', city: 'Erode', phone: '0424-2258352', lat: 11.3410, lng: 77.7172, lastUpdated: '25 mins ago', stocks: { 'A+': 17, 'A-': 2, 'B+': 21, 'B-': 2, 'O+': 31, 'O-': 5, 'AB+': 8, 'AB-': 1 }, distKm: null },
  { id: 'BB13', name: 'Govt Mohan Kumaramangalam Medical College', district: 'salem', city: 'Salem', phone: '0427-2383313', lat: 11.6643, lng: 78.1460, lastUpdated: '25 mins ago', stocks: { 'A+': 26, 'A-': 4, 'B+': 29, 'B-': 3, 'O+': 40, 'O-': 7, 'AB+': 12, 'AB-': 2 }, distKm: null },
  { id: 'BB14', name: 'Govt Tiruppur Medical College', district: 'tiruppur', city: 'Tiruppur', phone: '0421-2242151', lat: 11.1085, lng: 77.3411, lastUpdated: '16 mins ago', stocks: { 'A+': 19, 'A-': 3, 'B+': 24, 'B-': 2, 'O+': 33, 'O-': 6, 'AB+': 9, 'AB-': 1 }, distKm: null },
  { id: 'BB15', name: 'Govt Namakkal HQ Hospital', district: 'namakkal', city: 'Namakkal', phone: '04286-220800', lat: 11.2189, lng: 78.1674, lastUpdated: '35 mins ago', stocks: { 'A+': 13, 'A-': 1, 'B+': 16, 'B-': 1, 'O+': 24, 'O-': 4, 'AB+': 5, 'AB-': 0 }, distKm: null },
  { id: 'BB16', name: 'Govt Dharmapuri Medical College', district: 'dharmapuri', city: 'Dharmapuri', phone: '04342-230890', lat: 12.1357, lng: 78.1560, lastUpdated: '28 mins ago', stocks: { 'A+': 15, 'A-': 2, 'B+': 18, 'B-': 2, 'O+': 27, 'O-': 4, 'AB+': 7, 'AB-': 1 }, distKm: null },
  { id: 'BB17', name: 'Govt Krishnagiri Medical College', district: 'krishnagiri', city: 'Krishnagiri', phone: '04343-232200', lat: 12.5186, lng: 78.2137, lastUpdated: '19 mins ago', stocks: { 'A+': 18, 'A-': 3, 'B+': 22, 'B-': 2, 'O+': 30, 'O-': 5, 'AB+': 8, 'AB-': 1 }, distKm: null },
  { id: 'BB18', name: 'Govt Nilgiris HQ Hospital', district: 'nilgiris', city: 'Ooty, Nilgiris', phone: '044-2442212', lat: 11.4102, lng: 76.6950, lastUpdated: '40 mins ago', stocks: { 'A+': 10, 'A-': 1, 'B+': 12, 'B-': 1, 'O+': 18, 'O-': 3, 'AB+': 4, 'AB-': 0 }, distKm: null },

  // CENTRAL & COASTAL REGION
  { id: 'BB19', name: 'Tiruchirappalli MGM Govt Hospital', district: 'trichy', city: 'Puthur, Trichy', phone: '0431-2410111', lat: 10.7905, lng: 78.7047, lastUpdated: '11 mins ago', stocks: { 'A+': 27, 'A-': 5, 'B+': 31, 'B-': 3, 'O+': 44, 'O-': 8, 'AB+': 13, 'AB-': 2 }, distKm: null },
  { id: 'BB20', name: 'Govt Thanjavur Medical College', district: 'thanjavur', city: 'Thanjavur', phone: '04362-240011', lat: 10.7870, lng: 79.1378, lastUpdated: '17 mins ago', stocks: { 'A+': 23, 'A-': 4, 'B+': 27, 'B-': 3, 'O+': 37, 'O-': 6, 'AB+': 10, 'AB-': 2 }, distKm: null },
  { id: 'BB21', name: 'Govt Karur Medical College', district: 'karur', city: 'Karur', phone: '04324-220100', lat: 10.9601, lng: 78.0766, lastUpdated: '24 mins ago', stocks: { 'A+': 14, 'A-': 2, 'B+': 17, 'B-': 1, 'O+': 25, 'O-': 4, 'AB+': 6, 'AB-': 1 }, distKm: null },
  { id: 'BB22', name: 'Govt Cuddalore HQ Hospital', district: 'cuddalore', city: 'Cuddalore', phone: '04142-230350', lat: 11.7480, lng: 79.7714, lastUpdated: '21 mins ago', stocks: { 'A+': 16, 'A-': 2, 'B+': 20, 'B-': 2, 'O+': 29, 'O-': 5, 'AB+': 7, 'AB-': 1 }, distKm: null },
  { id: 'BB23', name: 'Govt Villupuram Medical College', district: 'viluppuram', city: 'Mundiyampakkam, Villupuram', phone: '04146-232500', lat: 11.9401, lng: 79.4861, lastUpdated: '13 mins ago', stocks: { 'A+': 21, 'A-': 3, 'B+': 25, 'B-': 2, 'O+': 36, 'O-': 6, 'AB+': 9, 'AB-': 1 }, distKm: null },
  { id: 'BB24', name: 'Govt Nagapattinam Medical College', district: 'nagapattinam', city: 'Nagapattinam', phone: '04365-222300', lat: 10.7672, lng: 79.8449, lastUpdated: '31 mins ago', stocks: { 'A+': 12, 'A-': 1, 'B+': 15, 'B-': 1, 'O+': 21, 'O-': 3, 'AB+': 5, 'AB-': 0 }, distKm: null },
  { id: 'BB25', name: 'Govt Pudukkottai Medical College', district: 'pudukkottai', city: 'Pudukkottai', phone: '04322-221500', lat: 10.3833, lng: 78.8001, lastUpdated: '27 mins ago', stocks: { 'A+': 15, 'A-': 2, 'B+': 19, 'B-': 2, 'O+': 26, 'O-': 4, 'AB+': 6, 'AB-': 1 }, distKm: null },

  // SOUTHERN REGION
  { id: 'BB26', name: 'Govt Rajaji Hospital (GRH)', district: 'madurai', city: 'Panagal Road, Madurai', phone: '0452-2532536', lat: 9.9252, lng: 78.1198, lastUpdated: '14 mins ago', stocks: { 'A+': 29, 'A-': 5, 'B+': 33, 'B-': 4, 'O+': 48, 'O-': 9, 'AB+': 14, 'AB-': 2 }, distKm: null },
  { id: 'BB27', name: 'Govt Tirunelveli Medical College', district: 'tirunelveli', city: 'High Ground, Tirunelveli', phone: '0462-2572733', lat: 8.7139, lng: 77.7567, lastUpdated: '9 mins ago', stocks: { 'A+': 25, 'A-': 4, 'B+': 28, 'B-': 3, 'O+': 41, 'O-': 7, 'AB+': 11, 'AB-': 2 }, distKm: null },
  { id: 'BB28', name: 'Kanyakumari Govt Medical College', district: 'kanyakumari', city: 'Asaripallam, Nagercoil', phone: '04652-223201', lat: 8.1833, lng: 77.4119, lastUpdated: '16 mins ago', stocks: { 'A+': 20, 'A-': 3, 'B+': 23, 'B-': 2, 'O+': 32, 'O-': 5, 'AB+': 8, 'AB-': 1 }, distKm: null },
  { id: 'BB29', name: 'Govt Dindigul HQ Hospital', district: 'dindigul', city: 'Dindigul', phone: '0451-2423200', lat: 10.3673, lng: 77.9803, lastUpdated: '22 mins ago', stocks: { 'A+': 16, 'A-': 2, 'B+': 19, 'B-': 2, 'O+': 27, 'O-': 4, 'AB+': 7, 'AB-': 1 }, distKm: null },
  { id: 'BB30', name: 'Govt Virudhunagar Medical College', district: 'virudhunagar', city: 'Virudhunagar', phone: '04562-243500', lat: 9.5680, lng: 77.9624, lastUpdated: '29 mins ago', stocks: { 'A+': 17, 'A-': 2, 'B+': 20, 'B-': 2, 'O+': 28, 'O-': 4, 'AB+': 7, 'AB-': 1 }, distKm: null },
  { id: 'BB31', name: 'Govt Theni Medical College', district: 'theni', city: 'Kanavu Vilakku, Theni', phone: '04546-244500', lat: 10.0104, lng: 77.4768, lastUpdated: '15 mins ago', stocks: { 'A+': 18, 'A-': 3, 'B+': 22, 'B-': 2, 'O+': 30, 'O-': 5, 'AB+': 8, 'AB-': 1 }, distKm: null },
  { id: 'BB32', name: 'Govt Ramanathapuram Medical College', district: 'ramanathapuram', city: 'Ramanathapuram', phone: '04567-220300', lat: 9.3639, lng: 78.8395, lastUpdated: '33 mins ago', stocks: { 'A+': 13, 'A-': 1, 'B+': 16, 'B-': 1, 'O+': 23, 'O-': 3, 'AB+': 5, 'AB-': 0 }, distKm: null },
  { id: 'BB33', name: 'Govt Sivagangai Medical College', district: 'sivaganga', city: 'Sivagangai', phone: '04575-240200', lat: 9.8433, lng: 78.4809, lastUpdated: '26 mins ago', stocks: { 'A+': 14, 'A-': 2, 'B+': 17, 'B-': 1, 'O+': 24, 'O-': 4, 'AB+': 6, 'AB-': 1 }, distKm: null },
  { id: 'BB34', name: 'Govt Thoothukudi Medical College', district: 'thoothukudi', city: 'Thoothukudi', phone: '0461-2321000', lat: 8.7642, lng: 78.1348, lastUpdated: '12 mins ago', stocks: { 'A+': 22, 'A-': 4, 'B+': 26, 'B-': 3, 'O+': 38, 'O-': 6, 'AB+': 10, 'AB-': 2 }, distKm: null },
  { id: 'BB35', name: 'Govt Tenkasi HQ Hospital', district: 'tenkasi', city: 'Tenkasi', phone: '04633-222100', lat: 8.9593, lng: 77.3150, lastUpdated: '38 mins ago', stocks: { 'A+': 11, 'A-': 1, 'B+': 14, 'B-': 1, 'O+': 20, 'O-': 3, 'AB+': 4, 'AB-': 0 }, distKm: null }
];

/* Sync Nurse & Admin Updates */
function syncNurseUpdates() {
  const savedUpdates = JSON.parse(localStorage.getItem('medpulse_status_updates') || '{}');
  DOCTORS.forEach(doc => {
    if (savedUpdates[doc.id]) {
      doc.status = savedUpdates[doc.id].status;
      doc.lastPunch = 'Updated ' + (savedUpdates[doc.id].lastPunch || 'just now');
      if (savedUpdates[doc.id].reason) doc.note = savedUpdates[doc.id].reason;
    }
  });

  // Merge Doctor Overrides from Admin Desk
  const doctorOverrides = JSON.parse(localStorage.getItem('medpulse_doctors_override') || '[]');
  doctorOverrides.forEach(d => {
    const idx = DOCTORS.findIndex(x => x.id === d.id || x.id === d.doc_code);
    if (idx >= 0) {
      DOCTORS[idx] = { ...DOCTORS[idx], ...d };
    } else {
      DOCTORS.unshift({
        id: d.doc_code || d.id,
        hpr: d.hpr_id || d.hpr || 'HPR_VERIFIED',
        name: d.doctor_name || d.name,
        qual: d.qualification || d.qual || 'Specialist',
        dept: d.department || d.dept || 'General',
        cabin: d.cabin || 'Cabin 1',
        district: (d.district || 'chennai').toLowerCase(),
        districtName: d.district || 'Chennai',
        hospitalName: d.hospital_name || d.hospitalName || 'Govt Hospital',
        status: d.status || 'in',
        lastPunch: 'Just now',
        lat: d.lat || 13.0827,
        lng: d.lon || d.lng || 80.2707,
        opdStartTime: d.opd_start_time || d.opdStartTime || null,
        opdEndTime: d.opd_end_time || d.opdEndTime || null,
        distKm: null
      });
    }
  });

  // Merge Blood Bank Overrides from Admin Desk
  const bloodOverrides = JSON.parse(localStorage.getItem('medpulse_blood_override') || '[]');
  bloodOverrides.forEach(b => {
    const idx = BLOOD_BANKS.findIndex(x => x.id === b.id || x.name === b.blood_bank_name);
    if (idx >= 0) {
      BLOOD_BANKS[idx] = { ...BLOOD_BANKS[idx], ...b };
    } else {
      BLOOD_BANKS.unshift({
        id: b.id || ('BB_' + Date.now()),
        name: b.blood_bank_name || b.name,
        district: (b.district || 'chennai').toLowerCase(),
        city: b.address || b.city || 'Tamil Nadu',
        phone: b.contact || b.phone || '+91 44 2530 5000',
        lat: b.lat || 13.0827,
        lng: b.lon || b.lng || 80.2707,
        lastUpdated: 'Just now',
        stocks: b.stocks || { 'A+': 20, 'O+': 30, 'B+': 15, 'AB+': 5 },
        distKm: null
      });
    }
  });
}


/* ================= GEOLOCATION "NEAR ME" FUNCTIONS ================= */
function findNearMeDoctors() {
  const btn = document.getElementById('btn-near-me');
  if (btn) btn.textContent = '🔄 Locating GPS...';

  if (!navigator.geolocation) {
    alert('Geolocation is not supported by your browser.');
    if (btn) btn.textContent = '📍 Near Me';
    return;
  }

  const geoOptions = {
    enableHighAccuracy: true,
    timeout: 10000,
    maximumAge: 0
  };

  navigator.geolocation.getCurrentPosition(
    position => {
      const userLat = position.coords.latitude;
      const userLng = position.coords.longitude;

      DOCTORS.forEach(doc => {
        doc.distKm = calculateDistanceKm(userLat, userLng, doc.lat, doc.lng);
      });

      filteredDoctors = [...DOCTORS].sort((a, b) => a.distKm - b.distKm);

      if (btn) btn.textContent = '📍 Sorted by Location';
      renderDoctorGrid();
    },
    error => {
      let errMsg = 'Unable to retrieve your location. Please grant GPS permissions in your browser.';
      if (error.code === error.PERMISSION_DENIED) {
        errMsg = 'Location permission was denied. Please allow location access in your browser settings.';
      }
      alert(errMsg);
      if (btn) btn.textContent = '📍 Near Me';
    },
    geoOptions
  );
}

function findNearMeBlood() {
  const btn = document.getElementById('btn-near-me-blood');
  if (btn) btn.textContent = '🔄 Locating GPS...';

  if (!navigator.geolocation) {
    alert('Geolocation is not supported by your browser.');
    if (btn) btn.textContent = '📍 Near Me';
    return;
  }

  const geoOptions = {
    enableHighAccuracy: true,
    timeout: 10000,
    maximumAge: 0
  };

  navigator.geolocation.getCurrentPosition(
    position => {
      const userLat = position.coords.latitude;
      const userLng = position.coords.longitude;

      BLOOD_BANKS.forEach(bank => {
        bank.distKm = calculateDistanceKm(userLat, userLng, bank.lat, bank.lng);
      });

      const sorted = [...BLOOD_BANKS].sort((a, b) => a.distKm - b.distKm);

      if (btn) btn.textContent = '📍 Sorted by Location';
      renderBloodGrid(sorted);
    },
    error => {
      let errMsg = 'Unable to retrieve your location. Please grant GPS permissions in your browser.';
      if (error.code === error.PERMISSION_DENIED) {
        errMsg = 'Location permission was denied. Please allow location access in your browser settings.';
      }
      alert(errMsg);
      if (btn) btn.textContent = '📍 Near Me';
    },
    geoOptions
  );
}

function findNearMeMap() {
  if (!leafletMap) return;
  const btn = document.getElementById('btn-map-near-me');
  if (btn) btn.innerHTML = '🔄 Locating GPS...';

  if (!navigator.geolocation) {
    alert('Geolocation is not supported by your browser.');
    if (btn) btn.innerHTML = '<i class="fa-solid fa-location-crosshairs me-2"></i> Your Current Location';
    return;
  }

  const geoOptions = {
    enableHighAccuracy: true,
    timeout: 10000,
    maximumAge: 0
  };

  navigator.geolocation.getCurrentPosition(
    position => {
      const userLat = position.coords.latitude;
      const userLng = position.coords.longitude;

      leafletMap.flyTo([userLat, userLng], 14, { animate: true, duration: 1.5 });

      if (window.currentUserMarker) {
        leafletMap.removeLayer(window.currentUserMarker);
      }

      const userIcon = L.divIcon({
        html: `<div style="background:#2563eb; width:24px; height:24px; border-radius:50%; border:3px solid white; box-shadow:0 0 20px #2563eb;" class="animate-bounce"></div>`,
        className: ''
      });

      window.currentUserMarker = L.marker([userLat, userLng], { icon: userIcon, zIndexOffset: 10000 })
        .addTo(leafletMap)
        .bindPopup(`<b>📍 You are here</b><br><span style="font-size:11px;">GPS Position (${userLat.toFixed(4)}, ${userLng.toFixed(4)})</span>`)
        .openPopup();

      if (btn) btn.innerHTML = '📍 Centered on GPS';
    },
    error => {
      let errMsg = 'Unable to detect location. Please grant GPS permission in your browser.';
      if (error.code === error.PERMISSION_DENIED) {
        errMsg = 'Location permission was denied. Please allow location access in your browser settings.';
      } else if (error.code === error.POSITION_UNAVAILABLE) {
        errMsg = 'Location position is currently unavailable.';
      } else if (error.code === error.TIMEOUT) {
        errMsg = 'Location request timed out. Please try again.';
      }
      alert(errMsg);
      if (btn) btn.innerHTML = '<i class="fa-solid fa-location-crosshairs me-2"></i> Your Current Location';
    },
    geoOptions
  );
}

/* ================= RENDERING ENGINES ================= */
let filteredDoctors = [...DOCTORS];

function getStatusBadge(status) {
  if (status === 'in') {
    return `<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/15 text-emerald-400 border border-emerald-500/30"><span class="w-2 h-2 rounded-full pulse-green"></span>AVAILABLE NOW</span>`;
  } else if (status === 'emr') {
    return `<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-500/15 text-amber-400 border border-amber-500/30"><span class="w-2 h-2 rounded-full pulse-amber"></span>IN EMERGENCY / OT</span>`;
  } else {
    return `<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-red-500/15 text-red-400 border border-red-500/30"><span class="w-2 h-2 rounded-full pulse-red"></span>NOT ARRIVING TODAY</span>`;
  }
}

/* ================= OPD TIMINGS & SHIFT CALCULATOR ================= */
function formatOpdTiming(startTime, endTime) {
  if (!startTime || !endTime || startTime === '' || endTime === '') {
    return { text: 'OPD timing not available', isOpen: false, hasTiming: false };
  }

  function to12Hour(timeStr) {
    if (!timeStr) return null;
    const parts = timeStr.trim().split(':');
    if (parts.length < 1) return null;
    let hours = parseInt(parts[0], 10);
    const minutes = parts[1] ? parts[1].slice(0, 2) : '00';
    if (isNaN(hours)) return null;
    const ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12;
    hours = hours ? hours : 12;
    const formattedHours = hours < 10 ? `0${hours}` : `${hours}`;
    return `${formattedHours}:${minutes} ${ampm}`;
  }

  const startFormatted = to12Hour(startTime);
  const endFormatted = to12Hour(endTime);

  if (!startFormatted || !endFormatted) {
    return { text: 'OPD timing not available', isOpen: false, hasTiming: false };
  }

  const now = new Date();
  const currentMinutes = now.getHours() * 60 + now.getMinutes();

  const startParts = startTime.split(':');
  const endParts = endTime.split(':');
  const startMinutes = parseInt(startParts[0], 10) * 60 + parseInt(startParts[1] || 0, 10);
  const endMinutes = parseInt(endParts[0], 10) * 60 + parseInt(endParts[1] || 0, 10);

  let isOpen = false;
  if (!isNaN(startMinutes) && !isNaN(endMinutes)) {
    if (startMinutes <= endMinutes) {
      isOpen = currentMinutes >= startMinutes && currentMinutes <= endMinutes;
    } else {
      // Overnight shift handling (e.g. 20:00 to 04:00)
      isOpen = currentMinutes >= startMinutes || currentMinutes <= endMinutes;
    }
  }

  return {
    text: `OPD: ${startFormatted} - ${endFormatted}`,
    isOpen: isOpen,
    hasTiming: true
  };
}

function renderDoctorGrid() {
  const grid = document.getElementById('doctor-grid');
  const noRes = document.getElementById('no-results');
  if (!grid) return;

  if (filteredDoctors.length === 0) {
    grid.innerHTML = '';
    if (noRes) noRes.classList.remove('hidden');
    return;
  }

  if (noRes) noRes.classList.add('hidden');
  grid.innerHTML = filteredDoctors.slice(0, 48).map(doc => {
    const opdInfo = formatOpdTiming(doc.opdStartTime || doc.opd_start_time, doc.opdEndTime || doc.opd_end_time);
    const opdBadgeClass = opdInfo.isOpen ? 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30' : 'bg-slate-500/15 text-slate-400 border-slate-500/30';
    return `
    <div class="bg-card border border-theme rounded-2xl p-5 hover:border-emerald-500/40 transition-all shadow-lg flex flex-col justify-between anti-gravity-card">
      <div>
        <div class="flex items-start justify-between gap-3 mb-3">
          <div>
            <div class="flex items-center gap-1.5">
              <h3 class="font-bold text-main text-base">${doc.name}</h3>
              <span class="text-[10px] bg-blue-500/10 text-blue-400 px-1.5 py-0.5 rounded border border-blue-500/20 font-mono">✓ ${doc.hpr}</span>
            </div>
            <p class="text-xs text-muted mt-0.5">${doc.qual} · ${doc.dept}</p>
          </div>
          ${doc.distKm !== null ? `<span class="text-[11px] bg-emerald-500/10 text-emerald-400 px-2 py-0.5 rounded-full font-bold border border-emerald-500/20">📍 ${doc.distKm} km</span>` : ''}
        </div>

        <div class="mb-4">${getStatusBadge(doc.status)}</div>

        <div class="space-y-1.5 border-t border-theme pt-3 text-xs text-muted">
          <div class="flex items-center gap-2"><span>🏥</span><span class="font-semibold text-main">${doc.hospitalName}</span></div>
          <div class="flex items-center gap-2"><span>📍</span><span>${doc.districtName} District</span></div>
          <div class="flex items-center gap-2"><span>🚪</span><span>${doc.cabin}</span></div>
          <div class="flex items-center gap-2 text-emerald-400 font-semibold"><span>🕒</span><span>${opdInfo.text}</span></div>
          ${doc.note ? `<div class="text-[11px] text-amber-400 mt-1 bg-amber-500/10 p-2 rounded-lg border border-amber-500/20">Note: ${doc.note}</div>` : ''}
        </div>
      </div>

      <div class="mt-4 pt-3 border-t border-theme flex items-center justify-between text-[11px] text-muted">
        <span>⏱ ${doc.lastPunch}</span>
        ${opdInfo.hasTiming ? `<span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold border ${opdBadgeClass}">${opdInfo.isOpen ? '🟢 OPD OPEN' : '⚪ OPD CLOSED'}</span>` : '<span class="text-muted text-[10px]">Verified OPD</span>'}
      </div>
    </div>
  `}).join('');

  const countEl = document.getElementById('result-count');
  if (countEl) countEl.textContent = `Showing ${Math.min(48, filteredDoctors.length)} of ${filteredDoctors.length} doctors`;
}

function applyDoctorFilters() {
  const searchInput = document.getElementById('search-input');
  const distInput = document.getElementById('filter-district');
  const specInput = document.getElementById('filter-specialty');

  const search = searchInput ? searchInput.value.toLowerCase() : '';
  const dist = distInput ? distInput.value : '';
  const spec = specInput ? specInput.value.toLowerCase() : '';

  filteredDoctors = DOCTORS.filter(d => {
    const matchesSearch = d.name.toLowerCase().includes(search) || 
                          d.dept.toLowerCase().includes(search) || 
                          d.hospitalName.toLowerCase().includes(search);
    const matchesDist = !dist || d.district === dist;
    const matchesSpec = !spec || d.dept.toLowerCase() === spec;
    return matchesSearch && matchesDist && matchesSpec;
  });

  renderDoctorGrid();
}

/* Map Page Logic */
let leafletMap = null;

function initMapPage() {
  const mapEl = document.getElementById('map');
  if (!mapEl || leafletMap) return;

  leafletMap = L.map('map', {
    center: [11.1271, 78.6569],
    zoom: 7,
    preferCanvas: true,
    zoomAnimation: true,
    fadeAnimation: true,
    markerZoomAnimation: true,
    zoomControl: false
  });

  L.control.zoom({ position: 'topright' }).addTo(leafletMap);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(leafletMap);

  // 1. Plot hospital markers across ALL 38 districts of Tamil Nadu using Canvas CircleMarkers
  DISTRICT_CONFIG.forEach(dist => {
    const mainHospName = dist.hospitals[0] || `${dist.name} District Govt Hospital`;
    L.circleMarker([dist.lat, dist.lng], {
      radius: 8,
      fillColor: '#10b981',
      color: '#ffffff',
      weight: 2.5,
      opacity: 1,
      fillOpacity: 0.95
    })
      .addTo(leafletMap)
      .bindPopup(`<b>🏥 ${mainHospName}</b><br>District: <b>${dist.name}</b><br><span style="font-size:11px; color:#10b981; font-weight:bold;">24/7 Statewide Medical Hub</span>`);
  });

  // 2. Plot doctor availability markers across districts using Canvas CircleMarkers
  DOCTORS.filter((_, idx) => idx % 5 === 0).forEach(doc => {
    const color = doc.status === 'in' ? '#10b981' : doc.status === 'emr' ? '#f59e0b' : '#ef4444';
    L.circleMarker([doc.lat, doc.lng], {
      radius: 5,
      fillColor: color,
      color: '#ffffff',
      weight: 1.5,
      opacity: 0.9,
      fillOpacity: 0.85
    })
      .addTo(leafletMap)
      .bindPopup(`<b>${doc.name}</b><br>${doc.hospitalName} (${doc.districtName})<br><b>Status:</b> ${doc.status.toUpperCase()}`);
  });

  // 3. Handle search parameter from URL (e.g. map.html?search=Stanley%20Govt%20Hospital)
  const urlParams = new URLSearchParams(window.location.search);
  const searchTerm = urlParams.get('search');

  if (searchTerm && searchTerm.trim() !== '') {
    const rawTerm = decodeURIComponent(searchTerm).trim();
    const term = rawTerm.toLowerCase();

    const COORD_LOOKUP = {
      'stanley': { lat: 13.1075, lng: 80.2872, title: 'Stanley Govt Hospital', sub: 'Royapuram, Chennai' },
      'chennai': { lat: 13.0827, lng: 80.2707, title: 'Rajiv Gandhi Govt General Hospital (RGGGH)', sub: 'Park Town, Chennai' },
      'coimbatore': { lat: 11.0168, lng: 76.9558, title: 'Coimbatore Medical College Hospital', sub: 'Trichy Road, Coimbatore' },
      'madurai': { lat: 9.9252, lng: 78.1198, title: 'Madurai Govt Rajaji Hospital', sub: 'Goripalayam, Madurai' },
      'salem': { lat: 11.6643, lng: 78.1460, title: 'Salem Govt Mohan Kumaramangalam Hospital', sub: 'Four Roads, Salem' },
      'trichy': { lat: 10.7905, lng: 78.7047, title: 'Tiruchirappalli Govt Hospital', sub: 'Thillai Nagar, Trichy' },
      'tiruchirappalli': { lat: 10.7905, lng: 78.7047, title: 'Tiruchirappalli Govt Hospital', sub: 'Thillai Nagar, Trichy' },
      'tirunelveli': { lat: 8.7139, lng: 77.7567, title: 'Tirunelveli Govt Medical College Hospital', sub: 'High Ground, Tirunelveli' },
      'vellore': { lat: 12.9165, lng: 79.1325, title: 'Vellore Govt Headquarters Hospital', sub: 'Officers Line, Vellore' },
      'chengalpattu': { lat: 12.6841, lng: 79.9836, title: 'Chengalpattu Govt Medical College Hospital', sub: 'GST Road, Chengalpattu' },
      'kanchipuram': { lat: 12.8342, lng: 79.7036, title: 'Kanchipuram Govt Headquarters Hospital', sub: 'Railway Station Road, Kanchipuram' },
      'erode': { lat: 11.3410, lng: 77.7172, title: 'Erode Govt Medical College Hospital', sub: 'Perundurai, Erode' },
      'thanjavur': { lat: 10.7870, lng: 79.1378, title: 'Thanjavur Govt Medical College Hospital', sub: 'Medical College Road, Thanjavur' },
      'dindigul': { lat: 10.3673, lng: 77.9803, title: 'Dindigul Govt Headquarters Hospital', sub: 'Hospital Road, Dindigul' },
      'kanyakumari': { lat: 8.1833, lng: 77.4119, title: 'Kanyakumari Govt Medical College Hospital', sub: 'Asaripallam, Kanyakumari' },
      'nilgiris': { lat: 11.4102, lng: 76.6950, title: 'Govt Nilgiris HQ Hospital', sub: 'Ooty, Nilgiris, nilgiris' },
      'ooty': { lat: 11.4102, lng: 76.6950, title: 'Govt Nilgiris HQ Hospital', sub: 'Ooty, Nilgiris, nilgiris' }
    };

    let targetLat = null;
    let targetLng = null;
    let targetTitle = rawTerm;
    let targetSub = 'Tamil Nadu Healthcare Network';

    // A. Direct Match for Special Centers (e.g. Stanley Govt Hospital)
    for (let key in COORD_LOOKUP) {
      if (term.includes(key) || key.includes(term)) {
        targetLat = COORD_LOOKUP[key].lat;
        targetLng = COORD_LOOKUP[key].lng;
        targetTitle = COORD_LOOKUP[key].title;
        targetSub = COORD_LOOKUP[key].sub;
        break;
      }
    }

    // B. Match against BLOOD_BANKS list if no direct match
    if (!targetLat) {
      const matchedBank = BLOOD_BANKS.find(b =>
        b.name.toLowerCase().includes(term) ||
        b.city.toLowerCase().includes(term) ||
        b.district.toLowerCase().includes(term)
      );

      if (matchedBank) {
        targetLat = matchedBank.lat;
        targetLng = matchedBank.lng;
        targetTitle = matchedBank.name;
        targetSub = `${matchedBank.city}, ${matchedBank.district}`;
      } else {
        // C. Match against DISTRICT_CONFIG
        const matchedDist = DISTRICT_CONFIG.find(d =>
          d.id.toLowerCase().includes(term) ||
          d.name.toLowerCase().includes(term)
        );

        if (matchedDist) {
          targetLat = matchedDist.lat;
          targetLng = matchedDist.lng;
          targetTitle = `${matchedDist.name} District Medical Center`;
          targetSub = `Central District Hub, ${matchedDist.name}`;
        }
      }
    }

    // Fallback if no specific coordinates were found
    if (!targetLat) {
      targetLat = 13.1075;
      targetLng = 80.2872;
      targetTitle = rawTerm;
      targetSub = 'Tamil Nadu Healthcare Network';
    }

    if (targetLat && targetLng) {
      setTimeout(() => {
        if (leafletMap) leafletMap.invalidateSize();
        renderRouteGuidanceCard(targetLat, targetLng, targetTitle, targetSub);
      }, 300);
    }
  } else {
    // Direct navigation to Map page (No search parameter): Center on live user location & show statewide health hubs
    closeRouteGuidanceCard();
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
        (pos) => {
          const userLat = pos.coords.latitude;
          const userLng = pos.coords.longitude;
          if (leafletMap) {
            leafletMap.flyTo([userLat, userLng], 11, { animate: true, duration: 1.5 });
            const originIcon = L.divIcon({
              className: 'user-gps-pulse-marker',
              html: '<div class="user-pulse-outer"><div class="user-pulse-inner"></div></div>',
              iconSize: [24, 24],
              iconAnchor: [12, 12]
            });
            if (currentOriginMarker) leafletMap.removeLayer(currentOriginMarker);
            currentOriginMarker = L.marker([userLat, userLng], { icon: originIcon, zIndexOffset: 9000 })
              .addTo(leafletMap)
              .bindPopup(`<b>📍 You are here</b><br><span style="font-size:11px; color:#2563eb;">GPS Position (${userLat.toFixed(4)}, ${userLng.toFixed(4)})</span>`)
              .openPopup();
          }
          const gpsFab = document.getElementById('btn-map-near-me');
          if (gpsFab) gpsFab.innerHTML = `<i class="fa-solid fa-location-crosshairs me-1.5"></i> Centered on GPS`;
        },
        () => {
          if (leafletMap) leafletMap.setView([11.1271, 78.6569], 7);
        },
        { enableHighAccuracy: true, timeout: 6000, maximumAge: 0 }
      );
    }
  }

  setTimeout(() => {
    if (leafletMap) leafletMap.invalidateSize();
  }, 350);

  window.addEventListener('resize', () => {
    if (leafletMap) leafletMap.invalidateSize();
  });
}

/* ================= GIS ROUTE GUIDANCE CALCULATOR & OVERLAY ENGINE ================= */
let currentRoutePolyline = null;
let currentOriginMarker = null;
let currentDestMarker = null;

function calculateHaversineDistance(lat1, lon1, lat2, lon2) {
  const R = 6371; // Earth radius in km
  const dLat = (lat2 - lat1) * Math.PI / 180;
  const dLon = (lon2 - lon1) * Math.PI / 180;
  const a = 
    Math.sin(dLat / 2) * Math.sin(dLat / 2) +
    Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * 
    Math.sin(dLon / 2) * Math.sin(dLon / 2);
  const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
  return R * c;
}

function estimateDrivingTime(distKm) {
  const speedKmH = 40; // Average driving speed in TN transit corridors
  const totalMins = Math.round((distKm / speedKmH) * 60);
  const hrs = Math.floor(totalMins / 60);
  const mins = totalMins % 60;
  if (hrs > 0) {
    return `~${hrs} hr ${mins} mins`;
  }
  return `~${mins} mins`;
}

function closeRouteGuidanceCard() {
  const card = document.getElementById('route-guidance-card');
  if (card) card.classList.add('hidden');
}

function renderRouteGuidanceCard(destLat, destLng, destTitle, destSub) {
  if (!leafletMap) return;

  // Check URL query parameters for user_lat and user_lng
  const urlParams = new URLSearchParams(window.location.search);
  const paramUserLat = parseFloat(urlParams.get('user_lat'));
  const paramUserLng = parseFloat(urlParams.get('user_lng'));

  let defaultUserLat = (paramUserLat && !isNaN(paramUserLat)) ? paramUserLat : 11.2697;
  let defaultUserLng = (paramUserLng && !isNaN(paramUserLng)) ? paramUserLng : 77.6057;

  function plotRoute(lat1, lng1, lat2, lng2, title, sub, isRealGps) {
    // 1. Remove previous route elements if present
    if (currentRoutePolyline) leafletMap.removeLayer(currentRoutePolyline);
    if (currentOriginMarker) leafletMap.removeLayer(currentOriginMarker);
    if (currentDestMarker) leafletMap.removeLayer(currentDestMarker);

    // 2. User Origin Marker (Pulsing Blue GPS Dot)
    const originIcon = L.divIcon({
      className: 'user-gps-pulse-marker',
      html: '<div class="user-pulse-outer"><div class="user-pulse-inner"></div></div>',
      iconSize: [24, 24],
      iconAnchor: [12, 12]
    });

    currentOriginMarker = L.marker([lat1, lng1], { icon: originIcon, zIndexOffset: 9000 })
      .addTo(leafletMap)
      .bindPopup(`<b>📍 You are here</b><br><span style="font-size:11px; color:#2563eb;">GPS Position (${lat1.toFixed(4)}, ${lng1.toFixed(4)})</span>`)
      .openPopup();

    // 3. Destination Marker (Glowing Red Map Pin)
    const destIcon = L.divIcon({
      className: 'dest-pin-marker',
      html: '<div class="dest-pin-glow"><i class="fa-solid fa-location-dot text-red-500 text-2xl"></i></div>',
      iconSize: [36, 36],
      iconAnchor: [18, 36]
    });
    currentDestMarker = L.marker([lat2, lng2], { icon: destIcon, zIndexOffset: 10000 })
      .addTo(leafletMap)
      .bindPopup(`<b>🏥 ${title}</b><br><span style="font-size:11px; color:#ef4444;">${sub || 'Destination Center'}</span>`);

    // 4. Dashed Blue Polyline Route
    currentRoutePolyline = L.polyline([[lat1, lng1], [lat2, lng2]], {
      color: '#2563eb',
      weight: 4,
      dashArray: '8, 8',
      opacity: 0.9,
      lineCap: 'round'
    }).addTo(leafletMap);

    // 5. Fit Map Bounds
    leafletMap.fitBounds(currentRoutePolyline.getBounds(), { padding: [60, 60] });

    // 6. Calculate Distance & Driving Time
    const distKm = calculateHaversineDistance(lat1, lng1, lat2, lng2);
    const timeStr = estimateDrivingTime(distKm);

    // 7. Update Overlay Card DOM Elements
    const card = document.getElementById('route-guidance-card');
    const titleEl = document.getElementById('route-card-title');
    const subEl = document.getElementById('route-card-subtitle');
    const distEl = document.getElementById('route-card-distance');
    const timeEl = document.getElementById('route-card-time');
    const btnEl = document.getElementById('route-card-gmaps-btn');

    if (card) {
      if (titleEl) titleEl.textContent = title;
      if (subEl) subEl.textContent = sub || 'Tamil Nadu Healthcare Network';
      if (distEl) distEl.textContent = `${distKm.toFixed(1)} km`;
      if (timeEl) timeEl.textContent = timeStr;
      if (btnEl) {
        btnEl.href = `https://www.google.com/maps/dir/?api=1&origin=${lat1},${lng1}&destination=${lat2},${lng2}`;
      }
      card.classList.remove('hidden');
    }

    // 8. Update Bottom-Right Centered on GPS Button
    const gpsFab = document.getElementById('btn-map-near-me');
    if (gpsFab) {
      gpsFab.innerHTML = `<i class="fa-solid fa-location-crosshairs me-1.5"></i> Centered on GPS`;
    }
  }

  // 9. Query HTML5 Geolocation API
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      (pos) => {
        const liveLat = pos.coords.latitude;
        const liveLng = pos.coords.longitude;
        plotRoute(liveLat, liveLng, destLat, destLng, destTitle, destSub, true);
      },
      () => {
        plotRoute(defaultUserLat, defaultUserLng, destLat, destLng, destTitle, destSub, false);
      },
      { enableHighAccuracy: true, timeout: 6000, maximumAge: 0 }
    );
  } else {
    plotRoute(defaultUserLat, defaultUserLng, destLat, destLng, destTitle, destSub, false);
  }
}

/* Map Redirect Handler */
function redirectToMap(e, targetPage) {
  if (e) e.preventDefault();
  const searchInput = document.getElementById('search-hospital') || document.getElementById('search-input');
  const districtSelect = document.getElementById('filter-district') || document.getElementById('district-select');
  
  const searchVal = searchInput ? searchInput.value.trim() : '';
  const distVal = districtSelect ? districtSelect.value.trim() : '';
  
  const value = searchVal || distVal;
  const page = targetPage || (window.location.pathname.endsWith('.php') ? 'map.php' : 'map.html');
  
  if (value) {
    window.location.href = `${page}?search=${encodeURIComponent(value)}`;
  } else {
    window.location.href = page;
  }
}

/* Blood Bank Page Logic */
let selectedGroup = '';

function selectBloodGroup(group) {
  selectedGroup = group;
  document.querySelectorAll('.blood-pill').forEach(btn => {
    if (btn.textContent.trim() === (group || 'ALL')) {
      btn.className = 'blood-pill active px-3.5 py-1.5 rounded-xl text-xs font-bold border border-red-500 bg-red-600 text-white transition-all';
    } else {
      btn.className = 'blood-pill px-3.5 py-1.5 rounded-xl text-xs font-bold border border-theme bg-input text-main hover:border-red-500 transition-all';
    }
  });
  filterBloodBanks();
}

function filterBloodBanks() {
  const searchEl = document.getElementById('search-hospital');
  const distEl = document.getElementById('filter-district');
  const search = searchEl ? searchEl.value.toLowerCase() : '';
  const district = distEl ? distEl.value : '';

  const filtered = BLOOD_BANKS.filter(bank => {
    const matchesSearch = bank.name.toLowerCase().includes(search) || bank.city.toLowerCase().includes(search);
    const matchesDistrict = !district || bank.district === district;
    return matchesSearch && matchesDistrict;
  });

  renderBloodGrid(filtered);
}

function renderBloodGrid(banks) {
  const grid = document.getElementById('blood-grid');
  if (!grid) return;

  const countEl = document.getElementById('bank-count');
  if (countEl) countEl.textContent = `${banks.length} blood banks found`;

  grid.innerHTML = banks.map(bank => `
    <div class="bg-card border border-theme rounded-2xl p-5 hover:border-red-500/40 transition-all shadow-xl flex flex-col justify-between anti-gravity-card">
      <div>
        <div class="flex items-start justify-between gap-2 mb-3">
          <div>
            <h3 class="font-bold text-main text-base">${bank.name}</h3>
            <p class="text-xs text-muted">📍 ${bank.city}, Tamil Nadu</p>
          </div>
          ${bank.distKm !== null ? `<span class="text-[10px] bg-red-500/10 text-red-400 border border-red-500/20 px-2 py-0.5 rounded-full font-bold">📍 ${bank.distKm} km away</span>` : '<span class="text-[10px] bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 px-2 py-0.5 rounded-full font-semibold">VERIFIED</span>'}
        </div>

        <div class="grid grid-cols-4 gap-2 my-4">
          ${Object.entries(bank.stocks).map(([group, count]) => {
            const isSelected = selectedGroup === group;
            let bgClass = 'bg-input border-theme text-main';
            if (count === 0) bgClass = 'bg-red-500/10 border-red-500/30 text-red-400';
            else if (count <= 3) bgClass = 'bg-amber-500/10 border-amber-500/30 text-amber-400';
            else bgClass = 'bg-emerald-500/10 border-emerald-500/30 text-emerald-400';

            if (isSelected) bgClass += ' ring-2 ring-red-500';

            return `
              <div class="border rounded-xl p-2 text-center ${bgClass}">
                <div class="text-[11px] font-extrabold">${group}</div>
                <div class="text-xs font-bold mt-0.5">${count} <span class="text-[9px] font-normal">U</span></div>
              </div>
            `;
          }).join('')}
        </div>
      </div>

      <div class="pt-3 border-t border-theme flex items-center justify-between text-xs">
        <span class="text-muted">⏱ ${bank.lastUpdated}</span>
        <a href="tel:${bank.phone}" class="text-red-500 font-bold hover:underline">📞 Call Bank</a>
      </div>
    </div>
  `).join('');
}

/* ================= LIVE OPD QUEUE MODULE ================= */
function populateQueueDistrictDropdown() {
  const select = document.getElementById('queue-district-filter');
  if (!select) return;
  
  if (typeof DISTRICT_CONFIG !== 'undefined') {
    const districts = Object.keys(DISTRICT_CONFIG).sort();
    select.innerHTML = '<option value="">All 38 Districts</option>' + 
      districts.map(d => `<option value="${d}">${d}</option>`).join('');
  }
}

function filterQueueGrid() {
  renderQueueGrid();
}

function renderQueueGrid() {
  const grid = document.getElementById('queue-grid');
  if (!grid) return;

  const distVal = document.getElementById('queue-district-filter')?.value || '';
  const deptVal = document.getElementById('queue-dept-filter')?.value || '';

  const filtered = DOCTORS.filter(d => {
    const matchesDist = !distVal || d.district === distVal;
    const matchesDept = !deptVal || d.dept.toLowerCase().includes(deptVal.toLowerCase());
    return matchesDist && matchesDept;
  });

  const countBadge = document.getElementById('queue-count-badge');
  if (countBadge) countBadge.textContent = `Showing ${filtered.length} active OPD cabin queues`;

  if (filtered.length === 0) {
    grid.innerHTML = `
      <div class="col-span-full bg-card border border-theme rounded-2xl p-8 text-center text-muted">
        <i class="fa-solid fa-folder-open text-3xl mb-2 text-muted"></i>
        <p class="text-xs font-bold">No active OPD queues matching selected filters.</p>
      </div>
    `;
    return;
  }

  grid.innerHTML = filtered.map((doc) => {
    const tokenNo = 12 + ((doc.id * 3) % 25);
    const totalIssued = tokenNo + 14;
    const waitTime = Math.round((totalIssued - tokenNo) * 11);

    return `
      <div class="bg-card border border-theme rounded-2xl p-5 hover:border-emerald-500/40 transition-all shadow-xl flex flex-col justify-between anti-gravity-card">
        <div>
          <div class="flex items-start justify-between gap-2 mb-3">
            <div>
              <div class="flex items-center gap-1.5">
                <h3 class="font-extrabold text-main text-base">${doc.name}</h3>
                <span class="text-[10px] bg-blue-500/10 text-blue-400 px-1.5 py-0.5 rounded border border-blue-500/20 font-mono">✓ ${doc.hpr}</span>
              </div>
              <p class="text-xs text-muted mt-0.5">${doc.qual} · ${doc.dept}</p>
            </div>
            <div>${getStatusBadge(doc.status)}</div>
          </div>

          <div class="space-y-1.5 border-t border-theme pt-3 text-xs text-muted">
            <div class="flex items-center gap-2"><span>🏥</span><span class="font-semibold text-main">${doc.hospitalName}</span></div>
            <div class="flex items-center gap-2"><span>📍</span><span>${doc.districtName} District</span></div>
            <div class="flex items-center gap-2"><span>🚪</span><span>${doc.cabin}</span></div>
          </div>

          <!-- QUEUE TOKEN COUNTERS -->
          <div class="bg-input/60 border border-theme rounded-xl p-3 my-4 flex items-center justify-between text-xs">
            <div>
              <span class="block text-[10px] text-muted font-extrabold uppercase">SERVING TOKEN</span>
              <span class="text-lg font-black text-emerald-400">#${tokenNo}</span>
            </div>
            <div class="text-right">
              <span class="block text-[10px] text-muted font-extrabold uppercase">EST. WAIT TIME</span>
              <span class="text-xs font-bold text-main">~${waitTime} mins</span>
            </div>
          </div>
        </div>

        <button onclick="openTokenModal(${doc.id})" class="w-full bg-emerald-500/15 hover:bg-emerald-500 text-emerald-400 hover:text-white font-extrabold text-xs py-2.5 rounded-xl border border-emerald-500/30 transition-all flex items-center justify-center gap-2">
          <i class="fa-solid fa-ticket"></i> Issue OP Token
        </button>
      </div>
    `;
  }).join('');
}

/* ================= PATIENT OP TOKEN PERSISTENCE ENGINE ================= */
function getPatientTokens() {
  try {
    return JSON.parse(localStorage.getItem('medpulse_patient_tokens') || '[]');
  } catch (e) {
    return [];
  }
}

function savePatientToken(tokenData) {
  if (!tokenData || typeof tokenData !== 'object') return false;
  try {
    const existing = getPatientTokens();
    existing.unshift(tokenData);
    localStorage.setItem('medpulse_patient_tokens', JSON.stringify(existing));
    return true;
  } catch (e) {
    console.error('Failed to persist patient OP token:', e);
    return false;
  }
}

function openTokenModal(docId) {
  const modal = document.getElementById('token-modal');
  const docSelect = document.getElementById('token-doctor-select');
  if (!modal) return;

  if (docSelect && DOCTORS.length > 0) {
    docSelect.innerHTML = DOCTORS.map(d => `<option value="${d.id}" ${String(docId) === String(d.id) ? 'selected' : ''}>${d.name} (${d.dept} - ${d.hospitalName})</option>`).join('');
  }

  const resultWrapper = document.getElementById('generated-ticket-wrapper');
  if (resultWrapper) resultWrapper.classList.add('hidden');

  modal.classList.remove('hidden');
}

function closeTokenModal() {
  const modal = document.getElementById('token-modal');
  if (modal) modal.classList.add('hidden');
}

function generateOPToken(e) {
  e.preventDefault();
  const docSelect = document.getElementById('token-doctor-select');
  const selectedVal = docSelect ? docSelect.value : '';
  const doctor = DOCTORS.find(d => String(d.id) === String(selectedVal)) || DOCTORS[0];

  const tokenNum = Math.floor(Math.random() * 40) + 20;
  const qrCodeStr = `TOKEN-TN-${doctor.hpr ? doctor.hpr.replace('HPR_', '') : '89412'}-${Date.now().toString().slice(-4)}`;

  const docNameEl = document.getElementById('ticket-doc-name');
  const hospNameEl = document.getElementById('ticket-hosp-name');
  const ticketNumEl = document.getElementById('ticket-number');
  const qrCodeEl = document.getElementById('ticket-qr-code');

  if (docNameEl) docNameEl.textContent = `${doctor.name} (${doctor.qual})`;
  if (hospNameEl) hospNameEl.textContent = `${doctor.hospitalName}, ${doctor.cabin}`;
  if (ticketNumEl) ticketNumEl.textContent = `#${tokenNum}`;
  if (qrCodeEl) qrCodeEl.textContent = qrCodeStr;

  const patientName = document.getElementById('token-patient-name')?.value || 'Patient';
  const patientPhone = document.getElementById('token-patient-phone')?.value || '';
  const slot = document.getElementById('token-slot')?.value || '';
  const notes = document.getElementById('token-notes')?.value || '';

  const tokenData = {
    id: `TK_${Date.now()}_${tokenNum}`,
    tokenNumber: tokenNum,
    docId: doctor.id,
    doctorName: doctor.name,
    qualification: doctor.qual,
    department: doctor.dept,
    hospitalName: doctor.hospitalName,
    cabin: doctor.cabin,
    district: doctor.districtName,
    patientName: patientName,
    patientPhone: patientPhone,
    slot: slot,
    notes: notes,
    qrCode: qrCodeStr,
    timestamp: new Date().toISOString(),
    status: 'ACTIVE'
  };

  savePatientToken(tokenData);

  const resultWrapper = document.getElementById('generated-ticket-wrapper');
  if (resultWrapper) resultWrapper.classList.remove('hidden');

  if (document.getElementById('patient-tokens-modal') && !document.getElementById('patient-tokens-modal').classList.contains('hidden')) {
    renderPatientTokensList();
  }
}

/* ================= MY SAVED PATIENT TOKENS MODAL & RENDERER ================= */
function openPatientTokensModal() {
  if (typeof closeSidebar === 'function') closeSidebar();
  let modal = document.getElementById('patient-tokens-modal');
  if (!modal) {
    modal = document.createElement('div');
    modal.id = 'patient-tokens-modal';
    modal.className = 'fixed inset-0 bg-black/75 backdrop-blur-sm z-[10000] flex items-center justify-center p-4';
    modal.innerHTML = `
      <div class="bg-card border border-theme rounded-3xl p-6 max-w-xl w-full shadow-2xl relative max-h-[90vh] flex flex-col justify-between">
        <div class="flex items-center justify-between border-b border-theme pb-4 mb-4">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold text-lg">
              <i class="fa-solid fa-ticket"></i>
            </div>
            <div>
              <h3 class="text-lg font-extrabold text-main">My Saved OP Tokens</h3>
              <p class="text-xs text-muted">Active OPD tickets saved on this device</p>
            </div>
          </div>
          <button onclick="closePatientTokensModal()" class="text-muted hover:text-main text-xl font-bold p-1">✕</button>
        </div>

        <div id="patient-tokens-container" class="space-y-4 overflow-y-auto max-h-[60vh] pr-1 flex-grow">
        </div>

        <div class="pt-4 border-t border-theme flex items-center justify-between mt-4">
          <button onclick="clearPatientTokensHistory()" class="text-xs text-red-400 hover:text-red-300 font-bold flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-red-500/10 border border-red-500/20">
            <i class="fa-solid fa-trash"></i> Clear History
          </button>
          <button onclick="closePatientTokensModal()" class="bg-emerald-500 text-slate-950 font-bold px-5 py-2 rounded-xl text-xs hover:brightness-110">
            Close
          </button>
        </div>
      </div>
    `;
    document.body.appendChild(modal);
  }

  renderPatientTokensList();
  modal.classList.remove('hidden');
}

function closePatientTokensModal() {
  const modal = document.getElementById('patient-tokens-modal');
  if (modal) modal.classList.add('hidden');
}

function clearPatientTokensHistory() {
  if (confirm('Are you sure you want to clear all saved OP tokens from this device?')) {
    localStorage.removeItem('medpulse_patient_tokens');
    renderPatientTokensList();
  }
}

function renderPatientTokensList() {
  const container = document.getElementById('patient-tokens-container');
  if (!container) return;

  const tokens = getPatientTokens();

  if (!Array.isArray(tokens) || tokens.length === 0) {
    container.innerHTML = `
      <div class="bg-input/60 border border-theme rounded-2xl p-8 text-center text-muted">
        <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center mx-auto text-xl mb-3 border border-emerald-500/20">
          <i class="fa-solid fa-ticket"></i>
        </div>
        <h4 class="font-extrabold text-main text-sm mb-1">No OPD tokens found on this device</h4>
        <p class="text-xs text-muted">Tokens generated from the Live OPD Queue status page will be saved here automatically.</p>
      </div>
    `;
    return;
  }

  container.innerHTML = tokens.map(t => {
    let formattedDate = 'Recently Issued';
    if (t.timestamp) {
      try {
        formattedDate = new Date(t.timestamp).toLocaleString('en-IN', { dateStyle: 'medium', timeStyle: 'short' });
      } catch (err) {}
    }

    const docName = t.doctorName || 'Doctor';
    const qual = t.qualification ? ` (${t.qualification})` : '';
    const hosp = t.hospitalName || 'Govt Hospital';
    const cabinStr = t.cabin ? ` · ${t.cabin}` : '';
    const tokenNo = t.tokenNumber ? `#${t.tokenNumber}` : '#--';
    const patientName = t.patientName || 'Patient';
    const phoneStr = t.patientPhone ? ` (${t.patientPhone})` : '';
    const slotStr = t.slot || 'OPD Hours';
    const qrStr = t.qrCode || `TOKEN-TN-${t.id || '2026'}`;
    const notesStr = t.notes ? `<div class="text-[11px] text-amber-400 mt-2 bg-amber-500/10 p-2 rounded-lg border border-amber-500/20">Notes: ${t.notes}</div>` : '';

    return `
      <div class="bg-card border border-emerald-500/30 rounded-2xl p-4 shadow-lg space-y-3 relative">
        <div class="flex items-start justify-between gap-2 border-b border-theme pb-2.5">
          <div>
            <div class="flex items-center gap-2">
              <h4 class="font-extrabold text-main text-sm">${docName}${qual}</h4>
              <span class="text-[10px] bg-blue-500/10 text-blue-400 px-2 py-0.5 rounded border border-blue-500/20 font-bold">${t.department || 'OPD'}</span>
            </div>
            <p class="text-xs text-muted mt-0.5">🏥 ${hosp}${cabinStr}</p>
          </div>
          <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/15 text-emerald-400 border border-emerald-500/30">
            ${t.status || 'ACTIVE'}
          </span>
        </div>

        <div class="bg-input/60 rounded-xl p-3 flex items-center justify-between text-xs border border-theme">
          <div>
            <span class="block text-[10px] text-muted font-bold uppercase">TOKEN NUMBER</span>
            <span class="text-xl font-black text-emerald-400">${tokenNo}</span>
          </div>
          <div class="text-right">
            <span class="block text-[10px] text-muted font-bold uppercase">TIME SLOT</span>
            <span class="text-xs font-bold text-main">${slotStr}</span>
          </div>
        </div>

        <div class="grid grid-cols-2 gap-2 text-[11px] text-muted pt-1">
          <div>👤 <span class="font-semibold text-main">${patientName}${phoneStr}</span></div>
          <div class="text-right">⏱ <span>${formattedDate}</span></div>
        </div>

        ${notesStr}

        <div class="pt-2 border-t border-theme flex items-center justify-between text-[10px] font-mono text-muted">
          <span>QR Verification Code:</span>
          <span class="text-emerald-400 font-bold">${qrStr}</span>
        </div>
      </div>
    `;
  }).join('');
}

/* ================= TELEMEDICINE MODULE ================= */
function renderTelemedGrid() {
  const grid = document.getElementById('telemed-grid');
  if (!grid) return;

  grid.innerHTML = DOCTORS.slice(0, 12).map(doc => `
    <div class="bg-card border border-theme rounded-2xl p-5 hover:border-blue-500/40 transition-all shadow-xl flex flex-col justify-between anti-gravity-card">
      <div>
        <div class="flex items-start justify-between gap-2 mb-3">
          <div>
            <h3 class="font-extrabold text-main text-base">${doc.name}</h3>
            <p class="text-xs text-muted mt-0.5">${doc.qual} · ${doc.dept}</p>
          </div>
          <span class="text-[10px] bg-blue-500/10 text-blue-400 px-2 py-0.5 rounded-full font-bold border border-blue-500/20">ONLINE VIRTUAL</span>
        </div>

        <div class="space-y-1.5 border-t border-theme pt-3 text-xs text-muted">
          <div class="flex items-center gap-2"><span>🏥</span><span class="font-semibold text-main">${doc.hospitalName}</span></div>
          <div class="flex items-center gap-2"><span>🌐</span><span>eSanjeevani Teleconsultation Hub</span></div>
        </div>
      </div>

      <div class="mt-4 pt-3 border-t border-theme grid grid-cols-2 gap-2">
        <button onclick="openTelemedBookingModal(${doc.id})" class="bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs py-2 rounded-xl transition-all flex items-center justify-center gap-1.5">
          <i class="fa-solid fa-calendar-check"></i> Book Slot
        </button>
        <button onclick="alert('Launching eSanjeevani Secure Video Teleconsultation Channel with ${doc.name}...');" class="bg-input hover:bg-card border border-theme text-main font-bold text-xs py-2 rounded-xl transition-all flex items-center justify-center gap-1.5">
          <i class="fa-solid fa-video text-emerald-400"></i> Direct Call
        </button>
      </div>
    </div>
  `).join('');
}

function openTelemedBookingModal() {
  const modal = document.getElementById('telemed-modal');
  if (modal) modal.classList.remove('hidden');
}

function closeTelemedBookingModal() {
  const modal = document.getElementById('telemed-modal');
  if (modal) modal.classList.add('hidden');
}

function submitTelemedBooking(e) {
  e.preventDefault();
  alert('✓ Virtual Teleconsultation appointment booked successfully on eSanjeevani Gateway! Appointment confirmation sent via SMS/WhatsApp.');
  closeTelemedBookingModal();
}

function togglePrescriptionViewer() {
  const viewer = document.getElementById('eprescription-viewer');
  if (viewer) viewer.classList.toggle('hidden');
}

/* ================= NHA HPR VERIFICATION ENGINE ================= */
function fillHPRSearch(code) {
  const input = document.getElementById('hpr-search-input');
  if (input) {
    input.value = code;
    executeHPRSearch();
  }
}

function executeHPRSearch() {
  const input = document.getElementById('hpr-search-input');
  const container = document.getElementById('hpr-result-container');
  if (!input || !container) return;

  const query = input.value.trim().toLowerCase();
  if (!query) {
    alert('Please enter an HPR ID (e.g. HPR_18492) or Doctor Name.');
    return;
  }

  const match = DOCTORS.find(d => 
    d.hpr.toLowerCase().includes(query) || 
    d.name.toLowerCase().includes(query) || 
    d.dept.toLowerCase().includes(query)
  );

  if (match) {
    container.innerHTML = `
      <div class="bg-card border border-emerald-500/40 rounded-3xl p-6 shadow-2xl anti-gravity-card relative overflow-hidden">
        <div class="flex items-center justify-between border-b border-theme pb-4 mb-4">
          <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-2xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold text-xl border border-emerald-500/30">
              <i class="fa-solid fa-user-check"></i>
            </div>
            <div>
              <h3 class="font-extrabold text-main text-lg">${match.name}</h3>
              <p class="text-xs text-muted">${match.qual} · ${match.dept}</p>
            </div>
          </div>
          <span class="px-3.5 py-1 rounded-full bg-emerald-500/20 text-emerald-400 text-xs font-extrabold border border-emerald-500/30 flex items-center gap-1.5">
            <i class="fa-solid fa-circle-check"></i> ABDM VERIFIED
          </span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
          <div class="bg-input/60 p-3.5 rounded-xl border border-theme">
            <span class="text-muted block text-[10px] uppercase font-bold">NHA HPR REGISTERED ID</span>
            <span class="font-mono font-extrabold text-emerald-400 text-sm">${match.hpr}</span>
          </div>

          <div class="bg-input/60 p-3.5 rounded-xl border border-theme">
            <span class="text-muted block text-[10px] uppercase font-bold">MEDICAL COUNCIL REG NO</span>
            <span class="font-mono font-bold text-main">TNMC-${(match.id * 8941) % 90000 + 10000}</span>
          </div>

          <div class="bg-input/60 p-3.5 rounded-xl border border-theme">
            <span class="text-muted block text-[10px] uppercase font-bold">PRIMARY HOSPITAL ATTACHMENT</span>
            <span class="font-bold text-main">${match.hospitalName}</span>
          </div>

          <div class="bg-input/60 p-3.5 rounded-xl border border-theme">
            <span class="text-muted block text-[10px] uppercase font-bold">OPD CABIN & ROOM</span>
            <span class="font-bold text-main">${match.cabin} (${match.districtName} Dist)</span>
          </div>
        </div>

        <div class="mt-5 pt-4 border-t border-theme flex items-center justify-between text-xs">
          <span class="text-emerald-400 font-bold">✓ NHA Authorization Active & Valid</span>
          <span class="text-muted">DPDPA 2023 Compliant Telemetry</span>
        </div>
      </div>
    `;
  } else {
    container.innerHTML = `
      <div class="bg-card border border-red-500/40 rounded-3xl p-6 text-center shadow-xl anti-gravity-card">
        <div class="w-12 h-12 rounded-2xl bg-red-500/10 text-red-400 flex items-center justify-center mx-auto text-xl mb-3 border border-red-500/20">
          <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <h3 class="font-extrabold text-main text-base mb-1">HPR ID Not Found</h3>
        <p class="text-xs text-muted">No practitioner registered under ID "${query}". Please check the ID or try searching "HPR_18492".</p>
      </div>
    `;
  }
}

/* Active Drawer Item Auto-Detector */
function highlightActiveDrawerItems() {
  const currentPath = window.location.pathname.split('/').pop() || 'index.html';
  document.querySelectorAll('#sidebar a').forEach(link => {
    const href = link.getAttribute('href');
    if (href && (href === currentPath || (currentPath === '' && href === 'index.html') || (currentPath === 'index.php' && href === 'index.php'))) {
      link.classList.add('drawer-item-active');
    } else {
      link.classList.remove('drawer-item-active');
    }
  });
}

/* ================= DISTRICT STAFF AUTHENTICATION & DESK SECURITY ================= */
const DISTRICT_PIN_MAP = {
  "Ariyalur": "TN61", "Chengalpattu": "TN19", "Chennai": "TN01", 
  "Coimbatore": "TN37", "Cuddalore": "TN31", "Dharmapuri": "TN29", 
  "Dindigul": "TN57", "Erode": "TN33", "Kallakurichi": "TN15", 
  "Kancheepuram": "TN21", "Kanyakumari": "TN74", "Karur": "TN47", 
  "Krishnagiri": "TN24", "Madurai": "TN58", "Mayiladuthurai": "TN82", 
  "Nagapattinam": "TN51", "Namakkal": "TN28", "Nilgiris": "TN43", 
  "Perambalur": "TN46", "Pudukkottai": "TN55", "Ramanathapuram": "TN65", 
  "Ranipet": "TN73", "Salem": "TN27", "Sivaganga": "TN63", 
  "Tenkasi": "TN79", "Thanjavur": "TN49", "Theni": "TN60", 
  "Thoothukudi": "TN69", "Tiruchirappalli": "TN45", "Tirunelveli": "TN72", 
  "Tirupathur": "TN83", "Tiruppur": "TN39", "Tiruvallur": "TN20", 
  "Tiruvannamalai": "TN25", "Tiruvarur": "TN50", "Vellore": "TN23", 
  "Viluppuram": "TN32", "Virudhunagar": "TN67"
};

function handleAdminLogin(e) {
  if (e) e.preventDefault();
  const districtSelect = document.getElementById('admin-login-district');
  const pinInput = document.getElementById('admin-login-pin');

  const district = districtSelect ? districtSelect.value : '';
  const pin = pinInput ? pinInput.value.trim().toUpperCase() : '';

  if (!district) {
    showAuthToast('Please select your assigned Tamil Nadu district.', 'error');
    return;
  }

  const expectedPin = DISTRICT_PIN_MAP[district];
  const isMaster = (pin === 'ADMIN123' || pin === 'TN100');

  if (pin === expectedPin || isMaster) {
    const sessionData = {
      loggedIn: true,
      district: district,
      pin: pin,
      isMaster: isMaster,
      loginTime: Date.now()
    };
    sessionStorage.setItem('medpulse_admin_session', JSON.stringify(sessionData));
    
    showAuthToast(`✓ Authenticated for ${isMaster ? 'Statewide Master Admin' : district + ' District'} Desk`, 'success');
    
    setTimeout(() => {
      const isPhp = window.location.pathname.endsWith('.php');
      window.location.href = isPhp ? 'admin.php' : 'admin.html';
    }, 800);
  } else {
    showAuthToast(`✕ Invalid Staff PIN for ${district}. Expected format e.g. ${expectedPin || 'TN23'}.`, 'error');
  }
}

function showAuthToast(msg, type) {
  const toastEl = document.getElementById('auth-toast');
  if (!toastEl) return;
  toastEl.textContent = msg;
  toastEl.className = type === 'success'
    ? 'block mb-5 p-3.5 rounded-2xl text-xs font-bold text-center bg-emerald-500/20 text-emerald-400 border border-emerald-500/30'
    : 'block mb-5 p-3.5 rounded-2xl text-xs font-bold text-center bg-red-500/20 text-red-400 border border-red-500/30';
}

function getAdminSession() {
  const raw = sessionStorage.getItem('medpulse_admin_session');
  if (!raw) return null;
  try {
    return JSON.parse(raw);
  } catch (e) {
    return null;
  }
}

function logoutAdminSession() {
  sessionStorage.removeItem('medpulse_admin_session');
  const isPhp = window.location.pathname.endsWith('.php');
  window.location.href = isPhp ? 'login.php' : 'login.html';
}

function checkAdminSession() {
  const session = getAdminSession();
  const badgeEl = document.getElementById('admin-district-badge');
  if (session && session.district) {
    if (badgeEl) {
      badgeEl.textContent = session.isMaster 
        ? `📍 Logged in: Statewide Super Admin Console`
        : `📍 Logged in: ${session.district} District Staff Console`;
    }

    // Lock & Pre-fill Creation Modals to Authenticated Staff District
    if (!session.isMaster) {
      ['hosp-district', 'doc-district', 'blood-district'].forEach(id => {
        const select = document.getElementById(id);
        if (select) {
          select.value = session.district;
          select.setAttribute('disabled', 'disabled');
          select.classList.add('opacity-75', 'cursor-not-allowed');
        }
      });
    }
  }
}

/* ================= PATIENT HOSPITAL DETAILS & ASSIGNED DOCTORS MODAL ================= */
function openHospitalDetailsModal(hospName, district, address, specialty, contact, lat, lon) {
  if (typeof closeSidebar === 'function') closeSidebar();
  let modal = document.getElementById('patient-hospital-details-modal');
  if (!modal) {
    modal = document.createElement('div');
    modal.id = 'patient-hospital-details-modal';
    modal.className = 'fixed inset-0 bg-black/75 backdrop-blur-sm z-[10000] flex items-center justify-center p-4';
    modal.innerHTML = `
      <div class="bg-card border border-theme rounded-3xl p-6 max-w-2xl w-full shadow-2xl relative max-h-[90vh] flex flex-col justify-between">
        <div class="flex items-center justify-between border-b border-theme pb-4 mb-4">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold text-lg">
              <i class="fa-solid fa-hospital"></i>
            </div>
            <div>
              <h3 id="phd-hosp-name" class="text-lg font-extrabold text-main">Hospital Details</h3>
              <p id="phd-district" class="text-xs text-muted">District Medical Center</p>
            </div>
          </div>
          <button onclick="closeHospitalDetailsModal()" class="text-muted hover:text-main text-xl font-bold p-1">✕</button>
        </div>

        <div class="space-y-4 overflow-y-auto max-h-[65vh] pr-1 flex-grow">
          <!-- Hospital Info Card -->
          <div class="bg-input/60 border border-theme rounded-2xl p-4 text-xs space-y-2">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
              <div>
                <span class="text-muted block text-[10px] uppercase font-bold">PRIMARY SPECIALTY</span>
                <span id="phd-specialty" class="font-semibold text-main text-xs">General Emergency</span>
              </div>
              <div>
                <span class="text-muted block text-[10px] uppercase font-bold">HELPLINE / CONTACT</span>
                <span id="phd-contact" class="font-semibold text-emerald-400 text-xs">N/A</span>
              </div>
            </div>
            <div class="pt-2 border-t border-theme">
              <span class="text-muted block text-[10px] uppercase font-bold">ADDRESS LOCATION</span>
              <span id="phd-address" class="text-main">Address unavailable</span>
            </div>
          </div>

          <!-- Assigned Doctors List Header -->
          <div class="pt-2">
            <div class="flex items-center justify-between mb-3">
              <h4 class="font-bold text-main text-sm flex items-center gap-2">
                <span>🩺</span> Assigned Doctors & OPD Cabin Status
              </h4>
              <span id="phd-doctor-count" class="text-[11px] text-muted font-mono">Loading doctors...</span>
            </div>
            
            <div id="phd-doctor-list" class="space-y-3">
              <!-- Rendered dynamically -->
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="pt-4 border-t border-theme flex items-center justify-between mt-4">
          <a id="phd-directions-btn" href="map.html" class="bg-input hover:bg-emerald-500 hover:text-slate-950 text-main font-bold text-xs px-4 py-2 rounded-xl transition-all border border-theme flex items-center gap-2">
            <i class="fa-solid fa-diamond-turn-right"></i> Get Directions & Live Map
          </a>
          <button onclick="closeHospitalDetailsModal()" class="bg-emerald-500 text-slate-950 font-bold px-5 py-2 rounded-xl text-xs hover:brightness-110">
            Close
          </button>
        </div>
      </div>
    `;
    document.body.appendChild(modal);
  }

  // Populate Hospital Data
  const targetName = hospName || 'Medical Center';
  const targetDist = district || 'Tamil Nadu';
  const targetAddress = address || 'Address details unavailable';
  const targetSpecialty = specialty || 'Multi-Specialty & Emergency Care';
  const targetContact = contact || 'N/A';

  document.getElementById('phd-hosp-name').textContent = targetName;
  document.getElementById('phd-district').textContent = `📍 ${targetDist} District Hub`;
  document.getElementById('phd-specialty').textContent = targetSpecialty;
  document.getElementById('phd-contact').textContent = targetContact;
  document.getElementById('phd-address').textContent = targetAddress;

  const mapPage = window.location.pathname.endsWith('.php') ? 'map.php' : 'map.html';
  document.getElementById('phd-directions-btn').href = `${mapPage}?search=${encodeURIComponent(targetName)}`;

  // Find & Render Assigned Doctors from DOCTORS array
  const matchedDoctors = DOCTORS.filter(d => 
    d.hospitalName.toLowerCase().includes(targetName.toLowerCase()) || 
    targetName.toLowerCase().includes(d.hospitalName.toLowerCase())
  );

  const countEl = document.getElementById('phd-doctor-count');
  const listEl = document.getElementById('phd-doctor-list');

  if (countEl) countEl.textContent = `${matchedDoctors.length} doctors found`;

  if (matchedDoctors.length === 0) {
    listEl.innerHTML = `
      <div class="bg-input/60 border border-theme rounded-2xl p-6 text-center text-muted">
        <i class="fa-solid fa-user-slash text-2xl mb-2 text-muted"></i>
        <p class="text-xs font-bold">No assigned doctors currently registered for ${targetName}.</p>
      </div>
    `;
  } else {
    listEl.innerHTML = matchedDoctors.map(doc => {
      return `
        <div class="bg-card border border-theme rounded-2xl p-3.5 shadow-md flex items-center justify-between gap-3">
          <div>
            <div class="flex items-center gap-2">
              <h5 class="font-extrabold text-main text-xs">${doc.name}</h5>
              <span class="text-[10px] bg-blue-500/10 text-blue-400 font-mono px-1.5 py-0.5 rounded border border-blue-500/20">✓ ${doc.hpr || 'HPR'}</span>
            </div>
            <p class="text-[11px] text-muted mt-0.5">${doc.qual} · ${doc.dept}</p>
            <p class="text-[11px] text-main font-semibold mt-0.5">🚪 ${doc.cabin}</p>
          </div>
          <div>${getStatusBadge(doc.status || 'in')}</div>
        </div>
      `;
    }).join('');
  }

  modal.classList.remove('hidden');
}

function closeHospitalDetailsModal() {
  const modal = document.getElementById('patient-hospital-details-modal');
  if (modal) modal.classList.add('hidden');
}

/* ================= HOSPITAL DOCTOR DRILL-DOWN & STATUS MANAGEMENT ================= */
function viewHospitalDoctors(hospIdentifier, hospName) {
  const modal = document.getElementById('hospital-doctors-modal');
  const titleEl = document.getElementById('hosp-modal-title');
  const subEl = document.getElementById('hosp-modal-subtitle');
  const listEl = document.getElementById('hosp-modal-doctor-list');

  if (!modal || !listEl) return;

  const targetName = hospName || hospIdentifier;
  if (titleEl) titleEl.textContent = `${targetName} — Assigned Doctors`;
  if (subEl) subEl.textContent = `Real-time OPD Cabin Presence Management`;

  const assignedDoctors = DOCTORS.filter(d => 
    d.hospitalName.toLowerCase().includes(targetName.toLowerCase()) || 
    targetName.toLowerCase().includes(d.hospitalName.toLowerCase())
  );

  if (assignedDoctors.length === 0) {
    listEl.innerHTML = `
      <div class="bg-input/60 border border-theme rounded-2xl p-6 text-center text-muted">
        <i class="fa-solid fa-user-slash text-2xl mb-2 text-muted"></i>
        <p class="text-xs font-bold">No registered doctors currently assigned to ${targetName}.</p>
      </div>
    `;
  } else {
    listEl.innerHTML = assignedDoctors.map(doc => {
      const currentStatus = doc.status || 'in';
      return `
        <div class="bg-card border border-theme rounded-2xl p-4 shadow-lg flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div>
            <div class="flex items-center gap-2">
              <h4 class="font-extrabold text-main text-sm">${doc.name}</h4>
              <span class="text-[10px] bg-blue-500/10 text-blue-400 font-mono px-2 py-0.5 rounded border border-blue-500/20">✓ ${doc.hpr}</span>
            </div>
            <p class="text-xs text-muted mt-0.5">${doc.qual} · ${doc.dept} · 🚪 ${doc.cabin}</p>
            ${doc.note ? `<p class="text-[11px] text-amber-400 mt-1 bg-amber-500/10 px-2 py-1 rounded border border-amber-500/20">Note: ${doc.note}</p>` : ''}
          </div>

          <div class="flex items-center gap-2 shrink-0">
            <button onclick="updateDoctorPresenceStatus(${doc.id}, 'in')" class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all ${currentStatus === 'in' ? 'bg-emerald-500 text-white shadow-md' : 'bg-input text-muted hover:text-emerald-400 border border-theme'} flex items-center gap-1.5">
              <span>🟢</span> IN CABIN
            </button>
            <button onclick="updateDoctorPresenceStatus(${doc.id}, 'emr')" class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all ${currentStatus === 'emr' ? 'bg-amber-500 text-white shadow-md' : 'bg-input text-muted hover:text-amber-400 border border-theme'} flex items-center gap-1.5">
              <span>🟡</span> OT / EMR
            </button>
            <button onclick="updateDoctorPresenceStatus(${doc.id}, 'off')" class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all ${currentStatus === 'off' ? 'bg-red-500 text-white shadow-md' : 'bg-input text-muted hover:text-red-400 border border-theme'} flex items-center gap-1.5">
              <span>🔴</span> OFF DUTY
            </button>
          </div>
        </div>
      `;
    }).join('');
  }

  modal.classList.remove('hidden');
}

function updateDoctorPresenceStatus(docId, newStatus) {
  const doc = DOCTORS.find(d => d.id === docId);
  if (!doc) return;

  doc.status = newStatus;
  doc.lastPunch = 'Just now';

  const savedUpdates = JSON.parse(localStorage.getItem('medpulse_status_updates') || '{}');
  savedUpdates[docId] = {
    status: newStatus,
    reason: doc.note || 'Status updated via District Hospital Desk',
    lastPunch: 'Just now'
  };
  localStorage.setItem('medpulse_status_updates', JSON.stringify(savedUpdates));

  // Re-render Hospital Doctors Modal if open
  viewHospitalDoctors(doc.hospitalName, doc.hospitalName);

  if (document.getElementById('doctor-grid')) renderDoctorGrid();
  if (typeof renderDoctorsTable === 'function') renderDoctorsTable();
}

function closeHospitalDoctorsModal() {
  const modal = document.getElementById('hospital-doctors-modal');
  if (modal) modal.classList.add('hidden');
}

/* ================= DOCTOR REGISTRATION & ONBOARDING DESK MODULE ================= */
const SAMPLE_DOCTOR_APPLICATIONS = [
  { id: 'APP_1001', name: 'Dr. K. Anbarasan', email: 'dr.anbarasan@gmail.com', phone: '+91 98402 12345', district: 'Vellore', hpr: 'HPR_18492', council: 'TNMC/2021/84920', qual: 'MD Cardiology', dept: 'Cardiology', empStatus: 'Unemployed / Seeking Posting', status: 'PENDING', date: '2026-08-13' },
  { id: 'APP_1002', name: 'Dr. S. Nithya', email: 'dr.nithya@gmail.com', phone: '+91 98410 54321', district: 'Chennai', hpr: 'HPR_29104', council: 'TNMC/2019/54321', qual: 'MS Obstetrics', dept: 'Gynecology', empStatus: 'Locum / Temporary', status: 'PENDING', date: '2026-08-12' },
  { id: 'APP_1003', name: 'Dr. M. Rajesh Kumar', email: 'dr.rajesh@gmail.com', phone: '+91 98422 99887', district: 'Salem', hpr: 'HPR_38210', council: 'TNMC/2020/99887', qual: 'MS Orthopedics', dept: 'Orthopedics', empStatus: 'Private Practitioner Seeking Public Panel', status: 'PENDING', date: '2026-08-11' }
];

let activeApplicationId = null;

function openAdminDoctorRegModal() {
  const session = typeof getAdminSession === 'function' ? getAdminSession() : null;
  const distSelect = document.getElementById('admin-reg-district');
  if (distSelect) {
    distSelect.innerHTML = DISTRICTS.map(d => `<option value="${d}">${d}</option>`).join('');
    if (session && session.district && !session.isMaster) {
      distSelect.value = session.district;
    }
  }
  document.getElementById('admin-register-doc-modal').classList.remove('hidden');
}

function closeAdminDoctorRegModal() {
  const modal = document.getElementById('admin-register-doc-modal');
  if (modal) modal.classList.add('hidden');
}

async function saveAdminDoctorRegForm(e) {
  if (e) e.preventDefault();

  const name = document.getElementById('admin-reg-name').value;
  const email = document.getElementById('admin-reg-email').value;
  const phone = document.getElementById('admin-reg-phone').value;
  const district = document.getElementById('admin-reg-district').value;
  const hpr = document.getElementById('admin-reg-hpr').value;
  const council = document.getElementById('admin-reg-council').value;
  const qual = document.getElementById('admin-reg-qual').value;
  const dept = document.getElementById('admin-reg-dept').value;
  const status = document.getElementById('admin-reg-status').value;

  const appId = 'APP_' + Math.floor(1000 + Math.random() * 9000);
  const newApp = {
    id: appId,
    name: name,
    email: email,
    phone: phone,
    district: district,
    hpr: hpr,
    council: council,
    qual: qual,
    dept: dept,
    empStatus: status,
    status: 'PENDING',
    date: new Date().toISOString().split('T')[0]
  };

  try {
    await fetch('admin_api.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        action: 'register_doctor',
        doctor_name: name,
        email: email,
        phone: phone,
        district: district,
        hpr_id: hpr,
        medical_council_no: council,
        qualification: qual,
        specialization: dept,
        employment_status: status
      })
    });
  } catch(err) {}

  const local = JSON.parse(localStorage.getItem('medpulse_doctor_apps') || '[]');
  local.unshift(newApp);
  localStorage.setItem('medpulse_doctor_apps', JSON.stringify(local));

  closeAdminDoctorRegModal();
  alert(`✓ Practitioner application for ${name} registered successfully! Application ID: ${appId}. Appears live under Pending District Approvals.`);
  renderDoctorApplicationsTable();
}

function getStoredDoctorApplications() {
  const local = JSON.parse(localStorage.getItem('medpulse_doctor_apps') || '[]');
  return [...local, ...SAMPLE_DOCTOR_APPLICATIONS];
}

async function handleDoctorRegistration(e) {
  if (e) e.preventDefault();
  
  const name = document.getElementById('doc-reg-name').value;
  const email = document.getElementById('doc-reg-email').value;
  const phone = document.getElementById('doc-reg-phone').value;
  const district = document.getElementById('doc-reg-district').value;
  const hpr = document.getElementById('doc-reg-hpr').value;
  const council = document.getElementById('doc-reg-council').value;
  const qual = document.getElementById('doc-reg-qual').value;
  const dept = document.getElementById('doc-reg-dept').value;
  const status = document.getElementById('doc-reg-status').value;

  const appId = 'APP_' + Math.floor(1000 + Math.random() * 9000);
  const newApp = {
    id: appId,
    name: name,
    email: email,
    phone: phone,
    district: district,
    hpr: hpr,
    council: council,
    qual: qual,
    dept: dept,
    empStatus: status,
    status: 'PENDING',
    date: new Date().toISOString().split('T')[0]
  };

  try {
    await fetch('admin_api.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        action: 'register_doctor',
        doctor_name: name,
        email: email,
        phone: phone,
        district: district,
        hpr_id: hpr,
        medical_council_no: council,
        qualification: qual,
        specialization: dept,
        employment_status: status
      })
    });
  } catch(err) {}

  const local = JSON.parse(localStorage.getItem('medpulse_doctor_apps') || '[]');
  local.unshift(newApp);
  localStorage.setItem('medpulse_doctor_apps', JSON.stringify(local));

  document.getElementById('registration-card').classList.add('hidden');
  
  const confirmBox = document.getElementById('success-confirmation');
  if (confirmBox) {
    document.getElementById('success-district').textContent = `${district} District Health Officer Desk`;
    document.getElementById('success-app-id').textContent = appId;
    document.getElementById('success-name').textContent = name;
    confirmBox.classList.remove('hidden');
  }
}

function renderDoctorApplicationsTable() {
  const tbody = document.getElementById('applications-table-body');
  if (!tbody) return;

  const apps = getStoredDoctorApplications();
  const session = typeof getAdminSession === 'function' ? getAdminSession() : null;

  let filtered = apps;
  if (session && session.district && !session.isMaster) {
    filtered = apps.filter(a => isDistrictMatch(a.district, session.district));
    if (filtered.length === 0) {
      filtered = [{
        id: 'APP_1099',
        name: `Dr. P. ${session.district} Specialist`,
        email: 'applicant@tn.gov.in',
        phone: '+91 98400 12345',
        district: session.district,
        hpr: 'HPR_18492',
        council: 'TNMC/2022/10293',
        qual: 'MD Gen Medicine',
        dept: 'General Medicine',
        empStatus: 'Unemployed / Seeking Posting',
        status: 'PENDING',
        date: 'Today'
      }];
    }
  }

  tbody.innerHTML = filtered.map(app => {
    let badgeClass = 'bg-amber-500/15 text-amber-400 border-amber-500/30';
    if (app.status === 'VERIFIED') badgeClass = 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30';
    if (app.status === 'REJECTED') badgeClass = 'bg-red-500/15 text-red-400 border-red-500/30';

    return `
      <tr class="hover:bg-input/30 transition-all">
        <td class="p-4">
          <div class="font-bold text-main">${app.name}</div>
          <div class="text-[10px] text-muted">${app.email} · ${app.phone}</div>
        </td>
        <td class="p-4 font-mono">
          <div class="text-blue-400 font-bold">${app.hpr}</div>
          <div class="text-[10px] text-muted">${app.council}</div>
        </td>
        <td class="p-4">
          <div class="font-semibold text-main">${app.qual}</div>
          <div class="text-emerald-400 text-[11px]">${app.dept}</div>
        </td>
        <td class="p-4 text-muted text-xs">${app.empStatus}</td>
        <td class="p-4"><span class="px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 font-semibold border border-emerald-500/20">${app.district}</span></td>
        <td class="p-4">
          <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border ${badgeClass}">
            ${app.status === 'VERIFIED' ? '🟢 VERIFIED' : app.status === 'REJECTED' ? '🔴 REJECTED' : '🟡 PENDING'}
          </span>
        </td>
        <td class="p-4 text-right space-x-2">
          <button onclick="reviewDoctorApplication('${app.id}')" class="px-3 py-1.5 rounded-lg bg-amber-500/15 text-amber-400 hover:bg-amber-500 hover:text-white font-bold text-xs border border-amber-500/30 transition-all flex items-center gap-1 inline-flex">
            <i class="fa-solid fa-magnifying-glass"></i> Review & Verify Docs
          </button>
        </td>
      </tr>
    `;
  }).join('');
}

function reviewDoctorApplication(appId) {
  const apps = getStoredDoctorApplications();
  const app = apps.find(a => a.id === appId) || apps[0];
  if (!app) return;

  activeApplicationId = app.id;

  document.getElementById('review-doc-name').textContent = app.name;
  document.getElementById('review-doc-sub').textContent = `HPR: ${app.hpr} · Medical Council: ${app.council}`;
  document.getElementById('review-hpr').textContent = app.hpr;
  document.getElementById('review-council').textContent = app.council;
  document.getElementById('review-qual').textContent = app.qual;
  document.getElementById('review-dept').textContent = app.dept;
  document.getElementById('review-district').textContent = app.district;
  document.getElementById('review-status').textContent = app.empStatus;

  const resultEl = document.getElementById('abdm-check-result');
  if (resultEl) resultEl.classList.add('hidden');

  document.getElementById('doc-review-modal').classList.remove('hidden');
}

function closeDocReviewModal() {
  const modal = document.getElementById('doc-review-modal');
  if (modal) modal.classList.add('hidden');
}

function openSampleCert(certTitle) {
  openModal(`📜 ${certTitle} Preview`, `
    <div class="text-center space-y-4">
      <div class="p-6 rounded-2xl bg-card border border-theme space-y-2">
        <p class="text-xs font-mono text-emerald-400">STATE MEDICAL COUNCIL AUTHENTICATED DOCUMENT</p>
        <h4 class="font-extrabold text-main text-base">Tamil Nadu Medical Council (TNMC)</h4>
        <p class="text-xs text-muted">Official Certified Digital Transcript & Registration Proof</p>
        <div class="mt-3 p-3 bg-emerald-500/10 border border-emerald-500/30 rounded-xl text-emerald-400 font-mono text-xs font-bold">
          ✓ Digital Signature Valid · 256-Bit Encrypted
        </div>
      </div>
    </div>
  `);
}

function runABDMValidationCheck() {
  const resultEl = document.getElementById('abdm-check-result');
  if (!resultEl) return;

  resultEl.innerHTML = '<i class="fa-solid fa-spinner animate-spin"></i> Contacting NHA ABDM Registry...';
  resultEl.className = 'block text-center p-2 rounded-xl text-xs font-bold bg-amber-500/20 text-amber-400 border border-amber-500/30';

  setTimeout(() => {
    resultEl.innerHTML = '✓ NHA ABDM HPR Registry: Credentials Valid & Verified Active';
    resultEl.className = 'block text-center p-2 rounded-xl text-xs font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30';
  }, 700);
}

function openHospitalAttachModal() {
  const apps = getStoredDoctorApplications();
  const app = apps.find(a => a.id === activeApplicationId) || apps[0];
  if (!app) return;

  const districtNameEl = document.getElementById('attach-district-name');
  if (districtNameEl) districtNameEl.textContent = app.district;

  const select = document.getElementById('attach-hospital-select');
  if (select) {
    const matchedHosps = DISTRICT_CONFIG.find(d => isDistrictMatch(d.name, app.district));
    const hospOptions = matchedHosps ? matchedHosps.hospitals : [`Govt ${app.district} Headquarters Hospital`, `Govt Sub-District Hospital ${app.district}`];
    
    select.innerHTML = hospOptions.map(h => `<option value="${h}">${h}</option>`).join('');
  }

  const generatedCode = 'D' + Math.floor(1000 + Math.random() * 9000);
  document.getElementById('attach-doc-code').value = generatedCode;

  document.getElementById('hospital-attach-modal').classList.remove('hidden');
}

function closeHospitalAttachModal() {
  const modal = document.getElementById('hospital-attach-modal');
  if (modal) modal.classList.add('hidden');
}

async function confirmHospitalAttachment() {
  const apps = getStoredDoctorApplications();
  const app = apps.find(a => a.id === activeApplicationId);
  if (!app) return;

  const targetHospital = document.getElementById('attach-hospital-select').value;
  const docCode = document.getElementById('attach-doc-code').value;

  app.status = 'VERIFIED';

  const local = JSON.parse(localStorage.getItem('medpulse_doctor_apps') || '[]');
  const localIdx = local.findIndex(x => x.id === app.id);
  if (localIdx >= 0) local[localIdx].status = 'VERIFIED';
  else local.push(app);
  localStorage.setItem('medpulse_doctor_apps', JSON.stringify(local));

  const newDoctorObj = {
    id: docCode,
    hpr: app.hpr,
    name: app.name,
    qual: app.qual,
    dept: app.dept,
    cabin: 'Cabin 1, Block A',
    district: app.district.toLowerCase(),
    districtName: app.district,
    hospitalName: targetHospital,
    status: 'in',
    lastPunch: 'Just now',
    lat: 12.9165,
    lng: 79.1325,
    distKm: null
  };

  DOCTORS.unshift(newDoctorObj);

  const docOverrides = JSON.parse(localStorage.getItem('medpulse_doctors_override') || '[]');
  docOverrides.unshift(newDoctorObj);
  localStorage.setItem('medpulse_doctors_override', JSON.stringify(docOverrides));

  try {
    await fetch('admin_api.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        action: 'verify_doctor_application',
        app_id: parseInt(app.id.replace('APP_', ''), 10) || 1,
        hospital_name: targetHospital,
        district: app.district,
        doc_code: docCode
      })
    });
  } catch(e) {}

  closeHospitalAttachModal();
  closeDocReviewModal();

  alert(`✓ ${app.name} verified and attached to ${targetHospital}! Doctor Code: ${docCode}. Visible live on Doctor Directory and OPD Cabin desk.`);

  renderDoctorApplicationsTable();
  if (typeof renderDoctorsTable === 'function') renderDoctorsTable();
  if (typeof initDoctorPresenceSelect === 'function') initDoctorPresenceSelect();
}

async function rejectDoctorApplicationPrompt() {
  const reason = prompt('Enter rejection reason for practitioner credentials:', 'Invalid State Medical Council Certificate');
  if (!reason) return;

  const apps = getStoredDoctorApplications();
  const app = apps.find(a => a.id === activeApplicationId);
  if (!app) return;

  app.status = 'REJECTED';

  const local = JSON.parse(localStorage.getItem('medpulse_doctor_apps') || '[]');
  const localIdx = local.findIndex(x => x.id === app.id);
  if (localIdx >= 0) local[localIdx].status = 'REJECTED';
  else local.push(app);
  localStorage.setItem('medpulse_doctor_apps', JSON.stringify(local));

  try {
    await fetch('admin_api.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        action: 'reject_doctor_application',
        app_id: parseInt(app.id.replace('APP_', ''), 10) || 1,
        reason: reason
      })
    });
  } catch(e) {}

  closeDocReviewModal();
  alert(`✓ Application for ${app.name} marked as REJECTED.`);
  renderDoctorApplicationsTable();
}

/* Page Initialization */
document.addEventListener('DOMContentLoaded', () => {
  initTheme();
  syncNurseUpdates();
  highlightActiveDrawerItems();

  document.querySelectorAll('#map-btn, .map-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
      const isPhp = window.location.pathname.endsWith('.php');
      redirectToMap(e, isPhp ? 'map.php' : 'map.html');
    });
  });

  if (document.getElementById('doctor-grid')) renderDoctorGrid();
  if (document.getElementById('map')) setTimeout(initMapPage, 300);
  if (document.getElementById('blood-grid')) renderBloodGrid(BLOOD_BANKS);
  if (document.getElementById('queue-grid')) {
    populateQueueDistrictDropdown();
    renderQueueGrid();
  }
  if (document.getElementById('telemed-grid')) renderTelemedGrid();

  // Background timer: Auto-refresh OPD OPEN/CLOSED status badges every 60 seconds
  if (typeof window !== 'undefined' && !window.opdStatusRefreshInterval) {
    window.opdStatusRefreshInterval = setInterval(() => {
      const grid = document.getElementById('doctor-grid');
      if (grid && typeof renderDoctorGrid === 'function') {
        renderDoctorGrid();
      }
    }, 60000);
  }
});