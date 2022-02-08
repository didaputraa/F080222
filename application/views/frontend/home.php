<?php 
$sisa = 0;
$dataMonth = [];

foreach($period as $row)
{
	if($row['period_id'] == @$_GET['n'])
	{
		$dataMonth = $this->Bulan_model->getName($row['period_start'], $row['period_end']);
	}
}
$dataYear = date('Y', strtotime('now'));
?>
<section class="content">
  <div class="row"> 
    <div class="col-md-12">
      <div class="box box-info box-solid" style="border: 1px solid #FF0000 !important;">
        <div class="user-panel">
        <div class="pull-left image">
          <?php if ($this->session->userdata('student_img') != null) { ?>
          <img src="<?php echo upload_url().'/student/'.$this->session->userdata('student_img'); ?>" class="img-responsive">
          <?php } else { ?>
          <img src="<?php echo media_url() ?>img/avatar.png" class="img-responsive">
          <?php } ?>
        </div>
        <div class="pull-left info">
          <p><?php echo ucfirst($this->session->userdata('ufullname_student')); ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      
        <div class="box-header backg with-border">
          <h3 class="box-title">Cek Data Pembayaran Siswa</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          <?php echo form_open(current_url(), array('class' => 'form-horizontal', 'method' => 'get')) ?>
          <div class="form-group">            
            <label for="" class="col-sm-2 control-label">Tahun Ajaran</label>
            <div class="col-sm-2">
              <select class="form-control" name="n">
                <!-- <option value="">-- Tahun Ajaran --</option> -->
                <?php foreach ($period as $row): ?>
                  <option <?php echo (isset($f['n']) AND $f['n'] == $row['period_id']) ? 'selected' : '' ?> value="<?php echo $row['period_id'] ?>"><?php echo $row['period_start'].'/'.$row['period_end'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <label for="" class="col-sm-2 control-label">Cari Siswa</label>
            <div class="col-sm-2">
              <input type="text" autofocus name="r" <?php echo (isset($f['r'])) ? 'placeholder="'.$f['r'].'"' : 'placeholder="NIS Siswa"' ?> value="<?= $f['r'] ?>" class="form-control" required>
            </div>

            <div class="col-sm-4">
              <button type="submit" class="btn btn-success"><i class="fa fa-search"> </i> Cari Siswa</button>
            </div>
          </div>
        </form>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php if ($f) { ?>

    <div class="row">
      <div class="col-md-6">
        <div class="box box-info box-solid" style="border: 1px solid #FF0000 !important;">
          <div class="box-header backg with-border">
            <h3 class="box-title">Informasi Siswa</h3>
          </div><!-- /.box-header -->
          <div class="box-body">
            <table class="table table-striped">
              <tbody>
                <tr>
                  <td width="200">Tahun Ajaran</td><td width="4">:</td>
                  <?php foreach ($period as $row): ?>
                    <?php echo (isset($f['n']) AND $f['n'] == $row['period_id']) ? 
                    '<td><strong>'.$row['period_start'].'/'.$row['period_end'] .'<strong></td>' : '' ?> 
                    <?php endforeach; ?>
                  </tr>
                  <tr>
                    <td>NIS</td>
                    <td>:</td>
                    <?php foreach ($siswa as $row): ?>
                      <?php echo (isset($f['n']) AND $f['r'] == $row['student_nis']) ? 
                      '<td>'.$row['student_nis'].'</td>' : '' ?> 
                    <?php endforeach; ?>
                  </tr>
                  <tr>
                    <td>Nama Siswa</td>
                    <td>:</td>
                    <?php foreach ($siswa as $row): ?>
                      <?php echo (isset($f['n']) AND $f['r'] == $row['student_nis']) ? 
                      '<td>'.$row['student_full_name'].'</td>' : '' ?> 
                    <?php endforeach; ?>
                  </tr>
                  <tr>
                    <td>Nama Ibu Kandung</td>
                    <td>:</td>
                    <?php foreach ($siswa as $row): ?>
                      <?php echo (isset($f['n']) AND $f['r'] == $row['student_nis']) ?  
                      '<td>'.$row['student_name_of_mother'].'</td>' : '' ?> 
                    <?php endforeach; ?>
                  </tr>
                  <tr>
                    <td>Kelas</td>
                    <td>:</td>
                    <?php foreach ($siswa as $row): ?>
                      <?php echo (isset($f['n']) AND $f['r'] == $row['student_nis']) ? 
                      '<td>'.$row['class_name'].'</td>' : '' ?> 
                    <?php endforeach; ?>
                  </tr>
                  <?php if (majors()=='senior') { ?>
                  <tr>
                    <td>Program Keahlian</td>
                    <td>:</td>
                    <?php foreach ($siswa as $row): ?>
                      <?php echo (isset($f['n']) AND $f['r'] == $row['student_nis']) ? 
                      '<td>'.$row['majors_name'].'</td>' : '' ?> 
                    <?php endforeach; ?>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <!-- List Tagihan Bulanan --> 
          <div class="box box-info box-solid" style="border: 1px solid #FF0000 !important;">
            <div class="box-header backg with-border">
              <h3 class="box-title">Tagihan Bulanan</h3>
            </div><!-- /.box-header -->
            <div class="box-body table-responsive">
              <table class="table table-striped table-hover" style="cursor: pointer;">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>Jenis Pembayaran</th>
                    <th>Total Tagihan</th>
                    <th>Sudah Dibayar</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $i =1;
                  foreach ($student as $row):
                    $namePay = $row['pos_name'].' - T.A '.$row['period_start'].'/'.$row['period_end'];
                    if (isset($f['n']) AND $f['r'] == $row['student_nis']) {
                      ?>
                      <tr data-toggle="collapse" data-target="#demo" style="color:<?php echo ($total == $pay) ? '#00E640' : 'red' ?>">
                        <td><?= $i ?></td>
                        <td><?= $namePay ?></td>
                        <td><?= 'Rp. ' . number_format($total - $pay, 0, ',', '.') ?></td>
                        <td><?= 'Rp. ' . number_format($pay, 0, ',', '.') ?></td>
                        <td>
							<label class="label <?= ($total == $pay) ? 'label-success' : 'label-warning' ?>">
								<?php echo ($total == $pay) ? 'Lengkap' : 'Belum Lengkap' ?>
							</label>
						</td>

                      <?php 
                    }
                    $i++;
                  endforeach; 
                  ?> 
                  </tbody>
                  <tbody id="demo" class="collapse">
                  <tr>
                    <th>No.</th> 
                    <th>Bulan</th>
                    <th>Tahun</th>
                    <th>Tagihan</th>
                    <th style="text-align: center;">Status</th>
                  </tr>  
                  <?php 
                  $i =1;
                  foreach ($bulan as $row): 
                    $mont = ($row['month_month_id']<=6) ? $row['period_start'] : $row['period_end'];
                    ?>
                  <tr style="color:<?php echo ($row['bulan_status'] == 1) ? '#00E640' : 'red' ?>">              
                    <td><?php echo $i; ?></td>
                    <td><?php echo $row['month_name'] ?></td>
                    <td><?php echo $mont ?></td>
                    <td>
						<?php 
						echo 'Rp. ' . number_format($row['bulan_bill'], 0, ',', '.') 
						?>
					</td>
                    <td colspan="2" style="text-align: center;">
					<?php 
					if($row['bulan_status'] == 1){
						$sql = $this->db->query("SELECT * FROM `transaksi_midtrans` where 
												id_siswa={$siswa[0]['student_id']} && 
												typeBayar='bulan' && 
												target='{$row['bulan_id']}' 
												limit 1");
						if($sql->num_rows() > 0) 
						{
							foreach($sql->result() as $dd)
							{
								if($dd->statusBayar == 0) {
									
									try {
										
										$cek = $this->midtrans->status($dd->order_id);
										
										if($cek->transaction_status === 'settlement') {
											
											$this->db->where('order_id', $dd->order_id)
												 ->where("id_siswa", $siswa[0]['student_id'])
												 ->where('typeBayar', 'bulan')
												 ->where('target', $row['bulan_id'])
												 ->update('transaksi_midtrans', ['statusBayar' => 1]);
												 
											echo 'Lunas';
											
										} else {
											echo "<font color='orange'>proses</font>";
										}
										
									} catch(\Exception $e) {
										
										echo "<font color='red'>error</font>";
									}
									
								} else { echo 'Lunas'; }
							}
						}else{
							echo "<font color='red'>gagal</font>";
						}
					}
					else
					{
						$url = site_url('manage/payout/pay/'.$row['payment_payment_id'].'/'.$row['student_student_id'].'/'.$row['bulan_id']);
						
						if(isset($dataMonth[$mont]))
						{
							if(in_array(strtolower($row['month_name']), $dataMonth[$mont]))
							{
								if($row['bulan_bill'] > 0){
								echo "<a onClick=\"return confirm('Lakukan pembayaran bulan {$row['month_name']}?')\" href='{$url}/?grossamount={$row['bulan_bill']}'>Bayar</a>";
								}
							}
						}
					}
					?>
					</td>
                  </tr>
                  <?php
                  $i++;
                endforeach;
                ?>      
              </tbody>
            </table>
          </div>
        </div>
        <div class="box box-info box-solid" style="border: 1px solid #FF0000 !important;">
          <div class="box-header backg with-border">
            <h3 class="box-title">Tagihan Lainnya</h3>
          </div><!-- /.box-header -->
          <div class="box-body table-responsive">
            <table class="table table-striped table-hover">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Jenis Pembayaran</th>
                  <th>Total Tagihan</th>
                  <th>Dibayar</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $i   = 1;
				$sisa= 0;
                foreach ($bebas as $row):
                  if (isset($f['n']) AND $f['r'] == $row['student_nis']) {
                    $sisa = $row['bebas_bill']-$row['bebas_total_pay'];
                    $namePay = $row['pos_name'].' - T.A '.$row['period_start'].'/'.$row['period_end'];
                    ?>
                    <tr style="color:<?php echo ($row['bebas_bill'] == $row['bebas_total_pay']) ? '#00E640' : 'red' ?>">
                      <td><?php echo $i ?></td>
                      <td><?php echo $namePay ?></td>
                      <td><?php echo 'Rp. ' . number_format($sisa, 0, ',', '.') ?></td>
                      <td><?php echo 'Rp. ' . number_format($row['bebas_total_pay'], 0, ',', '.') ?></td>
                      <td>
						<?php
						$idSiswa = $siswa[0]['student_id'];
						$sql = $this->db->query("SELECT * FROM `transaksi_midtrans` where 
												id_siswa={$idSiswa} && 
												typeBayar='bebas' limit 1");
												
						$status = 'Belum lunas';
						$classNm= 'label-warning';
						
						foreach($sql->result() as $rec)
						{
							if($rec->statusBayar == 0)
							{
								try{
									
									$cek = $this->midtrans->status($rec->order_id);
									
									if($cek->transaction_status === 'settlement') {
										
										$status = 'Lunas';
										$classNm= 'label-success';
										
										$this->db->where('order_id', $rec->order_id)
											 ->where("id_siswa", $idSiswa)
											 ->where('typeBayar', 'bebas')
											 ->update('transaksi_midtrans', ['statusBayar' => 1]);
									}
									
								}catch(\Exception $e) {
									
								}
								
							} else {
								$status = 'Lunas';
								$classNm= 'label-success';
							}
						}
						?>
						<label class="label <?= $classNm ?>"><?= $status ?></label>
					</td>
					<?php
						if($row['bebas_bill']!=$row['bebas_total_pay']){
							echo "<td>
									<button onClick='payBtn({$sisa})' class='btn btn-primary text-white'>Bayar</button>
								 </td>";
						}
					?>
                    </tr>
                    <?php 
                  }
                  $i++;
                endforeach; 
                ?>        
              </tbody>
            </table> 
          </div>
          <?php } ?>
        </div>
      </div>
    </div>
  </section><br><br>
<form id="payment-form" method="post" action="<?= site_url() ?>/snap/finish">
	<input type="hidden" name="result_data" id="result-data" />
	<input type="hidden" name="student_student_id" value="<?= $siswa_id ?>" />
	<input type="hidden" name="bebas_pay_bill" value="<?= $sisa ?>" />
</form>
<script type="text/javascript"
        src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="SB-Mid-client-VG5GkE3IA4hd0M05"></script>
<script>
function toHistoryPay(data)
{
	$("#result-data").val(JSON.stringify(data));
	
	console.log(data);
	
	setTimeout(function(){
		$("#payment-form").submit();
	},700);
}

function payBtn(amount)
{
	let grossamount = amount;
	
		event.preventDefault();
		
	$(this).attr("disabled", "disabled");
	
	$.ajax({
		url: '<?=site_url()?>/snap/token',
		cache: false,
		data: {
			grossamount: grossamount,
			studentNIS : "<?= $_GET['r'] ?>"
		},
		success: function(data) {
			
			console.log('token = '+data);
			
			snap.pay(data, {

				onSuccess: function(result){
					toHistoryPay(result);
				},
				onPending: function(result){
					toHistoryPay(result);
				},
				onError: function(result){
					toHistoryPay(result);
					$(this).attr("disabled", false);
				}
			});
		}
	});
}
</script>
