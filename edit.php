
<?php
include 'db_con.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = (int) $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    header("Location: index.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);

    if ($name != "" && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $conn->prepare(
            "UPDATE users SET name = ?, email = ? WHERE id = ?"
        );
        $stmt->bind_param("ssi", $name, $email, $id);

        if ($stmt->execute()) {
            header("Location: index.php?msg=updated");
            exit();
        } else {
            $error = "Error updating record.";
        }
    } else {
        $error = "Please enter a valid name and email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">

    <h1 class="text-center">Edit User</h1>

    <?php if ($error != "") { ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php } ?>

    <form method="post" action="edit.php?id=<?= $id ?>">

        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control"
                   value="<?= htmlspecialchars($row['name']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control"
                   value="<?= htmlspecialchars($row['email']) ?>" required>
        </div>

        <button type="submit" name="submit" class="btn btn-primary">
            Update
        </button>

        <a href="index.php" class="btn btn-secondary">Cancel</a>

    </form>

</div>

</body>
</html>
