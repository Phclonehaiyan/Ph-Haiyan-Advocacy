@extends('admin.layouts.app', ['pageTitle' => 'Change Password'])

@section('content')
    <form action="{{ route('admin.password.update') }}" method="POST" class="space-y-6" x-data="{ showCurrent: false, showNew: false, showConfirm: false }">
        @csrf
        @method('PUT')

        <section class="admin-panel">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-3xl">
                    <div class="admin-kicker">Security</div>
                    <h2 class="admin-heading">Change admin password</h2>
                    <p class="admin-copy">Replace the initial login with a private password your team can manage internally. Use a strong password and keep it in your internal credential manager.</p>
                </div>

                <a href="{{ route('admin.dashboard') }}" class="btn-secondary gap-2 px-5 py-2.5 text-xs tracking-[0.2em]">
                    <x-icon name="arrow-left" class="h-4 w-4" />
                    Back to dashboard
                </a>
            </div>
        </section>

        <section class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_320px]">
            <div class="admin-panel">
                <div class="grid gap-6">
                    <div class="rounded-[24px] border border-slate-200/80 bg-slate-50/70 p-5">
                        <div class="admin-kicker">Account security</div>
                        <p class="mt-3 text-sm leading-7 text-slate-600">Changing the password will immediately affect the main admin login. Use a password that is unique to PH Haiyan’s internal access.</p>
                    </div>

                    <div>
                        <label for="current_password" class="admin-label">Current password</label>
                        <div class="relative mt-2">
                            <input id="current_password" name="current_password" x-bind:type="showCurrent ? 'text' : 'password'" autocomplete="current-password" class="admin-input pr-24">
                            <button type="button" class="absolute inset-y-0 right-3 inline-flex items-center text-xs font-semibold uppercase tracking-[0.18em] text-pine-700 transition hover:text-pine-900" @click="showCurrent = !showCurrent">
                                <x-icon x-show="!showCurrent" name="eye" class="mr-1 h-4 w-4" x-cloak />
                                <x-icon x-show="showCurrent" name="eye-off" class="mr-1 h-4 w-4" x-cloak />
                                <span x-text="showCurrent ? 'Hide' : 'Show'"></span>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label for="password" class="admin-label">New password</label>
                        <div class="relative mt-2">
                            <input id="password" name="password" x-bind:type="showNew ? 'text' : 'password'" autocomplete="new-password" class="admin-input pr-24">
                            <button type="button" class="absolute inset-y-0 right-3 inline-flex items-center text-xs font-semibold uppercase tracking-[0.18em] text-pine-700 transition hover:text-pine-900" @click="showNew = !showNew">
                                <x-icon x-show="!showNew" name="eye" class="mr-1 h-4 w-4" x-cloak />
                                <x-icon x-show="showNew" name="eye-off" class="mr-1 h-4 w-4" x-cloak />
                                <span x-text="showNew ? 'Hide' : 'Show'"></span>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label for="password_confirmation" class="admin-label">Confirm new password</label>
                        <div class="relative mt-2">
                            <input id="password_confirmation" name="password_confirmation" x-bind:type="showConfirm ? 'text' : 'password'" autocomplete="new-password" class="admin-input pr-24">
                            <button type="button" class="absolute inset-y-0 right-3 inline-flex items-center text-xs font-semibold uppercase tracking-[0.18em] text-pine-700 transition hover:text-pine-900" @click="showConfirm = !showConfirm">
                                <x-icon x-show="!showConfirm" name="eye" class="mr-1 h-4 w-4" x-cloak />
                                <x-icon x-show="showConfirm" name="eye-off" class="mr-1 h-4 w-4" x-cloak />
                                <span x-text="showConfirm ? 'Hide' : 'Show'"></span>
                            </button>
                        </div>
                    </div>

                    <div class="flex flex-col gap-4 border-t border-slate-200 pt-6 sm:flex-row sm:items-center sm:justify-between">
                        <div class="text-sm text-slate-500">Use a secure password that only trusted administrators can access.</div>
                        <button type="submit" class="btn-primary gap-2 px-6 py-3 text-xs tracking-[0.2em]">
                            <x-icon name="shield" class="h-4 w-4" />
                            Update password
                        </button>
                    </div>
                </div>
            </div>

            <aside class="space-y-6">
                <div class="admin-panel-subtle">
                    <div class="admin-kicker">Password guidance</div>
                    <ul class="mt-4 space-y-3 text-sm leading-7 text-slate-600">
                        <li class="flex gap-3">
                            <x-icon name="shield" class="mt-1 h-4 w-4 shrink-0 text-pine-700" />
                            <span>Use at least 12 characters.</span>
                        </li>
                        <li class="flex gap-3">
                            <x-icon name="spark" class="mt-1 h-4 w-4 shrink-0 text-pine-700" />
                            <span>Mix uppercase, lowercase, numbers, and symbols.</span>
                        </li>
                        <li class="flex gap-3">
                            <x-icon name="close" class="mt-1 h-4 w-4 shrink-0 text-pine-700" />
                            <span>Avoid reusing the seeded default password.</span>
                        </li>
                        <li class="flex gap-3">
                            <x-icon name="mail" class="mt-1 h-4 w-4 shrink-0 text-pine-700" />
                            <span>Store the new password in your internal credential manager.</span>
                        </li>
                    </ul>
                </div>

                <div class="admin-panel-subtle">
                    <div class="admin-kicker">Recommended workflow</div>
                    <ol class="mt-4 space-y-3 text-sm leading-7 text-slate-600">
                        <li>1. Confirm the current login password before changing it.</li>
                        <li>2. Save the new password in your team’s secure credential vault.</li>
                        <li>3. Sign out and test the new password once the update is complete.</li>
                    </ol>
                </div>
            </aside>
        </section>
    </form>
@endsection
