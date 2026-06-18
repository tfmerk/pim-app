<?php

use tfmerk\PolarisPim\Entities\User;

/**
 * @var string $heading
 * @var string $username
 * @var array<User> $users
 */
?>
<div class="card">
	<h1><?= htmlspecialchars($heading) ?></h1>
	<p>Welcome back, <strong><?= htmlspecialchars($username) ?></strong>!</p>

	<h2>Users</h2>
	<table>
		<thead>
			<tr>
				<th>ID</th>
				<th>Username</th>
				<th>E-Mail</th>
				<th>Last Seen</th>
				<th>Created At</th>
				<th>Metadata</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($users as $user): ?>
				<tr>
					<td><?= htmlspecialchars((string) $user->id) ?></td>
					<td><?= htmlspecialchars($user->username) ?></td>
					<td><?= htmlspecialchars($user->email) ?></td>
					<td><?= htmlspecialchars($user->lastSeen->format('Y-m-d H:i:s')) ?></td>
					<td><?= htmlspecialchars($user->createdAt->format('Y-m-d H:i:s')) ?></td>
					<td>
						<?php foreach ($user->metadata as $key => $value): ?>
							<?php
							// Format booleans to explicit true/false strings
							if (is_bool($value)) {
								$displayValue = $value ? 'true' : 'false';
							} elseif (is_array($value)) {
								$displayValue = implode(', ', $value);
							} else {
								$displayValue = (string) $value;
							}
							?>
							<div>
								<strong><?= htmlspecialchars($key) ?>:</strong>
								<?= htmlspecialchars($displayValue) ?>
							</div>
						<?php endforeach; ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
