<?php 
	
	echo '<p>';
	foreach ($_POST as $key => $value) 
	{
		//echo '<p>key :         ' . $key . '               | value : ' . $value . '</p>' ;
		if (!is_null($value))
		{
			$exploded_key = explode('|', $key);
			echo 'value changed : ' . $value . ' for the key ' . $exploded_key[1] . ' of the table ' . $exploded_key[0] .' <br/>' ;

		}
	}
	echo '</p>';
?>