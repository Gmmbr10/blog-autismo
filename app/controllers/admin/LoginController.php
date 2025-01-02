<?php

class LoginController
{

	public function index(array $data)
	{

		$links = file_get_contents(__DIR__ . "/../../views/components/style.html");
		$header = file_get_contents(__DIR__ . "/../../views/components/header-admin-logout.html");
		$footer = file_get_contents(__DIR__ . "/../../views/components/footer.html");
		$html = file_get_contents(__DIR__ . "/../../views/pages/admin/login.html");

		$html = str_replace( "{script_style}" , $links , $html );
		$html = str_replace( "{component_header}" , $header , $html );
		$html = str_replace( "{component_footer}" , $footer , $html );
		$html = str_replace( "{include_path}" , INCLUDE_PATH , $html );

		echo $html;

	}

}