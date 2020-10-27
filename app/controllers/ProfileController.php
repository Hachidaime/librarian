<?php

use app\controllers\Controller;
use app\helpers\Flasher;
use app\helpers\Functions;
use app\models\User;

class ProfileController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->setControllerAttribute(__CLASS__);
        $this->smarty->assign('title', $this->title);
        $this->smarty->assign('icon', 'fas fa-user-circle');
    }

    public function index()
    {
        $this->smarty->assign('breadcrumb', [[$this->title, '']]);

        $this->smarty->display("{$this->directory}/index.tpl");
    }

    public function search()
    {
        $log = app\models\Log::orderBy('created_at', 'desc')
            ->paginate(5, ['*'], 'page', $_POST['page'])
            ->toArray();

        [$list, $info] = Functions::paginationFormat($log);

        foreach ($list as $idx => $row) {
            $row['created_at'] = Functions::dateFormat(
                'Y-m-d H:i:s',
                'd/m/Y H.i.s',
                $row['created_at'],
            );

            $list[$idx] = $row;
        }

        echo json_encode([
            'list' => $list,
            'info' => $info,
        ]);
        exit();
    }

    /**
     * @desc this method will handle Data Uang form
     *
     * @method form
     * @param int $id is mata uang id
     */
    public function form()
    {
        $this->smarty->display("{$this->directory}/form.tpl");
    }

    public function detail()
    {
        $detail = User::where('id', $_POST['id'])->first();
        unset($detail['usr_password']);

        echo json_encode($detail);
        exit();
    }

    public function submit()
    {
        $data = $_POST;
        $detail = User::where('id', $_POST['id'])
            ->first()
            ->toArray();

        $data['usr_is_master'] = $detail['usr_is_master'];

        if ($this->validate($data)) {
            $data['usr_password'] = !empty($data['usr_password'])
                ? Functions::encrypt($data['usr_password'])
                : '';

            $user = User::find($data['id']);
            $user->usr_name = $_POST['usr_name'];
            $user->usr_username = $_POST['usr_username'];
            if (!empty($data['usr_password'])) {
                $user->usr_password = $_POST['usr_password'];
            }
            $result = $user->save();

            $tag = 'Ubah';
            $id = $data['id'];

            if ($result) {
                if ($id == $_SESSION['USER']['id']) {
                    $this->setUserSession($detail);
                }

                Flasher::setFlash(
                    "Berhasil {$tag} {$this->title}.",
                    $this->name,
                    'success',
                );
                $this->writeLog(
                    "{$tag} {$this->title}",
                    "{$tag} {$this->title} [{$id}] berhasil.",
                );
                echo json_encode(['success' => true]);
            } else {
                echo json_encode([
                    'success' => false,
                    'msg' => "Gagal {$tag} {$this->title}.",
                ]);
            }
            exit();
        }
    }

    public function validate($data)
    {
        $rules = [
            'usr_name' => 'required',
            'usr_username' =>
                'required|max:20|min:3|unique:' .
                User::getTableName() .
                ",id,{$data['id']}",
        ];

        if (empty($data['id'])) {
            $rules['usr_password'] = 'required|max:20|min:6';
        }

        $validation = $this->validator->make($data, $rules);

        $validation->setAliases([
            'usr_name' => 'Nama',
            'usr_username' => 'Username',
            'usr_password' => 'Password',
        ]);

        $validation->setMessages([
            'required' => '<strong>:attribute</strong> harus diisi.',
            'unique' => '<strong>:attribute</strong> sudah ada di database.',
            'min' => '<strong>:attribute</strong> minimum :min karakter.',
            'max' => '<strong>:attribute</strong> maximum :max karakter.',
        ]);

        $validation->validate();

        if ($validation->fails()) {
            echo json_encode([
                'success' => false,
                'msg' => $validation->errors()->firstOfAll(),
            ]);
            exit();
        }
        return true;
    }
}
