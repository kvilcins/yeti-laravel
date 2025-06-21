<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\UpdateProfileRequest;
use App\Http\Requests\Auth\DeleteAvatarRequest;
use App\Models\Bid;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    public function __construct(
        protected DataController $dataController,
        protected BreadcrumbsController $breadcrumbsController
    ) {}

    public function show()
    {
        $user = Auth::user();
        $commonData = $this->dataController->getCommonData();
        $breadcrumbs = $this->breadcrumbsController->generateBreadcrumbs(request());

        $userBids = Bid::where('user_id', $user->id)->get();

        return view('pages.profile', array_merge($commonData, [
            'breadcrumbs' => $breadcrumbs,
            'user' => $user,
            'userBids' => $userBids,
        ]));
    }

    public function edit()
    {
        $user = Auth::user();
        $commonData = $this->dataController->getCommonData();
        $breadcrumbs = $this->breadcrumbsController->generateBreadcrumbs(request());

        return view('pages.profile_edit', array_merge($commonData, [
            'user' => $user,
            'breadcrumbs' => $breadcrumbs
        ]));
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = Auth::user();

        try {
            $validatedData = $request->validated();

            // Обновляем основные данные
            $user->name = $validatedData['name'];

            // Обновляем контактные данные если есть
            if (isset($validatedData['message'])) {
                $user->contact_details = $validatedData['message'];
            }

            // Проверяем изменение email
            $emailChanged = isset($validatedData['email']) && $validatedData['email'] !== $user->email;

            if ($emailChanged) {
                $this->sendEmailVerification($user, $validatedData['email']);
                $message = 'Profile updated! Please check your new email (' . $validatedData['email'] . ') to confirm the change.';
            } else {
                $message = 'Profile updated successfully!';
            }

            // Обновляем пароль если указан
            if (!empty($validatedData['password'])) {
                $user->password = Hash::make($validatedData['password']);
            }

            // Обрабатываем аватар
            $this->handleAvatarUpload($request, $user);

            $user->save();

            return $this->successResponse($request, $user, $message);

        } catch (ValidationException $e) {
            return $this->validationErrorResponse($request, $e);
        } catch (\Exception $e) {
            \Log::error('Profile update error: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'request_data' => $request->all(),
                'stack_trace' => $e->getTraceAsString()
            ]);

            return $this->errorResponse($request, 'An error occurred while updating the profile');
        }
    }

    private function sendEmailVerification(User $user, string $newEmail)
    {
        // Удаляем старые токены
        DB::table('email_verifications')->where('user_id', $user->id)->delete();

        // Создаем новый токен
        $token = Str::random(60);

        DB::table('email_verifications')->insert([
            'user_id' => $user->id,
            'new_email' => $newEmail,
            'token' => $token,
            'expires_at' => now()->addHours(24),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Создаем URL для подтверждения
        $verificationUrl = route('email.verify', ['token' => $token]);

        // Отправляем письмо
        Mail::to($newEmail)->send(new \App\Mail\EmailVerification($user, $newEmail, $verificationUrl));
    }

    public function deleteAvatar(DeleteAvatarRequest $request)
    {
        $user = Auth::user();

        try {
            $this->removeUserAvatar($user);
            $user->save();

            return $this->successResponse($request, $user, 'Avatar deleted successfully');

        } catch (\Exception $e) {
            return $this->errorResponse($request, 'Error deleting avatar');
        }
    }

    /**
     * Update user basic data
     */
    private function updateUserData(User $user, array $validatedData): void
    {
        $user->name = $validatedData['name'];

        if (!empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }
    }

    /**
     * Handle avatar upload
     */
    private function handleAvatarUpload(Request $request, User $user): void
    {
        if (!$request->hasFile('avatar')) {
            return;
        }

        if ($user->avatar) {
            Storage::delete('public/' . $user->avatar);
        }

        $user->avatar = $request->file('avatar')->store('avatars', 'public');
    }

    /**
     * Remove user avatar
     */
    private function removeUserAvatar(User $user): void
    {
        if ($user->avatar) {
            Storage::delete('public/' . $user->avatar);
            $user->avatar = null;
        }
    }

    /**
     * Return success response
     */
    private function successResponse(Request $request, User $user, string $message)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'avatar_url' => $user->avatar_url
            ]);
        }

        return redirect()->route('profile.')->with('success', $message);
    }

    /**
     * Return validation error response
     */
    private function validationErrorResponse(Request $request, ValidationException $e)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        }

        return redirect()->back()
            ->withErrors($e->errors())
            ->withInput();
    }

    /**
     * Return error response
     */
    private function errorResponse(Request $request, string $message)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message
            ], 500);
        }

        return redirect()->back()
            ->with('error', $message)
            ->withInput();
    }
}
