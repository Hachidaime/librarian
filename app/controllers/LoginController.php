<?php
use app\helpers\Flasher;
use app\controllers\Controller;
use app\models\User;

class LoginController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->smarty->assign('title', 'Log In');
    }

    /**
     * @desc this method will handle default Home page
     *
     * @method index
     */
    public function login()
    {
        if (isset($_SESSION['USER'])) {
            header('Location: ' . BASE_URL);
        }
        $this->smarty->display('Login/login.tpl');
    }

    public function submit()
    {
        if ($this->validate()) {
            $detail = User::select(
                'id',
                'usr_name',
                'usr_username',
                'usr_is_master',
            )
                ->where('usr_username', '=', $_POST['usr_username'])
                ->first()
                ->toArray();

            $this->setUserSession($detail);

            $this->writeLog('User Login', "User {$detail['usr_name']} login.");
            Flasher::setFlash(
                "Selamat Datang {$detail['usr_name']}",
                'LoginController',
                'success',
            );

            echo json_encode(['success' => true]);
            exit();
        }
    }

    private function validate()
    {
        $validation = $this->validator->make($_POST, [
            'usr_username' => 'required',
            'usr_password' => 'required|login:' . $_POST['usr_username'],
        ]);

        $validation->setAliases([
            'usr_username' => 'Username',
            'usr_password' => 'Password',
        ]);

        $validation->setMessages([
            'required' => '<strong>:attribute</strong> harus diisi.',
        ]);

        $validation->validate();

        if ($validation->fails()) {
            $result = [
                'success' => false,
                'msg' => $validation->errors()->firstOfAll(),
            ];
            echo json_encode($result);
            exit();
        }
        return true;
    }

    public function logout()
    {
        $this->writeLog(
            'User Logout',
            "User {$_SESSION['USER']['usr_name']} logout.",
        );
        session_destroy();
        header('Location: ' . BASE_URL);
    }
}
