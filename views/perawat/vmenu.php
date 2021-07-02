<?php echo "<li class='treeview' ".($menu=='perawat' ? 'active' : '').">".anchor("dokter/rawat_inapdokter","<i class='fa fa-user'></i><span>Triage</span>")."</li>";?>
<?php echo "<li class='treeview' ".($menu=='perawat' ? 'active' : '').">".anchor("perawat/pasienigd","<i class='fa fa-user'></i><span>IGD</span>")."</li>";?>
<?php echo "<li class='treeview' ".($menu=='perawat' ? 'active' : '').">".anchor("perawat/pasienralan","<i class='fa fa-user'></i><span>Rawat Jalan</span>")."</li>";?>
<?php echo "<li class='treeview' ".($menu=='perawat' ? 'active' : '').">".anchor("perawat/pasieninap","<i class='fa fa-user'></i><span>Rawat Inap</span>")."</li>";?>
<?php echo "<li class='treeview' ".($menu=='perawat' ? 'active' : '').">".anchor("home/kontrole", "<i class='fa fa-user'></i><span>Kontrole</span>") . "</li>"; ?>
