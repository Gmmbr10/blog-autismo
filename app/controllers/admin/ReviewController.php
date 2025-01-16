<?php

require_once __DIR__ . "/../../core/Model.php";

class ReviewController
{

	public function index(array $data)
	{

		$links = file_get_contents(__DIR__ . "/../../views/components/style.html");
		$header = file_get_contents(__DIR__ . "/../../views/components/header-admin.html");
		$navbar = file_get_contents(__DIR__ . "/../../views/components/navbar-admin.html");
		$footer = file_get_contents(__DIR__ . "/../../views/components/footer.html");
		$html = file_get_contents(__DIR__ . "/../../views/pages/admin/reviews.html");

		$html = str_replace( "{script_style}" , $links , $html );
		$html = str_replace( "{component_header}" , $header , $html );
		$html = str_replace( "{component_navbar}" , $navbar , $html );
		$html = str_replace( "{component_footer}" , $footer , $html );
		$html = str_replace("{user_img}", $_SESSION["user"]["admin_img"], $html);
		
        require_once(__DIR__ . "/../../models/AdminModel.php");
        $model = new AdminModel();
		$result = $model->postsNotReview();
		
		if ( $result == false ) {

			$content = "Nenhuma postagem encontrada para revisão";
			
		} else {
			
			$content = "";

			for ( $index = 0 ; $index < sizeof($result) ; $index++ ) {

				if ( $result[$index]["review_auth"] == 1 || $result[$index]["review_auth"] == 2 ) {
					continue;
				}
				
				$content .= '<section class="post" onclick="window.location.href = \'{include_path}/admin/review/search/'. $result[$index]["post_id"] .'\'">
					<header class="post__header">
						<p>
						' . $result[$index]["post_title"] . '
						</p>
					</header>
					
					<main class="post__content">
						'. $result[$index]["post_content"] .'
					</main>
					
					<footer class="post__footer">
						<p>' . $result[$index]["user_name"] . '</p>
					</footer>
				</section>';

			}
			
		}

		if ( empty($content) ) {
			$content = "Nenhuma postagem encontrada para revisão";
		}
		
		$html = str_replace("{content}", $content, $html);
        $html = str_replace("{include_path}", INCLUDE_PATH, $html);

		echo $html;

	}

	public function search(array $data)
	{

		$links = file_get_contents(__DIR__ . "/../../views/components/style.html");
		$header = file_get_contents(__DIR__ . "/../../views/components/header-admin.html");
		$navbar = file_get_contents(__DIR__ . "/../../views/components/navbar-admin.html");
		$footer = file_get_contents(__DIR__ . "/../../views/components/footer.html");
		$html = file_get_contents(__DIR__ . "/../../views/pages/admin/review.html");

		$html = str_replace( "{script_style}" , $links , $html );
		$html = str_replace( "{component_header}" , $header , $html );
		$html = str_replace( "{component_navbar}" , $navbar , $html );
		$html = str_replace( "{component_footer}" , $footer , $html );
		
		require_once __DIR__ . "/../../models/PostModel.php";
		$model = new PostModel();
		$result = $model->list($data[0],true);

		if ( $result["review_auth"] == 1 || $result["review_auth"] == 2 ) {
			header("location:" . INCLUDE_PATH . "/admin/review");
			return;
		}

		$html = str_replace("{title}",$result["post_title"],$html);
		$html = str_replace("{content}",$result["post_content"],$html);
		$html = str_replace("{content}",$result["post_content"],$html);
		$html = str_replace("{author}",$result["user_name"],$html);
		$html = str_replace("{author_img}",$result["user_img"],$html);
		$html = str_replace("{id}",$result["post_id"],$html);
		$html = str_replace("{user_img}", $_SESSION["user"]["admin_img"], $html);
		
        $html = str_replace("{include_path}", INCLUDE_PATH, $html);
		echo $html;

	}

}