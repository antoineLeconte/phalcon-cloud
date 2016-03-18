<p>
    Debug : <br/>
    Id User : <?php echo $dIdUser; ?>
</p>

<h2>Mes disques -> <?php echo $loginUser; ?></h2>
<?php foreach ($disques as $disque) { ?>
    <div class="well well-sm">
        <h3><span class="glyphicon glyphicon-hdd"></span> <?php echo $disque->nom; ?></h3>
        <span><?php echo $occupationDisque[$disque->id]->occupation; ?> / <?php echo $infosDisques[$disque->id]->quota; ?> <?php echo $infosDisques[$disque->id]->unite; ?></span>

    </div>
<?php } ?>