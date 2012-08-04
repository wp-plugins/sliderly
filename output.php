<?php

	foreach ($slideshow_exploded as $slide)
	{
		$slide_exploded = explode("|02df|", $slide);
		$src = $slide_exploded[0];
		$title = $slide_exploded[1];
		$desc = $slide_exploded[2];
		$href = $slide_exploded[3];
		
		$html .= "
			<div class='slide'>";

		if ($colorbox == "true")
		{
			if ($desc != "")
			{
				$ddesc = " - " . $desc;
			}
			
			if ($href != "")
			{
				$html .= "<a href='" . $href . "' class='colorbox' title=\"" . $title . $ddesc . "\">";
			}
			else
			{
				$html .= "<a href='" . $src . "' class='colorbox' title=\"" . $title . $ddesc . "\">";
			}
		}
		else if ($href != "")
		{
			$html .= "<a href='" . $href . "'>";
		}

		if ($type == "slideshow")
		{
			
			if ($width != "" && $height != "")
			{
				$width_and_height = " style='width: " . $width . "px; height: " . $height . "px; '";
			}
			
			$html .= "
					<img src='" . $src . "' alt='" . $title . "' data-desc='" . $desc . "'" . $width_and_height . " />";
		}
		else
		{
			$html .= "
					<img src='" . $src . "' alt='" . $title . "' data-desc='" . $desc . "' />";
		}
		
		if ($type == "slideshow" && ($title != "" || $desc != ""))
		{
			$html .= "
					<div class='caption' style='bottom: 5px; '>";
					
			if ($title != "")
			{
				$html .= "<h2>$title</h2>";
			}
			
			if ($desc != "")
			{
				$html .= "<p>$desc</p>";
			}

			$html .= "
					</div>";
		}

		if ($colorbox == "true")
		{
			if ($href != "")
			{
				$html .= "</a>";
			}
			else
			{
				$html .= "</a>";
			}
		}
		else if ($href != "")
		{
			$html .= "</a>";
		}

		$html .= "
		</div>";
	}
	
	if ($files_set != "true")
	{
		include("css.php");
		include("js.php");
		$files_set = "true";
	}
	
	$output = "
			<div class='sliderly-$type sliderly-grid-$grid controls-$controls'>
				<p class='sliderly-title'>$slideshow_title</p>
				<div class='slides_container'>"
					 . $html . 
				"</div>";

	if ($type == "gallery")
	{
		$output .= "<div style='clear: both; '></div>";
	}

	$output .= "</div>";

?>