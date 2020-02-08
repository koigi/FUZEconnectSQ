<?php session_start();?>

<script> var currentMonth= <?php echo date("m");?>;
    var currentYear = <?php echo date("y");?>;
        
    </script>
    
    <?php
    var_dump($_SESSION);
    
    echo rawurlencode("emily-kyle"); echo "<br/>";
    echo rawurlencode("emily&kyle"); echo "<br/>";
    echo urlencode("emily&kyle");
    
    ?>

    

    
   