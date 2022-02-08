<html>
<head>
<style>
@page {
	margin-top: 2cm;
	margin-left: 1cm;
	margin-right: 1cm;
	margin-bottom: 0.1cm;
}
table{border-collapse:collapse;font-size:11.6px}
</style>
</head>
<body>
<?php

$period = $this->db->query("SELECT * FROM period WHERE period_id={$_GET['p']}")->first_row();

echo "<h4 align='center'>REKAPITULASI PEMBAYARAN SISWA</h4>";

echo"<p>Periode Laporan: ".$period->period_start."/".$period->period_end."</p>";
echo"<p>Tanggal unduh: ".date('d-m-Y H:i:s', strtotime('now'))."</p>\n";

echo "<table border=\"1px\" width=\"100%\">
	<tr>
		<th>No</th>
		<th>Kelas</th>
		<th>Nama siswa</th>
		<th colspan=\"12\">SPP-TA ".$period->period_start." / ".$period->period_end."</th>
		<th>Paket Buku pelajaran ".$period->period_start." / ".$period->period_end."</th>
	</tr>";

	foreach($siswa as $no => $student)
	{
		$kelas = $this->db->query("SELECT class_name FROM `class` WHERE class_id='{$_GET['c']}'")
				->first_row();
				
		echo"\n<tbody>
			 <tr>
				<td rowspan='2'>".($no+1)."</td>
				<td rowspan='2'>{$student->student_full_name}</td>
				<td rowspan='2'>{$kelas->class_name}</td>";
				
			$pay = $this->db->query("SELECT * FROM payment WHERE period_period_id={$_GET['p']}")->first_row();
			$bulan = $this->db->query("SELECT 
										`bulan`.`bulan_bill` as bill,
										`bulan`.`bulan_status` as status,
										`month`.`month_name` as bulan
										FROM bulan
										INNER JOIN `month` ON 
										`month`.`month_id` = `bulan`.`month_month_id`
										WHERE bulan.student_student_id={$student->student_id} &&
										bulan.payment_payment_id={$pay->payment_id}")
					 ->result();
			
			foreach($bulan as $month)
			{
				echo "<td>{$month->bulan}</td>\n\t\t\t\t";
			}
			
			$bebas = $this->db->query("SELECT * FROM bebas where student_student_id={$student->student_id}")->first_row();
			
			if($bebas->bebas_bill == $bebas->bebas_total_pay)
			{
				$status = 'Lunas';
			}else{
				$status = $bebas->bebas_bill == $bebas->bebas_total_pay;
			}
			echo "<td rowspan='2' align='center'>{$status}</td>\n";
			
			echo "\t\t\t</tr>\n\t\t\t<tr>\n";
			
			foreach($bulan as $month)
			{
				if($month->status==1){
					echo "<td align='center'>Lunas</td>\n\t\t\t";
				}else{
					echo "<td align='center'>{$month->bill}</td>\n\t\t\t\t";
				}
			}
			echo"</tr></tbody>";
	}
	?>
</table>
</body></html>
