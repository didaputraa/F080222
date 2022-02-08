gfc fcf<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?php echo isset($title) ? '' . $title : null; ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo site_url('student') ?>"><i class="fa fa-th"></i> Home</a></li>
			<li class="active"><?php echo isset($title) ? '' . $title : null; ?></li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<!-- /.box-header -->
					<div class="box-body table-responsive">
						<table class="table table-hover">
							<tr>
								<th>No</th>
								<th>Jenis Pembayaran</th>
								<th>Total Tagihan</th>
								<th>Status</th>
							</tr>
							<tbody>
								<?php
												$i =1;
												foreach ($student as $row):
													if ($f['n'] AND $f['r'] == $row['student_nis']) {
														?>
														<tr>
															<td><?php echo $i ?></td>
															<td><?php echo $row['pos_name'].' - T.A '.$row['period_start'].'/'.$row['period_end'] ?></td>
															<td><?php echo ($total == $pay) ? 'Rp. -' : 'Rp. '.number_format($total-$pay,0,',','.') ?></td>
															<?php foreach ($bulan as $row) : ?>
																<td class="<?php echo ($row['bulan_status'] ==1) ? 'success' : 'danger' ?>"><a href="<?php echo ($row['bulan_status'] ==0) ? site_url('manage/payout/pay/' . $row['payment_payment_id'].'/'.$row['student_student_id'].'/'.$row['bulan_id']) : site_url('manage/payout/not_pay/' . $row['payment_payment_id'].'/'.$row['student_student_id'].'/'.$row['bulan_id'])?>" onclick="return confirm('<?php echo ($row['bulan_status']==0) ? 'Anda Akan Melakukan Pembayaran bulan '.$row['month_name'].'?' : 'Anda Akan Menghapus Pembayaran bulan'.$row['month_name'].'?' ?>')">
																	<?php echo ($row['bulan_status']==1) ? '('.pretty_date($row['bulan_date_pay'],'d/m/y',false).')': number_format($row['bulan_bill'], 0, ',', '.') ?></a></td>
																<?php endforeach ?>

															</tr>
															<?php 
														}
														$i++;
													endforeach; 
													?>				
						</tbody>
					</table>
				</div>
				<!-- /.box-body -->
			</div>
			<!-- /.box -->
		</div>
	</div>
</section>
<!-- /.content -->
</div>