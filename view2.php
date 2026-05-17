<?php
session_start();

if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    header("Location: login.php");
    exit;
}

$servername = getenv('MYSQLHOST')     ?: 'localhost';
$username   = getenv('MYSQLUSER')     ?: 'root';
$password   = getenv('MYSQLPASSWORD') ?: '';
$dbname     = getenv('MYSQLDATABASE') ?: 'footwear';
$port       = getenv('MYSQLPORT')     ?: 3306;

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$table  = isset($_GET["table"]) ? $_GET["table"] : "customers";
$tables = [
    // customers
    "customers",
    "addresses",
    "locations",
    // products
    "brands",
    "categories",
    "products",
    "product_variants",
    "inventory",
    // orders
    "orders",
    "order_items",
    "order_totals",
    "payments"
];

if (!in_array($table, $tables)) {
    $table = "customers";
}

// pagination setup
$limit       = 50;
$page        = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
$offset      = ($page - 1) * $limit;

$total_result = $conn->query("SELECT COUNT(*) AS total FROM $table");
$total_row    = $total_result->fetch_assoc();
$total        = $total_row["total"];
$total_pages  = ceil($total / $limit);

$result = $conn->query("SELECT * FROM $table LIMIT $limit OFFSET $offset");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Footwear Ordering System</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --cream:  #F5F0E8;
            --dark:   #1A1410;
            --brown:  #6B3F1F;
            --gold:   #C8963E;
            --tan:    #D4A96A;
            --light:  #FAF7F2;
            --muted:  #9C8B78;
            --border: #E8DFD3;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--light);
            color: var(--dark);
            min-height: 100vh;
        }

        /* ── HEADER ── */
        header {
            background: var(--dark);
            padding: 0 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 64px;
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 3px solid var(--gold);
        }

        .header-brand {
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            color: var(--cream);
            letter-spacing: 0.5px;
        }

        .header-brand span { color: var(--gold); }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .header-user {
            font-size: 13px;
            color: var(--tan);
        }

        .btn-logout {
            padding: 7px 18px;
            background: transparent;
            border: 1.5px solid rgba(200,150,62,0.4);
            border-radius: 4px;
            color: var(--tan);
            font-family: 'DM Sans', sans-serif;
            font-size: 12px;
            font-weight: 500;
            letter-spacing: 1px;
            text-decoration: none;
            text-transform: uppercase;
            transition: all 0.2s;
        }

        .btn-logout:hover {
            background: var(--brown);
            border-color: var(--brown);
            color: var(--cream);
        }

        /* ── NAV ── */
        .table-nav {
            background: #fff;
            border-bottom: 1px solid var(--border);
            padding: 0 32px;
            display: flex;
            gap: 4px;
            overflow-x: auto;
        }

        .table-nav a {
            display: inline-block;
            padding: 14px 16px;
            font-size: 12px;
            font-weight: 500;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: var(--muted);
            text-decoration: none;
            border-bottom: 3px solid transparent;
            white-space: nowrap;
            transition: all 0.2s;
        }

        .table-nav a:hover { color: var(--brown); }

        .table-nav a.active {
            color: var(--brown);
            border-bottom-color: var(--gold);
        }

        /* ── MAIN ── */
        main { padding: 28px 32px; }

        .table-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .table-title {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            color: var(--dark);
        }

        .table-meta {
            font-size: 13px;
            color: var(--muted);
        }

        /* ── TABLE ── */
        .table-wrapper {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 8px;
            overflow: auto;
            box-shadow: 0 2px 12px rgba(26,20,16,0.06);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        thead tr {
            background: var(--dark);
        }

        thead th {
            padding: 12px 16px;
            text-align: left;
            color: var(--tan);
            font-weight: 500;
            font-size: 11px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            white-space: nowrap;
        }

        tbody tr {
            border-bottom: 1px solid var(--border);
            transition: background 0.15s;
        }

        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: #FBF7F1; }

        tbody td {
            padding: 11px 16px;
            color: var(--dark);
            white-space: nowrap;
            max-width: 220px;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        tbody td.null-val { color: var(--muted); font-style: italic; }

        /* ── PAGINATION ── */
        .pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 20px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .page-info {
            font-size: 13px;
            color: var(--muted);
        }

        .page-info strong { color: var(--dark); }

        .page-buttons { display: flex; gap: 8px; align-items: center; }

        .page-buttons a, .page-buttons span {
            padding: 7px 16px;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            border: 1.5px solid var(--border);
            color: var(--brown);
            transition: all 0.2s;
        }

        .page-buttons a:hover {
            background: var(--brown);
            border-color: var(--brown);
            color: #fff;
        }

        .page-buttons .current {
            background: var(--dark);
            border-color: var(--dark);
            color: var(--cream);
        }

        /* ── EMPTY ── */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--muted);
        }

        .empty-state .icon { font-size: 48px; margin-bottom: 12px; }
        .empty-state p { font-size: 14px; }
    </style>
</head>
<body>

<!-- HEADER -->
<header>
    <div class="header-brand">Footwear <span>Ordering</span> System</div>
    <div class="header-right">
        <span class="header-user">👤 <?= htmlspecialchars($_SESSION["username"]) ?></span>
        <a href="logout.php" class="btn-logout">Logout</a>
    </div>
</header>

<!-- TABLE NAV -->
<nav class="table-nav">
    <?php foreach ($tables as $t): ?>
        <a href="?table=<?= $t ?>" <?= $t === $table ? 'class="active"' : '' ?>>
            <?= ucfirst(str_replace('_', ' ', $t)) ?>
        </a>
    <?php endforeach; ?>
</nav>

<!-- MAIN CONTENT -->
<main>
    <div class="table-header">
        <h2 class="table-title"><?= ucfirst(str_replace('_', ' ', $table)) ?></h2>
        <span class="table-meta">
            <?= $total ?> total records &nbsp;·&nbsp;
            Showing <?= $offset + 1 ?>–<?= min($offset + $limit, $total) ?>
        </span>
    </div>

    <div class="table-wrapper">
        <?php if ($result && $result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <?php
                    $fields = $result->fetch_fields();
                    foreach ($fields as $field) {
                        echo "<th>" . htmlspecialchars($field->name) . "</th>";
                    }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <?php foreach ($row as $value): ?>
                        <?php if (is_null($value)): ?>
                            <td class="null-val">NULL</td>
                        <?php else: ?>
                            <td title="<?= htmlspecialchars($value) ?>">
                                <?= htmlspecialchars($value) ?>
                            </td>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="empty-state">
            <div class="icon">📭</div>
            <p>No records found in <strong><?= $table ?></strong></p>
        </div>
        <?php endif; ?>
    </div>

    <!-- PAGINATION -->
    <?php if ($total_pages > 1): ?>
    <div class="pagination">
        <div class="page-info">
            Page <strong><?= $page ?></strong> of <strong><?= $total_pages ?></strong>
        </div>
        <div class="page-buttons">
            <?php if ($page > 1): ?>
                <a href="?table=<?= $table ?>&page=1">« First</a>
                <a href="?table=<?= $table ?>&page=<?= $page - 1 ?>">← Prev</a>
            <?php endif; ?>

            <span class="current"><?= $page ?></span>

            <?php if ($page < $total_pages): ?>
                <a href="?table=<?= $table ?>&page=<?= $page + 1 ?>">Next →</a>
                <a href="?table=<?= $table ?>&page=<?= $total_pages ?>">Last »</a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</main>

</body>
</html>
<?php $conn->close(); ?>
