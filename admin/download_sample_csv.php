<?php
// Sample CSV file download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="sample_questions.csv"');

// Create the CSV content
$data = [
    ['category', 'question', 'option1', 'option2', 'option3', 'option4', 'correct_answer'],
    ['technical', 'What does PHP stand for?', 'Personal Home Page', 'Preprocessor', 'Hypertext Preprocessor', 'Programming Language', '3'],
    ['technical', 'What does SQL stand for?', 'Structured Question Language', 'Structured Query Language', 'Simple Query Language', 'Standard Query Language', '2'],
    ['technical', 'Which JavaScript keyword declares a variable?', 'var', 'let', 'const', 'All of the above', '4'],
    ['softskills', 'A team member is struggling with their task. What do you do?', 'Ignore them', 'Do their work for them', 'Offer help and guidance', 'Complain to the manager', '3'],
    ['softskills', 'How do you handle a tight deadline?', 'Panic', 'Work overtime alone', 'Prioritize tasks and communicate with team', 'Give up', '3'],
    ['softskills', 'What is the best way to communicate technical issues to non-technical people?', 'Use technical jargon', 'Use simple analogies', 'Send a long email', 'Ignore them', '2']
];

$output = fopen('php://output', 'w');
foreach ($data as $row) {
    fputcsv($output, $row);
}
fclose($output);
exit();
?>