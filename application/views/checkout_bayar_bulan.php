<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script type="text/javascript"
            src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="SB-Mid-client-VG5GkE3IA4hd0M05"></script>
	<!-- Note: replace with src="https://app.midtrans.com/snap/snap.js" for Production environment -->
</head>
<body>
<form id="payment-form" method="post" action="<?=site_url()?>/snap/add_bulan_history">
	<input type="hidden" name="nis" value="<?= $r ?>">
	<input type="hidden" name="bulan" value="<?= $idBulan ?>">
	<input type="hidden" name="result_data" id="result-data">
</form>
<form id="payment-cancel" method="post" action="<?=site_url()?>/snap/add_bulan_history_cancel">
	<input type="hidden" name="nis_cancel" value="<?= $r ?>">
	<input type="hidden" name="bulan_cancel" value="<?= $idBulan ?>">
</form>
	<script type="text/javascript">
	
		function addHistory(result) 
		{
			document.querySelector('#result-data').value = JSON.stringify(result);
			
			setTimeout(function(){
				document.querySelector('#payment-form').submit();
			},800)
		}
		
		window.snap.pay("<?= $snapToken ?>",{
			onSuccess: function(result){
				console.log(JSON.stringify(result));
				addHistory(result);
			},
			onPending: function(result){
				console.log(JSON.stringify(result));
				addHistory(result);
        	},
			onError: function(){
				document.querySelector('#payment-cancel').submit();
			},
			onClose: function(){
				document.querySelector('#payment-cancel').submit();
			}
		});
	</script>
	</body>
</html>

