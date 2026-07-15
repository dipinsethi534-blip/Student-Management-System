<!--
    footer.php
    -----------
    Common bottom section for every page: closes the container div
    opened in header.php, adds a simple footer, and loads JS files.
-->
</div> <!-- closes .container from header.php -->

<footer class="text-center text-muted py-3 mt-4 border-top">
    <small>&copy; <?php echo date("Y"); ?> Student Management System | BTech CSE Semester 4 Project</small>
</footer>

<!-- Bootstrap 5 JS bundle (includes Popper, needed for navbar toggle) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Our own custom JS (form validation, confirm dialogs, etc.) -->
<script src="js/script.js"></script>
</body>
</html>