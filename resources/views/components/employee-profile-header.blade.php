@php
    $employee = $getRecord(); // menjalankan closure untuk mendapatkan model
    $statusClass = [
        'active' => 'shadow-md border border-[#65a30d]',
        'inactive' => 'shadow-md border border-slate-800',
        'pending' => 'shadow-md border border-yellow-800',
        'blocked' => 'shadow-md border border-red-800',
    ][$employee->status->value] ?? 'text-gray-600 shadow-gray-200';
@endphp

<div>
    <div class="flex items-center space-x-4">
        {{-- Foto Profil --}}
        <img src="{{ $employee->photo ? Storage::url($employee->photo) : asset('assets/placeholder.png') }}"
             alt="{{ $employee->name ?? 'Unknown' }}"
             class="object-cover w-24 h-24 border rounded-md shadow-sm" />
    
        {{-- Informasi Karyawan --}}
        <div class="flex flex-col">
            <div class="flex items-center space-x-6">
                <div class="flex flex-col">
                    {{-- Nama , Email dan Whatsapp --}}
                    <h2 class="font-mono text-2xl font-semibold text-bg-gray-800">{{ $employee->name ?? 'Unknown' }}
    
                    </h2>
                    {{-- open direct Whatsapp app --}}
                    <a href="https://wa.me/{{ $employee->whatsapp }}" target="_blank" rel="noopener noreferrer"
                       class="flex items-center space-x-2 font-mono text-sm hover:underline">
                        {{-- icon whatsapp --}}
                       <x-lucide-phone class="w-3 h-3 text-[#65a30d]"/>
                        <span>{{ $employee->whatsapp ?? 'Unknown' }}</span>
                    </a>
                    {{-- link Email target blank --}}
                    <a href="mailto:{{ $employee->email }}" target="_blank" rel="noopener noreferrer"
                       class="flex items-center space-x-2 font-mono text-sm hover:underline">
                        {{-- icon email --}}
                        <x-lucide-mail class="w-3 h-3 text-pink-500" />
                        <span>{{ $employee->email ?? 'Unknown' }}</span>
                    </a>
                </div>
    
            </div>
    
            <div class="flex items-center mt-2 space-x-2 text-[10px]">
                {{-- Badge Status --}}
                <span class="inline-flex items-center px-2 py-0.5 rounded-md shadow {{ $statusClass }}">
                    <x-dynamic-component :component="$employee->status->getIcon()" class="w-3 h-3 mr-1" />
                    {{ $employee->status->getLabel() }}
                </span>
                {{-- Jabatan --}}
                {{-- @if ($employee->position)
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md shadow-md border border-gray-300">
                        <span class="capitalize">{{ $employee->position }}</span>
                    </span>
                @endif --}}
                {{-- Gender --}}
                @if ($employee->gender)
                    <div class="flex items-center">
                        <span class="inline-flex space-x-1 items-center px-2 py-0.5 rounded-md shadow-md border border-sky-500">
                            <span class="capitalize">{{ $employee->gender }}</span>
                            {{-- <x-lucide-facebook class="w-5 h-5 text-blue-600" /> --}}
                        </span>
                    </div>
                @endif
    
                {{-- Umur --}}
                @if ($employee->date_of_birth)
                    <div class="flex items-center">
                        <span class="inline-flex space-x-1 items-center px-2 py-0.5 rounded-md shadow-md border border-amber-500">
                            <span>{{ \Carbon\Carbon::parse($employee->date_of_birth)->age }} yrs</span>
                        </span>
                    </div>
                @endif
            </div>
        </div>
    </div>
    {{-- divider --}}
    <div class="flex mt-4 border border-gray-300"></div>
</div>
