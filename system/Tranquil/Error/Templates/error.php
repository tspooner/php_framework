<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Exception</title>
		<link rel="stylesheet" type="text/css" href="css/gumby.css">
		<link rel="stylesheet" type="text/css" href="css/prettyprint/prettify.css">
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/prettyprint/prettify.js"></script>
		<style type="text/css">
			<?php include 'styles.css'; ?>
		</style>
	</head>
	<body onload="prettyPrint()">
		<div class="header" style="margin-bottom: 0px;">
			<div class="row">
				<div class="twelve columns">
					<header>
						<h1>Uncaught Exception <span style="color: rgb(231,55,105);"><?php echo $e->getCode(); ?></span></h1>
						<h2><?php echo $e->calledException; ?></h2>
						<p><?php echo $e->getMessage(); ?></p>
					</header>
				</div>
			</div>
		</div>
		<div class="header" style="background-color: white;">
			<div class="row">
				<div class="twelve columns">
					<header>
						<p style="margin-top: 0px; text-align: center;"><?php echo $e->getCustom(); ?></p>
					</header>
				</div>
			</div>
		</div>
		<div class="container row">
			<div class="four columns">
				<section>
					<h2 style="margin-left: 20px; margin-top: 20px;">Stack Trace</h2>
					<?php
					$i = 1;
					foreach ($trace = $e->getTrace() as $key => $val)
					{
						?>
						<div id="<?php echo $i; ?>" class="trace <?php if ($i == 1) echo 'blue'; ?>">
							<span class="brown"><?php echo $key + 1; ?>.</span>
							<span><?php if (isset($val['class'])) echo $val['class']; ?> </span><span style="color: rgb(231,55,105);"><?php echo $val['type'] . ' ' . $val['function']; ?></span>
							<p><?php if (isset($val['file'])) echo str_replace(ROOT, '', $val['file']) . ': ' . $val['line']; ?></p>
						</div>
						<?php
						$i++;
					}
					?>
				</section>
			</div>
			<div class="eight columns">
				<section>
					<h2 style="margin-left: 20px; margin-top: 20px;">Code Snippet</h2>
					<?php foreach ($trace as $key => $val):
						$offset = ($val['line'] - 5) < 1 ? '1' : $val['line'] - 7;
						$lines = $e->getFileLines($val['file'], $offset, 12);
					?>
					<div id="code-<?php echo ($key + 1); ?>" class="code <?php if ($key == 0) echo 'current'; ?>">
						<div class="code-title"><span><?php echo $val['file']; ?></span></div>
						<pre class="prettyprint linenums:<?php echo ($offset+1); ?>"><?php foreach ($lines as $line) echo tab_to_space($line); ?></pre>
					</div>
					<?php endforeach; ?>
				</section>
				<section style="padding: 20px;">
					<h2>Server/Request Data</h2>
					<dl>
					<?php
					foreach ($_SERVER as $name => $val)
					{
						if (strpos($name, 'arg') !== 0)
							echo "<dt>$name</dt><dd><span class=\"brown\">$val</span></dd>";
					}
					?>
					</dl>
					<br />
					<h2>Environment Variables</h2>
					<dl>
					<?php
					foreach ($_ENV as $name => $val)
					{
						if (strpos($name, 'arg') !== 0)
							echo "<dt>$name</dt><dd><span class=\"brown\">$val</span></dd>";
					}
					?>
					</dl>
				</section>
			</div>
		</div>
		<script type="text/javascript">
			$('.trace').click(function() {
				$('.trace.blue').removeClass('blue');
				$(this).addClass('blue');

				currId = '#code-' + $(this).attr('id');

				$('.code.current').removeClass('current');
				$(currId).addClass('current');
			});

			$(window).bind('load', function() {
				$('pre.prettyprint').each(function() {
					$('li:eq(5)', this).addClass('highlight-b');
					$('li:eq(6)', this).addClass('highlight-a');
					$('li:eq(7)', this).addClass('highlight-b');
				});
			});
		</script>
	</body>
</html>