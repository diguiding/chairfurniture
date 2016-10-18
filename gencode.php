<?php 

error_reporting(0);

switch (intval($_GET['id']))
{
	case 1:
		$sql = "SELECT DISTINCT CONCAT(pl.name, ' ', pal.name) as name, pa.reference, pa.ean13
				FROM `ps_product_attribute` pa
				LEFT JOIN ps_product_attribute_combination pac
				ON pac.id_product_attribute = pa.id_product_attribute
				LEFT JOIN `ps_attribute_lang` AS pal
				ON pac.id_attribute = pal.id_attribute AND pal.id_lang = 2
				LEFT JOIN ps_product p
				ON pa.id_product = p.id_product
				LEFT JOIN ps_product_lang pl
				ON p.id_product = pl.id_product
				WHERE p.active = 1 AND pl.id_lang = 2 AND id_shop = 2
				ORDER BY `pa`.`ean13` ASC
				";
	break;
	case 2:
		$sql = "SELECT DISTINCT pl.name, p.reference, p.ean13
				FROM `ps_product` p
				LEFT JOIN ps_product_lang pl
				ON p.id_product = pl.id_product
				WHERE p.active = 1 AND id_lang = 2 AND id_shop = 2
				ORDER BY `p`.`ean13` ASC
				";
		
	break;
	default:
		$access = false;
}

if (empty($sql)) {
	echo 'NOT ALLOWED!';
	die;
}

require_once('config/settings.inc.php');

$db = mysql_connect(_DB_SERVER_, _DB_USER_, _DB_PASSWD_);
mysql_select_db(_DB_NAME_,$db);

// on envoie la requête
$req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());

?>
<table>
	<tr>
		<th>name</th>
		<th>reference</th>
		<th>ean13</th>
	</tr>
<?php 

// on fait une boucle qui va faire un tour pour chaque enregistrement
while($data = mysql_fetch_assoc($req))
{
	// on affiche les informations de l'enregistrement en cours
	echo '<tr>';
	echo '<td>'.$data['name'].'</td>';
	echo '<td>'.$data['reference'].'</td>';
	echo '<td>'.$data['ean13'].'</td>';
	echo '</td></tr>';
}

// on ferme la connexion à mysql
mysql_close();
?>
</table>