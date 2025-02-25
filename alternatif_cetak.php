<h1>Alternatif</h1>
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Kode</th>
            <th>Nama Alternatif</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <?php
    $q = esc_field(_get('q'));
    $rows = $db->get_results("SELECT * FROM tb_alternatif 
        WHERE 
            kode_alternatif LIKE '%$q%'
            OR nama_alternatif LIKE '%$q%'
            OR keterangan LIKE '%$q%'  
        ORDER BY kode_alternatif");
    $no = 0;
    foreach ($rows as $row) : ?>
        <tr>
            <td><?= ++$no ?></td>
            <td><?= $row->kode_alternatif ?></td>
            <td><?= $row->nama_alternatif ?></td>
            <td><?= $row->keterangan ?></td>
        </tr>
    <?php endforeach ?>
</table>