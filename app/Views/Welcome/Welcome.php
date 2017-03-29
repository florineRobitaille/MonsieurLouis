<div class="page-header">
    <h1><?= $title; ?></h1>
</div>
<?php

if($playlists != false) {
    echo "<div id='playlists'>";
    include('Playlist.php');
    echo "</div>";
    }

foreach($all as $c) {
    
//echo $c-> utilisateur->username; die(1);
    echo $c->nom." uploadé par".$c->utilisateur->username."<a href='#' class='listen' data-file='$c->fichier'>Listen</a>"."<br />";
}
?>


<script>
    $(document).ready(function(){
        $('a.listen').on('click', function(e){
            e.preventDefault();
            var audio = $("#player");
            var file = $(this).attr('data-file');
            audio[0].src = file;
            audio[0].play();
        });
    });
</script>





