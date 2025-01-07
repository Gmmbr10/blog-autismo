<?php

class CommonController {

  public function index(array $data)
	{

		$links = file_get_contents(__DIR__ . "/../views/components/style.html");

		session_start();

		if (isset($_SESSION["user"])) {

			$header = file_get_contents(__DIR__ . "/../views/components/header-loged.html");
			$footer = file_get_contents(__DIR__ . "/../views/components/footer-loged.html");
			$html = file_get_contents(__DIR__ . "/../views/pages/user/home.html");

			$header = str_replace("{inicio}", "navbar__link--active", $header);
			$header = str_replace("{recentes}", "", $header);
			$header = str_replace("{criar_postagem}", "", $header);
			
			$html = str_replace("{script_style}", $links, $html);
			$html = str_replace("{component_header}", $header, $html);
			$html = str_replace("{component_footer}", $footer, $html);
			$html = str_replace("{include_path}", INCLUDE_PATH, $html);

			echo $html;

			return;
		}

    session_destroy();

		header("location: ./home");
	}

  public function recentes()
	{
		session_start();

		if (isset($_SESSION["user"])) {

			$links = file_get_contents(__DIR__ . "/../views/components/style.html");
			$header = file_get_contents(__DIR__ . "/../views/components/header-loged.html");
			$footer = file_get_contents(__DIR__ . "/../views/components/footer-loged.html");
			$html = file_get_contents(__DIR__ . "/../views/pages/user/newPosts.html");

			$header = str_replace("{inicio}", "", $header);
			$header = str_replace("{recentes}", "navbar__link--active", $header);
			$header = str_replace("{criar_postagem}", "", $header);
			
			$html = str_replace("{script_style}", $links, $html);
			$html = str_replace("{component_header}", $header, $html);
			$html = str_replace("{component_footer}", $footer, $html);
			$html = str_replace("{include_path}", INCLUDE_PATH, $html);

			echo $html;

			return;
		}

		session_destroy();

		header("location: ../home");
	}

	public function criar()
	{
		session_start();

		if (isset($_SESSION["user"])) {

			$links = file_get_contents(__DIR__ . "/../views/components/style.html");
			$header = file_get_contents(__DIR__ . "/../views/components/header-loged.html");
			$footer = file_get_contents(__DIR__ . "/../views/components/footer-loged.html");
			$html = file_get_contents(__DIR__ . "/../views/pages/user/createPost.html");

			$header = str_replace("{inicio}", "", $header);
			$header = str_replace("{recentes}", "", $header);
			$header = str_replace("{criar_postagem}", "navbar__link--active", $header);
			
			$html = str_replace("{script_style}", $links, $html);
			$html = str_replace("{component_header}", $header, $html);
			$html = str_replace("{component_footer}", $footer, $html);
			$html = str_replace("{include_path}", INCLUDE_PATH, $html);

			echo $html;

			return;
		}

		session_destroy();

		header("location: ../home");
	}

}