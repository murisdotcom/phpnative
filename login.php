<?php
require "config.php";

session_start();

// cek cookie
if (isset($_SESSION["USER_ID"]) && isset($_SESSION["key"])) {
  $USER_ID = $_SESSION["USER_ID"];
  $key = $_SESSION["key"];

  // ambil username berdasarkan id
  $result = mssql_query("SELECT_ADM_USER_BY_USER_ID @USER_ID=$USER_ID");
  $row = mssql_fetch_assoc($result);

  // cek cookie dan username(Description)
  if ($key === has('sha256', $row["DESCRIPTION"])) {
    $_SESSION['login'] = true;
  }
}

if (isset($_SESSION["login"])) {
  header('Location:index.php');
  exit;
}

// Ambil post yang di input user
$USERNAME = htmlspecialchars($_POST["USERNAME"]);
$PWD = htmlspecialchars($_POST["PWD"]);

// cek jika username/pwd tidak di isi
// if (empty($USER_ID) && ($PWD)) {
//   header('Location:index.php?error=Silahkan isi User Id & Password!');
// } elseif (empty($USER_ID)) {
//   header('Location:index.php?error=User Id tidak boleh kosong!');
// } elseif (empty($PWD)) {
//   header('Location:index.php?error=Password tidak boleh kosong!');
// }

$sql = mssql_query("SP_001_LOGIN_ADM_USERS @USERNAME=$USERNAME");

if (mssql_num_rows($sql) === 1) {

  // Cek password
  $row = mssql_fetch_assoc($sql);
  if (password_verify($PWD, $row["PWD"])) {

    // set session
    $_SESSION["login"] = true;
    $_SESSION["USERNAME"] = $USERNAME;
    $_SESSION["SECTION"] = $row["SECTION"];

    // cek section
    if ($_SESSION["SECTION"] == "MINA1-IT") {
      header('Location:mits/index.php');
    } else {
      header('Location:mits/index.php');
    }

    // Cek remember me
    if (isset($_POST['remember'])) {


      // Buat cookie untuk user id & description(username) kita ubah usernamenya jadi 'key' supaya tdk mudak ditebak
      setcookie("USER_ID", $row["USER_ID"], time() + 60);
      setcookie('key', hash('sha256', $row['DESCRIPTION'], time() + 60));
    }
  }
} else {
  $error = true;
}
