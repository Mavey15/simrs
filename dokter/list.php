<?php
require_once __DIR__ . '/../_config/config.php';

// Handle bulk delete
$bulk_delete_msg = '';
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'bulk_delete') {
    if(isset($_POST['ids']) && is_array($_POST['ids']) && count($_POST['ids']) > 0) {
        $ids_to_delete = array_map(function($id) { return "'" . mysqli_real_escape_string($con, $id) . "'"; }, $_POST['ids']);
        $ids_str = implode(',', $ids_to_delete);
        $del_sql = "DELETE FROM dokter WHERE id_dokter IN ($ids_str)";
        if(mysqli_query($con, $del_sql)) {
            $bulk_delete_msg = 'Berhasil menghapus ' . count($_POST['ids']) . ' data.';
        }
    }
}

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$where = '';
if($search !== '') {
    $s = mysqli_real_escape_string($con, $search);
    $where = "WHERE id_dokter LIKE '%$s%' OR nama_dokter LIKE '%$s%' OR spesialis LIKE '%$s%'";
}

$count_sql = "SELECT COUNT(*) as total FROM dokter $where";
$count_result = mysqli_query($con, $count_sql);
$count_row = mysqli_fetch_assoc($count_result);
$total_records = $count_row['total'];
$total_pages = ceil($total_records / $limit);

$sql = "SELECT * FROM dokter $where ORDER BY id_dokter DESC LIMIT $limit OFFSET $offset";
$result = mysqli_query($con, $sql) or die(mysqli_error($con));

require_once __DIR__ . '/../_header.php';
?>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card-style">
                <h2><i class="fa fa-user-md"></i> Data Dokter</h2>
                <?php if($bulk_delete_msg): ?>
                    <div class="alert alert-success" style="margin-top:10px;"><?=htmlspecialchars($bulk_delete_msg);?></div>
                <?php endif; ?>
                <div style="margin-bottom:15px;">
                    <p style="margin:0 0 10px 0;">
                        <a href="<?=base_url('dokter/add.php');?>" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Tambah Dokter
                        </a>
                    </p>
                    <p style="margin:0; color:#666;">
                        <strong>Total Data:</strong> <?=$total_records;?> | <strong>Halaman:</strong> <?=$page;?> dari <?=$total_pages;?>
                    </p>
                </div>

                <form method="get" class="form-inline" style="margin-bottom:20px;">
                    <div class="form-group">
                        <input type="text" name="search" class="form-control" placeholder="Cari ID, nama, atau spesialis..." value="<?=htmlspecialchars($search);?>">
                    </div>
                    <button type="submit" class="btn btn-secondary"><i class="fa fa-search"></i> Cari</button>
                    <?php if($search !== ''): ?>
                        <a href="<?=base_url('dokter/list.php');?>" class="btn btn-default"><i class="fa fa-undo"></i> Reset</a>
                    <?php endif; ?>
                </form>

                <div id="dokterButtonsContainer" style="margin-bottom:15px;"></div>

                <form method="post" id="bulk-delete-form">
                    <div class="table-responsive">
                        <table id="dokterTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th style="width:30px;"><input type="checkbox" id="select-all" onclick="toggleSelectAll(this)"></th>
                                    <th>ID Dokter</th>
                                    <th>Nama Dokter</th>
                                    <th>Spesialis</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(mysqli_num_rows($result) > 0): ?>
                                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                                        <tr>
                                            <td><input type="checkbox" name="ids[]" value="<?=htmlspecialchars($row['id_dokter']);?>"></td>
                                            <td><?=htmlspecialchars($row['id_dokter']);?></td>
                                            <td><?=htmlspecialchars($row['nama_dokter']);?></td>
                                            <td><?=htmlspecialchars($row['spesialis']);?></td>
                                            <td>
                                                <a href="<?=base_url('dokter/edit.php?id='.$row['id_dokter']);?>" class="btn btn-xs btn-warning">
                                                    <i class="fa fa-edit"></i> Edit
                                                </a>
                                                <a href="<?=base_url('dokter/delete.php?id='.$row['id_dokter']);?>" class="btn btn-xs btn-danger" onclick="return confirm('Yakin hapus data ini?');">
                                                    <i class="fa fa-trash"></i> Hapus
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr><td colspan="5" class="text-center">Tidak ada data.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if(mysqli_num_rows($result) > 0): ?>
                        <div style="margin-top:10px;">
                            <button type="submit" name="action" value="bulk_delete" class="btn btn-danger" onclick="return confirm('Yakin hapus data terpilih?');">
                                <i class="fa fa-trash"></i> Hapus Terpilih
                            </button>
                        </div>
                    <?php endif; ?>
                </form>

                <!-- Pagination -->
                <?php if($total_pages > 1): ?>
                    <nav>
                        <ul class="pagination">
                            <?php if($page > 1): ?>
                                <li><a href="<?=base_url('dokter/list.php?page=1'.($search ? '&search='.$search : ''));?>">First</a></li>
                                <li><a href="<?=base_url('dokter/list.php?page='.($page-1).($search ? '&search='.$search : ''));?>">Previous</a></li>
                            <?php endif; ?>

                            <?php for($i = max(1, $page-2); $i <= min($total_pages, $page+2); $i++): ?>
                                <li <?=($i == $page ? 'class="active"' : '');?>>
                                    <a href="<?=base_url('dokter/list.php?page='.$i.($search ? '&search='.$search : ''));?>"><?=$i;?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if($page < $total_pages): ?>
                                <li><a href="<?=base_url('dokter/list.php?page='.($page+1).($search ? '&search='.$search : ''));?>">Next</a></li>
                                <li><a href="<?=base_url('dokter/list.php?page='.$total_pages.($search ? '&search='.$search : ''));?>">Last</a></li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>

                </div> <!-- /.card-style -->
            </div>
        </div>
    </div>

<script>
var table = $('#dokterTable').DataTable({
    dom: '<"row"<"col-sm-6"B><"col-sm-6"f>>t<"row"<"col-sm-5"i><"col-sm-7"p>>',
    buttons: [
        {
            extend: 'copy',
            text: '<i class="fa fa-copy"></i> Copy',
            className: 'btn btn-sm btn-info'
        },
        {
            extend: 'csv',
            text: '<i class="fa fa-download"></i> CSV',
            className: 'btn btn-sm btn-success',
            filename: 'Data_Dokter_' + new Date().toLocaleDateString()
        },
        {
            extend: 'excel',
            text: '<i class="fa fa-download"></i> Excel',
            className: 'btn btn-sm btn-success',
            filename: 'Data_Dokter_' + new Date().toLocaleDateString()
        },
        {
            extend: 'pdf',
            text: '<i class="fa fa-download"></i> PDF',
            className: 'btn btn-sm btn-danger',
            filename: 'Data_Dokter_' + new Date().toLocaleDateString(),
            title: 'Data Dokter',
            orientation: 'landscape'
        },
        {
            extend: 'print',
            text: '<i class="fa fa-print"></i> Print',
            className: 'btn btn-sm btn-warning'
        }
    ],
    responsive: true,
    paging: true,
    pageLength: 10,
    searching: true,
    ordering: true,
    info: true
});

function toggleSelectAll(checkbox) {
    var checkboxes = document.querySelectorAll('input[name="ids[]"]');
    checkboxes.forEach(function(cb) {
        cb.checked = checkbox.checked;
    });
}
document.querySelectorAll('input[name="ids[]"]').forEach(function(cb) {
    cb.addEventListener('change', function() {
        var allChecked = document.querySelectorAll('input[name="ids[]"]:checked').length === document.querySelectorAll('input[name="ids[]"]').length;
        document.getElementById('select-all').checked = allChecked;
    });
});
</script>

<?php
require_once __DIR__ . '/../_footer.php';
