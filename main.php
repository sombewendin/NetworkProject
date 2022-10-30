<?php
//include database connection file
include('bd.php');
// Ce programme est celui qui permet d'afficher la page d'accueil contenant les données enregistrées au niveau du serveur
// L'affichage obeit à un systeme de pagination(Cinquante enregistrements par page )

?>
<html>
<head>
<title>mainPage</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/
libs/jquery/1.3.0/jquery.min.js"></script>
<script type="text/javascript">
	var auto_refresh = setInterval(
	function ()
	{$('#refresh').load('autorefresh.php').fadeIn("slow");}, 5000); 

</script>

<body>
<div class="container" id="ref">

<button type="button" class="btn btn-outline-success btn-lg btn-block"> 

<a href="refresh.php"  class="btn btn-outline-success  btn-lg active" role="button" aria-pressed="true">Rafraichir la Page</a>

</button>
<form action="fichier.php" method="POST">

<p><h3>Faire la requête  selon :</h3></p> 
ID :<input type="text" placeholder="Identifiant" name="id"/>
Ip Srce :<input type="text" placeholder="IP source" name="ips"/>
Ip Dst :<input type="text" placeholder="IP Destination" name="ipD" minlenghth="7" maxlenghth="15"/>
 Mac Srce :<input type="text" placeholder="MAC source"  name="MacS" /><br/>
Mac Dst :<input type="text" placeholder="MAC Destination" name="MacD"/>
Port Srce :<input type="text" placeholder="Port source"  name="PS"/>
Port Dst :<input type="text" placeholder="Port Destination" name="PD" />
URL :<input type="text"  placeholder="URL"name="url"/> <br/>
<input type="submit" value="Envoyer la Requete">

</form>
</div>
  
<hr/>
<div class="container" id="refresh">
 
<h3>Informations enregistrées sur les paquets</h3>
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
//section affichage des données
if (isset($_GET['page_no']) && $_GET['page_no']!="") {
	$page_no = $_GET['page_no'];
	} else {
		$page_no = 1;
        }
 
	$total_records_per_page = 25;
    $offset = ($page_no-1) * $total_records_per_page;
	$previous_page = $page_no - 1;
	$next_page = $page_no + 1;
	$adjacents = "2"; 
 
	$result_count = mysqli_query($conn,"SELECT COUNT(*) As total_records FROM paquets ");
	$total_records = mysqli_fetch_array($result_count);
	$total_records = $total_records['total_records'];
    $total_no_of_pages = ceil($total_records / $total_records_per_page);
	$second_last = $total_no_of_pages - 1; // total page minus 1
 
    $result = mysqli_query($conn,"SELECT id, ip_src, ip_dst, mac_src, mac_dst, port_src, port_dst, url FROM paquets LIMIT $offset, $total_records_per_page");
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
<strong>Page <?php echo $page_no." of ".$total_no_of_pages; ?></strong>
</div>
 
<ul class="pagination">



 
    
	<li class="page-item" <?php if($page_no <= 1){ echo "class='disabled'"; } ?>>
	<a  class="page-link" <?php if($page_no > 1){ echo "href='?page_no=$previous_page'"; } ?>>Previous</a>
	</li>
       
    <?php 
    //section pagination
	if ($total_no_of_pages <= 20){  	 
		for ($counter = 1; $counter <= $total_no_of_pages; $counter++){
			if ($counter == $page_no) {
		   echo "<li class='active'><a class='page-link'>$counter</a></li>";	
				}else{
           echo "<li><a class='page-link'  href='?page_no=$counter'>$counter</a></li>";
				}
        }
	}
	elseif($total_no_of_pages > 20){
		
	if($page_no <= 8) {			
	 for ($counter = 1; $counter < 16; $counter++){		 
			if ($counter == $page_no) {
		   echo "<li class='active'><a class='page-link'>$counter</a></li>";	
				}else{
           echo "<li><a class='page-link' href='?page_no=$counter'>$counter</a></li>";
				}
        }
		echo "<li><a class='page-link'>...</a></li>";
		echo "<li><a class='page-link' href='?page_no=$second_last'>$second_last</a></li>";
		echo "<li><a class='page-link' href='?page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";
		}
 
	 elseif($page_no > 8 && $page_no < $total_no_of_pages - 8) {		 
		echo "<li><a class='page-link' href='?page_no=1'>1</a></li>";
		echo "<li><a class='page-link' href='?page_no=2'>2</a></li>";
        echo "<li><a class='page-link'>...</a></li>";
        for ($counter = $page_no - $adjacents; $counter <= $page_no + $adjacents; $counter++) {			
           if ($counter == $page_no) {
		   echo "<li class='active'><a class='page-link'>$counter</a></li> <br/>";	
				}else{
           echo "<li><a class='page-link' href='?page_no=$counter'>$counter</a></li>";
				}                  
       }
       echo "<li><a class='page-link'>...</a></li>";
	   echo "<li><a class='page-link' href='?page_no=$second_last'>$second_last</a></li>";
	   echo "<li><a class='page-link' href='?page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";      
            }
		
		else {
        echo "<li><a class='page-link' href='?page_no=1'>1</a></li>";
		echo "<li><a class='page-link' href='?page_no=2'>2</a></li>";
        echo "<li><a class='page-link'>...</a></li>";
 
        for ($counter = $total_no_of_pages - 12; $counter <= $total_no_of_pages; $counter++) {
          if ($counter == $page_no) {
		   echo "<li class='active'><a class='page-link'>$counter</a></li>";	
				}else{
           echo "<li><a class='page-link' href='?page_no=$counter'>$counter</a></li>";
				}                   
                }
            }
	}
?>
    
	<li <?php if($page_no >= $total_no_of_pages){ echo "class='disabled'"; } ?>>
	<a class='page-link' <?php if($page_no < $total_no_of_pages) { echo "href='?page_no=$next_page'"; } ?>>Next</a>
	</li>
    <?php if($page_no < $total_no_of_pages){
		echo "<li><a class='page-link' href='?page_no=$total_no_of_pages'>Last &rsaquo;&rsaquo;</a></li>";
		} ?>

</ul>
 
 
</div>
</body>
</html>
