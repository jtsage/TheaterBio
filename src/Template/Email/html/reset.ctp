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
<h3>Password reset requested!</h3>

<p>Your, or someone else from a computer at <strong><?= $ip ?></strong> has requested a password reset for <strong><?= $username ?></strong>.</p>

<p>To reset your password please go to <a href="<?= $fullURL . $hash ?>"><?= $fullURL . $hash ?></a> before <strong><?= $expire ?></strong>.</p>

<p>If you did not request this action, please just ignore this e-mail.  It was only sent to your address, and you should not assume your account has been compromised. If you are still concerned, logging in normally will clear this temporary reset link.</p>
