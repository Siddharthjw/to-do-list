<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = ""; 
$database = "c##sj"; 

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $task = $_POST["task"];
    $sql = "INSERT INTO tasks (task_name, completed) VALUES ('$task', 0)";

    if ($conn->query($sql) === TRUE) {
        // Redirect to prevent form resubmission on page refresh
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

if (isset($_GET["complete"])) {
    $taskId = $_GET["complete"];
    $sql = "UPDATE tasks SET completed = 1 WHERE id = $taskId";

    if ($conn->query($sql) === TRUE) {
        echo "Task marked as completed";
    } else {
        echo "Error marking task as completed: " . $conn->error;
    }
}

if (isset($_GET["delete"])) {
    $taskId = $_GET["delete"];
    $sql = "DELETE FROM tasks WHERE id = $taskId";

    if ($conn->query($sql) === TRUE) {
        echo "Task deleted successfully";
    } else {
        echo "Error deleting task: " . $conn->error;
    }
}

$result = $conn->query("SELECT * FROM tasks");
$tasks = [];
while ($row = $result->fetch_assoc()) {
    $tasks[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <h1>Todo List</h1>
    <div class="task-input-container">
        <form method="POST" action="">
            <input type="text" name="task" class="task-input" placeholder="Enter your task..." required>
            <button type="submit" class="add-task-button">Add Task</button>
        </form>
    </div>
    <h2>Tasks:</h2>
    <ul>
    <?php foreach ($tasks as $task) : ?>
        <li class="task-item">
            <div class="task-buttons">
                <input type="checkbox" id="checkbox-<?php echo $task['id']; ?>" class="task-checkbox" <?php echo ($task['completed'] == 1) ? 'checked' : ''; ?> disabled>
                <button class="complete-button" onclick="completeTask(<?php echo $task['id']; ?>)">Complete</button>
                <button class="delete-button" onclick="deleteTask(<?php echo $task['id']; ?>)">Delete</button>
                <button class="task-name-button"><?php echo $task['task_name']; ?></button>
            </div>
        </li>
    <?php endforeach; ?>
</ul>

<script>
    function completeTask(taskId) {
        const checkbox = document.getElementById(`checkbox-${taskId}`);
        checkbox.checked = true;
    }
</script>



    <script>
        function deleteTask(taskId) {
            if (confirm("Are you sure you want to delete this task?")) {
                window.location.href = `index.php?delete=${taskId}`;
            }
        }

        function completeTask(taskId) {
            window.location.href = `index.php?complete=${taskId}`;
        }
    </script>

    <script>
        const body = document.body;
        let position = 0;
        setInterval(() => {
            position -= 1;
            body.style.backgroundPosition = `${position}px 0`;
        }, 50);
    </script>
</body>

</html>
