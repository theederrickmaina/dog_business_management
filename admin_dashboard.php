<?php
session_start();

// Restrict access to admins only
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("Location: access_denied.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="Admin_dashboard_styling.css">
</head>
<body>
    <!-- Sidebar Navigation -->
    <div class="sidebar">
        <h2>GuardianPaws Admin</h2>
        <ul>
            <li><a href="#" class="nav-link" data-target="manage_inventory.php">Manage Inventory</a></li>
            <li><a href="#" class="nav-link" data-target="manage_dogs.php">Manage Dogs</a></li>
            <li><a href="#" class="nav-link" data-target="manage_vet_visits.php">Manage Vet Visits</a></li>
            <li><a href="#" class="nav-link" data-target="manage_sales.php">Manage Sales</a></li>
            <li><a href="#" class="nav-link" data-target="manage_client_payments.php">Manage Client Payments</a></li>
            <li><a href="#" class="nav-link" data-target="manage_users.php">Manage Users</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <h1>Welcome to the Admin Dashboard</h1>
            <div class="header-actions">
            <input type="text" id="searchBar" placeholder="Search..." oninput="performSearch(this.value)">
            <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </header>

        <!-- Dynamic Content Area -->
        <section id="dynamic-content">
            <h2>Dashboard Overview</h2>
            <div class="stats">
                <div class="stat-card">Total Dogs: <span id="totalDogs">0</span></div>
                <div class="stat-card">Sales: <span id="totalSales">0</span></div>
                <div class="stat-card">Upcoming Vet Visits: <span id="upcomingVisits">0</span></div>
            </div>

            <div class="data-table">
                <h3>Manage Content</h3>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Example Data Row -->
                        <tr>
                            <td>1</td>
                            <td>Sample Data</td>
                            <td><a href="#">Edit</a> | <a href="#">Delete</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <!-- JavaScript -->
    <script>

document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.querySelector("input[placeholder='Search...']");
    const contentArea = document.getElementById("dynamic-content");

    const originalContent = contentArea.innerHTML; // Save the original dashboard content

    searchInput.addEventListener("input", () => {
        const query = searchInput.value.trim();

        if (query.length > 0) {
            // Perform AJAX search when there's input
            fetch(`search_dashboard.php?query=${encodeURIComponent(query)}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.text();
                })
                .then(data => {
                    contentArea.innerHTML = data; // Update content with search results
                })
                .catch(error => console.error("Error during search:", error));
        } else {
            // Restore the original dashboard content when the search bar is cleared
            contentArea.innerHTML = originalContent;
        }
    });
});



         
        // Wait for the DOM to load
        document.addEventListener("DOMContentLoaded", () => {
            const links = document.querySelectorAll(".nav-link"); // Sidebar links
            const contentArea = document.getElementById("dynamic-content"); // Main content area

            // Function to remove active class from all links
            const clearActiveClasses = () => {
                links.forEach(link => link.classList.remove("active"));
            };

            links.forEach(link => {
                link.addEventListener("click", (e) => {
                    e.preventDefault(); // Prevent default link behavior
                    const targetPage = link.getAttribute("data-target"); // Get the target page

                    // Remove active class from all links, then add it to the clicked link
                    clearActiveClasses();
                    link.classList.add("active");

                    // Perform AJAX request using fetch
                    fetch(targetPage)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            return response.text(); // Get the HTML content
                        })
                        .then(data => {
                            contentArea.innerHTML = data; // Inject content into #dynamic-content
                        })
                        .catch(error => console.error("Error loading content:", error)); // Handle errors
                });
            });
        });
    </script>
</body>
</html>
