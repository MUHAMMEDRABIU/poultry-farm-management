<?php
include '../config.php';

// Fetch reports data
$reports_query = "SELECT report_title, category, description, amount, date FROM reports ORDER BY date DESC";
$reports_result = $conn->query($reports_query);

// Start HTML file
$html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farm Reports</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Farm Performance Reports</h2>
    <table>
        <tr>
            <th>Report Title</th>
            <th>Category</th>
            <th>Description</th>
            <th>Amount (₦)</th>
            <th>Date</th>
        </tr>';

// Add data
while ($row = $reports_result->fetch_assoc()) {
    $html .= '<tr>
                <td>' . htmlspecialchars($row['report_title']) . '</td>
                <td>' . htmlspecialchars($row['category']) . '</td>
                <td>' . htmlspecialchars($row['description']) . '</td>
                <td>' . number_format($row['amount'], 2) . '</td>
                <td>' . date("F j, Y", strtotime($row['date'])) . '</td>
              </tr>';
}

$html .= '</table></body></html>';

// Save as HTML file
file_put_contents('farm_reports.html', $html);

// Force download
header('Content-Type: text/html');
header('Content-Disposition: attachment; filename="farm_reports.html"');
echo $html;
?>
