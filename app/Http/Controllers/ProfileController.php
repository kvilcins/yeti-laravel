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

        $userLots = $user->lots()->with('category')->orderBy('created_at', 'desc')->get();

        $userBids = $user->bids()->with(['lot', 'lot.category'])->orderBy('bid_time', 'desc')->get();

        $wonLots = $user->wonLots()->with('category')->get();

        return view('pages.profile', array_merge($commonData, [
            'breadcrumbs' => $breadcrumbs,
            'user' => $user,
            'userLots' => $userLots,
            'userBids' => $userBids,
            'wonLots' => $wonLots,
        ]));
    }

    public function edit()
    {
        $user = Auth::user();
        $commonData = $this->dataController->getCommonData();
        $breadcrumbs = $this->breadcrumbsController->generateBreadcrumbs(request());

        return view('pages.profile', array_merge($commonData, [
            'user' => $user,
            'breadcrumbs' => $breadcrumbs
        ]));
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = Auth::user();

        try {
            $validatedData = $request->validated();

            $user->name = $validatedData['name'];

            if (isset($validatedData['message'])) {
                $user->contact_details = $validatedData['message'];
            }

            if (!empty($validatedData['password'])) {
                $user->password = Hash::make($validatedData['password']);
            }

            if ($request->has('delete_avatar') && $request->delete_avatar == '1') {
                $this->removeUserAvatar($user);
            } else {
                $this->handleAvatarUpload($request, $user);
            }

            $user->save();

            return $this->successResponse($request, $user, 'Profile updated successfully!');

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

        if ($user->avatar && !str_starts_with($user->avatar, 'img/')) {
            Storage::delete('public/' . $user->avatar);
        }

        $user->avatar = $request->file('avatar')->store('avatars', 'public');
    }

    /**
     * Remove user avatar
     */
    private function removeUserAvatar(User $user): void
    {
        if ($user->avatar && !str_starts_with($user->avatar, 'img/')) {
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
