<?php

use app\controllers\Controller;
use app\helpers\Flasher;
use app\helpers\Functions;
use app\models\User;

/**
 * @desc this class will handle Uang controller
 *
 * @class BankController
 * @extends Controller
 * @author Hachidaime
 */

class UserController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->setControllerAttribute(__CLASS__);
        $this->smarty->assign('title', $this->title);

        if (!$_SESSION['USER']['usr_is_master']) {
            header('Location:' . BASE_URL . '/403');
        }
    }

    public function index()
    {
        $this->smarty->assign('breadcrumb', [
            ['Master', ''],
            [$this->title, ''],
        ]);

        $this->smarty->assign('subtitle', "Daftar {$this->title}");

        $this->smarty->display("{$this->directory}/index.tpl");
    }

    public function search()
    {
        $page = $_POST['page'] ?? 1;
        $keyword = $_POST['keyword'] ?? null;

        $user = User::where('id', '>', 0);
        if (!is_null($keyword)) {
            $user = $user
                ->where('usr_name', 'LIKE', "%{$keyword}%")
                ->orWhere('usr_username', 'LIKE', "%{$keyword}%");
        }
        $user = $user
            ->orderBy('usr_name', 'asc')
            ->paginate(ROWS_PER_PAGE, ['*'], 'page', $page)
            ->toArray();

        [$list, $info] = Functions::paginationFormat($user);
        $info['keyword'] = $keyword;

        foreach ($list as $idx => $row) {
            unset($list[$idx]['usr_password']);
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
    public function form(int $id = null)
    {
        $tag = 'Tambah';
        if (!is_null($id)) {
            $count = User::where('id', $id)->count();
            if (!$count) {
                Flasher::setFlash(
                    'Data tidak ditemukan!',
                    $this->name,
                    'error',
                );
                header('Location: ' . BASE_URL . "/{$this->lowerName}");
            }

            $tag = 'Ubah';
            $this->smarty->assign('id', $id);
        }

        $this->smarty->assign('breadcrumb', [
            ['Master', ''],
            [$this->title, $this->lowerName],
            [$tag, ''],
        ]);

        $this->smarty->assign('subtitle', "{$tag} {$this->title}");

        $this->smarty->display("{$this->directory}/form.tpl");
    }

    public function detail()
    {
        $detail = User::where('id', $_POST['id'])
            ->first()
            ->toArray();
        unset($detail['usr_password']);

        echo json_encode($detail);
        exit();
    }

    public function submit()
    {
        $data = $_POST;
        $data['usr_is_master'] = $data['usr_is_master'] ?? 0;

        if ($this->validate($data)) {
            $data['usr_password'] = !empty($data['usr_password'])
                ? Functions::encrypt($data['usr_password'])
                : '';
            if (empty($data['usr_password'])) {
                unset($data['usr_password']);
            }

            if ($data['id'] > 0) {
                $user = User::find($data['id']);
                $tag = 'Ubah';
            } else {
                $user = new User();
                $tag = 'Tambah';
            }

            $user->usr_name = $data['usr_name'];
            $user->usr_username = $data['usr_username'];
            if (!empty($data['usr_password'])) {
                $user->usr_password = $data['usr_password'];
            }
            $user->usr_is_master = $data['usr_is_master'];
            $result = $user->save();

            $id = $data['id'] > 0 ? $data['id'] : $user->id;

            if ($result) {
                $detail = User::where('id', $id)
                    ->first()
                    ->toArray();

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

    public function remove()
    {
        $id = (int) $_POST['id'];
        $tag = 'Hapus';
        $result = User::destroy($id);

        if ($result) {
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
