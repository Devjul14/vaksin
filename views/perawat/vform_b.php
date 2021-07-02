<?php
$t1 = new DateTime('today');
$t2 = new DateTime($q->tgl_lahir);
$y  = $t1->diff($t2)->y;
$m  = $t1->diff($t2)->m;
$d  = $t1->diff($t2)->d;
$umur = $y." tahun ".$m." bulan ".$d." hari";
?>
<script>
$(document).ready(function() {
  $("[name='id_petugas']").select2({
    placeholder: "Pilih petugas"
  });
  var formattgl = "dd-mm-yy";
  $("[name='tgl']").datepicker({
    dateFormat: formattgl,
  });
  $('.edit').click(function() {
      var id = $(this).attr("id");
      var no_rm = $("[name='no_rm']").val();
      var no_reg = $("[name='no_reg']").val();
      var url = "<?php echo site_url("perawat/form_b");?>/"+no_rm+"/"+no_reg+"/"+id;
      window.location = url;
  });
  $('.hapus').click(function() {
      var id = $(this).attr("id");
      var no_rm = $("[name='no_rm']").val();
      var no_reg = $("[name='no_reg']").val();
      var url = "<?php echo site_url("perawat/hapusform_b");?>/"+no_rm+"/"+no_reg+"/"+id;
      window.location = url;
  });
  $('.dataChange').click(function(evt) {
      evt.preventDefault();
      var dataText = $(this);
      var kode = dataText.attr('kode');
      var jenis = dataText.attr("jenis");
      var dataContent = dataText.text().trim();
      var dataInputField = $('<input type="text" value="' + dataContent + '" class="form-control '+jenis+'" />');
      if (jenis=='petugas'){
          var petugas = dataText.attr('petugas');
          var result = getpetugas(petugas);
          var dataInputField = $(result);
      }
      if (jenis=="tgl"){
          dataText.before(dataInputField).hide();
          dataInputField.datepicker({
              dateFormat : "dd-mm-yy",
              onSelect: function() {
                  var inputval = $(this).val();
                  changeData(inputval,kode,jenis);
              }
          })
      } else {
          dataText.before(dataInputField).hide();
          dataInputField.focus().blur(function(){
              var inputval = dataInputField.val();
              changeData(inputval,kode,jenis);
              $(this).remove();
              dateText.show();
          }).keyup(function(evt) {
              if (evt.keyCode == 13) {
                  var inputval = dataInputField.val();
                  changeData(inputval,kode,jenis);
                  $(this).remove();
                  dateText.show();
              }
          });
      }
  });
});
var changeData = function(value,id,jenis){
    $.ajax({
        url: "<?php echo site_url('perawat/changedata');?>",
        type: 'POST',
        data: {id: id, value: value, jenis: jenis},
        success: function(){
            location.reload();
        }
    });
};
function getpetugas(val){
    var result = false;
    $.ajax({
        url: "<?php echo site_url('perawat/getpetugas');?>",
        type: 'POST',
        async: false,
        success: function(data){
            console.log(data)
            var html = "<select name='idpetugas' class='form-control'>";
            html += "<option value=''>---Pilih Petugas/Dokter---</option>";
            var dp = JSON.parse(data);
            $.each(dp["dokter"], function(key, value){
              html += "<option value='dokter/" + key + "'>" + value + "</option>";
            });
            $.each(dp["perawat"], function(key, value){
              html += "<option value='perawat/" + key + "'>" + value + "</option>";
            });
            html += "</select>";
            result = html;
        }
    });
    return result;
};
</script>
<?php
  if ($dt->num_rows()>0){
    $row = $dt->row();
    $tgl = date("d-m-Y",strtotime($row->tanggal));
    $jam = date("H:i",strtotime($row->tanggal));
    $id_petugas = $row->id_petugas;
    $items = json_decode($row->item);
    $dat = array();
    foreach ($items as $keys => $values) {
      $dat[$keys] = $values;
    }
    $action = "edit";
  } else {
    $tgl =
    $jam =
    $items =
    $id_petugas = "";
    $dat = array();
    $action = "simpan";
  }
?>
<div class="col-md-12">
  <?php
  if ($this->session->flashdata('message')) {
    $pesan = explode('-', $this->session->flashdata('message'));
    echo "<div class='alert alert-" . $pesan[0] . "' alert-dismissable>
    <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
    <b>" . $pesan[1] . "</b>
    </div>";
  }
  ?>
  <div class="box box-primary">
    <div class="form-horizontal">
      <div class="box-body">
        <?php echo form_open("perawat/simpanform_b/".$action , array("class" => "form-horizontal")); ?>
        <div class="form-group">
          <label class="col-md-2 control-label">No. RM</label>
          <div class="col-md-4">
            <input type="hidden" name="no_reg" value="<?php echo $no_reg ?>">
            <input type="hidden" name="id" value="<?php echo $id ?>">
            <input type="hidden" name="tanggal" value="<?php echo date("d-m-Y", strtotime($tanggal)) ?>">
            <input type="text" class="form-control" name='no_rm' readonly value="<?php echo $q->no_pasien;?>"/>
          </div>
          <label class="col-md-2 control-label">Nama Pasien</label>
          <div class="col-md-4">
            <input type="text" class="form-control" name='nama_pasien' readonly value="<?php echo $q->nama_pasien1;?>"/>
          </div>
        </div>
        <div class="form-group">
          <label class="col-md-2 control-label">Tgl Lahir / Umur</label>
          <div class="col-md-4">
            <input type="text" class="form-control" name='tgl_lahir' readonly value="<?php echo date("d-m-Y",strtotime($q->tgl_lahir)).' / '.$umur;?>"/>
          </div>
          <label class="col-md-2 control-label">Jenis Kelamin</label>
          <div class="col-md-4">
            <input type="text" class="form-control" name='jk' readonly value="<?php echo ($q->jenis_kelamin=="L" ? "Laki-laki" : "Perempuan");?>"/>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="col-md-12">
  <div class="box box-primary">
    <div class="box-header with-border" align="center"><b><h2 class="box-title">FORM-B MANAJER PELAYANAN PASIEN/ CASE MANAGER</h2></b></div>
    <div class="box-body">
      <div class="form-horizontal">
        <div class='form-group'>
          <label class="col-sm-2 control-label">Tanggal/ Jam</label>
          <div class="col-sm-4">
            <div class="row">
              <div class="col-sm-6"><input type="text" required name="tgl" class="form-control" value="<?php echo $tgl;?>" autocomplete="off"></div>
              <div class="col-sm-6"><input type="text" required name="jam" class="form-control" value="<?php echo $jam;?>" placeholder="00:00"></div>
            </div>
          </div>
          <label class="col-sm-2 control-label">Petugas</label>
          <div class="col-sm-4">
            <select name="id_petugas" class="form-control" style="width: 100%">
              <?php
              echo "<option value=''></option>";
              foreach ($dp["dokter"] as $key => $value) {
                echo "<option value='dokter/" . $key . "' ".($id_petugas==$key ? "selected" : "").">" . $value . "</option>";
              }
              foreach ($dp["perawat"] as $key => $value) {
                echo "<option value='perawat/" . $key . "' ".($id_petugas==$key ? "selected" : "").">" . $value . "</option>";
              }
              ?>
            </select>
          </div>
        </div>
        <?php
        foreach ($item->result() as $row) {
          echo "<div class='form-group'>";
          echo "<label class='col-sm-4 control-label'>".$row->kode.". ".$row->keterangan."</label>";
          echo "<div class='col-sm-8'><textarea type='text' name='".$row->kode."' class='form-control'>".$dat[$row->kode]."</textarea></div>";
          echo "</div>";
        }
        ?>
      </div>
    </div>
    <div class="box-footer">
      <div class="row">
        <div class="col-xs-12">
          <div class="pull-right">
            <div class="btn-group">
              <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Simpan</button>
            </div>
          </div>
          <?php echo form_close(); ?>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="col-md-12">
  <div class="box box-primary">
    <div class="box-body">
      <table class="table table-bordered table-hover " id="myTable">
        <thead>
          <tr>
            <th width=130px>Tanggal</th>
            <th width=150px>Jam</th>
            <th>Item</th>
            <th>Kegiatan Manajer  Pelayanan Pasien</th>
            <th width=250px>Nama</th>
            <!-- <th>Action</th> -->
          </tr>
        </thead>
        <tbody>
          <?php
            $id = "";
            foreach ($c->result() as $value){
              $item = json_decode($value->item);
              foreach ($item as $key => $val) {
                echo "<tr>";
                echo "<td>".($value->id!=$id ? date("d-m-Y",strtotime($value->tanggal)) : "")."</td>";
                echo "<td>".($value->id!=$id ? date("H:i",strtotime($value->tanggal))."&nbsp;&nbsp;<button type='button' class='btn btn-xs btn-warning edit' id='".$value->id."'><i class='fa fa-edit'></i></button>&nbsp;&nbsp;<button type='button' class='btn btn-xs btn-danger hapus' id='".$value->id."'><i class='fa fa-minus'></i></button>" : "")."</td>";
                echo "<td>".$key."</td>";
                echo "<td>".$val."</td>";
                echo "<td>".($value->id!=$id ? $dp[$value->jenis][$value->id_petugas] : "")."</td>";
                echo "</tr>";
                $id = $value->id;
              }
            }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<style>
  .select2-container--default .select2-selection--single .select2-selection__rendered {
    margin-top: -15px;
  }

  .select2-container--default .select2-selection--single {
    padding: 16px 0px;
    border-color: #d2d6de;
  }

  .select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: #3c8dbc;
  }

  .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    color: #f4f4f4;
  }
</style>
