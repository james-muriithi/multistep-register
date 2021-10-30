<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class RegisterController extends Controller
{
    public function index()
    {
        return view("register");
    }

    public function create(StoreUserRequest $request)
    {
        $uploadsFolder = 'uploads';
        $uploadsFolderLocation = public_path('storage') . '/' . $uploadsFolder;
        if (!file_exists($uploadsFolderLocation)) {
            mkdir($uploadsFolderLocation);
        }

        if ($request->hasFile("resume")) {
            $fileName = $request->file("resume")->getClientOriginalName() . '_' . time() . '.' . $request->file("resume")->getClientOriginalExtension();
            $request->file("resume")->move($uploadsFolderLocation, $fileName);
            $request->merge(['resume_path' => $uploadsFolder . '/' . $fileName]);
        }

        if ($request->hasFile("passport")) {
            $fileName = $request->file("resume")->getClientOriginalName() . '_' . time() . '.' . $request->file("passport")->getClientOriginalExtension();
            $request->file("passport")->move($uploadsFolderLocation, $fileName);
            $request->merge(['passport_path' => $uploadsFolder . '/'  . $fileName]);
        }

        if (!$request->input('password')) {
            $request->merge(['password' => 'password']);
        }

        $userData = array_merge(
            $request->except(['passport', 'resume', 'passport_path', 'resume_path']),
            ['resume' => $request->input("resume_path"), 'passport' => $request->input("passport_path")]
        );

        $user = User::create($userData);

        return response()
            ->json($user)
            ->setStatusCode(HttpFoundationResponse::HTTP_CREATED);
    }
}
