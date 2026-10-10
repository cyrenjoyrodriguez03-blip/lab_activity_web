
<?php
include 'db_con.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);

    if ($name != "" && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $conn->prepare(
            "INSERT INTO users (name, email) VALUES (?, ?)"
        );

        $stmt->bind_param("ss", $name, $email);

        if ($stmt->execute()) {
            header("Location: index.php?msg=added");
            exit();
        }
    }

    header("Location: index.php?msg=error");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management System</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">

    <h1 class="text-center">USER MANAGEMENT SYSTEM</h1>

    <?php
    if (isset($_GET['msg'])) {
        if ($_GET['msg'] == 'added') {
            echo '<div class="alert alert-success">New Record inserted successfully!</div>';
        } elseif ($_GET['msg'] == 'updated') {
            echo '<div class="alert alert-success">Record updated successfully!</div>';
        } elseif ($_GET['msg'] == 'deleted') {
            echo '<div class="alert alert-success">Record deleted successfully!</div>';
        } elseif ($_GET['msg'] == 'error') {
            echo '<div class="alert alert-danger">Error! Please check your input.</div>';
        }
    }
    ?>

    <form method="post" action="index.php">

        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control"
                   placeholder="Enter Full Name" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control"
                   placeholder="Enter Email Address" required>
        </div>

        <button type="submit" name="submit" class="btn btn-primary">
            Submit
        </button>

    </form>

    <br>

    <table class="table table-bordered table-striped table-sm">
        <thead>
            <tr>
                <th>#</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Joined</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            <?php
            $sql_query = "SELECT * FROM users ORDER BY id DESC";
            $result = $conn->query($sql_query);

            if ($result) {
                while ($row = $result->fetch_assoc()) {
            ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['created_at'] ?? '') ?></td>
                <td>
                    <a class="btn btn-success btn-sm"
                       href="edit.php?id=<?= $row['id'] ?>">Edit</a>

                    <a class="btn btn-danger btn-sm"
                       href="delete.php?id=<?= $row['id'] ?>"
                       onclick="return confirm('Are you sure you want to delete this record?')">Delete</a>
                </td>
            </tr>
            <?php
                }
            }
            ?>
        </tbody>
    </table>

</div>

</body>
</html>
