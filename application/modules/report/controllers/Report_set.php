<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_set extends CI_Controller {

	public function __construct() {
		parent::__construct(TRUE);
		if ($this->session->userdata('logged') == NULL) {
			header("Location:" . site_url('manage/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
		}
		$this->load->model(array('payment/Payment_model', 'student/Student_model', 'period/Period_model', 'pos/Pos_model', 'bulan/Bulan_model', 'bebas/Bebas_model', 'bebas/Bebas_pay_model', 'setting/Setting_model', 'kredit/Kredit_model', 'debit/Debit_model', 'logs/Logs_model'));

	}

    // payment view in list
	public function index($offset = NULL) {
        // Apply Filter
        // Get $_GET variable
		$q = $this->input->get(NULL, TRUE);

		$data['q'] = $q;

		$params = array();

    // Date start
		if (isset($q['ds']) && !empty($q['ds']) && $q['ds'] != '') {
			$params['date_start'] = $q['ds'];
		}

        // Date end
		if (isset($q['de']) && !empty($q['de']) && $q['de'] != '') {
			$params['date_end'] = $q['de'];
		}


		$paramsPage = $params;
		$data['period'] = $this->Period_model->get($params);
		$data['student'] = $this->Bulan_model->get(array('group'=>true));
		$data['bulan'] = $this->Bulan_model->get($params);
		$data['month'] = $this->Bulan_model->get(array('grup'=>true));
		$data['py'] = $this->Bulan_model->get(array('paymentt'=>true));
		$data['bebas'] = $this->Bebas_model->get(array('grup'=>true));
		$data['free'] = $this->Bebas_model->get($params);
		$data['dom'] = $this->Bebas_pay_model->get($params);


		$config['base_url'] = site_url('manage/report/index');
		$config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['total_rows'] = count($this->Bulan_model->get($paramsPage));

		$data['title'] = 'Laporan Keuangan';
		$data['main'] = 'report/report_list';
		$this->load->view('manage/layout', $data);
	}

	public function report_bill() {

		$q = $this->input->get(NULL, TRUE);

		$data['q'] = $q;

		$params = array();
		$param = array();
		$stu = array();
		$free = array();

		if (isset($q['p']) && !empty($q['p']) && $q['p'] != '') {
			$params['period_id'] = $q['p'];
			$param['period_id'] = $q['p'];
			$stu['period_id'] = $q['p'];
			$free['period_id'] = $q['p'];
		}

		if (isset($q['c']) && !empty($q['c']) && $q['c'] != '') {
			$params['class_id'] = $q['c'];
			$param['class_id'] = $q['c'];
			$stu['class_id'] = $q['c'];
			$free['class_id'] = $q['c'];
		}

		if (isset($q['k']) && !empty($q['k']) && $q['k'] != '') {
			$params['majors_id'] = $q['k'];
			$param['majors_id'] = $q['k'];
			$stu['majors_id'] = $q['k'];
			$free['majors_id'] = $q['k'];
		}

		$param['paymentt'] = TRUE;
		$params['grup'] = TRUE;
		$stu['group'] = TRUE;


		$data['period'] = $this->Period_model->get($params);
		$data['class'] = $this->Student_model->get_class($params);
		$data['majors'] = $this->Student_model->get_majors($params);
		$data['student'] = $this->Bulan_model->get($stu);
		$data['bulan'] = $this->Bulan_model->get($free);
		$data['month'] = $this->Bulan_model->get($params);
		$data['py'] = $this->Bulan_model->get($param);
		$data['bebas'] = $this->Bebas_model->get($params);
		$data['free'] = $this->Bebas_model->get($free);

		$config['suffix'] = '?' . http_build_query($_GET, '', "&");

		$data['title'] = 'Rekapitulasi';
		$data['main'] = 'report/report_bill_list';
		$this->load->view('manage/layout', $data);
	}

	public function report()
	{
        // Apply Filter
        // Get $_GET variable
		$q 		   = $this->input->get(NULL, TRUE);
		$data['q'] = $q;
		$params    = array();

        // Date start
		if (isset($q['ds']) && !empty($q['ds']) && $q['ds'] != '') {
			$params['date_start'] = $q['ds'];
		}
        // Date end
		if (isset($q['de']) && !empty($q['de']) && $q['de'] != '') {
			$params['date_end'] = $q['de'];
		}
		
		$params['status'] = 1;
		
		$data['bulan'] = $this->Bulan_model->get($params);
		$data['bebas'] = $this->Bebas_model->get($params);
		$data['free'] = $this->Bebas_pay_model->get($params);
		$data['kredit'] = $this->Kredit_model->get($params);
		$data['debit'] = $this->Debit_model->get($params);
		$data['setting_school'] = $this->Setting_model->get(array('id' => SCHOOL_NAME));
		$no   = 1;
		$html = "<h4>Laporan Keuangan</h4
				<p>{$data['setting_school']['setting_value']}<br>
				<p>Tanggal Laporan: ".pretty_date($q['ds'],'d F Y',false)." s/d".
					pretty_date($q['de'],'d F Y',false)."<br>
				Tanggal Unduh: ".pretty_date(date('Y-m-d h:i:s'),'d F Y, H:i',false)."<br>
				Pengunduh: {$this->session->userdata('ufullname')}</p>
				<table style='font-size:12.4px;text-align:center;border-collapse:collapse' width='100%'>
					<tr>
						<th bgcolor='#000' style='color:#fff;'>NO</th>
						<th bgcolor='#000' style='color:#fff;'>PEMBAYARAN</th>
						<th bgcolor='#000' style='color:#fff;'>NAMA SISWA</th>
						<th bgcolor='#000' style='color:#fff;'>KELAS</th>
						<th bgcolor='#000' style='color:#fff;'>TANGGAL</th>
						<th bgcolor='#000' style='color:#fff;'>PENERIMAAN</th>
						<th bgcolor='#000' style='color:#fff;'>PENGELUARAN</th>  
						<th bgcolor='#000' style='color:#fff;'>KETERANGAN</th>
					</tr>";
		foreach ($data['bulan'] as $row) {
			$html .= "
			<tr>
				<td>$no</td>
				<td align='left'>
					{$row['pos_name']} - T.A
					{$row['period_start']}/{$row['period_end']}-{$row['month_name']}
				</td>
				<td>{$row['student_full_name']}</td>
				<td>{$row['class_name']}</td>
				<td>".pretty_date($row['bulan_date_pay'], 'm/d/Y', FALSE)."</td>
				<td>".number_format($row['bulan_bill'],0,',','.')."</td>
				<td></td>
			</tr>";
			$no++;    
		}
		foreach ($data['free'] as $row) {
			$html .= "
				<tr>
					<td>$no</td>
					<td align='left'>{$row['pos_name']}-T.A {$row['period_start']}/{$row['period_end']}
					<td>{$row['student_full_name']}</td>
					<td>{$row['class_name']}</td>
					<td>".pretty_date($row['bebas_pay_input_date'], 'm/d/Y', FALSE)."
					<td>".number_format($row['bebas_pay_bill'],0,',','.')."
					<td></td>
				</tr>";
			$no++;
		}
		foreach ($data['debit'] as $row)
		{
			$html .= "
			<tr>
				<td>$no</td>
				<td>{$row['debit_desc']}</td>
				<td>-</td>
				<td>-</td>
				<td>".pretty_date($row['debit_date'], 'm/d/Y', FALSE)."
				<td>{$row['debit_value']}
			</tr>";
			
			$no++;    
		}
		$html .= "</table>";
		
		$this->load->helper(array('dompdf'));
		
		pdf_create($html, 'laporan-keuangan.pdf', true, 'paper', 'landscape');
	}


// Rekapituliasi
	public function report_bill_detail()
	{
		$this->load->helper('dompdf');
		
		$siswa = $this->db->query("SELECT * FROM student WHERE class_class_id={$_GET['c']}")->result();
		
		$html = $this->load->view('report_bill_detail',[ 'siswa' => $siswa ],true);
		
		$data = pdf_create($html, 'Rekapitulasi', TRUE, 'A4', 'landscape');

	}

}

/* End of file Report_set.php */
/* Location: ./application/modules/report/controllers/Report_set.php */
