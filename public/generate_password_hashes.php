<?php
$admin_password = password_hash('Adminpass123456?', PASSWORD_BCRYPT);
$employe_password = password_hash('Employeepass123?', PASSWORD_BCRYPT);

echo "Admin password hash: " . $admin_password . "<br>";
echo "Employe password hash: " . $employe_password . "<br>";
?>



