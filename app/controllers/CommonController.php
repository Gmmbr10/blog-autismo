<?php

class CommonController {

  public function index(array $data)
	{

		session_start();
		
		if (isset($_SESSION["user"])) {
			
			$links = file_get_contents(__DIR__ . "/../views/components/style.html");
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

			require_once __DIR__ . "/../core/Model.php";
			require_once __DIR__ . "/../models/PostModel.php";
			$model = new PostModel();
			$result = $model->listViews();

			$content = "";

			for ( $i = 0 ; $i < sizeof($result) ; $i++ ) {

				$content .= '<section class="post">
          <header class="post__header">
            <p>
              ' . $result[$i]["post_title"] . '
            </p>
          </header>

          <main class="post__content">
            '. $result[$i]["post_content"] .'
          </main>

          <footer class="post__footer">
            <p>' . $result[$i]["user_name"] . '</p>
          </footer>
        </section>';

			}

			$html = str_replace("{content}",$content,$html);

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

			require_once __DIR__ . "/../core/Model.php";
			require_once __DIR__ . "/../models/PostModel.php";
			$model = new PostModel();
			$result = $model->listPublish();

			$content = "";

			for ( $i = 0 ; $i < sizeof($result) ; $i++ ) {

				$content .= '<section class="post">
          <header class="post__header">
            <p>
              ' . $result[$i]["post_title"] . '
            </p>
          </header>

          <main class="post__content">
            '. $result[$i]["post_content"] .'
          </main>

          <footer class="post__footer">
            <p>' . $result[$i]["user_name"] . '</p>
          </footer>
        </section>';

			}

			$html = str_replace("{content}",$content,$html);

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
			$html = str_replace("{userId}", $_SESSION["user"]["user_id"], $html);
			$html = str_replace("{include_path}", INCLUDE_PATH, $html);

			echo $html;

			return;
		}

		session_destroy();

		header("location: ../home");
	}

}