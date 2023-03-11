<?php 
  
// namespace App\Http\Controllers\Auth;  uncomment this if this controller is in "Controllers/Auth/" Folder
  
use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
use DB; 
use Carbon\Carbon; 
use App\Models\User; 
use Mail; 
use Hash;
use Illuminate\Support\Str;


  
class ForgotPasswordController extends Controller
{
      public function showForgetPasswordForm()
      {
         return view('auth.forgetPassword');  // This form view will include basic forgot password form where user will input his username only and will request password reset link on mail
      }
  
       public function submitForgetPasswordForm(Request $request) // On submit request password reset link with unique string parameter
      {
		  $userInfo = DB::table('users')->where('username',$request->username)->orderBy('id', 'desc');
		  
		  if($userInfo->count() > 0){
			  $token = Str::random(64);
			   DB::table('password_resets')->insert([
				  'email' => $userInfo->first()->username, 
				  'token' => $token, 
				  'created_at' => Carbon::now()
				]);
			  
			  $this->sendMail($userInfo->first()->email, "Reset Your Password", view("email.forgetPassword",['token' => $token, 'name'=>$request->name, 'email'=>$userInfo->first()->email, 'username'=>$userInfo->first()->username]));
          return back()->with('message', 'We have e-mailed your password reset link!');
			  
		  }
      }
	
   
      public function showResetPasswordForm($token) { 
         return view('auth.forgetPasswordLink', ['token' => $token]);
      }
  
      public function submitResetPasswordForm(Request $request)
      {
          $request->validate([
              'email' => 'required|email|exists:users',
              'password' => 'required|string|min:6|confirmed',
              'password_confirmation' => 'required'
          ]);
  
          $updatePassword = DB::table('password_resets')
                              ->where([
                                'email' => $request->email, 
                                'token' => $request->token
                              ])
                              ->first();
  
          if(!$updatePassword){
              return back()->withInput()->with('error', 'Invalid token!');
          }
  
          $user = User::where('email', $request->email)
                      ->update(['password' => Hash::make($request->password)]);
 
          DB::table('password_resets')->where(['email'=> $request->email])->delete();
  
          return redirect('/login')->with('message', 'Your password has been changed!');
      }
	
	function sendMail($to, $subject, $txt){
        $txt = wordwrap($txt,70);
        $txt = str_replace("\n.", "\n..", $txt);
        $fromName = "Jon Doe";
        $from = "info@example.com";

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
        $headers .= "X-Priority: 3\r\n";
        $headers .= 'From: '.$fromName.'<'.$from.'>' . "\r\n" .
        "Reply-To: $fromName<$from>" . "\r\n" .
        "X-Mailer: PHP/" . phpversion();

        mail($to,$subject,$txt,$headers);
    }
	
}
