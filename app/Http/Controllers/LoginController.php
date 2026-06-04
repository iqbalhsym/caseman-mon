<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use LdapRecord\Models\ActiveDirectory\User as LdapUser;

class LoginController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard.index');
        }

        $captcha = $this->generateCaptchaData();

        return view('login', [
            'captcha_image' => $captcha['image']
        ]);
    }

    public function refreshCaptcha()
    {
        $captcha = $this->generateCaptchaData();
        return response()->json([
            'captcha_image' => $captcha['image']
        ]);
    }

    private function generateCaptchaData()
    {
        $num1 = rand(10, 50);
        $num2 = rand(1, 10);
        $operators = ['+', '-'];
        $operator = $operators[array_rand($operators)];

        if ($operator === '-') {
            if ($num1 < $num2) {
                $temp = $num1;
                $num1 = $num2;
                $num2 = $temp;
            }
            $answer = $num1 - $num2;
        } else {
            $answer = $num1 + $num2;
        }

        session(['captcha_answer' => $answer]);
        $captcha_question = "$num1 $operator $num2 =";

        // Create Image
        $width = 160;
        $height = 50;
        $image = imagecreatetruecolor($width, $height);

        // Colors
        $white = imagecolorallocate($image, 255, 255, 255);
        $bg_color = imagecolorallocate($image, 248, 249, 250); // Light gray/blue
        $text_color = imagecolorallocate($image, 31, 59, 179); // Primary blue
        $noise_color = imagecolorallocate($image, 180, 180, 180);
        $line_color = imagecolorallocate($image, 219, 58, 232); // Purple line

        imagefilledrectangle($image, 0, 0, $width, $height, $bg_color);

        // Add Noise
        for ($i = 0; $i < 50; $i++) {
            imagesetpixel($image, rand(0, $width), rand(0, $height), $noise_color);
        }

        // Add Line (like in example)
        imageline($image, 10, rand(30, 45), $width - 10, rand(5, 20), $line_color);
        imageline($image, 0, rand(0, $height), $width, rand(0, $height), $noise_color);

        // Add Text
        $font_size = 5;
        $x = 20;
        $y = 15;
        imagestring($image, $font_size, $x, $y, $captcha_question, $text_color);

        // Capture Image
        ob_start();
        imagepng($image);
        $image_data = ob_get_clean();
        imagedestroy($image);

        return [
            'image' => 'data:image/png;base64,' . base64_encode($image_data)
        ];
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
            'captcha'  => ['required', 'numeric'],
        ]);

        // Validasi Captcha
        if ($request->captcha != session('captcha_answer')) {
            return response()->json([
                'status' => false,
                'message' => 'Jawaban captcha salah. Silakan coba lagi.'
            ]);
        }

        $username = $request->username;
        $password = $request->password;
        $allowedGroup = env('LDAP_ALLOWED_GROUP', 'Sarpras Monitoring');

        // 1. Bypass untuk admin lokal
        if ($username === 'adminarya') {
    $localUser = \App\Models\User::where('username', 'adminarya')->first();
    
    if ($localUser && \Illuminate\Support\Facades\Hash::check($password, $localUser->password)) {
        Auth::login($localUser, false);
        $request->session()->regenerate();
        
        $redirectUrl = match($localUser->role_id) {
            1 => route('admin.dashboard.index'),
            default => route('admin.viewer.index'),
        };
        
        return response()->json([  // <-- return di DALAM if
            'status' => true,
            'message' => 'Success',
            'url' => $redirectUrl,
        ]);
    }
    
    // Jika password salah
    return response()->json([
        'status' => false,
        'message' => 'Password salah',
    ]);
}

        // 2. Cari user di Active Directory
        $ldapUser = LdapUser::where('samaccountname', $username)->first();

        if (!$ldapUser) {
            return response()->json([
                'status' => false,
                'message' => 'Username tidak ditemukan di Active Directory.'
            ]);
        }

        // 3. Cek keanggotaan grup via atribut memberOf
        $memberOf = $ldapUser->getAttribute('memberof') ?? [];
        $groupSearch = 'cn=' . strtolower($allowedGroup);
        $isMember = false;

        foreach ($memberOf as $groupDn) {
            if (str_contains(strtolower($groupDn), $groupSearch)) {
                $isMember = true;
                break;
            }
        }

        // Jika tidak ditemukan di memberOf langsung, coba metode rekursif
        if (!$isMember) {
            try {
                $groups = $ldapUser->groups()->recursive()->get();
                $isMember = $groups->contains(function ($group) use ($allowedGroup) {
                    $cn = $group->getFirstAttribute('cn');
                    return $cn && strtolower($cn) === strtolower($allowedGroup);
                });
            } catch (\Exception $e) {
                $isMember = false;
            }
        }

        if (!$isMember) {
            return response()->json([
                'status' => false,
                'message' => 'Akun Anda tidak memiliki akses.'
            ]);
        }

        // 4. Autentikasi password ke AD via Auth::attempt (LdapRecord auth driver)
        if (Auth::attempt(['samaccountname' => $username, 'password' => $password], false)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Assign default role (Viewer = 5) untuk user baru
            if (empty($user->role_id)) {
                $user->role_id = 5; 
                $user->status = 'active';
                $user->save();
            }

            if ($user->status == 'inactive'){
                Auth::logout();
                return response()->json([
                    'status' => false,
                    'message' => 'Akun anda tidak aktif! Silahkan hubungi Admin Aplikasi'
                ]);
            }

            if ($user->role_id == 1 || $user->role_id == 2){
                $url = route('admin.dashboard.index');
            } else if ($user->role_id == 3){
                $url = route('admin.permintaan.index');
            } else if ($user->role_id == 4){
                $url = route('admin.list-permintaan.index');
            } else if ($user->role_id == 5){
                $url = route('admin.viewer.index');
            } else {
                $url = route('admin.dashboard.index');
            }

            return response()->json([
                'status' => true,
                'message' => 'Success',
                'url' => $url,
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Password salah!'
        ]);
    }

    function Kirimfonnte($token, $data)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'target' => $data["target"],
                'message' => $data["message"],
            ),
            CURLOPT_HTTPHEADER => array(
                'Authorization: ' . $token
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response; //log response fonnte
    }
}
