<?php
include 'connect.php';

$query = isset($_GET['query']) ? trim($_GET['query']) : '';
$query = htmlspecialchars($query); // Sanitize input
$output = "<h2>Search Results</h2>";

try {
    // Fetch matching table names
    $stmt = $conn->prepare("SHOW TABLES");
    $stmt->execute();
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Filter tables based on the query
    $matchingTables = array_filter($tables, function ($table) use ($query) {
        return stripos($table, $query) !== false; // Case-insensitive match
    });

    if (!empty($matchingTables)) {
        foreach ($matchingTables as $table) {
            $output .= "<h3>Table: $table</h3>";
            
            // Fetch data from the matching table
            $stmt = $conn->prepare("SELECT * FROM $table LIMIT 10"); // Limit rows to avoid overwhelming output
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($rows)) {
                $output .= "<table border='1'><thead><tr>";

                // Display column names
                foreach (array_keys($rows[0]) as $column) {
                    $output .= "<th>$column</th>";
                }
                $output .= "</tr></thead><tbody>";

                // Display rows
                foreach ($rows as $row) {
                    $output .= "<tr>";
                    foreach ($row as $cell) {
                        $output .= "<td>" . htmlspecialchars($cell) . "</td>";
                    }
                    $output .= "</tr>";
                }
                $output .= "</tbody></table>";
            } else {
                $output .= "<p>No data available in table <strong>$table</strong>.</p>";
            }
        }
    } else {
        $output .= "<p>No matching tables found for '<strong>" . htmlspecialchars($query) . "</strong>'.</p>";
    }
} catch (PDOException $e) {
    $output = "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}

echo $output;
?>
