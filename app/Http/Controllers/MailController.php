<?php

namespace App\Http\Controllers;

use App\Mail\ResetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function send()
    {
    	$mail = Mail::to('dickidarmawansaputra@gmail.com')->send(new ResetPassword());
    	return $mail;
    }
}
