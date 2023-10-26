<script type="text/javascript">
	jQuery(document).ready(function() {

		var order_field = 'ctime';
		var order_dir = 'desc';

		$('form').submit(function(e) {
			e.preventDefault();
			[0,1,2,3].forEach(function(s) {
				reloadTable('<?= base_url('task') ?>', s);
			});
		});

		$('#reset').click(function(e) {
			e.preventDefault();

			$('[name=q]').val('');
			$('[name=status]').val('');
			$('[name=is_scope]').val('');
			$('[name=priority]').val('');

			[0,1,2,3].forEach(function(s) {
				reloadTable('<?= base_url('task') ?>', s);
			});
		});

		$('[name=q]').keyup(function() {
			[0,1,2,3].forEach(function(s) {
				reloadTable('<?= base_url('task') ?>', s);
			});
		});

		$('[name=priority]').change(function() {
			[0,1,2,3].forEach(function(s) {
				reloadTable('<?= base_url('task') ?>', s);
			});
		});

		$('[name=is_scope]').change(function() {
			[0,1,2,3].forEach(function(s) {
				reloadTable('<?= base_url('task') ?>', s);
			});
		});

		var reloadTable = function(url,s) {
			$.ajax({
				url: url,
				type: 'get',
				data: {
					status: s,
					q: $('[name=q]').val(),
					is_scope: $('[name=is_scope]').val(),
					priority: $('[name=priority]').val(),
					order_field: order_field,
					order_dir: order_dir
				},
				dataType: 'json',
				success: function(j) {
					$('#table-'+j.status).html(j.table);
					$('#pager-'+j.status).html(j.pager);
					$('#total-'+j.status).html(j.total);
				}
			});
		};

		[0,1,2,3].forEach(function(s) {
			reloadTable('<?= base_url('task') ?>', s);
		});

		setInterval(function() {
			[0,1,2,3].forEach(function(s) {
				reloadTable('<?= base_url('task') ?>', s);
			});
		}, 5000);

		$('[id*="pager"]').on('click', '.pagination > li > a', function(e) {
			e.preventDefault();
			reloadTable(this.href);
		});

		$('body').on('click', '.confirm', function(e) {
			e.preventDefault();
			if (confirm('Anda yakin?')) {
				$.ajax({
					url: this.href,
					type: 'get',
					dataType: 'json',
					success: function(j) {
						// reload the grid
						[0,1,2,3].forEach(function(s) {
							reloadTable('<?= base_url('task') ?>', s);
						});
					}
				});
			}
		});

		$('body').on('click', '.order', function(e) {
			e.preventDefault();

			order_field = $(this).data('field');
			order_dir = $(this).data('order');

			[0,1,2,3].forEach(function(s) {
				reloadTable('<?= base_url('task') ?>', s);
			});
		});

	});

</script>
