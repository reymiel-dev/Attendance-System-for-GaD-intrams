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
            
            // echo json_encode(["success" => true, "name" =>name]);
            $message = "Success";
        }
        else{
            // echo json_encode(["success" => false]);
            $message = "Failed";
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
    <div class="stars">
        <span class="star"></span>
        <span class="star"></span>
        <span class="star"></span>
        <span class="star"></span>
        <span class="star"></span>
        <span class="star"></span>
        <span class="star"></span>
        <span class="star"></span>
        <span class="star"></span>
        <span class="star"></span>
    </div>

    <main class="panel" >
        <h1>Attendance</h1>
        <p>Enter you Student ID number:</p>
        
        <form action="index.php" method="post" id="attendance-record">
            <input type="text" id="student_id" name="student_id" placeholder="Enter Student Id" autofocus required>
            <button type="submit">OK
            </button>
        </form>

        <p><?php $message?></p>
    </main>
        
    <!-- <div id="result" class="result hidden">
        <div class="result-panel">
            <h1 class="result-title">UPDATE</h1>
            <p class="result-text">player <span><?php $name?></span> has joined the game.</p>
            <button type="button "id="result-button">Press Enter</button>

        </div>
    </div> -->


    <!-- <script>
        const form = document.getElementById("attendance_record");
        const overlay = document.getElementById("result");
        const okButton = document.getElementById("result-button");
        const input = document.getElementById("student_id");
        const resultText = document.getElementById("result-text");

        form.addEventListener("submit", =
            function(event){
                event.preventDefault();

                const formData = new FormData(form);

                fetch("index.php", {method: "POST", body: formData})
                .then(response => response.json())
                .then(data => {
                    overlay.classList.remove("hidden");
                    if(data.success) {
                        resultText.textContent = data.name + " has joined the game!";
                    }
                    else{
                        resultText.textContent = "Invalid Student ID!";
                    }
                })

                .catch(error => {
                    console.error("Error:", error);
                });
            }
        );

        document.getElementById("okButton").addEventListener("click", 
            function(){
                overlay.classList.add("hidden");
                form.reset();
                document.getElementById("student_id").focus();
        });
    </script> -->

</body>
</html>