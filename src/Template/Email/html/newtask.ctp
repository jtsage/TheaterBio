<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
?>
<h3>A New Task has been created!</h3>

<p>A new task has been created and assigned:</p>

<table>
	<tr><th style="text-align: left; font-weight:bold; width: 30%">Created By:</th><td><?=  $creator ?></td></tr>
	<tr><th style="text-align: left; font-weight:bold">Asssigned To:</th><td><?=  $assign ?></td></tr>
	<tr><th style="text-align: left; font-weight:bold">Due Date:</th><td><?=  $due ?></td></tr>
	<tr><th style="text-align: left; font-weight:bold">Title:</th><td><?=  $title ?></td></tr>
	<tr><th style="text-align: left; font-weight:bold" colspan="2">Description:</th></tr>
	<tr><td><?=  nl2br($descrip) ?></td></tr>
</table>

<p>You can view this task at <a href="<?= $link ?>"><?= $link ?></a>.</p>