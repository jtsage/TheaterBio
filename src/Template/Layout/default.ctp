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


if ( $this->request->getParam('controller') == "Pages" ) {
  $cakeDescription = 'TheaterBio: the theater playbill biography and headshot system for '. CINFO['longname'];
} else {
  $cakeDescription = 'TheaterBio:' . CINFO['shortname'] . ":" . $this->fetch('title');
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?= $this->Html->charset() ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title><?= $cakeDescription ?></title>

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="apple-mobile-web-app-title" content="TheaterBio">
    <meta name="application-name" content="TheaterBio">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <?php

       echo $this->fetch('meta');
       echo $this->fetch('css');
       echo $this->fetch('script');
      
       echo $this->Html->css('bootstrap.min.css');
       echo $this->Html->css('bootstrap-switch.min');
       echo $this->Html->css('https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css');
       echo $this->Html->css('tdtracx');

    ?>

  </head>
  <body>

	<nav class="navbar navbar-expand-lg navbar-light bg-light">
		<a href="/" class="navbar-brand">Theater<span style="color:#C3593C">Bio</span><span style="color:#c39b1f"><?= CINFO['shortname']?></span></a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item <?= ($this->request->getParam('controller') == "Bios" ? "active'":"") ?>"><a class="nav-link" href="/bios/"><?= __("Bios") ?></a></li>
				<li class="nav-item <?= ($this->request->getParam('controller') == "Headshots" ? "class='active'":"") ?>"><a class="nav-link" href="/headshots/"><?= __("Headshots") ?></a></li>
				<li class="nav-item <?= ($this->request->getParam('controller') == "Users" ? "class='active'":"") ?>"><a class="nav-link" href="/users/"><?= ($WhoAmI) ? __("Users") : __("My Account") ?></a></li>
				<?= ($WhoAmI) ? "<li class='nav-item" . ($this->request->getParam('controller') == "Purposes" ? " class='active'":"") . "'><a class=\"nav-link\" href=\"/purposes/\">Purposes</a></li>" : "" ?>
				<li class="nav-item"><a class="nav-link" href="/users/logout/"><?= __("Logout") ?></a></li>
        <li class="nav-item"><a class="nav-link" onClick="javascript:$('#helpMeModal').modal(); return false;" href="#"><i class="fa fa-lg fa-fw fa-question-circle"></i>&thinsp;<?= __("Help") ?></a></li>
			</ul>
			<?php 
				$user = $this->request->getSession()->read('Auth.User');

				if( ! empty( $user ) ) {
					echo '<p class="navbar-text navbar-right">' . __("Signed in") . ': ' . $user['first'] . " " . $user['last'] . ' </p>';
				}
			?>
		</div>
	</nav>


  <div class="container" style="padding-top:20px" role="main">

    <?php 
      if ( !empty($crumby) && is_array($crumby) ) {
        echo '<nav aria-label="breadcrumb"><ol class="breadcrumb">';
        foreach ( $crumby as $crumb ) {
          if ( is_null($crumb[0]) ) {
            echo "<li class='breadcrumb-item active'>" . $crumb[1] . "</li>";
          } else {
            echo "<li class='breadcrumb-item'><a href='" . $crumb[0] . "'>" . $crumb[1] . "</a></li>";
          }
        }
        echo '</ol></nav>';
      }
    ?>

    <?= $this->Flash->render() ?>

    <?= $this->fetch('content') ?>
  
  </div>
  <footer style="padding-top: 20px; margin-top: 20px; border-top: 1px solid #e5e5e5;">
    <p class="text-center text-muted"><?= __("TheaterBio - The theater playbill biography &amp; headshot editor") ?><br /><small>Site Administrator Contact: <a href="mailto:<?= CINFO['adminmail'] ?>"><?= CINFO['adminname'] ?></a></small></p>
    <ul class="text-center list-inline text-muted d-print-none">
    	<li class="list-inline-item"><?= __('Currently v1.0.0a1') ?></li>
    	<li class="list-inline-item"><a href="https://github.com/jtsage/TheaterBio">GitHub</a></li>
    </ul>
    <p class="text-center text-muted d-print-block d-none">Printed on <?= date('Y-m-d H:i T') ?></p>
  </footer>
  
  <?php
    echo $this->Html->script('https://code.jquery.com/jquery-3.3.1.slim.min.js');
    echo $this->Html->script('bootstrap3-typeahead.min');
    echo $this->Html->script('bootstrap-switch.min');
    echo $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js');
    echo $this->Html->script('https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js');
    echo $this->Html->script('validator.min');
  ?>

  <script type="text/javascript">
    function do_rep() {
      var cur_pass = $('#password').val(),
          cur_user = $('#username').val(),
          cur_text = $('#welcomeEmail').val();

      cur_text = cur_text.replace(/username:.+\n/m, "username: " + cur_user + "\n");
      cur_text = cur_text.replace(/password:.+\n/m, "password: " + cur_pass + "\n");
      $('#welcomeEmail').val(cur_text);
    }
    $('#password').on('change', do_rep);
    $('#username').on('change', do_rep);

    $(".bootcheck").each(function() {
      $(this).bootstrapSwitch();
    });
    $('input[type="text"]').each(function() {
      $(this).after($('<div class="help-block with-errors"></div>'));
    })
    $(function () {
      $('[data-toggle="tooltip"]').tooltip({
        container: 'body'
      });
    });
    $(document).ready(function() { 
    	$('[role="alert"].error').addClass('alert alert-warning'); 
    	$('#bigcal tr[class^="bg-"]').each(function() { 
    		var color = $(this).css('backgroundColor'),
    			parts = color.match(/^rgba?\((\d+),\s*(\d+),\s*(\d+)(, \d+)?\)$/),
    			newColor = "rgba(" + parts[1] + "," + parts[2] + "," + parts[3] + ",0.25)";
    		$(this).css('backgroundColor', newColor);
    		$(this).removeClass('bg-primary bg-warning bg-success bg-default bg-dangert bg-info bg-dark bg-light bg-danger');
    	});
    	$('.card div[class^="card-body bg-"]').each(function() { 
    		var color = $(this).css('backgroundColor'),
    			parts = color.match(/^rgba?\((\d+),\s*(\d+),\s*(\d+)(, \d+)?\)$/),
    			newColor = "rgba(" + parts[1] + "," + parts[2] + "," + parts[3] + ",0.5)";
    		$(this).css('backgroundColor', newColor);
    		$(this).removeClass('bg-primary bg-warning bg-success bg-default bg-dangert bg-info bg-dark bg-light bg-danger');
    	});

    	$('#tableBod').each(function() {
    		var w = this,
    			h = Math.max(document.documentElement.clientHeight, window.innerHeight || 0),
    			hh = .75 * h,
          done = false;
          upper = $('#tableTop').find('tr').children('th'),
          lower = null, x = null, y = null, len = null;
    		$(w).attr('style', "postion: 'relative'; max-height: " + hh + "px; top: 0; left: 0; overflow-x: scroll");

        $(w).find('tr').each(function(){
          if ( !done && $(this).children('td').length > 3 ) {
            lower = $(this).children('td');
            len = lower.length;
            done = true;
          }
        });
        
        for (i = 0; i < len; i++) { $(upper[i]).width($(lower[i]).width()); }
    	});
    });
  </script>
  
  </body>
</html>
