<?php
header('Content-Type: application/json');
include 'db_connect.php';

$response = ['success' => false, 'message' => 'Invalid action'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
    $action = $input['action'] ?? '';

    if (!$db_connected) {
        echo json_encode(['success' => false, 'message' => 'Database connection offline. Changes saved to client session fallback.']);
        exit;
    }

    switch ($action) {

        // ---------------- DOCTOR PRESENCE STATUS UPDATE ----------------
        case 'update_doctor_status':
            $doc_code = $input['doc_code'] ?? '';
            $status = $input['status'] ?? 'in';
            $note = $input['note'] ?? '';

            if (!empty($doc_code)) {
                $stmt = $conn->prepare("UPDATE doctors SET status = ?, note = ? WHERE doc_code = ?");
                $stmt->bind_param("sss", $status, $note, $doc_code);
                if ($stmt->execute()) {
                    $response = ['success' => true, 'message' => 'Doctor presence status updated successfully.'];
                } else {
                    $response = ['success' => false, 'message' => 'Database update error: ' . $stmt->error];
                }
            } else {
                $response = ['success' => false, 'message' => 'Missing doctor code.'];
            }
            break;

        // ---------------- HOSPITAL SAVE (INSERT / UPDATE) ----------------
        case 'save_hospital':
            $id = $input['id'] ?? null;
            $hospital_name = trim($input['hospital_name'] ?? '');
            $district = trim($input['district'] ?? '');
            $specialty = trim($input['specialty'] ?? '');
            $address = trim($input['address'] ?? '');
            $contact = trim($input['contact'] ?? '');
            $lat = !empty($input['lat']) ? floatval($input['lat']) : null;
            $lon = !empty($input['lon']) ? floatval($input['lon']) : null;

            if (empty($hospital_name) || empty($district)) {
                $response = ['success' => false, 'message' => 'Hospital name and district are required.'];
                break;
            }

            if (!empty($id)) {
                // Update
                $stmt = $conn->prepare("UPDATE hospitals SET hospital_name = ?, district = ?, specialty = ?, address = ?, contact = ?, lat = ?, lon = ? WHERE id = ?");
                $stmt->bind_param("sssssddi", $hospital_name, $district, $specialty, $address, $contact, $lat, $lon, $id);
            } else {
                // Insert
                $stmt = $conn->prepare("INSERT INTO hospitals (hospital_name, district, specialty, address, contact, lat, lon) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssdd", $hospital_name, $district, $specialty, $address, $contact, $lat, $lon);
            }

            if ($stmt->execute()) {
                $response = ['success' => true, 'message' => 'Hospital record saved successfully.', 'id' => $id ?: $stmt->insert_id];
            } else {
                $response = ['success' => false, 'message' => 'Database error: ' . $stmt->error];
            }
            break;

        // ---------------- HOSPITAL DELETE ----------------
        case 'delete_hospital':
            $id = intval($input['id'] ?? 0);
            if ($id > 0) {
                $stmt = $conn->prepare("DELETE FROM hospitals WHERE id = ?");
                $stmt->bind_param("i", $id);
                if ($stmt->execute()) {
                    $response = ['success' => true, 'message' => 'Hospital deleted successfully.'];
                } else {
                    $response = ['success' => false, 'message' => 'Database error: ' . $stmt->error];
                }
            }
            break;

        // ---------------- DOCTOR SAVE (INSERT / UPDATE) ----------------
        case 'save_doctor':
            $id = $input['id'] ?? null;
            $doc_code = trim($input['doc_code'] ?? '');
            $hpr_id = trim($input['hpr_id'] ?? '');
            $doctor_name = trim($input['doctor_name'] ?? '');
            $qualification = trim($input['qualification'] ?? '');
            $department = trim($input['department'] ?? '');
            $hospital_name = trim($input['hospital_name'] ?? '');
            $district = trim($input['district'] ?? '');
            $cabin = trim($input['cabin'] ?? '');
            $status = $input['status'] ?? 'in';
            $note = trim($input['note'] ?? '');
            $lat = !empty($input['lat']) ? floatval($input['lat']) : null;
            $lon = !empty($input['lon']) ? floatval($input['lon']) : null;
            $opd_start_time = !empty($input['opd_start_time']) ? trim($input['opd_start_time']) : (!empty($input['opdStartTime']) ? trim($input['opdStartTime']) : null);
            $opd_end_time = !empty($input['opd_end_time']) ? trim($input['opd_end_time']) : (!empty($input['opdEndTime']) ? trim($input['opdEndTime']) : null);

            if (empty($doc_code) || empty($doctor_name) || empty($district)) {
                $response = ['success' => false, 'message' => 'Doctor Code, Name, and District are required.'];
                break;
            }

            if (!empty($id)) {
                // Update
                $stmt = $conn->prepare("UPDATE doctors SET doc_code = ?, hpr_id = ?, doctor_name = ?, qualification = ?, department = ?, hospital_name = ?, district = ?, cabin = ?, status = ?, note = ?, lat = ?, lon = ?, opd_start_time = ?, opd_end_time = ? WHERE id = ?");
                $stmt->bind_param("ssssssssssddssi", $doc_code, $hpr_id, $doctor_name, $qualification, $department, $hospital_name, $district, $cabin, $status, $note, $lat, $lon, $opd_start_time, $opd_end_time, $id);
            } else {
                // Insert
                $stmt = $conn->prepare("INSERT INTO doctors (doc_code, hpr_id, doctor_name, qualification, department, hospital_name, district, cabin, status, note, lat, lon, opd_start_time, opd_end_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssssssddss", $doc_code, $hpr_id, $doctor_name, $qualification, $department, $hospital_name, $district, $cabin, $status, $note, $lat, $lon, $opd_start_time, $opd_end_time);
            }

            if ($stmt->execute()) {
                $response = ['success' => true, 'message' => 'Doctor record saved successfully.', 'id' => $id ?: $stmt->insert_id];
            } else {
                $response = ['success' => false, 'message' => 'Database error: ' . $stmt->error];
            }
            break;

        // ---------------- DOCTOR DELETE ----------------
        case 'delete_doctor':
            $id = intval($input['id'] ?? 0);
            if ($id > 0) {
                $stmt = $conn->prepare("DELETE FROM doctors WHERE id = ?");
                $stmt->bind_param("i", $id);
                if ($stmt->execute()) {
                    $response = ['success' => true, 'message' => 'Doctor record deleted successfully.'];
                } else {
                    $response = ['success' => false, 'message' => 'Database error: ' . $stmt->error];
                }
            }
            break;

        // ---------------- BLOOD BANK SAVE (INSERT / UPDATE) ----------------
        case 'save_blood_bank':
            $id = $input['id'] ?? null;
            $blood_bank_name = trim($input['blood_bank_name'] ?? '');
            $district = trim($input['district'] ?? '');
            $address = trim($input['address'] ?? '');
            $blood_group = trim($input['blood_group'] ?? 'A+');
            $units = intval($input['units'] ?? 0);
            $contact = trim($input['contact'] ?? '');
            $lat = !empty($input['lat']) ? floatval($input['lat']) : null;
            $lon = !empty($input['lon']) ? floatval($input['lon']) : null;

            if (empty($blood_bank_name) || empty($district)) {
                $response = ['success' => false, 'message' => 'Blood bank name and district are required.'];
                break;
            }

            if (!empty($id)) {
                // Update
                $stmt = $conn->prepare("UPDATE blood_bank SET blood_bank_name = ?, district = ?, address = ?, blood_group = ?, units = ?, contact = ?, lat = ?, lon = ? WHERE id = ?");
                $stmt->bind_param("ssssisddi", $blood_bank_name, $district, $address, $blood_group, $units, $contact, $lat, $lon, $id);
            } else {
                // Insert
                $stmt = $conn->prepare("INSERT INTO blood_bank (blood_bank_name, district, address, blood_group, units, contact, lat, lon) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssisdd", $blood_bank_name, $district, $address, $blood_group, $units, $contact, $lat, $lon);
            }

            if ($stmt->execute()) {
                $response = ['success' => true, 'message' => 'Blood bank stock updated successfully.', 'id' => $id ?: $stmt->insert_id];
            } else {
                $response = ['success' => false, 'message' => 'Database error: ' . $stmt->error];
            }
            break;

        // ---------------- BLOOD BANK DELETE ----------------
        case 'delete_blood_bank':
            $id = intval($input['id'] ?? 0);
            if ($id > 0) {
                $stmt = $conn->prepare("DELETE FROM blood_bank WHERE id = ?");
                $stmt->bind_param("i", $id);
                if ($stmt->execute()) {
                    $response = ['success' => true, 'message' => 'Blood bank deleted successfully.'];
                } else {
                    $response = ['success' => false, 'message' => 'Database error: ' . $stmt->error];
                }
            }
            break;
        // ---------------- REGISTER DOCTOR APPLICATION ----------------
        case 'register_doctor':
            $doctor_name = trim($input['doctor_name'] ?? '');
            $email = trim($input['email'] ?? '');
            $phone = trim($input['phone'] ?? '');
            $district = trim($input['district'] ?? '');
            $hpr_id = trim($input['hpr_id'] ?? '');
            $medical_council_no = trim($input['medical_council_no'] ?? '');
            $qualification = trim($input['qualification'] ?? '');
            $specialization = trim($input['specialization'] ?? '');
            $employment_status = trim($input['employment_status'] ?? 'Unemployed / Seeking Posting');
            $degree_cert_url = $input['degree_cert_url'] ?? 'degree_cert_sample.pdf';
            $council_cert_url = $input['council_cert_url'] ?? 'council_cert_sample.pdf';

            if (empty($doctor_name) || empty($hpr_id) || empty($district)) {
                $response = ['success' => false, 'message' => 'Doctor name, HPR ID, and district are required.'];
                break;
            }

            $stmt = $conn->prepare("INSERT INTO doctor_applications (doctor_name, email, phone, district, hpr_id, medical_council_no, qualification, specialization, employment_status, degree_cert_url, council_cert_url, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'PENDING')");
            $stmt->bind_param("sssssssssss", $doctor_name, $email, $phone, $district, $hpr_id, $medical_council_no, $qualification, $specialization, $employment_status, $degree_cert_url, $council_cert_url);

            if ($stmt->execute()) {
                $response = ['success' => true, 'message' => 'Application submitted successfully.', 'app_id' => 'APP_' . $stmt->insert_id];
            } else {
                $response = ['success' => false, 'message' => 'Database error: ' . $stmt->error];
            }
            break;

        // ---------------- VERIFY & ATTACH DOCTOR TO HOSPITAL ----------------
        case 'verify_doctor_application':
            $app_id = intval($input['app_id'] ?? 0);
            $hospital_name = trim($input['hospital_name'] ?? '');
            $district = trim($input['district'] ?? '');
            $doc_code = trim($input['doc_code'] ?? ('D' . rand(1000, 9999)));

            if ($app_id > 0 && !empty($hospital_name)) {
                // Fetch application
                $resApp = $conn->query("SELECT * FROM doctor_applications WHERE id = $app_id");
                if ($resApp && $row = $resApp->fetch_assoc()) {
                    // Update status
                    $conn->query("UPDATE doctor_applications SET status = 'VERIFIED', assigned_hospital_id = '$hospital_name' WHERE id = $app_id");

                    // Insert into main doctors table
                    $name = $conn->real_escape_string($row['doctor_name']);
                    $hpr = $conn->real_escape_string($row['hpr_id']);
                    $qual = $conn->real_escape_string($row['qualification']);
                    $dept = $conn->real_escape_string($row['specialization']);
                    $hosp = $conn->real_escape_string($hospital_name);
                    $dist = $conn->real_escape_string($district ?: $row['district']);

                    $conn->query("INSERT INTO doctors (doc_code, hpr_id, doctor_name, qualification, department, hospital_name, district, cabin, status) VALUES ('$doc_code', '$hpr', '$name', '$qual', '$dept', '$hosp', '$dist', 'Cabin 1, Main Block', 'in')");

                    $response = ['success' => true, 'message' => 'Doctor verified and attached to hospital successfully.'];
                } else {
                    $response = ['success' => false, 'message' => 'Application record not found.'];
                }
            } else {
                $response = ['success' => false, 'message' => 'Missing application ID or hospital name.'];
            }
            break;

        // ---------------- REJECT DOCTOR APPLICATION ----------------
        case 'reject_doctor_application':
            $app_id = intval($input['app_id'] ?? 0);
            $reason = trim($input['reason'] ?? 'Invalid Medical Council Credentials');

            if ($app_id > 0) {
                $stmt = $conn->prepare("UPDATE doctor_applications SET status = 'REJECTED', rejection_reason = ? WHERE id = ?");
                $stmt->bind_param("si", $reason, $app_id);
                if ($stmt->execute()) {
                    $response = ['success' => true, 'message' => 'Doctor application marked as REJECTED.'];
                } else {
                    $response = ['success' => false, 'message' => 'Database error: ' . $stmt->error];
                }
            }
            break;
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? '';
    if ($action === 'get_all' && $db_connected) {
        $hospitals = [];
        $doctors = [];
        $blood_banks = [];
        $applications = [];

        $resH = $conn->query("SELECT * FROM hospitals ORDER BY district ASC, hospital_name ASC");
        if ($resH) { while ($row = $resH->fetch_assoc()) $hospitals[] = $row; }

        $resD = $conn->query("SELECT * FROM doctors ORDER BY district ASC, doctor_name ASC");
        if ($resD) { while ($row = $resD->fetch_assoc()) $doctors[] = $row; }

        $resB = $conn->query("SELECT * FROM blood_bank ORDER BY district ASC, blood_bank_name ASC");
        if ($resB) { while ($row = $resB->fetch_assoc()) $blood_banks[] = $row; }

        $resA = $conn->query("SELECT * FROM doctor_applications ORDER BY created_at DESC");
        if ($resA) { while ($row = $resA->fetch_assoc()) $applications[] = $row; }

        $response = [
            'success' => true,
            'hospitals' => $hospitals,
            'doctors' => $doctors,
            'blood_banks' => $blood_banks,
            'applications' => $applications
        ];
    }
}

echo json_encode($response);
?>
