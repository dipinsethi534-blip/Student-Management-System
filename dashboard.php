<?php
/*
    dashboard.php
    ---------------
    The main landing page after login. Shows a few quick statistics
    (total students, total courses, total semesters in use) fetched
    from the database.dashboard is for seeing activities and content 
    colleges gives us
*/

require_once 'includes/auth_check.php';   // must be logged in
require_once 'includes/db_connect.php';

// --- Total number of students ---
$total_students = 0;
$result = $conn->query("SELECT COUNT(*) AS total FROM students");
if ($result) {
    $total_students = $result->fetch_assoc()['total'];
}

// --- Number of distinct courses ---
$total_courses = 0;
$result = $conn->query("SELECT COUNT(DISTINCT course) AS total FROM students");
if ($result) {
    $total_courses = $result->fetch_assoc()['total'];
}

// --- Number of distinct semesters in use ---
$total_semesters = 0;
$result = $conn->query("SELECT COUNT(DISTINCT semester) AS total FROM students");
if ($result) {
    $total_semesters = $result->fetch_assoc()['total'];
}

// --- Most recently added students (for a quick preview table) ---
$recent_students = $conn->query("SELECT name, roll_number, course, semester FROM students ORDER BY id DESC LIMIT 5");

$page_title = "Dashboard - Student Management System";
require_once 'includes/header.php';
?>

<h3 class="mb-4">Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?> 👋</h3>

<!-- Summary cards -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card dashboard-card bg-card-blue">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1">Total Students</h6>
                    <h2 class="mb-0"><?php echo $total_students; ?></h2>
                </div>
                <i class="bi bi-people-fill card-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card dashboard-card bg-card-green">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1">Courses Offered</h6>
                    <h2 class="mb-0"><?php echo $total_courses; ?></h2>
                </div>
                <i class="bi bi-journal-bookmark-fill card-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card dashboard-card bg-card-orange">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1">Semesters in Use</h6>
                    <h2 class="mb-0"><?php echo $total_semesters; ?></h2>
                </div>
                <i class="bi bi-calendar3 card-icon"></i>
            </div>
        </div>
    </div>
</div>

<!-- Quick action buttons -->
<div class="mb-4">
    <a href="add_student.php" class="btn btn-primary me-2"><i class="bi bi-person-plus-fill"></i> Add New Student</a>
    <a href="view_students.php" class="btn btn-outline-secondary"><i class="bi bi-list-ul"></i> View All Students</a>
</div>

<!-- Recently added students preview -->
<h5 class="mb-3">Recently Added Students</h5>
<div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th>Name</th>
                <th>Roll Number</th>
                <th>Course</th>
                <th>Semester</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($recent_students && $recent_students->num_rows > 0): ?>
                <?php while ($row = $recent_students->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['roll_number']); ?></td>
                        <td><?php echo htmlspecialchars($row['course']); ?></td>
                        <td><?php echo htmlspecialchars($row['semester']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="4" class="text-center text-muted">No students added yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once 'includes/footer.php'; ?>
