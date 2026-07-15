<?php
/*
    edit_student.php
    -------------------
    Loads a student's existing details (based on ?id=... in the URL),
    pre-fills the form, and updates the record in the database when
    the form is submitted. Validation logic mirrors add_student.php.
*/

require_once 'includes/auth_check.php';
require_once 'includes/db_connect.php';

$errors = [];
$success_message = "";

// ---------------- Get the student ID from the URL ----------------
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: view_students.php?error=Invalid student ID.");
    exit();
}
$id = intval($_GET['id']);

// ---------------- Fetch the existing student record ----------------
$stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: view_students.php?error=Student not found.");
    exit();
}
$student = $result->fetch_assoc();
$stmt->close();

// Pre-fill form fields with existing data (will be overwritten below on POST)
$name        = $student['name'];
$roll_number = $student['roll_number'];
$course      = $student['course'];
$semester    = $student['semester'];
$email       = $student['email'];
$phone       = $student['phone'];
$address     = $student['address'];

// ---------------- Handle form submission ----------------
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name        = trim($_POST['name']);
    $roll_number = trim($_POST['roll_number']);
    $course      = trim($_POST['course']);
    $semester    = trim($_POST['semester']);
    $email       = trim($_POST['email']);
    $phone       = trim($_POST['phone']);
    $address     = trim($_POST['address']);

    // Same validation rules as add_student.php
    if (empty($name)) {
        $errors[] = "Name is required.";
    } elseif (!preg_match("/^[a-zA-Z\s.]+$/", $name)) {
        $errors[] = "Name should only contain letters and spaces.";
    }

    if (empty($roll_number)) {
        $errors[] = "Roll number is required.";
    }

    if (empty($course)) {
        $errors[] = "Course is required.";
    }

    if (empty($semester) || !is_numeric($semester) || $semester < 1 || $semester > 8) {
        $errors[] = "Semester must be a number between 1 and 8.";
    }

    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }

    if (empty($phone)) {
        $errors[] = "Phone number is required.";
    } elseif (!preg_match("/^[0-9]{10}$/", $phone)) {
        $errors[] = "Phone number must be exactly 10 digits.";
    }

    // Roll number must be unique, EXCEPT it's allowed to match this same student's own record
    if (empty($errors)) {
        $check = $conn->prepare("SELECT id FROM students WHERE roll_number = ? AND id != ?");
        $check->bind_param("si", $roll_number, $id);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            $errors[] = "This roll number is already used by another student.";
        }
        $check->close();
    }

    // ---------------- Update the database ----------------
    if (empty($errors)) {
        $update = $conn->prepare(
            "UPDATE students
             SET name = ?, roll_number = ?, course = ?, semester = ?, email = ?, phone = ?, address = ?
             WHERE id = ?"
        );
        $update->bind_param("sssisssi", $name, $roll_number, $course, $semester, $email, $phone, $address, $id);

        if ($update->execute()) {
            // Redirect back to the student list with a success message
            header("Location: view_students.php?success=Student updated successfully!");
            exit();
        } else {
            $errors[] = "Something went wrong while updating. Please try again.";
        }
        $update->close();
    }
}

$page_title = "Edit Student - Student Management System";
require_once 'includes/header.php';
?>

<h3 class="mb-4"><i class="bi bi-pencil-square"></i> Edit Student</h3>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <i class="bi bi-exclamation-triangle-fill"></i> Please fix the following:
        <ul class="mb-0">
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="form-card">
    <form method="POST" action="edit_student.php?id=<?php echo $id; ?>" class="needs-validation" novalidate>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($name); ?>" required>
                <div class="invalid-feedback">Please enter the student's name.</div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Roll Number</label>
                <input type="text" name="roll_number" class="form-control" value="<?php echo htmlspecialchars($roll_number); ?>" required>
                <div class="invalid-feedback">Please enter a roll number.</div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Course</label>
                <input type="text" name="course" class="form-control" value="<?php echo htmlspecialchars($course); ?>" required>
                <div class="invalid-feedback">Please enter the course name.</div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Semester</label>
                <select name="semester" class="form-select" required>
                    <option value="">-- Select Semester --</option>
                    <?php for ($i = 1; $i <= 8; $i++): ?>
                        <option value="<?php echo $i; ?>" <?php echo ($semester == $i) ? "selected" : ""; ?>>
                            Semester <?php echo $i; ?>
                        </option>
                    <?php endfor; ?>
                </select>
                <div class="invalid-feedback">Please select a semester.</div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>" required>
                <div class="invalid-feedback">Please enter a valid email.</div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Phone Number</label>
                <input type="text" id="phone" name="phone" maxlength="10" class="form-control" value="<?php echo htmlspecialchars($phone); ?>" required>
                <div class="invalid-feedback">Please enter a 10-digit phone number.</div>
            </div>
            <div class="col-12">
                <label class="form-label">Address</label>
                <textarea name="address" class="form-control" rows="2"><?php echo htmlspecialchars($address); ?></textarea>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary"><i class="bi bi-save-fill"></i> Update Student</button>
            <a href="view_students.php" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>