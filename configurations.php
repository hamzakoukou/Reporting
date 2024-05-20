<?php
include 'layout/header.php'; 
include 'connection.php';


function fetchTableData($pdo, $tableName) {
    $stmt = $pdo->prepare("SELECT * FROM `$tableName`");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



$tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

$tableDisplayNames = [
    'codesap' => 'Codes SAP',
    'costdirect' => 'Codes Costs Directs',
    'tarifsjh' => 'Tarifs JH',
    'typecost' => 'Types de Costs'
];

function getDisplayName($tableName, $tableDisplayNames) {
    return $tableDisplayNames[$tableName] ?? $tableName; // Return the table name itself if not found in the dictionary
}

?>

<main id="main-container">
    <div class="block">
        <ul class="nav nav-tabs nav-tabs-alt" data-toggle="tabs">
            <?php foreach ($tables as $index => $table): ?>
                <li class="<?= $index === 0 ? 'active' : '' ?>">
                    <a href="#<?= $table ?>" data-toggle="tab"><?= getDisplayName($table, $tableDisplayNames) ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
        <div class="block-content tab-content">
            <?php foreach ($tables as $index => $table): ?>
                <div class="tab-pane <?= $index === 0 ? 'active' : '' ?>" id="<?= $table ?>">
                    <input type="text" class="form-control search-input" placeholder="Search..." data-table="<?= $table ?>">
                    <!-- Edit Button -->
                    <a href="editconfigurations.php?table=<?= $table ?>" class="btn btn-primary">Edit</a>
                    
                    <div class="table-responsive">
                        <?php
                        $tableData = fetchTableData($pdo, $table);
                        if (count($tableData) > 0) {
                            echo "<table class='table table-bordered table-striped'>";
                            echo "<thead><tr>";
                            foreach (array_keys($tableData[0]) as $columnName) {
                                echo "<th>$columnName</th>";
                            }
                            echo "</tr></thead>";
                            echo "<tbody>";
                            foreach ($tableData as $row) {
                                echo "<tr>";
                                foreach ($row as $cell) {
                                    echo "<td>$cell</td>";
                                }
                                echo "</tr>";
                            }
                            echo "</tbody>";
                            echo "</table>";
                        } else {
                            echo "No data available.";
                        }
                        ?>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <select class="row-count-selector" data-table="<?= $table ?>">
                                <option value="10">10 rows</option>
                                <option value="20">20 rows</option>
                                <option value="50">50 rows</option>
                            </select>
                        </div>
                        <div class="col-sm-5">
                            <button class="prev-page" data-table="<?= $table ?>">Prev</button>
                            <select class="page-selector" data-table="<?= $table ?>"></select>
                            <button class="next-page" data-table="<?= $table ?>">Next</button>
                        </div>
                        <div class="col-sm-3">
                            <span class="pagination-info" data-table="<?= $table ?>"></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    function updatePaginationDisplay(table, currentPage, totalPages) {
        $('#' + table + ' .pagination-info').text('Page ' + (currentPage + 1) + ' of ' + totalPages);
        var pageSelector = $('#' + table + ' .page-selector');
        pageSelector.empty();
        for (let i = 0; i < totalPages; i++) {
            pageSelector.append($('<option>', {
                value: i,
                text: 'Page ' + (i + 1)
            }));
        }
        pageSelector.val(currentPage);
    }

    function paginate(table, rowCount) {
        var totalRows = $('#' + table + ' tbody tr').length;
        var totalPages = Math.ceil(totalRows / rowCount);
        var currentPage = parseInt($('#' + table + ' .page-selector').val()) || 0;

        $('#' + table + ' tbody tr').hide().slice(currentPage * rowCount, (currentPage + 1) * rowCount).show();
        updatePaginationDisplay(table, currentPage, totalPages);
    }

    $('.row-count-selector').change(function() {
        var table = $(this).data('table');
        var rowCount = $(this).val();
        paginate(table, rowCount);
    });

    $('.search-input').on('keyup', function() {
        var searchTerm = $(this).val().toLowerCase();
        var table = $(this).data('table');
        $('#' + table + ' tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(searchTerm) > -1)
        });
    });

    $('.prev-page, .next-page').click(function() {
        var table = $(this).data('table');
        var rowCount = $('#' + table + ' .row-count-selector').val();
        var currentPage = parseInt($('#' + table + ' .page-selector').val());
        var totalPages = Math.ceil($('#' + table + ' tbody tr').length / rowCount);

        if ($(this).hasClass('prev-page') && currentPage > 0) {
            currentPage--;
        } else if ($(this).hasClass('next-page') && currentPage < totalPages - 1) {
            currentPage++;
        }

        $('#' + table + ' .page-selector').val(currentPage);
        paginate(table, rowCount);
    });

    $('.page-selector').change(function() {
        var table = $(this).data('table');
        var rowCount = $('#' + table + ' .row-count-selector').val();
        paginate(table, rowCount);
    });

    // Initialize display and pagination info
    $('.tab-pane').each(function() {
        var table = $(this).attr('id');
        var rowCount = $('#' + table + ' .row-count-selector').val();
        paginate(table, rowCount);
    });
});
</script>

<?php include 'layout/footer.php'; ?>

