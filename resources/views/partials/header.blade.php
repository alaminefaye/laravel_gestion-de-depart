@php
    $settings = \App\Models\SiteSetting::getSettings();
@endphp

<!-- Header -->
<header class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                @if($settings->logo_path)
                    <img src="{{ Storage::url($settings->logo_path) }}" alt="Logo" class="w-12 h-12 object-contain bg-white rounded-full">
                @else
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-bus text-2xl text-blue-600"></i>
                    </div>
                @endif
                <div>
                    <h1 class="text-2xl font-bold gradient-text">{{ $settings->site_name }}</h1>
                    <p class="text-gray-600">{{ $settings->slogan ?? "L'Excellence du Transport" }}</p>
                </div>
            </div>
            <nav class="hidden md:flex space-x-6">
                @if($settings->contact_phone)
                    <a href="tel:{{ $settings->contact_phone }}" class="text-gray-600 hover:text-blue-600 transition flex items-center space-x-2">
                        <i class="fas fa-phone-alt"></i>
                        <span>{{ $settings->contact_phone }}</span>
                    </a>
                @endif
                @if($settings->contact_phone_2)
                    <a href="tel:{{ $settings->contact_phone_2 }}" class="text-gray-600 hover:text-blue-600 transition flex items-center space-x-2">
                        <i class="fas fa-phone-alt"></i>
                        <span>{{ $settings->contact_phone_2 }}</span>
                    </a>
                @endif
                @if($settings->contact_email)
                    <a href="mailto:{{ $settings->contact_email }}" class="text-gray-600 hover:text-blue-600 transition flex items-center space-x-2">
                        <i class="fas fa-envelope"></i>
                        <span>{{ $settings->contact_email }}</span>
                    </a>
                @endif
            </nav>
        </div>
    </div>
</header>
