<?php
    require "../config/database.php";

    $status = "";
    $message = "";
    $student = null;
    $recordedAt = "";

    if($_SERVER["REQUEST_METHOD"] === "POST"){
        $studentId = trim($_POST["student_id"] ?? "");

        if($studentId === ""){
            $status = "error";
            $message = "Please enter a student ID.";
        } else {
            $stmt = $conn->prepare("SELECT name, student_id, 
                                    course_and_section FROM students WHERE student_id = ?");
            $stmt->execute([$studentId]);
            $student = $stmt->fetch(PDO::FETCH_ASSOC);

            if(!$student){
                $status = "error";
                $message = "Student ID not found.";
            } else {
                $stmt = $conn->prepare(
                    "SELECT timestamp FROM attendance_tbl WHERE student_id = ?
                    -- AND timestamp >= (NOW() - INTERVAL 1 MINUTE)"
                );
                $stmt->execute([$studentId]);
                $recent = $stmt->fetch(PDO::FETCH_ASSOC);

                if($recent){
                    $status = "duplicate";
                    $message = "Already recorded a moment ago.";
                    $recordedAt = $recent["timestamp"];
                } else {
                    $stmt = $conn->prepare(
                        "INSERT INTO attendance_tbl (name, student_id, course_and_section) VALUES (?, ?, ?)"
                    );
                    $stmt->execute([
                        $student["name"],
                        $student["student_id"],
                        $student["course_and_section"]
                    ]);

                    $stmt = $conn->prepare(
                        "SELECT timestamp FROM attendance_tbl WHERE attendance_id = ?"
                    );
                    $stmt->execute([$conn->lastInsertId()]);
                    $recordedAt = $stmt->fetchColumn();
                }
            }
        }

    }

    $stmt = $conn->prepare("SELECT COUNT(*) FROM attendance_tbl");
    $stmt->execute();
    $total = $stmt->fetchColumn();

    $stmt = $conn->prepare(
        "SELECT name, student_id, course_and_section, timestamp FROM attendance_tbl ORDER BY timestamp DESC"
    );
    $stmt->execute();
    $history = $stmt->fetchALL(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance</title>
    <link rel="stylesheet" href="booth.css">
</head>
<body>

    <main class="booth">
        <section class="col" id="input-panel">
            <h1 class="text-header" id="attendance-head">Attendance</h1>

            <form action="booth.php" method="post" autocomplete="off">
                <label for="student_id">Enter Student ID</label>
                <input type="text" id="student_id" name="student_id" autofocus>
            </form>

            <?php if($status !== ""): ?>
                <div class="feedback <?= htmlspecialchars($status)?>">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <?php if($student): ?>
                <div class="student-panel">
                    <h3 class="text-header" id="info-text-header">Student Information</h3>
                    <p><span>Name:</span> <?= htmlspecialchars($student["name"]) ?></p>
                    <p><span>Student ID:</span> <?= htmlspecialchars($student["student_id"]) ?></p>
                    <p><span>Course & Section:</span> <?= htmlspecialchars($student["course_and_section"]) ?></p>
                    <p><span>Date and Time:</span> <?= htmlspecialchars($recordedAt) ?></p>
                </div>
            <?php endif; ?>
        </section>

        <section class="col" id="history-box">
            <div class="history-head">
                <h2 class="text-header">History</h2>
                <div class="total">
                   Total: <strong><?= (int)$total ?></strong>
                </div>
                <div class="download-box">
                    <a href="../admin/export.php" class="download-btn">Download</a>
                </div>
            </div>
            <div class="table-wrap">
                <table>
                    <div class="table-column">
                        <thead>
                            <div class="colcol">
                                <tr>
                                    <th>Name</th>
                                    <th>Student ID</th>
                                    <th>Course & Section</th>
                                    <th>Date & Time</th>
                                </tr>
                            </div>
                        </thead>
                        <tbody>
                            <div class="colcol">
                                <?php foreach($history as $row): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row["name"]) ?></td>
                                        <td><?= htmlspecialchars($row["student_id"]) ?></td>
                                        <td><?= htmlspecialchars($row["course_and_section"]) ?></td>
                                        <td><?= htmlspecialchars($row["timestamp"]) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </div>
                        </tbody>
                    </div>
                </table>
            </div>
        </section>
            <!-- <section class="col">
                <a href="../admin/export.php" class="download-btn">Download</a>
            </section> -->
    </main> 

</body>
</html>