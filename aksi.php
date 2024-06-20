<?php
require_once 'functions.php';
/** LOGIN **/
if ($act == 'login') {
    $user = esc_field($_POST['user']);
    $pass = esc_field($_POST['pass']);

    $row = $db->get_row("SELECT * FROM tb_user WHERE user='$user' AND pass='$pass'");
    if ($row) {
        $_SESSION['login'] = $row->user;
        $_SESSION['level'] = $row->level;
        redirect_js("index.php");
    } else {
        print_msg("Salah kombinasi username dan password.");
    }
} else if ($mod == 'password') {
    $pass1 = $_POST['pass1'];
    $pass2 = $_POST['pass2'];
    $pass3 = $_POST['pass3'];

    $row = $db->get_row("SELECT * FROM tb_user WHERE user='$_SESSION[login]' AND pass='$pass1'");

    if ($pass1 == '' || $pass2 == '' || $pass3 == '')
        print_msg('Field bertanda * harus diisi.');
    elseif (!$row)
        print_msg('Password lama salah.');
    elseif ($pass2 != $pass3)
        print_msg('Password baru dan konfirmasi password baru tidak sama.');
    else {
        $db->query("UPDATE tb_user SET pass='$pass2' WHERE user='$_SESSION[login]'");
        print_msg('Password berhasil diubah.', 'success');
    }
} elseif ($act == 'logout') {
    unset($_SESSION['login'], $_SESSION['level']);
    header("location:login.php");
}
/** ALTERNATIF */
elseif ($mod == 'alternatif_tambah') {
    $kode_alternatif = $_POST['kode_alternatif'];
    $nama_alternatif = $_POST['nama_alternatif'];
    $keterangan = $_POST['keterangan'];
    if ($kode_alternatif == '' || $nama_alternatif == '')
        print_msg("Field bertanda * tidak boleh kosong!");
    elseif ($db->get_results("SELECT * FROM tb_alternatif WHERE kode_alternatif='$kode_alternatif'"))
        print_msg("Kode sudah ada!");
    else {
        $db->query("INSERT INTO tb_alternatif (kode_alternatif, nama_alternatif, keterangan) VALUES ('$kode_alternatif', '$nama_alternatif', '$keterangan')");

        $db->query("INSERT INTO tb_rel_alternatif(kode_alternatif, kode_kriteria, kode_crips) SELECT '$kode_alternatif', kode_kriteria, 0 FROM tb_kriteria");
        redirect_js("index.php?m=alternatif");
    }
} else if ($mod == 'alternatif_ubah') {
    $kode_alternatif = $_POST['kode_alternatif'];
    $nama_alternatif = $_POST['nama_alternatif'];
    $keterangan = $_POST['keterangan'];
    if ($kode_alternatif == '' || $nama_alternatif == '')
        print_msg("Field bertanda * tidak boleh kosong!");
    else {
        $db->query("UPDATE tb_alternatif SET nama_alternatif='$nama_alternatif', keterangan='$keterangan' WHERE kode_alternatif='$_GET[ID]'");
        redirect_js("index.php?m=alternatif");
    }
} else if ($act == 'alternatif_hapus') {
    $db->query("DELETE FROM tb_alternatif WHERE kode_alternatif='$_GET[ID]'");
    $db->query("DELETE FROM tb_rel_alternatif WHERE kode_alternatif='$_GET[ID]'");
    header("location:index.php?m=alternatif");
}

/** KRITERIA */
elseif ($mod == 'kriteria_tambah') {
    $kode_kriteria = $_POST['kode_kriteria'];
    $nama_kriteria = $_POST['nama_kriteria'];
    $atribut = $_POST['atribut'];
    $bobot = $_POST['bobot'];

    if ($kode_kriteria == '' || $nama_kriteria == '' || $atribut == '' || $bobot == '')
        print_msg("Field bertanda * tidak boleh kosong!");
    elseif ($bobot > 100)
        print_msg("Maksimal bobot 100!");
    elseif ($db->get_results("SELECT * FROM tb_kriteria WHERE kode_kriteria='$kode_kriteria'"))
        print_msg("Kode sudah ada!");
    else {
        $db->query("INSERT INTO tb_kriteria (kode_kriteria, nama_kriteria, atribut, bobot) VALUES ('$kode_kriteria', '$nama_kriteria', '$atribut', '$bobot')");
        $db->query("INSERT INTO tb_rel_alternatif(kode_alternatif, kode_kriteria, kode_crips) SELECT kode_alternatif, '$kode_kriteria', 0  FROM tb_alternatif");
        redirect_js("index.php?m=kriteria");
    }
} else if ($mod == 'kriteria_ubah') {
    $nama_kriteria = $_POST['nama_kriteria'];
    $atribut = $_POST['atribut'];
    $bobot = $_POST['bobot'];

    if ($nama_kriteria == '' || $atribut == '' || $bobot == '')
        print_msg("Field bertanda * tidak boleh kosong!");
    elseif ($bobot > 100)
        print_msg("Maksimal bobot 100!");
    elseif ($db->get_results("SELECT * FROM tb_kriteria WHERE kode_kriteria='$kode_kriteria'"))
        print_msg("Kode sudah ada!");
    else {
        $db->query("UPDATE tb_kriteria SET nama_kriteria='$nama_kriteria', atribut='$atribut', bobot='$bobot' WHERE kode_kriteria='$_GET[ID]'");
        redirect_js("index.php?m=kriteria");
    }
} else if ($act == 'kriteria_hapus') {
    $db->query("DELETE FROM tb_kriteria WHERE kode_kriteria='$_GET[ID]'");
    $db->query("DELETE FROM tb_crips WHERE kode_kriteria='$_GET[ID]'");
    $db->query("DELETE FROM tb_rel_alternatif WHERE kode_kriteria='$_GET[ID]'");
    header("location:index.php?m=kriteria");
}

/** crips */
elseif ($mod == 'crips_tambah') {
    $kode_kriteria = $_POST['kode_kriteria'];
    $nama_crips = $_POST['nama_crips'];
    $nilai = $_POST['nilai'];

    if ($kode_kriteria == '' || $nama_crips == '' || $nilai == '')
        print_msg("Field bertanda * tidak boleh kosong!");
    else {
        $db->query("INSERT INTO tb_crips (kode_kriteria, nama_crips, nilai) VALUES ('$kode_kriteria', '$nama_crips', '$nilai')");
        redirect_js("index.php?m=crips&kode_kriteria");
    }
} else if ($mod == 'crips_ubah') {
    $kode_kriteria = $_POST['kode_kriteria'];
    $nama_crips = $_POST['nama_crips'];
    $nilai = $_POST['nilai'];

    if ($kode_kriteria == '' || $nama_crips == '' || $nilai == '')
        print_msg("Field bertanda * tidak boleh kosong!");
    else {
        $db->query("UPDATE tb_crips SET kode_kriteria='$kode_kriteria', nama_crips='$nama_crips', nilai='$nilai' WHERE kode_crips='$_GET[ID]'");
        redirect_js("index.php?m=crips&kode_kriteria");
    }
} else if ($act == 'crips_hapus') {
    $db->query("DELETE FROM tb_crips WHERE kode_crips='$_GET[ID]'");
    header("location:index.php?m=crips&kode_kriteria");
}

/** RELASI ALTERNATIF */
else if ($act == 'rel_alternatif_ubah') {
    foreach ($_POST['kode_crips'] as $key => $val) {
        $db->query("UPDATE tb_rel_alternatif SET kode_crips='$val' WHERE ID='$key'");
    }
    header("location:index.php?m=rel_alternatif");
}
