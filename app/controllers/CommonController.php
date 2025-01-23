<?php

class CommonController
{

	public function __construct()
	{
		session_start();

		if (!isset($_SESSION["user"])) {
			header("location:" . INCLUDE_PATH . "/login");
			return;
		}
	}

	public function index(array $data)
	{

		session_start();

		if (empty($_SESSION["user"]["user_type"])) {
			header("location:" . INCLUDE_PATH . "/common/type");
			return;
		}

		$links = file_get_contents(__DIR__ . "/../views/components/style.html");
		$header = file_get_contents(__DIR__ . "/../views/components/header-loged.html");
		$footer = file_get_contents(__DIR__ . "/../views/components/footer-loged.html");
		$html = file_get_contents(__DIR__ . "/../views/pages/user/home.html");

		if (sizeof($data) != 0) {
			$html = file_get_contents(__DIR__ . "/../views/pages/user/post.html");
			$navbar = file_get_contents(__DIR__ . "/../views/components/navbar-recentes.html");
			$html = str_replace("{component_navbar}", $navbar, $html);
		}

		$header = str_replace("{inicio}", "navbar__link--active", $header);
		$header = str_replace("{recentes}", "", $header);
		$header = str_replace("{criar_postagem}", "", $header);

		$html = str_replace("{script_style}", $links, $html);
		$html = str_replace("{component_header}", $header, $html);
		$html = str_replace("{component_footer}", $footer, $html);
		$html = str_replace("{user_img}", $_SESSION["user"]["user_img"], $html);
		$html = str_replace("{include_path}", INCLUDE_PATH, $html);

		require_once __DIR__ . "/../core/Model.php";
		require_once __DIR__ . "/../models/PostModel.php";
		$model = new PostModel();

		if (sizeof($data) != 0) {

			$result = $model->list($data[0]);

			if (!is_array($result)) {
				header("location:" . INCLUDE_PATH . "/common");
				return;
			}

			$tags_db = explode(",", $result["post_tags"]);
			$tags_html = "";

			for ($i = 0; $i < sizeof($tags_db); $i++) {

				$tags_html .= '<span class="tags__tag">' . $tags_db[$i] . '</span>';
			}

			$html = str_replace("{tags}", $tags_html, $html);

			$html = str_replace("{title}", $result["post_title"], $html);
			$html = str_replace("{content}", $result["post_content"], $html);
			$html = str_replace("{content}", $result["post_content"], $html);
			$html = str_replace("{author}", $result["user_name"], $html);
			$html = str_replace("{type}", $result["user_type"], $html);
			$html = str_replace("{author_img}", $result["user_img"], $html);
			$html = str_replace("{include_path}", INCLUDE_PATH, $html);

			echo $html;
			return;
		}

		$result = $model->listViews();

		$content = "";

		if (!is_array($result)) {

			$content = "Nenhum registro encontrado";
		} else {

			for ($i = 0; $i < sizeof($result); $i++) {

				$content .= '<section class="post" onclick="window.location.href = \'{include_path}/common/' . $result[$i]["post_id"] . '\'">
					<header class="post__header">
					<p>
					' . $result[$i]["post_title"] . '
					</p>
					</header>
					
					<main class="post__content">
					' . $result[$i]["post_content"] . '
					</main>
					
					<footer class="post__footer">
					<p>' . $result[$i]["user_name"] . '</p>
					</footer>
					</section>';
			}
		}

		$html = str_replace("{content}", $content, $html);
		$html = str_replace("{include_path}", INCLUDE_PATH, $html);

		echo $html;

		return;
	}

	public function recentes(array $data)
	{
		session_start();

		if (empty($_SESSION["user"]["user_type"])) {
			header("location:" . INCLUDE_PATH . "/common/type");
			return;
		}

		$links = file_get_contents(__DIR__ . "/../views/components/style.html");
		$header = file_get_contents(__DIR__ . "/../views/components/header-loged.html");
		$footer = file_get_contents(__DIR__ . "/../views/components/footer-loged.html");
		$html = file_get_contents(__DIR__ . "/../views/pages/user/newPosts.html");

		if (sizeof($data) != 0) {
			$html = file_get_contents(__DIR__ . "/../views/pages/user/post.html");
			$navbar = file_get_contents(__DIR__ . "/../views/components/navbar-publish.html");
			$html = str_replace("{component_navbar}", $navbar, $html);
		}

		$header = str_replace("{inicio}", "", $header);
		$header = str_replace("{recentes}", "navbar__link--active", $header);
		$header = str_replace("{criar_postagem}", "", $header);

		$html = str_replace("{script_style}", $links, $html);
		$html = str_replace("{component_header}", $header, $html);
		$html = str_replace("{component_footer}", $footer, $html);
		$html = str_replace("{include_path}", INCLUDE_PATH, $html);

		require_once __DIR__ . "/../core/Model.php";
		require_once __DIR__ . "/../models/PostModel.php";
		$model = new PostModel();

		if (sizeof($data) != 0) {

			$result = $model->list($data[0]);

			if (!is_array($result)) {
				header("location:" . INCLUDE_PATH . "/common/recentes");
				return;
			}

			$tags_db = explode(",", $result["post_tags"]);
			$tags_html = "";

			for ($i = 0; $i < sizeof($tags_db); $i++) {

				$tags_html .= '<span class="tags__tag">' . $tags_db[$i] . '</span>';
			}

			$html = str_replace("{tags}", $tags_html, $html);

			$html = str_replace("{title}", $result["post_title"], $html);
			$html = str_replace("{content}", $result["post_content"], $html);
			$html = str_replace("{content}", $result["post_content"], $html);
			$html = str_replace("{user_img}", $_SESSION["user"]["user_img"], $html);
			$html = str_replace("{author_img}", $result["user_img"], $html);
			$html = str_replace("{type}", $result["user_type"], $html);
			$html = str_replace("{author}", $result["user_name"], $html);

			echo $html;
			return;
		}

		$result = $model->listPublish();

		$content = "";

		if (!is_array($result)) {

			$content = "Nenhum registro encontrado";
		} else {

			for ($i = 0; $i < sizeof($result); $i++) {

				$content .= '<section class="post" onclick="window.location.href = \'{include_path}/common/recentes/' . $result[$i]["post_id"] . '\'">
						<header class="post__header">
							<p>
							' . $result[$i]["post_title"] . '
							</p>
						</header>
	
						<main class="post__content">
							' . $result[$i]["post_content"] . '
						</main>
	
						<footer class="post__footer">
							<p>' . $result[$i]["user_name"] . '</p>
						</footer>
					</section>';
			}
		}


		$html = str_replace("{content}", $content, $html);
		$html = str_replace("{user_img}", $_SESSION["user"]["user_img"], $html);
		$html = str_replace("{include_path}", INCLUDE_PATH, $html);

		echo $html;

		return;
	}

	public function criar()
	{
		session_start();

		if (empty($_SESSION["user"]["user_type"])) {
			header("location:" . INCLUDE_PATH . "/common/type");
			return;
		}

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
		$html = str_replace("{user_img}", $_SESSION["user"]["user_img"], $html);
		$html = str_replace("{userId}", $_SESSION["user"]["user_id"], $html);
		$html = str_replace("{include_path}", INCLUDE_PATH, $html);

		echo $html;

		return;
	}

	public function publishs(array $data)
	{

		session_start();

		if (empty($_SESSION["user"]["user_type"])) {
			header("location:" . INCLUDE_PATH . "/common/type");
			return;
		}

		$links = file_get_contents(__DIR__ . "/../views/components/style.html");
		$header = file_get_contents(__DIR__ . "/../views/components/header-loged.html");
		$footer = file_get_contents(__DIR__ . "/../views/components/footer-loged.html");
		$html = file_get_contents(__DIR__ . "/../views/pages/user/publishs.html");

		$header = str_replace("{inicio}", "", $header);
		$header = str_replace("{recentes}", "", $header);
		$header = str_replace("{criar_postagem}", "", $header);

		$html = str_replace("{script_style}", $links, $html);
		$html = str_replace("{component_header}", $header, $html);
		$html = str_replace("{component_footer}", $footer, $html);
		$html = str_replace("{user_img}", $_SESSION["user"]["user_img"], $html);
		$html = str_replace("{userId}", $_SESSION["user"]["user_id"], $html);

		require_once __DIR__ . "/../core/Model.php";
		require_once __DIR__ . "/../models/PostModel.php";
		$model = new PostModel();
		$result = $model->myPublishs($_SESSION["user"]["user_id"]);

		$content = "";

		if (!is_array($result)) {

			$content = "Nenhuma postagem encontrada";
		} else {

			for ($i = 0; $i < sizeof($result); $i++) {

				$situacao = "";
				$redirect = "";

				switch ($result[$i]["review_auth"]) {
					case '1':
						$situacao = '<span class="post--aprove" >Aprovado</span>';
						break;
					case '2':
						$situacao = '<span class="post--reprove" >Reprovado</span>';
						$redirect = 'onclick="window.location.href = \'{include_path}/common/edit/' . $result[$i]["post_id"] . '\'"';
						break;
					default:
						$situacao = '<span class="post--waiting" >Aguardando</span>';
						break;
				}

				$content .= '<section class="post" ' . $redirect . '>
						' . $situacao . '
						<header class="post__header">
							<p>
							' . $result[$i]["post_title"] . '
							</p>
						</header>
	
						<main class="post__content">
							' . $result[$i]["post_content"] . '
						</main>
	
						<footer class="post__footer">
							<p>' . $result[$i]["user_name"] . '</p>
						</footer>
					</section>';
			}
		}

		$html = str_replace("{content}", $content, $html);
		$html = str_replace("{include_path}", INCLUDE_PATH, $html);
		echo $html;

		return;
	}

	public function profile(array $data)
	{

		session_start();

		if (empty($_SESSION["user"]["user_type"])) {
			header("location:" . INCLUDE_PATH . "/common/type");
			return;
		}

		$links = file_get_contents(__DIR__ . "/../views/components/style.html");
		$header = file_get_contents(__DIR__ . "/../views/components/header-loged.html");
		$footer = file_get_contents(__DIR__ . "/../views/components/footer-loged.html");
		$html = file_get_contents(__DIR__ . "/../views/pages/profile.html");
		$modal_disable = file_get_contents(__DIR__ . "/../views/components/modal-disable.html");

		$header = str_replace("{inicio}", "", $header);
		$header = str_replace("{recentes}", "", $header);
		$header = str_replace("{criar_postagem}", "", $header);

		$html = str_replace("{script_style}", $links, $html);
		$html = str_replace("{component_header}", $header, $html);
		$html = str_replace("{component_footer}", $footer, $html);
		$html = str_replace("{userId}", $_SESSION["user"]["user_id"], $html);
		$html = str_replace("{user_name}", $_SESSION["user"]["user_name"], $html);
		$html = str_replace("{user_email}", $_SESSION["user"]["user_email"], $html);
		$html = str_replace("{user_img}", $_SESSION["user"]["user_img"], $html);

		$html = str_replace("{link_api}", "{include_path}/api/user", $html);

		$html = str_replace("{disable_button}", '<button class="btn btn--radius-none btn--warning" onclick="disable(\'{include_path}/common/disable\')">Desabilitar a conta</button>', $html);
		$html = str_replace("{script_disable}", '<script src="{include_path}/public/assets/script/disableFunction.js"></script>', $html);
		$html = str_replace("{modal_disable}", $modal_disable, $html);

		$html = str_replace("{include_path}", INCLUDE_PATH, $html);

		echo $html;

		return;
	}

	public function edit(array $data)
	{
		session_start();

		if (empty($_SESSION["user"]["user_type"])) {
			header("location:" . INCLUDE_PATH . "/common/type");
			return;
		}

		$links = file_get_contents(__DIR__ . "/../views/components/style.html");
		$header = file_get_contents(__DIR__ . "/../views/components/header-loged.html");
		$footer = file_get_contents(__DIR__ . "/../views/components/footer-loged.html");
		$html = file_get_contents(__DIR__ . "/../views/pages/user/editReprove.html");

		$header = str_replace("{inicio}", "", $header);
		$header = str_replace("{recentes}", "", $header);
		$header = str_replace("{criar_postagem}", "", $header);

		require_once __DIR__ . "/../core/Model.php";
		require_once __DIR__ . "/../models/PostModel.php";
		$model = new PostModel();
		$result = $model->myPublish($_SESSION["user"]["user_id"], $data[0]);

		if ($result == false) {
			header("location: " . INCLUDE_PATH . "/common/publishs");
			return;
		}

		$tags = explode(",", $result["post_tags"]);

		for ($i = 0; $i < sizeof($tags); $i++) {

			switch ($tags[$i]) {
				case "Dica":
					$html = str_replace("{dica}", "checked", $html);
					break;
				case "Comportamento":
					$html = str_replace("{comp}", "checked", $html);
					break;
				case "Pedagogia":
					$html = str_replace("{peda}", "checked", $html);
					break;
				case "Relato":
					$html = str_replace("{rela}", "checked", $html);
					break;
				case "Comunicação":
					$html = str_replace("{comu}", "checked", $html);
					break;
				default:
					break;
			}
		}

		$html = str_replace("{script_style}", $links, $html);
		$html = str_replace("{component_header}", $header, $html);
		$html = str_replace("{component_footer}", $footer, $html);
		$html = str_replace("{user_img}", $_SESSION["user"]["user_img"], $html);
		$html = str_replace("{userId}", $_SESSION["user"]["user_id"], $html);
		$html = str_replace("{postId}", $result["post_id"], $html);
		$html = str_replace("{content}", $result["post_content"], $html);
		$html = str_replace("{title}", $result["post_title"], $html);
		$html = str_replace("{msg}", $result["review_message"], $html);
		$html = str_replace("{include_path}", INCLUDE_PATH, $html);

		echo $html;

		return;
	}

	public function type(array $data)
	{

		session_start();

		if (!empty($_SESSION["user"]["user_type"])) {
			header("location:" . INCLUDE_PATH . "/common");
			return;
		}

		$links = file_get_contents(__DIR__ . "/../views/components/style.html");
		$header = file_get_contents(__DIR__ . "/../views/components/header-loged.html");
		$footer = file_get_contents(__DIR__ . "/../views/components/footer-loged.html");
		$html = file_get_contents(__DIR__ . "/../views/pages/user/type.html");

		$header = str_replace("{inicio}", "", $header);
		$header = str_replace("{recentes}", "", $header);
		$header = str_replace("{criar_postagem}", "", $header);
		$html = str_replace("{script_style}", $links, $html);
		$html = str_replace("{component_header}", $header, $html);
		$html = str_replace("{component_footer}", $footer, $html);
		$html = str_replace("{user_img}", $_SESSION["user"]["user_img"], $html);
		$html = str_replace("{userId}", $_SESSION["user"]["user_id"], $html);
		$html = str_replace("{include_path}", INCLUDE_PATH, $html);

		echo $html;

		return;
	}

	public function disable(array $data)
	{

		session_start();

		if (empty($_SESSION["user"]["user_type"])) {
			header("location:" . INCLUDE_PATH . "/common/type");
			return;
		}

		require_once __DIR__ . "/../core/Model.php";
		require_once __DIR__ . "/../models/UserModel.php";
		$model = new UserModel();
		$result = $model->disable($_SESSION["user"]["user_id"]);

		session_destroy();

		header("location: ".INCLUDE_PATH."/home");
		
	}
}
