<?php
//include database connection file
include('bd.php');
 //Ce programme s'occupe du rafraichissement de notre page web , Il est charger de retourner les cinquantes derniers enregistrements de la table contenant les informations sur les paquets
?>
<html>
<head>
<title>Refresh</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/
libs/jquery/1.3.0/jquery.min.js"></script>
<script type="text/javascript">
var auto_refresh = setInterval(
function ()
{$('#fifty').load('refresh.php').fadeIn("slow");}, 5000); 

</script>


<body>


<hr/>


<div class="container" id="fifty">

<table class="table table-striped table-bordered">
<thead>
<tr>
<th style='width:50px;'>Identifiant</th>
<th style='width:150px;'>IP Source</th>
<th style='width:150px;'>IP Dest</th>
<th style='width:150px;'>MAC Source</th>

<th style='width:150px;'>MAC Dest</th>
<th style='width:50px;'>PORT Source</th>
<th style='width:50px;'>PORT Dest</th>
<th style='width:150px;'>URL</th>
</tr>
</thead>
<tbody>
<?php
 
$demande= "SELECT id, ip_src, ip_dst, mac_src, mac_dst, port_src, port_dst, url FROM (
   SELECT id, ip_src, ip_dst, mac_src, mac_dst, port_src, port_dst, url FROM paquets ORDER BY id DESC LIMIT 50
)var
   ORDER BY id ASC;";
$result = mysqli_query($conn,$demande);
    while($row = mysqli_fetch_array($result)){
		echo "<tr>
			  <td>".$row['id']."</td>
			  <td>".$row['ip_src']."</td>
	 		  <td>".$row['ip_dst']."</td>
		   	  <td>".$row['mac_src']."</td>
		   	  
		   	  
		   	  
		   	  <td>".$row['mac_dst']."</td>
			  <td>".$row['port_src']."</td>
	 		  <td>".$row['port_dst']."</td>
		   	  <td>".$row['url']."</td>
		   	  </tr>";
		   	  
		   	  
        }
	mysqli_close($conn);

    ?>
</tbody>
</table>
<div class="container">
<button type="button" class="btn btn-outline-success btn-block">
<a href="main.php" class="btn btn-success btn-lg active" role="button" aria-pressed="true">Retour à la Page d'accueil</a>
</button>

</div> 

</body>
</html>
