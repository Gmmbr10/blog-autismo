<?php

class ProfileController
{

    public function index(array $data)
    {
        $links = file_get_contents(__DIR__ . "/../../views/components/style.html");
		$header = file_get_contents(__DIR__ . "/../../views/components/header-admin-profile.html");
		$footer = file_get_contents(__DIR__ . "/../../views/components/footer.html");
        $html = file_get_contents(__DIR__ . "/../../views/pages/profile.html");

        $header = str_replace("{inicio}", "", $header);
        $header = str_replace("{recentes}", "", $header);
        $header = str_replace("{criar_postagem}", "", $header);

        $html = str_replace("{script_style}", $links, $html);
        $html = str_replace("{component_header}", $header, $html);
        $html = str_replace("{component_footer}", $footer, $html);

        $html = str_replace("{userId}", $_SESSION["user"]["admin_id"], $html);
        $html = str_replace("{user_name}", $_SESSION["user"]["admin_name"], $html);
        $html = str_replace("{user_email}", $_SESSION["user"]["admin_email"], $html);
        $html = str_replace("{user_img}", $_SESSION["user"]["admin_img"], $html);

        $html = str_replace("{include_path}/common/profile", "{include_path}/admin/profile", $html);
        $html = str_replace("{link_api}", "{include_path}/api/admin", $html);

		$html = str_replace("{disable_button}", '', $html);
        $html = str_replace("{include_path}", INCLUDE_PATH, $html);

        echo $html;
    }
}
