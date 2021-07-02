<?php if ($this->session->userdata("idstatus") == 32) : ?>
    <li class="<?php echo ($menu == 'user' ? 'active' : ''); ?>">
        <?php echo anchor("pendaftaran", "<i class='fa fa-user-plus'></i><span class='nav-label'>Daftar Pasien Baru</span>"); ?>
    </li>
    <li class="<?php echo ($menu == 'ralan' ? 'active' : ''); ?>">
        <?php echo anchor("pendaftaran/rawat_jalan", "<i class='fa fa-ambulance'></i><span class='nav-label'>Rawat Jalan</span>"); ?>
    </li>
<?php endif ?>
