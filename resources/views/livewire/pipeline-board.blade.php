<div class="py-12" x-data="{ recentlyMoved: null }" x-on:leadMoved.window="recentlyMoved = $event.detail.id">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

            <div
                wire:loading.delay.longer
                class="fixed inset-0 flex flex-col items-center justify-center bg-gray-900 bg-opacity-60 z-50 backdrop-blur-sm"
            >
                <div class="flex flex-col items-center space-y-3">
                    <div class="animate-spin rounded-full h-14 w-14 border-t-4 border-b-4 border-blue-500"></div>
                    <p class="text-white text-lg font-semibold tracking-wide">Please wait...</p>
                </div>
            </div>
            {{-- Header --}}
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold">CRM Pipeline</h1>
                <button wire:click="showCreateModal"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    + New Lead
                </button>
            </div>

            <div
                x-data="{ draggedId: null, dropTarget: null, recentlyMovedId: null }"
                x-on:leadMoved.window="recentlyMovedId = $event.detail.id; setTimeout(() => recentlyMovedId = null, 1500)"
                class="grid grid-cols-1 md:grid-cols-4 gap-4 overflow-x-auto"
            >
                @foreach ($statuses as $status)
                    <div
                        class="bg-gray-100 rounded p-2 min-h-[300px] transition-all duration-200"
                        :class="{ 'ring-2 ring-blue-400 bg-blue-50': dropTarget === '{{ $status }}' }"
                        x-on:dragover.prevent="dropTarget = '{{ $status }}'"
                        x-on:dragleave="dropTarget = null"
                        x-on:drop="
                            const id = event.dataTransfer.getData('id');
                            Livewire.dispatch('updateStatus', { id: id, status: '{{ $status }}' });
                            dropTarget = null;
                        "
                    >
                        <h2 class="font-semibold capitalize mb-2">{{ str_replace('_', ' ', $status) }}</h2>

                        <div class="space-y-2 overflow-y-auto h-72 scrollbar-data scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-100">
                            @foreach (($leads[$status] ?? []) as $lead)
                                <div
                                    draggable="true"
                                    x-on:dragstart="event.dataTransfer.setData('id', '{{ $lead['id'] }}'); draggedId = '{{ $lead['id'] }}'"
                                    x-on:dragend="draggedId = null"
                                    class="bg-white shadow rounded p-2 border cursor-move transition hover:shadow-md"
                                    :class="{
                                        'ring-2 ring-green-400': draggedId == '{{ $lead['id'] }}',
                                        'bg-green-50 ring-2 ring-green-500': recentlyMovedId == '{{ $lead['id'] }}'
                                    }"
                                    x-transition
                                >
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <h3 class="font-medium">{{ $lead['title'] }}</h3>
                                            <p class="text-xs text-gray-500">{{ $lead['email'] }}</p>

                                        </div>
                                        <div class="space-x-1">
                                            <button wire:click="editLead('{{ $lead['id'] }}')" class="text-blue-500">✎</button>
                                            <button
                                                type="button"
                                                class="text-red-500"
                                                x-on:click="
                                                    Swal.fire({
                                                        title: 'Are you sure?',
                                                        text: 'This lead will be permanently deleted.',
                                                        icon: 'warning',
                                                        showCancelButton: true,
                                                        confirmButtonColor: '#3085d6',
                                                        cancelButtonColor: '#d33',
                                                        confirmButtonText: 'Yes, delete it!'
                                                    }).then((result) => {
                                                        if (result.isConfirmed) {
                                                            Livewire.dispatch('deleteLead', { id: '{{ $lead['id'] }}' });
                                                        }
                                                    });
                                                "
                                            >🗑️</button>
                                        </div>
                                    </div>

                                    <div x-data="{ open: false }" class="mt-1">
                                        <div x-show="open" x-transition>
                                            <p class="text-sm text-gray-500 mt-2">📞 {{ $lead['phone'] }}</p>
                                        </div>
                                        <p  x-show="open" x-transition class="text-[11px] text-gray-500 mt-1">
                                            <span class="block">Created: {{ $lead['created_at'] }}</span>
                                            <span>Updated: {{ $lead['updated_at'] }}</span>
                                        </p>
                                        <button @click="open = !open" class="text-xs text-gray-500">
                                            <span x-text="open ? 'Hide' : 'Details'"></span>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Modal --}}
            <x-pipline.modal wire:model="showModal">
                <div class="p-4">
                    <h2 class="text-lg font-bold mb-4">{{ $editingLead ? 'Edit Lead' : 'Add New Lead' }}</h2>
                    <form wire:submit.prevent="saveLead" class="space-y-3">
                        <div>
                            <label class="block text-sm">Title</label>
                            <input wire:model="title" class="w-full border rounded p-2" />
                            @error('title') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm">Email</label>
                            <input wire:model="email" class="w-full border rounded p-2" />
                            @error('email') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm">Phone</label>
                            <input wire:model="phone" type="number" maxlength="10" class="w-full border rounded p-2" />
                        </div>
                        <div class="flex justify-end">
                            <button type="button" wire:click="$set('showModal', false)" class="px-3 py-2 border rounded mr-2">Cancel</button>
                            <button class="px-3 py-2 bg-blue-600 text-white rounded">Save</button>
                        </div>
                    </form>
                </div>
            </x-pipline.modal>

        </div>
    </div>

    {{-- ccs for scrollbar only this page this reason not add extrnal --}}
    <style>
        .scrollbar-data{padding: 0px 6px 0px 0px;}
        .scrollbar-data::-webkit-scrollbar{-webkit-appearance:none}
        .scrollbar-data::-webkit-scrollbar:vertical{width:5px}
        .scrollbar-data::-webkit-scrollbar:horizontal{height:5px}
        .scrollbar-data::-webkit-scrollbar-thumb{border-radius:8px; border:1px solid rgba(255,255,255,.25);background-color:#696060}
    </style>
</div>


