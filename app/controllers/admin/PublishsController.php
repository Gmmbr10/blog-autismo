<?php

class PublishsController
{

    public function index(array $data)
    {

        $data = array_pop(explode("/", $_GET["url"]));

        $links = file_get_contents(__DIR__ . "/../../views/components/style.html");
        $header = file_get_contents(__DIR__ . "/../../views/components/header-admin.html");
        $navbar = file_get_contents(__DIR__ . "/../../views/components/navbar-admin.html");
        $footer = file_get_contents(__DIR__ . "/../../views/components/footer.html");
        $html = file_get_contents(__DIR__ . "/../../views/pages/admin/search.html");

        $html = str_replace("{script_style}", $links, $html);
        $html = str_replace("{component_header}", $header, $html);
        $html = str_replace("{component_navbar}", $navbar, $html);
        $html = str_replace("{component_footer}", $footer, $html);
        $html = str_replace("{user_img}", $_SESSION["user"]["admin_img"], $html);

        require_once __DIR__ . "/../../core/Model.php";
        require_once __DIR__ . "/../../models/PostModel.php";
        $model = new PostModel();

        if (is_numeric($data)) {

            $result = $model->list($data);

            if (!is_array($result)) {
                header("location:" . INCLUDE_PATH . "/admin/publishs");
                return;
            }

            $html = file_get_contents(__DIR__ . "/../../views/pages/admin/post.html");
            $header = file_get_contents(__DIR__ . "/../../views/components/header-admin.html");
            $navbar = file_get_contents(__DIR__ . "/../../views/components/navbar-admin.html");
            $footer = file_get_contents(__DIR__ . "/../../views/components/footer.html");
            $html = str_replace("{component_navbar}", $navbar, $html);
            $html = str_replace("{component_header}", $header, $html);
            $html = str_replace("{component_footer}", $footer, $html);
            $html = str_replace("{script_style}", $links, $html);

            $tags_db = explode(",", $result["post_tags"]);
            $tags_html = "";

            for ($i = 0; $i < sizeof($tags_db); $i++) {

                $tags_html .= '<span class="tags__tag">' . $tags_db[$i] . '</span>';
            }

            $html = str_replace("{tags}", $tags_html, $html);

            $html = str_replace("{title}", $result["post_title"], $html);
            $html = str_replace("{content}", $result["post_content"], $html);
            $html = str_replace("{content}", $result["post_content"], $html);
            $html = str_replace("{user_img}", $_SESSION["user"]["admin_img"], $html);
            $html = str_replace("{author_img}", $result["user_img"], $html);
            $html = str_replace("{type}", $result["user_type"], $html);
            $html = str_replace("{author}", $result["user_name"], $html);
            $html = str_replace("{include_path}", INCLUDE_PATH, $html);

            echo $html;
            return;
        }

        if (isset($_POST["search"])) {

            $search = array_shift($_POST);
            $tags = $_POST;

            if (empty($search) and empty($tags)) {
                header("location:" . INCLUDE_PATH . "/common/buscar");
            }

            $result = $model->search($search, $tags);

            if (sizeof($result) == 0) {

                $content = "Nenhum registro encontrado";
            } else {

                for ($i = 0; $i < sizeof($result); $i++) {

                    $content .= '<section class="post" onclick="window.location.href = \'{include_path}/admin/publishs/' . $result[$i]["post_id"] . '\'">
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

        $result = $model->listPublish();

        $content = "";

        if (!is_array($result)) {

            $content = "Nenhum registro encontrado";
        } else {

            for ($i = 0; $i < sizeof($result); $i++) {

                $content .= '<section class="post" onclick="window.location.href = \'{include_path}/admin/publishs/' . $result[$i]["post_id"] . '\'">
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
}
