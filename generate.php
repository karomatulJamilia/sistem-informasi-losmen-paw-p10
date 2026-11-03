<?php
//membuat hash melalui PHP
echo "Hash Admin". password_hash('admin123', PASSWORD_DEFAULT) ."<br>";

// Generate hash untuk manajer (misal passwordnya: manajer123)
echo "Hash Manajer: " . password_hash('manajer123', PASSWORD_DEFAULT) . "<br>";

// Generate hash untuk resepsionis (misal passwordnya: resepsionis123)
echo "Hash Resepsionis: " . password_hash('resepsionis123', PASSWORD_DEFAULT);