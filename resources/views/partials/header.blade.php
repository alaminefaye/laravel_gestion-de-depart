@php
    $settings = \App\Models\SiteSetting::getSettings();
@endphp

<!-- Header -->
<header class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                @if($settings->logo_path)
                    <img src="{{ Storage::url($settings->logo_path) }}" alt="Logo" class="w-20 h-20 object-contain bg-white rounded-full">
                @else
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-bus text-4xl text-green-600"></i>
                    </div>
                @endif
                <div>
                    <h1 class="text-2xl font-bold gradient-text">{{ $settings->site_name }}</h1>
                    <p class="text-gray-600">{{ $settings->slogan ?? "L'Excellence du Transport" }}</p>
                </div>
            </div>
            <nav class="hidden md:flex items-center space-x-6">
                <div class="flex items-center space-x-4">
                    @if($settings->contact_phone || $settings->contact_phone_2)
                        <div class="flex items-center space-x-2">
                            @if($settings->contact_phone)
                                <a href="tel:{{ $settings->contact_phone }}" 
                                   class="price-animation text-white transition-all duration-300 flex items-center space-x-2 rounded-lg px-3 py-1.5">
                                    <i class="fas fa-phone-alt text-white"></i>
                                    <span class="font-medium">{{ $settings->contact_phone }}</span>
                                </a>
                            @endif
                            @if($settings->contact_phone && $settings->contact_phone_2)
                                <span class="text-gray-400">/</span>
                            @endif
                            @if($settings->contact_phone_2)
                                <a href="tel:{{ $settings->contact_phone_2 }}" 
                                   class="price-animation text-white transition-all duration-300 flex items-center space-x-2 rounded-lg px-3 py-1.5">
                                    <i class="fas fa-phone-alt text-white"></i>
                                    <span class="font-medium">{{ $settings->contact_phone_2 }}</span>
                                </a>
                            @endif
                        </div>
                    @endif
                    @if($settings->contact_email)
                        <a href="mailto:{{ $settings->contact_email }}" 
                           class="price-animation text-white transition-all duration-300 flex items-center space-x-2 rounded-lg px-3 py-1.5">
                            <i class="fas fa-envelope text-white"></i>
                            <span class="font-medium">{{ $settings->contact_email }}</span>
                        </a>
                    @endif
                </div>
            </nav>
        </div>
    </div>
</header>
