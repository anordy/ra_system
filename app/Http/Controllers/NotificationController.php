<?php

namespace App\Http\Controllers;

use App\Notifications\DatabaseNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(){
        // auth()->user()->notify(new DatabaseNotification(
        //     $type = 'info', // info / success / warning / error
        //     $message = 'Test Notification',
        //     $messageLong = 'This is a longer message for the test notification '.rand(1, 99999), // optional
        //     $href = '/some-custom-url', // optional, e.g. backpack_url('/example')
        //     $hrefText = 'Go to custom URL' // optional
        // ));
        // dd('notification sent');
        return view('notifications');
    }
}
