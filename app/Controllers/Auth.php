<?php
namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function register()
    {
        log_message('debug','Register method called, method: {m}', ['m'=>$this->request->getMethod()]);
        log_message('debug','POST data: {d}', ['d'=>json_encode($this->request->getPost())]);
        log_message('debug','Request URI: {u}', ['u'=>$this->request->getUri()]);
        
        if ($this->request->getMethod() === 'post' || $this->request->getPost()) {
            $rules = [
                'name'             => 'required|min_length[3]',
                'email'            => 'required|valid_email|is_unique[users.email]',
                'password'         => 'required|min_length[8]',
                'password_confirm' => 'required|matches[password]',
            ];
            if (! $this->validate($rules)) {
                log_message('error','Register validation failed: {err}', ['err'=>$this->validator->listErrors()]);
                $data = ['title' => 'Register', 'validation' => $this->validator];
                return view('auth/register', $data);
            }

            $data = [
                'name'     => $this->request->getPost('name'),
                'email'    => $this->request->getPost('email'),
                'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'role'     => 'user',
            ];

            $users = model(\App\Models\UserModel::class);
            $insertResult = $users->insert($data);
            log_message('debug','Insert result: {r}', ['r'=>$insertResult ? 'SUCCESS' : 'FAILED']);
            
            if (! $insertResult) {
                log_message('error','UserModel insert errors: {e}', ['e'=>json_encode($users->errors())]);
                $db = \Config\Database::connect();
                log_message('error','DB error: {e}', ['e'=>json_encode($db->error())]);
                return redirect()->back()->withInput()->with('error','Failed to create account.');
            }

            log_message('debug','Register success for email {e}', ['e'=>$data['email']]);
            return redirect()->to(site_url('login'))->with('success','Account created. Please log in.');
        }

        $data = ['title' => 'Register'];
        return view('auth/register', $data);
    }


    public function login()
    {
        log_message('debug','Login method called, method: {m}', ['m'=>$this->request->getMethod()]);
        log_message('debug','Login POST data: {d}', ['d'=>json_encode($this->request->getPost())]);
        if ($this->request->getMethod() === 'post' || $this->request->getPost()) {
            $rules = ['email'=>'required|valid_email','password'=>'required'];
            if (! $this->validate($rules)) {
                log_message('debug','Login validation failed: {err}', ['err'=>$this->validator->listErrors()]);
                $data = ['title' => 'Login', 'validation' => $this->validator];
                return view('auth/login', $data);
            }

            $email = $this->request->getPost('email');
            $pass  = $this->request->getPost('password');
            $user  = model(\App\Models\UserModel::class)->where('email',$email)->first();

            if (! $user) {
                log_message('debug','Login no user for email {e}', ['e'=>$email]);
                return redirect()->back()->withInput()->with('error','No account found for that email.');
            }
            if (! password_verify($pass, $user['password'])) {
                log_message('debug','Login bad password for email {e}', ['e'=>$email]);
                return redirect()->back()->withInput()->with('error','Invalid email or password.');
            }

            session()->regenerate();
            session()->set([
                'user_id'    => $user['id'],
                'name'       => $user['name'],
                'email'      => $user['email'],
                'role'       => $user['role'] ?? 'student',
                'isLoggedIn' => true,
            ]);

            $to = session('redirect_url') ?? site_url('dashboard');
            session()->remove('redirect_url');
            log_message('debug','Login success for email {e}, redirecting to {r}', ['e'=>$email,'r'=>$to]);

            return redirect()->to($to)->with('success','Welcome, '.$user['name'].'!');
        }

        $data = ['title' => 'Login'];
        return view('auth/login', $data);
    }

    private function requireLogin(): bool
    {
        if (! session()->get('isLoggedIn')) {
            session()->set('redirect_url', current_url());
            return false;
        }
        return true;
    }

    public function dashboard()
    {
        log_message('debug','Dashboard accessed, isLoggedIn: {s}', ['s'=>session()->get('isLoggedIn') ? 'true' : 'false']);
        if (! $this->requireLogin()) {
            log_message('debug','Dashboard guard: not logged in, back to login');
            return redirect()->to(site_url('login'))->with('error','Please log in first.');
        }

        $role = session('role') ?? 'student';
        $db   = \Config\Database::connect();

        $data = ['title' => 'Dashboard', 'role' => $role];

        $count = function(string $table) use ($db): int {
            try {
                if (method_exists($db, 'tableExists') && ! $db->tableExists($table)) return 0;
                return (int) $db->table($table)->countAllResults();
            } catch (\Throwable $e) {
                return 0;
            }
        };

        if ($role === 'admin') {
            $data['stats'] = [
                'users'   => $count('users'),
                'courses' => $count('courses'),
                'lessons' => $count('lessons'),
            ];
        } elseif ($role === 'teacher') {
            $data['stats'] = [
                'my_courses' => $count('courses'),
                'quizzes'    => $count('quizzes'),
            ];
        } else {
            $data['stats'] = [
                'enrolled' => $count('enrollments'),
                'quizzes'  => $count('quizzes'),
            ];
        }

        return view('auth/dashboard', $data);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(site_url('login'))->with('success','You have been logged out.');
    }

    public function debug()
    {
        echo "<h3>Auth Debug Info</h3>";
        echo "<p>Views exist: " . (file_exists(APPPATH . 'Views/auth/register.php') ? 'YES' : 'NO') . "</p>";
        echo "<p>Session started: " . (session_status() === PHP_SESSION_ACTIVE ? 'YES' : 'NO') . "</p>";
        echo "<p>Current user count: ";
        try {
            $users = model(\App\Models\UserModel::class);
            echo $users->countAll();
        } catch(\Exception $e) {
            echo "ERROR: " . $e->getMessage();
        }
        echo "</p>";
        echo "<p>Session data: " . json_encode(session()->get()) . "</p>";
    }
}
