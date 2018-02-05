<div class="wrap">
	<h1>Dead Simple Google Drive Backups</h1>
	<hr/>
	<h2>Logs</h2>
	<div class="log-wrapper">
		<?php $logger = Sole_Google_Logger::get_instance(); ?>
		<?php $log_messages = $logger->get_log_events(); ?>
		<table>
			<?php foreach ( $log_messages as $message ): ?>
				<tr class="message-container">
					<td><?php echo $message->log_time; ?></td>
					<td><?php echo $message->log_type; ?></td>
					<td><?php echo $message->log_message; ?></td>
				</tr>
			<?php endforeach; ?>
		</table>
		<?php $logger->the_table_pagination(); ?>
	</div>
</div>
<style>
	.log-wrapper table {
		border-spacing: 0;
	}
	.log-wrapper tr:nth-child(2n) {
		background-color: #ccc;
	}
	.log-wrapper tr td {
		padding: 10px 20px;
	}
	.log-wrapper tr td:nth-child(2) {
		padding: 10px 30px;
	}
</style>
