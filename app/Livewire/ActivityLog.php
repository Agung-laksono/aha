<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ActivityLog as LogModel;

class ActivityLog extends Component
{
    use WithPagination;

    public $search = '';

    protected $queryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $logs = LogModel::with(['user', 'team'])
            ->where('team_id', auth()->user()->current_team_id)
            ->when($this->search, function ($query) {
                $query->where('action', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(20);

        return view('livewire.activity-log', compact('logs'));
    }
}
