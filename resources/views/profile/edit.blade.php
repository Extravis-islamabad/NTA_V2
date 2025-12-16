@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Profile Settings</h1>
        <p class="text-gray-500 mt-1">Manage your account settings and password</p>
    </div>

    <div class="space-y-6">
        <!-- Update Profile Information -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900">Profile Information</h2>
                <p class="text-sm text-gray-500 mt-1">Update your account's profile information and email address.</p>
            </div>

            <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                @csrf
                @method('patch')

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#5548F5] focus:ring-[#5548F5]">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#5548F5] focus:ring-[#5548F5]">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit" class="btn-monetx px-4 py-2 rounded-lg text-white font-medium">
                        Save Changes
                    </button>
                    @if (session('status') === 'profile-updated')
                        <span class="text-sm text-green-600">Saved successfully!</span>
                    @endif
                </div>
            </form>
        </div>

        <!-- Update Password -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900">Update Password</h2>
                <p class="text-sm text-gray-500 mt-1">Ensure your account is using a strong password to stay secure.</p>
            </div>

            <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                @csrf
                @method('put')

                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                    <input type="password" name="current_password" id="current_password" required
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#5548F5] focus:ring-[#5548F5]">
                    @error('current_password', 'updatePassword')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                    <input type="password" name="password" id="password" required
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#5548F5] focus:ring-[#5548F5]">
                    @error('password', 'updatePassword')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#5548F5] focus:ring-[#5548F5]">
                    @error('password_confirmation', 'updatePassword')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit" class="btn-monetx px-4 py-2 rounded-lg text-white font-medium">
                        Update Password
                    </button>
                    @if (session('status') === 'password-updated')
                        <span class="text-sm text-green-600">Password updated!</span>
                    @endif
                </div>
            </form>
        </div>

        <!-- Delete Account -->
        <div class="bg-white shadow rounded-lg p-6 border border-red-200">
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-red-600">Delete Account</h2>
                <p class="text-sm text-gray-500 mt-1">Once your account is deleted, all of its resources and data will be permanently deleted.</p>
            </div>

            <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
                @csrf
                @method('delete')

                <div class="mb-4">
                    <label for="delete_password" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input type="password" name="password" id="delete_password" required
                        class="mt-1 block w-full max-w-md rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                    @error('password', 'userDeletion')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition">
                    Delete Account
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
