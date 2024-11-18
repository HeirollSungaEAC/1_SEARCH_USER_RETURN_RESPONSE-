<?php
require_once '../models.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: ../index.php');
    exit;
}

$applicant = readApplicants()[
    'querySet'
];

foreach($applicant as $item){
    if($item['id']==$id){ $data=$item; }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = updateApplicant($id, $_POST);
    if ($response['statusCode'] === 200) {
        header('Location: ../index.php');
        exit;
    } else {
        $error = $response['message'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Applicant</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <h1>Edit Applicant</h1>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="post" action="">
        <label>First Name:</label>
        <input type="text" name="first_name" value="<?= htmlspecialchars($data['first_name']) ?>" required>
        <label>Last Name:</label>
        <input type="text" name="last_name" value="<?= htmlspecialchars($data['last_name']) ?>" required>
        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($data['email']) ?>" required>
        <label>Phone:</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($data['phone']) ?>" required>
        <label>Address:</label>
        <textarea name="address" required><?= htmlspecialchars($data['address']) ?></textarea>
        <label>Qualifications:</label>
        <textarea name="qualifications" required><?= htmlspecialchars($data['qualifications']) ?></textarea>
        <input type="hidden" name="action" value="update">
        <button type="submit">Update</button>
        <a href="../index.php">Cancel</a>
    </form>
</body>
</html>
