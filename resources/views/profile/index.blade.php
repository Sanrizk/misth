@extends(Auth::user()->role->name === 'customer' ? 'layouts.store' : 'layouts.app')
@section('title', 'Profil Saya')

@section('content')
<div class="{{ Auth::user()->role->name === 'customer' ? 'max-w-4xl mx-auto' : 'max-w-5xl' }} space-y-6">

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Profil Saya</h2>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center gap-2">
            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        
        <div class="p-6 md:p-8 flex flex-col md:flex-row gap-8 items-start">
            
            {{-- Avatar Section --}}
            <div class="flex flex-col items-start space-y-4 w-full md:w-1/4">
                <div class="w-32 h-32 rounded-full bg-green-600 text-white flex items-center justify-center text-5xl font-bold shadow-lg">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="text-left">
                    <p class="font-semibold text-gray-800">{{ $user->name }}</p>
                    <p class="text-sm text-gray-500 capitalize">{{ $user->role->name ?? 'User' }}</p>
                </div>
            </div>

            {{-- Forms Section --}}
            <div class="flex-1 w-full space-y-8">
                
                {{-- Update Info Form --}}
                <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-4">Informasi Akun</h3>
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4">
                        <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-4">Ubah Password <span class="text-xs text-gray-400 font-normal">(opsional)</span></h3>
                    </div>

                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Password Saat Ini</label>
                        <input type="password" name="current_password" id="current_password"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 @error('current_password') border-red-500 @enderror"
                               placeholder="Kosongkan jika tidak ingin mengubah password">
                        @error('current_password')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                            <input type="password" name="new_password" id="new_password"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 @error('new_password') border-red-500 @enderror"
                                   placeholder="Minimal 8 karakter">
                            @error('new_password')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                        </div>
                    </div>

                    <div class="pt-6 flex justify-end">
                        <button type="submit"
                                class="bg-green-600 hover:bg-green-700 text-white font-medium px-6 py-2.5 rounded-lg transition shadow-sm">
                            Simpan Perubahan
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
@endsection

