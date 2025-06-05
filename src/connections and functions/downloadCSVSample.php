<?php
// download_csv.php

// 1. Tell the browser this is a CSV download
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="sample_students.csv"');

// 2. Prevent caching (optional but recommended)
header('Pragma: no-cache');
header('Expires: 0');

// 3. Output the CSV content directly.
//    Since your upload logic expects NO HEADER ROW, we’ll provide only sample rows.
//
$output = fopen('php://output', 'w');

// Example rows (no header). Feel free to adjust or leave just an empty template line.
// Note: each line must have exactly three comma-separated fields: Name,Username,Email
fputcsv($output, ['John Doe','202112345','jdoe@example.com']);
fputcsv($output, ['Jane Smith','202401244','jsmith@example.com']);
fputcsv($output, ['Jane Doe','202501234','janedoe@example.com']);

// Close the output handle
fclose($output);
exit();