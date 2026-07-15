<?php
/*
    view_students.php
    --------------------
    Displays all students in a table. Supports searching by name,
    roll number, or course using a simple GET-based search box
    (so the search term stays visible in the URL and survives a refresh).
*/

require_once 'includes/auth_check.php';
require_once 'includes/db_connect.php';

// Get the search term from the URL, e.g. view_students.php?search=priya
$search = isset($_GET['search']) ? trim($_GET['search']) : "";

if ($search !== "") {
    // Search across name, roll_number, and course using LIKE
    // The % wildcard means "any characters before/after"
    $stmt = $conn->prepare(
        "SELECT * FROM students
         WHERE name LIKE ? OR roll_number LIKE ? OR course LIKE ?
         ORDER BY id DESC"
    );
    $likeTerm = "%" . $search . "%";
    $stmt->bind_param("sss", $likeTerm, $likeTerm, $likeTerm);
    $stmt->execute();
    $students = $stmt->get_result();
} else {
    // No search term -> show everyone
    $students = $conn->query("SELECT * FROM students ORDER BY id DESC");
}

$page_title = "View Students - Student Management System";
require_once 'includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center flex-wrap mb-4 gap-2">
    <h3 class="mb-0"><i class="bi bi-people-fill"></i> All Students</h3>
    <a href="add_student.php" class="btn btn-primary"><i class="bi bi-person-plus-fill"></i> Add New Student</a>
</div>

<!-- Search form -->
<form method="GET" action="view_students.php" class="row g-2 mb-3">
    <div class="col-md-6">
        <input type="text" name="search" class="form-control"
               placeholder="Search by name, roll number, or course..."
               value="<?php echo htmlspecialchars($search); ?>">
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-outline-primary"><i class="bi bi-search"></i> Search</button>
    </div>
    <?php if ($search !== ""): ?>
        <div class="col-auto">
            <a href="view_students.php" class="btn btn-outline-secondary">Clear</a>
        </div>
    <?php endif; ?>
</form>

<!-- Feedback messages passed from add/edit/delete pages via URL parameters -->
<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success"><i class="bi bi-check-circle-fill"></i> <?php echo htmlspecialchars($_GET['success']); ?></div>
<?php endif; ?>
<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill"></i> <?php echo htmlspecialchars($_GET['error']); ?></div>
<?php endif; ?>

<div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Roll No.</th>
                <th>Course</th>
                <th>Semester</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($students && $students->num_rows > 0): ?>
                <?php $i = 1; while ($row = $students->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['roll_number']); ?></td>
                        <td><?php echo htmlspecialchars($row['course']); ?></td>
                        <td><?php echo htmlspecialchars($row['semester']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                        <td><?php echo htmlspecialchars($row['address']); ?></td>
                        <td class="text-center">
                            <a href="edit_student.php?id=<?php echo $row['id']; ?>"
                               class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <a href="delete_student.php?id=<?php echo $row['id']; ?>"
                               class="btn btn-sm btn-outline-danger btn-delete"
                               data-name="<?php echo htmlspecialchars($row['name']); ?>"
                               title="Delete">
                                <i class="bi bi-trash-fill"></i>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="9" class="text-center text-muted py-3">No students found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once 'includes/footer.php'; ?>