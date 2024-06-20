<div class="page-header">
    <h1>Perhitungan Manual</h1>
</div>
<?php
$rel_alternatif = get_rel_alternatif();
foreach ($rel_alternatif as $key => $val) {
    foreach ($val as $k => $v) {
        $data[$key][$k] = $CRIPS[$v]->nilai;
    }
}
foreach ($KRITERIA as $key => $val) {
    $atribut[$key] = $val->atribut;
    $bobot[$key] = $val->bobot;
}
$saw = new SAW($data, $atribut, $bobot);
?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Hasil Analisa</h3>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <?php foreach ($KRITERIA as $key => $val) : ?>
                        <th><?= $val->nama_kriteria ?></th>
                    <?php endforeach ?>
                </tr>
            </thead>
            <?php foreach ($rel_alternatif as $key => $value) : ?>
                <tr>
                    <td><?= $key ?></td>
                    <td><?= $ALTERNATIF[$key] ?></td>
                    <?php foreach ($value as $k => $v) : ?>
                        <td><?= $CRIPS[$v]->nama_crips ?></td>
                    <?php endforeach ?>
                </tr>
            <?php endforeach ?>
        </table>
    </div>
</div>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Nilai Alternatif</h3>
    </div>
    <table class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th>Kode</th>
                <?php foreach ($KRITERIA as $key => $val) : ?>
                    <th><?= $key ?></th>
                <?php endforeach ?>
        </thead>
        <?php
        $no = 1;
        foreach ($rel_alternatif as $key => $value) : ?>
            <tr>
                <td><?= $key ?></td>
                <?php foreach ($value as $k => $v) : ?>
                    <td><?= $CRIPS[$v]->nilai ?></td>
                <?php endforeach ?>
            </tr>
        <?php endforeach ?>
    </table>
</div>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Mencari Nilai Maksimum dan Minimum Kriteria</h3>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover table-condensed">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Atribut</th>
                    <th>Bobot</th>
                    <th>Max</th>
                    <th>Min</th>
                    <th>Bobot Normal</th>
                </tr>
            </thead>
            <?php
            $no = 1;
            $arr = array();
            foreach ($saw->rel_alternatif as $key => $val) {
                foreach ($val as $k => $v) {
                    $arr[$k][$key] = $v;
                }
            }
            $minmax = array();
            foreach ($KRITERIA as $key => $val) :
                $minmax[$key]['max'] = max($arr[$key]);
                $minmax[$key]['min'] = min($arr[$key]); ?>
                <tr>
                    <td><?= $key ?> </td>
                    <td> <?= $val->nama_kriteria ?></td>
                    <td> <?= $val->atribut ?></td>
                    <td> <?= $val->bobot ?></td>
                    <td><code>max(<?= implode(', ', $arr[$key]) ?>)=<?= $minmax[$key]['max'] ?></code></td>
                    <td><code>min(<?= implode(', ', $arr[$key]) ?>)=<?= $minmax[$key]['min'] ?></code></td>
                    <td><code>1/<?= $saw->bobot[$key] ?>=<?= round($saw->bobot_normal[$key], 4) ?></code></td>
                </tr>
            <?php endforeach ?>
            <tfoot>
                <tr>
                    <td colspan="3">Total Bobot</td>
                    <td><?= array_sum($saw->bobot) ?></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Normalisasi</h3>
    </div>
    <div class="panel-body">
        <ul>
            <?php
            $normal = array();
            foreach ($saw->rel_alternatif as $key => $val) : ?>
                <li>
                    Perhitungan <b><?= $key ?> -<?= $ALTERNATIF[$key] ?></b>
                    <ul>
                        <?php foreach ($val as $k => $v) :
                            $normal[$key][$k] = $KRITERIA[$k]->atribut == 'benefit' ?  $v / $minmax[$k]['max'] : $minmax[$k]['min'] / $v;
                        ?>
                            <li>
                                Kriteria <b><?= $k ?> -<?= $KRITERIA[$k]->nama_kriteria ?></b>, karena bertipe <code><?= $KRITERIA[$k]->atribut ?></code>, maka
                                <?php if ($KRITERIA[$k]->atribut == 'benefit') : ?>
                                    <code><?= $v ?> / <?= $minmax[$k]['max'] ?> = <?= round($normal[$key][$k], 4) ?></code>
                                <?php else : ?>
                                    <code><?= $minmax[$k]['min'] ?> / <?= $v ?>= <?= round($normal[$key][$k], 4) ?></code>
                                <?php endif ?>
                            </li>
                        <?php endforeach ?>
                    </ul>
                </li>
            <?php endforeach ?>
        </ul>
        <p>Hasilnya bisa dilihat sebagai berikut:</p>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>Kode</th>
                    <?php foreach ($KRITERIA as $key => $val) : ?>
                        <th><?= $key ?></th>
                    <?php endforeach ?>
                </tr>
            </thead>
            <?php foreach ($saw->normal as $key => $val) : ?>
                <tr>
                    <td><?= $key ?></td>
                    <?php foreach ($val as $k => $v) : ?>
                        <td><?= round($v, 4) ?></td>
                    <?php endforeach ?>
                </tr>
            <?php endforeach ?>
        </table>
    </div>
</div>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Normalisasi Terbobot</h3>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>Kode</th>
                    <?php foreach ($KRITERIA as $key => $val) : ?>
                        <th><?= $key ?></th>
                    <?php endforeach ?>
                </tr>
            </thead>
            <?php foreach ($saw->normal as $key => $val) : ?>
                <tr>
                    <td><?= $key ?></td>
                    <?php foreach ($val as $k => $v) : ?>
                        <td><code><?= round($v, 4) ?>*<?= round($saw->bobot_normal[$k], 4) ?>=<?= round($saw->terbobot[$key][$k], 4) ?></code></td>
                    <?php endforeach ?>
                </tr>
            <?php endforeach ?>
        </table>
    </div>
</div>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Perangkingan</h3>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Perhitungan</th>
                    <th>Total</th>
            </thead>
            <?php
            $rank = get_rank($saw->total);
            $categories = array();
            $series = array();
            foreach ($rank as $key => $val) :
                $arr = array();
                foreach ($saw->terbobot[$key] as $k => $v)
                    $arr[] = round($v, 4) ?>
                <tr>
                    <td><?= $val ?></td>
                    <td><?= $key ?></td>
                    <td><?= $ALTERNATIF[$key] ?></td>
                    <td>
                        <code><?= implode('+', $arr) ?></code>
                    </td>
                    <td><?= round($saw->total[$key], 4) ?></td>
                </tr>
            <?php endforeach ?>
        </table>
    </div>
</div>