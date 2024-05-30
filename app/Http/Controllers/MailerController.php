<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailerController extends Controller
{

    // =============== [ Email ] ===================
    public function index(Request $request)
    {
        return view('email');
    }


    // ========== [ Compose Email ] ================
    public function store(Request $request)
    {
        $mail = new PHPMailer(true);     // Passing `true` enables exceptions

        try {

            // Email server settings
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = env('MAIL_HOST');
            $mail->SMTPAuth = true;
            $mail->Username = env('MAIL_USERNAME');
            $mail->Password = env('MAIL_PASSWORD');
            $mail->SMTPSecure = env('MAIL_ENCRYPTION');
            $mail->Port = env('MAIL_PORT');

            $mail->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $mail->addAddress($request->email);

            $mail->isHTML(true);

            $mail->Subject = $request->subject;
            $mail->Body = $request->body;

            if (!$mail->send()) {
                return back()->with('error', 'Email gagal terkirim')->withErrors($mail->ErrorInfo);
            } else {
                return back()->with('success', 'Email berhasil dikirim');
            }
        } catch (Exception $e) {
            return back()->with('error', 'Message could not be sent.');
        }
    }
}
