<?php

function buat_pengaduan($user_id, $message, $image, $conn) {
    // Prepare the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO reports (user_id, message, image, status, created_at) VALUES (?, ?, ?, ?, NOW())");

    // Default value for status
    $status = 'proses';

    // Bind parameters
    $stmt->bind_param("ssss", $user_id, $message, $image, $status);

    // Execute and return the result
    return $stmt->execute();
}

// Function to get reports by user status
function get_pengaduan_by_status($username, $status, $conn) {
    // Query to get the user's ID based on username
    $query_id = "SELECT id FROM user WHERE username = '$username'";
    $result_id = mysqli_query($conn, $query_id);

    // Check if the query succeeded and fetch ID
    if (!$result_id || mysqli_num_rows($result_id) === 0) {
        return []; // Return empty if the user is not found
    }
    $row = mysqli_fetch_assoc($result_id);
    $id = $row['id'];

    // Query to get the reports based on user ID and status
    $query = "SELECT * FROM reports WHERE user_id = '$id' AND status = '$status'";
    $result = mysqli_query($conn, $query);

    // Check if the query succeeded
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    // Store the fetched reports in an array
    $pengaduan = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $pengaduan[] = $row;
    }
    return $pengaduan;
}


function get_all_pengaduan_by_status($status, $conn) {
    //membuat query
    $query = "SELECT * FROM reports WHERE status = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $status);

    $stmt->execute();

    $result = $stmt->get_result();

    

    $pengaduan = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $pengaduan[] = $row;
    }
    return $pengaduan;
};
//function feedback
function addFeedback($report_id, $petugas_id, $feedback, $conn) {

    $conn->begin_transaction();
 
    try {
        // Prepare the insert query
        $stmt = $conn->prepare("INSERT INTO feedback(report_id, feedback, petugas_id, created_at) VALUES (?, ?, ?, NOW())");    
        $stmt->bind_param("isi", $report_id, $feedback, $petugas_id); // Perhatikan "isi" harus "isi"

        if (!$stmt->execute()) {
            throw new Exception("Gagal menyimpan feedback");
        }

        $updatestmt = $conn->prepare("UPDATE reports SET status = 'selesai' WHERE id = ?");
        $updatestmt->bind_param("i", $report_id);


        if (!$updatestmt->execute()) {
            throw new Exception("Gagal mengubah status");
        }

        $conn->commit();
        return true;

    } catch (Throwable $th) {

        $conn->rollback();
        echo "Error: " . $th->getMessage();
        return false;
    }
}

function get_reports_with_feedback_by_status($conn) {
    $query = "SELECT
    reports.id,
    user.username AS pelapor,
    reports.message, reports.image,
    reports.created_at AS report_date,
    feedback.feedback, 
    feedback.created_at AS feedback_date
    
    FROM reports
    LEFT JOIN feedback ON reports.id = feedback.report_id
    LEFT JOIN user ON reports.user_id = user.id
    WHERE reports.status ='selesai'
    ORDER BY reports.created_at DESC";

$result = $conn->query($query);

$data = [];
while($row =$result->fetch_assoc()){
    $data[] = $row;
}
return $data;
}

function get_reports_with_feedback_by_user($username ,$conn) {
    //ambil user id berdasarkan yg login
    $user_id = $conn->query ("SELECT id FROM user WHERE username = '$username'")->fetch_assoc()['id'];
    //mendapatkan laporann dgn feedback sesuai user
    $query = "SELECT
    reports.id,
    reports.message,
    reports.created_at AS report_date,
    feedback.feedback,
    feedback.created_at AS feedback_date
    FROM reports
    LEFT JOIN feedback ON reports.id = feedback.report_id
    WHERE reports.user_id = ? AND reports.status = 'selesai'
    ORDER BY reports.created_at DESC";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
while($row =$result->fetch_assoc()){
    $data[] = $row;
}
return $data;
}




?>
