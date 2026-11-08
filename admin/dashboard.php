<?php
    require "../config/database.php";

    $sql = "SELECT * FROM attendance_tbl ORDER BY `timestamp` DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $attendanceRecord = $stmt->fetchALL(PDO::FETCH_ASSOC);
    $totalAttendance = count($attendanceRecord);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <main>
        <div class="header">
            <div class="info" >
                <h1>Attendance Records</h1>
                <h3>Total: <?php echo $totalAttendance; ?></h3>
            </div>
            <a href="export.php" class="export-btn">DOWNLOAD</a>
        </div>

        <table border="1" cellpadding="10">
            <thead>
                <tr>
                    <th>Attendance No.</th>
                    <th>Name</th>
                    <th>Student ID</th>
                    <th>Course and Section</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
                <?php if($attendanceRecord): ?>
                    <?php foreach($attendanceRecord as $record): ?>
                        <tr>
                            <td><?=htmlspecialchars($record["attendance_id"])?></td>
                            <td><?=htmlspecialchars($record["name"])?></td>
                            <td><?=htmlspecialchars($record["student_id"])?></td>
                            <td><?=htmlspecialchars($record["course_and_section"])?></td>
                            <td><?=htmlspecialchars($record["timestamp"])?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No attendance records yet.</td>
                    </tr>
                    <?php endif; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
