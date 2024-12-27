<div class="sidebar">
        <div class="logo_name">
            <h2><span>Parish</span>Track</h2>
        </div>
        <div class="links">
            <div class="dashed"></div>
            <a href="net_revenue.php">
                <i class="fas fa-balance-scale"></i> Revenue vs Expenses
            </a>
            <div class="dashed"></div>
            <a href="add_revenue.php">
                <i class="fas fa-plus-circle"></i> Add Revenue
            </a>
            <div class="dashed"></div>
            <a href="view_revenue.php">
                <i class="fas fa-eye"></i> View Revenue
            </a>
            <div class="dashed"></div>
            <a href="record_revenue.php">
                <i class="fas fa-file-alt"></i> Record Revenue
            </a>
            <div class="dashed"></div>
            <a href="view_recorded_revenues.php">
                <i class="fas fa-folder-open"></i> View Record Revenue
            </a>
            <div class="dashed"></div>
            <a href="revenue_report.php">
                <i class="fas fa-chart-bar"></i> Revenue Report
            </a>
            <div class="dashed"></div>
            <a href="expenses.php">
                <i class="fas fa-wallet"></i> Expenses
            </a>
            <div class="dashed"></div>
            <a href="expenses_history.php">
                <i class="fas fa-history"></i> Expenses History
            </a>
            <div class="dashed"></div>
        </div>
        <a href="logout.php">
            <div class="logout">
                <i class="fas fa-power-off"></i> Logout
            </div>
        </a>
    </div>
    <div class="toggle_btn">
        <p><i class="fas fa-xmark"></i></p>
    </div>

    <script>
        const sidebar = document.querySelector('.sidebar');
        const toggleBtn = document.querySelector('.toggle_btn');
        const toggleIcon = toggleBtn.querySelector('i');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('hidden');
            toggleBtn.classList.toggle('collapsed');

            if (sidebar.classList.contains('hidden')) {
                toggleIcon.classList.replace('fa-xmark', 'fa-bars');
            } else {
                toggleIcon.classList.replace('fa-bars', 'fa-xmark');
            }
        });

            // Get the current page URL
    const currentPage = window.location.pathname.split("/").pop();
    const links = document.querySelectorAll(".links a");

    // Loop through the links and add the 'active' class to the matching link
    links.forEach(link => {
        if (link.getAttribute("href") === currentPage) {
            link.classList.add("active");
        }
    });
    </script>

