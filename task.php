<?PHP
DEFINE("TASKS_FILE", "tasks.json"); //Define a constant for the tasks file (tasks.json)

// Create a function to load tasks from the tasks.json file and 
// This function should read the JSON file and return the decoded array
function loadTasks(): array {
    if(!file_exists(TASKS_FILE)){
        return [];
    }
    $data=file_get_contents(TASKS_FILE);
    return $data ? json_decode($data,true): [];
}
$tasks=loadTasks(); // Load tasks from the tasks.json file

// Create a function to save tasks to the tasks.json file and
// This function should take an array of tasks and save it back to the JSON file
function saveTasks(array $tasks): void {
    file_put_contents(TASKS_FILE,json_encode($tasks,JSON_PRETTY_PRINT));
}
// Check if the form has been submitted using POST request
if($_SERVER['REQUEST_METHOD']==='POST'){
    //Use if-else to handle adding, deleting, and marking tasks. Save and load tasks from the tasks.json file.
    if(isset($_POST['task']) && !empty(trim($_POST['task']))){ 
        // add tasks as user input the data
        $tasks [] = [
            'task'=> htmlspecialchars($_POST['task']), //Use htmlspecialchars to avoid security issues like XSS.
            'done'=>false, 
        ];
        saveTasks($tasks); //Save Tasks After Adding
        header('Location: '. $_SERVER['PHP_SELF']); //Redirect After Action After adding a task, the page should reload automatically.
        exit;

    }elseif(isset($_POST['delete'])){
        //Delete Task
        unset($tasks[$_POST['delete']]);
        $tasks = array_values($tasks); 
        saveTasks($tasks); //Save Tasks After Deleting
        header('Location: ' . $_SERVER['PHP_SELF']); //Redirect after deleting a task, the page should reload automatically.
        exit;

    }elseif(isset($_POST['toggle'])){
        //Mark Task as Done/Undone
        $tasks[$_POST['toggle']]['done'] = !$tasks[$_POST['toggle']]['done'];
        saveTasks($tasks);//Save Tasks After Marking
        header('Location: ' . $_SERVER['PHP_SELF']); //Redirect After Action After marking a task, the page should reload automatically.
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>To Do App</title>
    <!-- Use basic CSS Milligram for styling.-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.min.css">
    <style>
        body {
            margin-top: 20px;
        }
        .task-card {
            border: 1px solid #ececec; 
            padding: 20px;
            border-radius: 5px;
            background: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); 
        }
        .task{
            color: #888;
        }
        .task-done {
            text-decoration: line-through;
            color: #888;
        }
        .task-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        ul {
            padding-left: 20px;
        }
        button {
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="container">
        <div class="task-card">
            <h1>To-Do App</h1>
            <!-- Add Task Form -->
            <form method="POST">
                <div class="row">
                    <div class="column column-75">
                        <input type="text" name="task" placeholder="Enter a new task" required>
                    </div>
                    <div class="column column-25">
                        <button type="submit" class="button-primary">Add Task</button>
                    </div>
                </div>
            </form>

            <!-- Task List -->
            <h2>Task List</h2>
            <ul style="list-style: none; padding: 0;">
                <!-- Loop through tasks array and display each task with a toggle and delete option -->
                <!-- If there are no tasks, display a message saying "No tasks yet. Add one above!" -->
                 <?php if(empty($tasks)): ?>
                     <li>No tasks yet. Add one above!</li>
                    <!-- if there are tasks, display each task with a toggle and delete option -->
                 <?php else: ?>
                    <?php foreach($tasks as $index => $task): ?>
                        <li class="task-item">
                            <form method="POST" style="flex-grow: 1;">
                                <input type="hidden" name="toggle" value="<?= $index ?>">
                           
                            <button type="submit" style="border: none; background: none; cursor: pointer; text-align: left; width: 100%;">
                        <span class="task <?= $task['done'] ? 'task-done' : '' ?>">
                            <?= htmlspecialchars($task['task']) ?>
                        </span>
                    </button>
                     </form>
                     <form method="POST">
                <input type="hidden" name="delete" value="<?= $index ?>">
                <button type="submit" class="button button-outline" style="margin-left: 10px;">Delete</button>
                     </form>
                        </li>
                        <?php endforeach; ?>
                 <?php endif; ?>
            </ul>
        </div>
    </div>
</body>
</html>