<?php
// note_taking_app.php

session_start();

// Define CSV file paths
define('USERS_FILE', 'users.csv');
define('NOTES_FILE', 'notes.csv');

// Initialize CSV files if they don't exist
if (!file_exists(USERS_FILE)) {
    $file = fopen(USERS_FILE, 'w');
    // Predefined user: Username=Yasin, Password=khan (Password is hashed)
    $hashed_password = password_hash('khan', PASSWORD_DEFAULT);
    fputcsv($file, ['username', 'password']);
    fputcsv($file, ['Yasin', $hashed_password]);
    fclose($file);
}

if (!file_exists(NOTES_FILE)) {
    $file = fopen(NOTES_FILE, 'w');
    fputcsv($file, ['id', 'title', 'content', 'date_created']);
    fclose($file);
}

// Function to read users from CSV
function read_users()
{
    $users = [];
    if (($handle = fopen(USERS_FILE, 'r')) !== FALSE) {
        $header = fgetcsv($handle);
        while (($data = fgetcsv($handle)) !== FALSE) {
            $users[$data[0]] = $data[1]; // username => hashed_password
        }
        fclose($handle);
    }
    return $users;
}

// Function to read notes from CSV
function read_notes()
{
    $notes = [];
    if (($handle = fopen(NOTES_FILE, 'r')) !== FALSE) {
        $header = fgetcsv($handle);
        while (($data = fgetcsv($handle)) !== FALSE) {
            $notes[] = [
                'id' => $data[0],
                'title' => $data[1],
                'content' => $data[2],
                'date_created' => $data[3]
            ];
        }
        fclose($handle);
    }
    return $notes;
}

// Function to write notes to CSV
function write_notes($notes)
{
    $file = fopen(NOTES_FILE, 'w');
    fputcsv($file, ['id', 'title', 'content', 'date_created']);
    foreach ($notes as $note) {
        fputcsv($file, [$note['id'], $note['title'], $note['content'], $note['date_created']]);
    }
    fclose($file);
}

// Function to add a new note
function add_note($title, $content)
{
    $notes = read_notes();
    // Generate a unique ID
    $new_id = count($notes) > 0 ? max(array_column($notes, 'id')) + 1 : 1;
    $notes[] = [
        'id' => $new_id,
        'title' => $title,
        'content' => $content,
        'date_created' => date('Y-m-d H:i:s')
    ];
    write_notes($notes);
}

// Function to update a note
function update_note($id, $title, $content)
{
    $notes = read_notes();
    foreach ($notes as &$note) {
        if ($note['id'] == $id) {
            $note['title'] = $title;
            $note['content'] = $content;
            break;
        }
    }
    write_notes($notes);
}

// Function to delete a note
function delete_note($id)
{
    $notes = read_notes();
    $notes = array_filter($notes, function($note) use ($id) {
        return $note['id'] != $id;
    });
    write_notes($notes);
}

// Handle Logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header("Location: note_taking_app.php");
    exit();
}

// Handle Login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $users = read_users();

    if (array_key_exists($username, $users) && password_verify($password, $users[$username])) {
        $_SESSION['username'] = $username;
        header("Location: note_taking_app.php");
        exit();
    } else {
        $login_error = "Invalid username or password.";
    }
}

// Handle Add Note
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_note'])) {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    if ($title !== '' && $content !== '') {
        add_note($title, $content);
        $success_message = "Note added successfully.";
    } else {
        $add_error = "Please provide both title and content.";
    }
}

// Handle Edit Note
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_note'])) {
    $id = $_POST['id'];
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    if ($title !== '' && $content !== '') {
        update_note($id, $title, $content);
        $success_message = "Note updated successfully.";
    } else {
        $edit_error = "Please provide both title and content.";
    }
}

// Handle Delete Note
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    delete_note($id);
    $success_message = "Note deleted successfully.";
}

// Fetch all notes
$notes = read_notes();

// If editing, fetch the specific note
$edit_note = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    foreach ($notes as $note) {
        if ($note['id'] == $_GET['id']) {
            $edit_note = $note;
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>PHP Note-Taking App</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            max-width: 800px;
            margin: auto;
            overflow: hidden;
            padding: 20px;
            background-color: #fff;
            margin-top: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2, h3 {
            text-align: center;
            color: #333;
        }
        form {
            margin-bottom: 20px;
        }
        input[type="text"], input[type="password"], textarea {
            width: 100%;
            padding: 10px;
            margin: 5px 0 15px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            resize: vertical;
        }
        input[type="submit"], button {
            background-color: #4287f5;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover, button:hover {
            background-color: #306edc;
        }
        .message {
            text-align: center;
            color: green;
            margin-bottom: 20px;
        }
        .error {
            text-align: center;
            color: red;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom:20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #4287f5;
            color: white;
        }
        tr:nth-child(even){
            background-color: #f9f9f9;
        }
        .actions a {
            margin-right: 10px;
            text-decoration: none;
            color: #4287f5;
        }
        .actions a:hover {
            text-decoration: underline;
        }
        .logout {
            text-align: right;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if (!isset($_SESSION['username'])): ?>
            <h2>Login to Your Account</h2>
            <?php if (isset($login_error)): ?>
                <div class="error"><?php echo htmlspecialchars($login_error); ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <input type="submit" name="login" value="Login">
            </form>
        <?php else: ?>
            <div class="logout">
                Logged in as <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong> |
                <a href="note_taking_app.php?action=logout" style="color: #4287f5;">Logout</a>
            </div>

            <h2>My Notes</h2>

            <?php if (isset($success_message)): ?>
                <div class="message"><?php echo htmlspecialchars($success_message); ?></div>
            <?php endif; ?>

            <?php if (isset($add_error)): ?>
                <div class="error"><?php echo htmlspecialchars($add_error); ?></div>
            <?php endif; ?>

            <?php if (isset($edit_error)): ?>
                <div class="error"><?php echo htmlspecialchars($edit_error); ?></div>
            <?php endif; ?>

            <!-- Add Note Form -->
            <?php if (!$edit_note): ?>
                <h3>Add New Note</h3>
                <form method="POST" action="">
                    <input type="hidden" name="add_note" value="1">
                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" required>

                    <label for="content">Content:</label>
                    <textarea id="content" name="content" rows="5" required></textarea>

                    <input type="submit" value="Add Note">
                </form>
            <?php endif; ?>

            <!-- Edit Note Form -->
            <?php if ($edit_note): ?>
                <h3>Edit Note</h3>
                <form method="POST" action="">
                    <input type="hidden" name="edit_note" value="1">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($edit_note['id']); ?>">
                    
                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($edit_note['title']); ?>" required>

                    <label for="content">Content:</label>
                    <textarea id="content" name="content" rows="5" required><?php echo htmlspecialchars($edit_note['content']); ?></textarea>

                    <input type="submit" value="Update Note">
                    <button type="button" onclick="window.location.href='note_taking_app.php'">Cancel</button>
                </form>
            <?php endif; ?>

            <!-- Notes List -->
            <h3>Your Notes</h3>
            <?php if (count($notes) === 0): ?>
                <p>No notes available. Start by adding a new note!</p>
            <?php else: ?>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Content</th>
                        <th>Date Created</th>
                        <th>Actions</th>
                    </tr>
                    <?php foreach ($notes as $note): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($note['id']); ?></td>
                            <td><?php echo htmlspecialchars($note['title']); ?></td>
                            <td><?php echo nl2br(htmlspecialchars($note['content'])); ?></td>
                            <td><?php echo htmlspecialchars($note['date_created']); ?></td>
                            <td class="actions">
                                <a href="note_taking_app.php?action=edit&id=<?php echo htmlspecialchars($note['id']); ?>">Edit</a>
                                <a href="note_taking_app.php?action=delete&id=<?php echo htmlspecialchars($note['id']); ?>" onclick="return confirm('Are you sure you want to delete this note?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>
