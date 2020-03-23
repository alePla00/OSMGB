<?php
$config_path = __DIR__;
$util = $config_path .'/../util.php';
require $util;
setup();
?>
<html>
       <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<?php //stampaIntestazione(); ?>
<body>
<?php //stampaNavbar(); ?>
 <?php
 $util2 = $config_path .'/../db/db_conn.php';
 require_once $util2;
?>
<?php stampaIntestazione(); ?>
<?php stampaNavbar(); ?>
<?php
$id_persona=$_POST["id_persona"];

// selezione della casa in cui abita la persona passata in input
$query1 =   "SELECT c.id id_casa,c.nome nome_casa, c.id_moranca id_moranca, m.nome nome_moranca, z.nome nome_zona";
$query1 .=   " FROM casa c ";
$query1 .=  " INNER JOIN morance m  ON  c.id_moranca = m.id ";
$query1 .=  " INNER JOIN pers_casa pc  ON c.id = pc.id_casa ";
$query1 .=  " INNER JOIN persone p  ON  p.id = pc.id_pers ";
$query1 .=  " INNER JOIN zone z  ON  z.cod = m.cod_zona ";
$query1 .=  " WHERE p.id = $id_persona ";

//echo $query1;

$result1 = $conn->query($query1);
$row1 = $result1->fetch_array();
$id_casa= $row1['id_casa'];

$mystr = utf8_encode ($row1['nome_moranca']) ;

echo "<br>ELENCO ABITANTI DELLA CASA: id= $id_casa<br>";
echo "<br>nome= '$row1[nome_casa]'<br>moranca= '$mystr'<br>zona = '$row1[nome_zona]'";

// elenco delle persone che abitano in quella casa
   $query = "SELECT c.id, c.nome,";
   $query .= " z.nome zona, c.id_moranca, m.nome nome_moranca, p.id id_pers, p.nominativo, ";
   $query .= " pc.cod_ruolo_pers_fam, rpf.descrizione desc_ruolo ";
   $query .= " FROM casa c INNER JOIN morance m  ON  c.id_moranca = m.id ";
   $query .= " INNER JOIN zone z  ON  z.cod = m.cod_zona ";
   $query .= " INNER JOIN pers_casa pc  ON  pc.id_casa = c.id ";
   $query .= " INNER JOIN ruolo_pers_fam rpf   ON  pc.cod_ruolo_pers_fam = rpf.cod ";
   $query .= " INNER JOIN persone p  ON  pc.id_pers = p.id ";
   $query .= " AND c.id = $id_casa";
 
   $result = $conn->query($query);
   
   //echo $query;

   
	if ($result->num_rows !=0)
	{     
		echo "<table border>";
		echo "<tr>";
		echo "<th>id persona</th>";
		echo "<th>nominativo</th>";
		echo "<th>ruolo</th>";
		echo "</tr>";
        $cnt=0;
	    while ($row = $result->fetch_array())
		 {
		  echo "<tr>";
		  echo "<td>$row[id_pers]</th>";
		  $mystr = utf8_encode ($row['nominativo']) ;
		  echo "<td>$mystr</th>";
          echo "<td>$row[cod_ruolo_pers_fam]- $row[desc_ruolo]</th>";
	      echo "</tr>";                    
		 }
		 echo "</table>";
	}
	else
		echo " Nessuna casa &egrave; presente nel database.";

  $result->free();
  $conn->close();	
 ?>
 <br>  

 
 </body>
</html>