<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Illuminate\Validation\Rules\Password;
use Laravel\Jetstream\Jetstream;

class UserManagement extends Component
{
    // === Create Modal State ===
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $role = 'member';
    public $showCreateModal = false;

    // === Edit Modal State ===
    public $editUserId;
    public $editName;
    public $editEmail;
    public $editRole = 'member';
    public $editPassword;
    public $editPasswordConfirmation;
    public $showEditModal = false;

    protected function createRules(): array
    {
        $roleRules = implode(',', array_keys(Jetstream::$roles));
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', new Password(8), 'confirmed'],
            'role' => ['required', 'string', 'in:' . $roleRules],
        ];
    }

    protected function editRules(): array
    {
        $roleRules = implode(',', array_keys(Jetstream::$roles));
        return [
            'editName' => ['required', 'string', 'max:255'],
            'editEmail' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $this->editUserId],
            'editRole' => ['required', 'string', 'in:' . $roleRules],
            'editPassword' => ['nullable', 'string', new Password(8), 'confirmed'],
        ];
    }

    public function createUser()
    {
        $currentUser = auth()->user();
        $team = $currentUser->currentTeam;

        if (!$team || !$currentUser->hasTeamRole($team, 'admin')) {
            abort(403, 'Akses ditolak.');
        }

        $this->validate($this->createRules());

        $newUser = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        $team->users()->attach($newUser, ['role' => $this->role]);
        $newUser->switchTeam($team);

        $this->reset(['name', 'email', 'password', 'password_confirmation', 'role']);
        $this->showCreateModal = false;

        session()->flash('success', 'Pengguna ' . $newUser->name . ' berhasil ditambahkan ke tim.');
        $this->dispatch('user-created');
    }

    public function openEditModal(int $userId)
    {
        $currentUser = auth()->user();
        if (!$currentUser->hasTeamRole($currentUser->currentTeam, 'admin')) {
            abort(403, 'Akses ditolak.');
        }

        $user = User::findOrFail($userId);
        $this->editUserId = $user->id;
        $this->editName = $user->name;
        $this->editEmail = $user->email;

        // Fetch the user's current role in this team
        $membership = $currentUser->currentTeam->users()->where('user_id', $userId)->first();
        $this->editRole = $membership?->membership?->role ?? 'member';

        $this->editPassword = '';
        $this->editPasswordConfirmation = '';
        $this->showEditModal = true;
    }

    public function updateUser()
    {
        $currentUser = auth()->user();
        $team = $currentUser->currentTeam;

        if (!$team || !$currentUser->hasTeamRole($team, 'admin')) {
            abort(403, 'Akses ditolak.');
        }

        $this->validate($this->editRules());

        $user = User::findOrFail($this->editUserId);
        $user->name = $this->editName;
        $user->email = $this->editEmail;

        if (!empty($this->editPassword)) {
            $user->password = Hash::make($this->editPassword);
        }

        $user->save();

        // Update the role in the team_user pivot table
        $team->users()->updateExistingPivot($user->id, ['role' => $this->editRole]);

        $this->reset(['editUserId', 'editName', 'editEmail', 'editRole', 'editPassword', 'editPasswordConfirmation']);
        $this->showEditModal = false;

        session()->flash('success', 'Data pengguna ' . $user->name . ' berhasil diperbarui.');
    }

    public function render()
    {
        $currentUser = auth()->user();

        if (!$currentUser || !$currentUser->currentTeam || !$currentUser->hasTeamRole($currentUser->currentTeam, 'admin')) {
            return abort(403, 'Akses ditolak. Halaman ini khusus Administrator.');
        }

        $teamUsers = $currentUser->currentTeam->users()->orderBy('name')->get();
        $owner = $currentUser->currentTeam->owner;
        if (!$teamUsers->contains('id', $owner->id)) {
            $teamUsers->prepend($owner);
        }

        $availableRoles = [];
        foreach (Jetstream::$roles as $roleKey => $roleData) {
            $availableRoles[$roleKey] = $roleData->name;
        }

        return view('livewire.user-management', [
            'users' => $teamUsers,
            'roles' => $availableRoles,
            'ownerId' => $owner->id,
        ])->layout('layouts.app', ['header' => '<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Manajemen Pengguna Tim</h2>']);
    }
}
