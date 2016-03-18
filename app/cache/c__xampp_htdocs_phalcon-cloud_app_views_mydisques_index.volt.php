<p>
    Debug : <br/>
    Id User : <?php echo $dIdUser; ?> <br/>
</p>

<h2>Mes disques -> <?php echo $loginUser; ?></h2>
<?php foreach ($disques as $disque) { ?>
    <div class="well well-sm">
        <h3>
            <span class="glyphicon glyphicon-hdd"></span> <?php echo $disque->nom; ?>
            <span class="badge"><?php echo $infosDisques[$disque->id]['occupation']; ?> <?php echo $infosDisques[$disque->id]['uniteOccupation']; ?>/
                <?php echo $infosDisques[$disque->id]['tailleMax']; ?> <?php echo $infosDisques[$disque->id]['uniteTailleMax']; ?></span>
        </h3>
        <?php echo $q['barreOccupation' . $disque->id]; ?>
        <?php echo $q['boutonOuverture' . $disque->id]; ?>
    </div>
<?php } ?>

<?php echo $script_foot; ?>