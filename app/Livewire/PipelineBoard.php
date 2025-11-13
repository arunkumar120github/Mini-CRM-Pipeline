<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Lead;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PipelineBoard extends Component
{
    public $statuses = ['lead', 'contacted', 'proposal_sent', 'won'];
    public $leads = [];

    public $showModal = false;
    public $editingLead;
    public $title;
    public $email;
    public $phone;
    public $status = 'lead';

    protected $listeners = ['updateStatus','deleteLead'];

    protected $rules = [
        'title' => 'required|string|max:255',
        'email' => 'required|email',
        'phone' => 'nullable|string|max:20',
    ];

    public function mount()
    {
        $this->loadLeads();
    }

    public function loadLeads()
    {
        $grouped = Lead::where('user_id', Auth::id())
            ->orderByDesc('updated_at')
            ->get()
            ->groupBy('status');

        $this->leads = $grouped->map(fn($group) =>
            $group->map(fn($lead) => [
                'id' => $lead->id,
                'title' => $lead->title,
                'email' => $lead->email,
                'phone' => $lead->phone,
                'status' => $lead->status,
                'created_at' => Carbon::parse($lead->created_at)->format('M d, Y - h:i A'),
                'updated_at' => Carbon::parse($lead->updated_at)->format('M d, Y - h:i A'),
            ])->values()->toArray()
        )->toArray();
    }

    public function showCreateModal()
    {
        $this->reset(['title', 'email', 'phone', 'editingLead', 'status']);
        $this->showModal = true;
    }

    public function saveLead()
    {
        $this->validate();

        if ($this->editingLead) {
            $lead = Lead::find($this->editingLead);
            $this->authorize('update', $lead);
            $lead->update($this->only(['title', 'email', 'phone', 'status']));
        } else {
            Auth::user()->leads()->create($this->only(['title', 'email', 'phone', 'status']));
        }

        $this->showModal = false;
        $this->reset(['title', 'email', 'phone', 'status', 'editingLead']);
        $this->loadLeads();
    }

    public function editLead($id)
    {
        $lead = Lead::findOrFail($id);
        $this->authorize('update', $lead);
        $this->editingLead = $lead->id;

        $this->fill([
            'title' => $lead->title,
            'email' => $lead->email,
            'phone' => $lead->phone,
            'status' => $lead->status,
        ]);

        $this->showModal = true;
    }


    public function deleteLead($id)
    {
        $lead = Lead::findOrFail($id);
        $this->authorize('delete', $lead);
        $lead->delete();

        $this->loadLeads();
        $this->dispatch('lead-deleted', id: $id);
    }


    public function updateStatus($id, $status)
    {
        $lead = Lead::find($id);

        if ($lead && $lead->user_id === Auth::id()) {
            $lead->update([
                'status' => $status,
                'updated_at' => now(),
            ]);

            $this->loadLeads();
            $this->dispatch('leadMoved', id: $id);
        }
    }

    public function render()
    {
        return view('livewire.pipeline-board')->layout('layouts.app');
    }
}
