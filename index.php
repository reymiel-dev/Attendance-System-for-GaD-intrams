<?php
    require "config/database.php";

    $message = "";

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $studentId = trim($_POST["student_id"]);

        $sql = "SELECT * FROM students WHERE student_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$studentId]);

        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        if($student){
            $name = $student["name"];
            $courseAndSection = $student["course_and_section"];
            $insert = "INSERT INTO attendance_tbl (name, student_id, course_and_section) VALUES(?, ?, ?)";
            
            $stmt = $conn->prepare($insert);
            $stmt->execute([$name, $studentId, $courseAndSection]);
            
            $message = "Attendance recorded successfully!, " . htmlspecialchars($name);
        }
        else{
            $message = "Student ID not found!";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Attendance System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
    <h1>Student Attendance System</h1>
    <p>Enter you Student ID number:</p>

    <form action="index.php" method="post">
        <input type="text" name="student_id" placeholder="Enter Student ID" required>
        <button type="submit">Submit</button>
    </form>

    <p><?php echo $message; ?></p>

</body>
</html>