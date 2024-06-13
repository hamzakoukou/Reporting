<?php
include 'layout/header.php'; 


$month = $_GET['month'] ?? date('Y-m'); // Get month from URL or default to current month

function fetchMonthlyData($pdo, $month) {
    $stmt = $pdo->prepare("SELECT * FROM `resultat` WHERE DATE_FORMAT(MOIS, '%Y-%m') = :month");
    $stmt->bindParam(':month', $month);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$monthlyData = fetchMonthlyData($pdo, $month);

?>

<main id="main-container" style="display: flex; justify-content: center;">
    <div class="block" style="width: 80%;">
        <h2 style="text-align: center;">Données pour le mois de <?= htmlspecialchars($month) ?></h2>
        <div class="row">
            <div class="col-sm-11">
                <input type="text" class="form-control search-input" placeholder="Search..." data-table="monthlyData">
            </div>
            <div class="col-sm-1">
                <!-- Download Button -->
                <a href="downloads.php?month=<?= $month ?>" class="btn btn-primary">Download</a>
            </div>
        </div>
        <div class="table-responsive">
            <?php if (count($monthlyData) > 0): ?>
                <table class='table table-bordered table-striped' style="margin: auto;">
                    <thead>
                        <tr>
                            <?php foreach (array_keys($monthlyData[0]) as $columnName): ?>
                                <th><?= htmlspecialchars($columnName) ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($monthlyData as $row): ?>
                            <tr>
                                <?php foreach ($row as $cell): ?>
                                    <td><?= htmlspecialchars($cell) ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="text-align: center;">Aucune donnée disponible pour le mois sélectionné.</p>
            <?php endif; ?>
        </div>
    </div>
</main>
<?php include 'layout/footer.php'; ?>





