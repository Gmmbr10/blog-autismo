<?php

class BlogController
{

	public function index(array $data)
	{

		$links = file_get_contents(__DIR__ . "/../views/components/style.html");
		$header = file_get_contents(__DIR__ . "/../views/components/header-default.html");
		$footer = file_get_contents(__DIR__ . "/../views/components/footer.html");
		$html = file_get_contents(__DIR__ . "/../views/pages/blog.html");

		$header = str_replace("{inicio}", "", $header);
		$header = str_replace("{blog}", "navbar__link--active", $header);
		$header = str_replace("{about}", "", $header);

		$html = str_replace("{script_style}", $links, $html);
		$html = str_replace("{component_header}", $header, $html);
		$html = str_replace("{component_footer}", $footer, $html);

		require_once __DIR__ . "/../core/Model.php";
		require_once __DIR__ . "/../models/PostModel.php";
		$model = new PostModel();
		$result = $model->listPublish();

		$content = "";

		if (!is_array($result)) {

			$content = "Nenhum registro encontrado";
		} else {

			for ($i = 0; $i < sizeof($result); $i++) {

				$content .= '<section class="post" onclick="window.location.href = \'{include_path}/blog/post/' . $result[$i]["post_id"] . '\'">
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
	}

	public function post(array $data)
	{

		$links = file_get_contents(__DIR__ . "/../views/components/style.html");
		$header = file_get_contents(__DIR__ . "/../views/components/header-default.html");
		$footer = file_get_contents(__DIR__ . "/../views/components/footer.html");
		$html = file_get_contents(__DIR__ . "/../views/pages/post.html");

		$header = str_replace("{inicio}", "", $header);
		$header = str_replace("{blog}", "navbar__link--active", $header);
		$header = str_replace("{about}", "", $header);

		$html = str_replace("{script_style}", $links, $html);
		$html = str_replace("{component_header}", $header, $html);
		$html = str_replace("{component_footer}", $footer, $html);

		require_once __DIR__ . "/../core/Model.php";
		require_once __DIR__ . "/../models/PostModel.php";
		$model = new PostModel();
		$result = $model->list($data[0]);

		if (!is_array($result)) {
			header("location: ../");
			return;
		}

		$html = str_replace("{title}", $result["post_title"], $html);
		$html = str_replace("{content}", $result["post_content"], $html);
		$html = str_replace("{content}", $result["post_content"], $html);
		$html = str_replace("{author}", $result["user_name"], $html);
		$html = str_replace("{author_img}",$result["user_img"],$html);

		$html = str_replace("{include_path}", INCLUDE_PATH, $html);
		echo $html;
	}
}
