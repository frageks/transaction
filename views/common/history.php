<?php
/**
 * Author: frageks.
 * Date: 13/09/17.
 * Time: 16:07.
 */

/* @var $data \app\models\TransactionHistory[] */
$this->title = "History";
?>

<div class="common-history">
	<ul>
	<?php foreach ((array)$data as $key => $item) {
		echo "<li>", $item->sender0->username, " ---> ", $item->sum, " ---> ", $item->receiver0->username, "</li>";
	} ?>
	</ul>
</div>
