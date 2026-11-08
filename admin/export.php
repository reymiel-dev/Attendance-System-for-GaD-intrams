<?php
    require "../config/database.php";

    $sql = "SELECT attendance_id, name, student_id, course_and_section, 
            timestamp FROM attendance_tbl ORDER BY timestamp DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $records = $stmt->fetchALL(PDO::FETCH_ASSOC);

    header("Content-Type: text/csv; charset=utf-8");
    header("Content-Disposition: attachment; filename=attendance_record.csv");

    $output = fopen("php://output", "w");

    fputcsv($output, ["ATTENDANCE NO.", "NAME", "STUDENT ID", "COURSE AND SECTION", "TIMESTAMP"]);
    
    foreach($records as $record){
        fputcsv($output, [
            $record["attendance_id"],
            $record["name"],
            "=\"" . $record["student_id"] . "\"",
            $record["course_and_section"],
            $record["timestamp"]
            ]);
    }

    fclose($output);
    exit;
?>