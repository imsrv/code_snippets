<?php

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Mail;
use DB;

class RegisteredUserController extends Controller
{
  public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
			
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'refer_id' => $request->refer_id,
            'password' => Hash::make($request->password),
        ]);
		$uname = 'XYZ55486'.$user->id.rand(0,9);  // Generate Unique Username for the new user with a XYZ prefix
		
		DB::table('users')->where("id", $user->id)->update(['username' => $uname]);
		
		// $updateUser->username = 'XYZ55486'.Auth::user()->id;
		// 'username' => 'XYZ5548'.$userid->id, 
		
    
		 $this->sendMail($request->email, "Account Created Successfully", view("welcome_email",["name"=>$request->name, "email"=>$request->email, "username"=>$uname, "password"=> $request->password]));

        event(new Registered($user));

        Auth::login($user); // Auto login user after signup
		 if (Auth()->user()->is_admin == '1') {

            return redirect('dashboard');
     
     } else {
			 
		return view('auth.account_details',['userid'=>$uname,'password'=>$request->password,'fullname'=>$request->name,'email'=>$request->email]); // show account details to user
        // or you can do - return redirect('userdashboard');  
     }
    }
//   end function store
  
  function sendMail($to, $subject, $txt){
        $txt = wordwrap($txt,70);
        $txt = str_replace("\n.", "\n..", $txt);
        $fromName = "Jon Doe";  // Mail Sent from Name / Company Name 
        $from = "info@example.com"; // Mail sent from this mail

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
        $headers .= "X-Priority: 3\r\n";
        $headers .= 'From: '.$fromName.'<'.$from.'>' . "\r\n" .
        "Reply-To: $fromName<$from>" . "\r\n" .
        "X-Mailer: PHP/" . phpversion();

        mail($to,$subject,$txt,$headers);
    }
  
  
}
