<?php
include 'includes/session.php';
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$contractor_id = $_SESSION['user_id'];

$sql = "
    SELECT 
        p.id, p.labour_id, p.total_amount, p.payed_amount, p.remaining_amount, 
        p.from_date, p.to_date, p.note, l.name AS labour_name, l.mobile AS labour_mobile
    FROM payments p
    JOIN labours l ON p.labour_id = l.id
    WHERE l.contractor_id = ?
    ORDER BY p.to_date DESC, p.id DESC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $contractor_id);
$stmt->execute();
$result = $stmt->get_result();
$payments = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="hi">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>भुगतान इतिहास | Payment History</title>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f5f7fa;
        padding: 1rem;
        color: #333;
    }
    h1 {
        text-align: center;
        margin-bottom: 1rem;
    }
    .search-container {
        max-width: 400px;
        margin: 0 auto 1rem;
    }
    .search-container input {
        width: 100%;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        border: 1.8px solid #4a90e2;
        font-size: 1rem;
        box-sizing: border-box;
    }
    .table-wrapper {
        overflow-x: auto;
        max-width: 960px;
        margin: 0 auto;
        background: white;
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        padding: 1rem 1.5rem;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.95rem;
    }
    thead tr {
        background: linear-gradient(90deg, #4a90e2, #357ABD);
        color: white;
        text-transform: uppercase;
        font-weight: 700;
        font-size: 0.85rem;
    }
    thead th, tbody td {
        padding: 0.7rem 0.8rem;
        border-bottom: 1px solid #e3e9f1;
        text-align: left;
    }
    tbody tr:nth-child(odd) {
        background: #fafbff;
    }
    tbody tr:hover {
        background: #d7e3fc;
        box-shadow: inset 3px 0 0 0 #357ABD;
    }
    @media (max-width: 700px) {
        thead {
            display: none;
        }
        tbody tr {
            display: block;
            margin-bottom: 1.2rem;
            background: #fff;
            padding: 1rem;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(53, 122, 189, 0.15);
        }
        tbody td {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: none;
            font-size: 0.9rem;
        }
        tbody td::before {
            content: attr(data-label);
            font-weight: 600;
            color: #4a90e2;
        }
    }
</style>
</head>
<body>
<div class="search-container">
    <input type="text" id="searchInput" placeholder="मज़दूर का नाम या मोबाइल से खोजें..." />
</div>
<div class="table-wrapper">
    <table id="paymentTable" aria-label="Payment history table">
        <thead>
            <tr>
                <th>मज़दूर का नाम</th>
                <th>मोबाइल</th>
                <th>अवधि (From - To)</th>
                <th>कुल राशि (₹)</th>
                <th>भुगतान राशि (₹)</th>
                <th>बकाया राशि (₹)</th>
                <th>नोट्स</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($payments) > 0): ?>
                <?php foreach ($payments as $index => $p): ?>
                    <tr>
                        <td data-label="मज़दूर का नाम"><?= htmlspecialchars($p['labour_name']) ?></td>
                        <td data-label="मोबाइल"><?= htmlspecialchars($p['labour_mobile']) ?></td>
                        <td data-label="अवधि"><?= htmlspecialchars($p['from_date']) ?> - <?= htmlspecialchars($p['to_date']) ?></td>
                        <td data-label="कुल राशि"><?= number_format($p['total_amount'], 2) ?></td>
                        <td data-label="भुगतान राशि"><?= number_format($p['payed_amount'], 2) ?></td>
                        <td data-label="बकाया राशि"><?= number_format($p['remaining_amount'], 2) ?></td>
                        <td data-label="नोट्स"><?= htmlspecialchars($p['note'] ?: '—') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7" style="text-align:center; padding:2rem; color:#888;">कोई भुगतान रिकॉर्ड उपलब्ध नहीं है।</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
    const searchInput = document.getElementById('searchInput');
    const table = document.getElementById('paymentTable');
    const rows = table.tBodies[0].rows;

    searchInput.addEventListener('input', () => {
        const filter = searchInput.value.toLowerCase();

        for (let row of rows) {
            const name = row.cells[0].textContent.toLowerCase();
            const mobile = row.cells[1].textContent.toLowerCase();

            if (name.includes(filter) || mobile.includes(filter)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    });
</script>
</body>
</html>
