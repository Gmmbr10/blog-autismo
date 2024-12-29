<?php

class SiginController
{

	public function index(array $data)
	{

		$links = file_get_contents(__DIR__ . "/../views/components/style.html");
		$header = file_get_contents(__DIR__ . "/../views/components/header-default.html");
		$footer = file_get_contents(__DIR__ . "/../views/components/footer.html");
		$html = file_get_contents(__DIR__ . "/../views/pages/sigin.html");

		$header = str_replace( "{inicio}" , "" , $header );
		$header = str_replace( "{blog}" , "" , $header );
		$header = str_replace( "{about}" , "" , $header );

		$html = str_replace( "{script_style}" , $links , $html );
		$html = str_replace( "{component_header}" , $header , $html );
		$html = str_replace( "{component_footer}" , $footer , $html );
		$html = str_replace( "{include_path}" , INCLUDE_PATH , $html );

		echo $html;

	}

}