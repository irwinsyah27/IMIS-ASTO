<script type="text/javascript">
	$(function() {
		$('[name=submit]').click(function() {
			if ($('#name').val() == '') {
				alert('Nama fuel tank tidak boleh kosong');
				return false;
			}

			if ($('#capacity').val() == '' || $('#capacity').val() == 0) {
				alert('Capacity tidak boleh kosong');
				return false;
			}

			$('form').submit();
		});
	});
</script>
