<?php
	foreach($playlists as $p) 
		echo "$p->nom<br />";
?>
	<form id='formpl' method='post'  action="/playlist/cree">
    	<label>Nom</label><input type='text' id='plnom'  name='nom' placeholder="le titre" /><br />
    	<input type="submit" />
    </form>



<script type="text/javascript">

	$(document).ready(function() {
	$("#formpl").submit(function(e) { // le e permet de dire qu'on intercepte l'action
		e.preventDefault();
		$.ajax({
			type: "POST", // Le type de ma requete
			  url: "/playlist/cree", // L url vers laquelle la requete sera envoyee
			data: {
    				nom: $('#plnom').val(), // Les donnees à envoyer
  			}, 
			success: function(data, textStatus, jqXHR) {
				$("#playlists").html(data);
  			},
			error: function(jqXHR, textStatus, errorThrown) {
    			// Une erreur sest produite lors de la requete
			}
		});
		return false;
	});
});
</script>