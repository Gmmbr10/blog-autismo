<?php

class AddController
{

	public function index(array $data)
	{

        
        $links = file_get_contents(__DIR__ . "/../../views/components/style.html");
		$header = file_get_contents(__DIR__ . "/../../views/components/header-admin.html");
		$navbar = file_get_contents(__DIR__ . "/../../views/components/navbar-admin.html");
		$footer = file_get_contents(__DIR__ . "/../../views/components/footer.html");
		$html = file_get_contents(__DIR__ . "/../../views/pages/admin/createAdmin.html");
        
		$html = str_replace( "{script_style}" , $links , $html );
		$html = str_replace( "{component_header}" , $header , $html );
		$html = str_replace( "{component_navbar}" , $navbar , $html );
		$html = str_replace( "{component_footer}" , $footer , $html );
		$html = str_replace("{user_img}", $_SESSION["user"]["admin_img"], $html);

        if ( isset($_POST["nome"]) ) {

            $this->post($_POST["nome"],$_POST["email"]);

            $html = str_replace( "{create_admin}" , '<script>createModal(
                true,
                "Administrador criado com sucesso! Senha padrão: admin",
                "{include_path}/admin/home"
                );</script>' , $html );
        } else {
            $html = str_replace( "{create_admin}" , '' , $html );
        }
        
        $html = str_replace( "{include_path}" , INCLUDE_PATH , $html );

		echo $html;

	}

    private function post($name,$email)
    {

        require_once __DIR__ . "/../../models/AdminModel.php";
        $model = new AdminModel();
        $password = password_hash("admin",PASSWORD_BCRYPT);
        $result = $model->create($name,$email,$password);
        
    }

}