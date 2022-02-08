<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods:GET,OPTIONS,POST");

class Snap extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */


	public function __construct()
    {
        parent::__construct();
        $params = array('server_key' => 'SB-Mid-server-Lo_uSY01swGxJlSirpJkYx6x', 'production' => false);
		$this->load->library('midtrans');
		$this->midtrans->config($params);
		$this->load->helper('url');	
    }

    public function index()
    {
    	$this->load->view('checkout_snap');
    }

    public function spp()
    {
    	$this->load->view('pembayaranspp');
    }

    public function token()
    {
		
		// Required
		$grossamount = $this->input->get('grossamount');
		$transaction_details = array(
		  'order_id' => rand(),
		  'gross_amount' => $grossamount, // no decimal allowed for creditcard
		);

		// Optional
		$item1_details = array(
		  // 'id' => 'a1',
		  // 'price' => 18000,
		  // 'quantity' => 3,
		  // 'name' => "Apple"
		);

		// Optional
		$item2_details = array(
		  // 'id' => 'a2',
		  // 'price' => 20000,
		  // 'quantity' => 2,
		  // 'name' => "Orange"
		);

		// Optional
		$item_details = array ($item1_details, $item2_details);

		// Optional
		$billing_address = array(
		  // 'first_name'    => "Andri",
		  // 'last_name'     => "Litani",
		  // 'address'       => "Mangga 20",
		  // 'city'          => "Jakarta",
		  // 'postal_code'   => "16602",
		  // 'phone'         => "081122334455",
		  // 'country_code'  => 'IDN'
		);

		// Optional
		$shipping_address = array(
		  // 'first_name'    => "Obet",
		  // 'last_name'     => "Supriadi",
		  // 'address'       => "Manggis 90",
		  // 'city'          => "Jakarta",
		  // 'postal_code'   => "16601",
		  // 'phone'         => "08113366345",
		  // 'country_code'  => 'IDN'
		);

		// Optional
		$customer_details = array(
		  // 'first_name'    => "Andri",
		  // 'last_name'     => "Litani",
		  // 'email'         => "andri@litani.com",
		  // 'phone'         => "081122334455",
		  // 'billing_address'  => $billing_address,
		  // 'shipping_address' => $shipping_address
		);

		// Data yang akan dikirim untuk request redirect_url.
        $credit_card['secure'] = true;
        //ser save_card true to enable oneclick or 2click
        //$credit_card['save_card'] = true;

        $time = time();
        $custom_expiry = array(
            'start_time' => date("Y-m-d H:i:s O",$time),
            'unit' => 'day', 
            'duration'  => 1
        );
        
        $transaction_data = array(
            'transaction_details'=> $transaction_details,
            'item_details'       => $item_details,
            'customer_details'   => $customer_details,
            'credit_card'        => $credit_card,
            'expiry'             => $custom_expiry
        );

		error_log(json_encode($transaction_data));
		$snapToken = $this->midtrans->getSnapToken($transaction_data);
		error_log($snapToken);
		echo $snapToken;
    }

    public function finish()
    {
    	$result = json_decode($this->input->post('result_data'), true);
		
    	$data = [
    		'order_id' 	=> $result['order_id'],
			'id_siswa'	=> $_POST['student_student_id'],
    		'gross_amount' => $result['gross_amount'],
    		'payment_type' => $result['payment_type'],
    		'transaction_time' => $result['transaction_time'],
    		'bank' => $result['va_numbers'][0]["bank"],
    		'va_number' => $result['va_numbers'][0]["va_number"],
    		'pdf_url' => $result['pdf_url'],
    		'status_code' => $result['status_code'],
			'typeBayar'	  => 'bebas',
			'statusBayar' => 0
    	];

    	$simpan = $this->db->insert('transaksi_midtrans', $data);
		
		$bebas  = $this->db->query("SELECT * FROM `bebas` where student_student_id='{$_POST['student_student_id']}'");
		
		if($bebas->num_rows() != 0){
			
			$d = $bebas->first_row();
			
			$this->db->where('student_student_id', $_POST['student_student_id'])->update('bebas', [
				'bebas_total_pay' => $d->bebas_total_pay + $_POST['bebas_pay_bill']
			]);
		}
		
    	if ($simpan){
    		echo "sukses";
    	} else{
    		echo "gagal";
    	}
		
		redirect($_SERVER['HTTP_REFERER']);
    }
	
	public function add_bulan_history()
	{
		$result = json_decode($this->input->post('result_data'), true);
		
		$siswa  = $this->db->query("SELECT * FROM student where student_nis='{$_POST['nis']}'")->first_row();
		$data = [
    		'order_id' 		=> $result['order_id'],
			'id_siswa'		=> $siswa->student_id,
    		'gross_amount' 	=> $result['gross_amount'],
    		'payment_type' 	=> $result['payment_type'],
    		'transaction_time' => $result['transaction_time'],
    		'bank' 		=> $result['va_numbers'][0]["bank"],
    		'va_number' => $result['va_numbers'][0]["va_number"],
    		'pdf_url' 	=> $result['pdf_url'],
    		'status_code' => $result['status_code'],
			'typeBayar'	  => 'bulan',
			'statusBayar' => 0,
			'target' 	  => $_POST['bulan']
    	];
    	$simpan = $this->db->insert('transaksi_midtrans', $data);
		$back   = $_SESSION['back-home'];
		
		unset($_SESSION['back-home']);
		
		redirect($back);
	}
	
	public function add_bulan_history_cancel()
	{
		$siswa = $this->db->where('student_nis', $_POST['nis_cancel'])
					  ->get('student')
					  ->result();
					  
		if(isset($siswa[0]))
		{
			$this->db->where('bulan_id', $_POST['bulan_cancel'])
					 ->where('student_student_id', $siswa[0]->student_id)
					 ->update('bulan', [ 
						'bulan_status' 	   => 0, 
						'bulan_number_pay' => 'NULL',
						'bulan_date_pay'   => 'NULL'
					 ]);
		}
		
		$back = $_SESSION['back-home'];
		
		unset($_SESSION['back-home']);
		
		redirect($back);
	}
}
