<?php
date_default_timezone_set('Asia/Jakarta');
// $timeout = 5 * 60 * 60;
// Set maxlifetime of the session

// lni_set("session.gc_maxlifetime" . $timeout);
// lni_set("session.cookie_lifetime" . $timeout);

$conn = sqlsrv_connect("MURISDOTCOM\SQLEXPRESS01", "sa", "1234567890");
sqlsrv_select_db("phpnative", $conn);


if ($conn) {
  echo "DB  Connect";
}


function register($data)
{
  global $conn;

  $USER_ID = strtolower(stripslashes($data["USER_ID"]));
  $PWD = mssql_real_escape_string($conn, $data["PWD"]);
  $PWD2 = mssql_real_escape_string($conn, $data["PWD2"]);

  // Cek Konfirmasi password
  if ($PWD != $PWD2) {
    echo
      "<script>
            alert('Konfirmasi password tidak sesuai!');
      </script>";
    return false;
  }
  // enkripsi password/ mengamankan password
  $PWD = password_hash($PWD, PASSWORD_DEFAULT);
  var_dump($PWD);
  die;
  // Tambahkan userbaru ke database
  // mssql_query($conn,)
}
