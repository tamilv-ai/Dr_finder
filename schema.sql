-- MedPulse TN Database Initialization Script (All 38 Districts)
CREATE DATABASE IF NOT EXISTS `medpulse_tn` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `medpulse_tn`;

-- Hospitals Table
CREATE TABLE IF NOT EXISTS `hospitals` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `hospital_name` VARCHAR(255) NOT NULL,
  `district` VARCHAR(100) NOT NULL,
  `specialty` VARCHAR(255) NOT NULL,
  `address` TEXT NOT NULL,
  `contact` VARCHAR(50) NOT NULL,
  `lat` DECIMAL(10, 8) NULL,
  `lon` DECIMAL(11, 8) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Blood Bank Table
CREATE TABLE IF NOT EXISTS `blood_bank` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `blood_bank_name` VARCHAR(255) NOT NULL,
  `district` VARCHAR(100) NOT NULL,
  `address` TEXT NOT NULL,
  `blood_group` VARCHAR(10) NOT NULL,
  `units` INT NOT NULL DEFAULT 0,
  `contact` VARCHAR(50) NOT NULL,
  `lat` DECIMAL(10, 8) NULL,
  `lon` DECIMAL(11, 8) NULL,
  `last_updated` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Doctors Table
CREATE TABLE IF NOT EXISTS `doctors` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `doc_code` VARCHAR(20) UNIQUE NOT NULL,
  `hpr_id` VARCHAR(50) NOT NULL,
  `doctor_name` VARCHAR(255) NOT NULL,
  `qualification` VARCHAR(100) NOT NULL,
  `department` VARCHAR(100) NOT NULL,
  `hospital_name` VARCHAR(255) NOT NULL,
  `district` VARCHAR(100) NOT NULL,
  `cabin` VARCHAR(100) NOT NULL,
  `status` ENUM('in', 'emr', 'off') DEFAULT 'in',
  `note` TEXT NULL,
  `lat` DECIMAL(10, 8) NULL,
  `lon` DECIMAL(11, 8) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Doctor Applications Table (Onboarding & Verification Desk)
CREATE TABLE IF NOT EXISTS `doctor_applications` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `doctor_name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(191) NOT NULL,
  `phone` VARCHAR(50) NOT NULL,
  `district` VARCHAR(100) NOT NULL,
  `hpr_id` VARCHAR(50) NOT NULL,
  `medical_council_no` VARCHAR(100) NOT NULL,
  `qualification` VARCHAR(100) NOT NULL,
  `specialization` VARCHAR(100) NOT NULL,
  `employment_status` VARCHAR(100) DEFAULT 'Unemployed / Seeking Posting',
  `degree_cert_url` VARCHAR(255) NULL,
  `council_cert_url` VARCHAR(255) NULL,
  `status` ENUM('PENDING', 'VERIFIED', 'REJECTED') DEFAULT 'PENDING',
  `rejection_reason` VARCHAR(255) NULL,
  `assigned_hospital_id` VARCHAR(100) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed Data: Hospitals across all 38 Districts of Tamil Nadu
INSERT INTO `hospitals` (`hospital_name`, `district`, `specialty`, `address`, `contact`, `lat`, `lon`) VALUES
('Govt Headquarters Hospital Ariyalur', 'Ariyalur', 'General Medicine, Emergency Trauma', 'Jayankondam Road, Ariyalur - 621704', '+91 4329 220 231', 11.14010000, 79.07820000),
('Chengalpattu Govt Medical College Hospital', 'Chengalpattu', 'Multi-Specialty, Intensive Care, Trauma', 'GST Road, Chengalpattu - 603001', '+91 44 2742 6566', 12.68410000, 79.98360000),
('Rajiv Gandhi Government General Hospital (RGGGH)', 'Chennai', 'Cardiology, Neurology, Multi-Specialty Trauma', 'EVR Periyar Salai, Park Town, Chennai - 600003', '+91 44 2530 5000', 13.08270000, 80.27070000),
('Govt Stanley Medical College Hospital', 'Chennai', 'Plastic Surgery, Pediatrics, Emergency Care', 'Old Jail Road, Royapuram, Chennai - 600013', '+91 44 2528 1351', 13.10750000, 80.28720000),
('Kilpauk Medical College Hospital', 'Chennai', 'Burns Specialist, Nephrology, General Surgery', 'PH Road, Kilpauk, Chennai - 600010', '+91 44 2836 4951', 13.07840000, 80.24310000),
('Coimbatore Medical College Hospital', 'Coimbatore', 'Cardiology, Orthopedics, Intensive Care', 'Trichy Road, Coimbatore - 641018', '+91 422 230 1393', 11.01680000, 76.95580000),
('Cuddalore Govt Headquarters Hospital', 'Cuddalore', 'General Surgery, Pediatrics, Emergency Unit', 'Manjakuppam, Cuddalore - 607001', '+91 4142 230 331', 11.74800000, 79.77140000),
('Dharmapuri Medical College Hospital', 'Dharmapuri', 'Trauma Care, General Medicine, Orthopedics', 'Netaji Bypass Road, Dharmapuri - 636701', '+91 4342 233 255', 12.13570000, 78.15600000),
('Dindigul Govt Headquarters Hospital', 'Dindigul', 'Obstetrics, Pediatrics, Emergency Center', 'Sub Collector Office Road, Dindigul - 624001', '+91 451 242 3200', 10.36730000, 77.98030000),
('Govt Medical College Hospital Perundurai', 'Erode', 'Respiratory Medicine, General Medicine', 'Perundurai, Erode - 638053', '+91 424 253 3302', 11.34100000, 77.71720000),
('Kallakurichi Govt Headquarters Hospital', 'Kallakurichi', 'Emergency Care, General Surgery', 'Kachirapalayam Road, Kallakurichi - 606202', '+91 4151 222 340', 11.73840000, 78.96390000),
('Kanchipuram District Headquarters Hospital', 'Kancheepuram', 'Emergency Care, Obstetrics & Gynecology', 'Railway Station Road, Kanchipuram - 631501', '+91 44 2722 2434', 12.83420000, 79.70360000),
('Govt Headquarters Hospital Kanyakumari', 'Kanyakumari', 'Emergency Care, General Surgery', 'Asaripallam, Nagercoil - 629001', '+91 4652 230 461', 8.18330000, 77.41190000),
('Govt Medical College Hospital Karur', 'Karur', 'General Medicine, Trauma Unit', 'Gandhigramam, Karur - 639004', '+91 4324 225 300', 10.96010000, 78.07660000),
('Krishnagiri Govt Headquarters Hospital', 'Krishnagiri', 'Emergency Care, Orthopedics', 'Royakottai Road, Krishnagiri - 635001', '+91 4343 232 400', 12.51860000, 78.21370000),
('Madurai Govt Rajaji Hospital', 'Madurai', 'Multi-Specialty, Cardiology, Pediatrics', 'Panagal Road, Shenoy Nagar, Madurai - 625020', '+91 452 253 2535', 9.92520000, 78.11980000),
('Mayiladuthurai Govt District Hospital', 'Mayiladuthurai', 'General Medicine, Emergency Surgery', 'Hospital Road, Mayiladuthurai - 609001', '+91 4364 222 320', 11.10180000, 79.65250000),
('Nagapattinam Govt Medical College Hospital', 'Nagapattinam', 'Multi-Specialty, Emergency Unit', 'Public Office Road, Nagapattinam - 611001', '+91 4365 222 300', 10.76720000, 79.84490000),
('Govt Medical College Hospital Namakkal', 'Namakkal', 'General Surgery, Cardiology', 'Collectorate Complex, Namakkal - 637003', '+91 4286 221 400', 11.21890000, 78.16740000),
('Ooty Govt District Headquarters Hospital', 'Nilgiris', 'Hill Emergency Unit, Respiratory Care', 'Hospital Road, Ooty - 643001', '+91 423 244 2212', 11.41020000, 76.69500000),
('Perambalur Govt Headquarters Hospital', 'Perambalur', 'Emergency Care, General Medicine', 'Trichy Main Road, Perambalur - 621212', '+91 4328 277 300', 11.23420000, 78.88200000),
('Govt Pudukkottai Medical College Hospital', 'Pudukkottai', 'Multi-Specialty, ICU, Pediatrics', 'Mulamangalam, Pudukkottai - 622004', '+91 4322 221 500', 10.38330000, 78.80010000),
('Ramanathapuram Govt Medical College Hospital', 'Ramanathapuram', 'Emergency Surgery, Cardiology', 'Rameswaram Road, Ramanathapuram - 623501', '+91 4567 230 400', 9.36390000, 78.83950000),
('Ranipet Govt District Hospital', 'Ranipet', 'General Medicine, Emergency Trauma', 'MBT Road, Ranipet - 632401', '+91 4172 272 500', 12.92960000, 79.33310000),
('Govt Mohan Kumaramangalam Medical College Hospital', 'Salem', 'Neurology, Oncology, Emergency Trauma Center', 'Steel Plant Road, Salem - 636030', '+91 427 226 0204', 11.66430000, 78.14600000),
('Sivaganga Govt Medical College Hospital', 'Sivaganga', 'Multi-Specialty, Pediatrics, ICU', 'Melavaniyangudi, Sivaganga - 630562', '+91 4575 240 600', 9.84330000, 78.48090000),
('Tenkasi Govt District Headquarters Hospital', 'Tenkasi', 'Emergency Care, General Surgery', 'Railway Feeder Road, Tenkasi - 627811', '+91 4633 222 400', 8.95930000, 77.31500000),
('Thanjavur Medical College Hospital', 'Thanjavur', 'Cardiology, Neurosurgery, Critical Care', 'Medical College Road, Thanjavur - 613004', '+91 4362 240 011', 10.78700000, 79.13780000),
('Govt Theni Medical College Hospital', 'Theni', 'Multi-Specialty, Emergency Unit', 'K.Vellaimalaimedu, Theni - 625512', '+91 4546 263 700', 10.01040000, 77.47680000),
('Thoothukudi Govt Medical College Hospital', 'Thoothukudi', 'Cardiology, Pediatrics, Intensive Care', '3rd Mile, Thoothukudi - 628008', '+91 461 239 2200', 8.76420000, 78.13480000),
('Govt KAP Viswanatham Medical College Hospital', 'Tiruchirappalli', 'Gastroenterology, Orthopedics, General Care', 'Periyamilaguparai, Tiruchirappalli - 620001', '+91 431 240 1011', 10.79050000, 78.70470000),
('Tirunelveli Medical College Hospital', 'Tirunelveli', 'Nephrology, Pediatrics, Cardiology', 'High Ground, Tirunelveli - 627011', '+91 462 257 2733', 8.71390000, 77.75670000),
('Tirupathur Govt Headquarters Hospital', 'Tirupathur', 'General Surgery, Emergency Care', 'GH Road, Tirupathur - 635601', '+91 4179 220 300', 12.49290000, 78.56860000),
('Govt Medical College Hospital Tiruppur', 'Tiruppur', 'Multi-Specialty, Intensive Care', 'Palani Road, Tiruppur - 641604', '+91 421 224 2000', 11.10850000, 77.34110000),
('Govt Medical College Hospital Tiruvallur', 'Tiruvallur', 'General Medicine, Emergency Trauma', 'CVD Road, Tiruvallur - 602001', '+91 44 2766 0300', 13.14320000, 79.90700000),
('Tiruvannamalai Govt Medical College Hospital', 'Tiruvannamalai', 'Nephrology, Pediatrics, Trauma Center', 'Outer Ring Road, Tiruvannamalai - 606604', '+91 4175 222 500', 12.22530000, 79.07470000),
('Govt Tiruvarur Medical College Hospital', 'Tiruvarur', 'Multi-Specialty, Emergency Surgery', 'Thandalai, Tiruvarur - 610004', '+91 4366 228 000', 10.77260000, 79.63650000),
('Vellore Government Medical College Hospital', 'Vellore', 'Pulmonology, General Surgery, ICU', 'Adukkamparai, Vellore - 632011', '+91 416 226 0900', 12.91650000, 79.13250000),
('Govt Villupuram Medical College Hospital', 'Viluppuram', 'Multi-Specialty, Emergency Unit', 'Mundiyampakkam, Villupuram - 605602', '+91 4146 232 500', 11.94010000, 79.48610000),
('Govt Medical College Hospital Virudhunagar', 'Virudhunagar', 'General Medicine, Intensive Care', 'Desigapuram, Virudhunagar - 626001', '+91 4562 243 500', 9.56800000, 77.96240000);

-- Seed Data: Blood Banks
INSERT INTO `blood_bank` (`blood_bank_name`, `district`, `address`, `blood_group`, `units`, `contact`, `lat`, `lon`) VALUES
('RGGGH Central Blood Bank', 'Chennai', 'EVR Periyar Salai, Park Town, Chennai', 'A+', 28, '+91 44 2530 5000', 13.08270000, 80.27070000),
('RGGGH Central Blood Bank', 'Chennai', 'EVR Periyar Salai, Park Town, Chennai', 'O+', 45, '+91 44 2530 5000', 13.08270000, 80.27070000),
('Stanley Hospital Blood Center', 'Chennai', 'Old Jail Road, Royapuram, Chennai', 'O-', 8, '+91 44 2528 1351', 13.10750000, 80.28720000),
('Salem Medical College Blood Bank', 'Salem', 'Steel Plant Road, Salem', 'B+', 18, '+91 427 226 0204', 11.66430000, 78.14600000),
('Coimbatore GH Blood Bank', 'Coimbatore', 'Trichy Road, Coimbatore', 'A+', 30, '+91 422 230 1393', 11.01680000, 76.95580000),
('Madurai Rajaji Blood Center', 'Madurai', 'Panagal Road, Madurai', 'AB-', 2, '+91 452 253 2535', 9.92520000, 78.11980000);

-- Seed Data: Doctors
INSERT INTO `doctors` (`doc_code`, `hpr_id`, `doctor_name`, `qualification`, `department`, `hospital_name`, `district`, `cabin`, `status`, `note`, `lat`, `lon`) VALUES
('D1001', 'HPR_18492', 'Dr. K. Raman', 'MD Cardiology', 'Cardiology', 'Rajiv Gandhi Government General Hospital (RGGGH)', 'Chennai', 'Cabin 1, Block A', 'in', 'In OPD Consultation', 13.08270000, 80.27070000),
('D1002', 'HPR_29104', 'Dr. S. Meenakshi', 'DM Neurology', 'Neurology', 'Govt Stanley Medical College Hospital', 'Chennai', 'Cabin 3, Block B', 'emr', 'In Emergency Surgery Wing', 13.10750000, 80.28720000),
('D1007', 'HPR_30491', 'Dr. P. Vijayakumar', 'MS Orthopedics', 'Orthopedics', 'Govt Mohan Kumaramangalam Medical College Hospital', 'Salem', 'Cabin 2, Block C', 'in', 'Available for OP Patients', 11.66430000, 78.14600000),
('D1013', 'HPR_41920', 'Dr. M. Sundar', 'MD Cardiology', 'Cardiology', 'Coimbatore Medical College Hospital', 'Coimbatore', 'Cabin 1, Main Block', 'in', 'Conducting rounds until 1:00 PM', 11.01680000, 76.95580000),
('D1019', 'HPR_52819', 'Dr. R. Anitha', 'MS Obstetrics', 'Gynecology', 'Madurai Govt Rajaji Hospital', 'Madurai', 'Cabin 4, Maternity Block', 'off', 'Post-duty rest', 9.92520000, 78.11980000);
