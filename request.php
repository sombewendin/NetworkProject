<?php
//Fichier de connexion a la base de données
include('bd.php');
 //Ce programme gère les requetes sur les données enregistrées dans notre base de données
 //Ces requetes sont faites en fonction des multiples attributs de la table a savoir l'identifiant , les ports source et destination, les adresses MAC source est destination ainsi que les adresses IP
 //et l'URL éventuellement
?>
<html>
<head>
<title>Query</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
<div class="container">
<button type="button" class="btn btn-outline-success btn-lg btn-block"> 

<a href="refresh.php"  class="btn btn-outline-success  btn-lg active" role="button" aria-pressed="true">Rafraichir la Page</a>

</button>
</div>


<hr/>

<div class="container">

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
//code permettant de verifier le parametre selon lequel on doit faire la requete SQl

		 

	if (isset($_POST["id"])) {

		$id = $_POST["id"];
		$req = "SELECT id, ip_src, ip_dst, mac_src, mac_dst, port_src, port_dst, url FROM paquets WHERE id LIKE '$id%'";	
	
	                         }
	                         
	                         	
	if (isset($_POST["ips"])) {

		$ip_s = $_POST["ips"];
		$req = "SELECT id, ip_src, ip_dst, mac_src, mac_dst, port_src, port_dst, url FROM paquets WHERE id LIKE '$id%' AND ip_src LIKE '%$ip_s%'";	
	
	                          }
	                          
	                          
	if (isset($_POST["ipD"])) {

		$ipD = $_POST["ipD"];
		$req = "SELECT id, ip_src, ip_dst, mac_src, mac_dst, port_src, port_dst, url FROM paquets WHERE id LIKE '$id%' AND ip_src LIKE '%$ip_s%' AND ip_dst LIKE '%$ipD%'";	
	                           }	
	                           
	                           
	                           
	if (isset($_POST["MacS"])) {

		$mac_s = $_POST["MacS"];
		$req = "SELECT id, ip_src, ip_dst, mac_src, mac_dst, port_src, port_dst, url FROM paquets WHERE id LIKE '$id%' AND ip_src LIKE '%$ip_s%' AND ip_dst LIKE '%$ipD%' AND mac_src LIKE '%$mac_s%'";	
	
	                          }	
	                          
	                          
	if (isset($_POST["MacD"])) {

		$mac_d = $_POST["MacD"];
		$req = "SELECT id, ip_src, ip_dst, mac_src, mac_dst, port_src, port_dst, url FROM paquets WHERE id LIKE '$id%' AND ip_src LIKE '%$ip_s%'  AND ip_dst LIKE '%$ipD%' AND mac_src LIKE '%$mac_s%' AND mac_dst LIKE '%$mac_d%' ";
	
	
	                            }
	                            
	                            
	                            
	                        
	if (isset($_POST["PS"])) {

		$ps = $_POST["PS"];
		$req = "SELECT id, ip_src, ip_dst, mac_src, mac_dst, port_src, port_dst, url FROM paquets WHERE id LIKE '$id%' AND ip_src LIKE '%$ip_s%'  AND ip_dst LIKE '%$ipD%' AND mac_src LIKE '%$mac_s%' AND mac_dst LIKE '%$mac_d%'  AND port_src LIKE '%$ps%' ";
	
	                         }
	                         
	                         
	                         	
	if (isset($_POST["PD"])) {

		$pd = $_POST["PD"];
		$req = "SELECT id, ip_src, ip_dst, mac_src, mac_dst, port_src, port_dst, url FROM paquets WHERE id LIKE '$id%' AND ip_src LIKE '%$ip_s%'  AND ip_dst LIKE '%$ipD%' AND mac_src LIKE '%$mac_s%' AND mac_dst LIKE '%$mac_d%'  AND port_src LIKE '%$ps%' AND port_dst LIKE '%$pd%'";
	
	
	                         }
                         
	                       	                         	
	if (isset($_POST["url"])) {

		$url = $_POST["url"];
		$req = "SELECT id, ip_src, ip_dst, mac_src, mac_dst, port_src, port_dst, url FROM paquets WHERE id LIKE '$id%' AND ip_src LIKE '%$ip_s%' AND ip_dst  LIKE '%$ipD%' AND mac_src LIKE '%$mac_s%' AND mac_dst LIKE '%$mac_d%'  AND port_src LIKE '%$ps%' AND port_dst LIKE '%$pd%' AND url LIKE '%$url%' ";
	
	                          }			
?>	
	
<?php	

	//Execution de la requete selon ce qui a ete recupere plus haut
	
	$result = $conn->query($req);
	
	if ($result->num_rows > 0) {
	

		while($row = mysqli_fetch_assoc($result)) {
	  
	  
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
	}
	else {
		echo "Pas de résultat";
	}
	mysqli_close($conn);
	
?>
</tbody>
</table>
</div>
<div class="container">
<button type="button" class="btn btn-outline-success btn-block">
<a href="main.php" class="btn btn-success btn-lg active" role="button" aria-pressed="true">Retour à la Page d'accueil</a>
</button>

</div> 
</body>
</html>

