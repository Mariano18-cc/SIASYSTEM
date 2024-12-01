<?php
// Input validation function
function validateInput($salary, $frequency) {
    $errors = [];
    
    // Validate salary
    if (!is_numeric($salary) || $salary <= 0) {
        $errors[] = "Salary must be a positive number";
    }
    
    // Validate frequency
    $valid_frequencies = ['weekly', 'semi-monthly', 'monthly'];
    if (!in_array($frequency, $valid_frequencies)) {
        $errors[] = "Invalid payment frequency";
    }
    
    return $errors;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['salary'])) {
    $salary = floatval($_POST['salary']);
    $frequency = $_POST['frequency'] ?? '';
    
    $errors = validateInput($salary, $frequency);
    
    if (!empty($errors)) {
        header('Content-Type: application/json');
        echo json_encode(['errors' => $errors]);
        exit;
    }
    
    // Set the frequency for tax calculation
    $GLOBALS['frequency'] = $frequency;
    
    $results = calculateContributions($salary);
    
    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($results);
    exit;
}

function calculateIncomeTax($actualsalary) {
    // Get frequency from GLOBALS
    $frequency = $GLOBALS['frequency'] ?? 'monthly'; // Default to monthly if not set
    $untaxedsalary = $actualsalary;

    if (!in_array($frequency, ['weekly', 'semi-monthly', 'monthly'])) {
        throw new Exception("Invalid frequency: $frequency");
    }

    switch ($frequency) {
        case 'weekly':
            if ($untaxedsalary <= 4808) {
                return 0; 
            } elseif ($untaxedsalary < 7691) {
                return $untaxedsalary * 0.15;
            } elseif ($untaxedsalary < 15384) {
                return 432.60 + ($untaxedsalary * 0.20);
            } elseif ($untaxedsalary < 38461) {
                return 1971.20 + ($untaxedsalary * 0.25);
            } elseif ($untaxedsalary < 153845) {
                return 7740.45 + ($untaxedsalary * 0.30);
            } else {
                return 42355.65 + ($untaxedsalary * 0.35);
            }

        case 'semi-monthly':
            if ($untaxedsalary < 10417) {
                return 0;
            } elseif ($untaxedsalary < 16666) {
                return $untaxedsalary * 0.15;
            } elseif ($untaxedsalary < 33332) {
                return 937.50 + ($untaxedsalary * 0.20);
            } elseif ($untaxedsalary < 83332) {
                return 4270.70 + ($untaxedsalary * 0.25);
            } elseif ($untaxedsalary < 333332) {
                return 16770.70 + ($untaxedsalary * 0.30);
            } else {
                return 91770.70 + ($untaxedsalary * 0.35);
            }

        case 'monthly':
            if ($untaxedsalary < 20833) {
                return 0;
            } elseif ($untaxedsalary < 33332) {
                return $untaxedsalary * 0.15;
            } elseif ($untaxedsalary < 66666) {
                return 1875.00 + ($untaxedsalary * 0.20);
            } elseif ($untaxedsalary < 166666) {
                return 8541.80 + ($untaxedsalary * 0.25);
            } elseif ($untaxedsalary < 666666) {
                return $untaxedsalary * 0.30;
            } else {
                return $untaxedsalary * 0.35;
            }
    }
}

function calculatePhilHealth($untaxedsalary) {
    // 2024 PhilHealth Premium Rate is 5%
    $premium_rate = 0.05;
    
    // Floor and ceiling amounts
    $minimum_salary = 10000;
    $maximum_salary = 100000;
    
    // Calculate monthly premium
    if ($untaxedsalary <= $minimum_salary) {
        return 500; // Minimum monthly premium
    } elseif ($untaxedsalary >= $maximum_salary) {
        return 5000; // Maximum monthly premium
    } else {
        return $untaxedsalary * $premium_rate;
    }
}

function calculatePagibig($untaxedsalary) {
    $pagibigRate = 0.02;
    $pagibigContribution = $untaxedsalary * $pagibigRate;
    return $pagibigContribution > 200 ? 200 : $pagibigContribution;
}

function calculateSSS($untaxedsalary) {
    // Your existing SSS calculation function remains the same
    if ($untaxedsalary < 4250) {
        return 180.00;
    } elseif ($untaxedsalary < 4749.99) {
        return 202.50;
    }
    // ... rest of the SSS brackets ...
    else {
        return 900.00;
    }
}

function calculateContributions($actualsalary) {
    $incomeTax = calculateIncomeTax($actualsalary);
    $philHealth = calculatePhilHealth($actualsalary);
    $pagibig = calculatePagibig($actualsalary);
    $sss = calculateSSS($actualsalary);
    
    return [
        'Income Tax' => number_format($incomeTax, 2),
        'PhilHealth' => number_format($philHealth, 2),
        'Pag-IBIG' => number_format($pagibig, 2),
        'SSS' => number_format($sss, 2),
        'Total Deductions' => number_format($incomeTax + $philHealth + $pagibig + $sss, 2),
        'Net Salary' => number_format($actualsalary - ($incomeTax + $philHealth + $pagibig + $sss), 2)
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enhanced Payroll Calculator</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }

        .calculator-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input, select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #45a049;
        }

        .tooltip {
            position: relative;
            display: inline-block;
            cursor: help;
        }

        .tooltip .tooltiptext {
            visibility: hidden;
            width: 200px;
            background-color: #555;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 5px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            margin-left: -100px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .tooltip:hover .tooltiptext {
            visibility: visible;
            opacity: 1;
        }

        .loading {
            display: none;
            text-align: center;
            padding: 20px;
        }

        .loading:after {
            content: "⚪⚪⚪";
            animation: loading 1s infinite;
        }

        @keyframes loading {
            0% { content: "⚪⚪⚪"; }
            33% { content: "⚫⚪⚪"; }
            66% { content: "⚫⚫⚪"; }
            100% { content: "⚫⚫⚫"; }
        }

        .results {
            margin-top: 20px;
            display: none;
        }

        .results table {
            width: 100%;
            border-collapse: collapse;
        }

        .results th, .results td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .results th {
            background-color: #f8f8f8;
        }

        .print-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
            width: auto;
            display: inline-block;
        }

        @media print {
            .calculator-container {
                box-shadow: none;
            }
            
            .form-group, button, .print-button {
                display: none;
            }

            .results {
                display: block !important;
            }
        }
    </style>
</head>
<body>
    <div class="calculator-container">
        <h2>Enhanced Payroll Calculator</h2>
        <form id="payrollForm" method="post">
            <div class="form-group">
                <label for="salary" class="tooltip">
                    Gross Salary 
                    <span class="tooltiptext">Enter the total salary before deductions</span>
                </label>
                <input type="number" id="salary" name="salary" required step="0.01" min="0">
            </div>

            <div class="form-group">
                <label for="frequency" class="tooltip">
                    Payment Frequency
                    <span class="tooltiptext">Select how often you receive your salary</span>
                </label>
                <select id="frequency" name="frequency" required>
                    <option value="">Select frequency</option>
                    <option value="weekly">Weekly</option>
                    <option value="semi-monthly">Semi-Monthly</option>
                    <option value="monthly">Monthly</option>
                </select>
            </div>

            <div class="loading" id="loadingIndicator">Calculating...</div>
            
            <button type="submit">Calculate</button>
        </form>

        <div class="results" id="results">
            <h3>Calculation Results</h3>
            <table>
                <tr>
                    <th>Item</th>
                    <th>Amount (PHP)</th>
                </tr>
                <tbody id="resultsBody">
                </tbody>
            </table>
            <button class="print-button" onclick="window.print()">Print Results</button>
        </div>
    </div>

    <script>
        document.getElementById('payrollForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const salary = document.getElementById('salary').value;
            const frequency = document.getElementById('frequency').value;
            const loadingIndicator = document.getElementById('loadingIndicator');
            const resultsDiv = document.getElementById('results');
            
            // Show loading indicator
            loadingIndicator.style.display = 'block';
            resultsDiv.style.display = 'none';

            fetch('tax.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `salary=${salary}&frequency=${frequency}`
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                loadingIndicator.style.display = 'none';
                
                if (data.errors) {
                    alert(data.errors.join('\n'));
                    return;
                }

                const resultsBody = document.getElementById('resultsBody');
                resultsBody.innerHTML = '';

                for (const [key, value] of Object.entries(data)) {
                    const row = `
                        <tr>
                            <td>${key}</td>
                            <td>₱${value}</td>
                        </tr>
                    `;
                    resultsBody.innerHTML += row;
                }

                resultsDiv.style.display = 'block';
            })
            .catch(error => {
                loadingIndicator.style.display = 'none';
                console.error('Error:', error);
                alert('An error occurred while calculating.');
            });
        });
    </script>
</body>
</html>