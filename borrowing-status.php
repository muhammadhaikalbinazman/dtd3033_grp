<?php
session_start();

// Check if user is logged in, if not redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'dbconfig.php';

// Get user's borrowing records only
$user_id = $_SESSION['user_id']; // Get the logged-in user's ID
$sql = "SELECT bb.*, u.name 
        FROM bookborrowing bb 
        INNER JOIN users u ON bb.user_id = u.user_id 
        WHERE bb.user_id = ? 
        ORDER BY bb.borrow_date DESC";


// Use prepared statement to prevent SQL injection
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<?php
// In the POST handling section of borrow.php, modify the SQL query:
$user_id = $_SESSION['user_id'];
$sql = "INSERT INTO bookborrowing (borrow_id, book_type, borrow_date, return_date, title, author, year, user_id) 
        VALUES ($borrow_id, '$book_type', '$borrow_date', '$return_date', '$title', '$author', $year, $user_id)";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Borrowing Status</title>
    <link rel="stylesheet" href="layout.css">
    <style>
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }

        .welcome-banner {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .welcome-text {
            font-size: 16px;
            color: #333;
        }

        h2 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }

        .filters {
            margin-bottom: 20px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .filter-group {
            flex: 1;
            min-width: 200px;
        }

        .filter-group label {
            display: block;
            margin-bottom: 5px;
            color: #666;
        }

        .filter-group select,
        .filter-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .status-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .status-table th,
        .status-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .status-table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .status-table tr:hover {
            background-color: #f5f5f5;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-approved {
            background-color: #d4edda;
            color: #155724;
        }

        .status-rejected {
            background-color: #f8d7da;
            color: #721c24;
        }

        .status-returned {
            background-color: #e2e3e5;
            color: #383d41;
        }

        .no-records {
            text-align: center;
            padding: 20px;
            color: #666;
        }

        @media (max-width: 768px) {
            .status-table {
                display: block;
                overflow-x: auto;
            }

            .container {
                padding: 15px;
            }

            .filter-group {
                min-width: 100%;
            }
        }
    </style>
</head>
<body>

<?php
include 'header.php';
include 'menu.php';
?>

<div class="container">

    <div class="filters">
        <div class="filter-group">
            <label for="statusFilter">Filter by Status</label>
            <select id="statusFilter">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
                <option value="returned">Returned</option>
            </select>
        </div>
        <div class="filter-group">
            <label for="searchInput">Search by Title/Author</label>
            <input type="text" id="searchInput" placeholder="Enter title or author...">
        </div>
    </div>

    <table class="status-table">
        <thead>
            <tr>
                <th>Borrow ID</th>
                <th>Book Title</th>
                <th>Author</th>
                <th>Book Type</th>
                <th>Borrow Date</th>
                <th>Return Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody id="statusTableBody">
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['borrow_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['author']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['book_type']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['borrow_date']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['return_date']) . "</td>";
                    echo "<td><span class='status-badge status-" . strtolower($row['status']) . "'>" . 
                         ucfirst(htmlspecialchars($row['status'])) . "</span></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7' class='no-records'>You have no borrowing records yet</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusFilter = document.getElementById('statusFilter');
    const searchInput = document.getElementById('searchInput');
    const tableBody = document.getElementById('statusTableBody');
    
    function filterTable() {
        const statusValue = statusFilter.value.toLowerCase();
        const searchValue = searchInput.value.toLowerCase();
        const rows = tableBody.getElementsByTagName('tr');
        
        for (let row of rows) {
            if (row.cells.length < 7) continue; // Skip the "no records" row
            
            const title = row.cells[1].textContent.toLowerCase();
            const author = row.cells[2].textContent.toLowerCase();
            const status = row.cells[6].textContent.toLowerCase();
            
            const matchesStatus = statusValue === '' || status.includes(statusValue);
            const matchesSearch = searchValue === '' || 
                                title.includes(searchValue) || 
                                author.includes(searchValue);
            
            row.style.display = matchesStatus && matchesSearch ? '' : 'none';
        }
    }
    
    // Add event listeners for filtering
    statusFilter.addEventListener('change', filterTable);
    searchInput.addEventListener('input', filterTable);
    
    // Auto-refresh the page every 5 minutes to show updated statuses
    setInterval(function() {
        location.reload();
    }, 300000); // 5 minutes in milliseconds
});
</script>

<?php include 'footer.php'; ?>
</body>
</html>