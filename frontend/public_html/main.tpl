<!doctype html>
<html>
	<head>
		<title>Descriptive Programming</title>
		<script type="text/javascript" src="./main.js"></script>
	</head>
	<body>
		ho ho ho
		<?php
		
			$t1 = microtime(true);
			echo shell_exec('find "/mnt/d/Work_2020/TRAVEL/ECOMM - REPOS/TF_dev_on_DEV002/" -type f -not -path "*/.git/*" -not -path "*/gens/*" -printf "%p %TY-%Tm-%Td %TH:%TM:%TS %Tz\n"');
			$t2 = microtime(true);
			
			var_dump($t2 - $t1);
		
		?>
	</body>
</html>
