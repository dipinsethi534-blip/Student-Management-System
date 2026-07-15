<?php
/*
    add_student.php
    ------------------
    Shows a form to add a new student and handles saving it to the
    database. Server-side validation is done here because JavaScript
    validation can always be bypassed by the user.
*/

require_once 'includes/auth_check.php';
require_once 'includes/db_connect.php';

$errors = [];
$success_message = "";

// Keep submitted values so the form doesn't clear on error
$name = $roll_number = $course = $semester = $email = $phone = $address = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Collect and trim form data
    $name        = trim($_POST['name']);
    $roll_number = trim($_POST['roll_number']);
    $course      = trim($_POST['course']);
    $semester    = trim($_POST['semester']);
    $email       = trim($_POST['email']);
    $phone       = trim($_POST['phone']);
    $address     = trim($_POST['address']);

    // ---------------- Server-side validation ----------------
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

    // Check the roll number is not already used (it's UNIQUE in the DB)
    if (empty($errors)) {
        $check = $conn->prepare("SELECT id FROM students WHERE roll_number = ?");
        $check->bind_param("s", $roll_number);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            $errors[] = "This roll number already exists.";
        }
        $check->close();
    }

    // ---------------- Insert into database ----------------
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO students (name, roll_number, course, semester, email, phone, address) VALUES (?, ?, ?, ?, ?, ?, ?)");
        // types: s = string, i = integer
        // "sssisss" tells MySQLi the data type of each value in order:
        // name(s) roll_number(s) course(s) semester(i) email(s) phone(s) address(s)
        $stmt->bind_param("sssisss", $name, $roll_number, $course, $semester, $email, $phone, $address);

        if ($stmt->execute()) {
            $success_message = "Student added successfully!";
            // Clear the form fields after a successful save
            $name = $roll_number = $course = $semester = $email = $phone = $address = "";
        } else {
            $errors[] = "Something went wrong. Please try again.";
        }
        $stmt->close();
    }
}

$page_title = "Add Student - Student Management System";
require_once 'includes/header.php';
?>

<h3 class="mb-4"><i class="bi bi-person-plus-fill"></i> Add New Student</h3>

<?php if (!empty($success_message)): ?>
    <div class="alert alert-success"><i class="bi bi-check-circle-fill"></i> <?php echo $success_message; ?></div>
<?php endif; ?>

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
    <form method="POST" action="add_student.php" class="needs-validation" novalidate>
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
                <input type="text" name="course" class="form-control" placeholder="e.g. B.Tech CSE" value="<?php echo htmlspecialchars($course); ?>" required>
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
            <button type="submit" class="btn btn-primary"><i class="bi bi-save-fill"></i> Save Student</button>
            <a href="view_students.php" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>