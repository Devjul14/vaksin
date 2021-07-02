<li class="treeview <?php echo ($menu=='dokter' ? 'active' : '');?>">
    <a href="#">
        <i class="fa fa-user"></i>
        <span>Dokter</span>
        <i class='fa fa-angle-left pull-right'></i>
    </a>
    <ul class="treeview-menu">
        <li>
            <?php echo "<li>".anchor("dokter/pasienigd","IGD")."</li>";?>
        </li>
        <?php echo "<li>".anchor("dokter/rawat_jalandokter","Rawat Jalan")."</li>";?>
        <?php echo "<li>".anchor("dokter/rawat_inapdokter_ranap","Rawat Inap")."</li>";?>
    </ul>
</li>