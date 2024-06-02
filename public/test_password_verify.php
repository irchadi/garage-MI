<?php
$hashed_password_admin = '$2y$10$mZt1Yey80E3I79fTv6rPr.C/lO1hdayL/qrVnCxLqZXSaz/1mOZF2'; // Hachage pour Adminpass123456?
$admin_password = 'Adminpass123456?';

if (password_verify($admin_password, $hashed_password_admin)) {
    echo "Admin password verification successful.<br>";
} else {
    echo "Admin password verification failed.<br>";
}

$hashed_password_employe = '$2y$10$41gDHM5XjFzl.Cfbd90f3OyzcWB7KwKIFXV4R59jLS1luLtWsKwya'; // Hachage pour Employeepass123?
$employe_password = 'Employeepass123?';

if (password_verify($employe_password, $hashed_password_employe)) {
    echo "Employe password verification successful.<br>";
} else {
    echo "Employe password verification failed.<br>";
}
?>



